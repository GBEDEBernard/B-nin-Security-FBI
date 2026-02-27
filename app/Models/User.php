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

    protected $table      = 'users';
    protected $guard_name = 'web';

    protected $fillable = [
        'name',
        'email',
        'telephone',
        'photo',
        'password',
        'is_superadmin',
        'is_active',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'is_superadmin'     => 'boolean',
        'is_active'         => 'boolean',
        'last_login_at'     => 'datetime',
    ];

    // ── Vérifications de rôle ────────────────────────────────────────────────

    public function estSuperAdmin(): bool
    {
        return $this->is_superadmin === true;
    }

    // ── Accès à l'application ────────────────────────────────────────────────

    /**
     * Le SuperAdmin peut TOUJOURS accéder.
     */
    public function peutAcceder(): bool
    {
        // SuperAdmin : accès total
        if ($this->estSuperAdmin()) {
            return true;
        }

        // Compte désactivé
        return $this->is_active === true;
    }

    // ── Redirection selon le rôle ────────────────────────────────────────────

    public function getAdminRoute(): string
    {
        return 'admin.superadmin.index';
    }

    public function getAdminUrl(): string
    {
        return route($this->getAdminRoute());
    }

    // ── Contexte Entreprise (SuperAdmin) ─────────────────────────────────────

    /**
     * Vérifie si le SuperAdmin est en contexte entreprise
     * (c'est-à-dire s'il a sélectionné une entreprise spécifique via switch)
     */
    public function estEnContexteEntreprise(): bool
    {
        // Le SuperAdmin n'est en contexte entreprise que si une entreprise est sélectionnée en session
        return session()->has('entreprise_id') && !is_null(session('entreprise_id'));
    }

    /**
     * Obtient l'entreprise actuellement sélectionnée (si en contexte entreprise)
     */
    public function getEntrepriseId(): ?int
    {
        return session('entreprise_id');
    }

    /**
     * Obtient l'objet entreprise du contexte (si en contexte entreprise)
     */
    public function getEntrepriseContexte(): ?Entreprise
    {
        $entrepriseId = $this->getEntrepriseId();
        if (!$entrepriseId) {
            return null;
        }
        return Entreprise::find($entrepriseId);
    }

    /**
     * Vérifie si l'utilisateur est un utilisateur entreprise (employé avec rôle)
     */
    public function estUtilisateurEntreprise(): bool
    {
        // C'est un utilisateur entreprise s'il a un entreprise_id et n'est pas SuperAdmin
        return !$this->estSuperAdmin() && $this->entreprise_id !== null;
    }

    /**
     * Vérifie si l'utilisateur est un agent (rôle agent)
     */
    public function estAgent(): bool
    {
        // L'agent est un employé avec le rôle 'agent'
        return $this->hasRole('agent');
    }

    /**
     * Vérifie si l'utilisateur est un client (ancien système - via client_id)
     */
    public function estClient(): bool
    {
        // L'utilisateur est un client s'il a un client_id
        return $this->client_id !== null;
    }
}
