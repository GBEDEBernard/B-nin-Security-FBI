<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'notifiable_type',
        'notifiable_id',
        'type',
        'titre',
        'message',
        'statut',
        'cible_type',
        'cible_id',
        'envoyeur_id',
        'entreprise_id',
        'url',
        'donnees',
        'lu_le',
    ];

    protected $casts = [
        'donnees' => 'array',
        'lu_le' => 'datetime',
    ];

    /**
     * Types de notification disponibles
     */
    public const TYPES = [
        'info' => 'Information',
        'success' => 'Succès',
        'warning' => 'Avertissement',
        'error' => 'Erreur',
    ];

    /**
     * Statuts de notification disponibles
     */
    public const STATUTS = [
        'brouillon' => 'Brouillon',
        'envoyee' => 'Envoyée',
        'echouee' => 'Échouée',
    ];

    /**
     * Relation polymorphic vers le destinataire
     */
    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Relation vers l'entreprise
     */
    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(Entreprise::class);
    }

    /**
     * Relation vers l'envoyeur
     */
    public function envoyeur(): MorphTo
    {
        return $this->morphTo('envoyeur');
    }

    /**
     * Relation vers la cible (utilisateur, employé, client)
     */
    public function cible(): MorphTo
    {
        return $this->morphTo('cible');
    }

    /**
     * Scope pour les notifications non-lues
     */
    public function scopeNonLues($query)
    {
        return $query->whereNull('lu_le');
    }

    /**
     * Scope pour les notifications d'une entreprise
     */
    public function scopePourEntreprise($query, $entrepriseId)
    {
        return $query->where('entreprise_id', $entrepriseId);
    }

    /**
     * Scope pour les notifications globales (non liées à une entreprise)
     */
    public function scopeGlobales($query)
    {
        return $query->whereNull('entreprise_id');
    }

    /**
     * Scope pour les notifications d'un utilisateur
     */
    public function scopePourUtilisateur($query, $user)
    {
        return $query->where(function ($q) use ($user) {
            $q->where('notifiable_type', get_class($user))
                ->where('notifiable_id', $user->id);
        });
    }

    /**
     * Scope pour les notifications par type
     */
    public function scopeParType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope pour les notifications par statut
     */
    public function scopeParStatut($query, $statut)
    {
        return $query->where('statut', $statut);
    }

    /**
     * Marquer la notification comme lue
     */
    public function marquerLue()
    {
        if (!$this->lu_le) {
            $this->update(['lu_le' => now()]);
        }
        return $this;
    }

    /**
     * Marquer la notification comme non-lue
     */
    public function marquerNonLue()
    {
        $this->update(['lu_le' => null]);
        return $this;
    }

    /**
     * Vérifier si la notification est lue
     */
    public function estLue(): bool
    {
        return !is_null($this->lu_le);
    }

    /**
     * Vérifier si la notification est non-lue
     */
    public function estNonLue(): bool
    {
        return is_null($this->lu_le);
    }

    /**
     * Obtenir la classe CSS selon le type
     */
    public function getTypeBadgeAttribute(): string
    {
        return match ($this->type) {
            'info' => 'bg-info',
            'success' => 'bg-success',
            'warning' => 'bg-warning',
            'error' => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    /**
     * Obtenir l'icône selon le type
     */
    public function getTypeIconAttribute(): string
    {
        return match ($this->type) {
            'info' => 'bi-info-circle-fill',
            'success' => 'bi-check-circle-fill',
            'warning' => 'bi-exclamation-triangle-fill',
            'error' => 'bi-x-circle-fill',
            default => 'bi-bell-fill',
        };
    }

    /**
     * Obtenir le temps écoulé depuis la création
     */
    public function getTempsEcouleAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Créer une notification
     */
    public static function creer(array $data): self
    {
        return self::create([
            'notifiable_type' => $data['notifiable_type'],
            'notifiable_id' => $data['notifiable_id'],
            'type' => $data['type'] ?? 'info',
            'titre' => $data['titre'],
            'message' => $data['message'],
            'statut' => $data['statut'] ?? 'envoyee',
            'cible_type' => $data['cible_type'] ?? null,
            'cible_id' => $data['cible_id'] ?? null,
            'envoyeur_id' => $data['envoyeur_id'] ?? null,
            'entreprise_id' => $data['entreprise_id'] ?? null,
            'url' => $data['url'] ?? null,
            'donnees' => $data['donnees'] ?? null,
        ]);
    }

    /**
     * Créer une notification pour un utilisateur
     */
    public static function pourUtilisateur($user, string $titre, string $message, array $options = []): self
    {
        return self::creer([
            'notifiable_type' => get_class($user),
            'notifiable_id' => $user->id,
            'titre' => $titre,
            'message' => $message,
            'type' => $options['type'] ?? 'info',
            'url' => $options['url'] ?? null,
            'entreprise_id' => $options['entreprise_id'] ?? null,
            'envoyeur_id' => $options['envoyeur_id'] ?? null,
            'donnees' => $options['donnees'] ?? null,
        ]);
    }

    /**
     * Créer une notification pour plusieurs utilisateurs
     */
    public static function pourUtilisateurs($users, string $titre, string $message, array $options = []): array
    {
        $notifications = [];

        foreach ($users as $user) {
            $notifications[] = self::pourUtilisateur($user, $titre, $message, $options);
        }

        return $notifications;
    }
}
