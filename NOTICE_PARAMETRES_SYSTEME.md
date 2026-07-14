# Notice de Réalisation — Paramètres Système

## Bénin Security FBI · Module d'administration Super Admin

---

## 1. Vue d'ensemble

Le module **Paramètres Système** est le panneau de configuration global de la plateforme Bénin Security FBI. Accessible exclusivement au **Super Admin** (et partiellement aux directeurs généraux via permissions Spatie), il centralise la configuration technique et fonctionnelle de l'application.

### 1.1 Emplacement dans l'interface

| Élément | Fichier | Détail |
|---------|---------|--------|
| Sidebar | `resources/views/layouts/sidebar.blade.php:376-382` | Lien direct "Paramètres Système" avec icône `bi-gear-fill` |
| Dashboard | `resources/views/admin/superadmin.blade.php:525-528` | Bouton d'action rapide "Paramètres" |
| Header | `resources/views/layouts/header.blade.php:136,156` | Menu déroulant utilisateur > "Paramètres" |
| Breadcrumb | `resources/views/admin/superadmin/parametres/index.blade.php:14-16` | Dashboard > Paramètres |

### 1.2 Routes

Définies dans `routes/web.php:163-174` — préfixe : `/admin/superadmin/parametres`, nom : `admin.superadmin.parametres.*`.

| Méthode | URI | Action | Nom |
|---------|-----|--------|-----|
| GET | `/` | `index()` | `parametres.index` |
| PUT | `/general` | `general()` | `parametres.general` |
| PUT | `/email` | `email()` | `parametres.email` |
| PUT | `/security` | `security()` | `parametres.security` |
| PUT | `/api` | `api()` | `parametres.api` |
| PUT | `/mobile` | `mobile()` | `parametres.mobile` |
| POST | `/test-email` | `testEmail()` | `parametres.test-email` |
| POST | `/clear-cache` | `clearCache()` | `parametres.clear-cache` |
| GET | `/logs` | `logs()` | `parametres.logs` |
| POST | `/optimize` | `optimize()` | `parametres.optimize` |

### 1.3 Contrôleur

**Fichier** : `app/Http/Controllers/SuperAdmin/ParametreController.php` (189 lignes)

**Middleware** : `auth`, `superadmin` — toutes les routes sont protégées.

**Permissions Spatie associées** (définies dans `database/seeders/RolesAndPermissionsSeeder.php`) :

| Permission | super_admin | general_director |
|-----------|:-----------:|:----------------:|
| `manage_tenant_settings` | ✅ | ❌ |
| `view_settings` | ✅ | ✅ |
| `edit_settings` | ✅ | ✅ |
| `view_audit_logs` | ✅ | ✅ |

---

## 2. Architecture actuelle — État des lieux

### 2.1 Ce qui fonctionne

- **Routage complet** : les 10 endpoints sont déclarés et répondent
- **Squelette du contrôleur** : toutes les méthodes sont implémentées avec validation
- **Navigation** : sidebar, header, dashboard pointent vers la page
- **Structure de la vue** : la page `index.blade.php` hérite de `layouts.app` et affiche le breadcrumb

### 2.2 Ce qui est en placeholder (non fonctionnel)

1. **Vue principale** (`index.blade.php:26`) :
   ```php
   <p class="text-muted">Paramètres globaux du système en cours de développement.</p>
   ```
   Affiche un simple message — aucun formulaire, aucun onglet, aucune interface.

2. **Persistance des données** — Toutes les méthodes de mise à jour (`general()`, `email()`, `security()`, `api()`, `mobile()`) valident les entrées mais **ne sauvegardent rien** :
   ```php
   public function general(Request $request)
   {
       $validated = $request->validate([...]);
       // ← Aucune persistance
       return redirect()->route(...)->with('success', 'Paramètres généraux mis à jour.');
   }
   ```

3. **Cache / Optimisation** — Les appels Artisan sont commentés :
   ```php
   // Artisan::call('cache:clear');
   // Artisan::call('config:clear');
   ```

4. **Test email** — Retourne un message de succès sans réel envoi.

5. **Logs** — Lecture rudimentaire du fichier `storage/logs/laravel.log` (100 dernières lignes), vue `logs.blade.php` inexistante.

### 2.3 Table de stockage

Il n'existe **aucune table dédiée** aux paramètres globaux. Actuellement :
- Les paramètres système sont lus depuis `config/` (fichiers statiques Laravel)
- Les paramètres par entreprise sont stockés dans la colonne JSON `parametres` de la table `entreprises` (migration `0001_01_01_000002`, ligne 61)

