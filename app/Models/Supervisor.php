<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Supervisor extends Model
{
    protected $fillable = [
        'id_locataire',
        'id_utilisateur',
        'id_agence',
        'nom_complet',
        'email',
        'telephone',
        'numero_id',
        'type_id',
        'date_naissance',
        'genre',
        'adresse',
        'ville',
        'code_postal',
        'salaire_par_mois',
        'date_embauche',
        'statut',
        'date_fin',
        'metadonnees',
    ];

    protected function casts(): array
    {
        return [
            'metadonnees' => 'array',
            'date_naissance' => 'date',
            'date_embauche' => 'date',
            'date_fin' => 'date',
            'salaire_par_mois' => 'decimal:2',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class);
    }
}
