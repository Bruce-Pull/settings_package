<?php

namespace BrucePull\SettingsPackage\Providers;

use Illuminate\Support\ServiceProvider;
use BrucePull\SettingsPackage\Services\SettingService;

/**
 * --------------------------------------------------------------------------
 * Settings Service Provider
 * --------------------------------------------------------------------------
 *
 * Registers and bootstraps the Settings package components with Laravel.
 * Handles package configuration, service registration, and resource publishing.
 */
class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register package services and configuration.
     *
     * @return void
     */
    public function register(): void
    {
        // Merge package configuration with application config
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/settings.php',
            'settings'
        );

        // Register the main SettingService as a singleton
        $this->app->singleton(SettingService::class, function ($app) {
            return new SettingService(
                cacheDuration: config('settings.cache_duration', 60)
            );
        });

        // Register service alias for convenient access
        $this->app->alias(SettingService::class, 'laravel-settings');

        // Register package helpers
        if (file_exists(__DIR__ . '/../helpers.php')) {
            require_once __DIR__ . '/../helpers.php';
        }
    }

    /**
     * Bootstrap package services and resources.
     *
     * @return void
     */
    public function boot(): void
    {
        // Publish configuration file
        $this->publishes([
            __DIR__ . '/../../config/settings.php' => config_path('settings.php'),
        ], 'settings-config'); // Tag: 'settings-config'

        // Publish migrations
        $this->publishes([
            __DIR__ . '/../../database/migrations/' => database_path('migrations'),
        ], 'settings-migrations'); // Tag: 'settings-migrations'

        // Load package migrations automatically
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        // Register console commands when running in CLI
        if ($this->app->runningInConsole()) {
            $this->commands([
                // Register Artisan commands here when needed
                // Example: \BrucePull\SettingsPackage\Console\Commands\InstallSettings::class
            ]);
        }
    }
}
