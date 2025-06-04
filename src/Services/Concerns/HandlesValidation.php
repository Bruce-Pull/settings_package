<?php

namespace BrucePull\SettingsPackage\Services\Concerns;

use BrucePull\SettingsPackage\Enums\SettingLevel;

/**
 * --------------------------------------------------------------------------
 * HandlesValidation Trait
 * --------------------------------------------------------------------------
 *
 * Provides functionality to validate settings and check the levels at which they can be applied.
 * - Validates the value of a setting based on predefined validation rules.
 * - Checks if a setting can be applied at a specific level.
 */
trait HandlesValidation
{
    /**
     * --------------------------------------------------------------------------
     * validateSettingValue Method
     * --------------------------------------------------------------------------
     *
     * Validates the given value for a specific setting based on its predefined validation rules.
     *
     * @param string $key The setting's key to validate.
     * @param mixed $value The value to be validated.
     * @return bool Returns true if the value passes validation, otherwise false.
     */
    public function validateSettingValue(string $key, $value): bool
    {
        // Check if the setting key exists in the available settings
        if (!isset($this->availableSettings[$key])) {
            return false;
        }

        // Retrieve the setting configuration
        $config = $this->availableSettings[$key];

        // Check if validation rules are defined for the setting
        if (isset($config['validation'])) {
            // Create a validator instance to validate the value
            $validator = validator([
                'value' => $value
            ], [
                'value' => $config['validation']
            ]);

            // Return true if validation passes, false otherwise
            return !$validator->fails();
        }

        // If no validation rules exist, assume the value is valid
        return true;
    }

    /**
     * --------------------------------------------------------------------------
     * canSetSettingAtThisLevel Method
     * --------------------------------------------------------------------------
     *
     * Checks if the setting can be applied at a specific level.
     *
     * @param string $key The setting's key to check.
     * @param SettingLevel $level The level at which the setting is being applied.
     * @return bool Returns true if the setting can be applied at the given level, otherwise false.
     */
    private function canSetSettingAtThisLevel(string $key, SettingLevel $level): bool
    {
        // Check if the setting key exists in the available settings
        if (!isset($this->availableSettings[$key])) {
            return false;
        }

        // Check if the setting's levels array includes the specified level
        return in_array($level, $this->availableSettings[$key]['levels']);
    }
}
