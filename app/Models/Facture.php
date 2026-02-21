<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Facture extends Model
{
    protected $table = 'factures';
    protected $fillable = [
        'entreprise_id',
        'contrat_prestation_id',
        'client_id',
        'numero_facture',
        'reference',
        'mois',
        'annee',
        'montant_ht',
        'tva',
        'montant_ttc',
        'detail_prestation',
        'periodes_calc',
        'date_emission',
        'date_echeance',
        'date_paiement',
        'statut',
        'montant_paye',
        'montant_restant',
        'fichier_pdf',
        'notes',
        'cree_par',
    ];

    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(Entreprise::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function contrat(): BelongsTo
    {
        return $this->belongsTo(ContratPrestation::class, 'contrat_prestation_id');
    }

    public function paiements(): HasMany
    {
        return $this->hasMany(PaiementFacture::class, 'facture_id');
    }

    public function paiements(): HasMany
    {
        return $this->hasMany(PaiementFacture::class, 'facture_id');
    }
}
