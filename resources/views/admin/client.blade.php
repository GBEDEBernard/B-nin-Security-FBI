@extends('layouts.app')

@section('title', 'Dashboard Client - Benin Security')

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
        background: linear-gradient(135deg, #198754 0%, #20c997 100%);
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
        min-height: 280px;
    }

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
</style>
@endpush

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0"><i class="bi bi-person-vcard me-2 text-success"></i>Mon Espace Client</h3>
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
        <div class="row mb-4">
            <div class="col-12">
                <div class="welcome-banner">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-1">Bienvenue, {{ Auth::user()->name }}! 👋</h4>
                            <p class="mb-0 opacity-75">Gérez vos contrats et services de sécurité</p>
                        </div>
                        <div class="d-none d-md-block"><i class="bi bi-shield-check" style="font-size: 4rem; opacity: 0.3;"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-lg-3 col-6">
                <div class="stat-card dashboard-card p-4 animate-fade-in-up" style="opacity: 0;">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon bg-gradient-primary text-white"><i class="bi bi-file-earmark-check"></i></div>
                    </div>
                    <div class="stat-number mb-1">{{ \App\Models\ContratPrestation::where('client_id', auth()->user()->client_id)->count() }}</div>
                    <div class="text-muted small">Mes Contrats</div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="stat-card dashboard-card p-4 animate-fade-in-up" style="opacity: 0;">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon bg-gradient-danger text-white"><i class="bi bi-receipt"></i></div>
                    </div>
                    <div class="stat-number mb-1">{{ \App\Models\Facture::where('client_id', auth()->user()->client_id)->where('statut', 'impayee')->count() }}</div>
                    <div class="text-muted small">Factures Impayées</div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="stat-card dashboard-card p-4 animate-fade-in-up" style="opacity: 0;">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon bg-gradient-warning text-dark"><i class="bi bi-exclamation-triangle"></i></div>
                    </div>
                    <div class="stat-number mb-1">0</div>
                    <div class="text-muted small">Incidents Aujourd'hui</div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="stat-card dashboard-card p-4 animate-fade-in-up" style="opacity: 0;">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon bg-gradient-success text-white"><i class="bi bi-building"></i></div>
                    </div>
                    <div class="stat-number mb-1">{{ \App\Models\SiteClient::where('client_id', auth()->user()->client_id)->count() }}</div>
                    <div class="text-muted small">Mes Sites</div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="dashboard-card p-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-lightning me-2 text-warning"></i>Actions Rapides</h6>
                    <div class="row g-3">
                        <div class="col-6 col-md-3">
                            <a href="{{ route('admin.client.incidents.create') }}" class="quick-action-btn w-100">
                                <i class="bi bi-exclamation-triangle text-danger"></i><span>Signaler Incident</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('admin.client.factures.index') }}" class="quick-action-btn w-100">
                                <i class="bi bi-receipt text-warning"></i><span>Mes Factures</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('admin.client.contrats.index') }}" class="quick-action-btn w-100">
                                <i class="bi bi-file-earmark-text text-primary"></i><span>Mes Contrats</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="#" class="quick-action-btn w-100">
                                <i class="bi bi-chat-dots text-info"></i><span>Contacter Support</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-lg-6">
                <div class="dashboard-card">
                    <div class="card-header"><i class="bi bi-bar-chart me-2 text-success"></i>État des Contrats</div>
                    <div class="card-body">
                        <div id="contrats-chart" class="chart-container">
                            <p class="text-center text-muted">Chargement du graphique...</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="dashboard-card">
                    <div class="card-header"><i class="bi bi-pie-chart me-2 text-primary"></i>Répartition des Factures</div>
                    <div class="card-body">
                        <div id="factures-chart" class="chart-container">
                            <p class="text-center text-muted">Chargement du graphique...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-lg-8">
                <div class="dashboard-card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span><i class="bi bi-file-earmark-text me-2 text-primary"></i>Mes Contrats</span>
                        <a href="{{ route('admin.client.contrats.index') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>N° Contrat</th>
                                        <th>Type</th>
                                        <th>Date début</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse(\App\Models\ContratPrestation::where('client_id', auth()->user()->client_id)->latest()->take(5)->get() as $contrat)
                                    <tr>
                                        <td>{{ $contrat->numero_contrat ?? 'N/A' }}</td>
                                        <td>{{ $contrat->type_contrat ?? 'Prestation' }}</td>
                                        <td>{{ $contrat->date_debut ? \Carbon\Carbon::parse($contrat->date_debut)->format('d/m/Y') : 'N/A' }}</td>
                                        <td>
                                            @if($contrat->statut === 'actif')
                                            <span class="status-badge status-active">Actif</span>
                                            @elseif($contrat->statut === 'en_cours')
                                            <span class="status-badge status-pending">En cours</span>
                                            @else
                                            <span class="status-badge" style="background: rgba(220,53,69,0.1); color: #dc3545;">Expiré</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">Aucun contrat trouvé</td>
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
                    <div class="card-header"><i class="bi bi-person-circle me-2 text-info"></i>Informations du Compte</div>
                    <div class="card-body">
                        @php $client = auth()->user()->client; @endphp
                        <div class="text-center mb-3">
                            <div class="avatar-sm mx-auto mb-2 bg-primary-subtle rounded-circle" style="width: 60px; height: 60px; font-size: 1.5rem; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <h5 class="fw-bold">{{ $client->nom ?? 'N/A' }}</h5>
                            <span class="badge bg-primary">{{ $client && $client->type_client === 'entreprise' ? 'Entreprise' : 'Particulier' }}</span>
                        </div>
                        <div class="mb-2"><small class="text-muted">Email</small>
                            <div>{{ auth()->user()->email }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="dashboard-card">
                    <div class="card-header"><i class="bi bi-receipt me-2 text-warning"></i>Mes Factures Récentes</div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>N° Facture</th>
                                        <th>Montant</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse(\App\Models\Facture::where('client_id', auth()->user()->client_id)->latest()->take(3)->get() as $facture)
                                    <tr>
                                        <td>{{ $facture->numero_facture ?? 'N/A' }}</td>
                                        <td>{{ number_format($facture->montant_total ?? 0, 0, ',', ' ') }} F</td>
                                        <td>
                                            @if($facture->statut === 'payee')
                                            <span class="status-badge status-active">Payée</span>
                                            @else
                                            <span class="status-badge" style="background: rgba(220,53,69,0.1); color: #dc3545;">Impayée</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4">Aucune facture</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="dashboard-card">
                    <div class="card-header"><i class="bi bi-activity me-2 text-danger"></i>Historique</div>
                    <div class="card-body">
                        <div class="activity-timeline">
                            <div class="activity-item">
                                <div class="fw-semibold">Bienvenue sur votre espace</div>
                                <div class="text-muted small">Commencez à gérer vos contrats</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Try to initialize charts only if ApexCharts is loaded
        if (typeof ApexCharts !== 'undefined') {
            // Contrats Chart
            var contratsChartOptions = {
                series: [{
                    name: 'Actifs',
                    data: [2]
                }, {
                    name: 'En cours',
                    data: [1]
                }, {
                    name: 'Expirés',
                    data: [0]
                }],
                chart: {
                    type: 'bar',
                    height: 250,
                    toolbar: {
                        show: false
                    }
                },
                colors: ['#198754', '#ffc107', '#dc3545'],
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
                    categories: ['Contrats']
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'right'
                }
            };
            new ApexCharts(document.querySelector('#contrats-chart'), contratsChartOptions).render();

            // Factures Chart
            var facturesChartOptions = {
                series: [3, 1],
                labels: ['Payées', 'Impayées'],
                chart: {
                    type: 'donut',
                    height: 250
                },
                colors: ['#198754', '#dc3545'],
                plotOptions: {
                    pie: {
                        donut: {
                            size: '65%'
                        }
                    }
                },
                legend: {
                    position: 'bottom'
                }
            };
            new ApexCharts(document.querySelector('#factures-chart'), facturesChartOptions).render();
        } else {
            console.log('ApexCharts not loaded');
        }
    });
</script>
@endpush