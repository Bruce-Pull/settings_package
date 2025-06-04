<?php

namespace BrucePull\SettingsPackage\Services\Helpers;

use Illuminate\Support\Str;
use BrucePull\SettingsPackage\Enums\SettingLevel;

/**
 * --------------------------------------------------------------------------
 * LevelResolver Class
 * --------------------------------------------------------------------------
 *
 * Provides helper functions to resolve the appropriate entity ID based on the setting level.
 * - Resolves entity IDs based on system, facility, or user levels.
 * - Filters levels according to priority.
 * - Ensures model IDs are correctly provided for non-system settings.
 */
class LevelResolver
{
    /**
     * --------------------------------------------------------------------------
     * getEntityIdForLevel Method
     * --------------------------------------------------------------------------
     *
     * Resolves the entity ID based on the setting level.
     * - Returns null for system level.
     * - Returns the facility ID for the facility level.
     * - Returns the user ID for the user level.
     *
     * @param SettingLevel $level The setting level (SYSTEM, FACILITY, USER).
     * @param int|null $facilityId The ID of the facility (optional, required for FACILITY level).
     * @param int|null $userId The ID of the user (optional, required for USER level).
     * @return int|null Returns the appropriate entity ID or null if no valid ID is found.
     */
    public static function getEntityIdForLevel(SettingLevel $level, ?int $facilityId = null, ?int $userId = null): ?int
    {
        return match ($level) {
            SettingLevel::SYSTEM => null, // SYSTEM level has no entity ID
            SettingLevel::FACILITY => $facilityId, // FACILITY level uses facility ID
            SettingLevel::USER => $userId // USER level uses user ID
        };
    }

    /**
     * --------------------------------------------------------------------------
     * getEntityIdForLevelFromModel Method
     * --------------------------------------------------------------------------
     *
     * Resolves the entity ID based on the setting level, and requires a model ID for non-system levels.
     * - Throws an exception if no model ID is provided for levels other than SYSTEM.
     *
     * @param SettingLevel $level The setting level (SYSTEM, FACILITY, USER).
     * @param int|null $modelId The ID of the model (required for all levels except SYSTEM).
     * @return int|null Returns the resolved entity ID, or throws an exception for invalid input.
     * @throws \InvalidArgumentException If the model ID is missing for non-SYSTEM levels.
     */
    public static function getEntityIdForLevelFromModel(SettingLevel $level, ?int $modelId = null): ?int
    {
        // If the level is not SYSTEM and no model ID is provided, throw an exception
        if ($level !== SettingLevel::SYSTEM && $modelId === null) {
            throw new \InvalidArgumentException("Model ID is required for {$level->value} settings");
        }

        // Return the model ID unless the level is SYSTEM, in which case return null
        return $level === SettingLevel::SYSTEM
            ? null :
            $modelId;
    }

    /**
     * --------------------------------------------------------------------------
     * getLevelsInPriorityOrder Method
     * --------------------------------------------------------------------------
     *
     * Returns the available setting levels sorted by their predefined priority.
     * - Filters out levels not included in the available levels array.
     *
     * @param array $availableLevels An array of available setting levels.
     * @return array Returns an array of setting levels sorted by priority.
     */
    public static function getLevelsInPriorityOrder(array $availableLevels): array
    {
        // Filter and return the available levels sorted by priority
        return array_filter(
            SettingLevel::getOrderedByPriority(), // Get levels in priority order
            fn($level) => in_array($level, $availableLevels) // Only include levels in the availableLevels array
        );
    }

    /**
     * --------------------------------------------------------------------------
     * getLevels Method
     * --------------------------------------------------------------------------
     *
     * Returns an array of available setting levels with their properties.
     *
     * @return array Returns an array of available setting levels.
     */
    public static function getLevels(): array
    {
        return array_map(fn($level) => [
            'value' => $level->value,
            'name' => $level->name(),
            'slug' => Str::slug($level->value),
            'description' => $level->description(),
            'priority' => $level->priority()
        ], SettingLevel::cases());
    }
}
