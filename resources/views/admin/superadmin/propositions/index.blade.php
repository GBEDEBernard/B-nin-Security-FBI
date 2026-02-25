@extends('layouts.app')

@section('title', 'Propositions de Contrat - Super Admin')

@push('styles')
<style>
    .proposition-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease;
    }

    .proposition-card:hover {
        transform: translateY(-3px);
    }

    .badge-statut {
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-weight: 500;
        font-size: 0.75rem;
    }

    .table-responsive {
        border-radius: 12px;
        overflow: hidden;
    }

    .data-table thead th {
        background: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
        font-weight: 600;
        color: #495057;
        font-size: 0.8rem;
        text-transform: uppercase;
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
                <div class="proposition-card card bg-primary bg-opacity-10 h-100">
                    <div class="card-body">
                        <h5 class="mb-1">{{ \App\Models\PropositionContrat::count() }}</h5>
                        <p class="text-muted mb-0 small">Total</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="proposition-card card bg-warning bg-opacity-10 h-100">
                    <div class="card-body">
                        <h5 class="mb-1 text-warning">{{ \App\Models\PropositionContrat::whereIn('statut', ['soumis', 'en_cours', 'contrat_envoye'])->count() }}</h5>
                        <p class="text-muted mb-0 small">En attente</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="proposition-card card bg-info bg-opacity-10 h-100">
                    <div class="card-body">
                        <h5 class="mb-1 text-info">{{ \App\Models\PropositionContrat::where('statut', 'en_attente_signature')->count() }}</h5>
                        <p class="text-muted mb-0 small">En attente signature</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="proposition-card card bg-success bg-opacity-10 h-100">
                    <div class="card-body">
                        <h5 class="mb-1 text-success">{{ \App\Models\PropositionContrat::where('statut', 'signe')->count() }}</h5>
                        <p class="text-muted mb-0 small">Signés</p>
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
                        <button type="submit" class="btn btn-success w-100">Filtrer</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Liste -->
        <div class="card proposition-card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Liste des propositions</h5>
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
                                    <small class="text-muted">{{ $prop->nom_commercial }}</small>
                                </td>
                                <td>
                                    <div>{{ $prop->email }}</div>
                                    <small class="text-muted">{{ $prop->telephone }}</small>
                                </td>
                                <td>{{ $prop->type_service_label }}</td>
                                <td>{{ $prop->nombre_agents }}</td>
                                <td>{{ $prop->date_soumission?->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ $prop->statut_badge_class }}">
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
                <div class="text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted"></i>
                    <h5 class="mt-3 text-muted">Aucune proposition</h5>
                </div>
                @endif
            </div>
            @if($propositions->hasPages())
            <div class="card-footer bg-white">
                {{ $propositions->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
<!--end::App Content-->
@endsection