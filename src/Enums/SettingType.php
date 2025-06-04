<?php

namespace BrucePull\SettingsPackage\Enums;

/**
 * --------------------------------------------------------------------------
 * Setting Type Enum
 * --------------------------------------------------------------------------
 *
 * Defines the data types supported by application settings.
 * Provides type casting and serialization methods to ensure consistent
 * data handling across storage and application layers.
 */
enum SettingType: string
{
    /**
     * Plain text string (default type)
     */
    case STRING = 'string';

    /**
     * Boolean true/false value
     */
    case BOOLEAN = 'boolean';

    /**
     * Whole number integer value
     */
    case INTEGER = 'integer';

    /**
     * JSON-encoded complex data (arrays or objects)
     */
    case JSON = 'json';

    /**
     * Floating point/decimal value
     */
    case FLOAT = 'float';

    /**
     * Cast raw value to the appropriate PHP type
     *
     * @param mixed $value The raw value from storage
     * @return mixed The type-cast value
     *
     * @throws \JsonException If JSON decoding fails
     */
    public function cast($value)
    {
        return match ($this) {
            self::BOOLEAN => (bool) $value,
            self::INTEGER => (int) $value,
            self::FLOAT => (float) $value,
            self::JSON => json_decode($value, true),
            default => $value
        };
    }

    /**
     * Serialize PHP value to storable string format
     *
     * @param mixed $value The PHP value to store
     * @return string The serialized string representation
     *
     * @throws \JsonException If JSON encoding fails
     */
    public function serialize($value): string
    {
        // Convert null to empty string
        if ($value === null) {
            return '';
        }

        return match ($this) {
            self::BOOLEAN => (bool) $value,
            self::INTEGER, self::FLOAT => (string) $value,
            self::JSON => json_encode($value),
            default => (string) $value
        };
    }
}
