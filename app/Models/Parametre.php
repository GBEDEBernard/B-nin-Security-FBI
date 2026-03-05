<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Parametre extends Model
{
    protected $fillable = [
        'cle',
        'valeur',
        'type',
        'categorie',
        'description',
        'est_visible',
        'est_modifiable',
    ];

    protected $casts = [
        'est_visible' => 'boolean',
        'est_modifiable' => 'boolean',
    ];

    /**
     * Catégories disponibles
     */
    public const CATEGORIES = [
        'general' => 'Général',
        'email' => 'Email',
        'security' => 'Sécurité',
        'api' => 'API',
        'mobile' => 'Mobile',
        'paiement' => 'Paiement',
        'backup' => 'Sauvegarde',
        'notifications' => 'Notifications',
        'abonnement' => 'Abonnement',
        'facturation' => 'Facturation',
    ];

    /**
     * Obtenir un paramètre par sa clé
     */
    public static function get(string $cle, $default = null)
    {
        $cacheKey = "parametre_{$cle}";
        
        return Cache::remember($cacheKey, 3600, function () use ($cle, $default) {
            $parametre = static::where('cle', $cle)->first();
            
            if (!$parametre) {
                return $default;
            }

            return static::castValeur($parametre->valeur, $parametre->type);
        });
    }

    /**
     * Définir un paramètre
     */
    public static function set(string $cle, $valeur): bool
    {
        $type = static::detectType($valeur);
        $valeurString = static::valeurToString($valeur, $type);

        $parametre = static::where('cle', $cle)->first();

        if ($parametre) {
            $parametre->update([
                'valeur' => $valeurString,
                'type' => $type,
            ]);
        } else {
            // Extraire la catégorie de la clé
            $categorie = explode('.', $cle)[0] ?? 'general';

            static::create([
                'cle' => $cle,
                'valeur' => $valeurString,
                'type' => $type,
                'categorie' => $categorie,
            ]);
        }

        // Effacer le cache
        Cache::forget("parametre_{$cle}");

        return true;
    }

    /**
     * Obtenir tous les paramètres d'une catégorie
     */
    public static function getByCategorie(string $categorie)
    {
        return static::where('categorie', $categorie)
            ->where('est_visible', true)
            ->orderBy('cle')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->cle => static::castValeur($item->valeur, $item->type)];
            });
    }

    /**
     * Obtenir tous les paramètres visibles
     */
    public static function getAllVisible()
    {
        return static::where('est_visible', true)
            ->orderBy('categorie')
            ->orderBy('cle')
            ->get();
    }

    /**
     * Détecter le type de la valeur
     */
    private static function detectType($valeur): string
    {
        if (is_bool($valeur)) {
            return 'boolean';
        }
        if (is_int($valeur)) {
            return 'integer';
        }
        if (is_array($valeur) || is_object($valeur)) {
            return 'json';
        }
        return 'string';
    }

    /**
     * Convertir la valeur en chaîne pour le stockage
     */
    private static function valeurToString($valeur, string $type): string
    {
        switch ($type) {
            case 'boolean':
                return $valeur ? '1' : '0';
            case 'integer':
                return (string) $valeur;
            case 'json':
                return json_encode($valeur);
            default:
                return (string) $valeur;
        }
    }

    /**
     * Convertir la valeur stockée en type approprié
     */
    private static function castValeur($valeur, string $type)
    {
        switch ($type) {
            case 'boolean':
                return in_array($valeur, ['1', 'true', 'yes'], true);
            case 'integer':
                return (int) $valeur;
            case 'json':
                return json_decode($valeur, true);
            default:
                return $valeur;
        }
    }

    /**
     * Scope pour filtrer par catégorie
     */
    public function scopeCategorie($query, string $categorie)
    {
        return $query->where('categorie', $categorie);
    }

    /**
     * Scope pour les paramètres visibles
     */
    public function scopeVisible($query)
    {
        return $query->where('est_visible', true);
    }

    /**
     * Scope pour les paramètres modifiables
     */
    public function scopeModifiable($query)
    {
        return $query->where('est_modifiable', true);
    }
}

