<?php

namespace BrucePull\SettingsPackage\Models;

use BrucePull\SettingsPackage\Enums\SettingLevel;
use BrucePull\SettingsPackage\Enums\SettingType;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * --------------------------------------------------------------------------
 * Setting Model
 * --------------------------------------------------------------------------
 *
 * Represents a configurable application setting with type casting and scopes.
 * 
 * @property string $key Unique setting identifier
 * @property mixed $value Setting value (type-cast according to type)
 * @property SettingLevel $level Scope level (system/facility/user)
 * @property int|null $model_id Related model ID for scoped settings
 * @property SettingType $type Data type of the value
 * @property bool $is_resettable Whether setting can be reset to default
 */
class Setting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'key',
        'value',
        'level',
        'model_id',
        'type',
        'is_resettable'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // Cast to SettingLevel enum
        'level' => SettingLevel::class,

        // Cast to SettingType enum
        'type' => SettingType::class,

        // Ensure integer type
        'model_id' => 'integer'
    ];

    /**
     * Value attribute accessor/mutator with automatic type conversion.
     *
     * @return Attribute<mixed, mixed>
     */
    protected function value(): Attribute
    {
        return Attribute::make(
            // Cast stored value to PHP type
            get: fn($value) => $this->type->cast($value),

            // Convert PHP value to storable format
            set: fn($value) => $this->type->serialize($value)
        );
    }

    /**
     * ----------------------------------------------------------------------
     * Query Scopes
     * ----------------------------------------------------------------------
     */

    /**
     * Scope settings for a specific level and optional model ID.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param SettingLevel $level The scope level to filter by
     * @param int|null $modelId Related model ID for scoped settings
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForLevel($query, SettingLevel $level, ?int $modelId = null)
    {
        return $query->where('level', $level)
            ->where('model_id', $modelId);
    }

    /**
     * Scope settings by their key.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $key The setting key to search for
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForKey($query, string $key)
    {
        return $query->where('key', $key);
    }

    /**
     * Scope settings for multiple levels.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array<SettingLevel> $levels Array of levels to filter by
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForLevels($query, array $levels)
    {
        return $query->whereIn('level', $levels);
    }
}
