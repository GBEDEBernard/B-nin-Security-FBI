@extends('layouts.app')

@section('title', 'Gestion des Clients - Entreprise')

@push('styles')
<style>
    /* ═══════════════════════════════════════════════════════════════
       ULTRA PRO DESIGN - Liste des Clients
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
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        padding: 1rem 0.75rem;
        white-space: nowrap;
    }

    .table tbody td {
        padding: 0.85rem 0.75rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--bs-border-color);
        color: var(--bs-body-color);
        font-size: 0.875rem;
    }

    .table tbody tr:last-child td {
        border-bottom: none;
    }

    .table tbody tr:hover {
        background: var(--bs-tertiary-bg);
    }

    /* ── Client Info ── */
    .client-name {
        font-weight: 600;
        font-size: 0.9375rem;
        color: var(--bs-body-color);
    }

    .client-raison {
        font-size: 0.8125rem;
        color: var(--bs-secondary-color);
        margin-top: 0.25rem;
    }

    /* ── Type Badges ── */
    .type-badge {
        padding: 0.3rem 0.7rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        display: inline-block;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .type-particulier {
        background: rgba(111, 66, 193, 0.12);
        color: #6f42c1;
    }

    .type-entreprise {
        background: rgba(13, 110, 253, 0.12);
        color: #0d6efd;
    }

    .type-institution {
        background: rgba(253, 126, 20, 0.12);
        color: #fd7e14;
    }

    /* ── Status Badges ── */
    .status-badge {
        padding: 0.3rem 0.7rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
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
        padding: 0.35rem 0.6rem;
        border-radius: 8px;
        font-size: 0.875rem;
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
    .alert-success-custom {
        background: rgba(25, 135, 84, 0.1);
        border: 1px solid rgba(25, 135, 84, 0.2);
        color: #198754;
        border-radius: 12px;
    }

    .alert-danger-custom {
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

    /* ── Badge Count ── */
    .badge-count {
        background: rgba(25, 135, 84, 0.1);
        color: #198754;
        padding: 0.25rem 0.65rem;
        border-radius: 20px;
        font-size: 0.8125rem;
        font-weight: 600;
    }

    /* ── Info Text ── */
    .info-text {
        font-size: 0.8125rem;
        color: var(--bs-secondary-color);
    }

    .info-text strong {
        color: var(--bs-body-color);
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

        .table-responsive {
            font-size: 0.8125rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">

    {{-- ═══════════════════════════════════════════════════════
         HEADER
    ═══════════════════════════════════════════════════════ --}}
    <div class="page-header animate-fade-in">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h3>
                    <i class="bi bi-people-fill"></i>
                    Gestion des Clients
                </h3>
            </div>
            <div class="col-md-6">
                <ol class="breadcrumb float-md-end">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.entreprise.index') }}">
                            <i class="bi bi-house-door"></i> Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item active">Clients</li>
                </ol>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         ALERTES
    ═══════════════════════════════════════════════════════ --}}
    @if(session('success'))
    <div class="alert alert-success-custom alert-dismissible fade show animate-fade-in" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger-custom alert-dismissible fade show animate-fade-in" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        {{ session('error') }}
        <button type="button" class="-bs-dismiss="alert"></buttonbtn-close" data>
    </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════
         STATISTIQUES
    ═══════════════════════════════════════════════════════ --}}
    @php
    $entrepriseId = null;
    if (Auth::guard('employe')->check()) {
        $entrepriseId = Auth::guard('employe')->user()->entreprise_id;
    } elseif (Auth::guard('web')->check() && session()->has('entreprise_id')) {
        $entrepriseId = session('entreprise_id');
    }

    $stats = [
        'total' => \App\Models\Client::where('entreprise_id', $entrepriseId)->count(),
        'actifs' => \App\Models\Client::where('entreprise_id', $entrepriseId)->where('est_actif', true)->count(),
        'particuliers' => \App\Models\Client::where('entreprise_id', $entrepriseId)->where('type_client', 'particulier')->count(),
        'entreprises' => \App\Models\Client::where('entreprise_id', $entrepriseId)->whereIn('type_client', ['entreprise', 'institution'])->count(),
    ];
    @endphp

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card animate-fade-in">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: rgba(25, 135, 84, 0.1); color: #198754;">
                        <i class="bi bi-people"></i>
                    </div>
                    <div>
                        <div class="stat-number">{{ $stats['total'] }}</div>
                        <div class="stat-label">Total Clients</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card animate-fade-in">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: rgba(25, 135, 84, 0.1); color: #198754;">
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
                    <div class="stat-icon" style="background: rgba(111, 66, 193, 0.1); color: #6f42c1;">
                        <i class="bi bi-person"></i>
                    </div>
                    <div>
                        <div class="stat-number">{{ $stats['particuliers'] }}</div>
                        <div class="stat-label">Particuliers</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card animate-fade-in">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: rgba(13, 110, 253, 0.1); color: #0d6efd;">
                        <i class="bi bi-building"></i>
                    </div>
                    <div>
                        <div class="stat-number">{{ $stats['entreprises'] }}</div>
                        <div class="stat-label">Entreprises</div>
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
            <form method="GET" action="{{ route('admin.entreprise.clients.index') }}" class="row g-3 align-items-end">
                <div class="col-12 col-md-3">
                    <label class="form-label">
                        <i class="bi bi-search me-1 text-success"></i>
                        Rechercher
                    </label>
                    <input type="text" name="search" class="form-control"
                        placeholder="Nom, email, téléphone, NIF, RCCM..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label">
                        <i class="bi bi-tag me-1 text-success"></i>
                        Type
                    </label>
                    <select name="type_client" class="form-select">
                        <option value="">Tous types</option>
                        <option value="particulier" {{ request('type_client') == 'particulier' ? 'selected' : '' }}>Particulier</option>
                        <option value="entreprise" {{ request('type_client') == 'entreprise' ? 'selected' : '' }}>Entreprise</option>
                        <option value="institution" {{ request('type_client') == 'institution' ? 'selected' : '' }}>Institution</option>
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label">
                        <i class="bi bi-toggle-on me-1 text-success"></i>
                        Statut
                    </label>
                    <select name="est_actif" class="form-select">
                        <option value="">Tous statuts</option>
                        <option value="1" {{ request('est_actif') == '1' ? 'selected' : '' }}>Actif</option>
                        <option value="0" {{ request('est_actif') == '0' ? 'selected' : '' }}>Inactif</option>
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-filter flex-grow-1">
                            <i class="bi bi-funnel me-1"></i> Filtrer
                        </button>
                        <a href="{{ route('admin.entreprise.clients.index') }}" class="btn btn-outline-secondary" title="Réinitialiser">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         TABLEAU DES CLIENTS
    ═══════════════════════════════════════════════════════ --}}
    <div class="card table-card animate-fade-in">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>
                <i class="bi bi-list-ul text-success"></i>
                Liste des clients
                <span class="badge-count ms-2">
                    {{ $clients->total() }}
                </span>
            </h5>
            <a href="{{ route('admin.entreprise.clients.create') }}" class="btn btn-new">
                <i class="bi bi-plus-circle"></i>
                Nouveau client
            </a>
        </div>

        <div class="card-body p-0">
            @if($clients->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th><i class="bi bi-hash me-1"></i>N°</th>
                            <th><i class="bi bi-person me-1"></i>Client</th>
                            <th><i class="bi bi-tag me-1"></i>Type</th>
                            <th><i class="bi bi-envelope me-1"></i>Email</th>
                            <th><i class="bi bi-telephone me-1"></i>Téléphone</th>
                            <th><i class="bi bi-geo-alt me-1"></i>Localisation</th>
                            <th><i class="bi bi-person-badge me-1"></i>Contact</th>
                            <th><i class="bi bi-bank me-1"></i>NIF/RCCM</th>
                            <th><i class="bi bi-toggle-on me-1"></i>Statut</th>
                            <th class="text-end"><i class="bi bi-gear me-1"></i>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clients as $client)
                        <tr>
                            <td>
                                <span class="fw-semibold"></span>
                            </td>
                            <td>
                                <div class="client-name">{{ $client->nom }}</div>
                                @if($client->prenoms)
                                <div class="client-raison">{{ $client->prenoms }}</div>
                                @endif
                                @if($client->type_client != 'particulier' && $client->raison_sociale)
                                <div class="client-raison">{{ $client->raison_sociale }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="type-badge type-{{ $client->type_client }}">
                                    @switch($client->type_client)
                                        @case('particulier') Particulier @break
                                        @case('entreprise') Entreprise @break
                                        @case('institution') Institution @break
                                        @default {{ $client->type_client }}
                                    @endswitch
                                </span>
                            </td>
                            <td>
                                @if($client->email)
                                <div class="info-text">{{ $client->email }}</div>
                                @else
                                <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($client->telephone)
                                <div class="info-text">{{ $client->telephone }}</div>
                                @if($client->telephone_secondaire)
                                <div class="info-text" style="font-size: 0.75rem;">{{ $client->telephone_secondaire }}</div>
                                @endif
                                @else
                                <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($client->adresse || $client->ville)
                                <div class="info-text">{{ $client->adresse }}</div>
                                <div class="info-text" style="font-size: 0.75rem;">
                                    {{ $client->ville }}@if($client->pays), {{ $client->pays }}@endif
                                </div>
                                @else
                                <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($client->contact_principal_nom)
                                <div class="info-text"><strong>{{ $client->contact_principal_nom }}</strong></div>
                                @if($client->contact_principal_fonction)
                                <div class="info-text" style="font-size: 0.75rem;">{{ $client->contact_principal_fonction }}</div>
                                @endif
                                @else
                                <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($client->nif || $client->rc)
                                @if($client->nif)
                                <div class="info-text">NIF: {{ $client->nif }}</div>
                                @endif
                                @if($client->rc)
                                <div class="info-text" style="font-size: 0.75rem;">RCCM: {{ $client->rc }}</div>
                                @endif
                                @else
                                <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($client->est_actif)
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
                                    <a href="{{ route('admin.entreprise.clients.show', $client->id) }}"
                                        class="btn btn-action btn-view" title="Voir">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.entreprise.clients.edit', $client->id) }}"
                                        class="btn btn-action btn-edit" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-action btn-delete"
                                        title="Supprimer" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal{{ $client->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        {{-- Modal suppression --}}
                        <div class="modal fade" id="deleteModal{{ $client->id }}" tabindex="-1">
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
                                            Supprimer le client <strong>{{ $client->nom }}</strong> ?
                                        </p>
                                        @if($client->sites->count() > 0 || $client->contrats->count() > 0)
                                        <div class="alert alert-warning py-2 mb-0">
                                            <i class="bi bi-exclamation-circle me-1"></i>
                                            Ce client est associé à des sites ou des contrats.
                                        </div>
                                        @endif
                                    </div>
                                    <div class="modal-footer border-0 pt-0">
                                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                                            Annuler
                                        </button>
                                        @if($client->sites->count() == 0 && $client->contrats->count() == 0)
                                        <form action="{{ route('admin.entreprise.clients.destroy', $client->id) }}" method="POST">
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
                    <i class="bi bi-people"></i>
                </div>
                <h5>Aucun client trouvé</h5>
                <p>Commencez par créer votre premier client</p>
                <a href="{{ route('admin.entreprise.clients.create') }}" class="btn btn-new">
                    <i class="bi bi-plus-circle me-1"></i>Créer un client
                </a>
            </div>
            @endif
        </div>

        @if($clients->hasPages())
        <div class="pagination-wrapper">
            {{ $clients->withQueryString()->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
