<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Vehicle extends Model
{
    protected $fillable = [
        'tenant_id',
        'agency_id',
        'registration_number',
        'make',
        'model',
        'year',
        'type',
        'color',
        'vin',
        'license_plate',
        'registration_expiry',
        'insurance_expiry',
        'daily_rate',
        'status',
        'notes',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'registration_expiry' => 'date',
            'insurance_expiry' => 'date',
            'daily_rate' => 'decimal:2',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class);
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }
}
