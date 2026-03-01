# TODO - Nettoyage et uniformisation

## Tâches terminées

### 1. Uniformisation des pages de profil (TERMINÉ)

- [x] Modifier `utilisateurs/show.blade.php` pour correspondre au design de `profile.blade.php`
- [x] Modifier `header.blade.php` pour le gradient violet

### 2. Supprimer l'onglet Abonnement du formulaire Entreprise (TERMINÉ)

- [x] Supprimer l'onglet "Abonnement" dans `entreprises/create.blade.php`
- Les entreprises sont maintenant enregistrées séparément sans formulaire d'abonnement
- Les abonnements peuvent être gérés dans la section "Abonnements"

### 3. CRUD Abonnements fonctionnel (TERMINÉ)

**Nouveau fichier créé :**

- `database/seeders/AbonnementSeeder.php` - Crée les plans d'abonnement et lie les entreprises

**Fichier modifié :**

- `database/seeders/DatabaseSeeder.php` - Ajout du AbonnementSeeder

**Fonctionnalités :**

- Les plans d'abonnement (Essai, Basic, Standard, Premium, Enterprise) sont créés automatiquement
- Les entreprises existantes sont liées aux abonnements correspondants via `abonnement_id`
- Le CRUD des abonnements fonctionne via les pages existantes

**Pour tester, exécuter :**

```bash
php artisan migrate:fresh --seed
```

### 4. Pages d'abonnements (Pages existantes)

Les pages d'abonnements (index, create, edit, show) sont déjà bien conçues et fonctionnelles.



##
il faut me refaire les abonnement d'une manière où pour les different abonnement que le montant à gagné par mois sois caclcueler par le nombre d'agent d'employés au complet ( directeur , supersviseur , agent ) que cette entreprise veux utilsé avec le notre plateforme , par exemeple sur un employés par moi on peut gagné 100000, ou 150000 ; sa depent et ou bien 200000f ; pour les stanard on peut dire que c'est 20 à 40 c'est 100000 , premieu 40 à 100 employés nous c'est 150000f  ainsi de suite  ; lors des entregistrement des employés si cela veux depasser le nombre d'employés normal pour abonnement choisir un message vient pour le notifié et lui dire de passé à l'abonnement suivant    ; nous remplissons comme ça et avant d'entregister une entreprise nous allons choisir ce le type d'abonnement que l'entreprse veux avec nous , : le mode basic c'est pour un temps par exemple 3 mois et mode essayé fini  donc faire moi un bon crud de abomment avec des bonne disgn bien propre et aussi les crud des Entreprise aussi bien propre