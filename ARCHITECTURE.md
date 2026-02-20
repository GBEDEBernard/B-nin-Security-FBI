# Architecture du Système de Sécurité - Bénin Security

## Vue d'ensemble

Ce document décrit l'architecture complète du système de gestion des opérations de sécurité avec ses acteurs, rôles et permissions.

## Acteurs du Système

### 1. Acteurs Internes (Par Entreprise Cliente / Tenant)

Ces utilisateurs appartiennent à une entreprise cliente spécifique et gèrent les opérations de sécurité.

#### 1.1 Directeur Général (General Director)

- **Description**: Chef de l'entreprise cliente, accès administratif complet au sein de son tenant
- **Rôle**: `general_director`
- **Responsabilités**:
    - Administration complète du système du tenant
    - Gestion de tous les utilisateurs
    - Validation des opérations critiques
    - Accès à tous les rapports et analytics

#### 1.2 Directeur Adjoint (Deputy Director)

- **Description**: Second en charge, gestion opérationnelle et validation
- **Rôle**: `deputy_director`
- **Responsabilités**:
    - Gestion opérationnelle de jour
    - Validation des missions et affectations
    - Support au Directeur Général
    - Accès aux rapports analytiques

#### 1.3 Directeur des Opérations (Operations Director)

- **Description**: Planification et affectation des agents
- **Rôle**: `operations_director`
- **Responsabilités**:
    - Planification des missions
    - Affectation des agents aux missions
    - Gestion des shifts
    - Suivi des opérations en temps réel

#### 1.4 Superviseurs (Supervisors)

- **Description**: Encadrement direct des agents de sécurité
- **Rôle**: `supervisor`
- **Responsabilités**:
    - Encadrement et suivi des agents
    - Validation des missions
    - Rapports d'activité quotidiens
    - Coordinations avec les agents

#### 1.5 Contrôleurs (Controllers)

- **Description**: Vérification terrain et conformité
- **Rôle**: `controller`
- **Responsabilités**:
    - Effectuer des rondes de vérification
    - Vérifier la conformité des missions
    - Générer des rapports de conformité
    - Validation des documents terrain

#### 1.6 Agents de Sécurité (Security Agents)

- **Description**: Exécution des missions sur le terrain
- **Rôle**: `agent`
- **Responsabilités**:
    - Effectuer les missions affectées
    - Soumettre les rapports de missions
    - Télécharger les documents (photos, rapports)
    - Consulter leur tableau de bord personnel

### 2. Acteurs Externes

Ces utilisateurs accèdent au système depuis l'extérieur pour consulter les services de sécurité.

#### 2.1 Clients Particuliers (Individual Clients)

- **Description**: Personnes physiques utilisant les services de sécurité
- **Rôle**: `client_individual`
- **Responsabilités**:
    - Consulter le statut des missions
    - Accès aux documents
    - Consultation des rapports
    - Pas d'accès aux analytics

#### 2.2 Clients Entreprises (Company Clients)

- **Description**: Entreprises (SBEE, SONEB, SOMEMAP, etc.) utilisant les services
- **Rôle**: `client_company`
- **Responsabilités**:
    - Consulter le statut des missions
    - Accès aux documents
    - Consultation des rapports avancés
    - Accès aux analytics
    - Consultation des paiements

### 3. Super Administrateur (Super Admin)

- **Description**: Gestionnaire de la plateforme entière (équipe Bénin Security)
- **Rôle**: `super_admin`
- **Responsabilités**:
    - Gestion complète du système
    - Gestion des tenants (clients)
    - Gestion des utilisateurs globalement
    - Configuration globale du système
    - Audit complet du système

## Structure des Données

### Modèles Principaux