### 2.4 Modèle Entreprise — Méthodes liées aux paramètres

```php
// app/Models/Entreprise.php

public function getParametre(string $cle, mixed $defaut = null): mixed
{
    return data_get($this->parametres, $cle, $defaut);
}

public function getRayonGpsDefaut(): int
{
    return $this->getParametre('rayon_gps_defaut', 300);
}

public function getFuseauHoraire(): string
{
    return $this->getParametre('fuseau_horaire', 'Africa/Porto-Novo');
}
```

---

## 3. Périmètre fonctionnel — Ce qui doit être réalisé

### 3.1 Page principale des paramètres

Une interface à onglets (tabs) avec 5 sections configurables + 2 actions système + 1 section monitoring.

#### 3.1.1 Général

| Champ | Type | Validation | Default |
|-------|------|-----------|---------|
| Nom de l'application | text | required, string, max:255 | `config('app.name')` |
| Environnement | select(production, local, staging) | required, in:... | `config('app.env')` |
| Debug mode | toggle/boolean | boolean | `config('app.debug')` |
| URL de l'application | url | required, url | `config('app.url')` |
| Fuseau horaire | select(liste PHP) | required, timezone | `config('app.timezone')` |
| Langue | select(fr, en) | required, size:2 | `config('app.locale')` |

**Route** : `PUT /admin/superadmin/parametres/general`

#### 3.1.2 Email

| Champ | Type | Validation |
|-------|------|-----------|
| Mail Driver | select(smtp, mailgun, postmark, ses, log, array) | required, string |
| Hôte SMTP | text | required, string |
| Port | number | required, integer |
| Chiffrement | select(tls, ssl, null) | nullable |
| Nom d'utilisateur | text | nullable, string |
| Mot de passe | password | nullable, string |
| Adresse d'envoi | email | required, email |
| Nom d'envoi | text | required, string |

**Route** : `PUT /admin/superadmin/parametres/email`

#### 3.1.3 Sécurité

| Champ | Type | Validation | Default |
|-------|------|-----------|---------|
| Longueur min. mot de passe | number | required, integer, min:6 | 8 |
| Durée de session (minutes) | number | required, integer, min:15 | 120 |
| Tentatives de connexion max | number | required, integer, min:3 | 5 |
| Mode maintenance | toggle/boolean | boolean | false |

**Route** : `PUT /admin/superadmin/parametres/security`

#### 3.1.4 API

| Champ | Type | Validation | Default |
|-------|------|-----------|---------|
| Expiration du token API (heures) | number | required, integer | 24 |
| Limite de taux (requêtes/min) | number | required, integer | 60 |

**Route** : `PUT /admin/superadmin/parametres/api`

#### 3.1.5 Application Mobile

| Champ | Type | Validation | Default |
|-------|------|-----------|---------|
| Version minimum de l'app | text | required, string | 1.0.0 |
| Notifications activées | toggle/boolean | boolean | true |
| Géolocalisation activée | toggle/boolean | boolean | true |

**Route** : `PUT /admin/superadmin/parametres/mobile`

#### 3.1.6 Actions Système

- **Tester l'email** : `POST /admin/superadmin/parametres/test-email` → champ email + bouton "Envoyer un test"
- **Vider le cache** : `POST /admin/superadmin/parametres/clear-cache`
- **Optimiser l'application** : `POST /admin/superadmin/parametres/optimize`

#### 3.1.7 Monitoring

- **Logs applicatifs** : `GET /admin/superadmin/parametres/logs` → affichage paginé des logs

---

## 4. Stratégie de persistance recommandée

### 4.1 Solution : Table `system_settings`

Créer une migration et modèle dédiés plutôt que de modifier les fichiers `config/*.php`.

**Migration** :
```php
Schema::create('system_settings', function (Blueprint $table) {
    $table->id();
    $table->string('key')->unique()->index();
    $table->text('value')->nullable();
    $table->string('type')->default('string'); // string, boolean, integer, json
    $table->string('group')->default('general'); // general, email, security, api, mobile
    $table->boolean('is_encrypted')->default(false);
    $table->timestamps();
});
```

**Modèle** `App\Models\SystemSetting` :
```php
class SystemSetting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'group', 'is_encrypted'];

    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();
        if (!$setting) return $default;
        return match($setting->type) {
            'boolean' => (bool) $setting->value,
            'integer' => (int) $setting->value,
            'json'    => json_decode($setting->value, true),
            default   => $setting->value,
        };
    }

    public static function set(string $key, mixed $value, string $group = 'general'): void
    {
        $type = match(true) {
            is_bool($value)   => 'boolean',
            is_int($value)    => 'integer',
            is_array($value)  => 'json',
            default            => 'string',
        };
        static::updateOrCreate(
            ['key' => $key],
            ['value' => (string) $value, 'type' => $type, 'group' => $group]
        );
    }
}
```

