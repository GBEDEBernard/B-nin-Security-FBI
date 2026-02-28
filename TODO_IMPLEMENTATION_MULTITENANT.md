# TODO - Implémentation Multi-Tenant avec Stancl/Tenancy

## Phase 1: Configuration Base de Données ✅

- [x] 1.1 stancl/tenancy installé
- [x] 1.2 spatie/laravel-permission installé
- [x] 1.3 Modifier config/database.php pour ajouter connexion 'central'
- [x] 1.4 Mettre à jour config/tenancy.php pour MySQL

## Phase 2: Migrations Tenant ✅

- [x] 2.1 Créer le dossier database/migrations/tenant/
- [x] 2.2 employes_table.php
- [x] 2.3 clients_table.php
- [x] 2.4 contrats_table.php
- [x] 2.5 factures_table.php
- [x] 2.6 affectations_table.php
- [x] 2.7 pointages_table.php
- [x] 2.8 incidents_table.php

## Phase 3: Configuration Domaines ✅

- [x] 3.1 Mettre à jour central_domains dans config/tenancy.php
- [x] 3.2 Mettre à jour routes/tenant.php

## Phase 4: Commandes et Utilitaires ✅

- [x] 4.1 Créer commande pour créer un tenant (entreprise)
- [x] 4.2 Créer migration pour ajouter tenant_id/sous_domaine
- [x] 4.3 Créer documentation MULTITENANT_SETUP.md

## Prochaines Étapes (à faire manuellement)

1. Configurer MySQL et créer la base de données centrale
2. Mettre à jour le fichier .env
3. Exécuter php artisan migrate
4. Tester la création d'un tenant
