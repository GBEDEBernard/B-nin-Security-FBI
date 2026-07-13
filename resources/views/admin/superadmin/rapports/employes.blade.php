@extends('layouts.app')

@section('title', 'Rapport Employés - Super Admin')

@push('styles')
<style>
    .stat-card { animation: fadeIn 0.5s ease forwards; opacity: 0; }
    .stat-card:nth-child(1) { animation-delay: 0.05s; }
    .stat-card:nth-child(2) { animation-delay: 0.1s; }
    .stat-card:nth-child(3) { animation-delay: 0.15s; }
    .stat-card:nth-child(4) { animation-delay: 0.2s; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endpush

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0 fw-bold"><i class="bi bi-person-badge me-2 text-warning"></i>Rapport Employés</h2>
            <p class="text-muted mb-0">Effectif et répartition</p>
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
        <div class="col-md-3 stat-card">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-primary fs-1 mb-2"><i class="bi bi-people"></i></div>
                    <h3 class="fw-bold mb-0">{{ $stats['total'] }}</h3>
                    <small class="text-muted">Total</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 stat-card">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-success fs-1 mb-2"><i class="bi bi-check-circle"></i></div>
                    <h3 class="fw-bold mb-0">{{ $stats['actifs'] }}</h3>
                    <small class="text-muted">Actifs</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 stat-card">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-info fs-1 mb-2"><i class="bi bi-play-circle"></i></div>
                    <h3 class="fw-bold mb-0">{{ $stats['en_poste'] }}</h3>
                    <small class="text-muted">En poste</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 stat-card">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-warning fs-1 mb-2"><i class="bi bi-pause-circle"></i></div>
                    <h3 class="fw-bold mb-0">{{ $stats['en_conge'] }}</h3>
                    <small class="text-muted">En congé</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-bottom">
            <h5 class="mb-0 fw-semibold"><i class="bi bi-list-ul me-2"></i>Liste des employés</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr><th>Nom</th><th>Email</th><th>Entreprise</th><th>Catégorie</th><th>Statut</th></tr>
                    </thead>
                    <tbody>
                        @forelse($employes as $emp)
                        <tr>
                            <td class="fw-semibold">{{ $emp->nom }} {{ $emp->prenom }}</td>
                            <td>{{ $emp->email }}</td>
                            <td>{{ $emp->entreprise?->nom_entreprise ?? '-' }}</td>
                            <td>{{ $emp->categorie ?? '-' }}</td>
                            <td>
                                @php
                                $badge = ['en_poste' => 'success', 'conge' => 'warning', 'suspendu' => 'danger', 'quitte' => 'secondary'];
                                $label = ['en_poste' => 'En poste', 'conge' => 'Congé', 'suspendu' => 'Suspendu', 'quitte' => 'Quitté'];
                                @endphp
                                <span class="badge bg-{{ $badge[$emp->statut] ?? 'secondary' }}">{{ $label[$emp->statut] ?? $emp->statut }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Aucun employé</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection