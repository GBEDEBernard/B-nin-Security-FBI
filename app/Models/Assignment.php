<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assignment extends Model
{
    protected $fillable = [
        'id_locataire',
        'id_agent',
        'id_quart',
        'statut',
        'heure_arrivee',
        'heure_depart',
        'notes',
        'tarif_reel',
        'metadonnees',
    ];

    protected function casts(): array
    {
        return [
            'metadonnees' => 'array',
            'heure_arrivee' => 'datetime',
            'heure_depart' => 'datetime',
            'tarif_reel' => 'decimal:2',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }
}
