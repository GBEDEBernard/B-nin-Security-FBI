# TODO - Implémentation du Système d'Abonnement

## Objectif

Implémenter un système d'abonnement avec 3 formules à prix fixe :

- **Basic**: 20-40 employés → 100 000 F/mois (durée limitée)
- **Premium**: 41-100 employés → 150 000 F/mois
- **Enterprise**: 101-300 employés → 200 000 F/mois

## Étapes d'implémentation

### 1. Mise à jour du Modèle Abonnement ✅

- [x] Modifier les constantes pour les nouvelles formules
- [x] Ajouter la logique de tarification fixe
- [x] Mettre à jour les accesseurs et méthodes

### 2. Seeder des Abonnements ✅

- [x] Mettre à jour AbonnementSeeder avec les 3 nouvelles formules

### 3. Middleware de Vérification ✅

- [x] Créer CheckAbonnementLimite middleware
- [x] Bloquer l'ajout d'employé si limite atteinte
- [x] Enregistrer le middleware dans bootstrap/app.php

### 4. Routes ✅

- [x] Ajouter le middleware aux routes d'ajout d'employés

### 5. Modèle Entreprise ✅

- [x] Ajouter des méthodes helpers pour la gestion des limites

---

## Détails des Formules

| Formule    | Employés Min | Employés Max | Prix Mensuel | Durée       |
| ---------- | ------------ | ------------ | ------------ | ----------- |
| Basic      | 20           | 40           | 100 000 F    | 3 mois      |
| Premium    | 41           | 100          | 150 000 F    | Non limitée |
| Enterprise | 101          | 300          | 200 000 F    | Non limitée |

---

## Prochaines étapes (optionnelles)

- Créer une vue pour afficher les limites d'abonnement dans le dashboard Entreprise
- Ajouter des notifications quand l'entreprise approche de sa limite
- Créer une fonctionnalité de mise à niveau automatique

## Commandes à exécuter après le déploiement

```bash
# Recharger les seeds avec les nouvelles formules
php artisan migrate:fresh --seed

# Ou juste seeder les abonnements
php artisan db:seed --class=AbonnementSeeder
```
