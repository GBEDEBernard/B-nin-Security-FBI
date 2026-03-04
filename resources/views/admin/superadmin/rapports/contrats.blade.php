@extends('layouts.app')

@section('title', 'Rapport Contrats - Super Admin')

@section('content')
<div class="container-fluid p-4">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">
                <i class="bi bi-file-text text-warning me-2"></i>
                Rapport des Contrats
            </h2>
            <p class="text-muted mb-0">Statistiques des contrats toutes entreprises confondues</p>
        </div>
        <div>
            <a href="{{ route('admin.superadmin.rapports.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Retour
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Entreprise</label>
                    <select name="entreprise_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Toutes les entreprises</option>
                        @foreach($entreprises as $entreprise)
                        <option value="{{ $entreprise->id }}" {{ request('entreprise_id') == $entreprise->id ? 'selected' : '' }}>
                            {{ $entreprise->nom_entreprise }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="rounded-circle bg-warning bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-file-text fs-4 text-warning"></i>
                    </div>
                    <h3 class="mb-1">{{ $stats['total'] }}</h3>
                    <p class="text-muted mb-0">Total Contrats</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-check-circle fs-4 text-success"></i>
                    </div>
                    <h3 class="mb-1">{{ $stats['actifs'] }}</h3>
                    <p class="text-muted mb-0">Actifs</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="rounded-circle bg-danger bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-exclamation-triangle fs-4 text-danger"></i>
                    </div>
                    <h3 class="mb-1">{{ $stats['expires'] }}</h3>
                    <p class="text-muted mb-0">Expirés</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="rounded-circle bg-info bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-pie-chart fs-4 text-info"></i>
                    </div>
                    <h3 class="mb-1">{{ $stats['par_type']->count() }}</h3>
                    <p class="text-muted mb-0">Types de Contrats</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphique -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0"><i class="bi bi-pie-chart me-2 text-warning"></i>Par Type</h5>
                </div>
                <div class="card-body">
                    <canvas id="typeChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0"><i class="bi bi-building me-2 text-primary"></i>Par Entreprise</h5>
                </div>
                <div class="card-body">
                    <canvas id="entrepriseChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des contrats -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <h5 class="mb-0"><i class="bi bi-list me-2"></i>Liste des Contrats</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>N° Contrat</th>
                            <th>Entreprise</th>
                            <th>Client</th>
                            <th>Type</th>
                            <th>Date Début</th>
                            <th>Date Fin</th>
                            <th>Montant</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($contrats as $contrat)
                        <tr>
                            <td><strong>{{ $contrat->numero_contrat ?? $contrat->reference ?? '-' }}</strong></td>
                            <td>{{ $contrat->entreprise->nom_entreprise ?? '-' }}</td>
                            <td>{{ $contrat->client->nomAffichage ?? '-' }}</td>
                            <td>{{ $contrat->type_contrat ?? '-' }}</td>
                            <td>{{ $contrat->date_debut?->format('d/m/Y') ?? '-' }}</td>
                            <td>{{ $contrat->date_fin?->format('d/m/Y') ?? '-' }}</td>
                            <td>{{ number_format($contrat->montant_total ?? 0, 0, ',', ' ') }} CFA</td>
                            <td>
                                @switch($contrat->statut)
                                @case('en_cours')
                                <span class="badge bg-success">En cours</span>
                                @break
                                @case('expire')
                                <span class="badge bg-danger">Expiré</span>
                                @break
                                @case('resilie')
                                <span class="badge bg-secondary">Résilié</span>
                                @break
                                @case('suspendu')
                                <span class="badge bg-warning">Suspendu</span>
                                @break
                                @default
                                <span class="badge bg-info">{{ $contrat->statut }}</span>
                                @endswitch
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Aucun contrat trouvé
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    // Par type
    const typeLabels = @json($stats['par_type'] -> keys());
    const typeData = @json($stats['par_type'] -> values());

    new Chart(document.getElementById('typeChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: typeLabels,
            datasets: [{
                data: typeData,
                backgroundColor: ['#ffc107', '#0d6efd', '#198754', '#dc3545', '#6f42c1'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Par entreprise
    const entreprisesData = @json($contrats -> groupBy('entreprise_id') -> map(fn($c, $id) => [
        $c -> first() -> entreprise -> nom_entreprise ?? 'Inconnu',
        $c -> count()
    ]));
    new Chart(document.getElementById('entrepriseChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: entreprisesData.map(e => e[0]),
            datasets: [{
                label: 'Contrats',
                data: entreprisesData.map(e => e[1]),
                backgroundColor: '#ffc107',
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
</script>
@endpush
@endsection