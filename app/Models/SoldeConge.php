<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SoldeConge extends Model
{
    protected $table = 'soldes_conges';

    protected $fillable = [
        'entreprise_id',
        'employe_id',
        'annee',
        'jours_acquis',
        'jours_pris',
        'jours_restants',
        'jours_maladie_utilises',
        'jours_exceptionnels_utilises',
    ];

    public function employe(): BelongsTo
    {
        return $this->belongsTo(Employe::class);
    }
}
