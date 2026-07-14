<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SystemSetting extends Model
{
    protected $table = 'system_settings';

    protected $fillable = [
        'key', 'value', 'type', 'group', 'is_encrypted',
    ];

    protected $casts = [
        'is_encrypted' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('system_settings');
        });
        static::deleted(function () {
            Cache::forget('system_settings');
        });
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();
        if (!$setting) {
            return $default;
        }
        $value = $setting->value;
        return match ($setting->type) {
            'boolean' => (bool) $value,
            'integer' => (int) $value,
            'json'    => json_decode($value, true),
            default   => $value,
        };
    }

    public static function set(string $key, mixed $value, string $group = 'general', bool $encrypted = false): void
    {
        $type = match (true) {
            is_bool($value)   => 'boolean',
            is_int($value)    => 'integer',
            is_array($value)  => 'json',
            default            => 'string',
        };
        static::updateOrCreate(
            ['key' => $key],
            [
                'value'        => (string) $value,
                'type'         => $type,
                'group'        => $group,
                'is_encrypted' => $encrypted,
            ]
        );
    }

    public static function getGroup(string $group): array
    {
        return static::where('group', $group)->pluck('value', 'key')->toArray();
    }

    public static function getAllGrouped(): array
    {
        $all = static::all();
        return $all->groupBy('group')->map(function ($items) {
            return $items->pluck('value', 'key')->toArray();
        })->toArray();
    }
}
