<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Document extends Model
{
    protected $fillable = [
        'id_locataire',
        'documentable_type',
        'documentable_id',
        'type',
        'chemin_fichier',
        'nom_fichier_original',
        'type_mime',
        'taille_fichier',
        'date_expiration',
        'notes',
        'statut',
        'metadonnees',
    ];

    protected function casts(): array
    {
        return [
            'metadonnees' => 'array',
            'date_expiration' => 'date',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function documentable(): MorphTo
    {
        return $this->morphTo();
    }
}
