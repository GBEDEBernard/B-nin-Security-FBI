# TODO - Implémentation Système Multi-tenant

## Phase 1: Mise à jour du Modèle User

- [x] 1.1 Ajouter les nouveaux champs au $fillable
- [x] 1.2 Ajouter les casts pour les nouveaux champs
- [x] 1.3 Ajouter la relation client()
- [x] 1.4 Ajouter les méthodes de vérification de rôle
- [x] 1.5 Ajouter les méthodes de redirection

## Phase 2: Mise à jour du AuthController

- [x] 2.1 Ajouter l'enregistrement du last_login_at et last_login_ip
- [x] 2.2 Ajouter la vérification is_active
- [x] 2.3 Ajouter la redirection selon le rôle après connexion

## Phase 3: Création des Middlewares

- [x] 3.1 Créer TenantMiddleware
- [x] 3.2 Créer RoleBasedRedirect middleware
- [x] 3.3 Enregistrer les middlewares dans le kernel

## Phase 4: Mise à jour des Routes

- [x] 4.1 Créer les routes pour chaque type de dashboard
- [x] 4.2 Ajouter les middlewares aux routes

## Phase 5: Création des Dashboards

- [x] 5.1 Créer dashboard.superadmin.blade.php
- [x] 5.2 Créer dashboard.entreprise.blade.php
- [x] 5.3 Créer dashboard.agent.blade.php
- [x] 5.4 Créer dashboard.client.blade.php
- [x] 5.5 Créer les pages enfants des dashboards

## Phase 6: Mise à jour du Sidebar

- [x] 6.1 Rendre le menu dynamique selon le rôle
- [x] 6.2 Ajouter les conditions pour chaque section
