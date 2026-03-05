@extends('layouts.app')

@section('title', 'Sauvegardes - Super Admin')

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

    .backups-container {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
        min-height: 100vh;
        padding: 1.5rem;
    }

    .backups-header {
        background: linear-gradient(135deg, rgba(25, 135, 84, 0.2) 0%, rgba(32, 201, 151, 0.1) 100%);
        border: 1px solid rgba(25, 135, 84, 0.3);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .backups-header h1 {
        color: #fff;
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .backups-header p {
        color: var(--text-muted);
        margin-bottom: 0;
    }

    /* Cards */
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

    /* Table */
    .table-responsive {
        border-radius: 8px;
        overflow: hidden;
    }

    .table {
        margin-bottom: 0;
        color: #fff;
    }

    .table thead th {
        background: rgba(0, 0, 0, 0.3);
        border-bottom: 1px solid var(--border-color);
        color: var(--text-muted);
        font-weight: 600;
        padding: 1rem;
    }

    .table tbody td {
        background: rgba(0, 0, 0, 0.2);
        border-bottom: 1px solid var(--border-color);
        padding: 1rem;
        vertical-align: middle;
    }

    .table tbody tr:hover td {
        background: rgba(25, 135, 84, 0.1);
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

    .btn-danger {
        background: rgba(220, 53, 69, 0.2);
        border: 1px solid rgba(220, 53, 69, 0.3);
        color: #f8d7da;
    }

    .btn-danger:hover {
        background: rgba(220, 53, 69, 0.4);
        color: #fff;
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
        text-decoration: none;
    }

    .quick-action-btn:hover {
        background: rgba(25, 135, 84, 0.2);
        border-color: var(--primary-color);
        transform: translateY(-2px);
        color: #fff;
    }

    .quick-action-btn i {
        color: var(--primary-light);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem;
    }

    .empty-state i {
        font-size: 4rem;
        color: var(--text-muted);
        margin-bottom: 1rem;
    }

    .empty-state h5 {
        color: #fff;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: var(--text-muted);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .backups-container {
            padding: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="backups-container">
    <!-- En-tête -->
    <div class="backups-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1><i class="bi bi-cloud-arrow-up me-2"></i>Sauvegardes</h1>
                <p>Gérez les sauvegardes de votre plateforme Benin Security</p>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="quick-actions">
                    <a href="{{ route('admin.superadmin.parametres.index') }}" class="quick-action-btn">
                        <i class="bi bi-gear"></i> Paramètres
                    </a>
                    <a href="{{ route('admin.superadmin.parametres.status') }}" class="quick-action-btn">
                        <i class="bi bi-speedometer2"></i> Status
                    </a>
                </div>
            </div>
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

    <!-- Créer une sauvegarde -->
    <div class="info-card">
        <h5 class="card-title"><i class="bi bi-plus-circle"></i> Créer une sauvegarde</h5>

        <form action="{{ route('admin.superadmin.parametres.create-backup') }}" method="POST" class="row align-items-end">
            @csrf
            <div class="col-md-4">
                <label class="form-label">Type de sauvegarde</label>
                <select name="type" class="form-select">
                    <option value="full">Complète (Base de données + Fichiers)</option>
                    <option value="database">Base de données uniquement</option>
                    <option value="files">Fichiers uniquement</option>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-cloud-arrow-up me-1"></i> Créer la sauvegarde
                </button>
            </div>
        </form>
    </div>

    <!-- Liste des sauvegardes -->
    <div class="info-card">
        <h5 class="card-title"><i class="bi bi-collection"></i> Sauvegardes existantes</h5>

        @if(count($backups) > 0)
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nom du fichier</th>
                        <th>Date de création</th>
                        <th>Taille</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($backups as $backup)
                    <tr>
                        <td>
                            <i class="bi bi-file-earmark-zip me-2"></i>
                            {{ $backup['nom'] }}
                        </td>
                        <td>{{ $backup['date'] }}</td>
                        <td>{{ $backup['taille'] }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <form action="{{ route('admin.superadmin.parametres.delete-backup', $backup['nom']) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette sauvegarde?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state">
            <i class="bi bi-cloud-slash"></i>
            <h5>Aucune sauvegarde</h5>
            <p>Aucune sauvegarde n'a été trouvée. Créez votre première sauvegarde.</p>
        </div>
        @endif
    </div>

    <!-- Informations -->
    <div class="info-card">
        <h5 class="card-title"><i class="bi bi-info-circle"></i> Informations</h5>

        <p style="color: var(--text-muted);">
            Les sauvegardes sont stockées dans le répertoire <code>storage/app/backups</code>.
            Il est recommandé de faire des sauvegardes régulières de votre base de données.
        </p>
    </div>
</div>
@endsection