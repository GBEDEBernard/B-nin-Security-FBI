<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Agency extends Model
{
    protected $fillable = [
        'tenant_id',
        'name',
        'registration_number',
        'license_number',
        'email',
        'phone',
        'address',
        'city',
        'postal_code',
        'country',
        'owner_name',
        'monthly_rate',
        'status',
        'activated_at',
        'deactivated_at',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'activated_at' => 'datetime',
            'deactivated_at' => 'datetime',
            'monthly_rate' => 'decimal:2',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function agents(): HasMany
    {
        return $this->hasMany(Agent::class);
    }

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }

    public function shifts(): HasMany
    {
        return $this->hasMany(Shift::class);
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }
}
