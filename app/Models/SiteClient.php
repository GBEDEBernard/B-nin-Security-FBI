<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class SiteClient extends Model
{
    protected $table = 'sites_clients';

    protected $fillable = [
        'entreprise_id',
        'client_id',
        'nom_site',
        'code_site',
        'adresse',
        'ville',
        'commune',
        'quartier',
        'latitude',
        'longitude',
        'rayon_pointage',
        'contact_nom',
        'contact_telephone',
        'contact_email',
        'niveau_risque',
        'equipements',
        'consignes_specifiques',
        'photos',
        'est_actif',
        'notes',
    ];

    protected $casts = [
        'est_actif'    => 'boolean',
        'equipements'  => 'array',
        'photos'       => 'array',
        'latitude'     => 'float',
        'longitude'    => 'float',
    ];

    // ── Relations ────────────────────────────────────────────────────────────

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(Entreprise::class);
    }

    /**
     * Contrats liés à ce site (via la table pivot sites_contrats)
     */
    public function contrats(): BelongsToMany
    {
        return $this->belongsToMany(ContratPrestation::class, 'sites_contrats', 'site_client_id', 'contrat_prestation_id')
            ->withPivot('nombre_agents_site', 'horaires_site', 'consignes_site')
            ->withTimestamps();
    }

    /**
     * Affectations d'agents sur ce site
     */
    public function affectations(): HasMany
    {
        return $this->hasMany(Affectation::class, 'site_client_id');
    }

    /**
     * Agents affectés sur ce site (avec relation employée)
     */
    public function agentsAffectes()
    {
        return $this->hasMany(Affectation::class, 'site_client_id')
            ->with('employe')
            ->where('statut', 'active');
    }

    /**
     * Nombre d'agents requis pour ce site (via les contrats)
     */
    public function agentsRequis(): int
    {
        // Utiliser une requête avec join pour accéder à la colonne pivot
        return \DB::table('sites_contrats')
            ->where('site_client_id', $this->id)
            ->join('contrats_prestation', 'sites_contrats.contrat_prestation_id', '=', 'contrats_prestation.id')
            ->where('contrats_prestation.statut', 'en_cours')
            ->sum('sites_contrats.nombre_agents_site');
    }

    /**
     * Nombre d'agents actuellement affectés (actifs)
     */
    public function agentsAffectesCount(): int
    {
        return $this->affectations()
            ->where('statut', 'active')
            ->count();
    }

    /**
     * Nombre d'agents manquants
     */
    public function agentsManquants(): int
    {
        $requis = $this->agentsRequis();
        $affectes = $this->agentsAffectesCount();
        return max(0, $requis - $affectes);
    }

    /**
     * Vérifier si le site a besoin d'agents
     */
    public function aBesoinAgents(): bool
    {
        return $this->agentsManquants() > 0;
    }

    /**
     * Pourcentage de couverture en agents
     */
    public function pourcentageCouverture(): int
    {
        $requis = $this->agentsRequis();
        if ($requis === 0) return 100;

        $affectes = $this->agentsAffectesCount();
        return min(100, round(($affectes / $requis) * 100));
    }
}
