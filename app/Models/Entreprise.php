<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

class Entreprise extends Model
{
    use HasFactory, SoftDeletes, Notifiable;

    protected $table = 'entreprises';

    protected $fillable = [
        'nom_entreprise',
        'slug',
        'nom_commercial',
        'forme_juridique',
        'numero_registre',
        'numeroIdentificationFiscale',
        'numeroContribuable',
        'email',
        'telephone',
        'telephone_alternatif',
        'adresse',
        'ville',
        'pays',
        'code_postal',
        'nom_representant_legal',
        'email_representant_legal',
        'telephone_representant_legal',
        'logo',
        'couleur_primaire',
        'couleur_secondaire',
        'abonnement_id',
        'est_active',
        'parametres',
        'notes',
    ];

    protected $casts = [
        'parametres'        => 'array',
        'est_active'        => 'boolean',
    ];

    // ── Relations ────────────────────────────────────────────────────────────

    public function employes(): HasMany
    {
        return $this->hasMany(Employe::class);
    }
    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }
    public function contratsPrestation(): HasMany
    {
        return $this->hasMany(ContratPrestation::class);
    }
    public function factures(): HasMany
    {
        return $this->hasMany(Facture::class);
    }
    public function paies(): HasMany
    {
        return $this->hasMany(Paie::class);
    }
    public function incidents(): HasMany
    {
        return $this->hasMany(Incident::class);
    }
    public function utilisateurs(): HasMany
    {
        return $this->hasMany(User::class);
    }

    // Relation vers abonnement (appartient à un abonnement)
    public function abonnement(): BelongsTo
    {
        return $this->belongsTo(Abonnement::class);
    }

    public function propositions(): HasMany
    {
        return $this->hasMany(PropositionContrat::class);
    }

    // ── Scopes ───────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('est_active', true);
    }
    public function scopeEnEssai($query)
    {
        return $query->whereHas('abonnement', fn($q) => $q->where('est_en_essai', true));
    }
    public function scopeByFormule($query, string $formule)
    {
        return $query->whereHas('abonnement', fn($q) => $q->where('formule', $formule));
    }

    // ── Accesseurs ───────────────────────────────────────────────────────────

    public function getLogoUrlAttribute(): string
    {
        return $this->logo
            ? asset('storage/' . $this->logo)
            : asset('images/logo-defaut.svg');
    }

    public function getNomAffichageAttribute(): string
    {
        return $this->nom_commercial ?? $this->nom_entreprise;
    }

    // ── Méthodes métier ──────────────────────────────────────────────────────

    public function abonnementEstValide(): bool
    {
        return $this->abonnement?->est_valide ?? false;
    }

    public function nombreAgentsActifs(): int
    {
        return $this->employes()->where('est_actif', true)->where('statut', 'en_poste')->count();
    }

    public function peutAjouterAgent(): bool
    {
        if (!$this->abonnement) return false;
        return $this->abonnement->peutAjouterAgent($this->nombreAgentsActifs());
    }

    // ── Accesseurs de délégation vers l'abonnement ────────────────────────────

    public function getFormuleAttribute($value): ?string
    {
        return $value ?? $this->abonnement?->formule;
    }

    public function getNombreAgentsMaxAttribute($value): int
    {
        return $value ?? $this->abonnement?->nombre_agents_max ?? 0;
    }

    public function getNombreSitesMaxAttribute($value): int
    {
        return $value ?? $this->abonnement?->nombre_sites_max ?? 0;
    }

    public function getMontantMensuelAttribute($value): float
    {
        return $value ?? $this->abonnement?->montant_mensuel ?? 0;
    }

    public function getCycleFacturationAttribute($value): ?string
    {
        return $value ?? $this->abonnement?->cycle_facturation;
    }

    public function getEstEnEssaiAttribute($value): bool
    {
        return $value ?? $this->abonnement?->est_en_essai ?? false;
    }

    public function getDateFinEssaiAttribute($value): ?\Carbon\Carbon
    {
        return $value ?? $this->abonnement?->date_fin_essai;
    }

    public function getDateDebutContratAttribute($value): ?\Carbon\Carbon
    {
        return $value ?? $this->abonnement?->date_debut;
    }

    public function getDateFinContratAttribute($value): ?\Carbon\Carbon
    {
        return $value ?? $this->abonnement?->date_fin;
    }

    public function getParametre(string $cle, mixed $defaut = null): mixed
    {
        return data_get($this->parametres, $cle, $defaut);
    }

    public function getRayonGpsDefaut(): int
    {
        return $this->getParametre('rayon_gps_defaut', 300);
    }
    public function getFuseauHoraire(): string
    {
        return $this->getParametre('fuseau_horaire', 'Africa/Porto-Novo');
    }
}
