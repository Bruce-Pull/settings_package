<?php

namespace BrucePull\SettingsPackage\Services\Concerns;

use BrucePull\SettingsPackage\Enums\SettingLevel;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use BrucePull\SettingsPackage\Models\Setting;
use BrucePull\SettingsPackage\Services\Helpers\LevelResolver;

/**
 * --------------------------------------------------------------------------
 * HandlesSchema Trait
 * --------------------------------------------------------------------------
 *
 * Provides functionality for organizing and retrieving settings in structured formats:
 * - Hierarchical grouping by category
 * - Schema generation for UI rendering
 * - Setting inheritance visualization
 * - Data export capabilities
 */
trait HandlesSchema
{
    /**
     * Get a hierarchical schema of settings for a specific level
     *
     * @param SettingLevel $level The scope level to organize
     * @param int|null $modelId Related model ID
     * @return array Structured schema with grouped settings
     *
     * @example Returns:
     * [
     *   'name' => 'System',
     *   'slug' => 'system',
     *   'groups' => [
     *     [
     *       'name' => 'Company',
     *       'groups' => [
     *         [
     *           'name' => 'Branding',
     *           'settings' => [...]
     *         ]
     *       ]
     *     ]
     *   ]
     * ]
     */
    public function getSchemaForLevel(
        SettingLevel $level,
        ?int $modelId = null
    ): array {
        $flatSettings = $this->getSettingsForLevel($level, $modelId);
        $hierarchy = [];

        // Build nested hierarchy from group paths
        foreach ($flatSettings as $setting) {
            $groups = $setting['config']['group_path'] ?? ['General'];

            $currentLevel = &$hierarchy;
            $pathKey = '';

            foreach ($groups as $index => $groupName) {
                $pathKey .= $groupName . '|';
                $isLeaf = ($index === count($groups) - 1);

                if (!Arr::exists($currentLevel, $pathKey)) {
                    $currentLevel[$pathKey] = [
                        'name' => $groupName,
                        'groups' => [],
                        'settings' => [],
                    ];
                }

                if ($isLeaf) {
                    $currentLevel[$pathKey]['settings'][] = $setting;
                }

                if (!$isLeaf) {
                    $currentLevel = &$currentLevel[$pathKey]['groups'];
                }
            }

            unset($currentLevel);
        }

        // Convert flat hierarchy to nested structure
        $convertToNested = function ($items) use (&$convertToNested) {
            return collect($items)
                ->map(function ($item) use ($convertToNested) {
                    return [
                        'name' => $item['name'],
                        'groups' => $convertToNested($item['groups']),
                        'settings' => $item['settings'],
                    ];
                })
                ->values()
                ->toArray();
        };

        return [
            'name' => $level->name(),
            'slug' => Str::slug($level->value),
            'description' => $level->description(),
            'priority' => $level->priority(),
            'groups' => $convertToNested($hierarchy),
        ];
    }

    /**
     * Get complete settings schema for all available levels
     *
     * @param int|null $facilityId Facility scope ID
     * @param int|null $userId User scope ID
     * @return array Array of level schemas
     */
    public function getSettingsSchema(?int $facilityId = null, ?int $userId = null): array
    {
        $schema = [];

        foreach ($this->getLevels() as $levelData) {
            $modelId = LevelResolver::getEntityIdForLevel(SettingLevel::from($levelData['value']), $facilityId, $userId);

            $result = $this->getSchemaForLevel(SettingLevel::from($levelData['value']), $modelId);

            $schema[] = $result;
        }

        return $schema;
    }

    /**
     * Get inheritance hierarchy for a specific setting
     *
     * @param string $key Setting key
     * @param int|null $facilityId Facility scope ID
     * @param int|null $userId User scope ID
     * @return array Inheritance chain with effective value marker
     *
     * @example Returns:
     * [
     *   [
     *     'level' => 'system',
     *     'value' => 'blue',
     *     'exists' => true,
     *     'is_effective' => false
     *   ],
     *   [
     *     'level' => 'user',
     *     'value' => 'red',
     *     'exists' => true,
     *     'is_effective' => true  
     *   ]
     * ]
     */
    public function getSettingHierarchy(string $key, ?int $facilityId = null, ?int $userId = null): array
    {
        if (!isset($this->availableSettings[$key])) {
            return [];
        }

        $settingConfig = $this->availableSettings[$key];
        $availableLevels = $settingConfig['levels'];
        $hierarchy = [];

        foreach ($availableLevels as $level) {
            $entityId = LevelResolver::getEntityIdForLevel($level, $facilityId, $userId);

            $setting = Setting::forKey($key)
                ->forLevel($level, $entityId)
                ->first();

            $hierarchy[] = [
                'level' => $level,
                'model_id' => $entityId,
                'value' => $setting
                    ? $setting->value
                    : null,
                'exists' => $setting !== null,
                'is_effective' => false
            ];
        }

        // Mark the effective level (first existing in hierarchy)
        foreach ($hierarchy as &$item) {
            if ($item['exists']) {
                $item['is_effective'] = true;
                break;
            }
        }

        // Add default value if no level has the setting
        if (!collect($hierarchy)->contains('is_effective', true)) {
            $hierarchy[] = [
                'level' => 'default',
                'model_id' => null,
                'value' => $settingConfig['default_value'],
                'exists' => true,
                'is_effective' => true
            ];
        }

        return $hierarchy;
    }

    /**
     * Export settings as key-value pairs for a specific level
     *
     * @param SettingLevel $level The scope level to export
     * @param int|null $modelId Related model ID
     * @return array Key-value pairs of settings
     */
    public function exportLevelSettings(SettingLevel $level, ?int $modelId = null): array
    {
        $settings = $this->getSettingsForLevel($level, $modelId);

        return $settings->mapWithKeys(function ($setting) {
            return [$setting['key'] => $setting['value']];
        })->toArray();
    }
}