```
Tenant (Client / Entreprise)
├── Agencies (Agences de sécurité)
├── Managers (Directeurs, Directeurs Adjoints, Directeurs des Opérations)
├── Supervisors (Superviseurs)
├── Controllers (Contrôleurs)
├── Agents (Agents de Sécurité)
├── Clients (Clients Particuliers et Entreprises)
├── Shifts (Équipes de travail)
├── Assignments (Missions / Affectations)
├── Documents (Documents et rapports)
└── Vehicles (Véhicules de patrouille)

Users (Table Centrale)
├── User → Tenant (Multi-tenant)
├── User → Agent (si l'utilisateur est un agent)
├── User → Supervisor (si l'utilisateur est un superviseur)
├── User → Controller (si l'utilisateur est un contrôleur)
└── User → Manager (si l'utilisateur est un gestionnaire)
```

### Tables de Base de Données

1. **users**: Table d'authentification
    - id, tenant_id, name, email, password, phone, avatar_path

2. **managers**: Directeur Général, Adjoint, Opérations
    - id, tenant_id, user_id, agency_id, manager_type, full_name, email, salary_per_month, ...

3. **supervisors**: Superviseurs
    - id, tenant_id, user_id, agency_id, full_name, email, salary_per_month, ...

4. **controllers**: Contrôleurs
    - id, tenant_id, user_id, agency_id, full_name, email, salary_per_month, ...

5. **agents**: Agents de Sécurité
    - id, tenant_id, agency_id, full_name, email, salary_per_day, position, ...

6. **clients**: Clients (Particuliers ou Entreprises)
    - id, tenant_id, agency_id, type (individual/company), name, email, ...

## Rôles et Permissions

### 9 Rôles Disponibles

| Rôle                    | Description            | Permissions                |
| ----------------------- | ---------------------- | -------------------------- |
| **super_admin**         | Admin système global   | Absolument tout            |
| **general_director**    | PDG du tenant          | Gestion complète du tenant |
| **deputy_director**     | Gestion opérationnelle | Gestion des opérations     |
| **operations_director** | Planification          | Missions et affectations   |
| **supervisor**          | Encadrement            | Agents et missions         |
| **controller**          | Vérification           | Validation et conformité   |
| **agent**               | Terrain                | Exécution des missions     |
| **client_individual**   | Client particulier     | Consultation des missions  |
| **client_company**      | Client entreprise      | Consultation+ + analytics  |

### Permissions Disponibles (47 permissions)

#### Dashboard & Access (2)

- `view_dashboard`
- `access_platform`

#### Users Management (5)

- `view_users`, `create_users`, `edit_users`, `delete_users`, `manage_user_roles`

#### Staff Management (12)

- Managers: `view_managers`, `create_managers`, `edit_managers`, `delete_managers`
- Supervisors: `view_supervisors`, `create_supervisors`, `edit_supervisors`, `delete_supervisors`
- Controllers: `view_controllers`, `create_controllers`, `edit_controllers`, `delete_controllers`

#### Agents Management (5)

- `view_agents`, `create_agents`, `edit_agents`, `delete_agents`, `manage_agent_status`

#### Agencies & Clients (8)

- Agencies: `view_agencies`, `create_agencies`, `edit_agencies`, `delete_agencies`
- Clients: `view_clients`, `create_clients`, `edit_clients`, `delete_clients`, `manage_client_status`

#### Assignments/Missions (6)

- `view_assignments`, `create_assignments`, `edit_assignments`, `delete_assignments`, `assign_agents`, `validate_assignments`

#### Shifts & Documents (7)

- Shifts: `view_shifts`, `create_shifts`, `edit_shifts`, `delete_shifts`
- Documents: `view_documents`, `create_documents`, `edit_documents`, `delete_documents`, `upload_documents`

#### Vehicles & Reports (6)

- Vehicles: `view_vehicles`, `create_vehicles`, `edit_vehicles`, `delete_vehicles`
- Reports: `view_reports`, `create_reports`, `export_reports`, `view_analytics`

#### Tenant Management (6) - Super Admin Only

