@extends('layouts.app')

@section('title', 'Rapports Globaux - Super Admin')

@section('content')
<div class="container-fluid p-4">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">
                <i class="bi bi-bar-chart-fill text-info me-2"></i>
                Rapports Globaux
            </h2>
            <p class="text-muted mb-0">Statistiques consolidées de toutes les entreprises</p>
        </div>
        <div>
            <button class="btn btn-outline-secondary me-2" onclick="exporterPDF()">
                <i class="bi bi-download me-1"></i> Exporter PDF
            </button>
            <button class="btn btn-outline-primary" onclick="exporterExcel()">
                <i class="bi bi-file-excel me-1"></i> Exporter Excel
            </button>
        </div>
    </div>

    <!-- Cartes de statistiques principales -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card text-center h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-building fs-4 text-primary"></i>
                    </div>
                    <h4 class="mb-1">{{ number_format($stats['total_entreprises'] ?? 0, 0, ',', ' ') }}</h4>
                    <p class="text-muted mb-0 small">Entreprises</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-check-circle fs-4 text-success"></i>
                    </div>
                    <h4 class="mb-1">{{ number_format($stats['entreprises_actives'] ?? 0, 0, ',', ' ') }}</h4>
                    <p class="text-muted mb-0 small">Actives</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="rounded-circle bg-info bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-people fs-4 text-info"></i>
                    </div>
                    <h4 class="mb-1">{{ number_format($stats['total_clients'] ?? 0, 0, ',', ' ') }}</h4>
                    <p class="text-muted mb-0 small">Clients</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="rounded-circle bg-warning bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-person-badge fs-4 text-warning"></i>
                    </div>
                    <h4 class="mb-1">{{ number_format($stats['total_employes'] ?? 0, 0, ',', ' ') }}</h4>
                    <p class="text-muted mb-0 small">Employés</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="rounded-circle bg-danger bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-receipt fs-4 text-danger"></i>
                    </div>
                    <h4 class="mb-1">{{ number_format($stats['total_factures'] ?? 0, 0, ',', ' ') }}</h4>
                    <p class="text-muted mb-0 small">Factures</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center h-100 border-0 shadow-sm border-success">
                <div class="card-body">
                    <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-currency-dollar fs-4 text-success"></i>
                    </div>
                    <h4 class="mb-1">{{ number_format($stats['chiffre_affaires'] ?? 0, 0, ',', ' ') }}</h4>
                    <p class="text-muted mb-0 small">CA Total (CFA)</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="row mb-4">
        <!-- Répartition des entreprises par statut -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0"><i class="bi bi-pie-chart me-2 text-primary"></i>Entreprises par Statut</h5>
                </div>
                <div class="card-body">
                    <canvas id="entreprisesChart"></canvas>
                </div>
            </div>
        </div>
        <!-- Répartition des employés par catégorie -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0"><i class="bi bi-pie-chart me-2 text-warning"></i>Employés par Catégorie</h5>
                </div>
                <div class="card-body">
                    <canvas id="employesChart"></canvas>
                </div>
            </div>
        </div>
        <!-- Répartition des clients par type -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0"><i class="bi bi-pie-chart me-2 text-info"></i>Clients par Type</h5>
                </div>
                <div class="card-body">
                    <canvas id="clientsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques supplémentaires -->
    <div class="row mb-4">
        <!-- Évolution du chiffre d'affaires -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0"><i class="bi bi-graph-up me-2 text-success"></i>Évolution du Chiffre d'Affaires</h5>
                </div>
                <div class="card-body">
                    <canvas id="caChart" height="100"></canvas>
                </div>
            </div>
        </div>
        <!-- Activité récente -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0"><i class="bi bi-activity me-2 text-danger"></i>Activité Récente</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-building text-primary"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">{{ $stats['total_entreprises'] ?? 0 }}</h6>
                            <small class="text-muted">Entreprises</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-success bg-opacity-10 me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-people text-success"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">{{ $stats['total_employes'] ?? 0 }}</h6>
                            <small class="text-muted">Employés</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-info bg-opacity-10 me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-person-check text-info"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">{{ $stats['total_clients'] ?? 0 }}</h6>
                            <small class="text-muted">Clients</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-warning bg-opacity-10 me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-file-text text-warning"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">{{ $stats['total_contrats'] ?? 0 }}</h6>
                            <small class="text-muted">Contrats</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-danger bg-opacity-10 me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-receipt text-danger"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">{{ $stats['total_factures'] ?? 0 }}</h6>
                            <small class="text-muted">Factures</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau récapitulatif -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-table me-2 text-dark"></i>Résumé par Entreprise</h5>
                <a href="{{ route('admin.superadmin.rapports.par-entreprise') }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-eye me-1"></i> Voir tout
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Entreprise</th>
                            <th>Employés</th>
                            <th>Clients</th>
                            <th>Contrats</th>
                            <th>Factures</th>
                            <th>CA Estimé</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($entreprises as $entreprise)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle bg-primary bg-opacity-10 text-primary me-2">
                                        {{ substr($entreprise->nom_entreprise ?? 'E', 0, 1) }}
                                    </div>
                                    <div>
                                        <strong>{{ $entreprise->nom_entreprise }}</strong>
                                        @if($entreprise->nom_commercial)
                                        <br><small class="text-muted">{{ $entreprise->nom_commercial }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary bg-opacity-10 text-primary">
                                    {{ $entreprise->employes_count ?? 0 }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-info bg-opacity-10 text-info">
                                    {{ $entreprise->clients_count ?? 0 }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-warning bg-opacity-10 text-warning">
                                    {{ $entreprise->contrats_prestation_count ?? 0 }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-danger bg-opacity-10 text-danger">
                                    {{ $entreprise->factures_count ?? 0 }}
                                </span>
                            </td>
                            <td>
                                <strong>{{ number_format($entreprise->factures->sum('montant_ttc') ?? 0, 0, ',', ' ') }}</strong>
                                <small class="text-muted">FCFA</small>
                            </td>
                            <td>
                                @if($entreprise->est_active)
                                <span class="badge bg-success">Active</span>
                                @else
                                <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Aucune entreprise trouvée
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    // Données pour les graphiques
    var entreprisesData = {
        actives: {
            {
                $stats['entreprises_actives'] ?? 0
            }
        },
        totales: {
            {
                $stats['total_entreprises'] ?? 0
            }
        }
    };

    var employesData = {
        direction: {
            {
                $statsParCategorie['direction'] ?? 0
            }
        },
        supervision: {
            {
                $statsParCategorie['supervision'] ?? 0
            }
        },
        controle: {
            {
                $statsParCategorie['controle'] ?? 0
            }
        },
        agent: {
            {
                $statsParCategorie['agent'] ?? 0
            }
        }
    };

    var clientsData = {
        particulier: {
            {
                $statsParTypeClient['particulier'] ?? 0
            }
        },
        entreprise: {
            {
                $statsParTypeClient['entreprise'] ?? 0
            }
        },
        institution: {
            {
                $statsParTypeClient['institution'] ?? 0
            }
        }
    };

    var caData = {
        labels: {
            !!json_encode($caParMois['labels'] ?? ['Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aout', 'Sep', 'Oct', 'Nov', 'Dec']) !!
        },
        data: {
            !!json_encode($caParMois['data'] ?? [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]) !!
        }
    };

    // Graphique entreprises par statut
    const entreprisesCtx = document.getElementById('entreprisesChart').getContext('2d');
    new Chart(entreprisesCtx, {
        type: 'doughnut',
        data: {
            labels: ['Actives', 'Inactives'],
            datasets: [{
                data: [entreprisesData.actives, entreprisesData.totales - entreprisesData.actives],
                backgroundColor: ['#198754', '#6c757d'],
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

    // Graphique employés par catégorie
    const employesCtx = document.getElementById('employesChart').getContext('2d');
    new Chart(employesCtx, {
        type: 'doughnut',
        data: {
            labels: ['Direction', 'Supervision', 'Contrôle', 'Agent'],
            datasets: [{
                data: [employesData.direction, employesData.supervision, employesData.controle, employesData.agent],
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

    // Graphique clients par type
    const clientsCtx = document.getElementById('clientsChart').getContext('2d');
    new Chart(clientsCtx, {
        type: 'doughnut',
        data: {
            labels: ['Particulier', 'Entreprise', 'Institution'],
            datasets: [{
                data: [clientsData.particulier, clientsData.entreprise, clientsData.institution],
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

    // Graphique évolution CA
    const caCtx = document.getElementById('caChart').getContext('2d');
    new Chart(caCtx, {
        type: 'line',
        data: {
            labels: caData.labels,
            datasets: [{
                label: 'Chiffre d\'Affaires (FCFA)',
                data: caData.data,
                borderColor: '#198754',
                backgroundColor: 'rgba(25, 135, 84, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    function exporterPDF() {
        window.location.href = '{{ route("admin.superadmin.rapports.index") }}?export=pdf';
    }

    function exporterExcel() {
        window.location.href = '{{ route("admin.superadmin.rapports.index") }}?export=excel';
    }
</script>
@endpush
@endsection