### 4.2 Helper global

Créer un helper `system_settings()` accessible partout :

```php
// app/helpers.php ou via AppServiceProvider
if (!function_exists('system_setting')) {
    function system_setting(string $key, mixed $default = null): mixed
    {
        return App\Models\SystemSetting::get($key, $default);
    }
}
```

### 4.3 Service Provider (cache)

Dans `AppServiceProvider::boot()`, charger les settings en cache :

```php
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Cache;

public function boot(): void
{
    $settings = Cache::remember('system_settings', 3600, function () {
        return SystemSetting::pluck('value', 'key')->toArray();
    });
    config()->set('system_settings', $settings);
}
```

---

## 5. Plan d'implémentation

### Étape 1 : Base de données
- Créer la migration `create_system_settings_table.php`
- Créer le modèle `SystemSetting`
- Créer le helper `system_setting()`
- Lancer `php artisan migrate`

### Étape 2 : Contrôleur — Persistance
- Modifier `ParametreController::general()` pour appeler `SystemSetting::set()`
- Faire de même pour `email()`, `security()`, `api()`, `mobile()`
- Décommenter les appels Artisan dans `clearCache()` et `optimize()`
- Implémenter l'envoi réel de l'email de test via `Mail::raw()`
- Améliorer la lecture des logs pour gérer la rotation et la pagination

### Étape 3 : Vue — Interface complète
- Remplacer le placeholder par une interface à onglets (AdminLTE `card-header` avec `nav-tabs`)
- Pour chaque section, créer un formulaire avec `@csrf` et `@method('PUT')`
- Pré-remplir les champs avec `config()` et `SystemSetting::get()`
- Ajouter les boutons d'action système avec confirmation JavaScript
- Créer la vue `logs.blade.php`

### Étape 4 : Cache & Optimisation
- Implémenter `clearCache()` :
  ```php
  Artisan::call('cache:clear');
  Artisan::call('config:clear');
  Artisan::call('route:clear');
  Artisan::call('view:clear');
  ```
- Implémenter `optimize()` :
  ```php
  Artisan::call('config:cache');
  Artisan::call('route:cache');
  Artisan::call('view:cache');
  ```

### Étape 5 : Tests
- Tester chaque endpoint PUT avec des données valides et invalides
- Vérifier que les valeurs sont bien persistées en base
- Vérifier que `system_setting()` retourne les bonnes valeurs
- Tester les actions `clear-cache`, `optimize`, `test-email`
- Tester la lecture des logs

---

## 6. Schéma de navigation

```
Paramètres Système
├── 📋 Général
│   ├── Nom app, Environnement, Debug, URL
│   ├── Fuseau horaire, Langue
│   └── [Sauvegarder]
├── 📧 Email
│   ├── Driver, Hôte, Port, Chiffrement
│   ├── Identifiants SMTP
│   ├── Adresse/Nom d'envoi
│   ├── [Sauvegarder]
│   └── [Tester la configuration] → modal avec champ email
├── 🔒 Sécurité
│   ├── Longueur mot de passe, Session, Tentatives
│   ├── Mode maintenance
│   └── [Sauvegarder]
├── 🔌 API
│   ├── Expiration token, Rate limit
│   └── [Sauvegarder]
├── 📱 Application Mobile
│   ├── Version minimum, Notifications, Géolocalisation
│   └── [Sauvegarder]
├── ⚡ Actions Système
│   ├── [Vider le cache]
│   └── [Optimiser l'application]
└── 📄 Logs applicatifs
    └── Affichage paginé du fichier de log
```

---

## 7. Fichiers à modifier / créer

### Fichiers existants à modifier

| Fichier | Modification |
|---------|-------------|
| `app/Http/Controllers/SuperAdmin/ParametreController.php` | Ajouter la logique de persistance, décommenter les appels Artisan |
| `resources/views/admin/superadmin/parametres/index.blade.php` | Remplacer le placeholder par l'interface complète |

### Nouveaux fichiers à créer

