<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    protected $table = 'activity_log';

    const UPDATED_AT = null;

    protected $fillable = [
        'log_name',
        'description',
        'subject_type',
        'subject_id',
        'causer_id',
        'causer_type',
        'properties',
        'ip_address',
    ];

    protected $casts = [
        'properties' => 'array',
        'created_at' => 'datetime',
    ];

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public function causer(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeOfLogName($query, string $name)
    {
        return $query->where('log_name', $name);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeBetweenDates($query, $debut, $fin)
    {
        if ($debut) $query->whereDate('created_at', '>=', $debut);
        if ($fin) $query->whereDate('created_at', '<=', $fin);
        return $query;
    }

    public function getActionBadgeAttribute(): string
    {
        $desc = strtolower($this->description ?? '');
        return match (true) {
            str_contains($desc, 'connexion') || str_contains($desc, 'login') => 'success',
            str_contains($desc, 'création') || str_contains($desc, 'created') => 'primary',
            str_contains($desc, 'modification') || str_contains($desc, 'updated') => 'warning',
            str_contains($desc, 'suppression') || str_contains($desc, 'deleted') => 'danger',
            str_contains($desc, 'export') => 'info',
            str_contains($desc, 'erreur') || str_contains($desc, 'error') || str_contains($desc, 'échec') => 'danger',
            default => 'secondary',
        };
    }

    public function getActionLabelAttribute(): string
    {
        $desc = strtolower($this->description ?? '');
        return match (true) {
            str_contains($desc, 'connexion') || str_contains($desc, 'login') => 'Connexion',
            str_contains($desc, 'création') || str_contains($desc, 'created') => 'Création',
            str_contains($desc, 'modification') || str_contains($desc, 'updated') => 'Modification',
            str_contains($desc, 'suppression') || str_contains($desc, 'deleted') => 'Suppression',
            str_contains($desc, 'export') => 'Export',
            str_contains($desc, 'erreur') || str_contains($desc, 'error') || str_contains($desc, 'échec') => 'Erreur',
            default => 'Action',
        };
    }

    public function getModuleLabelAttribute(): string
    {
        if (!$this->subject_type) return 'Système';
        $parts = explode('\\', $this->subject_type);
        return end($parts);
    }

    public function getCauserNameAttribute(): string
    {
        if (!$this->causer_id) return 'Système';
        try {
            $user = $this->causer_type ? app($this->causer_type)::find($this->causer_id) : null;
            return $user?->name ?? $user?->email ?? "ID: {$this->causer_id}";
        } catch (\Throwable) {
            return "ID: {$this->causer_id}";
        }
    }
}
