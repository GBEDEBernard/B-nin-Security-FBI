@extends('layouts.app')

@use('App\Models\Parametre')

@section('title', 'Paramètres Système - Super Admin')

@push('styles')
<style>
    :root {
        --primary-color: #198754;
        --primary-light: #20c997;
        --bg-dark: #1a1a1a;
        --bg-card: #2d2d2d;
        --text-muted: #a0a0a0;
        --border-color: #404040;
    }

    .settings-container {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
        min-height: 100vh;
        padding: 1.5rem;
    }

    .settings-header {
        background: linear-gradient(135deg, rgba(25, 135, 84, 0.2) 0%, rgba(32, 201, 151, 0.1) 100%);
        border: 1px solid rgba(25, 135, 84, 0.3);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .settings-header h1 {
        color: #fff;
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .settings-header p {
        color: var(--text-muted);
        margin-bottom: 0;
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 1.25rem;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        border-color: var(--primary-color);
        box-shadow: 0 4px 20px rgba(25, 135, 84, 0.2);
    }

    .stat-card .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-bottom: 0.75rem;
    }

    .stat-card .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #fff;
    }

    .stat-card .stat-label {
        font-size: 0.875rem;
        color: var(--text-muted);
    }

    /* Tabs Navigation */
    .settings-tabs {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        overflow: hidden;
    }

    .tabs-header {
        display: flex;
        flex-wrap: wrap;
        background: rgba(0, 0, 0, 0.2);
        border-bottom: 1px solid var(--border-color);
        padding: 0.5rem;
        gap: 0.25rem;
    }

    .tab-btn {
        padding: 0.75rem 1.25rem;
        border: none;
        background: transparent;
        color: var(--text-muted);
        font-weight: 500;
        border-radius: 8px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .tab-btn:hover {
        background: rgba(25, 135, 84, 0.1);
        color: #fff;
    }

    .tab-btn.active {
        background: linear-gradient(135deg, #198754 0%, #20c997 100%);
        color: #fff;
        box-shadow: 0 4px 15px rgba(25, 135, 84, 0.4);
    }

    .tab-btn i {
        font-size: 1rem;
    }

    /* Tab Content */
    .tab-content {
        padding: 1.5rem;
    }

    .tab-pane {
        display: none;
        animation: fadeIn 0.3s ease;
    }

    .tab-pane.active {
        display: block;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Settings Section */
    .settings-section {
        background: rgba(0, 0, 0, 0.2);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .settings-section:last-child {
        margin-bottom: 0;
    }

    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--border-color);
    }

    .section-title {
        color: #fff;
        font-size: 1.1rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin: 0;
    }

    .section-title i {
        color: var(--primary-light);
    }

    /* Form Elements */
    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-group:last-child {
        margin-bottom: 0;
    }

    .form-label {
        display: block;
        color: #fff;
        font-weight: 500;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .form-label .required {
        color: #dc3545;
    }

    .form-label .hint {
        color: var(--text-muted);
        font-weight: 400;
        font-size: 0.8rem;
        margin-left: 0.25rem;
    }

    .form-control,
    .form-select {
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        color: #fff;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus,
    .form-select:focus {
        background: rgba(0, 0, 0, 0.5);
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.2);
        color: #fff;
    }

    .form-control::placeholder {
        color: var(--text-muted);
    }

    .form-select option {
        background: var(--bg-dark);
        color: #fff;
    }

    /* Toggle Switch */
    .form-check {
        padding-left: 2.5rem;
    }

    .form-check-input {
        width: 2rem;
        height: 1rem;
        margin-left: -2.5rem;
    }

    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .form-check-input:focus {
        box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.2);
    }

    /* Buttons */
    .btn-primary {
        background: linear-gradient(135deg, #198754 0%, #20c997 100%);
        border: none;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(25, 135, 84, 0.4);
    }

    .btn-outline-light {
        border: 1px solid var(--border-color);
        color: #fff;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .btn-outline-light:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: #fff;
        color: #fff;
    }

    .btn-sm {
        padding: 0.4rem 0.75rem;
        font-size: 0.85rem;
    }

    /* Alert Messages */
    .alert {
        border: none;
        border-radius: 8px;
        padding: 1rem 1.25rem;
    }

    .alert-success {
        background: rgba(25, 135, 84, 0.2);
        border: 1px solid rgba(25, 135, 84, 0.3);
        color: #20c997;
    }

    .alert-danger {
        background: rgba(220, 53, 69, 0.2);
        border: 1px solid rgba(220, 53, 69, 0.3);
        color: #f8d7da;
    }

    /* Action Buttons Row */
    .action-buttons {
        display: flex;
        gap: 0.75rem;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--border-color);
    }

    /* Settings Grid for Key-Value */
    .settings-grid {
        display: grid;
        gap: 1rem;
    }

    .setting-item {
        display: grid;
        grid-template-columns: 1fr 2fr auto;
        gap: 1rem;
        align-items: center;
        padding: 1rem;
        background: rgba(0, 0, 0, 0.2);
        border-radius: 8px;
        border: 1px solid transparent;
        transition: all 0.3s ease;
    }

    .setting-item:hover {
        border-color: var(--border-color);
    }

    .setting-info h6 {
        color: #fff;
        font-weight: 500;
        margin-bottom: 0.25rem;
    }

    .setting-info small {
        color: var(--text-muted);
        font-size: 0.8rem;
    }

    .setting-value {
        min-width: 200px;
    }

    .setting-actions {
        display: flex;
        gap: 0.5rem;
    }

    /* Quick Actions */
    .quick-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .quick-action-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1rem;
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        color: #fff;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .quick-action-btn:hover {
        background: rgba(25, 135, 84, 0.2);
        border-color: var(--primary-color);
        transform: translateY(-2px);
    }

    .quick-action-btn i {
        color: var(--primary-light);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .settings-container {
            padding: 1rem;
        }

        .tabs-header {
            overflow-x: auto;
            flex-wrap: nowrap;
        }

        .tab-btn {
            white-space: nowrap;
            padding: 0.5rem 1rem;
        }

        .setting-item {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }

        .setting-value {
            min-width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="settings-container">
    <!-- En-tête -->
    <div class="settings-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1><i class="bi bi-gear-fill me-2"></i>Paramètres Système</h1>
                <p>Gérez toutes les configurations de votre plateforme Benin Security</p>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="quick-actions">
                    <a href="{{ route('admin.superadmin.parametres.status') }}" class="quick-action-btn">
                        <i class="bi bi-speedometer2"></i> Status
                    </a>
                    <a href="{{ route('admin.superadmin.parametres.logs') }}" class="quick-action-btn">
                        <i class="bi bi-journal-text"></i> Logs
                    </a>
                    <a href="{{ route('admin.superadmin.parametres.backups') }}" class="quick-action-btn">
                        <i class="bi bi-cloud-arrow-up"></i> Backups
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(25, 135, 84, 0.2);">
                <i class="bi bi-sliders" style="color: #20c997;"></i>
            </div>
            <div class="stat-value">{{ $stats['total'] }}</div>
            <div class="stat-label">Total Paramètres</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(59, 130, 246, 0.2);">
                <i class="bi bi-folder" style="color: #3b82f6;"></i>
            </div>
            <div class="stat-value">{{ $stats['categories'] }}</div>
            <div class="stat-label">Catégories</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(245, 158, 11, 0.2);">
                <i class="bi bi-pencil" style="color: #f59e0b;"></i>
            </div>
            <div class="stat-value">{{ $stats['modifiables'] }}</div>
            <div class="stat-label">Modifiables</div>
        </div>
    </div>

    <!-- Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Tabs -->
    <div class="settings-tabs">
        <div class="tabs-header">
            <button class="tab-btn {{ $categorie === 'general' ? 'active' : '' }}" onclick="switchTab('general')">
                <i class="bi bi-house-gear"></i> Général
            </button>
            <button class="tab-btn {{ $categorie === 'email' ? 'active' : '' }}" onclick="switchTab('email')">
                <i class="bi bi-envelope"></i> Email
            </button>
            <button class="tab-btn {{ $categorie === 'security' ? 'active' : '' }}" onclick="switchTab('security')">
                <i class="bi bi-shield-lock"></i> Sécurité
            </button>
            <button class="tab-btn {{ $categorie === 'api' ? 'active' : '' }}" onclick="switchTab('api')">
                <i class="bi bi-code-slash"></i> API
            </button>
            <button class="tab-btn {{ $categorie === 'mobile' ? 'active' : '' }}" onclick="switchTab('mobile')">
                <i class="bi bi-phone"></i> Mobile
            </button>
            <button class="tab-btn {{ $categorie === 'paiement' ? 'active' : '' }}" onclick="switchTab('paiement')">
                <i class="bi bi-credit-card"></i> Paiement
            </button>
            <button class="tab-btn {{ $categorie === 'backup' ? 'active' : '' }}" onclick="switchTab('backup')">
                <i class="bi bi-cloud-arrow-up"></i> Sauvegarde
            </button>
            <button class="tab-btn {{ $categorie === 'notifications' ? 'active' : '' }}" onclick="switchTab('notifications')">
                <i class="bi bi-bell"></i> Notifications
            </button>
            <button class="tab-btn {{ $categorie === 'abonnement' ? 'active' : '' }}" onclick="switchTab('abonnement')">
                <i class="bi bi-person-badge"></i> Abonnement
            </button>
            <button class="tab-btn {{ $categorie === 'facturation' ? 'active' : '' }}" onclick="switchTab('facturation')">
                <i class="bi bi-receipt"></i> Facturation
            </button>
        </div>

        <div class="tab-content">
            <!-- Onglet Général -->
            <div class="tab-pane {{ $categorie === 'general' ? 'active' : '' }}" id="general">
                <form action="{{ route('admin.superadmin.parametres.updateCategorie', 'general') }}" method="POST">
                    @csrf
                    <div class="settings-section">
                        <div class="section-header">
                            <h5 class="section-title"><i class="bi bi-info-circle"></i> Informations de l'Application</h5>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Nom de l'application</label>
                                    <input type="text" class="form-control" name="app_nom" value="{{ Parametre::get('app.nom', 'Bénin Security') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Description</label>
                                    <input type="text" class="form-control" name="app_description" value="{{ Parametre::get('app.description', '') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="settings-section">
                        <div class="section-header">
                            <h5 class="section-title"><i class="bi bi-globe"></i> Localisation</h5>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Fuseau horaire (Timezone)</label>
                                    <select class="form-select" name="app_timezone">
                                        <option value="Africa/Porto-Novo" {{ Parametre::get('app.timezone') === 'Africa/Porto-Novo' ? 'selected' : '' }}>Africa/Porto-Novo (GMT+1)</option>
                                        <option value="Africa/Lagos" {{ Parametre::get('app.timezone') === 'Africa/Lagos' ? 'selected' : '' }}>Africa/Lagos (GMT+1)</option>
                                        <option value="Europe/Paris" {{ Parametre::get('app.timezone') === 'Europe/Paris' ? 'selected' : '' }}>Europe/Paris (GMT+1)</option>
                                        <option value="UTC" {{ Parametre::get('app.timezone') === 'UTC' ? 'selected' : '' }}>UTC</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Langue</label>
                                    <select class="form-select" name="app_locale">
                                        <option value="fr" {{ Parametre::get('app.locale') === 'fr' ? 'selected' : '' }}>Français</option>
                                        <option value="en" {{ Parametre::get('app.locale') === 'en' ? 'selected' : '' }}>English</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Devise</label>
                                    <select class="form-select" name="app_devise">
                                        <option value="XOF" {{ Parametre::get('app.devise') === 'XOF' ? 'selected' : '' }}>XOF (Franc CFA)</option>
                                        <option value="EUR" {{ Parametre::get('app.devise') === 'EUR' ? 'selected' : '' }}>EUR (Euro)</option>
                                        <option value="USD" {{ Parametre::get('app.devise') === 'USD' ? 'selected' : '' }}>USD (Dollar)</option>
                                </div>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Enregistrer
                        </button>
                        <button type="reset" class="btn btn-outline-light">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Réinitialiser
                        </button>
                    </div>
                </form>
            </div>

            <!-- Onglet Email -->
            <div class="tab-pane {{ $categorie === 'email' ? 'active' : '' }}" id="email">
                <form action="{{ route('admin.superadmin.parametres.updateCategorie', 'email') }}" method="POST">
                    @csrf
                    <div class="settings-section">
                        <div class="section-header">
                            <h5 class="section-title"><i class="bi bi-server"></i> Configuration SMTP</h5>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Driver</label>
                                    <select class="form-select" name="mail_driver">
                                        <option value="smtp" {{ Parametre::get('mail.driver') === 'smtp' ? 'selected' : '' }}>SMTP</option>
                                        <option value="sendmail" {{ Parametre::get('mail.driver') === 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                                        <option value="log" {{ Parametre::get('mail.driver') === 'log' ? 'selected' : '' }}>Log (Fichier)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Host SMTP</label>
                                    <input type="text" class="form-control" name="mail_host" value="{{ Parametre::get('mail.host', 'smtp.mailgun.org') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Port</label>
                                    <input type="number" class="form-control" name="mail_port" value="{{ Parametre::get('mail.port', 587) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Encryption</label>
                                    <select class="form-select" name="mail_encryption">
                                        <option value="tls" {{ Parametre::get('mail.encryption') === 'tls' ? 'selected' : '' }}>TLS</option>
                                        <option value="ssl" {{ Parametre::get('mail.encryption') === 'ssl' ? 'selected' : '' }}>SSL</option>
                                        <option value="" {{ Parametre::get('mail.encryption') === '' ? 'selected' : '' }}>Aucune</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Username</label>
                                    <input type="text" class="form-control" name="mail_username" value="{{ Parametre::get('mail.username', '') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Password</label>
                                    <input type="password" class="form-control" name="mail_password" placeholder="••••••••">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="settings-section">
                        <div class="section-header">
                            <h5 class="section-title"><i class="bi bi-send"></i> Expéditeur</h5>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Adresse Email</label>
                                    <input type="email" class="form-control" name="mail_from_address" value="{{ Parametre::get('mail.from.address', 'noreply@benin-security.com') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Nom de l'expéditeur</label>
                                    <input type="text" class="form-control" name="mail_from_name" value="{{ Parametre::get('mail.from.name', 'Bénin Security') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Enregistrer
                        </button>
                        <button type="button" class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#testEmailModal">
                            <i class="bi bi-send-check me-1"></i> Tester Email
                        </button>
                    </div>
                </form>
            </div>

            <!-- Onglet Sécurité -->
            <div class="tab-pane {{ $categorie === 'security' ? 'active' : '' }}" id="security">
                <form action="{{ route('admin.superadmin.parametres.updateCategorie', 'security') }}" method="POST">
                    @csrf
                    <div class="settings-section">
                        <div class="section-header">
                            <h5 class="section-title"><i class="bi bi-key"></i> Politique de Mot de Passe</h5>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Longueur minimale</label>
                                    <input type="number" class="form-control" name="security_password_min_length" value="{{ Parametre::get('security.password_min_length', 8) }}" min="6">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Durée de verrouillage (minutes)</label>
                                    <input type="number" class="form-control" name="security_lockout_duration" value="{{ Parametre::get('security.lockout_duration', 15) }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="security_password_require_uppercase" {{ Parametre::get('security.password_require_uppercase') ? 'checked' : '' }}>
                                    <label class="form-check-label">Majuscule requise</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="security_password_require_lowercase" {{ Parametre::get('security.password_require_lowercase') ? 'checked' : '' }}>
                                    <label class="form-check-label">Minuscule requise</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="security_password_require_numbers" {{ Parametre::get('security.password_require_numbers') ? 'checked' : '' }}>
                                    <label class="form-check-label">Chiffre requis</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="security_password_require_special" {{ Parametre::get('security.password_require_special') ? 'checked' : '' }}>
                                    <label class="form-check-label">Caractère spécial</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="settings-section">
                        <div class="section-header">
                            <h5 class="section-title"><i class="bi bi-clock-history"></i> Session</h5>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Durée de vie de la session (minutes)</label>
                                    <input type="number" class="form-control" name="security_session_lifetime" value="{{ Parametre::get('security.session_lifetime', 120) }}" min="15">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Tentatives de connexion max</label>
                                    <input type="number" class="form-control" name="security_max_login_attempts" value="{{ Parametre::get('security.max_login_attempts', 5) }}" min="3">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="settings-section">
                        <div class="section-header">
                            <h5 class="section-title"><i class="bi bi-tools"></i> Mode Maintenance</h5>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="security_maintenance_mode" {{ Parametre::get('security.maintenance_mode') ? 'checked' : '' }}>
                            <label class="form-check-label">Activer le mode maintenance</label>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Message de maintenance</label>
                            <textarea class="form-control" name="security_maintenance_message" rows="2">{{ Parametre::get('security.maintenance_message', 'Le site est en maintenance. Veuillez revenir plus tard.') }}</textarea>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>

            <!-- Onglet API -->
            <div class="tab-pane {{ $categorie === 'api' ? 'active' : '' }}" id="api">
                <form action="{{ route('admin.superadmin.parametres.updateCategorie', 'api') }}" method="POST">
                    @csrf
                    <div class="settings-section">
                        <div class="section-header">
                            <h5 class="section-title"><i class="bi bi-plug"></i> Configuration API</h5>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="api_enabled" {{ Parametre::get('api.enabled') ? 'checked' : '' }}>
                            <label class="form-check-label">Activer l'API</label>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Expiration du token (jours)</label>
                                    <input type="number" class="form-control" name="api_token_expiration" value="{{ Parametre::get('api.token_expiration', 365) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Limite de requêtes/minute</label>
                                    <input type="number" class="form-control" name="api_rate_limit" value="{{ Parametre::get('api.rate_limit', 60) }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>

            <!-- Onglet Mobile -->
            <div class="tab-pane {{ $categorie === 'mobile' ? 'active' : '' }}" id="mobile">
                <form action="{{ route('admin.superadmin.parametres.updateCategorie', 'mobile') }}" method="POST">
                    @csrf
                    <div class="settings-section">
                        <div class="section-header">
                            <h5 class="section-title"><i class="bi bi-phone"></i> Application Mobile</h5>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Version actuelle</label>
                                    <input type="text" class="form-control" name="mobile_app_version" value="{{ Parametre::get('mobile.app_version', '1.0.0') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Version minimale</label>
                                    <input type="text" class="form-control" name="mobile_minimum_version" value="{{ Parametre::get('mobile.minimum_version', '1.0.0') }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="mobile_force_update" {{ Parametre::get('mobile.force_update') ? 'checked' : '' }}>
                            <label class="form-check-label">Forcer la mise à jour</label>
                        </div>
                    </div>

                    <div class="settings-section">
                        <div class="section-header">
                            <h5 class="section-title"><i class="bi bi-gear"></i> Fonctionnalités</h5>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="mobile_notifications_enabled" {{ Parametre::get('mobile.notifications_enabled') ? 'checked' : '' }}>
                            <label class="form-check-label">Notifications push</label>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="mobile_geolocation_enabled" {{ Parametre::get('mobile.geolocation_enabled') ? 'checked' : '' }}>
                            <label class="form-check-label">Géolocalisation</label>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="mobile_offline_mode" {{ Parametre::get('mobile.offline_mode') ? 'checked' : '' }}>
                            <label class="form-check-label">Mode hors ligne</label>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>

            <!-- Onglet Paiement -->
            <div class="tab-pane {{ $categorie === 'paiement' ? 'active' : '' }}" id="paiement">
                <form action="{{ route('admin.superadmin.parametres.updateCategorie', 'paiement') }}" method="POST">
                    @csrf
                    <div class="settings-section">
                        <div class="section-header">
                            <h5 class="section-title"><i class="bi bi-credit-card-2-front"></i> Fedapay</h5>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="paiement_fedapay_enabled" {{ Parametre::get('paiement.fedapay.enabled') ? 'checked' : '' }}>
                            <label class="form-check-label">Activer Fedapay</label>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Mode</label>
                                    <select class="form-select" name="paiement_fedapay_mode">
                                        <option value="sandbox" {{ Parametre::get('paiement.fedapay.mode') === 'sandbox' ? 'selected' : '' }}>Sandbox (Test)</option>
                                        <option value="live" {{ Parametre::get('paiement.fedapay.mode') === 'live' ? 'selected' : '' }}>Production</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">API Key</label>
                                    <input type="text" class="form-control" name="paiement_fedapay_api_key" value="{{ Parametre::get('paiement.fedapay.api_key', '') }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Secret Key</label>
                            <input type="password" class="form-control" name="paiement_fedapay_secret_key" placeholder="••••••••">
                        </div>
                    </div>

                    <div class="settings-section">
                        <div class="section-header">
                            <h5 class="section-title"><i class="bi bi-currency-bitcoin"></i> Stripe</h5>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="paiement_stripe_enabled" {{ Parametre::get('paiement.stripe.enabled') ? 'checked' : '' }}>
                            <label class="form-check-label">Activer Stripe</label>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Clé publique</label>
                                    <input type="text" class="form-control" name="paiement_stripe_key" value="{{ Parametre::get('paiement.stripe.key', '') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Secret</label>
                                    <input type="password" class="form-control" name="paiement_stripe_secret" placeholder="••••••••">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>

            <!-- Onglet Sauvegarde -->
            <div class="tab-pane {{ $categorie === 'backup' ? 'active' : '' }}" id="backup">
                <form action="{{ route('admin.superadmin.parametres.updateCategorie', 'backup') }}" method="POST">
                    @csrf
                    <div class="settings-section">
                        <div class="section-header">
                            <h5 class="section-title"><i class="bi bi-cloud-arrow-up"></i> Sauvegarde Automatique</h5>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="backup_enabled" {{ Parametre::get('backup.enabled') ? 'checked' : '' }}>
                            <label class="form-check-label">Activer les sauvegardes automatiques</label>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Fréquence</label>
                                    <select class="form-select" name="backup_frequency">
                                        <option value="hourly" {{ Parametre::get('backup.frequency') === 'hourly' ? 'selected' : '' }}>Toutes les heures</option>
                                        <option value="daily" {{ Parametre::get('backup.frequency') === 'daily' ? 'selected' : '' }}>Quotidienne</option>
                                        <option value="weekly" {{ Parametre::get('backup.frequency') === 'weekly' ? 'selected' : '' }}>Hebdomadaire</option>
                                        <option value="monthly" {{ Parametre::get('backup.frequency') === 'monthly' ? 'selected' : '' }}>Mensuelle</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Conservation (jours)</label>
                                    <input type="number" class="form-control" name="backup_retention_days" value="{{ Parametre::get('backup.retention_days', 30) }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="backup_compress" {{ Parametre::get('backup.compress') ? 'checked' : '' }}>
                            <label class="form-check-label">Compresser les sauvegardes</label>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Enregistrer
                        </button>
                        <a href="{{ route('admin.superadmin.parametres.backups') }}" class="btn btn-outline-light">
                            <i class="bi bi-folder me-1"></i> Voir les sauvegardes
                        </a>
                    </div>
                </form>
            </div>

            <!-- Onglet Notifications -->
            <div class="tab-pane {{ $categorie === 'notifications' ? 'active' : '' }}" id="notifications">
                <form action="{{ route('admin.superadmin.parametres.updateCategorie', 'notifications') }}" method="POST">
                    @csrf
                    <div class="settings-section">
                        <div class="section-header">
                            <h5 class="section-title"><i class="bi bi-bell"></i> Canaux de Notification</h5>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="notifications_email_enabled" {{ Parametre::get('notifications.email_enabled') ? 'checked' : '' }}>
                            <label class="form-check-label">Notifications par email</label>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="notifications_sms_enabled" {{ Parametre::get('notifications.sms_enabled') ? 'checked' : '' }}>
                            <label class="form-check-label">Notifications SMS</label>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="notifications_push_enabled" {{ Parametre::get('notifications.push_enabled') ? 'checked' : '' }}>
                            <label class="form-check-label">Notifications Push</label>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>

            <!-- Onglet Abonnement -->
            <div class="tab-pane {{ $categorie === 'abonnement' ? 'active' : '' }}" id="abonnement">
                <form action="{{ route('admin.superadmin.parametres.updateCategorie', 'abonnement') }}" method="POST">
                    @csrf
                    <div class="settings-section">
                        <div class="section-header">
                            <h5 class="section-title"><i class="bi bi-person-badge"></i> Configuration des Abonnements</h5>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Jours d'essai gratuit</label>
                                    <input type="number" class="form-control" name="abonnement_trial_days" value="{{ Parametre::get('abonnement.trial_days', 14) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Période de grâce (jours)</label>
                                    <input type="number" class="form-control" name="abonnement_grace_period" value="{{ Parametre::get('abonnement.grace_period', 7) }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Plan par défaut</label>
                                    <select class="form-select" name="abonnement_default_plan">
                                        <option value="basic" {{ Parametre::get('abonnement.default_plan') === 'basic' ? 'selected' : '' }}>Basic</option>
                                        <option value="premium" {{ Parametre::get('abonnement.default_plan') === 'premium' ? 'selected' : '' }}>Premium</option>
                                        <option value="enterprise" {{ Parametre::get('abonnement.default_plan') === 'enterprise' ? 'selected' : '' }}>Enterprise</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="abonnement_auto_renew" {{ Parametre::get('abonnement.auto_renew') ? 'checked' : '' }}>
                            <label class="form-check-label">Renouvellement automatique par défaut</label>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>

            <!-- Onglet Facturation -->
            <div class="tab-pane {{ $categorie === 'facturation' ? 'active' : '' }}" id="facturation">
                <form action="{{ route('admin.superadmin.parametres.updateCategorie', 'facturation') }}" method="POST">
                    @csrf
                    <div class="settings-section">
                        <div class="section-header">
                            <h5 class="section-title"><i class="bi bi-receipt"></i> Configuration de Facturation</h5>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Taux de TVA (%)</label>
                                    <input type="number" class="form-control" name="facturation_tva" value="{{ Parametre::get('facturation.tva', 18) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Délai de paiement (jours)</label>
                                    <input type="number" class="form-control" name="facturation_delai_paiement" value="{{ Parametre::get('facturation.delai_paiement', 30) }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Devise</label>
                                    <select class="form-select" name="facturation_devise">
                                        <option value="XOF" {{ Parametre::get('facturation.devise') === 'XOF' ? 'selected' : '' }}>XOF (Franc CFA)</option>
                                        <option value="EUR" {{ Parametre::get('facturation.devise') === 'EUR' ? 'selected' : '' }}>EUR (Euro)</option>
                                        <option value="USD" {{ Parametre::get('facturation.devise') === 'USD' ? 'selected' : '' }}>USD (Dollar)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Pénalité de retard (%)</label>
                                    <input type="number" class="form-control" name="facturation_penalite_retard" value="{{ Parametre::get('facturation.penalite_retard', 5) }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Test Email -->
<div class="modal fade" id="testEmailModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="background: var(--bg-card); border: 1px solid var(--border-color);">
            <div class="modal-header">
                <h5 class="modal-title text-white">Tester la configuration email</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.superadmin.parametres.test-email') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Adresse email de test</label>
                        <input type="email" class="form-control" name="email" required placeholder="votre@email.com">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send me-1"></i> Envoyer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function switchTab(categorie) {
        // Update URL without reload
        const url = new URL(window.location.href);
        url.searchParams.set('categorie', categorie);
        window.history.pushState({}, '', url);

        // Update tabs
        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
        document.querySelector(`.tab-btn[onclick="switchTab('${categorie}')"]`).classList.add('active');

        // Update content
        document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active'));
        document.getElementById(categorie).classList.add('active');
    }

    // Initialize from URL
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const categorie = urlParams.get('categorie') || 'general';
        switchTab(categorie);
    });
</script>
@endsection