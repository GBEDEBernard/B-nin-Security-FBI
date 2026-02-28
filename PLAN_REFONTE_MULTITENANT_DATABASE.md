# Plan de Refonte - Système Multi-tenant avec Database par Tenant

## Contexte

### État Actuel du Projet

- **Approche actuelle**: Multi-tenant avec base de données partagée (shared database)
- **Tenants**: Entreprises de sécurité stockées dans la table `entreprises`
- **Gardes d'authentification**: 3 guards (web/SuperAdmin, employe, client)
- **Gestion des rôles**: Spatie laravel-permission
- **Domaine unique**: benin-security.com (pas de sous-domaines)

### Objectif de la Refonte

- **Nouvelle approche**: Database par tenant avec `stancl/tenancy`
- **Domaine par tenant**: `*.benin-security.com` (ex: securite-alpha.benin-security.com)
- **Dashboard central**: Pour les développeurs (SuperAdmin)
- **Dashboards隔离**: Entreprise, Agent, Client par tenant

---

## Décision: Database par Tenant

### Avantages

1. **Isolation complète des données** - Chaque entreprise a sa propre DB
2. **Performance** - Base de données plus légère par tenant
3. **Sécurité** - Isolation physique des données
4. **Personnalisation** - Chaque tenant peut avoir sa propre configuration
5. **Gestion des domaines** - \*.benin-security.com pour chaque entreprise

### Inconvénients

1. **Complexité de migration** - Plus complexe à mettre en place
2. **Maintenance** - Plus de bases de données à maintenir
3. **Backup** - Plus de bases à sauvegarder

---

## PHASE 1: Installation et Configuration de Stancl/Tenancy

### 1.1 Installation du package

```bash
composer require stancl/tenancy
```

### 1.2 Configuration des routes

```php
// routes/web.php - Ajout du middleware tenancy
Route::middleware(['web', 'tenancy'])->group(function () {
    // Routes tenant
});
```

### 1.3 Configuration centrale (pas de tenancy)

```php
// SuperAdmin routes - sans middleware tenancy
Route::middleware(['web'])->group(function () {
    // Routes centrales
});
```

---

## PHASE 2: Modèle Tenant + Domaines

### 2.1 Création du modèle Tenant

```php
// app/Models/Tenant.php
class Tenant extends Model {
    use HasFactory;

    // Tableau de bord
    public function dashboard() {
        return $this->belongsTo(Dashboard::class);
    }

    // Domaine
    public function domains() {
        return $this->hasMany(Domain::class);
    }
}
```

### 2.2 Création du modèle Domain

```php
// app/Models/Domain.php
class Domain extends Model {
    public function tenant() {
        return $this->belongsTo(Tenant::class);
    }
}
```

### 2.3 Migration des tables

```php
// create_tenants_table
Schema::create('tenants', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->string('database')->unique();
    $table->foreignId('user_id')->nullable(); // Owner
    $table->json('settings')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});

// create_domains_table
Schema::create('domains', function (Blueprint $table) {
    $table->id();
    $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
    $table->string('domain')->unique();
    $table->boolean('is_primary')->default(false);
    $table->timestamps();
});
```

---

## PHASE 3: Refonte du Système d'Authentification

### 3.1 Architecture d'authentification centralisée

```
┌─────────────────────────────────────────────────────────────┐
│                    SERVEUR CENTRAL                          │
│  ┌─────────────────────────────────────────────────────┐   │
│  │               Base de données: central              │   │
│  │  - users (SuperAdmins/Développeurs)                │   │
│  │  - tenants (entreprises avec domaines)             │   │
│  │  - domains                                          │   │
│  │  - abonnements                                      │   │
│  └─────────────────────────────────────────────────────┘   │
│                    benin-security.com                        │
└─────────────────────────────────────────────────────────────┘
                            │
           ┌────────────────┼────────────────┐
           │                │                │
           ▼                ▼                ▼
    ┌──────────────┐ ┌──────────────┐ ┌──────────────┐
    │  Tenant 1    │ │  Tenant 2    │ │  Tenant 3    │
    │ Entreprise A  │ │ Entreprise B │ │ Entreprise C │
    │ sec-alpha    │ │ sec-beta     │ │ sec-gamma   │
    │ .benin-      │ │ .benin-     │ │ .benin-     │
    │ security.com │ │ security.com│ │ security.com│
    └──────────────┘ └──────────────┘ └──────────────┘
```

