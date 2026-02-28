<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * Modèle Employé avec support Multi-Tenant
 *
 * L'isolation par entreprise se fait via entreprise_id dans chaque requête.
 * PAS de GlobalScope ici — il causait des boucles infinies au login.
 */
class Employe extends Authenticatable
{
    use HasFactory, SoftDeletes, Notifiable, HasApiTokens, HasRoles;

    protected $table = 'employes';

    // ⚠️ IMPORTANT: guard_name doit être 'employe' pour correspondre au guard Auth
    protected $guard_name = 'employe';

    protected $fillable = [
        'entreprise_id',
        'matricule',
        'civilite',
        'nom',
        'prenoms',
        'email',
        'password',
        'cni',
        'date_naissance',
        'lieu_naissance',
        'telephone',
        'telephone_urgence',
        'photo',
        'adresse',
        'categorie',
        'poste',
        'niveau_hierarchique',
        'type_contrat',
        'date_embauche',
        'date_fin_contrat',
        'salaire_base',
        'numero_cnss',
        'est_actif',
        'disponible',
        'statut',
        'date_depart',
        'motif_depart',
        'est_connecte',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'est_actif'       => 'boolean',
        'disponible'      => 'boolean',
        'est_connecte'    => 'boolean',
        'date_naissance'  => 'date',
        'date_embauche'   => 'date',
        'date_fin_contrat'=> 'date',
        'date_depart'     => 'date',
        'salaire_base'    => 'decimal:2',
        'last_login_at'   => 'datetime',
    ];

    // ── Constantes ──────────────────────────────────────────────────────────

    public const CATEGORIES = ['direction', 'supervision', 'controle', 'agent'];

    public const POSTES = [
        'directeur_general', 'directeur_adjoint',
        'superviseur_general', 'superviseur_adjoint',
        'controleur_principal', 'controleur',
        'agent_terrain', 'agent_mobile', 'agent_poste_fixe',
    ];

    public const STATUTS = ['en_poste', 'conge', 'suspendu', 'licencie'];

    public const POSTE_ROLES = [
        'directeur_general'   => ['general_director'],
        'directeur_adjoint'   => ['deputy_director'],
        'superviseur_general' => ['supervisor'],
        'superviseur_adjoint' => ['supervisor'],
        'controleur_principal'=> ['controller'],
        'controleur'          => ['controller'],
        'agent_terrain'       => ['agent'],
        'agent_mobile'        => ['agent'],
        'agent_poste_fixe'    => ['agent'],
    ];

    // ── Relations ────────────────────────────────────────────────────────────

    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(Entreprise::class, 'entreprise_id');
    }

    public function affectations(): HasMany
    {
        return $this->hasMany(Affectation::class);
    }

    public function pointages(): HasMany
    {
        return $this->hasMany(Pointage::class);
    }

    public function conges(): HasMany
    {
        return $this->hasMany(Conge::class);
    }

    public function soldesConge(): HasMany
    {
        return $this->hasMany(SoldeConge::class);
    }

    public function paies(): HasMany
    {
        return $this->hasMany(Paie::class);
    }

    // ── Scopes ───────────────────────────────────────────────────────────────

    public function scopeActifs($query)
    {
        return $query->where('est_actif', true)->where('statut', 'en_poste');
    }

    public function scopeByCategorie($query, string $categorie)
    {
        return $query->where('categorie', $categorie);
    }

    public function scopeAgents($query)
    {
        return $query->where('categorie', 'agent');
    }

    public function scopeDisponibles($query)
    {
        return $query->where('disponible', true)->where('est_actif', true);
    }

    public function scopeCanLogin($query)
    {
        return $query->where('est_actif', true)
            ->whereIn('statut', ['en_poste', 'conge'])
            ->whereNotNull('email')
            ->whereNotNull('password');
    }

    // ── Accesseurs ───────────────────────────────────────────────────────────

    public function getNomCompletAttribute(): string
    {
        return trim(($this->prenoms ?? '') . ' ' . ($this->nom ?? ''));
    }

    // Alias utilisé dans certains endroits du code
    public function getNomCompletAffichageAttribute(): string
    {
        return $this->getNomCompletAttribute();
    }

    public function getInitialesAttribute(): string
    {
        $nom    = strtoupper(substr($this->nom ?? 'X', 0, 1));
        $prenom = strtoupper(substr($this->prenoms ?? 'X', 0, 1));
        return $nom . $prenom;
    }

    // ── Méthodes métier ──────────────────────────────────────────────────────

    public function estEnPoste(): bool
    {
        return $this->est_actif && $this->statut === 'en_poste';
    }

    public function peutSeConnecter(): bool
    {
        return $this->est_actif
            && in_array($this->statut, ['en_poste', 'conge'])
            && !empty($this->email)
            && !empty($this->password);
    }

    public function estDirecteurGeneral(): bool
    {
        return $this->poste === 'directeur_general';
    }

    public function estSuperviseur(): bool
    {
        return in_array($this->poste, ['superviseur_general', 'superviseur_adjoint']);
    }

    public function estControleur(): bool
    {
        return in_array($this->poste, ['controleur_principal', 'controleur']);
    }

    public function estAgent(): bool
    {
        return in_array($this->poste, ['agent_terrain', 'agent_mobile', 'agent_poste_fixe']);
    }

    public function estDirection(): bool
    {
        return in_array($this->poste, ['directeur_general', 'directeur_adjoint']);
    }

    public function getLibelleCategorie(): string
    {
        return match ($this->categorie) {
            'direction'  => 'Direction',
            'supervision'=> 'Supervision',
            'controle'   => 'Contrôle',
            'agent'      => 'Agent',
            default      => 'Inconnu',
        };
    }

    /**
     * Route du dashboard selon le poste
     */
    public function getDashboardRoute(): string
    {
        if ($this->estDirection() || $this->estSuperviseur() || $this->estControleur()) {
            return 'admin.entreprise.index';
        }
        return 'admin.agent.index';
    }

    /**
     * URL du dashboard
     */
    public function getDashboardUrl(): string
    {
        return route($this->getDashboardRoute());
    }

    /**
     * Assigner le rôle par défaut selon le poste
     */
    public function assignRoleByPoste(): void
    {
        $roles = self::POSTE_ROLES[$this->poste] ?? ['agent'];
        $this->assignRole($roles);
    }

    // ── Boot ────────────────────────────────────────────────────────────────

    /**
     * ✅ PAS de GlobalScope ici.
     *
     * Raison: un GlobalScope basé sur la session causait une boucle infinie :
     *   1. L'employé se connecte → session('entreprise_id') = null
     *   2. Le scope retournait 0 résultats
     *   3. Auth::guard('employe')->user() = null
     *   4. EntrepriseMiddleware redirige vers /login
     *   5. Boucle infinie
     *
     * L'isolation par entreprise est gérée MANUELLEMENT dans chaque
     * contrôleur via ->where('entreprise_id', $entrepriseId).
     */
    protected static function boot()
    {
        parent::boot();
        // Aucun GlobalScope — isolation gérée dans les contrôleurs
    }
}