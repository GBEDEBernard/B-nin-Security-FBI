<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaiementFacture extends Model
{
    protected $table = 'paiements_factures';

    protected $fillable = [
        'facture_id',
        'montant',
        'date_paiement',
        'mode_paiement',
        'reference',
        'piece_justificative',
        'notes',
        'enregistre_par',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'date_paiement' => 'date',
    ];

    public function facture(): BelongsTo
    {
        return $this->belongsTo(Facture::class);
    }

    public function enregistrePar(): BelongsTo
    {
        return $this->belongsTo(Employe::class, 'enregistre_par');
    }
}
