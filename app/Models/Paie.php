<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Paie extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'paies';

    protected $fillable = [
        // Références
        'entreprise_id',
        'employe_id',

        // Période
        'mois',
        'annee',

        // Salaire de base
        'salaire_base',

        // Temps de travail
        'jours_travailles',
        'heures_normales',
        'heures_supplementaires',

        // Primes
        'prime_anciennete',
        'prime_panier',
        'prime_transport',
        'prime_risque',
        'prime_performance',
        'autres_primes',
        'indemnites',

        // Brut
        'brut_imposable',

        // Cotisations
        'cnss_part_salariale',
        'cnss_part_patronale',
        'ipps',
        'autres_cotisations',

        // Retenues
        'avance_salaire',
        'absence_deduction',
        'autres_retenues',

        // Net
        'net_a_payer',

        // Suivi
        'date_paiement',
        'statut',
        'bulletin_pdf',
        'details_calcul',

        // Utilisateurs
        'calcule_par',
        'valide_par',
    ];

    protected $casts = [
        'details_calcul' => 'array',
        'date_paiement' => 'date',
        'salaire_base' => 'decimal:2',
        'heures_normales' => 'decimal:2',
        'heures_supplementaires' => 'decimal:2',
        'prime_anciennete' => 'decimal:2',
        'prime_panier' => 'decimal:2',
        'prime_transport' => 'decimal:2',
        'prime_risque' => 'decimal:2',
        'prime_performance' => 'decimal:2',
        'autres_primes' => 'decimal:2',
        'indemnites' => 'decimal:2',
        'brut_imposable' => 'decimal:2',
        'cnss_part_salariale' => 'decimal:2',
        'cnss_part_patronale' => 'decimal:2',
        'ipps' => 'decimal:2',
        'autres_cotisations' => 'decimal:2',
        'avance_salaire' => 'decimal:2',
        'absence_deduction' => 'decimal:2',
        'autres_retenues' => 'decimal:2',
        'net_a_payer' => 'decimal:2',
    ];

    // ── Constantes ─────────────────────────────────────────────────────────

    public const STATUTS = [
        'calcule',
        'valide',
        'paye',
        'annule'
    ];

    // ── Relations ───────────────────────────────────────────────────────────

    /**
     * L'entreprise
     */
    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(Entreprise::class);
    }

    /**
     * L'employé
     */
    public function employe(): BelongsTo
    {
        return $this->belongsTo(Employe::class);
    }

    /**
     * L'utilisateur qui a calculé la paie
     */
    public function calculateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'calcule_par');
    }

    /**
     * L'utilisateur qui a validé la paie
     */
    public function validateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'valide_par');
    }

    // ── Scopes ──────────────────────────────────────────────────────────────

    /**
     * Paies payées
     */
    public function scopePayees($query)
    {
        return $query->where('statut', 'paye');
    }

    /**
     * Paies en attente de paiement
     */
    public function scopeEnAttente($query)
    {
        return $query->whereIn('statut', ['calcule', 'valide']);
    }

    /**
     * Paies par période
     */
    public function scopePeriode($query, int $mois, int $annee)
    {
        return $query->where('mois', $mois)->where('annee', $annee);
    }

    // ── Accesseurs ──────────────────────────────────────────────────────────

    /**
     * Période formatée
     */
    public function getPeriodeLabelAttribute(): string
    {
        $moisNoms = [
            1 => 'Janvier',
            2 => 'Février',
            3 => 'Mars',
            4 => 'Avril',
            5 => 'Mai',
            6 => 'Juin',
            7 => 'Juillet',
            8 => 'Août',
            9 => 'Septembre',
            10 => 'Octobre',
            11 => 'Novembre',
            12 => 'Décembre'
        ];
        return $moisNoms[$this->mois] . ' ' . $this->annee;
    }

    /**
     * Statut formaté
     */
    public function getStatutLabelAttribute(): string
    {
        return match ($this->statut) {
            'calcule' => 'Calculé',
            'valide' => 'Validé',
            'paye' => 'Payé',
            'annule' => 'Annulé',
            default => 'Inconnu'
        };
    }

    // ── Méthodes métier ─────────────────────────────────────────────────────

    /**
     * Est payée
     */
    public function estPayee(): bool
    {
        return $this->statut === 'paye';
    }

    /**
     * Total des primes
     */
    public function totalPrimes(): float
    {
        return $this->prime_anciennete
            + $this->prime_panier
            + $this->prime_transport
            + $this->prime_risque
            + $this->prime_performance
            + $this->autres_primes
            + $this->indemnites;
    }

    /**
     * Total des cotisations
     */
    public function totalCotisations(): float
    {
        return $this->cnss_part_salariale
            + $this->ipps
            + $this->autres_cotisations;
    }

    /**
     * Total des retenues
     */
    public function totalRetenues(): float
    {
        return $this->totalCotisations()
            + $this->avance_salaire
            + $this->absence_deduction
            + $this->autres_retenues;
    }
}
