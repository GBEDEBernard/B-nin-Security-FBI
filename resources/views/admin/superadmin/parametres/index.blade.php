@extends('layouts.app')

@section('title', 'Paramètres Système - Super Admin')

@push('styles')
<style>
    .settings-tabs .nav-link {
        color: #6c757d;
        border: none;
        padding: 0.85rem 1.25rem;
        font-weight: 500;
        transition: all 0.25s ease;
        border-radius: 8px 8px 0 0;
        position: relative;
    }
    .settings-tabs .nav-link:hover {
        color: #198754;
        background: rgba(25, 135, 84, 0.05);
    }
    .settings-tabs .nav-link.active {
        color: #198754;
        background: #fff;
        border-bottom: 3px solid #198754;
    }
    .settings-tabs .nav-link i {
        margin-right: 8px;
        font-size: 1.1rem;
    }
    .settings-card {
        border: none;
        border-radius: 0 0 12px 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    }
    .settings-card .card-body {
        padding: 2rem;
    }
    .form-section-title {
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #198754;
        margin-bottom: 1.25rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e9ecef;
    }
    .form-label {
        font-weight: 500;
        font-size: 0.875rem;
        color: #344767;
    }
    .form-control, .form-select {
        border-radius: 8px;
        border: 1.5px solid #e2e8f0;
        padding: 0.55rem 0.9rem;
        font-size: 0.875rem;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .form-control:focus, .form-select:focus {
        border-color: #198754;
        box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.12);
    }
    .form-switch .form-check-input {
        width: 2.8em;
        height: 1.4em;
        cursor: pointer;
    }
    .form-switch .form-check-input:checked {
        background-color: #198754;
        border-color: #198754;
    }
    .btn-save {
        padding: 0.55rem 1.8rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
    }
    .action-card {
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.25rem;
        transition: all 0.25s ease;
        background: #fff;
    }
    .action-card:hover {
        border-color: #198754;
        box-shadow: 0 4px 16px rgba(25, 135, 84, 0.1);
    }
    .action-card .action-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }
    .log-line {
        font-size: 0.8rem;
        font-family: 'SF Mono', 'Fira Code', 'Courier New', monospace;
        padding: 0.35rem 0.75rem;
        border-bottom: 1px solid #f1f3f5;
        color: #495057;
        line-height: 1.5;
        word-break: break-all;
    }
    .log-line:nth-child(odd) {
        background: #f8f9fa;
    }
    .log-line:hover {
        background: #e8f5e9;
    }
    .log-error { color: #dc3545; }
    .log-warning { color: #f59e0b; }
    .log-info { color: #0ea5e9; }
    .stat-card {
        border: none;
        border-radius: 12px;
        padding: 1.25rem;
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    }
    .timezone-select {
        max-height: 250px;
        overflow-y: auto;
    }
    @media (max-width: 768px) {
        .settings-tabs .nav-link {
            padding: 0.6rem 0.8rem;
            font-size: 0.8rem;
        }
        .settings-tabs .nav-link i {
            margin-right: 4px;
        }
        .settings-card .card-body {
            padding: 1.25rem;
        }
    }
</style>
@endpush

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">
                    <i class="bi bi-gear-fill me-2"></i>Paramètres Système
                </h3>
                <p class="text-muted mb-0">Configuration globale de la plateforme Bénin Security FBI</p>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Paramètres</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        @php $currentTab = request('tab', 'general'); @endphp

        <div class="card settings-card">
            <div class="card-header bg-white p-0 border-bottom-0">
                <ul class="nav nav-tabs settings-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link {{ $currentTab === 'general' ? 'active' : '' }}"
                           href="{{ route('admin.superadmin.parametres.index', ['tab' => 'general']) }}"
                           role="tab">
                            <i class="bi bi-sliders2"></i><span class="d-none d-sm-inline">Général</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $currentTab === 'email' ? 'active' : '' }}"
                           href="{{ route('admin.superadmin.parametres.index', ['tab' => 'email']) }}"
                           role="tab">
                            <i class="bi bi-envelope-fill"></i><span class="d-none d-sm-inline">Email</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $currentTab === 'security' ? 'active' : '' }}"
                           href="{{ route('admin.superadmin.parametres.index', ['tab' => 'security']) }}"
                           role="tab">
                            <i class="bi bi-shield-lock-fill"></i><span class="d-none d-sm-inline">Sécurité</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $currentTab === 'api' ? 'active' : '' }}"
                           href="{{ route('admin.superadmin.parametres.index', ['tab' => 'api']) }}"
                           role="tab">
                            <i class="bi bi-plug-fill"></i><span class="d-none d-sm-inline">API</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $currentTab === 'mobile' ? 'active' : '' }}"
                           href="{{ route('admin.superadmin.parametres.index', ['tab' => 'mobile']) }}"
                           role="tab">
                            <i class="bi bi-phone-fill"></i><span class="d-none d-sm-inline">Mobile</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $currentTab === 'system' ? 'active' : '' }}"
                           href="{{ route('admin.superadmin.parametres.index', ['tab' => 'system']) }}"
                           role="tab">
                            <i class="bi bi-cpu-fill"></i><span class="d-none d-sm-inline">Système</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $currentTab === 'logs' ? 'active' : '' }}"
                           href="{{ route('admin.superadmin.parametres.logs') }}"
                           role="tab">
                            <i class="bi bi-journal-text"></i><span class="d-none d-sm-inline">Logs</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body">
                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Erreurs de validation :</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                {{-- ============================================================ --}}
                {{-- TAB : GÉNÉRAL --}}
                {{-- ============================================================ --}}
                @if($currentTab === 'general')
                <form action="{{ route('admin.superadmin.parametres.general') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-section-title">
                        <i class="bi bi-globe2 me-2"></i>Informations générales de l'application
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">Nom de l'application</label>
                            <input type="text" name="app_name" class="form-control"
                                   value="{{ old('app_name', $settings['general']['app_name'] ?? config('app.name')) }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Environnement</label>
                            <select name="app_env" class="form-select" required>
                                @foreach(['production' => 'Production', 'local' => 'Local', 'staging' => 'Staging'] as $val => $label)
                                <option value="{{ $val }}" {{ (old('app_env', $settings['general']['app_env'] ?? config('app.env')) === $val) ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Mode debug</label>
                            <div class="form-check form-switch pt-2">
                                <input type="hidden" name="app_debug" value="0">
                                <input type="checkbox" name="app_debug" class="form-check-input" role="switch" value="1"
                                       {{ (old('app_debug', $settings['general']['app_debug'] ?? config('app.debug'))) ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4 mt-1">
                        <div class="col-md-4">
                            <label class="form-label">URL de l'application</label>
                            <input type="url" name="app_url" class="form-control"
                                   value="{{ old('app_url', $settings['general']['app_url'] ?? config('app.url')) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Fuseau horaire</label>
                            <select name="app_timezone" class="form-select timezone-select" required>
                                @php $currentTz = old('app_timezone', $settings['general']['app_timezone'] ?? config('app.timezone')); @endphp
                                @foreach(timezone_identifiers_list() as $tz)
                                <option value="{{ $tz }}" {{ $currentTz === $tz ? 'selected' : '' }}>{{ $tz }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Langue</label>
                            <select name="app_locale" class="form-select" required>
                                <option value="fr" {{ (old('app_locale', $settings['general']['app_locale'] ?? config('app.locale')) === 'fr') ? 'selected' : '' }}>Français</option>
                                <option value="en" {{ (old('app_locale', $settings['general']['app_locale'] ?? config('app.locale')) === 'en') ? 'selected' : '' }}>English</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-primary btn-save">
                            <i class="bi bi-check2 me-1"></i> Enregistrer les paramètres généraux
                        </button>
                    </div>
                </form>

                {{-- ============================================================ --}}
                {{-- TAB : EMAIL --}}
                {{-- ============================================================ --}}
                @elseif($currentTab === 'email')
                <form action="{{ route('admin.superadmin.parametres.email') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-section-title">
                        <i class="bi bi-envelope-paper me-2"></i>Configuration du serveur mail
                    </div>

                    <div class="row g-4">
                        <div class="col-md-4">
                            <label class="form-label">Driver mail</label>
                            <select name="mail_driver" class="form-select" required>
                                @foreach(['smtp' => 'SMTP', 'mailgun' => 'Mailgun', 'postmark' => 'Postmark', 'ses' => 'Amazon SES', 'log' => 'Log', 'array' => 'Array'] as $val => $label)
                                <option value="{{ $val }}" {{ (old('mail_driver', $settings['email']['mail_driver'] ?? config('mail.default')) === $val) ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Hôte SMTP</label>
                            <input type="text" name="mail_host" class="form-control"
                                   value="{{ old('mail_host', $settings['email']['mail_host'] ?? config('mail.mailers.smtp.host')) }}" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Port</label>
                            <input type="number" name="mail_port" class="form-control"
                                   value="{{ old('mail_port', $settings['email']['mail_port'] ?? config('mail.mailers.smtp.port')) }}" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Chiffrement</label>
                            <select name="mail_encryption" class="form-select">
                                <option value="tls" {{ (old('mail_encryption', $settings['email']['mail_encryption'] ?? 'tls') === 'tls') ? 'selected' : '' }}>TLS</option>
                                <option value="ssl" {{ (old('mail_encryption', $settings['email']['mail_encryption'] ?? 'tls') === 'ssl') ? 'selected' : '' }}>SSL</option>
                                <option value="null" {{ (old('mail_encryption', $settings['email']['mail_encryption'] ?? 'tls') === 'null') ? 'selected' : '' }}>Aucun</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-4 mt-1">
                        <div class="col-md-4">
                            <label class="form-label">Nom d'utilisateur</label>
                            <input type="text" name="mail_username" class="form-control"
                                   value="{{ old('mail_username', $settings['email']['mail_username'] ?? '') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Mot de passe</label>
                            <input type="password" name="mail_password" class="form-control"
                                   value="{{ old('mail_password', $settings['email']['mail_password'] ?? '') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Adresse d'envoi</label>
                            <input type="email" name="mail_from_address" class="form-control"
                                   value="{{ old('mail_from_address', $settings['email']['mail_from_address'] ?? config('mail.from.address')) }}" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Nom d'envoi</label>
                            <input type="text" name="mail_from_name" class="form-control"
                                   value="{{ old('mail_from_name', $settings['email']['mail_from_name'] ?? config('mail.from.name')) }}" required>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-save">
                            <i class="bi bi-check2 me-1"></i> Enregistrer les paramètres email
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-save" data-bs-toggle="modal" data-bs-target="#testEmailModal">
                            <i class="bi bi-send me-1"></i> Tester la configuration
                        </button>
                    </div>
                </form>

                {{-- ============================================================ --}}
                {{-- TAB : SÉCURITÉ --}}
                {{-- ============================================================ --}}
                @elseif($currentTab === 'security')
                <form action="{{ route('admin.superadmin.parametres.security') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-section-title">
                        <i class="bi bi-shield-check me-2"></i>Politiques de sécurité
                    </div>

                    <div class="row g-4">
                        <div class="col-md-4">
                            <label class="form-label">Longueur minimale du mot de passe</label>
                            <input type="number" name="password_min_length" class="form-control"
                                   value="{{ old('password_min_length', $settings['security']['password_min_length'] ?? 8) }}" min="6" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Durée de session (minutes)</label>
                            <input type="number" name="session_lifetime" class="form-control"
                                   value="{{ old('session_lifetime', $settings['security']['session_lifetime'] ?? config('session.lifetime')) }}" min="15" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tentatives de connexion max.</label>
                            <input type="number" name="max_login_attempts" class="form-control"
                                   value="{{ old('max_login_attempts', $settings['security']['max_login_attempts'] ?? 5) }}" min="3" required>
                        </div>
                    </div>

                    <div class="row g-4 mt-1">
                        <div class="col-md-4">
                            <label class="form-label">Mode maintenance</label>
                            <div class="form-check form-switch pt-2">
                                <input type="hidden" name="maintenance_mode" value="0">
                                <input type="checkbox" name="maintenance_mode" class="form-check-input" role="switch" value="1"
                                       {{ (old('maintenance_mode', $settings['security']['maintenance_mode'] ?? false)) ? 'checked' : '' }}>
                            </div>
                            <small class="text-muted d-block">Activer le mode maintenance pour bloquer l'accès à la plateforme</small>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-primary btn-save">
                            <i class="bi bi-check2 me-1"></i> Enregistrer les paramètres de sécurité
                        </button>
                    </div>
                </form>

                {{-- ============================================================ --}}
                {{-- TAB : API --}}
                {{-- ============================================================ --}}
                @elseif($currentTab === 'api')
                <form action="{{ route('admin.superadmin.parametres.api') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-section-title">
                        <i class="bi bi-diagram-3 me-2"></i>Configuration de l'API REST
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">Expiration du token API (heures)</label>
                            <input type="number" name="api_token_expiration" class="form-control"
                                   value="{{ old('api_token_expiration', $settings['api']['api_token_expiration'] ?? 24) }}" min="1" required>
                            <small class="text-muted">Durée de validité des tokens d'authentification API</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Limite de taux (requêtes/minute)</label>
                            <input type="number" name="api_rate_limit" class="form-control"
                                   value="{{ old('api_rate_limit', $settings['api']['api_rate_limit'] ?? 60) }}" min="10" required>
                            <small class="text-muted">Nombre maximum de requêtes API par minute et par client</small>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-primary btn-save">
                            <i class="bi bi-check2 me-1"></i> Enregistrer les paramètres API
                        </button>
                    </div>
                </form>

                {{-- ============================================================ --}}
                {{-- TAB : MOBILE --}}
                {{-- ============================================================ --}}
                @elseif($currentTab === 'mobile')
                <form action="{{ route('admin.superadmin.parametres.mobile') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-section-title">
                        <i class="bi bi-phone me-2"></i>Configuration de l'application mobile
                    </div>

                    <div class="row g-4">
                        <div class="col-md-4">
                            <label class="form-label">Version minimale requise</label>
                            <input type="text" name="app_version_minimum" class="form-control"
                                   value="{{ old('app_version_minimum', $settings['mobile']['app_version_minimum'] ?? '1.0.0') }}" required>
                            <small class="text-muted">Les utilisateurs avec une version inférieure seront invités à mettre à jour</small>
                        </div>
                    </div>

                    <div class="row g-4 mt-1">
                        <div class="col-md-4">
                            <label class="form-label">Notifications push</label>
                            <div class="form-check form-switch pt-2">
                                <input type="hidden" name="notification_enabled" value="0">
                                <input type="checkbox" name="notification_enabled" class="form-check-input" role="switch" value="1"
                                       {{ (old('notification_enabled', $settings['mobile']['notification_enabled'] ?? true)) ? 'checked' : '' }}>
                            </div>
                            <small class="text-muted">Activer l'envoi de notifications push vers les appareils mobiles</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Géolocalisation</label>
                            <div class="form-check form-switch pt-2">
                                <input type="hidden" name="geolocation_enabled" value="0">
                                <input type="checkbox" name="geolocation_enabled" class="form-check-input" role="switch" value="1"
                                       {{ (old('geolocation_enabled', $settings['mobile']['geolocation_enabled'] ?? true)) ? 'checked' : '' }}>
                            </div>
                            <small class="text-muted">Activer le suivi GPS pour les agents sur le terrain</small>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-primary btn-save">
                            <i class="bi bi-check2 me-1"></i> Enregistrer les paramètres mobile
                        </button>
                    </div>
                </form>

                {{-- ============================================================ --}}
                {{-- TAB : SYSTÈME --}}
                {{-- ============================================================ --}}
                @elseif($currentTab === 'system')
                <div class="form-section-title">
                    <i class="bi bi-cpu me-2"></i>Actions système & Maintenance
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="action-card d-flex align-items-start gap-3">
                            <div class="action-icon bg-warning bg-opacity-10 text-warning">
                                <i class="bi bi-arrow-clockwise"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Vider le cache</h6>
                                <p class="text-muted mb-2" style="font-size:0.85rem;">
                                    Efface le cache de l'application, la configuration, les routes et les vues compilées.
                                </p>
                                <form action="{{ route('admin.superadmin.parametres.clear-cache') }}" method="POST"
                                      onsubmit="return confirm('Vider le cache ? Cette action est irréversible.');">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-sm">
                                        <i class="bi bi-trash me-1"></i> Vider le cache
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="action-card d-flex align-items-start gap-3">
                            <div class="action-icon bg-success bg-opacity-10 text-success">
                                <i class="bi bi-lightning-charge"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Optimiser l'application</h6>
                                <p class="text-muted mb-2" style="font-size:0.85rem;">
                                    Met en cache la configuration, les routes et les vues pour améliorer les performances.
                                </p>
                                <form action="{{ route('admin.superadmin.parametres.optimize') }}" method="POST"
                                      onsubmit="return confirm('Lancer l\'optimisation ?');">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="bi bi-lightning me-1"></i> Optimiser
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="action-card d-flex align-items-start gap-3">
                            <div class="action-icon bg-info bg-opacity-10 text-info">
                                <i class="bi bi-info-circle"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Informations système</h6>
                                <p class="text-muted mb-0" style="font-size:0.85rem;">
                                    PHP {{ phpversion() }} · Laravel {{ app()->version() }} · 
                                    @if(config('database.default') === 'sqlite')
                                    SQLite
                                    @else
                                    {{ ucfirst(config('database.default')) }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="action-card d-flex align-items-start gap-3">
                            <div class="action-icon bg-danger bg-opacity-10 text-danger">
                                <i class="bi bi-exclamation-triangle"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Mode maintenance</h6>
                                <p class="text-muted mb-2" style="font-size:0.85rem;">
                                    @php $maintenanceMode = $settings['security']['maintenance_mode'] ?? false; @endphp
                                    @if($maintenanceMode)
                                    <span class="badge bg-danger">Activé</span> — La plateforme est actuellement en maintenance.
                                    @else
                                    <span class="badge bg-success">Désactivé</span> — La plateforme est accessible.
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section-title mt-4">
                    <i class="bi bi-database me-2"></i>Paramètres actuels (lecture seule)
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm" style="font-size:0.85rem;">
                        <thead class="table-light">
                            <tr>
                                <th>Clé</th>
                                <th>Valeur</th>
                                <th>Groupe</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($settings as $group => $items)
                                @foreach($items as $key => $value)
                                <tr>
                                    <td><code>{{ $key }}</code></td>
                                    <td><code>{{ $value }}</code></td>
                                    <td><span class="badge bg-secondary">{{ $group }}</span></td>
                                </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- ============================================================ --}}
                {{-- TAB : LOGS (redirigé vers une page dédiée) --}}
                {{-- ============================================================ --}}
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Modal de test email --}}
<div class="modal fade" id="testEmailModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.superadmin.parametres.test-email') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-send me-2"></i>Tester la configuration email</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Adresse email de test</label>
                    <input type="email" name="email" class="form-control" placeholder="exemple@domaine.com" required>
                    <small class="text-muted">Un email de test sera envoyé à cette adresse avec la configuration actuelle.</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send me-1"></i> Envoyer le test
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
