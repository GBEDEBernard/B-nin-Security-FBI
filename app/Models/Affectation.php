<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Affectation extends Model
{
    protected $table = 'affectations';

    protected $fillable = [
        'entreprise_id',
        'employe_id',
        'contrat_prestation_id',
        'site_client_id',
        'role_site',
        'date_debut',
        'date_fin',
        'date_affectation',
        'date_fin_affectation',
        'horaire_debut',
        'horaire_fin',
        'jours_travail',
        'responsabilites',
        'superviseur_direct_id',
        'controleur_id',
        'statut',
        'motif_fin',
        'affecte_par',
    ];

    public function employe(): BelongsTo
    {
        return $this->belongsTo(Employe::class);
    }

    public function contrat(): BelongsTo
    {
        return $this->belongsTo(ContratPrestation::class, 'contrat_prestation_id');
    }

    public function siteClient(): BelongsTo
    {
        return $this->belongsTo(SiteClient::class, 'site_client_id');
    }
}
