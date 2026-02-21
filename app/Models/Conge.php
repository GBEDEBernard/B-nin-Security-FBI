<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Conge extends Model
{
    protected $table = 'conges';

    protected $fillable = [
        'entreprise_id',
        'employe_id',
        'type_conge',
        'date_debut',
        'date_fin',
        'nombre_jours',
        'est_deduit_solde',
        'motif',
        'piece_justificative',
        'remplacant_id',
        'consignes_remplacement',
        'demande_par',
        'valide_par',
        'statut',
        'commentaire_validation',
        'date_validation',
    ];

    public function employe(): BelongsTo
    {
        return $this->belongsTo(Employe::class);
    }

    public function remplacant(): BelongsTo
    {
        return $this->belongsTo(Employe::class, 'remplacant_id');
    }
}
