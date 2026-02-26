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

class Employe extends Authenticatable
{
    use HasFactory, SoftDeletes, Notifiable, HasApiTokens, HasRoles;

    protected $table = 'employes';
    protected $guard_name = 'web';

    protected $fillable = [
        //Entreprise (référence)
        'entreprise_id',

        // Identité
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

        // Poste dans l'entreprise de sécurité
        'categorie', // direction, supervision, controle, agent
        'poste', // directeur_general, superviseur, agent_terrain, etc.
        'niveau_hierarchique', // 1 = plus haut, 5 = agent

        // Contrat de travail
        'type_contrat', // cdi, cdd, stage, prestation
        'date_embauche',
        'date_fin_contrat',
        'salaire_base',
        'numero_cnss',

        // Statut
        'est_actif',
        'disponible',
        'statut', // en_poste, conge, suspendu, licencie
        'date_depart',
        'motif_depart',

        // Connexion
        'est_connecte',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'est_actif' => 'boolean',
        'disponible' => 'boolean',
        'est_connecte' => 'boolean',
        'date_naissance' => 'date',
        'date_embauche' => 'date',
        'date_fin_contrat' => 'date',
        'date_depart' => 'date',
        'salaire_base' => 'decimal:2',
        'last_login_at' => 'datetime',
    ];

    // ── Constantes ─────────────────────────────────────────────────────────

    public const CATEGORIES = [
        'direction',
        'supervision',
        'controle',
        'agent'
    ];

    public const POSTES = [
        // Direction
        'directeur_general',
        'directeur_adjoint',
        // Supervision
        'superviseur_general',
        'superviseur_adjoint',
        // Contrôle
        'controleur_principal',
        'controleur',
        // Agents
        'agent_terrain',
        'agent_mobile',
        'agent_poste_fixe'
    ];

    public const STATUTS = [
        'en_poste',
        'conge',
        'suspendu',
        'licencie'
    ];

    // Rôles par défaut selon le poste
    public const POSTE_ROLES = [
        'directeur_general' => ['general_director'],
        'directeur_adjoint' => ['deputy_director'],
        'superviseur_general' => ['supervisor'],
        'superviseur_adjoint' => ['supervisor'],
        'controleur_principal' => ['controller'],
        'controleur' => ['controller'],
        'agent_terrain' => ['agent'],
        'agent_mobile' => ['agent'],
        'agent_poste_fixe' => ['agent'],
    ];

    // ── Relations ───────────────────────────────────────────────────────────

    /**
     * L'entreprise de sécurité employees this employee
     */
    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(Entreprise::class);
    }

    /**
     * Les affectations de cet employé aux sites clients
     */
    public function affectations(): HasMany
    {
        return $this->hasMany(Affectation::class);
    }

    /**
     * Les pointages de cet employé
     */
    public function pointages(): HasMany
    {
        return $this->hasMany(Pointage::class);
    }

    /**
     * Les congés de cet employé
     */
    public function conges(): HasMany
    {
        return $this->hasMany(Conge::class);
    }

    /**
     * Les soldes de congés
     */
    public function soldesConge(): HasMany
    {
        return $this->hasMany(SoldeConge::class);
    }

    /**
     * Les bulletins de paie
     */
    public function paies(): HasMany
    {
        return $this->hasMany(Paie::class);
    }

    // ── Scopes ──────────────────────────────────────────────────────────────

    /**
     * Employés actifs (en poste)
     */
    public function scopeActifs($query)
    {
        return $query->where('est_actif', true)->where('statut', 'en_poste');
    }

    /**
     * Employés par catégorie
     */
    public function scopeByCategorie($query, string $categorie)
    {
        return $query->where('categorie', $categorie);
    }

    /**
     * Agents de terrain
     */
    public function scopeAgents($query)
    {
        return $query->where('categorie', 'agent');
    }

    /**
     * Employés disponibles (pour les missions)
     */
    public function scopeDisponibles($query)
    {
        return $query->where('disponible', true)->where('est_actif', true);
    }

    /**
     * Employés pouvant se connecter
     */
    public function scopeCanLogin($query)
    {
        return $query->where('est_actif', true)
            ->whereIn('statut', ['en_poste', 'conge'])
            ->whereNotNull('email')
            ->whereNotNull('password');
    }

    // ── Accesseurs ──────────────────────────────────────────────────────────

    /**
     * Nom complet
     */
    public function getNomCompletAttribute(): string
    {
        return $this->prenoms . ' ' . $this->nom;
    }

    /**
     * Initiales
     */
    public function getInitialesAttribute(): string
    {
        $nom = strtoupper(substr($this->nom, 0, 1));
        $prenom = strtoupper(substr($this->prenoms, 0, 1));
        return $nom . $prenom;
    }

    // ── Méthodes métier ─────────────────────────────────────────────────────

    /**
     * Est en poste
     */
    public function estEnPoste(): bool
    {
        return $this->est_actif && $this->statut === 'en_poste';
    }

    /**
     * Peut se connecter
     */
    public function peutSeConnecter(): bool
    {
        return $this->est_actif
            && in_array($this->statut, ['en_poste', 'conge'])
            && !empty($this->email)
            && !empty($this->password);
    }

    /**
     * Est directeur général
     */
    public function estDirecteurGeneral(): bool
    {
        return $this->poste === 'directeur_general';
    }

    /**
     * Est superviseur
     */
    public function estSuperviseur(): bool
    {
        return in_array($this->poste, ['superviseur_general', 'superviseur_adjoint']);
    }

    /**
     * Est contrôleur
     */
    public function estControleur(): bool
    {
        return in_array($this->poste, ['controleur_principal', 'controleur']);
    }

    /**
     * Est agent
     */
    public function estAgent(): bool
    {
        return in_array($this->poste, ['agent_terrain', 'agent_mobile', 'agent_poste_fixe']);
    }

    /**
     * Est direction (DG + DA)
     */
    public function estDirection(): bool
    {
        return in_array($this->poste, ['directeur_general', 'directeur_adjoint']);
    }

    /**
     * Poste hiérarchique
     */
    public function getLibelleCategorie(): string
    {
        return match ($this->categorie) {
            'direction' => 'Direction',
            'supervision' => 'Supervision',
            'controle' => 'Contrôle',
            'agent' => 'Agent',
            default => 'Inconnu'
        };
    }

    /**
     * Route du dashboard selon le poste
     */
    public function getDashboardRoute(): string
    {
        if ($this->estDirection()) {
            return 'admin.entreprise.index';
        }
        if ($this->estSuperviseur() || $this->estControleur()) {
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
}
