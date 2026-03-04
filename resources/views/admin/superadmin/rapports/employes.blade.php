@extends('layouts.app')

@section('title', 'Rapport Employés - Super Admin')

@section('content')
<div class="container-fluid p-4">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">
                <i class="bi bi-people text-warning me-2"></i>
                Rapport des Employés
            </h2>
            <p class="text-muted mb-0">Statistiques du personnel toutes entreprises confondues</p>
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
                    <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-people fs-4 text-primary"></i>
                    </div>
                    <h3 class="mb-1">{{ $stats['total'] }}</h3>
                    <p class="text-muted mb-0">Total Employés</p>
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
                    <div class="rounded-circle bg-info bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-person-check fs-4 text-info"></i>
                    </div>
                    <h3 class="mb-1">{{ $stats['en_poste'] }}</h3>
                    <p class="text-muted mb-0">En Poste</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="rounded-circle bg-warning bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-calendar-check fs-4 text-warning"></i>
                    </div>
                    <h3 class="mb-1">{{ $stats['en_conge'] }}</h3>
                    <p class="text-muted mb-0">En Congé</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Répartition par catégorie -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0"><i class="bi bi-pie-chart me-2 text-primary"></i>Par Catégorie</h5>
                </div>
                <div class="card-body">
                    <canvas id="categorieChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0"><i class="bi bi-building me-2 text-info"></i>Par Entreprise</h5>
                </div>
                <div class="card-body">
                    <canvas id="entrepriseChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des employés -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <h5 class="mb-0"><i class="bi bi-list me-2"></i>Liste des Employés</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Matricule</th>
                            <th>Employé</th>
                            <th>Entreprise</th>
                            <th>Catégorie</th>
                            <th>Poste</th>
                            <th>Statut</th>
                            <th>Téléphone</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employes as $employe)
                        <tr>
                            <td><strong>{{ $employe->matricule ?? '-' }}</strong></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle bg-warning bg-opacity-10 text-warning me-2">
                                        {{ substr($employe->prenoms ?? 'E', 0, 1) }}{{ substr($employe->nom ?? 'E', 0, 1) }}
                                    </div>
                                    <div>
                                        <strong>{{ $employe->nomComplet }}</strong>
                                        <br><small class="text-muted">{{ $employe->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $employe->entreprise->nom_entreprise ?? '-' }}</td>
                            <td>
                                <span class="badge bg-primary">{{ $employe->getLibelleCategorie() }}</span>
                            </td>
                            <td>{{ $employe->poste ?? '-' }}</td>
                            <td>
                                @switch($employe->statut)
                                @case('en_poste')
                                <span class="badge bg-success">En poste</span>
                                @break
                                @case('conge')
                                <span class="badge bg-warning">En congé</span>
                                @break
                                @case('suspendu')
                                <span class="badge bg-danger">Suspendu</span>
                                @break
                                @default
                                <span class="badge bg-secondary">{{ $employe->statut }}</span>
                                @endswitch
                            </td>
                            <td>{{ $employe->telephone ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Aucun employé trouvé
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
        font-size: 12px;
    }
</style>

@php
// Données pour les graphiques
$direction = $stats['par_categorie']['direction'] ?? 0;
$supervision = $stats['par_categorie']['supervision'] ?? 0;
$controle = $stats['par_categorie']['controle'] ?? 0;
$agent = $stats['par_categorie']['agent'] ?? 0;

// Préparer les données pour le graphique par entreprise
$empLabels = [];
$empData = [];
foreach($employes->groupBy('entreprise_id') as $id => $es) {
$empLabels[] = $es->first()->entreprise->nom_entreprise ?? 'Inconnu';
$empData[] = $es->count();
}
@endphp

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    // Par catégorie
    new Chart(document.getElementById('categorieChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Direction', 'Supervision', 'Contrôle', 'Agent'],
            datasets: [{
                data: [{
                    {
                        $direction
                    }
                }, {
                    {
                        $supervision
                    }
                }, {
                    {
                        $controle
                    }
                }, {
                    {
                        $agent
                    }
                }],
                backgroundColor: ['#0d6efd', '#ffc107', '#6f42c1', '#20c997'],
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
                !!json_encode($empLabels) !!
            },
            datasets: [{
                label: 'Employés',
                data: {
                    !!json_encode($empData) !!
                },
                backgroundColor: '#17a2b8',
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