<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Client extends Model
{
    use HasFactory, SoftDeletes, Notifiable, HasApiTokens;

    protected $table = 'clients';

    protected $fillable = [
        // Entreprise (référence)
        'entreprise_id',

        // Type de client
        'type_client', // particulier, entreprise, institution

        // Si particulier
        'nom',
        'prenoms',

        // Si entreprise/institution
        'raison_sociale',
        'nif',
        'rc',

        // Contact principal
        'email',
        'password', // Ajouté pour authentification
        'telephone',
        'telephone_secondaire',
        'contact_principal_nom',
        'contact_principal_fonction',

        // Adresse principale
        'adresse',
        'ville',
        'pays',

        // Statut
        'est_actif',
        'est_connecte', // Pour suivi connexion
        'last_login_at',
        'last_login_ip',
        'notes',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'est_actif' => 'boolean',
        'est_connecte' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    // ── Constantes ─────────────────────────────────────────────────────────

    public const TYPES = [
        'particulier',
        'entreprise',
        'institution'
    ];

    // ── Relations ───────────────────────────────────────────────────────────

    /**
     * L'entreprise de sécurité qui gère ce client
     */
    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(Entreprise::class);
    }

    /**
     * Les sites de ce client
     */
    public function sites(): HasMany
    {
        return $this->hasMany(SiteClient::class);
    }

    /**
     * Les contrats de prestation avec ce client
     */
    public function contrats(): HasMany
    {
        return $this->hasMany(ContratPrestation::class);
    }

    /**
     * Les factures émises à ce client
     */
    public function factures(): HasMany
    {
        return $this->hasMany(Facture::class);
    }

    // ── Scopes ──────────────────────────────────────────────────────────────

    /**
     * Clients actifs
     */
    public function scopeActifs($query)
    {
        return $query->where('est_actif', true);
    }

    /**
     * Clients par type
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('type_client', $type);
    }

    // ── Accesseurs ──────────────────────────────────────────────────────────

    /**
     * Nom d'affichage du client
     */
    public function getNomAffichageAttribute(): string
    {
        if ($this->type_client === 'particulier') {
            return $this->prenoms . ' ' . $this->nom;
        }
        return $this->raison_sociale ?? $this->nom;
    }

    /**
     * Type de client formaté
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type_client) {
            'particulier' => 'Particulier',
            'entreprise' => 'Entreprise',
            'institution' => 'Institution',
            default => 'Inconnu'
        };
    }

    // ── Méthodes métier ─────────────────────────────────────────────────────

    /**
     * Nombre de sites actifs
     */
    public function nombreSitesActifs(): int
    {
        return $this->sites()->where('est_actif', true)->count();
    }

    /**
     * A un contrat actif
     */
    public function aContratActif(): bool
    {
        return $this->contrats()
            ->whereIn('statut', ['en_cours'])
            ->exists();
    }

    // ── Authentification ───────────────────────────────────────────────────

    /**
     * Peut se connecter
     */
    public function peutSeConnecter(): bool
    {
        return $this->est_actif
            && !empty($this->email)
            && !empty($this->password);
    }

    /**
     * Route du dashboard
     */
    public function getDashboardRoute(): string
    {
        return 'admin.client.index';
    }

    /**
     * URL du dashboard
     */
    public function getDashboardUrl(): string
    {
        return route($this->getDashboardRoute());
    }

    /**
     * Enregistrer la connexion
     */
    public function enregistrerConnexion(string $ip): void
    {
        $this->update([
            'est_connecte' => true,
            'last_login_at' => now(),
            'last_login_ip' => $ip,
        ]);
    }

    /**
     * Enregistrer la déconnexion
     */
    public function enregistrerDeconnexion(): void
    {
        $this->update([
            'est_connecte' => false,
        ]);
    }
}
