<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ApkVersion extends Model
{
    use SoftDeletes;

    protected $table = 'apk_versions';

    protected $fillable = [
        'version',
        'version_code',
        'type',
        'fichier_path',
        'taille',
        'checksum',
        'notes',
        'changelog',
        'telechargements',
        'est_active',
        'est_obligatoire',
        'date_publication',
        'publie_par',
    ];

    protected $casts = [
        'changelog' => 'array',
        'est_active' => 'boolean',
        'est_obligatoire' => 'boolean',
        'date_publication' => 'datetime',
        'telechargements' => 'integer',
        'taille' => 'integer',
        'version_code' => 'integer',
    ];

    protected $appends = [
        'fichier_url',
        'taille_format',
        'type_label',
        'type_color',
        'statut_label',
        'statut_color',
    ];

    public function publieur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'publie_par');
    }

    public function scopeActive($query)
    {
        return $query->where('est_active', true);
    }

    public function scopeStable($query)
    {
        return $query->where('type', 'stable');
    }

    public function activate(): void
    {
        static::where('est_active', true)->update(['est_active' => false]);
        $this->update(['est_active' => true, 'date_publication' => $this->date_publication ?? now()]);
    }

    public function deactivate(): void
    {
        $this->update(['est_active' => false]);
    }

    public function incrementTelechargements(): void
    {
        $this->increment('telechargements');
    }

    public function getFichierUrlAttribute(): ?string
    {
        return $this->fichier_path ? Storage::disk('apk')->url($this->fichier_path) : null;
    }

    public function getTailleFormatAttribute(): string
    {
        if (!$this->taille) return '—';
        $unites = ['o', 'Ko', 'Mo', 'Go'];
        $i = 0;
        $taille = $this->taille;
        while ($taille >= 1024 && $i < 3) {
            $taille /= 1024;
            $i++;
        }
        return round($taille, 2) . ' ' . $unites[$i];
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'stable' => 'Stable',
            'beta' => 'Beta',
            'alpha' => 'Alpha',
            default => ucfirst($this->type),
        };
    }

    public function getTypeColorAttribute(): string
    {
        return match ($this->type) {
            'stable' => 'success',
            'beta' => 'warning',
            'alpha' => 'danger',
            default => 'secondary',
        };
    }

    public function getStatutLabelAttribute(): string
    {
        return $this->est_active ? 'Active' : 'Inactive';
    }

    public function getStatutColorAttribute(): string
    {
        return $this->est_active ? 'success' : 'secondary';
    }
}
