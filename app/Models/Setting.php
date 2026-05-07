<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Cache key for all settings
    const CACHE_KEY = 'app_settings';
    const CACHE_TTL = 3600; // 1 hour

    /**
     * Get setting value by key
     */
    public static function get(string $key, $default = null)
    {
        // Try cache first
        $settings = Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return self::all()->mapWithKeys(function ($item) {
                return [$item->key => self::castValue($item->value, $item->type)];
            })->toArray();
        });

        return $settings[$key] ?? $default;
    }

    /**
     * Set setting value
     */
    public static function set(string $key, $value, string $type = 'string', string $group = 'general'): void
    {
        self::updateOrCreate(
            ['key' => $key],
            [
                'value' => self::prepareValueForStorage($value, $type),
                'type' => $type,
                'group' => $group
            ]
        );

        // Clear cache
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Get all settings grouped by category
     */
    public static function getAllByGroup(): array
    {
        return self::all()->groupBy('group')->map(function ($items) {
            return $items->mapWithKeys(function ($item) {
                return [$item->key => self::castValue($item->value, $item->type)];
            });
        })->toArray();
    }

    /**
     * Get all settings as flat array
     */
    public static function getAll(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return self::all()->mapWithKeys(function ($item) {
                return [$item->key => self::castValue($item->value, $item->type)];
            })->toArray();
        });
    }

    /**
     * Cast value based on type
     */
    private static function castValue($value, string $type): mixed
    {
        return match($type) {
            'boolean' => (bool) $value,
            'integer' => (int) $value,
            'float' => (float) $value,
            'json' => json_decode($value, true),
            'array' => json_decode($value, true) ?? [],
            default => (string) $value,
        };
    }

    /**
     * Prepare value for storage
     */
    private static function prepareValueForStorage($value, string $type): string
    {
        return match($type) {
            'boolean' => $value ? '1' : '0',
            'json', 'array' => json_encode($value),
            default => (string) $value,
        };
    }

    /**
     * Clear settings cache
     */
    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}