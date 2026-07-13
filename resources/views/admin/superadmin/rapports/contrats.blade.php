@extends('layouts.app')

@section('title', 'Rapport Contrats - Super Admin')

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
            <h2 class="mb-0 fw-bold"><i class="bi bi-file-earmark-text me-2 text-success"></i>Rapport Contrats</h2>
            <p class="text-muted mb-0">Suivi des contrats de prestation</p>
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
                    <div class="text-primary fs-1 mb-2"><i class="bi bi-file-earmark-text"></i></div>
                    <h3 class="fw-bold mb-0">{{ $stats['total'] }}</h3>
                    <small class="text-muted">Total contrats</small>
                </div>
            </div>
        </div>
        <div class="col-md-4 stat-card">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-success fs-1 mb-2"><i class="bi bi-check-circle"></i></div>
                    <h3 class="fw-bold mb-0">{{ $stats['actifs'] }}</h3>
                    <small class="text-muted">En cours</small>
                </div>
            </div>
        </div>
        <div class="col-md-4 stat-card">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-danger fs-1 mb-2"><i class="bi bi-clock-history"></i></div>
                    <h3 class="fw-bold mb-0">{{ $stats['expires'] }}</h3>
                    <small class="text-muted">Expirés</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-bottom">
            <h5 class="mb-0 fw-semibold"><i class="bi bi-list-ul me-2"></i>Liste des contrats</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr><th>N°</th><th>Client</th><th>Entreprise</th><th>Type</th><th>Début</th><th>Fin</th><th>Montant</th><th>Statut</th></tr>
                    </thead>
                    <tbody>
                        @forelse($contrats as $c)
                        <tr>
                            <td class="fw-semibold">{{ $c->numero_contrat ?? 'N/A' }}</td>
                            <td>{{ $c->client?->nom ?? $c->client?->prenom ?? '-' }}</td>
                            <td>{{ $c->entreprise?->nom_entreprise ?? '-' }}</td>
                            <td><span class="badge bg-info">{{ $c->type_contrat ?? '-' }}</span></td>
                            <td>{{ $c->date_debut?->format('d/m/Y') ?? '-' }}</td>
                            <td>{{ $c->date_fin?->format('d/m/Y') ?? '-' }}</td>
                            <td class="fw-semibold">{{ number_format($c->montant_total ?? 0, 0, ',', ' ') }} CFA</td>
                            <td>
                                @php
                                $badge = ['en_cours' => 'success', 'expire' => 'danger', 'resilie' => 'secondary', 'suspendu' => 'warning'];
                                $label = ['en_cours' => 'En cours', 'expire' => 'Expiré', 'resilie' => 'Résilié', 'suspendu' => 'Suspendu'];
                                @endphp
                                <span class="badge bg-{{ $badge[$c->statut] ?? 'secondary' }}">{{ $label[$c->statut] ?? $c->statut }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">Aucun contrat</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection