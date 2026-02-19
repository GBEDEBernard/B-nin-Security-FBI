<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Agent extends Model
{
    protected $fillable = [
        'tenant_id',
        'agency_id',
        'full_name',
        'email',
        'phone',
        'id_number',
        'id_type',
        'date_of_birth',
        'gender',
        'address',
        'city',
        'postal_code',
        'position',
        'salary_per_day',
        'hire_date',
        'status',
        'termination_date',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'date_of_birth' => 'date',
            'hire_date' => 'date',
            'termination_date' => 'date',
            'salary_per_day' => 'decimal:2',
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

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }
}
