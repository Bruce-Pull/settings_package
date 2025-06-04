<?php

namespace BrucePull\SettingsPackage\Services\Concerns;

use BrucePull\SettingsPackage\Models\Setting;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use BrucePull\SettingsPackage\Services\Helpers\LevelResolver;

/**
 * --------------------------------------------------------------------------
 * HandlesRetrieval Trait
 * --------------------------------------------------------------------------
 *
 * Provides core functionality for retrieving settings with:
 * - Multi-level inheritance (system > facility > user)
 * - Type casting
 * - Caching
 * - Metadata support
 */
trait HandlesRetrieval
{
    /**
     * Get a setting value with optional metadata
     *
     * @param string $key Setting key identifier
     * @param int|null $facilityId Facility scope ID
     * @param int|null $userId User scope ID
     * @param bool $withMetadata Return full metadata when true
     * @return mixed|null Setting value or full metadata array
     *
     * @note Follows the priority chain: User > Facility > System > Default
     */
    public function getSettingValue(string $key, ?int $facilityId = null, ?int $userId = null, bool $withMetadata = false)
    {
        // Return null if setting isn't defined in configuration
        if (!isset($this->availableSettings[$key])) {
            return null;
        }

        $settingConfig = $this->availableSettings[$key];

        // Get levels to check in priority order
        $availableLevels = $settingConfig['levels'];
        $levelsToCheck = LevelResolver::getLevelsInPriorityOrder($availableLevels);

        // Check each level in priority order
        foreach ($levelsToCheck as $level) {
            $entityId = LevelResolver::getEntityIdForLevel($level, $facilityId, $userId);

            $setting = Setting::forKey($key)
                ->forLevel($level, $entityId)
                ->first();

            if ($setting) {
                $castedValue = $settingConfig['type']->cast($setting->value);

                $castedValue;

                return $withMetadata
                    ? [
                        'key' => $key,
                        'value' => $castedValue,
                        'slug' => Str::slug($settingConfig['name'] ?? $key),
                        'effective_level' => $level,
                        'model_id' => $entityId,
                        'source' => 'database',
                        'is_default' => false,
                        'config' => $settingConfig
                    ] : $castedValue;
            }
        }

        // Return default value if no specific setting found
        return $withMetadata
            ? [
                'key' => $key,
                'value' => $settingConfig['default_value'],
                'slug' => Str::slug($settingConfig['name'] ?? $key),
                'effective_level' => null,
                'model_id' => null,
                'source' => 'config',
                'is_default' => true,
                'config' => $settingConfig
            ] : $settingConfig['default_value'];
    }

    /**
     * Get all settings with their effective values
     *
     * @param int|null $facilityId Facility scope ID
     * @param int|null $userId User scope ID
     * @return Collection Processed settings collection
     *
     * @uses Cache::remember() for performance optimization
     */
    public function getProcessedSettings(?int $facilityId = null, ?int $userId = null): Collection
    {
        return Cache::remember($this->cacheKey, $this->cacheDuration, function () use ($facilityId, $userId) {
            $processedSettings = collect();

            foreach ($this->availableSettings as $settingKey => $config) {
                $value = $this->getSettingValue($settingKey, $facilityId, $userId);
                $level = $this->getEffectiveLevel($settingKey, $facilityId, $userId);

                $processedSettings->push([
                    'key' => $settingKey,
                    'value' => $value,
                    'slug' => Str::slug($config['name'] ?? $settingKey),
                    'effective_level' => $level?->value ?? 'default',
                    'config' => [
                        ...$config,
                        'type' => $config['type']->value,
                        'levels' => array_map(fn($level) => $level->value, $config['levels'])
                    ],
                    'is_default' => $value === $config['default_value']
                ]);
            }

            return $processedSettings;
        });
    }
}
