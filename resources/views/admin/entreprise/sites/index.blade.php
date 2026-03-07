@extends('layouts.app')

@section('title', 'Gestion des Sites - Entreprise')

@push('styles')
<style>
    /* ═══════════════════════════════════════════════════════════════
       ULTRA PRO DESIGN - Liste des Sites
       ═══════════════════════════════════════════════════════════════ */

    /* ── Page Header ── */
    .page-header {
        background: linear-gradient(135deg, #198754 0%, #20c997 100%);
        padding: 1.75rem 2rem;
        border-radius: 20px;
        margin-bottom: 2rem;
        color: white;
        box-shadow: 0 4px 20px rgba(25, 135, 84, 0.3);
    }

    .page-header h3 {
        margin: 0;
        font-weight: 700;
        font-size: 1.6rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
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
        font-weight: 600;
    }

    .page-header .breadcrumb-item + .breadcrumb-item::before {
        color: rgba(255, 255, 255, 0.6);
    }

    /* ── Stat Cards ── */
    .stat-card {
        border: none;
        border-radius: 20px;
        padding: 1.5rem;
        height: 100%;
        transition: all 0.3s ease;
        background: var(--bs-body-bg);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .stat-icon.primary {
        background: rgba(25, 135, 84, 0.1);
        color: #198754;
    }

    .stat-icon.success {
        background: rgba(25, 135, 84, 0.1);
        color: #198754;
    }

    .stat-icon.secondary {
        background: rgba(108, 117, 125, 0.1);
        color: #6c757d;
    }

    .stat-icon.danger {
        background: rgba(220, 53, 69, 0.1);
        color: #dc3545;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 800;
        line-height: 1;
        color: var(--bs-body-color);
    }

    .stat-label {
        font-size: 0.8125rem;
        color: var(--bs-secondary-color);
        margin-top: 0.35rem;
        font-weight: 500;
    }

    /* ── Filter Card ── */
    .filter-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        background: var(--bs-body-bg);
    }

    .filter-card .card-body {
        padding: 1.5rem;
    }

    /* ── Form Elements ── */
    .form-label {
        font-weight: 600;
        font-size: 0.8125rem;
        color: var(--bs-body-color);
        margin-bottom: 0.5rem;
    }

    .form-control,
    .form-select {
        border-radius: 12px;
        border: 2px solid var(--bs-border-color);
        padding: 0.65rem 1rem;
        font-size: 0.9375rem;
        transition: all 0.25s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #198754;
        box-shadow: 0 0 0 4px rgba(25, 135, 84, 0.15);
        outline: none;
    }

    /* ── Buttons ── */
    .btn-filter {
        background: linear-gradient(135deg, #198754 0%, #20c997 100%);
        border: none;
        border-radius: 12px;
        padding: 0.7rem 1.5rem;
        font-weight: 600;
        color: #fff;
        transition: all 0.3s ease;
    }

    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(25, 135, 84, 0.3);
        color: #fff;
    }

    .btn-new {
        background: linear-gradient(135deg, #198754 0%, #20c997 100%);
        border: none;
        border-radius: 12px;
        padding: 0.7rem 1.5rem;
        font-weight: 600;
        color: #fff;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-new:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(25, 135, 84, 0.3);
        color: #fff;
    }

    /* ── Table Card ── */
    .table-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        background: var(--bs-body-bg);
        overflow: hidden;
    }

    .table-card .card-header {
        background: var(--bs-body-bg);
        border-bottom: 1px solid var(--bs-border-color);
        padding: 1.25rem 1.5rem;
    }

    .table-card .card-header h5 {
        margin: 0;
        font-weight: 700;
        font-size: 1.0625rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--bs-body-color);
    }

    .table-card .card-body {
        padding: 0;
    }

    /* ── Table Styles ── */
    .table {
        margin-bottom: 0;
    }

    .table thead th {
        background: var(--bs-tertiary-bg);
        border-bottom: 2px solid var(--bs-border-color);
        color: var(--bs-body-color);
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        padding: 1rem 1.25rem;
        white-space: nowrap;
    }

    .table tbody td {
        padding: 1rem 1.25rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--bs-border-color);
        color: var(--bs-body-color);
    }

    .table tbody tr:last-child td {
        border-bottom: none;
    }

    .table tbody tr:hover {
        background: var(--bs-tertiary-bg);
    }

    /* ── Site Info ── */
    .site-name {
        font-weight: 600;
        font-size: 0.9375rem;
        color: var(--bs-body-color);
    }

    .site-code {
        font-size: 0.8125rem;
        color: var(--bs-secondary-color);
        margin-top: 0.25rem;
    }

    .client-name {
        font-weight: 500;
        color: var(--bs-body-color);
    }

    /* ── Risk Badges ── */
    .risque-badge {
        padding: 0.35rem 0.85rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        display: inline-block;
    }

    .risque-faible {
        background: rgba(25, 135, 84, 0.12);
        color: #198754;
    }

    .risque-moyen {
        background: rgba(255, 193, 7, 0.15);
        color: #997404;
    }

    .risque-haut {
        background: rgba(253, 126, 20, 0.12);
        color: #d4621b;
    }

    .risque-critique {
        background: rgba(220, 53, 69, 0.12);
        color: #dc3545;
    }

    /* ── Status Badges ── */
    .status-badge {
        padding: 0.35rem 0.85rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .status-actif {
        background: rgba(25, 135, 84, 0.12);
        color: #198754;
    }

    .status-inactif {
        background: rgba(108, 117, 125, 0.12);
        color: #6c757d;
    }

    /* ── Action Buttons ── */
    .btn-action {
        padding: 0.4rem 0.7rem;
        border-radius: 10px;
        font-size: 0.9375rem;
        transition: all 0.2s ease;
        border: none;
    }

    .btn-action:hover {
        transform: scale(1.1);
    }

    .btn-view {
        color: #198754;
        background: rgba(25, 135, 84, 0.1);
    }

    .btn-view:hover {
        background: rgba(25, 135, 84, 0.2);
        color: #198754;
    }

    .btn-edit {
        color: #0d6efd;
        background: rgba(13, 110, 253, 0.1);
    }

    .btn-edit:hover {
        background: rgba(13, 110, 253, 0.2);
        color: #0d6efd;
    }

    .btn-delete {
        color: #dc3545;
        background: rgba(220, 53, 69, 0.1);
    }

    .btn-delete:hover {
        background: rgba(220, 53, 69, 0.2);
        color: #dc3545;
    }

    /* ── Empty State ── */
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
    }

    .empty-state-icon {
        font-size: 5rem;
        color: var(--bs-secondary-color);
        opacity: 0.3;
        margin-bottom: 1.5rem;
    }

    .empty-state h5 {
        color: var(--bs-body-color);
        font-weight: 700;
        font-size: 1.25rem;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: var(--bs-secondary-color);
        margin-bottom: 1.75rem;
    }

    /* ── Pagination ── */
    .pagination-wrapper {
        padding: 1rem 1.5rem;
        border-top: 1px solid var(--bs-border-color);
        background: var(--bs-body-bg);
    }

    .page-link {
        border-radius: 10px;
        margin: 0 3px;
        color: var(--bs-body-color);
        border-color: var(--bs-border-color);
        font-weight: 500;
    }

    .page-link:hover {
        background: var(--bs-tertiary-bg);
        border-color: #198754;
        color: #198754;
    }

    .page-item.active .page-link {
        background: linear-gradient(135deg, #198754 0%, #20c997 100%);
        border-color: #198754;
    }

    /* ── Alerts ── */
    .alert-success {
        background: rgba(25, 135, 84, 0.1);
        border: 1px solid rgba(25, 135, 84, 0.2);
        color: #198754;
        border-radius: 12px;
    }

    .alert-danger {
        background: rgba(220, 53, 69, 0.1);
        border: 1px solid rgba(220, 53, 69, 0.2);
        color: #dc3545;
        border-radius: 12px;
    }

    /* ── Animation ── */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fadeInUp 0.4s ease-out both;
    }

    .animate-fade-in:nth-child(1) { animation-delay: 0.05s; }
    .animate-fade-in:nth-child(2) { animation-delay: 0.1s; }
    .animate-fade-in:nth-child(3) { animation-delay: 0.15s; }
    .animate-fade-in:nth-child(4) { animation-delay: 0.2s; }
    .animate-fade-in:nth-child(5) { animation-delay: 0.25s; }
    .animate-fade-in:nth-child(6) { animation-delay: 0.3s; }

    /* ── Badge Count ── */
    .badge-count {
        background: rgba(25, 135, 84, 0.1);
        color: #198754;
        padding: 0.25rem 0.65rem;
        border-radius: 20px;
        font-size: 0.8125rem;
        font-weight: 600;
    }

    /* ── Modal Styles ── */
    .modal-content {
        border: none;
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
    }

    .modal-header {
        border-bottom: 1px solid var(--bs-border-color);
        padding: 1.25rem 1.5rem;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        border-top: 1px solid var(--bs-border-color);
        padding: 1rem 1.5rem;
    }

    /* ── Responsive ── */
    @media (max-width: 768px) {
        .page-header {
            padding: 1.25rem 1.5rem;
        }

        .page-header h3 {
            font-size: 1.3rem;
        }

        .stat-card {
            padding: 1.25rem;
        }

        .stat-number {
            font-size: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">

    {{-- ═══════════════════════════════════════════════════════
         HEADER - Titre et Fil d'Ariane
    ═══════════════════════════════════════════════════════ --}}
    <div class="page-header animate-fade-in">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h3>
                    <i class="bi bi-geo-alt-fill"></i>
                    Gestion des Sites
                </h3>
            </div>
            <div class="col-md-6">
                <ol class="breadcrumb float-md-end">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.entreprise.index') }}">
                            <i class="bi bi-house-door"></i> Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item active">Sites</li>
                </ol>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         ALERTES SESSION
    ═══════════════════════════════════════════════════════ --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show animate-fade-in" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show animate-fade-in" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════
         STATISTIQUES
    ═══════════════════════════════════════════════════════ --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card animate-fade-in">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon primary">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                    <div>
                        <div class="stat-number">{{ $stats['total'] }}</div>
                        <div class="stat-label">Total Sites</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card animate-fade-in">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon success">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div>
                        <div class="stat-number">{{ $stats['actifs'] }}</div>
                        <div class="stat-label">Actifs</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card animate-fade-in">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon secondary">
                        <i class="bi bi-pause-circle"></i>
                    </div>
                    <div>
                        <div class="stat-number">{{ $stats['inactifs'] }}</div>
                        <div class="stat-label">Inactifs</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card animate-fade-in">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon danger">
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

    {{-- ═══════════════════════════════════════════════════════
         FILTRES
    ═══════════════════════════════════════════════════════ --}}
    <div class="card filter-card mb-4 animate-fade-in">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                {{-- Recherche --}}
                <div class="col-12 col-md-3">
                    <label class="form-label">
                        <i class="bi bi-search me-1 text-success"></i>
                        Rechercher
                    </label>
                    <input type="text" name="search" class="form-control"
                        placeholder="Nom, code, adresse…"
                        value="{{ request('search') }}">
                </div>

                {{-- Client --}}
                    <div class="col-12 col-md-3">
                        <label class="form-label">
                            <i class="bi bi-building me-1 text-success"></i>
                            Client
                        </label>
                        <select name="client_id" class="form-select">
                            <option value="">Tous les clients</option>
                            @foreach($clients as $client)
                            <option value="{{ $client->id }}"
                                {{ request('client_id') == $client->id ? 'selected' : '' }}>
                                {{ $client->nom }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                {{-- Niveau risque --}}
                <div class="col-6 col-md-2">
                    <label class="form-label">
                        <i class="bi bi-shield-exclamation me-1 text-success"></i>
                        Risque
                    </label>
                    <select name="niveau_risque" class="form-select">
                        <option value="">Tous</option>
                        <option value="faible" {{ request('niveau_risque') == 'faible' ? 'selected' : '' }}>Faible</option>
                        <option value="moyen" {{ request('niveau_risque') == 'moyen' ? 'selected' : '' }}>Moyen</option>
                        <option value="haut" {{ request('niveau_risque') == 'haut' ? 'selected' : '' }}>Haut</option>
                        <option value="critique" {{ request('niveau_risque') == 'critique' ? 'selected' : '' }}>Critique</option>
                    </select>
                </div>

                {{-- Statut --}}
                <div class="col-6 col-md-2">
                    <label class="form-label">
                        <i class="bi bi-toggle-on me-1 text-success"></i>
                        Statut
                    </label>
                    <select name="est_actif" class="form-select">
                        <option value="">Tous</option>
                        <option value="1" {{ request('est_actif') === '1' ? 'selected' : '' }}>Actif</option>
                        <option value="0" {{ request('est_actif') === '0' ? 'selected' : '' }}>Inactif</option>
                    </select>
                </div>

                {{-- Actions filtre --}}
                <div class="col-12 col-md-2">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-filter flex-grow-1">
                            <i class="bi bi-funnel-fill me-1"></i>Filtrer
                        </button>
                        <a href="{{ route('admin.entreprise.sites.index') }}"
                            class="btn btn-outline-secondary"
                            title="Réinitialiser">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         TABLEAU DES SITES
    ═══════════════════════════════════════════════════════ --}}
    <div class="card table-card animate-fade-in">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>
                <i class="bi bi-list-ul text-success"></i>
                Liste des sites
                <span class="badge-count ms-2">
                    {{ $sites->total() }}
                </span>
            </h5>
            <a href="{{ route('admin.entreprise.sites.create') }}" class="btn btn-new">
                <i class="bi bi-plus-circle"></i>
                Nouveau site
            </a>
        </div>

        <div class="card-body p-0">
            @if($sites->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th><i class="bi bi-geo-alt me-1"></i>Site</th>
                            <th><i class="bi bi-building me-1"></i>Client</th>
                            <th><i class="bi bi-pin-map me-1"></i>Adresse</th>
                            <th><i class="bi bi-person me-1"></i>Contact</th>
                            <th><i class="bi bi-shield-exclamation me-1"></i>Risque</th>
                            <th><i class="bi bi-toggle-on me-1"></i>Statut</th>
                            <th class="text-end"><i class="bi bi-gear me-1"></i>Actions</th>
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
                                <span class="client-name">{{ $site->client?->nom ?? '—' }}</span>
                            </td>
                            <td>
                                <div style="font-size: 0.9375rem;">{{ $site->adresse }}</div>
                                <small class="text-muted">{{ $site->ville }}{{ $site->commune ? ', '.$site->commune : '' }}</small>
                            </td>
                            <td>
                                @if($site->contact_nom)
                                <div class="fw-medium" style="font-size: 0.9375rem;">{{ $site->contact_nom }}</div>
                                <small class="text-muted">{{ $site->contact_telephone }}</small>
                                @else
                                <span class="text-muted">—</span>
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
                                    <i class="bi bi-check-circle-fill"></i> Actif
                                </span>
                                @else
                                <span class="status-badge status-inactif">
                                    <i class="bi bi-pause-circle-fill"></i> Inactif
                                </span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.entreprise.sites.show', $site->id) }}"
                                        class="btn btn-action btn-view" title="Voir">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.entreprise.sites.edit', $site->id) }}"
                                        class="btn btn-action btn-edit" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-action btn-delete"
                                        title="Supprimer" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal{{ $site->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        {{-- Modal suppression --}}
                        <div class="modal fade" id="deleteModal{{ $site->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header border-0 pb-0">
                                        <h5 class="modal-title">
                                            <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>
                                            Confirmer la suppression
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body pt-2">
                                        <p class="mb-2">
                                            Supprimer le site <strong>{{ $site->nom_site }}</strong> ?
                                        </p>
                                        @if($site->contrats()->count() > 0 || $site->affectations()->count() > 0)
                                        <div class="alert alert-warning py-2 mb-0">
                                            <i class="bi bi-exclamation-circle me-1"></i>
                                            Ce site est associé à des contrats ou des agents.
                                        </div>
                                        @endif
                                    </div>
                                    <div class="modal-footer border-0 pt-0">
                                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                                            Annuler
                                        </button>
                                        @if($site->contrats()->count() == 0 && $site->affectations()->count() == 0)
                                        <form action="{{ route('admin.entreprise.sites.destroy', $site->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="bi bi-trash me-1"></i>Supprimer
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
                <a href="{{ route('admin.entreprise.sites.create') }}" class="btn btn-new">
                    <i class="bi bi-plus-circle me-1"></i>Créer un site
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
