<?php

use BrucePull\SettingsPackage\Facades\Settings;

/**
 * --------------------------------------------------------------------------
 * Setting Helper Function
 * --------------------------------------------------------------------------
 *
 * Provides a convenient helper function for retrieving settings values.
 * - Retrieves the setting value based on the provided key.
 * - Optionally, can use a facility and user context for more specific settings.
 * - Falls back to a default value if the setting is not found.
 * 
 * @param string|null $key The key of the setting to retrieve.
 * @param mixed $default The default value to return if the setting is not found.
 * @param int|null $facilityId The ID of the facility (optional).
 * @param int|null $userId The ID of the user (optional).
 *
 * @return mixed The setting value, or the default value if the setting is not found.
 */
if (!function_exists('setting')) {
    function setting($key = null, $default = null, $facilityId = null, $userId = null)
    {
        // If no key is provided, return the SettingService instance for further manipulation
        if (is_null($key)) {
            return app(\BrucePull\SettingsPackage\Services\SettingService::class);
        }

        // If no user ID is provided, use the authenticated user's ID if available
        if (is_null($userId) && auth()->check()) {
            $userId = auth()->id();
        }

        // If no facility ID is provided, use the authenticated user's facility ID if available
        if (is_null($facilityId) && auth()->check() && auth()->user() && property_exists(auth()->user(), 'facility_id')) {
            $facilityId = auth()->user()->facility_id;
        }

        // Retrieve the setting value using the provided key, facility ID, and user ID, or return the default value
        return Settings::getSettingValue($key, $facilityId, $userId) ?? $default;
    }
}
