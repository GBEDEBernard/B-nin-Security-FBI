# TODO: CRUD Contrats et Sites

## Objectifs

- CRUD Complet pour les Contrats (Entreprise)
- CRUD Complet pour les Sites (Entreprise)
- Application des règles métier

## Tâches

### 1. Modèles

- [x] Client.php - Vérifier relations existantes
- [x] ContratPrestation.php - Vérifier relations existantes
- [x] SiteClient.php - Vérifier relations existantes

### 2. Contrôleurs

- [x] Entreprise/ContratController - Mettre à jour avec CRUD complet
- [x] Entreprise/SiteController - Créer nouveau contrôleur

### 3. Vues Contrats Entreprise

- [x] index.blade.php - Remplacer avec vue complète
- [x] create.blade.php - Créer formulaire
- [x] show.blade.php - Créer détails contrat
- [x] edit.blade.php - Créer formulaire modification

### 4. Vues Sites Entreprise

- [x] index.blade.php - Liste des sites
- [x] create.blade.php - Créer site
- [x] show.blade.php - Détails site
- [x] edit.blade.php - Modifier site

### 5. Routes

- [x] Ajouter routes pour contrat
- [x] Ajouter routes pour site

### 6. Correction erreur 500

- [x] Ajouter champ 'cree_par' dans ContratController.store() - Corrigé

### 7. Mise à jour sidebar

- [ ] Ajouter menu Contrats (optionnel - à faire manuellement)
- [ ] Ajouter menu Sites (optionnel - à faire manuellement)
