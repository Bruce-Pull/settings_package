<?php

namespace BrucePull\SettingsPackage\Enums;

/**
 * --------------------------------------------------------------------------
 * Setting Level Enum
 * --------------------------------------------------------------------------
 *
 * Defines the hierarchy and scope levels for application settings.
 * Each level represents a different configuration scope with its own priority.
 * 
 * System -> Facility -> User (increasing priority)
 * 
 * Higher priority levels can override lower priority settings.
 */
enum SettingLevel: string
{
    /**
     * ----------------------------------------------------------------------
     * System Level
     * ----------------------------------------------------------------------
     * 
     * Global application settings that apply to the entire system.
     * These are typically configured by administrators and serve as defaults.
     */
    case SYSTEM = 'system';

    /**
     * 
     * ----------------------------------------------------------------------
     * Facility Level
     * ----------------------------------------------------------------------
     * 
     * Settings specific to individual facilities/locations.
     * These override system settings when available.
     */
    case FACILITY = 'facility';

    /**
     * ----------------------------------------------------------------------
     * User Level
     * ----------------------------------------------------------------------
     * 
     * Personal settings for individual users.
     * These have the highest priority and override all other levels.
     */
    case USER = 'user';

    /**
     * Get the priority level for setting inheritance.
     * 
     * Lower numbers indicate higher precedence in the override hierarchy.
     * 
     * @return int The priority value (1 = highest, 3 = lowest)
     */
    public function priority(): int
    {
        return match ($this) {
            self::SYSTEM => 1, // Highest precedence
            self::FACILITY => 2, // Medium precedence
            self::USER => 3 // Lowest precedence
        };
    }

    /**
     * Get the human-readable display name for this level.
     * 
     * @return string The formatted level name
     */
    public function name(): string
    {
        return match ($this) {
            self::SYSTEM => 'System',
            self::FACILITY => 'Facility',
            self::USER => 'User',
        };
    }

    /**
     * Get a descriptive explanation of this level's purpose.
     * 
     * @return string The level description
     */
    public function description(): string
    {
        return match ($this) {
            self::SYSTEM => 'Global system settings',
            self::FACILITY => 'Facility specifyc settings',
            self::USER => 'User specific settings',
        };
    }

    /**
     * ----------------------------------------------------------------------
     * Utility Methods
     * ----------------------------------------------------------------------
     */

    /**
     * Get all levels ordered by priority (highest first).
     * 
     * @return array<SettingLevel> Ordered array of enum cases
     */
    public static function getOrderedByPriority(): array
    {
        $levels = self::cases();

        usort($levels, fn($a, $b) => $b->priority() <=> $a->priority());

        return $levels;
    }

    /**
     * Filter available levels based on allowed permissions.
     * 
     * @param array<string> $allowedLevels Array of string values (e.g., ['system', 'user'])
     * @return array<SettingLevel> Filtered array of enum cases
     */
    public static function getAvailableLevelsFor(array $allowedLevels): array
    {
        return array_filter(
            self::cases(),
            fn($level) => in_array($level->value, $allowedLevels)
        );
    }
}
