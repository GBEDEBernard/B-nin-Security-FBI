# TODO - Renommage dashboard en Admin

## Étapes à compléter :

- [ ]   1. Renommer le dossier `resources/views/dashboard` en `resources/views/Admin`
- [ ]   2. Modifier `routes/web.php` - changer les routes de dashboard/_ à admin/_
- [ ]   3. Modifier `app/Models/User.php` - changer les noms de routes
- [ ]   4. Modifier `app/Http/Controllers/AuthController.php`
- [ ]   5. Modifier `app/Http/Middleware/RoleBasedRedirect.php`
- [ ]   6. Modifier `resources/views/layouts/sidebar.blade.php`
- [ ]   7. Modifier `resources/views/layouts/header.blade.php`

## Détails des changements :

### routes/web.php :

- `/dashboard` → `/admin`
- `dashboard/superadmin` → `admin/superadmin`
- `dashboard/entreprise` → `admin/entreprise`
- `dashboard/agent` → `admin/agent`
- `dashboard/client` → `admin/client`
- `name('dashboard.*')` → `name('admin.*')`
- `view('dashboard.*')` → `view('admin.*')`

### app/Models/User.php :

- `dashboard.superadmin.index` → `admin.superadmin.index`
- `dashboard.entreprise.index` → `admin.entreprise.index`
- `dashboard.agent.index` → `admin.agent.index`
- `dashboard.client.index` → `admin.client.index`
- `dashboard` → `admin`

### app/Http/Controllers/AuthController.php :

- `route('dashboard')` → `route('admin')`
- `redirect('/dashboard')` → `redirect('/admin')`

### app/Http/Middleware/RoleBasedRedirect.php :

- `dashboard/' . $type` → `admin/' . $type`

### resources/views/layouts/sidebar.blade.php :

- Toutes les routes `dashboard.*` → `admin.*`
- `request()->routeIs('dashboard*')` → `request()->routeIs('admin*')`

### resources/views/layouts/header.blade.php :

- `route('dashboard')` → `route('admin')`
