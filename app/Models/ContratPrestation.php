<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContratPrestation extends Model
{
    protected $table = 'contrats_prestation';
    protected $fillable = [
        'entreprise_id',
        'client_id',
        'numero_contrat',
        'intitule',
        'date_debut',
        'date_fin',
        'est_renouvelable',
        'duree_preavis',
        'montant_annuel_ht',
        'montant_mensuel_ht',
        'tva',
        'montant_mensuel_ttc',
        'periodicite_facturation',
        'nombre_agents_requis',
        'postes_requis',
        'description_prestation',
        'horaires_globaux',
        'conditions_particulieres',
        'documents_contractuels',
        'statut',
        'motif_resiliation',
        'date_resiliation',
        'signataire_client_nom',
        'signataire_client_fonction',
        'signataire_securite_id',
        'date_signature',
        'cree_par',
        'valide_par',
        'date_validation',
    ];

    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(Entreprise::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function sites(): HasMany
    {
        return $this->hasMany(SiteContrat::class);
    }
}
