@extends('layouts.app')

@section('title', 'Propositions de Contrat - Super Admin')

@push('styles')
<style>
    :root {
        --pp-surface: #ffffff;
        --pp-border: #e9ecef;
        --pp-text: #212529;
        --pp-text-muted: #6c757d;
        --pp-bg-soft: #f8f9fa;
        --pp-shadow: 0 0.25rem 0.5rem rgba(0,0,0,0.05);
        --pp-shadow-lg: 0 0.5rem 1rem rgba(0,0,0,0.08);
    }
    :root[data-bs-theme="dark"] {
        --pp-surface: #1a1d27;
        --pp-border: #2a2d3a;
        --pp-text: #f0f2f8;
        --pp-text-muted: #8b90a8;
        --pp-bg-soft: #12141c;
        --pp-shadow: 0 0.25rem 0.5rem rgba(0,0,0,0.3);
        --pp-shadow-lg: 0 0.5rem 1rem rgba(0,0,0,0.4);
    }

    /* ===== Cards générales ===== */
    .proposition-card {
        border: none;
        border-radius: 16px;
        background: var(--pp-surface);
        box-shadow: var(--pp-shadow);
        transition: transform 0.3s ease;
        color: var(--pp-text);
    }

    .proposition-card:hover {
        transform: translateY(-3px);
    }

    /* Header / footer de card : plus jamais de bg-white codé en dur */
    .proposition-card .card-header,
    .proposition-card .card-footer {
        background: var(--pp-surface);
        color: var(--pp-text);
        border-color: var(--pp-border);
    }

    .proposition-card .card-header h5,
    .proposition-card h3,
    .proposition-card h5 {
        color: var(--pp-text);
    }

    /* ===== Badges statut ===== */
    .badge-statut {
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-weight: 500;
        font-size: 0.75rem;
    }

    /* ===== Table ===== */
    .table-responsive {
        border-radius: 12px;
        overflow: hidden;
    }

    .data-table {
        background: var(--pp-surface);
        margin-bottom: 0;
    }

    .data-table thead th {
        background: var(--pp-bg-soft);
        border-bottom: 2px solid var(--pp-border);
        font-weight: 600;
        color: var(--pp-text-muted);
        font-size: 0.8rem;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .data-table tbody td {
        background: var(--pp-surface);
        color: var(--pp-text);
        border-color: var(--pp-border);
        vertical-align: middle;
    }

    .data-table tbody tr:hover td {
        background: rgba(25, 135, 84, 0.06);
    }

    [data-bs-theme="dark"] .data-table tbody tr:hover td {
        background: rgba(25, 135, 84, 0.12);
    }

    /* ===== Textes secondaires ===== */
    .proposition-card .text-muted,
    .proposition-card small.text-muted {
        color: var(--pp-text-muted) !important;
    }

    /* ===== Formulaires (filtres) ===== */
    .proposition-card .form-control,
    .proposition-card .form-select {
        background: var(--pp-bg-soft);
        border-color: var(--pp-border);
        color: var(--pp-text);
    }

    .proposition-card .form-control::placeholder {
        color: var(--pp-text-muted);
        opacity: 1;
    }

    .proposition-card .form-control:focus,
    .proposition-card .form-select:focus {
        background: var(--pp-bg-soft);
        color: var(--pp-text);
        border-color: #198754;
        box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.15);
    }

    /* ===== Stat cards (gradients) : contraste OK sur les 2 thèmes ===== */
    .stat-card-icon-wrap {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* ===== Pagination lisible en dark ===== */
    [data-bs-theme="dark"] .pagination .page-link {
        background: var(--pp-bg-soft);
        border-color: var(--pp-border);
        color: var(--pp-text);
    }

    [data-bs-theme="dark"] .pagination .page-item.active .page-link {
        background: #198754;
        border-color: #198754;
        color: #fff;
    }

    [data-bs-theme="dark"] .pagination .page-item.disabled .page-link {
        background: transparent;
        color: var(--pp-text-muted);
    }

    /* ===== Empty state ===== */
    .empty-state i,
    .empty-state h5 {
        color: var(--pp-text-muted) !important;
    }
</style>
@endpush

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0"><i class="bi bi-file-earmark-ruled me-2"></i>Propositions de Contrat</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Propositions</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!--end::App Content Header-->

<!--begin::App Content-->
<div class="app-content">
    <div class="container-fluid">

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <!-- Statistiques -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="proposition-card card border-0 h-100" style="background: linear-gradient(135deg, #0d6efd22, #0d6efd11);">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary bg-opacity-10 rounded-3 p-3 stat-card-icon-wrap">
                                <i class="bi bi-file-earmark-ruled fs-3 text-primary"></i>
                            </div>
                            <div>
                                <h3 class="mb-0 fw-bold">{{ $stats['total'] }}</h3>
                                <p class="text-muted mb-0 small">Total</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="proposition-card card border-0 h-100" style="background: linear-gradient(135deg, #ffc10722, #ffc10711);">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-warning bg-opacity-10 rounded-3 p-3 stat-card-icon-wrap">
                                <i class="bi bi-hourglass-split fs-3 text-warning"></i>
                            </div>
                            <div>
                                <h3 class="mb-0 fw-bold text-warning">{{ $stats['en_attente'] }}</h3>
                                <p class="text-muted mb-0 small">En attente</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="proposition-card card border-0 h-100" style="background: linear-gradient(135deg, #0dcaf022, #0dcaf011);">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-info bg-opacity-10 rounded-3 p-3 stat-card-icon-wrap">
                                <i class="bi bi-pen fs-3 text-info"></i>
                            </div>
                            <div>
                                <h3 class="mb-0 fw-bold text-info">{{ $stats['en_attente_signature'] }}</h3>
                                <p class="text-muted mb-0 small">En attente signature</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="proposition-card card border-0 h-100" style="background: linear-gradient(135deg, #19875422, #19875411);">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-success bg-opacity-10 rounded-3 p-3 stat-card-icon-wrap">
                                <i class="bi bi-check2-circle fs-3 text-success"></i>
                            </div>
                            <div>
                                <h3 class="mb-0 fw-bold text-success">{{ $stats['signes'] }}</h3>
                                <p class="text-muted mb-0 small">Signés</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="card proposition-card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="statut" class="form-select">
                            <option value="">Tous les statuts</option>
                            <option value="soumis" {{ request('statut') == 'soumis' ? 'selected' : '' }}>Soumis</option>
                            <option value="en_cours" {{ request('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                            <option value="contrat_envoye" {{ request('statut') == 'contrat_envoye' ? 'selected' : '' }}>Contrat envoyé</option>
                            <option value="en_attente_signature" {{ request('statut') == 'en_attente_signature' ? 'selected' : '' }}>En attente signature</option>
                            <option value="signe" {{ request('statut') == 'signe' ? 'selected' : '' }}>Signé</option>
                            <option value="rejete" {{ request('statut') == 'rejete' ? 'selected' : '' }}>Rejeté</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-funnel me-1"></i>Filtrer
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Liste -->
        <div class="card proposition-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Liste des propositions</h5>
                <a href="{{ route('admin.superadmin.propositions.create') }}" class="btn btn-success btn-sm">
                    <i class="bi bi-plus-lg"></i> Nouvelle proposition
                </a>
            </div>
            <div class="card-body p-0">
                @if($propositions->count() > 0)
                <div class="table-responsive">
                    <table class="table data-table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Entreprise</th>
                                <th>Contact</th>
                                <th>Type service</th>
                                <th>Agents</th>
                                <th>Date soumission</th>
                                <th>Statut</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($propositions as $prop)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $prop->nom_entreprise }}</div>
                                    <small class="text-muted">{{ $prop->entreprise?->slug ?? $prop->nom_commercial }}</small>
                                </td>
                                <td>
                                    <div>{{ $prop->email }}</div>
                                    <small class="text-muted">{{ $prop->telephone }}</small>
                                </td>
                                <td>{{ $prop->type_service_label }}</td>
                                <td>{{ $prop->nombre_agents }}</td>
                                <td>{{ $prop->date_soumission?->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge badge-statut bg-{{ $prop->statut_badge_class }}">
                                        {{ $prop->statut_label }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.superadmin.propositions.show', $prop->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5 empty-state">
                    <i class="bi bi-inbox fs-1"></i>
                    <h5 class="mt-3">Aucune proposition</h5>
                </div>
                @endif
            </div>
            @if($propositions->hasPages())
            <div class="card-footer">
                {{ $propositions->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
<!--end::App Content-->
@endsection