@extends('layouts.app')

@section('title', 'Rapport Clients - Super Admin')

@section('content')
<div class="container-fluid p-4">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">
                <i class="bi bi-people text-info me-2"></i>
                Rapport des Clients
            </h2>
            <p class="text-muted mb-0">Statistiques des clients toutes entreprises confondues</p>
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
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="rounded-circle bg-info bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-people fs-4 text-info"></i>
                    </div>
                    <h3 class="mb-1">{{ $stats['total'] }}</h3>
                    <p class="text-muted mb-0">Total Clients</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
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
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-pie-chart fs-4 text-primary"></i>
                    </div>
                    <h3 class="mb-1">{{ $stats['par_type']->count() }}</h3>
                    <p class="text-muted mb-0">Types de Clients</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphique -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0"><i class="bi bi-pie-chart me-2 text-info"></i>Par Type</h5>
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

    <!-- Liste des clients -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <h5 class="mb-0"><i class="bi bi-list me-2"></i>Liste des Clients</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Type</th>
                            <th>Entreprise</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Ville</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clients as $client)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle bg-info bg-opacity-10 text-info me-2">
                                        {{ substr($client->nomAffichage ?? 'C', 0, 1) }}
                                    </div>
                                    <div>
                                        <strong>{{ $client->nomAffichage }}</strong>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $client->typeLabel }}</span>
                            </td>
                            <td>{{ $client->entreprise->nom_entreprise ?? '-' }}</td>
                            <td>{{ $client->email ?? '-' }}</td>
                            <td>{{ $client->telephone ?? '-' }}</td>
                            <td>{{ $client->ville ?? '-' }}</td>
                            <td>
                                @if($client->est_actif)
                                <span class="badge bg-success">Actif</span>
                                @else
                                <span class="badge bg-secondary">Inactif</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Aucun client trouvé
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .avatar-circle {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 14px;
    }
</style>

@php
$particulier = $stats['par_type']['particulier'] ?? 0;
$entrepriseType = $stats['par_type']['entreprise'] ?? 0;
$institution = $stats['par_type']['institution'] ?? 0;

// Préparer les données pour le graphique par entreprise
$entrepriseLabels = [];
$entrepriseData = [];
foreach($clients->groupBy('entreprise_id') as $id => $cs) {
$entrepriseLabels[] = $cs->first()->entreprise->nom_entreprise ?? 'Inconnu';
$entrepriseData[] = $cs->count();
}
@endphp

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    // Par type
    new Chart(document.getElementById('typeChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Particulier', 'Entreprise', 'Institution'],
            datasets: [{
                data: [{
                    {
                        $particulier
                    }
                }, {
                    {
                        $entrepriseType
                    }
                }, {
                    {
                        $institution
                    }
                }],
                backgroundColor: ['#17a2b8', '#dc3545', '#6610f2'],
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
    new Chart(document.getElementById('entrepriseChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: {
                !!json_encode($entrepriseLabels) !!
            },
            datasets: [{
                label: 'Clients',
                data: {
                    !!json_encode($entrepriseData) !!
                },
                backgroundColor: '#0d6efd',
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