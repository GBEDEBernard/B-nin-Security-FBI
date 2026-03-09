# TODO - Mise à jour Page Détails Site

## Objectif

Sur les pages des Détails des Sites, afficher:

- Le Client lié à ce contrat
- Le contrat lié à ce site
- Le nombre d'agents du terrain dont le site aura besoin
- Gestion des agents affectés au site

## Tâches à effectuer

### 1. Mettre à jour SiteController.php - méthode show()

- [x] Charger les contrats avec les données du pivot (nombre_agents_site)
- [x] Charger les affectations avec les données de l'employé
- [x] Calculer les statistiques: agents requis vs agents affectés

### 2. Mettre à jour SiteClient.php

- [x] Ajouter méthode agentsRequis()
- [x] Ajouter méthode agentsAffectes()
- [x] Ajouter méthode agentsManquants()
- [x] Ajouter méthode pourcentageCouverture()

### 3. Mettre à jour show.blade.php

- [x] Ajouter section "Client lié" avec détails complets
- [x] Ajouter section "Contrats liés" avec nombre d'agents requis
- [x] Ajouter section "Agents affectés" avec liste et comparaison
- [x] Ajouter boutons pour gérer les affectations

### 4. Routes supplémentaires

- [x] Correction du AffectationController pour mapper correctement les champs
