# TODO - Réorganisation du Sidebar Super Admin

## Étape 1: Retirer "Contrats" du menu Super Admin

- [x] Analyser le sidebar existant
- [x] Supprimer la section "Gestion des Contrats" du menu Super Admin

## Étape 2: Nettoyer les doublons dans le sidebar

- [x] Supprimer les sections en double (Abonnements, Facturation, Rapports)
- [x] Conserver une seule instance de chaque menu

## Étape 3: Améliorer la page des Abonnements

- [x] Ajouter des liens fonctionnels (create, edit, show)
- [x] Afficher le nombre d'agents actuels vs maximum
- [x] Ajouter des boutons suspendre/activer
- [x] Ajouter une barre de progression pour l'utilisation des agents
- [x] Afficher les alertes pour les dates d'expiration proches

## Étape 4: Vérification finale

- [x] Les routes sont correctement configurées
- [x] Les formulaires utilisent POST (pas PATCH)

## Étape 5: Dashboard Super Admin - mise à jour vers les Abonnements

- [x] Remplacer "Contrats Actifs" par "Abonnements Actifs" dans les statistiques
- [x] Ajouter le lien "Abonnements" dans les Actions Rapides
- [x] Renommer "Évolution des Contrats" en "Évolution des Abonnements"
- [x] Renommer "Statut des Contrats" en "Statut des Abonnements"
- [x] Remplacer les statistiques "Contrats" par "Abonnements" dans les autres statistiques
- [x] Ajouter la colonne "Abonnement" dans le tableau des entreprises
- [x] Ajouter un bouton pour voir l'abonnement de chaque entreprise

## Étape 6: Création des vues manquantes

- [x] Créer la vue show.blade.php (détails d'un abonnement)
- [x] Créer la vue edit.blade.php (modifier un abonnement)
- [x] Créer la vue create.blade.php (nouvel abonnement)

## Résumé final

Toutes les modifications ont été effectuées avec succès!
