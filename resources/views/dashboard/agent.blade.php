@extends('layouts.app')

@section('title', 'Dashboard Agent - Benin Security')

@push('styles')
<style>
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

    .status-pending {
        background: rgba(255, 193, 7, 0.1);
        color: #d4a200;
    }

    .welcome-banner {
        background: linear-gradient(135deg, #0d6efd 0%, #6ea8fe 100%);
        border-radius: 16px;
        padding: 2rem;
        color: white;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: #212529;
    }

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

    .chart-container {
        position: relative;
        min-height: 250px;
    }
</style>
@endpush

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0"><i class="bi bi-person-badge me-2 text-primary"></i>Mon Dashboard</h3>
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
                                <p class="mb-0 opacity-75">Voici un aperçu de votre activité</p>
                            </div>
                            <div class="d-none d-md-block"><i class="bi bi-shield-check" style="font-size: 4rem; opacity: 0.3;"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Statistics Cards --}}
            <div class="row mb-4">
                <div class="col-lg-3 col-6">
                    <div class="stat-card dashboard-card p-4 animate-fade-in-up" style="opacity: 0;">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="stat-icon bg-gradient-primary text-white"><i class="bi bi-briefcase"></i></div>
                        </div>
                        <div class="stat-number mb-1">{{ \App\Models\Affectation::whereHas('employe', function($q) { $q->where('user_id', auth()->id()); })->whereDate('date_debut', '<=', now())->whereDate('date_fin', '>=', now())->count() }}</div>
                        <div class="text-muted small">Mes Missions</div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="stat-card dashboard-card p-4 animate-fade-in-up" style="opacity: 0;">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="stat-icon bg-gradient-success text-white"><i class="bi bi-clock"></i></div>
                        </div>
                        <div class="stat-number mb-1">{{ \App\Models\Pointage::whereHas('employe', function($q) { $q->where('user_id', auth()->id()); })->whereDate('date_pointage', today())->count() }}</div>
                        <div class="text-muted small">Pointages Aujourd'hui</div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="stat-card dashboard-card p-4 animate-fade-in-up" style="opacity: 0;">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="stat-icon bg-gradient-warning text-dark"><i class="bi bi-calendar-event"></i></div>
                        </div>
                        <div class="stat-number mb-1">{{ \App\Models\Conge::whereHas('employe', function($q) { $q->where('user_id', auth()->id()); })->where('statut', 'en_attente')->count() }}</div>
                        <div class="text-muted small">Congés en Attente</div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="stat-card dashboard-card p-4 animate-fade-in-up" style="opacity: 0;">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="stat-icon bg-gradient-danger text-white"><i class="bi bi-exclamation-triangle"></i></div>
                        </div>
                        <div class="stat-number mb-1">{{ \App\Models\Incident::whereHas('employe', function($q) { $q->where('user_id', auth()->id()); })->whereDate('created_at', today())->count() }}</div>
                        <div class="text-muted small">Incidents Aujourd'hui</div>
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
                                <button class="quick-action-btn w-100" data-bs-toggle="modal" data-bs-target="#pointageModal">
                                    <i class="bi bi-check-circle text-success"></i><span>Pointer</span>
                                </button>
                            </div>
                            <div class="col-6 col-md-3">
                                <button class="quick-action-btn w-100" data-bs-toggle="modal" data-bs-target="#incidentModal">
                                    <i class="bi bi-exclamation-triangle text-danger"></i><span>Signaler Incident</span>
                                </button>
                            </div>
                            <div class="col-6 col-md-3">
                                <a href="{{ route('dashboard.agent.conges.index') }}" class="quick-action-btn w-100">
                                    <i class="bi bi-calendar-plus text-warning"></i><span>Demander Congé</span>
                                </a>
                            </div>
                            <div class="col-6 col-md-3">
                                <a href="{{ route('dashboard.agent.missions.index') }}" class="quick-action-btn w-100">
                                    <i class="bi bi-list-task text-info"></i><span>Mes Missions</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Charts Row --}}
            <div class="row mb-4">
                <div class="col-lg-6">
                    <div class="dashboard-card">
                        <div class="card-header"><i class="bi bi-bar-chart me-2 text-success"></i>Mes Pointages (7 derniers jours)</div>
                        <div class="card-body">
                            <div id="pointages-chart" class="chart-container"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="dashboard-card">
                        <div class="card-header"><i class="bi bi-pie-chart me-2 text-primary"></i>Répartition des Missions</div>
                        <div class="card-body">
                            <div id="missions-chart" class="chart-container"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main Content --}}
            <div class="row mb-4">
                <div class="col-lg-8">
                    <div class="dashboard-card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <span><i class="bi bi-briefcase me-2 text-primary"></i>Mes Missions en Cours</span>
                            <a href="{{ route('dashboard.agent.missions.index') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Site</th>
                                            <th>Client</th>
                                            <th>Date</th>
                                            <th>Horaire</th>
                                            <th>Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse(\App\Models\Affectation::whereHas('employe', function($q) { $q->where('user_id', auth()->id()); })->with(['siteClient', 'contratPrestation.client'])->whereDate('date_debut', '<=', now())->whereDate('date_fin', '>=', now())->latest()->take(5)->get() as $affectation)
                                            <tr>
                                                <td>{{ $affectation->siteClient->nom_site ?? 'N/A' }}</td>
                                                <td>{{ $affectation->contratPrestation->client->nom ?? 'N/A' }}</td>
                                                <td>{{ $affectation->date_debut ? \Carbon\Carbon::parse($affectation->date_debut)->format('d/m/Y') : 'N/A' }}</td>
                                                <td>{{ $affectation->heure_debut ?? 'N/A' }} - {{ $affectation->heure_fin ?? 'N/A' }}</td>
                                                <td><span class="status-badge status-active">En cours</span></td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4">Aucune mission en cours</td>
                                            </tr>
                                            @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="dashboard-card mb-4">
                        <div class="card-header"><i class="bi bi-calendar-check me-2 text-warning"></i>Mon Solde de Congés</div>
                        <div class="card-body">
                            @php $employe = auth()->user()->employe; $soldeConge = $employe ? $employe->soldeConge : null; @endphp
                            <div class="text-center mb-3">
                                <div class="stat-number text-success">{{ $soldeConge ? $soldeConge->jours_restants : 0 }}</div>
                                <div class="text-muted small">jours restants</div>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Pris cette année</span>
                                <span class="fw-bold">{{ $soldeConge ? $soldeConge->jours_pris : 0 }} jours</span>
                            </div>
                            <div class="d-grid mt-3">
                                <a href="{{ route('dashboard.agent.conges.index') }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-plus-circle me-1"></i> Demander un congé
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pointage History --}}
            <div class="row">
                <div class="col-12">
                    <div class="dashboard-card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <span><i class="bi bi-clock-history me-2 text-info"></i>Mon Historique de Pointage</span>
                            <a href="{{ route('dashboard.agent.pointages.index') }}" class="btn btn-sm btn-outline-info">Voir tout</a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Site</th>
                                            <th>Arrivée</th>
                                            <th>Départ</th>
                                            <th>Durée</th>
                                            <th>Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse(\App\Models\Pointage::whereHas('employe', function($q) { $q->where('user_id', auth()->id()); })->with('affectation.siteClient')->latest()->take(5)->get() as $pointage)
                                        <tr>
                                            <td>{{ $pointage->date_pointage ? \Carbon\Carbon::parse($pointage->date_pointage)->format('d/m/Y') : 'N/A' }}</td>
                                            <td>{{ $pointage->affectation->siteClient->nom_site ?? 'N/A' }}</td>
                                            <td>{{ $pointage->heure_arrivee ?? 'N/A' }}</td>
                                            <td>{{ $pointage->heure_depart ?? 'N/A' }}</td>
                                            <td>{{ $pointage->duree ?? 'N/A' }}</td>
                                            <td>
                                                @if($pointage->statut === 'valide')
                                                <span class="status-badge status-active">Validé</span>
                                                @elseif($pointage->statut === 'en_attente')
                                                <span class="status-badge status-pending">En attente</span>
                                                @else
                                                <span class="status-badge" style="background: rgba(220,53,69,0.1); color: #dc3545;">Rejeté</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">Aucun pointage trouvé</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Modal Pointage -->
