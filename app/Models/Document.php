<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Document extends Model
{
    protected $fillable = [
        'tenant_id',
        'documentable_type',
        'documentable_id',
        'type',
        'file_path',
        'original_filename',
        'mime_type',
        'file_size',
        'expiry_date',
        'notes',
        'status',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'expiry_date' => 'date',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function documentable(): MorphTo
    {
        return $this->morphTo();
    }
}
