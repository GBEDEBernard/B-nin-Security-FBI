@extends('layouts.app')

@section('title', 'Sites - Entreprise')

@push('styles')
<style>
    /* ═════════════════════════════════════════════════════════════
       STYLES MODERNES POUR LA LISTE DES SITES
       Compatible mode clair et sombre
       ═════════════════════════════════════════════════════════════ */

    .page-header {
        background: linear-gradient(135deg, #0d6efd 0%, #6ea8fe 100%);
        padding: 1.5rem;
        border-radius: 16px;
        margin-bottom: 1.5rem;
        color: white;
    }

    .page-header h3 {
        font-weight: 600;
        font-size: 1.5rem;
    }

    .page-header .breadcrumb {
        margin: 0;
        padding: 0;
        background: transparent;
    }

    .page-header .breadcrumb-item,
    .page-header .breadcrumb-item a {
        color: rgba(255, 255, 255, 0.85);
        font-size: 0.875rem;
    }

    .page-header .breadcrumb-item.active {
        color: white;
        font-weight: 500;
    }

    .stat-card {
        border: none;
        border-radius: 12px;
        padding: 1rem;
        height: 100%;
        transition: transform 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-3px);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .stat-number {
        font-size: 1.75rem;
        font-weight: 700;
        line-height: 1;
    }

    .stat-label {
        font-size: 0.8rem;
        color: var(--bs-secondary-color);
        margin-top: 0.25rem;
    }

    .filter-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.05);
        background: var(--bs-body-bg);
    }

    .filter-card .card-body {
        padding: 1.25rem;
    }

    .search-input {
        border-radius: 10px;
        border: 1.5px solid var(--bs-border-color);
        padding: 0.6rem 1rem;
        background: var(--bs-body-bg);
        color: var(--bs-body-color);
    }

    .search-input:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
    }

    .filter-select {
        border-radius: 10px;
        border: 1.5px solid var(--bs-border-color);
        padding: 0.6rem 1rem;
        background: var(--bs-body-bg);
        color: var(--bs-body-color);
    }

    /* Force le style pour le mode sombre - background sombre avec texte clair */
    [data-bs-theme="dark"] .filter-select {
        background-color: #1e1e1e !important;
        color: #ffffff !important;
        border-color: #444444;
    }

    /* Options de select en mode sombre */
    [data-bs-theme="dark"] .filter-select option {
        background-color: #1e1e1e;
        color: #ffffff;
        padding: 0.5rem;
    }

    /* En mode clair */
    [data-bs-theme="light"] .filter-select {
        background-color: #ffffff;
        color: #212529;
        border-color: #dee2e6;
    }

    [data-bs-theme="light"] .filter-select option {
        background-color: #ffffff;
        color: #212529;
    }

    .filter-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
    }

    .btn-filter {
        background: #0d6efd;
        border: none;
        border-radius: 10px;
        padding: 0.6rem 1.25rem;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .btn-filter:hover {
        background: #146c43;
        transform: translateY(-1px);
    }

    .btn-new {
        background: linear-gradient(135deg, #0d6efd 0%, #0d6efd 100%);
        border: none;
        border-radius: 10px;
        padding: 0.6rem 1.25rem;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .btn-new:hover {
        background: #0b5ed7;
        transform: translateY(-1px);
        color: white;
    }

    .table-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.05);
        background: var(--bs-body-bg);
        overflow: hidden;
    }

    .table-card .card-header {
        background: var(--bs-body-bg);
        border-bottom: 1px solid var(--bs-border-color);
        padding: 1rem 1.5rem;
    }

    .table-card .card-header h5 {
        margin: 0;
        font-weight: 600;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .table-card .card-body {
        padding: 0;
    }

    .table thead th {
        background: var(--bs-tertiary-bg);
        border-bottom: 2px solid var(--bs-border-color);
        color: var(--bs-body-color);
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 0.75rem 1rem;
    }

    .table tbody td {
        padding: 0.85rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--bs-border-color);
        color: var(--bs-body-color);
    }

    .table tbody tr:hover {
        background: var(--bs-tertiary-bg);
    }

    .site-name {
        font-weight: 600;
        color: var(--bs-body-color);
        font-size: 0.95rem;
    }

    .site-code {
        font-size: 0.8rem;
        color: var(--bs-secondary-color);
    }

    .client-name {
        font-weight: 500;
        color: var(--bs-body-color);
    }

    .risque-badge {
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
    }

    .risque-faible {
        background: rgba(13, 110, 253, 0.15);
        color: #0d6efd;
    }

    .risque-moyen {
        background: rgba(255, 193, 7, 0.2);
        color: #997404;
    }

    .risque-haut {
        background: rgba(253, 126, 20, 0.15);
        color: #fd7e14;
    }

    .risque-critique {
        background: rgba(220, 53, 69, 0.15);
        color: #dc3545;
    }

    .status-badge {
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .status-actif {
        background: rgba(13, 110, 253, 0.15);
        color: #0d6efd;
    }

    .status-inactif {
        background: rgba(108, 117, 125, 0.15);
        color: #6c757d;
    }

    .btn-action {
        padding: 0.4rem 0.7rem;
        border-radius: 8px;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }

    .btn-action:hover {
        transform: scale(1.05);
    }

    .btn-view {
        color: #0d6efd;
        background: rgba(13, 110, 253, 0.1);
        border: none;
    }

    .btn-view:hover {
        background: rgba(13, 110, 253, 0.2);
        color: #0d6efd;
    }

    .btn-edit {
        color: #0d6efd;
        background: rgba(13, 110, 253, 0.1);
        border: none;
    }

    .btn-edit:hover {
        background: rgba(13, 110, 253, 0.2);
        color: #0d6efd;
    }

    .btn-delete {
        color: #dc3545;
        background: rgba(220, 53, 69, 0.1);
        border: none;
    }

    .btn-delete:hover {
        background: rgba(220, 53, 69, 0.2);
        color: #dc3545;
    }

    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
    }

    .empty-state-icon {
        font-size: 4rem;
        color: var(--bs-secondary-color);
        opacity: 0.5;
        margin-bottom: 1rem;
    }

    .empty-state h5 {
        color: var(--bs-body-color);
        font-weight: 600;
    }

    .empty-state p {
        color: var(--bs-secondary-color);
        margin-bottom: 1.5rem;
    }

    /* Pagination styles */
    .pagination-wrapper {
        padding: 1rem 1.5rem;
        border-top: 1px solid var(--bs-border-color);
    }

    .page-link {
        border-radius: 8px;
        margin: 0 2px;
        color: var(--bs-body-color);
        border-color: var(--bs-border-color);
    }

    .page-link:hover {
        background: var(--bs-tertiary-bg);
        border-color: #0d6efd;
        color: #0d6efd;
    }

    .page-item.active .page-link {
        background: #0d6efd;
        border-color: #0d6efd;
    }

    /* Animation sutilité */
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

    .animate-fade-in {
        animation: fadeIn 0.3s ease-out;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- En-tête de page -->
    <div class="page-header animate-fade-in">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h3><i class="bi bi-geo-alt-fill me-2"></i>Gestion des Sites</h3>
            </div>
            <div class="col-md-6">
                <ol class="breadcrumb float-md-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.entreprise.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Sites</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Messages de session -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show animate-fade-in" role="alert" style="animation-delay: 0.1s;">
        <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show animate-fade-in" role="alert" style="animation-delay: 0.1s;">
        <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Statistiques -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stat-card bg-primary bg-opacity-10 animate-fade-in" style="animation-delay: 0.15s;">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary bg-opacity-15 text-primary">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                    <div>
                        <div class="stat-number">{{ $stats['total'] }}</div>
                        <div class="stat-label">Total Sites</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card bg-success bg-opacity-10 animate-fade-in" style="animation-delay: 0.2s;">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-success bg-opacity-15 text-success">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div>
                        <div class="stat-number">{{ $stats['actifs'] }}</div>
                        <div class="stat-label">Sites Actifs</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card bg-secondary bg-opacity-10 animate-fade-in" style="animation-delay: 0.25s;">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-secondary bg-opacity-15 text-secondary">
                        <i class="bi bi-pause-circle"></i>
                    </div>
                    <div>
                        <div class="stat-number">{{ $stats['inactifs'] }}</div>
                        <div class="stat-label">Sites Inactifs</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card bg-danger bg-opacity-10 animate-fade-in" style="animation-delay: 0.3s;">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-danger bg-opacity-15 text-danger">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <div>
                        <div class="stat-number">{{ $stats['haut_risque'] }}</div>
                        <div class="stat-label">Haut Risque</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card filter-card mb-4 animate-fade-in" style="animation-delay: 0.35s;">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Rechercher</label>
                    <input type="text" name="search" class="form-control search-input" placeholder="Nom, code, adresse..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Client</label>
                    <select name="client_id" class="form-control filter-select">
                        <option value="">Tous les clients</option>
                        @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                            {{ $client->nom }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Niveau risque</label>
                    <select name="niveau_risque" class="form-control filter-select">
                        <option value="">Tous</option>
                        <option value="faible" {{ request('niveau_risque') == 'faible' ? 'selected' : '' }}>Faible</option>
                        <option value="moyen" {{ request('niveau_risque') == 'moyen' ? 'selected' : '' }}>Moyen</option>
                        <option value="haut" {{ request('niveau_risque') == 'haut' ? 'selected' : '' }}>Haut</option>
                        <option value="critique" {{ request('niveau_risque') == 'critique' ? 'selected' : '' }}>Critique</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Statut</label>
                    <select name="est_actif" class="form-control filter-select">
                        <option value="">Tous</option>
                        <option value="1" {{ request('est_actif') == '1' ? 'selected' : '' }}>Actif</option>
                        <option value="0" {{ request('est_actif') == '0' ? 'selected' : '' }}>Inactif</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-filter text-white flex-grow-1">
                            <i class="bi bi-funnel me-1"></i> Filtrer
                        </button>
                        <a href="{{ route('admin.entreprise.sites.index') }}" class="btn btn-outline-secondary" title="Réinitialiser">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des sites -->
    <div class="card table-card animate-fade-in" style="animation-delay: 0.4s;">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5><i class="bi bi-list-ul me-2"></i>Liste des sites ({{ $sites->total() }})</h5>
            <a href="{{ route('admin.entreprise.sites.create') }}" class="btn btn-new text-white">
                <i class="bi bi-plus-circle me-1"></i> Nouveau site
            </a>
        </div>
        <div class="card-body p-0">
            @if($sites->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th><i class="bi bi-geo-alt me-1"></i> Site</th>
                            <th><i class="bi bi-building me-1"></i> Client</th>
                            <th><i class="bi bi-pin-map me-1"></i> Adresse</th>
                            <th><i class="bi bi-person me-1"></i> Contact</th>
                            <th><i class="bi bi-shield-exclamation me-1"></i> Risque</th>
                            <th><i class="bi bi-toggle-on me-1"></i> Statut</th>
                            <th class="text-end"><i class="bi bi-gear me-1"></i> Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sites as $site)
                        <tr>
                            <td>
                                <div class="site-name">{{ $site->nom_site }}</div>
                                <div class="site-code">{{ $site->code_site }}</div>
                            </td>
                            <td>
                                <span class="client-name">{{ $site->client?->nom ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <div>{{ $site->adresse }}</div>
                                <small class="text-muted">{{ $site->ville }}, {{ $site->commune }}</small>
                            </td>
                            <td>
                                @if($site->contact_nom)
                                <div class="fw-medium">{{ $site->contact_nom }}</div>
                                <small class="text-muted">{{ $site->contact_telephone }}</small>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="risque-badge risque-{{ $site->niveau_risque ?? 'faible' }}">
                                    {{ ucfirst($site->niveau_risque ?? 'faible') }}
                                </span>
                            </td>
                            <td>
                                @if($site->est_actif)
                                <span class="status-badge status-actif">
                                    <i class="bi bi-check-circle me-1"></i> Actif
                                </span>
                                @else
                                <span class="status-badge status-inactif">
                                    <i class="bi bi-pause-circle me-1"></i> Inactif
                                </span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.entreprise.sites.show', $site->id) }}" class="btn btn-action btn-view" title="Voir">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.entreprise.sites.edit', $site->id) }}" class="btn btn-action btn-edit" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-action btn-delete" title="Supprimer" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $site->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <!-- Modal de suppression -->
                        <div class="modal fade" id="deleteModal{{ $site->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">
                                            <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                                            Confirmer la suppression
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Êtes-vous sûr de vouloir supprimer le site <strong>{{ $site->nom_site }}</strong> ?</p>
                                        @if($site->contrats()->count() > 0 || $site->affectations()->count() > 0)
                                        <div class="alert alert-warning">
                                            <i class="bi bi-exclamation-circle me-2"></i>
                                            Ce site est associé à des contrats ou des agents.
                                        </div>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                        @if($site->contrats()->count() == 0 && $site->affectations()->count() == 0)
                                        <form action="{{ route('admin.entreprise.sites.destroy', $site->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">
                                                <i class="bi bi-trash me-1"></i> Supprimer
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="bi bi-geo-alt"></i>
                </div>
                <h5>Aucun site trouvé</h5>
                <p>Commencez par créer votre premier site client</p>
                <a href="{{ route('admin.entreprise.sites.create') }}" class="btn btn-new text-white">
                    <i class="bi bi-plus-circle me-1"></i> Créer un site
                </a>
            </div>
            @endif
        </div>
        @if($sites->hasPages())
        <div class="pagination-wrapper">
            {{ $sites->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection