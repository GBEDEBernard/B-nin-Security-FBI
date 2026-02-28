# Configuration Multi-Tenant - Benin-Security

## Architecture

```
┌─────────────────────────────────────────────────────────────────────┐
│                    SERVEUR CENTRAL (benin-security.com)            │
│  Base de données: benin_security_central                           │
│  ├── users (SuperAdmins)                                           │
│  ├── tenants (référentiel des entreprises)                         │
│  ├── domains (sous-domaines)                                       │
│  ├── entreprises                                                    │
│  ├── abonnements                                                   │
│  └── permissions (Spatie)                                          │
└─────────────────────────────────────────────────────────────────────┘
                              │
           ┌──────────────────┼──────────────────┐
           │                  │                  │
           ▼                  ▼                  ▼
    ┌──────────────┐  ┌──────────────┐  ┌──────────────┐
    │   Tenant 1    │  │   Tenant 2    │  │   Tenant 3    │
    │ sec-alpha    │  │ sec-beta     │  │ sec-gamma   │
    │ .benin-      │  │ .benin-     │  │ .benin-     │
    │ security.com │  │ security.com│  │ security.com│
    └──────────────┘  └──────────────┘  └──────────────┘
    Base de données:   Base de données:   Base de données:
    benin_security_    benin_security_    benin_security_
    tenant_xxx        tenant_xxx        tenant_xxx
```

## Installation

### 1. Configuration de la base de données

Assurez-vous d'avoir MySQL installé et créez la base de données centrale:

```sql
CREATE DATABASE benin_security_central CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 2. Mettre à jour le fichier .env

```env
# Configuration base de données centrale
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=benin_security_central
DB_USERNAME=root
DB_PASSWORD=votre_mot_de_passe

# Configuration Tenancy
TENANCY_DATABASE_PREFIX=benin_security_
```

### 3. Exécuter les migrations

```bash
# Migrer la base centrale
php artisan migrate

# Installer les tables de tenancy
php artisan tenancy:install
```

### 4. Exécuter les seeders

```bash
php artisan db:seed --class=DatabaseSeeder
php artisan db:seed --class=SuperAdminSeeder
```

## CommandesUtiles

### Créer un nouveau tenant (entreprise)

```bash
# Créer un tenant pour l'entreprise ID 1
php artisan tenant:create 1

# Avec un sous-domaine personnalisé
php artisan tenant:create 1 --domain=securite-alpha
```

### Migrer les tenants

```bash
# Migrer tous les tenants
php artisan tenants:migrate

# Migrer un tenant spécifique
php artisan tenants:migrate --tenants=tenant_id
```

### Gérer les domaines

```bash
# Lister les tenants
php artisan tenant:list

# Passer à un tenant (pour debugging)
php artisan tenant:switch tenant_id
```

## Structure des URLs

| Type       | URL                                                    | Description          |
| ---------- | ------------------------------------------------------ | -------------------- |
| SuperAdmin | `benin-security.com/admin/superadmin/*`                | Dashboard central    |
| Entreprise | `securite-alpha.benin-security.com/admin/entreprise/*` | Dashboard entreprise |
| Agent      | `securite-alpha.benin-security.com/admin/agent/*`      | Dashboard agent      |
| Client     | `securite-alpha.benin-security.com/admin/client/*`     | Dashboard client     |

## Développement local

### Configuration hosts

Ajoutez les domaines à votre fichier `/etc/hosts`:

```
127.0.0.1 benin-security.com
127.0.0.1 securite-alpha.benin-security.com
127.0.0.1 sec-beta.benin-security.com
```

### Serveur de développement

```bash
# Démarrer le serveur
php artisan serve --host=benin-security.com

# Ou avec Laravel Valet
valet link benin-security
valet link securite-alpha
```

## Tables par Tenant

Les tables suivantes sont automatiquement créées pour chaque tenant:

- `employes` - Personnel de l'entreprise
- `clients` - Clients de l'entreprise
- `contrats` - Contrats de prestation
- `factures` - Factures émises
- `affectations` - Affectations employés aux sites
- `pointages` - Heures de travail
- `incidents` - Incidents signalés

## Rôles et Permissions

Les rôles sont gérés par Spatie Laravel Permission et sont **isolés par tenant**:

- **Direction** - Accès complet
- **Superviseur** - Gestion équipe
- **Contrôleur** - Validation pointages
- **Agent** - Missions et pointages
- **Client** - Consultation propres données

## Résolution de problèmes

### Erreur de connexion à la base de données

Vérifiez que:

1. MySQL est en cours d'exécution
2. Les identifiants dans `.env` sont corrects
3. La base de données `benin_security_central` existe

### Le domaine n'est pas reconnu

1. Vérifiez le fichier `/etc/hosts`
2. Effacez le cache: `php artisan config:clear`
3. Vérifiez la configuration dans `config/tenancy.php`

### Les migrations tenant ne fonctionnent pas

```bash
# Recréer les fichiers de migration tenant
php artisan tenant:install

# Vérifier les migrations
php artisan tenant:migrate:status
```
