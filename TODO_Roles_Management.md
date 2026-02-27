# TODO - Gestion des Rôles SuperAdmin

## Étapes à compléter:

- [ ]   1. Créer le contrôleur RoleController
- [ ]   2. Créer les vues (index, edit, show)
- [ ]   3. Mettre à jour le sidebar avec le lien Gestion des Rôles
- [ ]   4. Ajouter les routes dans web.php
- [ ]   5. Mettre à jour le seeder avec la permission manage_user_roles
- [ ]   6. Tester l'application

## Détails:

### 1. RoleController (app/Http/Controllers/SuperAdmin/RoleController.php)

- index() - Liste de tous les utilisateurs et employés avec leurs rôles
- show($id, $type) - Détails d'un utilisateur/employé
- edit($id, $type) - Formulaire de modification des rôles
- update() - Mettre à jour les rôles
- assignRole() - Assigner un rôle
- removeRole() - Retirer un rôle

### 2. Vues (resources/views/admin/superadmin/roles/)

- index.blade.php - Tableau avec onglets (Utilisateurs / Employés)
- edit.blade.php - Modification des rôles avec cases à cocher
- show.blade.php - Détails et historique des rôles

### 3. Sidebar

- Ajouter "Gestion des Rôles" dans le menu Administration

### 4. Routes

- GET /admin/superadmin/roles
- GET /admin/superadmin/roles/{id}/{type}
- GET /admin/superadmin/roles/{id}/{type}/edit
- PUT /admin/superadmin/roles/{id}/{type}
- POST /admin/superadmin/roles/{id}/{type}/assign
- DELETE /admin/superadmin/roles/{id}/{type}/remove
