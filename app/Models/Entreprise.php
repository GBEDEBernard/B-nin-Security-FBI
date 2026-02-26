<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Entreprise extends Model
{
    use HasFactory, SoftDeletes;

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
        'formule',
        'nombre_agents_max',
        'nombre_sites_max',
        'date_debut_contrat',
        'date_fin_contrat',
        'montant_mensuel',
        'cycle_facturation',
        'est_active',
        'est_en_essai',
        'date_fin_essai',
        'parametres',
        'notes',
    ];

    protected $casts = [
        'parametres'        => 'array',
        'est_active'        => 'boolean',
        'est_en_essai'      => 'boolean',
        'date_debut_contrat' => 'date',
        'date_fin_contrat'  => 'date',
        'date_fin_essai'    => 'date',
        'montant_mensuel'   => 'decimal:2',
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

    // ── Scopes ───────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('est_active', true);
    }
    public function scopeEnEssai($query)
    {
        return $query->where('est_en_essai', true);
    }
    public function scopeByFormule($query, string $formule)
    {
        return $query->where('formule', $formule);
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
        if ($this->est_en_essai && $this->date_fin_essai) {
            return $this->date_fin_essai->isFuture();
        }
        if (!$this->date_fin_contrat) return true;
        return $this->date_fin_contrat->isFuture();
    }

    public function nombreAgentsActifs(): int
    {
        return $this->employes()->where('est_actif', true)->where('statut', 'en_poste')->count();
    }

    public function peutAjouterAgent(): bool
    {
        return $this->nombreAgentsActifs() < ($this->nombre_agents_max ?? 0);
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
