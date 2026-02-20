<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Vehicle extends Model
{
    protected $fillable = [
        'id_locataire',
        'id_agence',
        'numero_enregistrement',
        'marque',
        'modele',
        'annee',
        'type',
        'couleur',
        'vin',
        'plaque_immatriculation',
        'expiration_enregistrement',
        'expiration_assurance',
        'tarif_quotidien',
        'statut',
        'notes',
        'metadonnees',
    ];

    protected function casts(): array
    {
        return [
            'metadonnees' => 'array',
            'expiration_enregistrement' => 'date',
            'expiration_assurance' => 'date',
            'tarif_quotidien' => 'decimal:2',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class);
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }
}
