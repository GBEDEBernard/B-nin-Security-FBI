@extends('layouts.app')

@section('title', 'Rapport Clients - Super Admin')

@push('styles')
<style>
    .stat-card { animation: fadeIn 0.5s ease forwards; opacity: 0; }
    .stat-card:nth-child(1) { animation-delay: 0.05s; }
    .stat-card:nth-child(2) { animation-delay: 0.1s; }
    .stat-card:nth-child(3) { animation-delay: 0.15s; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endpush

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0 fw-bold"><i class="bi bi-people me-2 text-info"></i>Rapport Clients</h2>
            <p class="text-muted mb-0">Base clients consolidée</p>
        </div>
        <div class="d-flex gap-2">
            <form method="GET">
                <select name="entreprise_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Toutes les entreprises</option>
                    @foreach($entreprises as $e)
                    <option value="{{ $e->id }}" {{ request('entreprise_id') == $e->id ? 'selected' : '' }}>{{ $e->nom_entreprise }}</option>
                    @endforeach
                </select>
            </form>
            <a href="{{ route('admin.superadmin.rapports.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i></a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4 stat-card">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-primary fs-1 mb-2"><i class="bi bi-people"></i></div>
                    <h3 class="fw-bold mb-0">{{ $stats['total'] }}</h3>
                    <small class="text-muted">Total clients</small>
                </div>
            </div>
        </div>
        <div class="col-md-4 stat-card">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-success fs-1 mb-2"><i class="bi bi-check-circle"></i></div>
                    <h3 class="fw-bold mb-0">{{ $stats['actifs'] }}</h3>
                    <small class="text-muted">Actifs</small>
                </div>
            </div>
        </div>
        <div class="col-md-4 stat-card">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-info fs-1 mb-2"><i class="bi bi-tags"></i></div>
                    <h3 class="fw-bold mb-0">{{ $stats['par_type']->count() }}</h3>
                    <small class="text-muted">Types</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-bottom">
            <h5 class="mb-0 fw-semibold"><i class="bi bi-list-ul me-2"></i>Liste des clients</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr><th>Nom</th><th>Email</th><th>Téléphone</th><th>Entreprise</th><th>Type</th><th>Statut</th></tr>
                    </thead>
                    <tbody>
                        @forelse($clients as $c)
                        <tr>
                            <td class="fw-semibold">{{ $c->nom }} {{ $c->prenom }}</td>
                            <td>{{ $c->email }}</td>
                            <td>{{ $c->telephone ?? '-' }}</td>
                            <td>{{ $c->entreprise?->nom_entreprise ?? '-' }}</td>
                            <td><span class="badge bg-secondary">{{ $c->type_client ?? '-' }}</span></td>
                            <td>
                                <span class="badge bg-{{ $c->est_actif ? 'success' : 'danger' }}">
                                    {{ $c->est_actif ? 'Actif' : 'Inactif' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Aucun client</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection