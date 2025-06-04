<?php

namespace BrucePull\SettingsPackage\Facades;

use Illuminate\Support\Facades\Facade;
use BrucePull\SettingsPackage\Services\SettingService;

/**
 * --------------------------------------------------------------------------
 * Settings Facade
 * --------------------------------------------------------------------------
 *
 * Provides static access to the SettingService throughout the application.
 *
 * @method static mixed get(string $key, mixed $default = null) Get a setting value
 * @method static void set(string $key, mixed $value) Store a setting value
 * @method static bool has(string $key) Check if a setting exists
 * @method static void forget(string $key) Remove a setting
 * @method static void flush() Clear all settings
 * @method static SettingService instance() Get the underlying service instance
 *
 * @see \BrucePull\SettingsPackage\Services\SettingService
 */
class Settings extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string The service container binding key
     * @method static mixed getSettingValue(string $key, int $facilityId, int $userId, bool $withMetadata = false)
     * @method static mixed getProcessedSettings(string $key, int $facilityId, int $userId)
     * @method static array getProcessedSettings(?int $facilityId = null, ?int $userId = null)
     * @method static \BrucePull\SettingsPackage\Services\SettingsService instance() Get the service instance
     * @see \BrucePull\SettingsPackage\Services\SettingsService
     * 
     * @throws \RuntimeException If the facade cannot be resolved
     */
    protected static function getFacadeAccessor()
    {
        return SettingService::class;
    }

    /**
     * Handle dynamic static method calls.
     *
     * Adds special handling for the 'instance' method to directly
     * return the service instance rather than calling through.
     *
     * @param string $method The method being called
     * @param array $args The method arguments
     * @return mixed
     *
     * @throws \BadMethodCallException If method doesn't exist
     */
    public static function __callStatic($method, $args)
    {
        if ($method === 'instance') {
            // Direct instance access
            return static::getFacadeRoot();
        }

        // Normal facade behavior
        return parent::__callStatic($method, $args);
    }
}
