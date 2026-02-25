<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropositionContrat extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'proposition_contrats';

    protected $fillable = [
        // Informations de l'entreprise interessée
        'nom_entreprise',
        'nom_commercial',
        'forme_juridique',
        'email',
        'telephone',
        'adresse',
        'ville',
        'pays',

        // Informations légales
        'numero_registre',
        'numeroIdentificationFiscale',
        'numeroContribuable',

        // Représentant légal
        'representant_nom',
        'representant_fonction',
        'representant_email',
        'representant_telephone',

        // Besoins
        'type_service',
        'nombre_agents',
        'description_besoins',
        'budget_approx',

        // Statut de la proposition
        'statut',
        'date_soumission',
        'date_signature',
        'date_rejet',
        'motif_rejet',

        // Contrat PDF
        'fichier_contrat_signe',
        'contrat_pdf_path',

        // Notes internes
        'notes',

        // Admin qui traite
        'traite_par',
        'date_traitement',
    ];

    protected $casts = [
        'date_soumission' => 'datetime',
        'date_signature' => 'datetime',
        'date_rejet' => 'datetime',
        'date_traitement' => 'datetime',
        'nombre_agents' => 'integer',
        'budget_approx' => 'decimal:2',
    ];

    // ── Constantes ─────────────────────────────────────────────────────────

    public const STATUTS = [
        'soumis' => 'Soumis',
        'en_cours' => 'En cours de traitement',
        'contrat_envoye' => 'Contrat envoyé',
        'en_attente_signature' => 'En attente de signature',
        'signe' => 'Signé',
        'rejete' => 'Rejeté',
        'expire' => 'Expiré',
    ];

    public const TYPES_SERVICE = [
        'garde_renforcee' => 'Garde renforcée',
        'garde_simple' => 'Garde simple',
        'surveillance_electronique' => 'Surveillance électronique',
        'garde_evenementiel' => 'Garde événementiel',
        'conseil' => 'Conseil en sécurité',
        'autre' => 'Autre',
    ];

    // ── Relations ─────────────────────────────────────────────────────────

    /**
     * L'utilisateur qui traite la proposition
     */
    public function traiterPar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'traite_par');
    }

    /**
     * L'entreprise créée à partir de cette proposition (si signée)
     */
    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(Entreprise::class);
    }

    // ── Scopes ──────────────────────────────────────────────────────────

    public function scopeSoumis($query)
    {
        return $query->where('statut', 'soumis');
    }

    public function scopeEnAttente($query)
    {
        return $query->whereIn('statut', ['soumis', 'en_cours', 'contrat_envoye', 'en_attente_signature']);
    }

    public function scopeSignes($query)
    {
        return $query->where('statut', 'signe');
    }

    // ── Accesseurs ──────────────────────────────────────────────────────

    public function getStatutLabelAttribute(): string
    {
        return self::STATUTS[$this->statut] ?? 'Inconnu';
    }

    public function getTypeServiceLabelAttribute(): string
    {
        return self::TYPES_SERVICE[$this->type_service] ?? 'Non spécifié';
    }

    public function getStatutBadgeClassAttribute(): string
    {
        return match ($this->statut) {
            'soumis' => 'secondary',
            'en_cours' => 'info',
            'contrat_envoye' => 'primary',
            'en_attente_signature' => 'warning',
            'signe' => 'success',
            'rejete' => 'danger',
            'expire' => 'dark',
            default => 'secondary',
        };
    }

    // ── Méthodes ───────────────────────────────────────────────────────

    public function estSigne(): bool
    {
        return $this->statut === 'signe';
    }

    public function peutEtreSigne(): bool
    {
        return in_array($this->statut, ['contrat_envoye', 'en_attente_signature']);
    }

    public function genererNumeroProposition(): string
    {
        $prefix = 'PROP-' . date('Y');
        $dernier = self::where('id', 'like', "{$prefix}%")->orderBy('id', 'desc')->first();

        if ($dernier) {
            $numero = (int) substr($dernier->id, -4) + 1;
        } else {
            $numero = 1;
        }

        return $prefix . str_pad($numero, 4, '0', STR_PAD_LEFT);
    }
}
