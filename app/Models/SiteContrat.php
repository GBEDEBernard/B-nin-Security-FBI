<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiteContrat extends Model
{
    protected $table = 'sites_contrats';
    protected $fillable = [
        'contrat_prestation_id',
        'site_client_id',
        'nombre_agents_site',
        'horaires_site',
        'consignes_site',
    ];

    public function contrat(): BelongsTo
    {
        return $this->belongsTo(ContratPrestation::class, 'contrat_prestation_id');
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(SiteClient::class, 'site_client_id');
    }
}
