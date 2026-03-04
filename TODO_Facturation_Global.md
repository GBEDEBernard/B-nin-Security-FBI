# TODO - Amélioration Facturation Globale

## Objectifs

1. ✅ Corriger l'affichage du client (utiliser withoutGlobalScopes)
2. ✅ Ajouter les colonnes TVA
3. ✅ Améliorer les statistiques (montant payé)
4. ✅ Ajouter fonctionnalités PDF (download/print)
5. ✅ Connecter les filtres
6. ⏳ Intégrer FEDAPAY (préparation - configuration déjà présente)

## Étapes

### 1. Mettre à jour FacturationController

- [x] Améliorer les requêtes avec withoutGlobalScopes
- [x] Calculer correctement les stats depuis PaiementFacture
- [x] Ajouter méthode generatePDF()
- [x] Ajouter méthode printPdf()

### 2. Mettre à jour la vue

- [x] Afficher le nom du client correctement
- [x] Ajouter colonne TVA (taux + montant)
- [x] Connecter les filtres au formulaire
- [x] Ajouter boutons download/print
- [x] Améliorer l'affichage des statuts

### 3. Créer template PDF

- [x] Créer vue PDF facture professionnelle
- [x] Include détails entreprise, client, contrat
- [x] Afficher historique des paiements

### 4. Mettre à jour les seeders

- [x] Ajouter nouvelles factures avec entreprise_id correct
- [x] Créer automatiquement les enregistrements de paiement
- [x] Afficher les statistiques dans le seeder

### 5. Routes

- [x] Ajouter routes pour download et print PDF