<div class="modal fade" id="pointageModal" tabindex="-1" aria-labelledby="pointageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pointageModalLabel">Pointer mon arrivée</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sur le site? Cliquez sur pointer pour enregistrer votre arrivée.</p>
                <div class="mb-3">
                    <label for="site" class="form-label">Site</label>
                    <select class="form-select" id="site" required>
                        <option value="">Sélectionner un site</option>
                        @foreach(\App\Models\Affectation::whereHas('employe', function($q) { $q->where('user_id', auth()->id()); })->with('siteClient')->get() as $affectation)
                        <option value="{{ $affectation->siteClient->id }}">{{ $affectation->siteClient->nom_site }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="heure_arrivee" class="form-label">Heure d'arrivée</label>
                    <input type="time" class="form-control" id="heure_arrivee" value="{{ now()->format('H:i') }}" required>
                </div>
                <div class="mb-3">
                    <label for="observation" class="form-label">Observation</label>
                    <textarea class="form-control" id="observation" rows="3" placeholder="Observation optionnelle..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-success">Pointer</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Incident -->
<div class="modal fade" id="incidentModal" tabindex="-1" aria-labelledby="incidentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="incidentModalLabel">Signaler un incident</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="titre" class="form-label">Titre de l'incident</label>
                    <input type="text" class="form-control" id="titre" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" rows="4" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="niveau" class="form-label">Niveau de gravité</label>
                    <select class="form-select" id="niveau" required>
                        <option value="faible">Faible</option>
                        <option value="moyen">Moyen</option>
                        <option value="eleve">Élevé</option>
                        <option value="critique">Critique</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger">Signaler</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // Pointages Bar Chart
    var pointagesChartOptions = {
        series: [{
            name: 'Pointages',
            data: [3, 4, 2, 5, 3, 4, 5]
        }],
        chart: {
            type: 'bar',
            height: 250,
            toolbar: {
                show: false
            },
            animations: {
                enabled: true,
                speed: 800
            }
        },
        colors: ['#198754'],
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '50%',
                borderRadius: 6
            }
        },
        dataLabels: {
            enabled: false
        },
        xaxis: {
            categories: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
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
        grid: {
            borderColor: '#e9ecef'
        }
    };
    new ApexCharts(document.querySelector('#pointages-chart'), pointagesChartOptions).render();

    // Missions Donut Chart
    var missionsChartOptions = {
        series: [5, 3, 2],
        labels: ['Gardiennage', 'Surveillance', 'Escorte'],
        chart: {
            type: 'donut',
            height: 250
        },
        colors: ['#0d6efd', '#198754', '#ffc107'],
        plotOptions: {
            pie: {
                donut: {
                    size: '65%'
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
    new ApexCharts(document.querySelector('#missions-chart'), missionsChartOptions).render();
</script>
@endpush