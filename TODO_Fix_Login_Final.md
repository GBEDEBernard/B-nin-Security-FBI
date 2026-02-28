# TODO - Fix Login & Authentication

## Problèmes identifiés:

1. La route / ne redirige pas correctement vers login
2. Des vues inutilisées existent (welcome.blade.php)
3. Il faut nettoyer le projet et vérifier que l'authentification fonctionne

## Tâches à accomplir:

### 1. Nettoyer les vues inutilisées

- [ ] Supprimer resources/views/welcome.blade.php
- [ ] Vérifier les autres vues inutilisées dans resources/views/

### 2. Optimiser les routes web.php

- [ ] Simplifier la route racine /
- [ ] S'assurer que la redirection vers login fonctionne

### 3. Tester l'application

- [ ] Vider tous les caches
- [ ] Vérifier que la route login fonctionne
- [ ] Vérifier les redirections par rôle

### 4. Vérifications finales

- [ ] SuperAdmin → dashboard SuperAdmin
- [ ] Employé (Direction/Supervision) → dashboard Entreprise
- [ ] Employé (Agent) → dashboard Agent
- [ ] Client → dashboard Client
