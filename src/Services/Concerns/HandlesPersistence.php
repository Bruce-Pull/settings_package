<?php

namespace BrucePull\SettingsPackage\Services\Concerns;

use BrucePull\SettingsPackage\Models\Setting;
use BrucePull\SettingsPackage\Enums\SettingLevel;
use Illuminate\Support\Collection;
use BrucePull\SettingsPackage\Services\Helpers\LevelResolver;

/**
 * --------------------------------------------------------------------------
 * HandlesPersistence Trait
 * --------------------------------------------------------------------------
 *
 * Provides core functionality for managing settings persistence including:
 * - Creating/updating settings
 * - Bulk operations
 * - Setting removal
 * - Reset functionality
 */
trait HandlesPersistence
{
    /**
     * Create or update a setting
     *
     * @param string $key Setting identifier
     * @param mixed $value Setting value
     * @param SettingLevel $level Scope level
     * @param int|null $modelId Related model ID
     * @param bool $skipValidation Bypass validation when true
     * @return Setting The saved setting model
     *
     * @throws \InvalidArgumentException If level isn't allowed or validation fails
     * @throws \RuntimeException If persistence fails
     *
     * @note Automatically clears cache after saving
     */
    public function setSetting(
        string $key,
        $value,
        SettingLevel $level,
        ?int $modelId = null,
        bool $skipValidation = false
    ): Setting {
        // Check if setting is allowed at this level
        if (!$this->canSetSettingAtThisLevel($key, $level)) {
            throw new \InvalidArgumentException(
                "The '{$key}' setting is not available in the '{$level->value}' level"
            );
        }

        // Validate value unless explicitly skipped
        if (!$skipValidation && !$this->validateSettingValue($key, $value)) {
            throw new \InvalidArgumentException(
                "The value for '{$key}' is not valid according to its validation rules"
            );
        }

        $entityId = LevelResolver::getEntityIdForLevelFromModel($level, $modelId);
        $settingConfig = $this->availableSettings[$key];

        // Convert value to storable format using SettingType enum class
        $serializedValue = $settingConfig['type']->serialize($value);

        // Find existing or create new setting
        $setting = Setting::firstOrNew([
            'key' => $key,
            'level' => $level,
            'model_id' => $entityId
        ]);

        // Update setting attributes
        $setting->type = $settingConfig['type'];
        $setting->value = $serializedValue;
        $setting->is_resettable = $settingConfig['is_resettable'] ?? false;

        $setting->save();

        // Clear cache
        $this->clearSettingsCache();

        return $setting;
    }

    /**
     * Save multiple settings in a batch operation using setSetting() method
     *
     * @param array $settings Array of setting definitions [
     *     ['key' => string, 'value' => mixed, 'level' => string, 'model_id' => ?int]
     * ]
     * @return Collection Collection of saved Setting models
     *
     * @throws \InvalidArgumentException If any setting fails validation
     */
    public function setSettings(array $settings): Collection
    {
        $proccessedSettings = collect([]);

        foreach ($settings as $setting) {
            // use setSetting() method to create each setting in batch
            $setting = $this->setSetting(
                $setting['key'],
                $setting['value'],
                SettingLevel::from($setting['level']),
                $setting['model_id']
            );

            $proccessedSettings->push($setting);
        }

        return $proccessedSettings;
    }

    /**
     * Remove a specific setting
     *
     * @param string $key Setting key
     * @param SettingLevel $level Scope level
     * @param int|null $modelId Related model ID
     * @return bool True if setting was deleted
     *
     * @note Clears cache after successful deletion
     */
    public function removeSetting(
        string $key,
        SettingLevel $level,
        ?int $modelId = null
    ): bool {
        $entityId = LevelResolver::getEntityIdForLevelFromModel($level, $modelId);

        $deleted = Setting::forKey($key)
            ->forLevel($level, $entityId)
            ->delete();

        if ($deleted) {
            $this->clearSettingsCache();
        }

        return $deleted > 0;
    }

    /**
     * Reset all resettable settings to their defaults
     *
     * @return void
     *
     * @note Only affects settings marked as is_resettable
     * @note Clears cache after reset
     */
    public function resetSettings()
    {
        Setting::query()
            ->where('is_resettable', true)->delete();

        $this->clearSettingsCache();
    }
}
