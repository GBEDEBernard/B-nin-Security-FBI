<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assignment extends Model
{
    protected $fillable = [
        'tenant_id',
        'agent_id',
        'shift_id',
        'status',
        'check_in_time',
        'check_out_time',
        'notes',
        'actual_rate',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'check_in_time' => 'datetime',
            'check_out_time' => 'datetime',
            'actual_rate' => 'decimal:2',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }
}
