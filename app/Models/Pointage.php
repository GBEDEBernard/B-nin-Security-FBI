<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;

class Pointage extends Model
{
    protected $table = 'pointages';

    protected $fillable = [
        'entreprise_id',
        'employe_id',
        'affectation_id',
        'site_client_id',
        'date_pointage',
        'heure_arrivee',
        'latitude_arrivee',
        'longitude_arrivee',
        'photo_arrivee',
        'heure_depart',
        'latitude_depart',
        'longitude_depart',
        'photo_depart',
        'pauses',
        'heures_travaillees',
        'heures_supplementaires',
        'mode_pointage',
        'statut',
        'commentaire',
        'anomalie',
        'valide_par',
        'date_validation',
        'commentaire_validation',
    ];

    protected $casts = [
        'pauses' => 'array',
        'date_pointage' => 'date',
        'heure_arrivee' => 'datetime',
        'heure_depart' => 'datetime',
    ];

    public function affectation(): BelongsTo
    {
        return $this->belongsTo(Affectation::class);
    }

    public function employe(): BelongsTo
    {
        return $this->belongsTo(Employe::class);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(SiteClient::class, 'site_client_id');
    }

    public function validePar(): BelongsTo
    {
        return $this->belongsTo(Employe::class, 'valide_par');
    }
}