### 3.2 Types d'utilisateurs après refonte

| Type             | Where                             | Connexion     |
| ---------------- | --------------------------------- | ------------- |
| SuperAdmin       | Central DB (benin-security.com)   | Directly      |
| Admin Entreprise | Tenant DB (\*.benin-security.com) | Via subdomain |
| Employé          | Tenant DB                         | Via subdomain |
| Client           | Tenant DB                         | Via subdomain |

### 3.3 Login par domaine

```php
// LoginController.php
public function login(Request $request) {
    $domain = $request->getHost();

    if ($domain === 'benin-security.com') {
        // Connexion SuperAdmin (central)
        return $this->loginSuperAdmin($request);
    } else {
        // Connexion Tenant (entreprise)
        return $this->loginTenant($request, $domain);
    }
}
```

### 3.4 Modification des guards

```php
// config/auth.php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
    'superadmin' => [
        'driver' => 'session',
        'provider' => 'superadmins',
    ],
    // Employés et clients seront dans le tenant
    'employe' => [
        'driver' => 'session',
        'provider' => 'employes',
    ],
],
```

---

## PHASE 4: Gestion des Permissions par Tenant

### 4.1 Installation/configuration Spatie

```bash
composer require spatie/laravel-permission
```

### 4.2 Configuration par tenant

- Les rôles et permissions seront **isolés par tenant**
- Chaque tenant aura sa propre table `roles` et `permissions`
- Utiliser `spatie/laravel-permission` avec support multi-tenant

### 4.3 Middleware de vérification

```php
// Vérifie que l'utilisateur a le rôle dans SON tenant
Route::middleware(['role:admin_entreprise'])->group(function () {
    // Routes administration entreprise
});
```

---

## PHASE 5: Middlewares et Routes par Niveau

### 5.1 Middlewares à créer

```php
// app/Http/Middleware/TenantSetup.php
class TenantSetup {
    public function handle($request, $next) {
        // Initialise la connexion tenant
        tenancy()->initialize($request->tenant());
        return $next($request);
    }
}
```

```php
// app/Http/Middleware/SuperAdminAccess.php
class SuperAdminAccess {
    public function handle($request, $next) {
        // Vérifie que c'est un superadmin
        if (!auth()->user()->isSuperAdmin()) {
            return redirect('/');
        }
        return $next($request);
    }
}
```

```php
// app/Http/Middleware/TenantUser.php
class TenantUser {
    public function handle($request, $next) {
        // Vérifie que l'utilisateur appartient au tenant
        if (!auth()->user()->tenant_id === $request->tenant()->id) {
            return redirect('/');
        }
        return $next($request);
    }
}
```

### 5.2 Structure des routes

```
Routes Centrales (benin-security.com):
├── /login, /logout
├── /admin/superadmin/* (Dashboard SuperAdmin)
│   ├── Gestion des tenants
│   ├── Gestion des abonnements
│   └── Configuration globale
│
Routes Tenants (*.benin-security.com):
├── /login, /logout
├── /dashboard (redirige selon rôle)
├── /admin/entreprise/*
│   ├── Gestion des employés
│   ├── Gestion des clients
│   └── Rapports
├── /agent/*
│   ├── Missions
│   └── Pointages
└── /client/*
    ├── Mes contrats
    └── Mes factures
```

---

## PHASE 6: Dashboards

### 6.1 Dashboard SuperAdmin (Central)

- Liste des entreprises (tenants)
- Gestion des abonnements
- Statistiques globales
- Configuration système

### 6.2 Dashboard Entreprise (Tenant)

- Vue d'ensemble
- Gestion des employés
- Gestion des clients
- Contrats et facturation
- Rapports

### 6.3 Dashboard Agent (Tenant)

- Mes missions
- Mes pointages
- Mes congés

### 6.4 Dashboard Client (Tenant)

