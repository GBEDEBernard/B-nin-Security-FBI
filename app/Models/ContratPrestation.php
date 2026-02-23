<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContratPrestation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'contrats_prestation';

    protected $fillable = [
        // Références
        'entreprise_id',
        'client_id',

        // Identifiant
        'numero_contrat',
        'intitule',

        // Période
        'date_debut',
        'date_fin',
        'est_renouvelable',
        'duree_preavis',

        // Aspects financiers
        'montant_annuel_ht',
        'montant_mensuel_ht',
        'tva',
        'montant_mensuel_ttc',
        'periodicite_facturation',

        // Ressources
        'nombre_agents_requis',
        'postes_requis',

        // Détails prestation
        'description_prestation',
        'horaires_globaux',
        'conditions_particulieres',
        'documents_contractuels',

        // Statut
        'statut',
        'motif_resiliation',
        'date_resiliation',

        // Signataires
        'signataire_client_nom',
        'signataire_client_fonction',
        'signataire_securite_id',

        // Suivi
        'date_signature',
        'cree_par',
        'valide_par',
        'date_validation',
    ];

    protected $casts = [
        'est_renouvelable' => 'boolean',
        'postes_requis' => 'array',
        'horaires_globaux' => 'array',
        'documents_contractuels' => 'array',
        'date_debut' => 'date',
        'date_fin' => 'date',
        'date_signature' => 'date',
        'date_resiliation' => 'date',
        'date_validation' => 'datetime',
        'montant_annuel_ht' => 'decimal:2',
        'montant_mensuel_ht' => 'decimal:2',
        'montant_mensuel_ttc' => 'decimal:2',
        'tva' => 'decimal:2',
    ];

    // ── Constantes ─────────────────────────────────────────────────────────

    public const STATUTS = [
        'brouillon',
        'en_cours',
        'suspendu',
        'termine',
        'resilie'
    ];

    public const PERIODICITES = [
        'mensuel',
        'trimestriel',
        'semestriel',
        'annuel'
    ];

    // ── Relations ───────────────────────────────────────────────────────────

    /**
     * L'entreprise de sécurité
     */
    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(Entreprise::class);
    }

    /**
     * Le client
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Le signataire sécurité (employé)
     */
    public function signataireSecurite(): BelongsTo
    {
        return $this->belongsTo(Employe::class, 'signataire_securite_id');
    }

    /**
     * Le créateur du contrat (employé)
     */
    public function createur(): BelongsTo
    {
        return $this->belongsTo(Employe::class, 'cree_par');
    }

    /**
     * Le validateur du contrat (employé)
     */
    public function validateur(): BelongsTo
    {
        return $this->belongsTo(Employe::class, 'valide_par');
    }

    /**
     * Les sites associés à ce contrat
     */
    public function sites(): HasMany
    {
        return $this->hasMany(SiteContrat::class);
    }

    /**
     * Les affectations d'agents à ce contrat
     */
    public function affectations(): HasMany
    {
        return $this->hasMany(Affectation::class);
    }

    /**
     * Les factures de ce contrat
     */
    public function factures(): HasMany
    {
        return $this->hasMany(Facture::class);
    }

    // ── Scopes ──────────────────────────────────────────────────────────────

    /**
     * Contrats actifs (en cours)
     */
    public function scopeActifs($query)
    {
        return $query->where('statut', 'en_cours');
    }

    /**
     * Contrats par statut
     */
    public function scopeByStatut($query, string $statut)
    {
        return $query->where('statut', $statut);
    }

    // ── Accesseurs ──────────────────────────────────────────────────────────

    /**
     * Statut formaté
     */
    public function getStatutLabelAttribute(): string
    {
        return match ($this->statut) {
            'brouillon' => 'Brouillon',
            'en_cours' => 'En cours',
            'suspendu' => 'Suspendu',
            'termine' => 'Terminé',
            'resilie' => 'Résilié',
            default => 'Inconnu'
        };
    }

    // ── Méthodes métier ─────────────────────────────────────────────────────

    /**
     * Est actif
     */
    public function estActif(): bool
    {
        return $this->statut === 'en_cours';
    }

    /**
     * Est expiré
     */
    public function estExpire(): bool
    {
        return $this->date_fin && $this->date_fin->isPast();
    }

    /**
     * Nombre d'agents affectés
     */
    public function nombreAgentsAffectes(): int
    {
        return $this->affectations()
            ->where('est_actif', true)
            ->count();
    }

    /**
     * Montant total des factures payées
     */
    public function montantPaye(): float
    {
        return $this->factures()
            ->where('statut', 'payee')
            ->sum('montant_paye');
    }

    /**
     * Montant restant à payer
     */
    public function montantRestant(): float
    {
        return $this->factures()
            ->whereIn('statut', ['emise', 'partiellement_payee'])
            ->sum('montant_restant');
    }
}
