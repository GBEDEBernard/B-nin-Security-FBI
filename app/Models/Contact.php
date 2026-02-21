<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contact extends Model
{
    protected $table = 'contacts';

    protected $fillable = [
        'entreprise_id',
        'client_id',
        'site_client_id',
        'employe_id',
        'nom',
        'prenoms',
        'fonction',
        'email',
        'telephone',
        'telephone_secondaire',
        'adresse',
        'est_principal',
        'notes',
    ];

    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(Entreprise::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(SiteClient::class, 'site_client_id');
    }

    public function employe(): BelongsTo
    {
        return $this->belongsTo(Employe::class);
    }
}
