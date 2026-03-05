@extends('layouts.app')

@section('title', 'Statut Système - Super Admin')

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

    .status-container {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
        min-height: 100vh;
        padding: 1.5rem;
    }

    .status-header {
        background: linear-gradient(135deg, rgba(25, 135, 84, 0.2) 0%, rgba(32, 201, 151, 0.1) 100%);
        border: 1px solid rgba(25, 135, 84, 0.3);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .status-header h1 {
        color: #fff;
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .status-header p {
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

    /* Info Cards */
    .info-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .info-card .card-title {
        color: #fff;
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-card .card-title i {
        color: var(--primary-light);
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--border-color);
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        color: var(--text-muted);
    }

    .info-value {
        color: #fff;
        font-weight: 500;
    }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .status-badge.success {
        background: rgba(25, 135, 84, 0.2);
        color: #20c997;
    }

    .status-badge.warning {
        background: rgba(245, 158, 11, 0.2);
        color: #f59e0b;
    }

    .status-badge.danger {
        background: rgba(220, 53, 69, 0.2);
        color: #f8d7da;
    }

    /* Quick Actions */
    .quick-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-top: 1rem;
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

    /* Responsive */
    @media (max-width: 768px) {
        .status-container {
            padding: 1rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="status-container">
    <!-- En-tête -->
    <div class="status-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1><i class="bi bi-speedometer2 me-2"></i>Statut Système</h1>
                <p>Surveillez la santé et les performances de votre plateforme Benin Security</p>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="quick-actions">
                    <a href="{{ route('admin.superadmin.parametres.index') }}" class="quick-action-btn">
                        <i class="bi bi-gear"></i> Paramètres
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
                <i class="bi bi-building" style="color: #20c997;"></i>
            </div>
            <div class="stat-value">{{ $stats['entreprises'] }}</div>
            <div class="stat-label">Entreprises</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(59, 130, 246, 0.2);">
                <i class="bi bi-people" style="color: #3b82f6;"></i>
            </div>
            <div class="stat-value">{{ $stats['utilisateurs'] }}</div>
            <div class="stat-label">Utilisateurs</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(245, 158, 11, 0.2);">
                <i class="bi bi-person-badge" style="color: #f59e0b;"></i>
            </div>
            <div class="stat-value">{{ $stats['employes'] }}</div>
            <div class="stat-label">Employés</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(168, 85, 247, 0.2);">
                <i class="bi bi-credit-card" style="color: #a855f7;"></i>
            </div>
            <div class="stat-value">{{ $stats['abonnements'] }}</div>
            <div class="stat-label">Abonnements</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(236, 72, 153, 0.2);">
                <i class="bi bi-receipt" style="color: #ec4899;"></i>
            </div>
            <div class="stat-value">{{ $stats['factures'] }}</div>
            <div class="stat-label">Factures</div>
        </div>
    </div>

    <div class="row">
        <!-- Informations Système -->
        <div class="col-md-6">
            <div class="info-card">
                <h5 class="card-title"><i class="bi bi-info-circle"></i> Informations Système</h5>

                <div class="info-row">
                    <span class="info-label">Version PHP</span>
                    <span class="info-value">{{ $systemStatus['php'] }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Version Laravel</span>
                    <span class="info-value">{{ $systemStatus['laravel'] }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Environnement</span>
                    <span class="info-value">{{ $systemStatus['environment'] }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Mode Debug</span>
                    <span class="info-value">
                        <span class="status-badge {{ $systemStatus['debug'] === 'Activé' ? 'success' : 'warning' }}">
                            {{ $systemStatus['debug'] }}
                        </span>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Fuseau horaire</span>
                    <span class="info-value">{{ $systemStatus['timezone'] }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Langue</span>
                    <span class="info-value">{{ $systemStatus['locale'] }}</span>
                </div>
            </div>
        </div>

        <!-- Configuration -->
        <div class="col-md-6">
            <div class="info-card">
                <h5 class="card-title"><i class="bi bi-gear"></i> Configuration</h5>

                <div class="info-row">
                    <span class="info-label">Base de données</span>
                    <span class="info-value">{{ $systemStatus['database'] }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Cache</span>
                    <span class="info-value">{{ $systemStatus['cache'] }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Session</span>
                    <span class="info-value">{{ $systemStatus['session'] }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Mode Maintenance</span>
                    <span class="info-value">
                        <span class="status-badge {{ $systemStatus['maintenance'] === 'Désactivé' ? 'success' : 'danger' }}">
                            {{ $systemStatus['maintenance'] }}
                        </span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Dernière sauvegarde -->
    <div class="info-card">
        <h5 class="card-title"><i class="bi bi-cloud-arrow-up"></i> Dernière Sauvegarde</h5>

        @if($lastBackup)
        <div class="info-row">
            <span class="info-label">Nom du fichier</span>
            <span class="info-value">{{ $lastBackup['nom'] }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Date</span>
            <span class="info-value">{{ $lastBackup['date'] }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Taille</span>
            <span class="info-value">{{ $lastBackup['taille'] }}</span>
        </div>
        @else
        <div class="info-row">
            <span class="info-label">Aucune sauvegarde trouvée</span>
            <span class="info-value">
                <span class="status-badge warning">Aucune</span>
            </span>
        </div>
        @endif

        <div class="mt-3">
            <a href="{{ route('admin.superadmin.parametres.backups') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Créer une sauvegarde
            </a>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="info-card">
        <h5 class="card-title"><i class="bi bi-lightning"></i> Actions Rapides</h5>

        <div class="quick-actions">
            <form action="{{ route('admin.superadmin.parametres.clear-cache') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="quick-action-btn">
                    <i class="bi bi-trash"></i> Vider le cache
                </button>
            </form>

            <form action="{{ route('admin.superadmin.parametres.optimize') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="quick-action-btn">
                    <i class="bi bi-speedometer"></i> Optimiser
                </button>
            </form>

            <a href="{{ route('admin.superadmin.parametres.logs') }}" class="quick-action-btn">
                <i class="bi bi-journal-text"></i> Voir les logs
            </a>

            <a href="{{ route('admin.superadmin.parametres.download-logs') }}" class="quick-action-btn">
                <i class="bi bi-download"></i> Télécharger les logs
            </a>
        </div>
    </div>
</div>
@endsection