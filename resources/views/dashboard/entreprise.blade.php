@extends('layouts.app')

@section('title', 'Dashboard Entreprise - Benin Security')

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

    .stat-card .stat-trend {
        font-size: 0.85rem;
        font-weight: 500;
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

    /* Animation keyframes */
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

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.7;
        }
    }

    .animate-fade-in-up {
        animation: fadeInUp 0.5s ease forwards;
    }

    .animate-pulse-subtle {
        animation: pulse 2s ease-in-out infinite;
    }

    /* Stagger animations */
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

    /* Card styles */
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

    /* Activity timeline */
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

    /* Quick actions */
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

    /* Table improvements */
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
        letter-spacing: 0.5px;
    }

    .status-active {
        background: rgba(25, 135, 84, 0.1);
        color: #198754;
    }

    .status-pending {
        background: rgba(255, 193, 7, 0.1);
        color: #d4a200;
    }

    .status-inactive {
        background: rgba(220, 53, 69, 0.1);
        color: #dc3545;
    }

    /* Progress bars */
    .custom-progress {
        height: 8px;
        border-radius: 4px;
        background: #e9ecef;
        overflow: hidden;
    }

    .custom-progress .progress-bar {
        border-radius: 4px;
        transition: width 1s ease;
    }

    /* Counter animation */
    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: #212529;
    }

    /* Welcome banner */
    .welcome-banner {
        background: linear-gradient(135deg, #198754 0%, #20c997 100%);
        border-radius: 16px;
        padding: 2rem;
        color: white;
    }

    .welcome-banner h4 {
        font-size: 1.5rem;
        font-weight: 700;
    }

    /* Avatar styles */
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
</style>
@endpush

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0">
                    <i class="bi bi-building me-2 text-success"></i>
                    Dashboard Entreprise
                </h3>
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
<!--end::App Content Header-->

<!--begin::App Content-->
<div class="app-content">
    <div class="container-fluid">

        {{-- Welcome Banner --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="welcome-banner">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-1">
                                Bienvenue, {{ Auth::user()->name }}! 👋
                            </h4>
                            <p class="mb-0 opacity-75">
                                Voici un aperçu de votre entreprise de sécurité
                            </p>
                        </div>
                        <div class="d-none d-md-block">
                            <i class="bi bi-shield-check" style="font-size: 4rem; opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistics Cards --}}
        <div class="row mb-4">
            {{-- Total Employés --}}
            <div class="col-lg-3 col-6">
                <div class="stat-card dashboard-card p-4 animate-fade-in-up" style="opacity: 0;">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon bg-gradient-primary text-white">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                    <div class="stat-number mb-1">
                        {{ \App\Models\Employe::where('entreprise_id', auth()->user()->entreprise_id)->count() }}
                    </div>
                    <div class="text-muted small">Total Employés</div>
                </div>
            </div>

            {{-- Total Clients --}}
            <div class="col-lg-3 col-6">
                <div class="stat-card dashboard-card p-4 animate-fade-in-up" style="opacity: 0;">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon bg-gradient-success text-white">
                            <i class="bi bi-person-vcard"></i>
                        </div>
                    </div>
                    <div class="stat-number mb-1">
                        {{ \App\Models\Client::where('entreprise_id', auth()->user()->entreprise_id)->count() }}
                    </div>
                    <div class="text-muted small">Clients</div>
                </div>
            </div>

            {{-- Contrats Actifs --}}
            <div class="col-lg-3 col-6">
                <div class="stat-card dashboard-card p-4 animate-fade-in-up" style="opacity: 0;">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon bg-gradient-warning text-dark">
                            <i class="bi bi-file-earmark-check"></i>
                        </div>
                    </div>
                    <div class="stat-number mb-1">
                        {{ \App\Models\ContratPrestation::where('entreprise_id', auth()->user()->entreprise_id)->where('statut', 'actif')->count() }}
                    </div>
                    <div class="text-muted small">Contrats Actifs</div>
                </div>
            </div>

            {{-- Incidents Aujourd'hui --}}
            <div class="col-lg-3 col-6">
                <div class="stat-card dashboard-card p-4 animate-fade-in-up" style="opacity: 0;">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon bg-gradient-danger text-white">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                    </div>
                    <div class="stat-number mb-1">
                        {{ \App\Models\Incident::where('entreprise_id', auth()->user()->entreprise_id)->whereDate('created_at', today())->count() }}
                    </div>
                    <div class="text-muted small">Incidents Aujourd'hui</div>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="dashboard-card p-4">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-lightning me-2 text-warning"></i>
                        Actions Rapides
                    </h6>
                    <div class="row g-3">
                        <div class="col-6 col-md-3">
                            <a href="{{ route('dashboard.entreprise.employes.create') }}" class="quick-action-btn">
                                <i class="bi bi-person-plus text-primary"></i>
                                <span>Nouvel Employé</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('dashboard.entreprise.clients.create') }}" class="quick-action-btn">
                                <i class="bi bi-person-vcard-add text-success"></i>
                                <span>Nouveau Client</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('dashboard.entreprise.contrats.index') }}" class="quick-action-btn">
                                <i class="bi bi-file-earmark-plus text-warning"></i>
                                <span>Nouveau Contrat</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('dashboard.entreprise.affectations.index') }}" class="quick-action-btn">
                                <i class="bi bi-calendar-check text-info"></i>
                                <span>Affectations</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content Row --}}
        <div class="row mb-4">
            {{-- Affectations du Jour --}}
            <div class="col-lg-8">
                <div class="dashboard-card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span>
                            <i class="bi bi-calendar-event me-2 text-success"></i>
                            Affectations du Jour
                        </span>
                        <a href="{{ route('dashboard.entreprise.affectations.index') }}" class="btn btn-sm btn-outline-success">Voir tout</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Agent</th>
                                        <th>Site</th>
                                        <th>Client</th>
                                        <th>Horaire</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse(\App\Models\Affectation::where('entreprise_id', auth()->user()->entreprise_id)->with(['employe', 'siteClient', 'contratPrestation.client'])->latest()->take(10)->get() as $affectation)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-2 bg-primary-subtle rounded-circle">
                                                    {{ substr($affectation->employe->prenoms ?? 'N', 0, 1) }}{{ substr($affectation->employe->nom ?? 'A', 0, 1) }}
                                                </div>
                                                <span>{{ $affectation->employe->prenoms ?? 'N/A' }} {{ $affectation->employe->nom ?? '' }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $affectation->siteClient->nom_site ?? 'N/A' }}</td>
                                        <td>{{ $affectation->contratPrestation->client->nom ?? 'N/A' }}</td>
                                        <td>{{ $affectation->heure_debut ?? 'N/A' }} - {{ $affectation->heure_fin ?? 'N/A' }}</td>
                                        <td><span class="status-badge status-active">Actif</span></td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">Aucune affectation trouvée</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Statistiques Rapides --}}
            <div class="col-lg-4">
                <div class="dashboard-card mb-4">
                    <div class="card-header">
                        <i class="bi bi-graph-up me-2 text-primary"></i>
                        Statistiques Rapides
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="fw-semibold">Agents disponibles</span>
                                <span class="text-success fw-bold">
                                    {{ \App\Models\Employe::where('entreprise_id', auth()->user()->entreprise_id)->where('disponible', true)->count() }}
                                </span>
                            </div>
                            <div class="custom-progress">
                                @php
                                $totalAgents = \App\Models\Employe::where('entreprise_id', auth()->user()->entreprise_id)->count();
                                $disponibles = \App\Models\Employe::where('entreprise_id', auth()->user()->entreprise_id)->where('disponible', true)->count();
                                $percent = $totalAgents > 0 ? ($disponibles / $totalAgents) * 100 : 0;
                                @endphp
                                <div class="progress-bar bg-success" style="width: {{ $percent }}%;"></div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="fw-semibold">Pointages aujourd'hui</span>
                                <span class="text-primary fw-bold">
                                    {{ \App\Models\Pointage::where('entreprise_id', auth()->user()->entreprise_id)->whereDate('date_pointage', today())->count() }}
                                </span>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="fw-semibold">Congés en attente</span>
                                <span class="text-warning fw-bold">
                                    {{ \App\Models\Conge::where('entreprise_id', auth()->user()->entreprise_id)->where('statut', 'en_attente')->count() }}
                                </span>
                            </div>
                        </div>
                        <div class="mb-0">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="fw-semibold">Factures impayées</span>
                                <span class="text-danger fw-bold">
                                    {{ \App\Models\Facture::where('entreprise_id', auth()->user()->entreprise_id)->where('statut', 'impayee')->count() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bottom Row --}}
        <div class="row">
            {{-- Évolution des Contrats --}}
            <div class="col-lg-6">
                <div class="dashboard-card">
                    <div class="card-header">
                        <i class="bi bi-pie-chart me-2 text-info"></i>
                        État des Contrats
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="stat-icon bg-gradient-success text-white mx-auto mb-3" style="width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                                <div class="fw-bold fs-3">
                                    {{ \App\Models\ContratPrestation::where('entreprise_id', auth()->user()->entreprise_id)->where('statut', 'actif')->count() }}
                                </div>
                                <div class="text-muted small">Actifs</div>
                            </div>
                            <div class="col-4">
                                <div class="stat-icon bg-gradient-warning text-dark mx-auto mb-3" style="width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-clock"></i>
                                </div>
                                <div class="fw-bold fs-3">
                                    {{ \App\Models\ContratPrestation::where('entreprise_id', auth()->user()->entreprise_id)->where('statut', 'en_cours')->count() }}
                                </div>
                                <div class="text-muted small">En cours</div>
                            </div>
                            <div class="col-4">
                                <div class="stat-icon bg-gradient-danger text-white mx-auto mb-3" style="width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-x-circle"></i>
                                </div>
                                <div class="fw-bold fs-3">
                                    {{ \App\Models\ContratPrestation::where('entreprise_id', auth()->user()->entreprise_id)->where('statut', 'expire')->count() }}
                                </div>
                                <div class="text-muted small">Expirés</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Activité Récente --}}
            <div class="col-lg-6">
                <div class="dashboard-card">
                    <div class="card-header">
                        <i class="bi bi-activity me-2 text-danger"></i>
                        Activité Récente
                    </div>
                    <div class="card-body">
                        <div class="activity-timeline">
                            <div class="activity-item">
                                <div class="fw-semibold">Nouveau pointage</div>
                                <div class="text-muted small">Agent: Jean Koffi - Site SBEE - Il y a 1h</div>
                            </div>
                            <div class="activity-item">
                                <div class="fw-semibold">Facture créée</div>
                                <div class="text-muted small">Facture #2024-001 - SONEB - Il y a 3h</div>
                            </div>
                            <div class="activity-item">
                                <div class="fw-semibold">Incident signalé</div>
                                <div class="text-muted small">Incident sur le site de la SBEE - Hier</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!--end::Container-->
</div>
<!--end::App Content-->
@endsection