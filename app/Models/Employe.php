<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employe extends Model
{
    protected $table = 'employes';

    protected $fillable = [
        'entreprise_id',
        'matricule',
        'civilite',
        'nom',
        'prenoms',
        'email',
        'password',
        'cni',
        'date_naissance',
        'lieu_naissance',
        'telephone',
        'telephone_urgence',
        'photo',
        'adresse',
        'categorie',
        'poste',
        'niveau_hierarchique',
        'type_contrat',
        'date_embauche',
        'date_fin_contrat',
        'salaire_base',
        'numero_cnss',
        'est_actif',
        'statut',
        'date_depart',
        'motif_depart',
    ];

    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(Entreprise::class);
    }

    public function affectations(): HasMany
    {
        return $this->hasMany(Affectation::class);
    }
}
