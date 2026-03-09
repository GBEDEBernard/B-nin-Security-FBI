<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}
