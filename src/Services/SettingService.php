<?php

namespace BrucePull\SettingsPackage\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use BrucePull\SettingsPackage\Services\Concerns\HandlesSchema;
use BrucePull\SettingsPackage\Services\Concerns\HandlesRetrieval;
use BrucePull\SettingsPackage\Services\Concerns\HandlesValidation;
use BrucePull\SettingsPackage\Services\Concerns\HandlesPersistence;

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
