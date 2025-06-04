<?php

namespace BrucePull\SettingsPackage\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use BrucePull\SettingsPackage\Services\Concerns\HandlesSchema;
use BrucePull\SettingsPackage\Services\Concerns\HandlesRetrieval;
use BrucePull\SettingsPackage\Services\Concerns\HandlesValidation;
use BrucePull\SettingsPackage\Services\Concerns\HandlesPersistence;
use BrucePull\SettingsPackage\Services\Helpers\LevelResolver;
use BrucePull\SettingsPackage\Models\Setting;
use BrucePull\SettingsPackage\Enums\SettingLevel;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SettingService
{
    use HandlesRetrieval;
    use HandlesPersistence;
    use HandlesSchema;
    use HandlesValidation;

    private array $availableSettings;
    private int $cacheDuration;
    private string $cacheKey = '';
    private ?int $userId = null;

    public function __construct(int $cacheDuration = 3600)
    {
        $this->availableSettings = Config::get('settings.default_settings', []);

        $this->userId = auth()->user()->id;
        $this->cacheKey = "settings_{$this->userId}";
        $this->cacheDuration = $cacheDuration;
    }


    /**
     * @method get(string $key, ?int $facilityId = null, ?int $userId = null) Alias of getSettingValue()
     */
    public function get(...$args)
    {
        return $this->getSettingValue(...$args);
    }

    /**
     * @method set(string $key, mixed $value) Alias of setSetting()
     */
    public function set(...$args)
    {
        return $this->setSetting(...$args);
    }

    /**
     * Get all settings of a specific level
     */
    public function getSettingsForLevel(
        SettingLevel $level,
        ?int $modelId = null
    ): Collection {
        $entityId = LevelResolver::getEntityIdForLevelFromModel($level, $modelId);
        $processedSettings = collect();

        // Filter only the settings that are available for this area
        $availableForLevel = array_filter($this->availableSettings, function ($config) use ($level) {
            return in_array($level, $config['levels']);
        });

        foreach ($availableForLevel as $settingKey => $config) {
            // Search if it exists specifically in this level
            $dbSetting = Setting::forKey($settingKey)
                ->forLevel($level, $entityId)
                ->first();

            if ($dbSetting) {
                // If exists in BD for this level, use that value
                $value = $dbSetting->value;
                $source = 'database';
                $isDefault = false;
            }

            if (!isset($dbSetting)) {
                // If not exists in BD for this level, use the default value
                $value = $config['default_value'];
                $source = 'config';
                $isDefault = true;
            }

            $processedSettings->push([
                'key' => $settingKey,
                'value' => $value,
                'slug' => Str::slug($config['name'] ?? $settingKey),
                'level' => $level->value,
                'model_id' => $entityId,
                'source' => $source,
                'is_default' => $isDefault,
                'config' => [
                    ...$config,
                    'type' => $config['type']->value,
                    'levels' => array_map(fn($level) => $level->value, $config['levels'])
                ]
            ]);
        }

        return $processedSettings;
    }

    /**
     * Get information about available settings configuration
     */
    public function getAvailableSettings(): array
    {
        // Serialize for the JSON response
        $serialized = [];

        foreach ($this->availableSettings as $key => $config) {
            $serialized[$key] = [
                ...$config,
                'levels' => array_map(fn($level) => $level->value, $config['levels']),
                'slug' => Str::slug($config['name'] ?? $key),
                'type' => $config['type']->value
            ];
        }

        return $serialized;
    }


    public function getLevels(): array
    {
        return LevelResolver::getlevels();
    }

    private function getEffectiveLevel(string $key, ?int $facilityId = null, ?int $userId = null): ?SettingLevel
    {
        $settingConfig = $this->availableSettings[$key];
        $availableLevels = $settingConfig['levels'];
        $levelsToCheck = LevelResolver::getLevelsInPriorityOrder($availableLevels);

        foreach ($levelsToCheck as $level) {
            $entityId = LevelResolver::getEntityIdForLevel($level, $facilityId, $userId);

            $exists = Setting::forKey($key)
                ->forLevel($level, $entityId)
                ->exists();

            if ($exists) {
                return $level;
            }
        }

        return null;
    }

    /**
     * Clear cache based on the provided key
     *
     * @return void
     */
    private function clearSettingsCache(): void
    {
        // Forget cache based on the provided key
        Cache::forget($this->cacheKey);
    }
}