- `view_tenants`, `create_tenants`, `edit_tenants`, `delete_tenants`, `manage_tenant_users`, `manage_tenant_settings`

#### System Settings (3)

- `view_settings`, `edit_settings`, `view_audit_logs`

#### Payments (3)

- `view_payments`, `create_invoices`, `manage_billing`

#### Personal (3)

- `view_personal_dashboard`, `update_personal_profile`, `view_my_assignments`

## Seeders et Données de Test

### Seeders Disponibles

1. **RolesAndPermissionsSeeder**
    - Crée toutes les permissions
    - Crée tous les 9 rôles
    - Configure les permissions pour chaque rôle

2. **SuperAdminSeeder**
    - Crée un Super Admin de test
    - Crée 8 utilisateurs de test (un pour chaque rôle)
    - Assigne les rôles appropriés

### Utilisateurs de Test

#### Super Admin

- Email: `admin@benin-security.local`
- Mot de passe: `admin@BenSecure2026`
- Rôle: `super_admin`

#### Utilisateurs Tenant Test

- Emails: `{role}@benin-security.local`
- Mot de passe: `test@BenSecure2026`
- Rôles disponibles:
    - general-director@benin-security.local (Directeur Général)
    - deputy-director@benin-security.local (Directeur Adjoint)
    - operations-director@benin-security.local (Directeur des Opérations)
    - supervisor@benin-security.local (Superviseur)
    - controller@benin-security.local (Contrôleur)
    - agent@benin-security.local (Agent de Sécurité)
    - client-individual@benin-security.local (Client Particulier)
    - client-company@benin-security.local (Client Entreprise)

## Commandes d'Exécution

### Exécuter toutes les migrations

```bash
php artisan migrate
```

### Exécuter les seeders

```bash
php artisan db:seed
```

### Exécuter les migrations + seeders (Fresh)

```bash
php artisan migrate:fresh --seed
```

### Voir tous les rôles et permissions

```bash
php artisan tinker
# dans tinker:
>>> Spatie\Permission\Models\Role::all();
>>> Spatie\Permission\Models\Permission::all();
```

### Assigner un rôle à un utilisateur

```bash
$user = App\Models\User::find(1);
$user->assignRole('agent');
```

### Vérifier les rôles/permissions d'un utilisateur

```bash
$user = App\Models\User::find(1);
$user->getRoleNames(); // Voir les rôles
$user->getPermissionNames(); // Voir les permissions
$user->hasPermissionTo('view_dashboard'); // Vérifier une permission
```

## Flux de Connexion et Autorisation

1. **Authentification**: Utilisateur se connecte avec email/mot de passe
2. **Rôle**: Le rôle est chargé via la relation `roles` de Spatie Permission
3. **Permissions**: Les permissions associées au rôle sont chargées
4. **Middleware**: Les routes utilisent `auth()` et les middlewares de permission
5. **Accès**: L'utilisateur accède au contenu selon ses permissions

### Exemple de Protection de Route

```php
Route::middleware(['auth', 'permission:view_agents'])->get('/agents', [AgentController::class, 'index']);
Route::middleware(['auth', 'role:operations_director'])->post('/assignments', [AssignmentController::class, 'store']);
```

## Points Importants

1. **Multi-tenant**: Chaque utilisateur doit avoir un `tenant_id` (sauf Super Admin)
2. **Rôles Hiérarchiques**: Les rôles sont organisés hiérarchiquement dans chaque tenant
3. **Super Admin**: L'unique utilisateur global avec accès à tout
4. **Permissions Granulaires**: 47 permissions permettent un contrôle précis
5. **Audit**: Tous les modèles ont `timestamps` et peuvent être audités

## Notes de Sécurité

- Assurez-vous que les mots de passe de test sont changés en production
- Utilisez les migrations fraîches en développement seulement
- Protégez les routes sensibles par des middlewares d'authentification et d'autorisation
- Testez toujours les permissions avant de déployer en production
