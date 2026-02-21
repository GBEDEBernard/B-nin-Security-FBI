<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Paie extends Model
{
    protected $table = 'paies';
    protected $fillable = [
        'entreprise_id',
        'employe_id',
        'mois',
        'annee',
        'salaire_base',
        'jours_travailles',
        'heures_normales',
        'heures_supplementaires',
        'prime_anciennete',
        'prime_panier',
        'prime_transport',
        'prime_risque',
        'prime_performance',
        'autres_primes',
        'indemnites',
        'brut_imposable',
        'cnss_part_salariale',
        'cnss_part_patronale',
        'ipps',
        'autres_cotisations',
        'avance_salaire',
        'absence_deduction',
        'autres_retenues',
        'net_a_payer',
        'date_paiement',
        'statut',
        'bulletin_pdf',
        'details_calcul',
        'calcule_par',
        'valide_par',
    ];

    public function employe(): BelongsTo
    {
        return $this->belongsTo(Employe::class);
    }
}
