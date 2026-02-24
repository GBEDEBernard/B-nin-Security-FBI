# Plan Complet - Système Multi-tenant avec Gestion des Rôles

## Analyse du Projet

### Structure Actuelle

- **Modèle User**: `entreprise_id`, `employe_id`, `is_superadmin`, rôles Spatie
- **Modèle Entreprise**: entreprises de sécurité (tenants)
- **Modèle Employé**: agents, directeurs, superviseurs
- **Modèle Client**: particuliers, entreprises (SBEE, SONEB), institutions
- **Rôles existants**: super_admin, general_director, deputy_director, operations_director, supervisor, controller, agent, client_individual, client_company

### Types d'Utilisateurs à Gérer

1. **Super Admin** (nous les devs) - Accès à toutes les entreprises
2. **Direction** (Directeur Général, Directeur Adjoint) - Entreprise de sécurité
3. **Supervision** (Superviseur) - Entreprise de sécurité
4. **Contrôle** (Contrôleur) - Entreprise de sécurité
5. **Agents de terrain** - Entreprise de sécurité (pointage, signalements)
6. **Clients** - Entreprises (SBEE, SONEB) ou particuliers avec contrats

---

## ÉTAPE 1: Modification de la Base de Données

### 1.1 Ajouter des champs utilisateur

```php
// Dans users table
- type_user: enum('interne', 'client')
- client_id: nullable (pour les clients qui ont un compte)
- is_active: boolean
- last_login_at: timestamp
- ip_adress: string
```

### 1.2 Créer migration

```php
php artisan make:migration add_fields_to_users_table
```

---

## ÉTAPE 2: Modifier le Modèle User

### 2.1 Mettre à jour User.php

- Ajouter les nouveaux champs fillable
- Ajouter les relations client()
- Ajouter les méthodes de vérification de rôle
- Ajouter les méthodes de redirection selon le rôle

---

## ÉTAPE 3: Modifier AuthController

### 3.1 Mettre à jour la méthode login()

- Vérifier le type d'utilisateur
- Gérer la redirection selon le rôle
- Enregistrer les logs de connexion
- Gérer les clients sans entreprise

### 3.2 Créer les redirections par rôle

- Super Admin → Dashboard Super Admin
- Direction → Dashboard Entreprise
- Agent → Dashboard Agent (pointages, missions)
- Client → Portal Client (suivi contrats)

---

## ÉTAPE 4: Créer les Middlewares

### 4.1 RedirectIfAuthenticated (existant à modifier)

- Gérer les redirections après connexion

### 4.2 Multi-tenant Middleware

- Vérifier que l'utilisateur appartient à une entreprise active
- Vérifier l'abonnement de l'entreprise

### 4.3 Role-based Redirect Middleware

- Rediriger selon le rôle après connexion

---

## ÉTAPE 5: Mettre à jour les Routes

### 5.1 Modifier web.php

- Grouper les routes par middleware
- Ajouter les routes pour chaque type d'utilisateur

### 5.2 Créer les routes spécifiques

- /dashboard/admin → Super Admin
- /dashboard/entreprise → Direction
- /dashboard/agent → Agents
- /dashboard/client → Clients

---

## ÉTAPE 6: Créer les Views (Dashboards)

### 6.1 Layout dynamique

- Modifier sidebar.blade.php pour afficher selon le rôle
- Modifier header.blade.php pour afficher les infos utilisateur

### 6.2 Créer les dashboards

- dashboard.superadmin.blade.php
- dashboard.entreprise.blade.php
- dashboard.agent.blade.php
- dashboard.client.blade.php

---

## ÉTAPE 7: Mettre à jour le Sidebar

### 7.1 Rendre le menu dynamique

- Afficher/masquer les éléments selon le rôle
- Utiliser @can directives de Laravel

---

## ÉTAPE 8: Tests et Validation

### 8.1 Tests unitaires

- Tester la connexion de chaque type d'utilisateur
- Tester les redirections
- Tester les permissions

---

## Fichiers à Modifier

1. `app/Models/User.php`
2. `app/Http/Controllers/AuthController.php`
3. `app/Http/Middleware/RedirectIfAuthenticated.php` (à créer)
4. `app/Http/Middleware/TenantMiddleware.php` (à créer)
5. `routes/web.php`
6. `resources/views/layouts/sidebar.blade.php`
7. `resources/views/layouts/header.blade.php`

## Fichiers à Créer

1. `database/migrations/xxxx_xx_xx_add_fields_to_users_table.php`
2. `app/Http/Middleware/RedirectIfAuthenticated.php`
3. `app/Http/Middleware/TenantMiddleware.php`
4. `resources/views/dashboard/superadmin.blade.php`
5. `resources/views/dashboard/entreprise.blade.php`
6. `resources/views/dashboard/agent.blade.php`
7. `resources/views/dashboard/client.blade.php`
