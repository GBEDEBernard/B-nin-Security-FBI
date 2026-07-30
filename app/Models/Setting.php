<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $table = 'settings';

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
        'description',
        'is_public',
    ];

    protected $casts = [
        'value' => 'string',
        'is_public' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('settings.all'));
        static::deleted(fn () => Cache::forget('settings.all'));
    }

    public static function getValue(string $key, mixed $default = null): mixed
    {
        $setting = static::getCached()->firstWhere('key', $key);
        if (!$setting) return $default;

        $value = $setting->value;

        return match ($setting->type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'number' => is_numeric($value) ? $value + 0 : $default,
            'json' => json_decode($value, true) ?? $default,
            default => $value,
        };
    }

    public static function setValue(string $key, mixed $value): bool
    {
        $type = match (true) {
            is_bool($value) => 'boolean',
            is_numeric($value) => 'number',
            is_array($value) || is_object($value) => 'json',
            default => 'string',
        };

        $stringValue = match ($type) {
            'boolean' => $value ? '1' : '0',
            'json' => json_encode($value),
            'number' => (string) $value,
            default => (string) $value,
        };

        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $stringValue, 'type' => $type]
        )->wasChanged();
    }

    public static function getCached(): \Illuminate\Database\Eloquent\Collection
    {
        return Cache::rememberForever('settings.all', fn () => static::get());
    }

    public static function getByGroup(?string $group = null): \Illuminate\Database\Eloquent\Collection
    {
        $settings = static::getCached();
        if ($group) {
            return $settings->where('group', $group);
        }
        return $settings;
    }

    public static function getGroups(): array
    {
        return static::getCached()->pluck('group')->unique()->values()->toArray();
    }

    public function castedValue(): mixed
    {
        return match ($this->type) {
            'boolean' => filter_var($this->value, FILTER_VALIDATE_BOOLEAN),
            'number' => is_numeric($this->value) ? $this->value + 0 : null,
            'json' => json_decode($this->value, true) ?? [],
            default => $this->value,
        };
    }

    public function getFormFieldType(): string
    {
        return match ($this->type) {
            'boolean' => 'checkbox',
            'number' => 'number',
            'password' => 'password',
            'json' => 'textarea',
            default => 'text',
        };
    }

    public function getGroupLabel(): string
    {
        return match ($this->group) {
            'general' => 'Général',
            'security' => 'Sécurité',
            'email' => 'Email',
            'api' => 'API',
            'mobile' => 'Mobile',
            'notification' => 'Notifications Push',
            'facturation' => 'Facturation',
            default => ucfirst($this->group),
        };
    }

    public function getGroupIcon(): string
    {
        return match ($this->group) {
            'general' => 'bi-gear',
            'security' => 'bi-shield-lock',
            'email' => 'bi-envelope-at',
            'api' => 'bi-code-slash',
            'mobile' => 'bi-phone',
            'notification' => 'bi-bell',
            'facturation' => 'bi-currency-exchange',
            default => 'bi-circle',
        };
    }
}
