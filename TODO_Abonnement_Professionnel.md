# TODO - Refonte Professionnelle des Abonnements

## Objectif
Centraliser toute la logique d'abonnement sur le modèle `Abonnement`, automatiser le cycle de vie, la facturation, les notifications, et sécuriser le système.

---

## Terminé ✅

### 1. Centralisation sur le modèle Abonnement
- [x] Migration : Supprimer les champs dupliqués sur `entreprises` (formule, nombre_agents_max, nombre_sites_max, date_debut_contrat, date_fin_contrat, montant_mensuel, cycle_facturation, est_en_essai, date_fin_essai)
- [x] Modèle `Entreprise` : retiré les champs du `$fillable`, ajouté des accesseurs de délégation vers `abonnement`
- [x] Mise à jour des contrôleurs et vues qui référencent ces champs

### 2. Migration : Ajouter abonnement_id à Facture
- [x] Création de la migration `2026_06_26_000003_add_abonnement_id_to_factures.php`
- [x] Modèle `Facture` : ajout de la relation `BelongsTo` vers `Abonnement`
- [x] Modèle `Abonnement` : relation `HasMany` factures déjà existante

### 3. Commande Artisan : Vérification des abonnements
- [x] `app/Console/Commands/VerifierAbonnements.php`
  - [x] Expire auto les abonnements dépassés
  - [x] Clôture les essais arrivés à terme
  - [x] Envoie des rappels 7 jours et 1 jour avant expiration
- [x] Planifié dans `routes/console.php` avec `->dailyAt('06:00')`

### 4. Notifications Email
- [x] `app/Notifications/AbonnementExpirationImminente.php` (7j et 1j avant)
- [x] `app/Notifications/AbonnementEtatChange.php` (suspendu/activé/résilié/essai clôturé)

### 5. Commande Artisan : Facturation automatique
- [x] `app/Console/Commands/GenererFacturesAbonnements.php`
  - [x] Génère les factures pour les abonnements actifs
  - [x] Respecte le `cycle_facturation`
  - [x] Lie chaque facture à `abonnement_id`
- [x] Planifié dans `routes/console.php` avec `->monthlyOn(1, '02:00')`

### 6. Middleware de vérification d'abonnement
- [x] `app/Http/Middleware/VerifierAbonnement.php`
- [x] Vérifie `$entreprise->abonnement?->est_valide`
- [x] Redirige vers `abonnement.requis` si abonnement expiré
- [x] Enregistré dans `bootstrap/app.php` comme alias `verifier.abonnement`
- [x] Route `abonnement.requis` créée avec vue dédiée

### 7. Sécurisation
- [x] Empêche la suppression d'un abonnement lié à des entreprises
- [x] Logger toutes les actions dans `activity_log` (ActivityLog model créé)
- [x] Vérifie dans `EntrepriseController@store` que l'abonnement assigné est actif
- [x] Mise à jour de `PropositionContratController@creerEntreprise` pour créer un Abonnement

### 8. Analytics et Rapports
- [x] MRR (Monthly Recurring Revenue) sur la page index des abonnements
- [x] Taux de churn : (résiliés du mois / total actifs début mois) × 100
- [x] Distribution des formules avec comptage
- [x] Alertes agents (≥ 80% de la limite atteinte)
- [x] Stats dashboard superadmin mises à jour (formule via Abonnement)

---

## Notes
- Tous les fichiers ci-dessus sont sur la branche `feature/abonnement-professionnel`
- Pour activer les changements : `php artisan migrate`
- Pour tester les commandes : `php artisan abonnements:verifier` et `php artisan abonnements:facturer`
- Pour le scheduler en production : ajouter `* * * * * cd /chemin/projet && php artisan schedule:run >> /dev/null 2>&1` au crontab
