# Fix: Suppression des requêtes `/session/extend` en excès

## Problème

Deux mécanismes indépendants de prolongation de session coexistent et génèrent
des centaines de requêtes POST `/session/extend` :
1. `header.blade.php` — heartbeat 30s + appel sur chaque activité utilisateur
2. `app.blade.php` — code mort (l'élément HTML `#session-countdown` n'existe pas)

## Modifications à faire

### 1. `resources/views/layouts/app.blade.php`

**Supprimer** le bloc entier `{{-- Session Timeout Handler --}}` (lignes ~456-571).

Rechercher depuis :
```
  {{-- Session Timeout Handler --}}
  <script>
    document.addEventListener('DOMContentLoaded', function() {
```

Jusqu'à la ligne avec `</script>` qui ferme ce bloc (juste avant
`{{-- Modal connexion entreprise --}}`).

### 2. `resources/views/layouts/header.blade.php`

Plusieurs modifications :

#### a) Ligne 752 — Supprimer `const HEARTBEAT_INTERVAL = 30000;`

#### b) Ligne 755 — Supprimer `let heartbeatInterval;`

#### c) Lignes 759-762 — Remplacer `startSessionTracking()` :

**Avant :**
```javascript
function startSessionTracking() {
  if (heartbeatInterval) clearInterval(heartbeatInterval);
  heartbeatInterval = setInterval(sendHeartbeat, HEARTBEAT_INTERVAL);
}
```

**Après :**
```javascript
function startSessionTracking() {
  resetInactivityTimer();
}
```

#### d) Lignes 764-769 — Supprimer `stopSessionTracking()` :

**Avant :**
```javascript
function stopSessionTracking() {
  if (heartbeatInterval) {
    clearInterval(heartbeatInterval);
    heartbeatInterval = null;
  }
}
```

**Après :**
→ supprimer entièrement cette fonction (elle n'est appelée nulle part)

#### e) Lignes 826-830 — Modifier `onUserActivity()` :

**Avant :**
```javascript
function onUserActivity() {
  if (sessionWillExpire) return;
  sendHeartbeat();
  resetInactivityTimer();
}
```

**Après :**
```javascript
function onUserActivity() {
  if (sessionWillExpire) return;
  resetInactivityTimer();
}
```

## Résumé

Ce qui est **conservé** :
- ✅ Modal de timeout (`sessionTimeoutModal`) qui apparaît après 2 min d'inactivité
- ✅ Compte à rebours de 60s dans le modal
- ✅ Auto-logout après 3 min sans activité
- ✅ Bouton "Rester connecté" (appelle `sendHeartbeat()` + réinitialise tout)
- ✅ `sendHeartbeat()` existe toujours pour le bouton "Rester connecté"
- ✅ `resetInactivityTimer()` remet le compteur à chaque clic/mouvement

Ce qui est **supprimé** :
- ❌ Heartbeat toutes les 30 secondes
- ❌ `sendHeartbeat()` appelé sur chaque mouvement de souris / clic / scroll
- ❌ Tout le bloc Session Timeout Handler de `app.blade.php` (code mort)