- Mes contrats
- Mes factures
- Signaler un incident

---

## PHASE 7: Migration des Données

### 7.1 Étapes de migration

1. **Export** des données depuis la base actuelle
2. **Transformation** pour le nouveau format
3. **Import** dans les bases de données des tenants

### 7.2 Données à migrer

- Entreprises → Tenants
- Domaines → Domains
- Utilisateurs → Selon le type (SuperAdmin central, autres dans tenants)
- Employés → Tenant DB
- Clients → Tenant DB
- Contrats → Tenant DB
- Factures → Tenant DB

### 7.3 Tests obligatoires

- Connexion SuperAdmin
- Connexion Admin Entreprise
- Connexion Employé
- Connexion Client
- Redirections par rôle
- Isolation des données entre tenants

---

## PHASE 8: HTTPS + Wildcard SSL

### 8.1 Configuration SSL

- Générer un certificat wildcard: `*.benin-security.com`
- Configuration Let's Encrypt ou autre provider

### 8.2 Configuration serveur (nginx)

```nginx
server {
    server_name *.benin-security.com;
    ssl_certificate /path/to/wildcard.crt;
    ssl_certificate_key /path/to/wildcard.key;

    # Redirige vers l'application Laravel
    # qui gère le multi-tenant
}
```

---

## Fichiers à Modifier/Créer

### À Modifier

1. `composer.json` - Ajouter stancl/tenancy, spatie/laravel-permission
2. `config/auth.php` - Ajouter guard superadmin
3. `bootstrap/app.php` - Ajouter middleware tenancy
4. `routes/web.php` - Refondre structure des routes
5. `app/Models/User.php` - Ajouter tenant_id
6. `app/Http/Controllers/AuthController.php` - Login par domaine
7. `resources/views/layouts/sidebar.blade.php` - Adapter au nouveau système

### À Créer

1. `app/Models/Tenant.php` - Modèle Tenant
2. `app/Models/Domain.php` - Modèle Domain
3. `app/Http/Middleware/TenantSetup.php`
4. `app/Http/Middleware/SuperAdminAccess.php`
5. `database/migrations/*_create_tenants_table.php`
6. `database/migrations/*_create_domains_table.php`
7. Views pour chaque dashboard

---

## Ordre d'Implémentation

1. **Installation** - Stancl/Tenancy + Spatie
2. **Configuration** - Middlewares, routes
3. **Modèles** - Tenant + Domain
4. **Migrations** - Tables centrales
5. **Auth** - Refonte login + guards
6. **Routes** - Structure par niveau
7. **Views** - Dashboards
8. **SSL** - Wildcard certificate
9. **Tests** - Validation complète
10. **Migration données** - Données vers tenants

---

## Risques et Solutions

| Risque                  | Solution                            |
| ----------------------- | ----------------------------------- |
| Perte de données        | Sauvegarde complète avant migration |
| Temps d'indisponibilité | Migration progressive               |
| Complexité maintenance  | Automatisation des tâches           |
| Performance             | Cache et optimisation BDD           |
| SSL wildcard            | Utiliser Let's Encrypt gratuit      |

---

## Résumé Technique

```
┌────────────────────────────────────────────────────────────────┐
│                    ARCHITECTURE FINALE                         │
├────────────────────────────────────────────────────────────────┤
│                                                                │
│  benin-security.com (Central)                                 │
│  ├── SuperAdmins (nous les devs)                              │
│  ├── Tenants (entreprises)                                    │
│  ├── Abonnements                                              │
│  └── Configuration globale                                     │
│                                                                │
│  *.benin-security.com (Par Tenant)                            │
│  ├── Employés (avec rôles Spatie)                             │
│  ├── Clients                                                  │
│  ├── Contrats                                                 │
│  └── Données métier                                           │
│                                                                │
│  Database:                                                    │
│  ├── central (benin_security_central)                        │
│  ├── tenant_1 (benin_security_entreprise_1)                  │
│  ├── tenant_2 (benin_security_entreprise_2)                  │
│  └── ...                                                       │
│                                                                │
└────────────────────────────────────────────────────────────────┘
```
