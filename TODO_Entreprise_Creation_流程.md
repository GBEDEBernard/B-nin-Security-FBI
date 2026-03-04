# TODO: Flux de création d'entreprise avec Abonnement et Paiement FEDAPAY

## Étape 1: Corriger la validation du contrôleur Entreprise

- [x] Modifier EntrepriseController.store() - Rendre formule, nombre_agents_max, nombre_sites_max optionnels
- [x] Modifier la validation pour accepter les champs optionnels

## Étape 2: Mettre à jour le formulaire de création d'entreprise

- [x] Les champs d'abonnement sont maintenant optionnels dans le contrôleur
- [x] La redirection se fait vers la page d'abonnement après création

## Étape 3: Configuration FEDAPAY

- [x] Ajouter la configuration FEDAPAY dans config/services.php
- [ ] Créer les clés API dans .env (à faire par l'utilisateur)

## Étape 4: Contrôleur de paiement FEDAPAY

- [x] Créer EntrepriseController.abonnement() - Page de choix d'abonnement
- [x] Créer EntrepriseController.choisirFormule() - Traiter le choix
- [x] Créer EntrepriseController.payer() - Afficher le formulaire de paiement
- [x] Créer EntrepriseController.initierPaiement() - Initier le paiement FEDAPAY
- [x] Créer EntrepriseController.callback() - Callback de FEDAPAY
- [x] Créer EntrepriseController.simulerPaiement() - Pour les tests

## Étape 5: Vues

- [x] Vue abonnement.blade.php - Page de choix de formule
- [x] Vue payer.blade.php - Page de paiement FEDAPAY
- [x] Vue paiement-succes.blade.php - Page de confirmation
- [x] Vue facture.blade.php - Facture détaillée

## Étape 6: Routes

- [x] Ajouter les routes dans web.php

## Étape 7: Tests et validation

- [ ] Tester la création d'entreprise
- [ ] Tester le flux d'abonnement
- [ ] Tester le paiement FEDAPAY
- [ ] Vérifier la génération de facture

---

## Notes:

- Formule ESSAI: Gratuit, 30 jours, 5 agents max, 2 sites max
- Formule BASIC: 100,000 CFA/mois, 20-40 agents, 5 sites
- Formule STANDARD: 150,000 CFA/mois, 41-100 agents, 10 sites
- Formule PREMIUM: 200,000 CFA/mois, 101-300 agents, illimité sites

## Configuration FEDAPAY:

Pour utiliser FEDAPAY en production, ajouter dans .env:

```
FEDAPAY_API_KEY=votre_api_key
FEDAPAY_SECRET_KEY=votre_secret_key
FEDAPAY_ENVIRONMENT=sandbox  # ou production
```
