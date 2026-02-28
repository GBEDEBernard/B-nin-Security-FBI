# TODO - Affichage des Logos d'Entreprise

## Objectif

Afficher les logos d'entreprise dans plusieurs parties de l'application:

1. Dashboard SuperAdmin - Liste des entreprises
2. Dashboard Entreprise - Bannière de bienvenue
3. Sidebar - Quand on est en contexte entreprise
4. Gestion des utilisateurs liés à une entreprise

## Tâches

### 1. Modifier superadmin.blade.php - Ajouter logo dans liste entreprises

- [x] Comprendre la structure actuelle
- [x] Ajouter le logo dans la colonne "Entreprise" du tableau
- [x] Utiliser l'accesseur logoUrl du modèle Entreprise

### 2. Modifier entreprise.blade.php - Ajouter logo dans bannière

- [x] Comprendre la structure actuelle
- [x] Ajouter le logo dans la bannière de bienvenue
- [x] Afficher à côté du nom de l'entreprise

### 3. Modifier sidebar.blade.php - Logo en contexte entreprise

- [x] Comprendre la structure actuelle
- [x] Ajouter le logo de l'entreprise dans l'indicateur de contexte
- [x] Remplacer l'icône par le logo

### 4. Vérifier que le stockage est configuré

- [x] Vérifier config/filesystems.php
- [x] Le chemin storage/app/public est correct

## Fichiers modifiés

1. resources/views/admin/superadmin.blade.php
2. resources/views/admin/entreprise.blade.php
3. resources/views/layouts/sidebar.blade.php

## Notes

- Le modèle Entreprise a déjà un accesseur `logoUrl` qui retourne:
    - `asset('storage/' . $this->logo)` si logo existe
    - `asset('images/logo-defaut.svg')` par défaut
- Le champ `logo` dans la table entreprises stocke le chemin relatif (ex: "logos/entreprise1.png")
- Les logos des entreprises doivent être uploadés via le formulaire de création/modification d'entreprise
