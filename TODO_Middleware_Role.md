# TODO - Création des Middleware de Rôle

## Objectif

Créer des middleware de rôle pour sécuriser l'accès aux différentes sections de l'application.

## Étapes à compléter

### 1. Créer les Middleware

- [x] 1.1 Créer `SuperAdminMiddleware` - Vérifie que l'utilisateur est SuperAdmin
- [x] 1.2 Créer `EntrepriseMiddleware` - Vérifie que l'utilisateur est un membre interne de l'entreprise (direction, superviseur, contrôleur, agent)
- [x] 1.3 Créer `ClientMiddleware` - Vérifie que l'utilisateur est un client

### 2. Enregistrer les Middleware

- [x] 2.1 Ajouter les aliases dans `bootstrap/app.php`

### 3. Mettre à jour les Contrôleurs

- [x] 3.1 Mettre à jour `FacturationController.php` (SuperAdmin)
- [x] 3.2 Mettre à jour `EntrepriseController.php` (SuperAdmin)
- [x] 3.3 Mettre à jour `RapportController.php` (SuperAdmin)
- [x] 3.4 Mettre à jour `ApkController.php` (SuperAdmin)
- [x] 3.5 Mettre à jour `NotificationController.php` (SuperAdmin)
- [x] 3.6 Mettre à jour `JournalController.php` (SuperAdmin)
- [x] 3.7 Mettre à jour `ModeleController.php` (SuperAdmin)
- [x] 3.8 Mettre à jour `UtilisateurController.php` (SuperAdmin)
- [x] 3.9 Mettre à jour `AbonnementController.php` (SuperAdmin)
- [x] 3.10 Mettre à jour `ParametreController.php` (SuperAdmin)
- [x] 3.11 Mettre à jour `SuperAdminController.php` (SuperAdmin)

### 4. Mettre à jour les Controllers Entreprise

- [x] 4.1 Mettre à jour `DashboardController.php` (Entreprise)
- [x] 4.2 Mettre à jour `ClientController.php` (Entreprise)
- [x] 4.3 Mettre à jour `EmployeController.php` (Entreprise)
- [x] 4.4 Mettre à jour `ContratController.php` (Entreprise)
- [x] 4.5 Mettre à jour `AffectationController.php` (Entreprise)
- [x] 4.6 Mettre à jour `RapportController.php` (Entreprise)

### 5. Mettre à jour les Controllers Agent

- [x] 5.1 Mettre à jour `MissionController.php` (Agent)
- [x] 5.2 Mettre à jour `PointageController.php` (Agent)
- [x] 5.3 Mettre à jour `CongeController.php` (Agent)

### 6. Mettre à jour les Controllers Client

- [x] 6.1 Mettre à jour `ContratController.php` (Client)
- [x] 6.2 Mettre à jour `FactureController.php` (Client)
- [x] 6.3 Mettre à jour `IncidentController.php` (Client)

### 7. Tester

- [x] 7.1 Vider le cache Laravel
