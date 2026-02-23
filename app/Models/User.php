<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, SoftDeletes, Notifiable, HasApiTokens, HasRoles;
    protected $table = 'users';
    protected $guard_name = 'web';
    protected $fillable = [
        'name',
        'email',
        'telephone',
        'password',
        'entreprise_id',
        'employe_id',
        'is_superadmin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_superadmin' => 'boolean',
        'entreprise_id' => 'integer',
        'employe_id' => 'integer',
    ];

    // ── Relations ───────────────────────────────────────────────────────────

    /**
     * L'entreprise (si l'utilisateur est lié à une entreprise)
     */
    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(Entreprise::class);
    }

    /**
     * L'employé (si l'utilisateur est un employé)
     */
    public function employe(): BelongsTo
    {
        return $this->belongsTo(Employe::class);
    }

    // ── Accesseurs ──────────────────────────────────────────────────────────

    /**
     * Nom complet
     */
    public function getNomCompletAttribute(): string
    {
        return $this->name;
    }

    // ── Méthodes métier ─────────────────────────────────────────────────────

    /**
     * Est super administrateur
     */
    public function estSuperAdmin(): bool
    {
        return $this->is_superadmin === true;
    }

    /**
     * Peut accéder à l'application
     */
    public function peutAcceder(): bool
    {
        if ($this->estSuperAdmin()) {
            return true;
        }

        if (!$this->entreprise_id) {
            return false;
        }

        $entreprise = $this->entreprise;
        return $entreprise && $entreprise->est_active;
    }
}
