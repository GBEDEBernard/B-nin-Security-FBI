@extends('layouts.app')

@section('title', 'Dashboard Super Admin - Benin Security')

@push('styles')
<style>
    /* Custom Dashboard Styles - Ultra Pro Design */
    .stat-card {
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    }

    .stat-card .stat-icon {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 24px;
    }

    .stat-card .stat-number {
        font-size: 2rem;
        font-weight: 700;
        line-height: 1;
    }

    /* Gradient backgrounds */
    .bg-gradient-success {
        background: linear-gradient(135deg, #198754 0%, #20c997 100%);
    }

    .bg-gradient-primary {
        background: linear-gradient(135deg, #0d6efd 0%, #6ea8fe 100%);
    }

    .bg-gradient-warning {
        background: linear-gradient(135deg, #ffc107 0%, #ffcd39 100%);
    }

    .bg-gradient-danger {
        background: linear-gradient(135deg, #dc3545 0%, #e35d6a 100%);
    }

    .bg-gradient-info {
        background: linear-gradient(135deg, #0dcaf0 0%, #6edff6 100%);
    }

    .bg-gradient-purple {
        background: linear-gradient(135deg, #6f42c1 0%, #9d7df3 100%);
    }

    /* Animations */
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

    .animate-fade-in-up {
        animation: fadeInUp 0.5s ease forwards;
    }

    .stat-card:nth-child(1) {
        animation-delay: 0.1s;
    }

    .stat-card:nth-child(2) {
        animation-delay: 0.2s;
    }

    .stat-card:nth-child(3) {
        animation-delay: 0.3s;
    }

    .stat-card:nth-child(4) {
        animation-delay: 0.4s;
    }

    .stat-card:nth-child(5) {
        animation-delay: 0.5s;
    }

    .stat-card:nth-child(6) {
        animation-delay: 0.6s;
    }

    /* Cards */
    .dashboard-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }

    .dashboard-card:hover {
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }

    .dashboard-card .card-header {
        background: transparent;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1rem 1.5rem;
        font-weight: 600;
    }

    /* Timeline */
    .activity-timeline {
        position: relative;
    }

    .activity-timeline::before {
        content: '';
        position: absolute;
        left: 20px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, #198754, #e9ecef);
    }

    .activity-item {
        position: relative;
        padding-left: 50px;
        padding-bottom: 1.5rem;
    }

    .activity-item::before {
        content: '';
        position: absolute;
        left: 12px;
        top: 4px;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #198754;
        border: 3px solid white;
        box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.2);
    }

    .activity-item:last-child {
        padding-bottom: 0;
    }

    /* Quick Actions */
    .quick-action-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 1.5rem 1rem;
        border-radius: 12px;
        border: 2px solid transparent;
        background: #f8f9fa;
        transition: all 0.3s ease;
        text-decoration: none;
        color: #495057;
    }

    .quick-action-btn:hover {
        border-color: #198754;
        background: rgba(25, 135, 84, 0.05);
        color: #198754;
        transform: translateY(-3px);
    }

    .quick-action-btn i {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    /* Table */
    .table-card .table {
        margin-bottom: 0;
    }

    .table-card .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        color: #6c757d;
        border-bottom-width: 2px;
    }

    .table-card .table td {
        vertical-align: middle;
    }

    .table-card .table tr:hover {
        background: rgba(25, 135, 84, 0.03);
    }

    /* Status badges */
    .status-badge {
        padding: 0.35rem 0.75rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-active {
        background: rgba(25, 135, 84, 0.1);
        color: #198754;
    }

    .status-inactive {
        background: rgba(220, 53, 69, 0.1);
        color: #dc3545;
    }

    /* Welcome banner */
    .welcome-banner {
        background: linear-gradient(135deg, #6f42c1 0%, #9d7df3 100%);
        border-radius: 16px;
        padding: 2rem;
        color: white;
    }

    .welcome-banner h4 {
        font-size: 1.5rem;
        font-weight: 700;
    }

    /* Stat number */
    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: #212529;
    }

    /* Avatar */
    .avatar-sm {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-weight: 600;
        font-size: 0.75rem;
    }

    /* Chart container */
    .chart-container {
        position: relative;
        min-height: 300px;
    }
</style>
@endpush

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0"><i class="bi bi-shield-lock me-2 text-purple"></i>Dashboard Super Administrateur</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            {{-- Welcome Banner --}}
            <div class="row mb-4">
                <div class="col-12">
                    <div class="welcome-banner">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="mb-1">Bienvenue, {{ Auth::user()->name }}! 👋</h4>
                                <p class="mb-0 opacity-75">Vue d'ensemble de toutes les entreprises de sécurité</p>
                            </div>
                            <div class="d-none d-md-block"><i class="bi bi-shield-lock" style="font-size: 4rem; opacity: 0.3;"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Statistics Cards --}}
            <div class="row mb-4">
                <div class="col-lg-3 col-6">
                    <div class="stat-card dashboard-card p-4 animate-fade-in-up" style="opacity: 0;">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="stat-icon bg-gradient-primary text-white"><i class="bi bi-building"></i></div>
                        </div>
                        <div class="stat-number mb-1">{{ \App\Models\Entreprise::count() }}</div>
                        <div class="text-muted small">Entreprises</div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="stat-card dashboard-card p-4 animate-fade-in-up" style="opacity: 0;">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="stat-icon bg-gradient-success text-white"><i class="bi bi-people"></i></div>
                        </div>
                        <div class="stat-number mb-1">{{ \App\Models\User::where('is_superadmin', false)->count() }}</div>
                        <div class="text-muted small">Utilisateurs</div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="stat-card dashboard-card p-4 animate-fade-in-up" style="opacity: 0;">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="stat-icon bg-gradient-warning text-dark"><i class="bi bi-person-vcard"></i></div>
                        </div>
                        <div class="stat-number mb-1">{{ \App\Models\Client::count() }}</div>
                        <div class="text-muted small">Clients</div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="stat-card dashboard-card p-4 animate-fade-in-up" style="opacity: 0;">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="stat-icon bg-gradient-danger text-white"><i class="bi bi-file-earmark-check"></i></div>
                        </div>
                        <div class="stat-number mb-1">{{ \App\Models\ContratPrestation::where('statut', 'actif')->count() }}</div>
                        <div class="text-muted small">Contrats Actifs</div>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="row mb-4">
                <div class="col-12">
                    <div class="dashboard-card p-4">
                        <h6 class="fw-bold mb-3"><i class="bi bi-lightning me-2 text-warning"></i>Actions Rapides</h6>
                        <div class="row g-3">
                            <div class="col-6 col-md-3">
                                <a href="{{ route('dashboard.superadmin.entreprises.create') }}" class="quick-action-btn">
                                    <i class="bi bi-building-add text-primary"></i><span>Nouvelle Entreprise</span>
                                </a>
                            </div>
                            <div class="col-6 col-md-3">
                                <a href="{{ route('dashboard.superadmin.utilisateurs.create') }}" class="quick-action-btn">
                                    <i class="bi bi-person-plus text-success"></i><span>Nouvel Utilisateur</span>
                                </a>
                            </div>
                            <div class="col-6 col-md-3">
                                <a href="{{ route('dashboard.superadmin.parametres.index') }}" class="quick-action-btn">
                                    <i class="bi bi-gear text-warning"></i><span>Paramètres</span>
                                </a>
                            </div>
                            <div class="col-6 col-md-3">
                                <a href="#" class="quick-action-btn"><i class="bi bi-bar-chart text-info"></i><span>Rapports</span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Charts Row --}}
            <div class="row mb-4">
                <div class="col-lg-8">
                    <div class="dashboard-card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <span><i class="bi bi-graph-up-arrow me-2 text-success"></i>Évolution des Contrats</span>
                        </div>
                        <div class="card-body">
                            <div id="contracts-chart" class="chart-container"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="dashboard-card h-100">
                        <div class="card-header"><i class="bi bi-pie-chart me-2 text-primary"></i>Répartition</div>
                        <div class="card-body">
                            <div id="distribution-chart" class="chart-container"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table & Stats Row --}}
            <div class="row mb-4">
                <div class="col-lg-8">
                    <div class="dashboard-card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <span><i class="bi bi-building me-2 text-purple"></i>Entreprises de Sécurité</span>
                            <a href="{{ route('dashboard.superadmin.entreprises.index') }}" class="btn btn-sm btn-outline-purple" style="color: #6f42c1; border-color: #6f42c1;">Voir tout</a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Nom</th>
                                            <th>Email</th>
                                            <th>Téléphone</th>
                                            <th>Statut</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse(\App\Models\Entreprise::latest()->take(8)->get() as $entreprise)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm me-2 bg-purple-subtle rounded-circle" style="background: rgba(111, 66, 193, 0.1);">{{ substr($entreprise->nom, 0, 2) }}</div>{{ $entreprise->nom }}
                                                </div>
                                            </td>
                                            <td>{{ $entreprise->email }}</td>
                                            <td>{{ $entreprise->telephone }}</td>
                                            <td>@if($entreprise->est_active)<span class="status-badge status-active">Actif</span>@else<span class="status-badge status-inactive">Inactif</span>@endif</td>
                                            <td>
                                                <a href="{{ route('dashboard.superadmin.entreprises.show', $entreprise->id) }}" class="btn btn-sm btn-primary"><i class="bi bi-eye"></i></a>
                                                <a href="{{ route('dashboard.superadmin.entreprises.edit', $entreprise->id) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">Aucune entreprise trouvée</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="dashboard-card">
                        <div class="card-header"><i class="bi bi-bar-chart me-2 text-info"></i>Statistiques Globales</div>
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <div class="stat-icon bg-gradient-success text-white mx-auto mb-2" style="width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center;"><i class="bi bi-person-badge"></i></div>
                                <div class="fs-2 fw-bold">{{ \App\Models\Employe::count() }}</div>
                                <div class="text-muted small">Employés</div>
                            </div>
                            <div class="text-center mb-4">
                                <div class="stat-icon bg-gradient-warning text-dark mx-auto mb-2" style="width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center;"><i class="bi bi-file-earmark-text"></i></div>
                                <div class="fs-2 fw-bold">{{ \App\Models\ContratPrestation::count() }}</div>
                                <div class="text-muted small">Contrats</div>
                            </div>
                            <div class="text-center mb-4">
                                <div class="stat-icon bg-gradient-info text-white mx-auto mb-2" style="width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center;"><i class="bi bi-receipt"></i></div>
                                <div class="fs-2 fw-bold">{{ \App\Models\Facture::count() }}</div>
                                <div class="text-muted small">Factures</div>
                            </div>
                            <div class="text-center">
                                <div class="stat-icon bg-gradient-danger text-white mx-auto mb-2" style="width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center;"><i class="bi bi-exclamation-triangle"></i></div>
                                <div class="fs-2 fw-bold">{{ \App\Models\Incident::count() }}</div>
                                <div class="text-muted small">Incidents</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Activity Timeline --}}
            <div class="row">
                <div class="col-12">
                    <div class="dashboard-card">
                        <div class="card-header"><i class="bi bi-activity me-2 text-danger"></i>Activité Récente</div>
                        <div class="card-body">
                            <div class="activity-timeline">
                                <div class="activity-item">
                                    <div class="fw-semibold">Nouvelle entreprise ajoutée</div>
                                    <div class="text-muted small">Entreprise de Sécurité ABC - Il y a 2h</div>
                                </div>
                                <div class="activity-item">
                                    <div class="fw-semibold">Nouveau contrat créé</div>
                                    <div class="text-muted small">Contrat avec SBEE - Il y a 5h</div>
                                </div>
                                <div class="activity-item">
                                    <div class="fw-semibold">Utilisateur activé</div>
                                    <div class="text-muted small">Jean Dupont - Agent - Hier</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // Bar Chart - Contracts Evolution
    var contractsChartOptions = {
        series: [{
            name: 'Contrats Actifs',
            data: [12, 15, 18, 22, 25, 28, 32, 35, 38, 42, 45, 48]
        }, {
            name: 'Contrats Expirés',
            data: [3, 4, 5, 4, 6, 5, 7, 8, 6, 9, 7, 10]
        }],
        chart: {
            type: 'bar',
            height: 300,
            toolbar: {
                show: false
            },
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800
            }
        },
        colors: ['#198754', '#dc3545'],
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
                borderRadius: 8
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        xaxis: {
            categories: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'],
            labels: {
                style: {
                    colors: '#6c757d'
                }
            }
        },
        yaxis: {
            labels: {
                style: {
                    colors: '#6c757d'
                }
            }
        },
        fill: {
            opacity: 1
        },
        tooltip: {
            y: {
                formatter: function(val) {
                    return val + ' contrats';
                }
            }
        },
        legend: {
            position: 'top',
            horizontalAlign: 'right'
        },
        grid: {
            borderColor: '#e9ecef'
        }
    };
    new ApexCharts(document.querySelector('#contracts-chart'), contractsChartOptions).render();

    // Pie Chart - Distribution
    var enterprisesCount = {
        {
            \
            App\ Models\ Entreprise::count()
        }
    };
    var clientsCount = {
        {
            \
            App\ Models\ Client::count()
        }
    };
    var contratsCount = {
        {
            \
            App\ Models\ ContratPrestation::count()
        }
    };
    var employesCount = {
        {
            \
            App\ Models\ Employe::count()
        }
    };
    var totalCount = enterprisesCount + clientsCount + contratsCount + employesCount;

    var distributionChartOptions = {
            series: [enterprisesCount, clientsCount, contratsCount, employesCount],
            labels: ['Entreprises', 'Clients', 'Contrats', 'Employés'],
            chart: {
                type: 'donut',
                height: 280
            },
            colors: ['#0d6efd', '#ffc107', '#198754', '#6f42c1'],
            plotOptions: {
                pie: {
                    donut: {
                        size: '65%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Total',
                                formatter: function() {
                                    return totalCount.toString();
                                }
                            }
                        }
                    }
                },
                dataLabels: {
                    enabled: false
                },
                legend: {
                    position: 'bottom'
                },
                stroke: {
                    width: 0
                }
            };
            new ApexCharts(document.querySelector('#distribution-chart'), distributionChartOptions).render();
</script>
@endpush