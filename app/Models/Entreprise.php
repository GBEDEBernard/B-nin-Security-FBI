<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Entreprise extends Model
{
    protected $table = 'entreprises';
    protected $fillable = ['nom', 'parametres'];

    public function employes(): HasMany
    {
        return $this->hasMany(Employe::class);
    }

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }

    public function contrats(): HasMany
    {
        return $this->hasMany(ContratPrestation::class);
    }
}
