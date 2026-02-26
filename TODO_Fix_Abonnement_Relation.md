# TODO - Correction Base de Données Relations

## Objectif

Corriger les relations selon les spécifications:

- Entreprise → Abonnement: 1:1 (chaque entreprise a son propre abonnement)
- Abonnement → Entreprise: 1:N (un abonnement peut être lié à plusieurs entreprises)

## Étapes à compléter

### 1. Migration: Inverser la relation Entreprise ↔ Abonnement

- [ ] Créer une nouvelle migration `2026_03_01_000001_fix_abonnement_entreprise_relation.php`
- [ ] Ajouter `abonnement_id` à la table `entreprises`
- [ ] Retirer `entreprise_id` de la table `abonnements`

### 2. Modifier le modèle Entreprise

- [ ] Changer `abonnement(): HasOne` → `abonnement(): BelongsTo`

### 3. Modifier le modèle Abonnement

- [ ] Changer `entreprise(): BelongsTo` → `entreprises(): HasMany`

### 4. Vérifier les controllers

- [ ] Vérifier `SuperAdmin/AbonnementController.php`
- [ ] Vérifier `SuperAdmin/EntrepriseController.php`

### 5. Tester l'application

- [ ] Vérifier la syntaxe PHP
- [ ] Tester l'accès à la page superadmin
