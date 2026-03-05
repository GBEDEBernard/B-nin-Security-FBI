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
        'envoyeur_type',   // ✅ ajouté ici
        'entreprise_id',
        'url',
        'donnees',
        'lu_le',
    ];

    protected $casts = [
        'donnees' => 'array',
        'lu_le'   => 'datetime',
    ];

    // ── Relations ────────────────────────────────────────────────────

    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }

    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(Entreprise::class);
    }

    public function envoyeur(): MorphTo
    {
        return $this->morphTo('envoyeur');
    }

    public function cible(): MorphTo
    {
        return $this->morphTo('cible');
    }

    // ── Scopes ───────────────────────────────────────────────────────

    public function scopeNonLues($query)
    {
        return $query->whereNull('lu_le');
    }

    public function scopePourEntreprise($query, $entrepriseId)
    {
        return $query->where('entreprise_id', $entrepriseId);
    }

    public function scopeGlobales($query)
    {
        return $query->whereNull('entreprise_id');
    }

    public function scopePourUtilisateur($query, $user)
    {
        return $query->where('notifiable_type', get_class($user))
                     ->where('notifiable_id', $user->id);
    }

    // ── Helpers ──────────────────────────────────────────────────────

    public function marquerLue(): self
    {
        if (!$this->lu_le) {
            $this->update(['lu_le' => now()]);
        }
        return $this;
    }

    public function marquerNonLue(): self
    {
        $this->update(['lu_le' => null]);
        return $this;
    }

    public function estLue(): bool
    {
        return !is_null($this->lu_le);
    }

    public function estNonLue(): bool
    {
        return is_null($this->lu_le);
    }

    // ── Accesseurs ───────────────────────────────────────────────────

    public function getTypeBadgeAttribute(): string
    {
        return match ($this->type) {
            'info'    => 'bg-info',
            'success' => 'bg-success',
            'warning' => 'bg-warning',
            'error'   => 'bg-danger',
            default   => 'bg-secondary',
        };
    }

    public function getTypeIconAttribute(): string
    {
        return match ($this->type) {
            'info'    => 'bi-info-circle-fill',
            'success' => 'bi-check-circle-fill',
            'warning' => 'bi-exclamation-triangle-fill',
            'error'   => 'bi-x-circle-fill',
            default   => 'bi-bell-fill',
        };
    }

    public function getTempsEcouleAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    // ── Méthodes statiques ───────────────────────────────────────────

    /**
     * Envoyer une notification à un utilisateur/employé/client
     */
    public static function envoyer(object $destinataire, string $titre, string $message, array $options = []): self
    {
        return self::create([
            'notifiable_type' => get_class($destinataire),
            'notifiable_id'   => $destinataire->id,
            'type'            => $options['type'] ?? 'info',
            'titre'           => $titre,
            'message'         => $message,
            'statut'          => 'envoyee',
            'url'             => $options['url'] ?? null,
            'entreprise_id'   => $options['entreprise_id'] ?? null,
            'envoyeur_id'     => $options['envoyeur_id'] ?? null,
            'envoyeur_type'   => $options['envoyeur_type'] ?? null,
            'donnees'         => $options['donnees'] ?? null,
        ]);
    }

    /**
     * Envoyer la même notification à plusieurs destinataires
     */
    public static function envoyerGroupe(iterable $destinataires, string $titre, string $message, array $options = []): int
    {
        $count = 0;
        foreach ($destinataires as $destinataire) {
            self::envoyer($destinataire, $titre, $message, $options);
            $count++;
        }
        return $count;
    }
}