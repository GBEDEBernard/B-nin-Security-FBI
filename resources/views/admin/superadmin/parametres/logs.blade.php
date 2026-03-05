@extends('layouts.app')

@section('title', 'Logs Système - Super Admin')

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

    .logs-container {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
        min-height: 100vh;
        padding: 1.5rem;
    }

    .logs-header {
        background: linear-gradient(135deg, rgba(25, 135, 84, 0.2) 0%, rgba(32, 201, 151, 0.1) 100%);
        border: 1px solid rgba(25, 135, 84, 0.3);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .logs-header h1 {
        color: #fff;
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .logs-header p {
        color: var(--text-muted);
        margin-bottom: 0;
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 1.25rem;
        transition: all 0.3s ease;
        text-align: center;
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
        margin: 0 auto 0.75rem;
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

    /* Filter Section */
    .filter-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .filter-card .card-title {
        color: #fff;
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-card .card-title i {
        color: var(--primary-light);
    }

    .filter-form {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: flex-end;
    }

    .filter-group {
        flex: 1;
        min-width: 200px;
    }

    .filter-group label {
        display: block;
        color: var(--text-muted);
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    .filter-group select,
    .filter-group input {
        width: 100%;
        padding: 0.625rem 1rem;
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        color: #fff;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .filter-group select:focus,
    .filter-group input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.1);
    }

    .filter-group select option {
        background: var(--bg-card);
        color: #fff;
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
        text-decoration: none;
    }

    .quick-action-btn:hover {
        background: rgba(25, 135, 84, 0.2);
        border-color: var(--primary-color);
        transform: translateY(-2px);
    }

    .quick-action-btn i {
        color: var(--primary-light);
    }

    .quick-action-btn.danger:hover {
        background: rgba(220, 53, 69, 0.2);
        border-color: #dc3545;
    }

    .quick-action-btn.danger i {
        color: #f8d7da;
    }

    /* Log Viewer */
    .log-viewer {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        overflow: hidden;
    }

    .log-viewer-header {
        background: rgba(0, 0, 0, 0.3);
        border-bottom: 1px solid var(--border-color);
        padding: 1rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .log-viewer-header h5 {
        color: #fff;
        margin: 0;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .log-viewer-header h5 i {
        color: var(--primary-light);
    }

    .log-count {
        color: var(--text-muted);
        font-size: 0.875rem;
    }

    .log-content {
        max-height: 600px;
        overflow-y: auto;
        padding: 1rem;
        background: #0d0d0d;
        font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
        font-size: 0.8rem;
        line-height: 1.6;
    }

    .log-line {
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        margin-bottom: 0.25rem;
        white-space: pre-wrap;
        word-break: break-all;
    }

    .log-line:hover {
        background: rgba(255, 255, 255, 0.05);
    }

    .log-line.error {
        background: rgba(220, 53, 69, 0.1);
        border-left: 3px solid #dc3545;
    }

    .log-line.warning {
        background: rgba(245, 158, 11, 0.1);
        border-left: 3px solid #f59e0b;
    }

    .log-line.info {
        background: rgba(59, 130, 246, 0.1);
        border-left: 3px solid #3b82f6;
    }

    .log-line.debug {
        background: rgba(168, 85, 247, 0.1);
        border-left: 3px solid #a855f7;
    }

    .log-line .timestamp {
        color: #6c757d;
    }

    .log-line .level {
        font-weight: 600;
        padding: 0.125rem 0.5rem;
        border-radius: 4px;
        margin-right: 0.5rem;
    }

    .log-line .level.ERROR {
        background: rgba(220, 53, 69, 0.3);
        color: #f8d7da;
    }

    .log-line .level.WARNING {
        background: rgba(245, 158, 11, 0.3);
        color: #fef3c7;
    }

    .log-line .level.INFO {
        background: rgba(59, 130, 246, 0.3);
        color: #bfdbfe;
    }

    .log-line .level.DEBUG {
        background: rgba(168, 85, 247, 0.3);
        color: #e9d5ff;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem;
        color: var(--text-muted);
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: var(--border-color);
    }

    .empty-state h4 {
        color: #fff;
        margin-bottom: 0.5rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .logs-container {
            padding: 1rem;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .filter-form {
            flex-direction: column;
        }

        .filter-group {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="logs-container">
    <!-- En-tête -->
    <div class="logs-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1><i class="bi bi-journal-text me-2"></i>Logs Système</h1>
                <p>Visualisez et analysez les journaux d'application</p>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="quick-actions">
                    <a href="{{ route('admin.superadmin.parametres.index') }}" class="quick-action-btn">
                        <i class="bi bi-gear"></i> Paramètres
                    </a>
                    <a href="{{ route('admin.superadmin.parametres.status') }}" class="quick-action-btn">
                        <i class="bi bi-speedometer2"></i> Statut
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
            <div class="stat-icon" style="background: rgba(255, 255, 255, 0.1);">
                <i class="bi bi-list-ul" style="color: #fff;"></i>
            </div>
            <div class="stat-value">{{ $stats['total'] }}</div>
            <div class="stat-label">Total Lignes</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(220, 53, 69, 0.2);">
                <i class="bi bi-exclamation-triangle" style="color: #f8d7da;"></i>
            </div>
            <div class="stat-value">{{ $stats['error'] }}</div>
            <div class="stat-label">Erreurs</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(245, 158, 11, 0.2);">
                <i class="bi bi-exclamation-circle" style="color: #fef3c7;"></i>
            </div>
            <div class="stat-value">{{ $stats['warning'] }}</div>
            <div class="stat-label">Avertissements</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(59, 130, 246, 0.2);">
                <i class="bi bi-info-circle" style="color: #bfdbfe;"></i>
            </div>
            <div class="stat-value">{{ $stats['info'] }}</div>
            <div class="stat-label">Informations</div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="filter-card">
        <h5 class="card-title"><i class="bi bi-funnel"></i> Filtres</h5>

        <form method="GET" action="{{ route('admin.superadmin.parametres.logs') }}" class="filter-form">
            <div class="filter-group">
                <label for="level">Niveau de log</label>
                <select name="level" id="level">
                    <option value="all" {{ $level === 'all' ? 'selected' : '' }}>Tous les niveaux</option>
                    <option value="ERROR" {{ $level === 'ERROR' ? 'selected' : '' }}>Erreurs</option>
                    <option value="WARNING" {{ $level === 'WARNING' ? 'selected' : '' }}>Avertissements</option>
                    <option value="INFO" {{ $level === 'INFO' ? 'selected' : '' }}>Informations</option>
                    <option value="DEBUG" {{ $level === 'DEBUG' ? 'selected' : '' }}>Débogage</option>
                </select>
            </div>

            <div class="filter-group">
                <label for="lines">Nombre de lignes</label>
                <select name="lines" id="lines">
                    <option value="50" {{ request('lines') == '50' ? 'selected' : '' }}>50 lignes</option>
                    <option value="100" {{ request('lines') == '100' || !request('lines') ? 'selected' : '' }}>100 lignes</option>
                    <option value="200" {{ request('lines') == '200' ? 'selected' : '' }}>200 lignes</option>
                    <option value="500" {{ request('lines') == '500' ? 'selected' : '' }}>500 lignes</option>
                </select>
            </div>

            <div class="filter-group" style="flex: 0; min-width: auto;">
                <button type="submit" class="btn btn-primary" style="padding: 0.625rem 1.5rem;">
                    <i class="bi bi-search me-1"></i> Filtrer
                </button>
            </div>
        </form>

        <div class="quick-actions">
            <a href="{{ route('admin.superadmin.parametres.download-logs') }}" class="quick-action-btn">
                <i class="bi bi-download"></i> Télécharger les logs
            </a>

            <form action="{{ route('admin.superadmin.parametres.purge-logs') }}" method="POST" class="d-inline"
                onsubmit="return confirm('Êtes-vous sûr de vouloir purger tous les logs ? Cette action est irréversible.');">
                @csrf
                <button type="submit" class="quick-action-btn danger">
                    <i class="bi bi-trash"></i> Purger les logs
                </button>
            </form>
        </div>
    </div>

    <!-- Log Viewer -->
    <div class="log-viewer">
        <div class="log-viewer-header">
            <h5><i class="bi bi-file-text"></i> Contenu des logs</h5>
            <span class="log-count">{{ count($logs) }} ligne(s)</span>
        </div>

        <div class="log-content">
            @if(count($logs) > 0)
            @foreach($logs as $log)
            @php
            $logClass = '';
            if (stripos($log, 'ERROR') !== false) {
            $logClass = 'error';
            } elseif (stripos($log, 'WARNING') !== false) {
            $logClass = 'warning';
            } elseif (stripos($log, 'INFO') !== false) {
            $logClass = 'info';
            } elseif (stripos($log, 'DEBUG') !== false) {
            $logClass = 'debug';
            }
            @endphp
            <div class="log-line {{ $logClass }}">{{ $log }}</div>
            @endforeach
            @else
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <h4>Aucun log trouvé</h4>
                <p>Aucune entrée de log disponible pour les critères sélectionnés.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection