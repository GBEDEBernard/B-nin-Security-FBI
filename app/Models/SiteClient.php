<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
     * Contrats liés à ce site (via la table pivot site_contrat ou ContratPrestation directement)
     * Adaptez selon votre structure réelle.
     */
    public function contrats(): HasMany
    {
        return $this->hasMany(ContratPrestation::class, 'site_id');
    }

    /**
     * Affectations d'agents sur ce site
     */
    public function affectations(): HasMany
    {
        return $this->hasMany(Affectation::class, 'site_id');
    }
}