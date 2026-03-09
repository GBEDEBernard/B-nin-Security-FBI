# Plan: Contrats - Sites et Prix par Agent

## Objectif

Implémenter l'affichage du nombre de sites et le calcul automatique du prix dans les contrats:

- SuperAdmin: Contrats (index, create, edit, show)
- Entreprise: Contrats (index, create, edit)

## Tâches à effectuer

### 1. Migration - Ajouter prix_par_agent

- [ ] Ajouter le champ `prix_par_agent` à la table `contrats_prestation`

### 2. Modèle - Mettre à jour ContratPrestation

- [ ] Ajouter `prix_par_agent` au tableau `$fillable`
- [ ] Ajouter un mutator/accesseur pour le calcul automatique du montant

### 3. Controller SuperAdmin - ContratController

- [ ] Mettre à jour `store()` pour calculer le montant basé sur nombre_agents_requis × prix_par_agent
- [ ] Mettre à jour `update()` pour recalculer automatiquement

### 4. Controller Entreprise - ContratController

- [ ] Mettre à jour `store()` pour calculer automatiquement
- [ ] Mettre à jour `update()` pour recalculer

### 5. Vues SuperAdmin

- [ ] `index.blade.php` - Ajouter colonne Nombre de sites et Prix/agent
- [ ] `create.blade.php` - Ajouter champ prix_par_agent et calcul auto
- [ ] `edit.blade.php` - Ajouter champ prix_par_agent et calcul auto
- [ ] `show.blade.php` - Afficher les détails nombre_sites et prix_par_agent

### 6. Vues Entreprise

- [ ] `index.blade.php` - Ajouter colonne Nombre de sites et Prix/agent
- [ ] `create.blade.php` - Ajouter champ prix_par_agent et calcul auto
- [ ] `edit.blade.php` - Ajouter champ prix_par_agent et calcul auto

## Détails techniques

### Calcul du montant

```
montant_mensuel_ht = nombre_agents_requis * prix_par_agent
montant_mensuel_ttc = montant_mensuel_ht * (1 + tva/100)
montant_annuel_ht = montant_mensuel_ht * 12
```

### Prix par agent par défaut

- Valeur par défaut suggérée: 50000 FCAF (comme mentionné par l'utilisateur)

## Ordre de priorité

1. Migration
2. Modèle
3. Controllers
4. Vues SuperAdmin
5. Vues Entreprise
