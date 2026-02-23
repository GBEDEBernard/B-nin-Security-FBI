<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Entreprise extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'entreprises';

    protected $fillable = [
        // Identité de l'entreprise
        'nom_entreprise',
        'slug',
        'nom_commercial',
        'forme_juridique',

        // Informations légales et fiscales
        'numero_registre',
        'numeroIdentificationFiscale',
        'numeroContribuable',

        // Contact principal
        'email',
        'telephone',
        'telephone_alternatif',

        // Adresse
        'adresse',
        'ville',
        'pays',
        'code_postal',

        // Représentant légal
        'nom_representant_legal',
        'email_representant_legal',
        'telephone_representant_legal',

        // Identité visuelle
        'logo',
        'couleur_primaire',
        'couleur_secondaire',

        // Abonnement et facturation
        'formule',
        'nombre_agents_max',
        'nombre_sites_max',
        'date_debut_contrat',
        'date_fin_contrat',
        'montant_mensuel',
        'cycle_facturation',

        // Statut
        'est_active',
        'est_en_essai',
        'date_fin_essai',

        // Configuration
        'parametres',

        // Notes
        'notes',
    ];

    protected $casts = [
        'parametres' => 'array',
        'est_active' => 'boolean',
        'est_en_essai' => 'boolean',
        'date_debut_contrat' => 'date',
        'date_fin_contrat' => 'date',
        'date_fin_essai' => 'date',
        'montant_mensuel' => 'decimal:2',
    ];

    // ── Relations ───────────────────────────────────────────────────────────

    /**
     * Les employés de l'entreprise de sécurité
     */
    public function employes(): HasMany
    {
        return $this->hasMany(Employe::class);
    }

    /**
     * Les clients (particuliers, entreprises, institutions)
     */
    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }

    /**
     * Les contrats de prestation avec les clients
     */
    public function contratsPrestation(): HasMany
    {
        return $this->hasMany(ContratPrestation::class);
    }

    /**
     * Les factures émises
     */
    public function factures(): HasMany
    {
        return $this->hasMany(Facture::class);
    }

    /**
     * Les bulletins de paie
     */
    public function paies(): HasMany
    {
        return $this->hasMany(Paie::class);
    }

    /**
     * Les incidents reportés
     */
    public function incidents(): HasMany
    {
        return $this->hasMany(Incident::class);
    }

    /**
     * Les utilisateurs de l'application
     */
    public function utilisateurs(): HasMany
    {
        return $this->hasMany(User::class);
    }

    // ── Scopes ──────────────────────────────────────────────────────────────

    /**
     * Entreprises actives
     */
    public function scopeActive($query)
    {
        return $query->where('est_active', true);
    }

    /**
     * Entreprises en période d'essai
     */
    public function scopeEnEssai($query)
    {
        return $query->where('est_en_essai', true);
    }

    /**
     * Entreprises par formule
     */
    public function scopeByFormule($query, string $formule)
    {
        return $query->where('formule', $formule);
    }

    // ── Accesseurs ──────────────────────────────────────────────────────────

    /**
     * URL du logo
     */
    public function getLogoUrlAttribute(): string
    {
        return $this->logo ? asset('storage/' . $this->logo) : asset('images/logo-defaut.png');
    }

    /**
     * Nom d'affichage (nom commercial ou nom de l'entreprise)
     */
    public function getNomAffichageAttribute(): string
    {
        return $this->nom_commercial ?? $this->nom_entreprise;
    }

    // ── Méthodes métier ─────────────────────────────────────────────────────

    /**
     * Vérifie si l'abonnement est valide
     */
    public function abonnementEstValide(): bool
    {
        if ($this->est_en_essai && $this->date_fin_essai) {
            return $this->date_fin_essai->isFuture();
        }

        if (!$this->date_fin_contrat) {
            return true; // Pas de date de fin = abonnement valide
        }

        return $this->date_fin_contrat->isFuture();
    }

    /**
     * Nombre d'agents actifs
     */
    public function nombreAgentsActifs(): int
    {
        return $this->employes()->where('est_actif', true)->where('statut', 'en_poste')->count();
    }

    /**
     * Peut ajouter un nouvel agent
     */
    public function peutAjouterAgent(): bool
    {
        return $this->nombreAgentsActifs() < $this->nombre_agents_max;
    }

    /**
     * Nombre de sites actifs (clients avec sites)
     */
    public function nombreSitesActifs(): int
    {
        return $this->clients()
            ->where('est_actif', true)
            ->withCount('sites')
            ->get()
            ->sum('sites_count');
    }

    /**
     * Obtenir un paramètre de configuration
     */
    public function getParametre(string $cle, mixed $defaut = null): mixed
    {
        return data_get($this->parametres, $cle, $defaut);
    }

    /**
     * Rayon GPS par défaut
     */
    public function getRayonGpsDefaut(): int
    {
        return $this->getParametre('rayon_gps_defaut', 300);
    }

    /**
     * Fuseau horaire
     */
    public function getFuseauHoraire(): string
    {
        return $this->getParametre('fuseau_horaire', 'Africa/Porto-Novo');
    }
}
