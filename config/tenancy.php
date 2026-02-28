<?php

declare(strict_types=1);

use Stancl\Tenancy\Database\Models\Domain;
use Stancl\Tenancy\Database\Models\Tenant;

return [
    /**
     * Configuration Multi-Tenant pour Benin-Security
     * 
     * Ce projet utilise une approche HYBRIDE:
     * - Base de données CENTRALE pour: entreprises, abonnements, utilisateurs SuperAdmin
     * - Isolation par entreprise_id pour les données opérationnelles
     * 
     * Le package stancl/laravel-tenancy est configuré mais l'isolation
     * des données se fait principalement via le champ entreprise_id
     */

    // Modèle Tenant - lien vers Entreprise
    'tenant_model' => App\Models\Entreprise::class,

    // Générateur d'ID
    'id_generator' => Stancl\Tenancy\UUIDGenerator::class,

    'domain_model' => Domain::class,

    /**
     * Domaines centraux
     */
    'central_domains' => [
        '127.0.0.1',
        'localhost',
        'benin-security.com',
        'www.benin-security.com',
    ],

    /**
     * Bootstrappers - DESACTIVÉS
     * On utilise l'approche par colonne entreprise_id
     */
    'bootstrappers' => [
        // Décommenter si vous voulez utiliser database-per-tenant:
        // Stancl\Tenancy\Bootstrappers\DatabaseTenancyBootstrapper::class,
    ],

    /**
     * Database config - Configuration centrale
     */
    'database' => [
        'central_connection' => 'mysql',
        'template_tenant_connection' => null,
        'prefix' => 'benin_security_',
        'suffix' => '',
        'managers' => [
            'sqlite' => Stancl\Tenancy\TenantDatabaseManagers\SQLiteDatabaseManager::class,
            'mysql' => Stancl\Tenancy\TenantDatabaseManagers\MySQLDatabaseManager::class,
            'pgsql' => Stancl\Tenancy\TenantDatabaseManagers\PostgreSQLDatabaseManager::class,
        ],
    ],

    /**
     * Cache config
     */
    'cache' => [
        'tag_base' => 'tenant',
    ],

    /**
     * Filesystem config
     */
    'filesystem' => [
        'suffix_base' => 'tenant',
        'disks' => [
            'local',
            'public',
        ],
        'root_override' => [
            'local' => '%storage_path%/app/',
            'public' => '%storage_path%/app/public/',
        ],
        'suffix_storage_path' => true,
        'asset_helper_tenancy' => true,
    ],

    /**
     * Redis config
     */
    'redis' => [
        'prefix_base' => 'tenant',
        'prefixed_connections' => [],
    ],

    /**
     * Features - DESACTIVÉS
     */
    'features' => [
        // Stancl\Tenancy\Features\UserImpersonation::class,
        // Stancl\Tenancy\Features\TelescopeTags::class,
        // Stancl\Tenancy\Features\UniversalRoutes::class,
        // Stancl\Tenancy\Features\TenantConfig::class,
    ],

    /**
     * Routes - Désactivé
     */
    'routes' => false,

    /**
     * Migration parameters
     */
    'migration_parameters' => [
        '--force' => true,
        '--path' => [database_path('migrations/tenant')],
        '--realpath' => true,
    ],

    /**
     * Seeder parameters
     */
    'seeder_parameters' => [
        '--class' => 'DatabaseSeeder',
    ],
];
