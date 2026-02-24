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
        // Nouveaux champs multi-tenant
        'type_user',
        'client_id',
        'is_active',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_superadmin' => 'boolean',
        'is_active' => 'boolean',
        'entreprise_id' => 'integer',
        'employe_id' => 'integer',
        'client_id' => 'integer',
        'last_login_at' => 'datetime',
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

        // Vérifier si le compte est actif
        if ($this->is_active === false) {
            return false;
        }

        if (!$this->entreprise_id) {
            return false;
        }

        $entreprise = $this->entreprise;
        return $entreprise && $entreprise->est_active;
    }

    // ── Relations pour les clients ─────────────────────────────────────────

    /**
     * Le client (si l'utilisateur est un client)
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    // ── Méthodes de vérification de rôle ───────────────────────────────────

    /**
     * Est un utilisateur interne (employé de l'entreprise de sécurité)
     */
    public function estInterne(): bool
    {
        return $this->type_user === 'interne';
    }

    /**
     * Est un client (particulier ou entreprise avec compte)
     */
    public function estClient(): bool
    {
        return $this->type_user === 'client';
    }

    /**
     * Est direction (Directeur Général ou Directeur Adjoint)
     */
    public function estDirection(): bool
    {
        return $this->hasRole(['general_director', 'deputy_director']);
    }

    /**
     * Est superviseur
     */
    public function estSuperviseur(): bool
    {
        return $this->hasRole('supervisor');
    }

    /**
     * Est contrôleur
     */
    public function estControleur(): bool
    {
        return $this->hasRole('controller');
    }

    /**
     * Est agent de terrain
     */
    public function estAgent(): bool
    {
        return $this->hasRole('agent');
    }

    /**
     * Est un utilisateur de l'entreprise (direction, superviseur, contrôleur, agent)
     */
    public function estUtilisateurEntreprise(): bool
    {
        return $this->estDirection() || $this->estSuperviseur() || $this->estControleur() || $this->estAgent();
    }

    // ── Méthodes de redirection ───────────────────────────────────────────

    /**
     * Retourne le nom de la route admin selon le rôle
     */
    public function getAdminRoute(): string
    {
        // Super Admin
        if ($this->estSuperAdmin()) {
            return 'admin.superadmin.index';
        }

        // Client
        if ($this->estClient()) {
            return 'admin.client.index';
        }

        // Utilisateurs de l'entreprise (direction, superviseur, contrôleur, agent)
        if ($this->estUtilisateurEntreprise()) {
            // Les agents ont un admin spécifique
            if ($this->estAgent()) {
                return 'admin.agent.index';
            }
            // Direction, superviseur et contrôleur ont le admin entreprise
            return 'admin.entreprise.index';
        }

        // Par défaut, admin générique
        return 'admin';
    }

    /**
     * Retourne l'URL du admin selon le rôle
     */
    public function getAdminUrl(): string
    {
        return route($this->getAdminRoute());
    }

    // ═══════════════════════════════════════════════════════════════════════
    // GESTION DU CONTEXTE TEMPORAIRE (POUR SUPERADMIN SE CONNECTANT AUX ENTREPRISES)
    // ═══════════════════════════════════════════════════════════════════════

    /**
     * Vérifie si le superadmin est en contexte d'entreprise
     */
    public function estEnContexteEntreprise(): bool
    {
        return session()->has('superadmin_temp_entreprise_id') && $this->estSuperAdmin();
    }

    /**
     * Obtient l'ID de l'entreprise temporaire (si en contexte)
     */
    public function getEntrepriseContexteId(): ?int
    {
        if ($this->estEnContexteEntreprise()) {
            return session()->get('superadmin_temp_entreprise_id');
        }
        return null;
    }

    /**
     * Obtient l'entreprise du contexte temporaire (si applicable)
     */
    public function getEntrepriseContexte(): ?Entreprise
    {
        $entrepriseId = $this->getEntrepriseContexteId();

        if ($entrepriseId) {
            return Entreprise::find($entrepriseId);
        }

        return null;
    }

    /**
     * Vérifie si on peut retourner au mode superadmin
     */
    public function peutRetournerSuperAdmin(): bool
    {
        return $this->estEnContexteEntreprise() && session()->has('superadmin_original');
    }

    /**
     * Retourne l'URL de retour au superadmin
     */
    public function getUrlRetourSuperAdmin(): string
    {
        return route('admin.superadmin.return');
    }

    /**
     * Retourne l'URL du dashboard entreprise (pour le superadmin en contexte)
     */
    public function getUrlDashboardEntreprise(): string
    {
        return route('admin.entreprise.index');
    }
}