| Fichier | Description |
|---------|-------------|
| `database/migrations/xxxx_xx_xx_xxxxxx_create_system_settings_table.php` | Table des paramètres globaux |
| `app/Models/SystemSetting.php` | Modèle Eloquent avec helpers `get()` / `set()` |
| `app/helpers.php` (ou dans `AppServiceProvider`) | Fonction globale `system_setting()` |
| `resources/views/admin/superadmin/parametres/logs.blade.php` | Vue de consultation des logs |
| `tests/Feature/ParametreControllerTest.php` | Tests fonctionnels |

---

## 8. Diagramme de flux de données

```
┌─────────────┐     PUT /general      ┌──────────────────┐
│  Super Admin │ ──────────────────▶  │ ParametreController│
│  (navigateur)│                      │ ::general()       │
└─────────────┘                      └────────┬─────────┘
                                              │
                                              ▼
                                    ┌──────────────────┐
                                    │ SystemSetting::set│
                                    │ ('app.name', ...) │
                                    └────────┬─────────┘
                                              │
                                              ▼
                                    ┌──────────────────┐
                                    │  system_settings  │
                                    │  (table SQL)      │
                                    └──────────────────┘
                                              │
                         ┌────────────────────┤
                         │                    │
                         ▼                    ▼
                  ┌──────────┐        ┌──────────────┐
                  │  Cache   │        │  Helper       │
                  │ (redis)  │        │ system_setting│
                  └──────────┘        └──────────────┘
```

---

## 9. Règles de validation consolidées

```php
'general' => [
    'nom_application'       => 'required|string|max:255',
    'env'                   => 'required|in:production,local,staging',
    'debug'                 => 'boolean',
    'url'                   => 'required|url',
    'timezone'              => 'required|timezone',
    'locale'                => 'required|size:2',
],
'email' => [
    'mail_driver'           => 'required|in:smtp,mailgun,postmark,ses,log,array',
    'mail_host'             => 'required|string',
    'mail_port'             => 'required|integer',
    'mail_encryption'       => 'nullable|in:tls,ssl,null',
    'mail_username'         => 'nullable|string',
    'mail_password'         => 'nullable|string',
    'mail_from_address'     => 'required|email',
    'mail_from_name'        => 'required|string',
],
'security' => [
    'password_min_length'   => 'required|integer|min:6',
    'session_lifetime'      => 'required|integer|min:15',
    'max_login_attempts'    => 'required|integer|min:3',
    'maintenance_mode'      => 'boolean',
],
'api' => [
    'api_token_expiration'  => 'required|integer|min:1',
    'api_rate_limit'        => 'required|integer|min:10',
],
'mobile' => [
    'app_version_minimum'   => 'required|string',
    'notification_enabled'  => 'boolean',
    'geolocation_enabled'   => 'boolean',
],
'test-email' => [
    'email'                 => 'required|email',
],
```

---

## 10. Tests recommandés

```php
// tests/Feature/ParametreControllerTest.php
class ParametreControllerTest extends TestCase
{
    // Authentification
    public function test_guest_cannot_access_settings()
    public function test_non_superadmin_cannot_access_settings()

    // Lecture
    public function test_index_returns_view_with_settings()
    public function test_logs_returns_view_with_log_lines()

    // Écriture
    public function test_can_update_general_settings()
    public function test_can_update_email_settings()
    public function test_can_update_security_settings()
    public function test_can_update_api_settings()
    public function test_can_update_mobile_settings()

    // Validations
    public function test_general_settings_validation_fails_with_invalid_data()
    // ... chaque section

    // Actions
    public function test_clear_cache_runs_artisan_commands()
    public function test_optimize_runs_artisan_commands()
    public function test_test_email_sends_test_email()

    // Helper
    public function test_system_setting_helper_returns_correct_value()
    public function test_system_setting_returns_default_when_not_found()
}
```

---

## 11. Checklist de validation

- [ ] La table `system_settings` est créée et migrée
- [ ] `SystemSetting::get()` et `SystemSetting::set()` fonctionnent
- [ ] Le helper global `system_setting()` est accessible partout
- [ ] Chaque onglet affiche les valeurs actuelles en lecture
- [ ] Chaque formulaire sauvegarde correctement en base
- [ ] Les messages flash `success`/`error` s'affichent après chaque action
- [ ] `clearCache()` exécute bien les 4 commandes Artisan
- [ ] `optimize()` exécute bien les 3 commandes Artisan
- [ ] Le test email envoie un vrai email via la config courante
- [ ] Les logs s'affichent avec pagination et gestion de rotation
- [ ] Les permissions Spatie sont respectées (`view_settings`, `edit_settings`, etc.)
- [ ] Les données sensibles (mot de passe SMTP, token API) sont chiffrées en base
- [ ] Le cache des settings est invalidé après chaque mise à jour
