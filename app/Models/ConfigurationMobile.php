<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ConfigurationMobile extends Model
{
    protected $table = 'configurations_mobiles';

    protected $fillable = [
        'cle',
        'valeur',
        'type',
        'description',
    ];

    protected $casts = [
        'valeur' => 'string',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('configurations_mobiles'));
        static::deleted(fn () => Cache::forget('configurations_mobiles'));
    }

    public static function getValeur(string $cle, mixed $defaut = null): mixed
    {
        $configs = Cache::rememberForever('configurations_mobiles', function () {
            return static::pluck('valeur', 'cle')->toArray();
        });

        $valeur = $configs[$cle] ?? $defaut;
        if ($valeur === null) return $defaut;

        $config = static::where('cle', $cle)->first();
        if (!$config) return $defaut;

        return match ($config->type) {
            'boolean' => filter_var($valeur, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $valeur,
            'json' => json_decode($valeur, true),
            default => $valeur,
        };
    }

    public static function setValeur(string $cle, mixed $valeur, string $type = 'string', ?string $description = null): self
    {
        if (is_bool($valeur)) {
            $valeur = $valeur ? 'true' : 'false';
            $type = 'boolean';
        } elseif (is_array($valeur)) {
            $valeur = json_encode($valeur);
            $type = 'json';
        } elseif (is_int($valeur)) {
            $type = 'integer';
        }

        return static::updateOrCreate(
            ['cle' => $cle],
            ['valeur' => (string) $valeur, 'type' => $type, 'description' => $description]
        );
    }
}
