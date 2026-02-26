<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Abonnement extends Model
{
    use HasFactory;

    protected $table = 'abonnements';

    protected $fillable = [
        // Formule
        'formule',
        'description',

        // Limites
        'nombre_agents_max',
        'nombre_sites_max',

        // Dates
        'date_debut',
        'date_fin',
        'date_fin_essai',

        // Facturation
        'montant_mensuel',
        'montant_total',
        'cycle_facturation',
        'tarif_agents_supplementaires',
        'nombre_agents_inclus',

        // Statut
        'est_active',
        'est_en_essai',
        'est_renouvele_auto',
        'statut',

        // Accès et fonctionnalités
        'fonctionnalites',
        'modules_accessibles',
        'limite_utilisateurs',
        'limite_stockage_go',

        // Paiement
        'mode_paiement',
        'reference_paiement',
        'date_dernier_paiement',
        'date_prochain_paiement',

        // Notes
        'notes',
    ];

    protected $casts = [
        'est_active' => 'boolean',
        'est_en_essai' => 'boolean',
        'est_renouvele_auto' => 'boolean',
        'date_debut' => 'date',
        'date_fin' => 'date',
        'date_fin_essai' => 'date',
        'date_dernier_paiement' => 'date',
        'date_prochain_paiement' => 'date',
        'montant_mensuel' => 'decimal:2',
        'montant_total' => 'decimal:2',
        'tarif_agents_supplementaires' => 'decimal:2',
        'fonctionnalites' => 'array',
        'modules_accessibles' => 'array',
    ];

    // ── Constantes ─────────────────────────────────────────────────────────

    public const FORMULES = [
        'essai' => 'Essai',
        'basic' => 'Basic',
        'standard' => 'Standard',
        'premium' => 'Premium',
        'enterprise' => 'Enterprise',
    ];

    public const STATUTS = [
        'actif' => 'Actif',
        'expire' => 'Expiré',
        'resilie' => 'Résilié',
        'suspendu' => 'Suspendu',
    ];

    public const CYCLES = [
        'mensuel' => 'Mensuel',
        'trimestriel' => 'Trimestriel',
        'semestriel' => 'Semestriel',
        'annuel' => 'Annuel',
    ];

    // ── Relations ───────────────────────────────────────────────────────────

    /**
     * Les entreprises liées à cet abonnement (1:N)
     */
    public function entreprises(): HasMany
    {
        return $this->hasMany(Entreprise::class);
    }

    /**
     * Les factures liées à cet abonnement
     */
    public function factures(): HasMany
    {
        return $this->hasMany(Facture::class);
    }

    // ── Scopes ──────────────────────────────────────────────────────────────

    /**
     * Abonnements actifs
     */
    public function scopeActifs($query)
    {
        return $query->where('est_active', true)->where('statut', 'actif');
    }

    /**
     * Abonnements en période d'essai
     */
    public function scopeEnEssai($query)
    {
        return $query->where('est_en_essai', true);
    }

    /**
     * Abonnements expirés
     */
    public function scopeExpirés($query)
    {
        return $query->where('statut', 'expire');
    }

    /**
     * Abonnements par formule
     */
    public function scopeByFormule($query, string $formule)
    {
        return $query->where('formule', $formule);
    }

    // ── Accesseurs ──────────────────────────────────────────────────────────

    /**
     * Libellé de la formule
     */
    public function getFormuleLabelAttribute(): string
    {
        return self::FORMULES[$this->formule] ?? $this->formule;
    }

    /**
     * Libellé du statut
     */
    public function getStatutLabelAttribute(): string
    {
        return self::STATUTS[$this->statut] ?? $this->statut;
    }

    /**
     * Libellé du cycle de facturation
     */
    public function getCycleLabelAttribute(): string
    {
        return self::CYCLES[$this->cycle_facturation] ?? $this->cycle_facturation;
    }

    /**
     * Est-ce que l'abonnement est valide
     */
    public function getEstValideAttribute(): bool
    {
        // Si en période d'essai
        if ($this->est_en_essai && $this->date_fin_essai) {
            return $this->date_fin_essai->isFuture();
        }

        // Si pas de date de fin, c'est valide
        if (!$this->date_fin) {
            return true;
        }

        // Vérifier la date de fin
        return $this->date_fin->isFuture() && $this->est_active;
    }

    /**
     * Jours restants avant expiration
     */
    public function getJoursRestantsAttribute(): ?int
    {
        if ($this->est_en_essai && $this->date_fin_essai) {
            return now()->diffInDays($this->date_fin_essai, false);
        }

        if ($this->date_fin) {
            return now()->diffInDays($this->date_fin, false);
        }

        return null;
    }

    /**
     * Montant total sur la période
     */
    public function getMontantPeriodeAttribute(): float
    {
        return match ($this->cycle_facturation) {
            'mensuel' => $this->montant_mensuel,
            'trimestriel' => $this->montant_mensuel * 3,
            'semestriel' => $this->montant_mensuel * 6,
            'annuel' => $this->montant_mensuel * 12,
            default => $this->montant_mensuel,
        };
    }

    // ── Méthodes métier ─────────────────────────────────────────────────────

    /**
     * Vérifier si l'abonnement est valide
     */
    public function estValide(): bool
    {
        return $this->est_valide;
    }

    /**
     * Renouveler l'abonnement
     */
    public function renouveler(array $data): self
    {
        $this->update([
            'date_debut' => $data['date_debut'] ?? now(),
            'date_fin' => $data['date_fin'],
            'montant_mensuel' => $data['montant_mensuel'] ?? $this->montant_mensuel,
            'cycle_facturation' => $data['cycle_facturation'] ?? $this->cycle_facturation,
            'est_active' => true,
            'est_en_essai' => false,
            'statut' => 'actif',
        ]);

        return $this;
    }

    /**
     * Suspendre l'abonnement
     */
    public function suspendre(): self
    {
        $this->update([
            'est_active' => false,
            'statut' => 'suspendu',
        ]);

        return $this;
    }

    /**
     * Activer l'abonnement
     */
    public function activer(): self
    {
        $this->update([
            'est_active' => true,
            'statut' => 'actif',
        ]);

        return $this;
    }

    /**
     * Résilier l'abonnement
     */
    public function resilier(): self
    {
        $this->update([
            'est_active' => false,
            'statut' => 'resilie',
        ]);

        return $this;
    }

    /**
     * Mettre en période d'essai
     */
    public function mettreEnEssai(\Carbon\Carbon $dateFinEssai): self
    {
        $this->update([
            'est_en_essai' => true,
            'date_fin_essai' => $dateFinEssai,
            'est_active' => true,
            'statut' => 'actif',
        ]);

        return $this;
    }

    /**
     * Fin de la période d'essai - basculer vers abonnement normal
     */
    public function cloturerEssai(string $formule, \Carbon\Carbon $dateFin, float $montantMensuel): self
    {
        $this->update([
            'est_en_essai' => false,
            'formule' => $formule,
            'date_fin_essai' => null,
            'date_debut' => now(),
            'date_fin' => $dateFin,
            'montant_mensuel' => $montantMensuel,
            'est_active' => true,
            'statut' => 'actif',
        ]);

        return $this;
    }

    /**
     * Peut ajouter un agent (dans la limite)
     */
    public function peutAjouterAgent(int $nombreAgentsActuels): bool
    {
        return $nombreAgentsActuels < $this->nombre_agents_max;
    }

    /**
     * Nombre d'agents restants disponibles
     */
    public function agentsRestants(int $nombreAgentsActuels): int
    {
        return max(0, $this->nombre_agents_max - $nombreAgentsActuels);
    }

    /**
     * Mettre à jour les dates de paiement
     */
    public function mettreAJourDatesPaiement(): self
    {
        if (!$this->date_prochain_paiement) {
            return $this;
        }

        $prochaineDate = match ($this->cycle_facturation) {
            'mensuel' => $this->date_prochain_paiement->addMonth(),
            'trimestriel' => $this->date_prochain_paiement->addMonths(3),
            'semestriel' => $this->date_prochain_paiement->addMonths(6),
            'annuel' => $this->date_prochain_paiement->addYear(),
            default => $this->date_prochain_paiement->addMonth(),
        };

        $this->update([
            'date_dernier_paiement' => $this->date_prochain_paiement,
            'date_prochain_paiement' => $prochaineDate,
        ]);

        return $this;
    }
}
