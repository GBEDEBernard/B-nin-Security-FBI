<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $table = 'clients';

    protected $fillable = [
        'entreprise_id',
        'type_client',
        'nom',
        'prenoms',
        'raison_sociale',
        'nif',
        'rc',
        'email',
        'telephone',
        'telephone_secondaire',
        'contact_principal_nom',
        'contact_principal_fonction',
        'adresse',
        'ville',
        'pays',
        'est_actif',
        'notes',
    ];

    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(Entreprise::class);
    }

    public function sites(): HasMany
    {
        return $this->hasMany(SiteClient::class, 'client_id');
    }
}
