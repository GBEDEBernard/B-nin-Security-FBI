<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
