<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;

    /**
     * Relation avec le modèle Entreprise
     * Chaque tenant correspond à une entreprise dans la base centrale
     */
    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(Entreprise::class, 'id', 'id');
    }

    /**
     * Obtenir l'entreprise associée à ce tenant
     */
    public function getEntreprise(): ?Entreprise
    {
        return Entreprise::find($this->id);
    }

    /**
     * Vérifier si le tenant est actif
     */
    public function isActive(): bool
    {
        $entreprise = $this->getEntreprise();
        return $entreprise && $entreprise->est_active;
    }

    /**
     * Obtenir le nom d'affichage du tenant
     */
    public function getDisplayNameAttribute(): string
    {
        $entreprise = $this->getEntreprise();
        return $entreprise ? $entreprise->nom_entreprise : 'Tenant ' . $this->id;
    }
}
