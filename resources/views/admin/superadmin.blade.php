@extends('layouts.app')

@section('title', 'Dashboard Super Admin - Benin Security')

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

    .stat-card .stat-number {
        font-size: 2rem;
        font-weight: 700;
        line-height: 1;
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

    .bg-gradient-purple {
        background: linear-gradient(135deg, #6f42c1 0%, #9d7df3 100%);
    }

    .bg-gradient-money {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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

    .stat-card:nth-child(5) {
        animation-delay: 0.5s;
    }

    .stat-card:nth-child(6) {
        animation-delay: 0.6s;
    }

    .dashboard-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        background-color: var(--bs-body-bg);
    }

    .dashboard-card:hover {
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }

    .dashboard-card .card-header {
        background: transparent;
        border-bottom: 1px solid var(--bs-border-color);
        padding: 1rem 1.5rem;
        font-weight: 600;
        color: var(--bs-body-color);
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
        background: linear-gradient(to bottom, #198754, var(--bs-border-color));
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
        border: 3px solid var(--bs-body-bg);
        box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.2);
    }

    .activity-item:last-child {
        padding-bottom: 0;
    }

    .quick-action-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 1.5rem 1rem;
        border-radius: 12px;
        border: 2px solid var(--bs-border-color);
        background: var(--bs-tertiary-bg);
        transition: all 0.3s ease;
        text-decoration: none;
        color: var(--bs-body-color);
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

    .status-inactive {
        background: rgba(220, 53, 69, 0.1);
        color: #dc3545;
    }

    .status-pending {
        background: rgba(255, 193, 7, 0.1);
        color: #ffc107;
    }

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
        width: 100%;
        min-height: 300px;
    }

    #contracts-chart {
        height: 300px;
    }

    #distribution-chart {
        height: 280px;
    }

    #revenue-chart {
        height: 300px;
    }

    #factures-chart {
        height: 280px;
    }

    #contrats-status-chart {
        height: 280px;
    }

    #propositions-chart {
        height: 280px;
    }

    #entreprises-chart {
        height: 300px;
    }

    [data-bs-theme="dark"] .text-muted {
        color: #a0a0a0 !important;
    }

    [data-bs-theme="dark"] .dashboard-card .card-header {
        color: #e0e0e0 !important;
    }

    [data-bs-theme="dark"] .table {
        color: #e0e0e0 !important;
    }

    [data-bs-theme="dark"] .table thead th {
        color: #e0e0e0 !important;
    }

    [data-bs-theme="dark"] .activity-item .fw-semibold {
        color: #ffffff !important;
    }

    [data-bs-theme="dark"] .activity-item .text-muted {
        color: #a0a0a0 !important;
    }

    [data-bs-theme="dark"] .stat-card {
        background-color: #1a1a1a !important;
        border: 1px solid #2d2d2d !important;
    }

    [data-bs-theme="dark"] .stat-card .stat-number {
        color: #ffffff !important;
    }

    [data-bs-theme="dark"] .stat-card .text-muted {
        color: #a0a0a0 !important;
    }

    [data-bs-theme="dark"] .quick-action-btn {
        background-color: #1a1a1a !important;
        border-color: #2d2d2d !important;
        color: #e0e0e0 !important;
    }

    [data-bs-theme="dark"] .quick-action-btn:hover {
        background-color: #2d2d2d !important;
        color: #198754 !important;
    }

    [data-bs-theme="dark"] .quick-action-btn i {
        color: #198754 !important;
    }

    [data-bs-theme="dark"] .breadcrumb-item a {
        color: #198754 !important;
    }

    [data-bs-theme="dark"] .breadcrumb-item.active {
        color: #a0a0a0 !important;
    }

    [data-bs-theme="dark"] .btn-outline-secondary {
        border-color: #6c757d !important;
        color: #e0e0e0 !important;
    }

    [data-bs-theme="dark"] .btn-outline-secondary:hover {
        background-color: #2d2d2d !important;
        color: #ffffff !important;
    }

    [data-bs-theme="dark"] h3,
    [data-bs-theme="dark"] h4,
    [data-bs-theme="dark"] h5,
    [data-bs-theme="dark"] h6 {
        color: #e0e0e0 !important;
    }

    [data-bs-theme="dark"] .welcome-banner {
        color: #ffffff !important;
    }
</style>
@endpush

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0">
                    <i class="bi bi-shield-lock me-2" style="color:#6f42c1;"></i>
                    Dashboard Super Administrateur
                </h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
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
                        <div class="d-none d-md-block">
                            <i class="bi bi-shield-lock" style="font-size:4rem; opacity:0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats Cards --}}
        @php
        $nbEntreprises = \App\Models\Entreprise::count();
        $nbEntreprisesActives = \App\Models\Entreprise::where('est_active', true)->count();
        $nbUtilisateurs = \App\Models\User::where('is_superadmin', false)->count();
        $nbClients = \App\Models\Client::count();
        $nbContratsActifs = \App\Models\ContratPrestation::where('statut', 'actif')->count();
        $nbEmployes = \App\Models\Employe::count();
        $nbContrats = \App\Models\ContratPrestation::count();
        $nbFactures = \App\Models\Facture::count();
        $nbIncidents = \App\Models\Incident::count();
        $nbPropositions = \App\Models\PropositionContrat::count();
        $nbPropositionsSignees = \App\Models\PropositionContrat::where('statut', 'signe')->count();
        $nbPropositionsEnAttente = \App\Models\PropositionContrat::where('statut', 'soumis')->count();

        // Statut des factures
        $nbFacturesPayees = \App\Models\Facture::where('statut', 'payee')->count();
        $nbFacturesEnAttente = \App\Models\Facture::where('statut', 'en_attente')->count();
        $nbFacturesImpayes = \App\Models\Facture::where('statut', 'impaye')->count();

        // Statut des contrats
        $nbContratsExpirés = \App\Models\ContratPrestation::where('statut', 'expiré')->count();
        $nbContratsEnCours = \App\Models\ContratPrestation::where('statut', 'actif')->count();
        $nbContratsEnNegociation = \App\Models\ContratPrestation::where('statut', 'en_cours')->count();
        $nbContratsResiliés = \App\Models\ContratPrestation::where('statut', 'resilie')->count();

        // Gains mensuels (revenus des factures payées)
        $gainsMensuels = [];
        $gainsMoisNoms = [];
        for ($i = 1; $i <= 12; $i++) {
            $gainsMensuels[]=\App\Models\Facture::where('statut', 'payee' )
            ->whereMonth('date_paiement', $i)
            ->whereYear('date_paiement', date('Y'))
            ->sum('montant_paye');
            $gainsMoisNoms[] = date('M', mktime(0, 0, 0, $i, 1));
            }
            $gainTotalAnnée = array_sum($gainsMensuels);
            $gainCeMois = $gainsMensuels[date('n') - 1] ?? 0;

            // Répartition par formule
            $formuleEssai = \App\Models\Entreprise::where('formule', 'essai')->count();
            $formuleBasic = \App\Models\Entreprise::where('formule', 'basic')->count();
            $formuleStandard = \App\Models\Entreprise::where('formule', 'standard')->count();
            $formulePremium = \App\Models\Entreprise::where('formule', 'premium')->count();

            // Données pour les graphiques - 12 derniers mois
            $contratsParMois = [];
            $contratsExpirésParMois = [];
            for ($i = 1; $i <= 12; $i++) {
                $contratsParMois[]=\App\Models\ContratPrestation::whereMonth('created_at', $i)->whereYear('created_at', date('Y'))->count();
                $contratsExpirésParMois[] = \App\Models\ContratPrestation::where('statut', 'expiré')->whereMonth('date_fin', $i)->whereYear('date_fin', date('Y'))->count();
                }
                @endphp

                <div class="row mb-4">
                    {{-- Entreprises --}}
                    <div class="col-lg-2 col-6">
                        <div class="stat-card dashboard-card p-4 animate-fade-in-up" style="opacity:0;">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="stat-icon bg-gradient-primary text-white"><i class="bi bi-building"></i></div>
                            </div>
                            <div class="stat-number mb-1">{{ $nbEntreprises }}</div>
                            <div class="text-muted small">Entreprises</div>
                        </div>
                    </div>
                    {{-- Utilisateurs --}}
                    <div class="col-lg-2 col-6">
                        <div class="stat-card dashboard-card p-4 animate-fade-in-up" style="opacity:0;">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="stat-icon bg-gradient-success text-white"><i class="bi bi-people"></i></div>
                            </div>
                            <div class="stat-number mb-1">{{ $nbUtilisateurs }}</div>
                            <div class="text-muted small">Utilisateurs</div>
                        </div>
                    </div>
                    {{-- Clients --}}
                    <div class="col-lg-2 col-6">
                        <div class="stat-card dashboard-card p-4 animate-fade-in-up" style="opacity:0;">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="stat-icon bg-gradient-warning text-dark"><i class="bi bi-person-vcard"></i></div>
                            </div>
                            <div class="stat-number mb-1">{{ $nbClients }}</div>
                            <div class="text-muted small">Clients</div>
                        </div>
                    </div>
                    {{-- Abonnements Actifs --}}
                    <div class="col-lg-2 col-6">
                        <div class="stat-card dashboard-card p-4 animate-fade-in-up" style="opacity:0;">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="stat-icon bg-gradient-danger text-white"><i class="bi bi-credit-card-2-front-fill"></i></div>
                            </div>
                            <div class="stat-number mb-1">{{ $nbEntreprisesActives }}</div>
                            <div class="text-muted small">Abonnements Actifs</div>
                        </div>
                    </div>
                    {{-- Employés --}}
                    <div class="col-lg-2 col-6">
                        <div class="stat-card dashboard-card p-4 animate-fade-in-up" style="opacity:0;">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="stat-icon bg-gradient-info text-white"><i class="bi bi-person-badge"></i></div>
                            </div>
                            <div class="stat-number mb-1">{{ $nbEmployes }}</div>
                            <div class="text-muted small">Employés</div>
                        </div>
                    </div>
                    {{-- Gains du mois --}}
                    <div class="col-lg-2 col-6">
                        <div class="stat-card dashboard-card p-4 animate-fade-in-up" style="opacity:0;">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="stat-icon bg-gradient-money text-white"><i class="bi bi-cash-stack"></i></div>
                            </div>
                            <div class="stat-number mb-1">{{ number_format($gainCeMois, 0, ',', ' ') }}</div>
                            <div class="text-muted small">FCFA Ce Mois</div>
                        </div>
                    </div>
                </div>

                {{-- Quick Actions --}}
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="dashboard-card p-4">
                            <h6 class="fw-bold mb-3"><i class="bi bi-lightning me-2 text-warning"></i>Actions Rapides</h6>
                            <div class="row g-3">
                                <div class="col-6 col-md-2">
                                    <a href="{{ route('admin.superadmin.entreprises.create') }}" class="quick-action-btn">
                                        <i class="bi bi-building-add text-primary"></i><span>Nouvelle Entreprise</span>
                                    </a>
                                </div>
                                <div class="col-6 col-md-2">
                                    <a href="{{ route('admin.superadmin.abonnements.index') }}" class="quick-action-btn">
                                        <i class="bi bi-credit-card-2-front text-success"></i><span>Abonnements</span>
                                    </a>
                                </div>
                                <div class="col-6 col-md-2">
                                    <a href="{{ route('admin.superadmin.utilisateurs.create') }}" class="quick-action-btn">
                                        <i class="bi bi-person-plus text-info"></i><span>Nouvel Utilisateur</span>
                                    </a>
                                </div>
                                <div class="col-6 col-md-2">
                                    <a href="{{ route('admin.superadmin.parametres.index') }}" class="quick-action-btn">
                                        <i class="bi bi-gear text-warning"></i><span>Paramètres</span>
                                    </a>
                                </div>
                                <div class="col-6 col-md-2">
                                    <a href="{{ route('admin.superadmin.rapports.index') }}" class="quick-action-btn">
                                        <i class="bi bi-bar-chart text-purple"></i><span>Rapports</span>
                                    </a>
                                </div>
                                <div class="col-6 col-md-2">
                                    <a href="{{ route('admin.superadmin.propositions.index') }}" class="quick-action-btn">
                                        <i class="bi bi-file-earmark-ruled text-danger"></i><span>Propositions</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Charts Row 1: Revenue & Enterprises Created --}}
                <div class="row mb-4">
                    <div class="col-lg-8">
                        <div class="dashboard-card">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <span><i class="bi bi-building-add me-2" style="color: #6f42c1;"></i>Évolution des Entreprises Créées ({{ date('Y') }})</span>
                                <span class="badge" style="background: #6f42c1;">{{ $nbEntreprises }} total</span>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <div id="entreprises-chart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="dashboard-card h-100">
                            <div class="card-header">
                                <i class="bi bi-pie-chart me-2 text-primary"></i>Répartition par Formule
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <div id="distribution-chart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Charts Row 2: Revenue --}}
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="dashboard-card">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <span><i class="bi bi-cash-stack me-2 text-success"></i>Revenus Mensuels ({{ date('Y') }})</span>
                                <span class="badge bg-success">{{ number_format($gainTotalAnnée, 0, ',', ' ') }} FCA/an</span>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <div id="revenue-chart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Charts Row 2: Abonnements Evolution & Status --}}
                <div class="row mb-4">
                    <div class="col-lg-8">
                        <div class="dashboard-card">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <span><i class="bi bi-graph-up-arrow me-2 text-success"></i>Évolution des Abonnements ({{ date('Y') }})</span>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <div id="contracts-chart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="dashboard-card h-100">
                            <div class="card-header">
                                <i class="bi bi-pie-chart me-2 text-warning"></i>Statut des Abonnements
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <div id="contrats-status-chart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Charts Row 3: Factures & Propositions --}}
                <div class="row mb-4">
                    <div class="col-lg-6">
                        <div class="dashboard-card">
                            <div class="card-header">
                                <i class="bi bi-receipt me-2 text-info"></i>Statut des Factures
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <div id="factures-chart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="dashboard-card">
                            <div class="card-header">
                                <i class="bi bi-file-earmark-ruled me-2 text-purple"></i>Propositions
                                @if($nbPropositionsEnAttente > 0)
                                <span class="badge bg-danger ms-2">{{ $nbPropositionsEnAttente }} en attente</span>
                                @endif
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <div id="propositions-chart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Table & Stats Row --}}
                <div class="row mb-4">
                    <div class="col-lg-8">
                        <div class="dashboard-card">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <span><i class="bi bi-building me-2" style="color:#6f42c1;"></i>Entreprises de Sécurité</span>
                                <div>
                                    <a href="{{ route('admin.superadmin.abonnements.index') }}" class="btn btn-sm btn-outline-success me-1">
                                        <i class="bi bi-credit-card-2-front"></i> Abonnements
                                    </a>
                                    <a href="{{ route('admin.superadmin.entreprises.index') }}" class="btn btn-sm btn-outline-secondary">Voir tout</a>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>Nom</th>
                                                <th>Email</th>
                                                <th>Téléphone</th>
                                                <th>Abonnement</th>
                                                <th>Statut</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse(\App\Models\Entreprise::latest()->take(8)->get() as $entreprise)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm me-2" style="background:rgba(111,66,193,0.1);">
                                                            {{ substr($entreprise->nom ?? $entreprise->nom_entreprise ?? 'EN', 0, 2) }}
                                                        </div>
                                                        {{ $entreprise->nom ?? $entreprise->nom_entreprise }}
                                                    </div>
                                                </td>
                                                <td>{{ $entreprise->email }}</td>
                                                <td>{{ $entreprise->telephone }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $entreprise->formule == 'premium' ? 'purple' : ($entreprise->formule == 'standard' ? 'info' : ($entreprise->formule == 'basic' ? 'warning' : 'secondary')) }}">
                                                        {{ ucfirst($entreprise->formule ?? 'Standard') }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($entreprise->est_active ?? $entreprise->est_actif)
                                                    <span class="status-badge status-active">Actif</span>
                                                    @else
                                                    <span class="status-badge status-inactive">Inactif</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.superadmin.abonnements.show', $entreprise->id) }}" class="btn btn-sm btn-success" title="Abonnement">
                                                        <i class="bi bi-credit-card-2-front"></i>
                                                    </a>
                                                    <a href="{{ route('admin.superadmin.entreprises.show', $entreprise->id) }}" class="btn btn-sm btn-primary" title="Voir">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.superadmin.entreprises.edit', $entreprise->id) }}" class="btn btn-sm btn-warning" title="Modifier">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-4">Aucune entreprise trouvée</td>
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
                            <div class="card-header"><i class="bi bi-bar-chart me-2 text-info"></i>Autres Statistiques</div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                    <div>
                                        <div class="text-muted small">Factures</div>
                                        <div class="fw-bold">{{ $nbFactures }}</div>
                                    </div>
                                    <div class="text-end">
                                        <div class="text-success small">Payées: {{ $nbFacturesPayees }}</div>
                                        <div class="text-warning small">En attente: {{ $nbFacturesEnAttente }}</div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                    <div>
                                        <div class="text-muted small">Abonnements</div>
                                        <div class="fw-bold">{{ $nbEntreprises }}</div>
                                    </div>
                                    <div class="text-end">
                                        <div class="text-success small">Actifs: {{ $nbEntreprisesActives }}</div>
                                        <div class="text-danger small">Inactifs: {{ $nbEntreprises - $nbEntreprisesActives }}</div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                    <div>
                                        <div class="text-muted small">Propositions</div>
                                        <div class="fw-bold">{{ $nbPropositions }}</div>
                                    </div>
                                    <div class="text-end">
                                        <div class="text-success small">Signées: {{ $nbPropositionsSignees }}</div>
                                        <div class="text-warning small">En attente: {{ $nbPropositionsEnAttente }}</div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="text-muted small">Incidents</div>
                                        <div class="fw-bold">{{ $nbIncidents }}</div>
                                    </div>
                                    <div class="text-end">
                                        <div class="text-muted small">Total enregistré</div>
                                    </div>
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
@endsection

{{-- Données JavaScript pour les graphiques --}}
@php
$revenueData = array_fill(0, 12, 0);
$revenueLabels = [];
for ($i = 1; $i <= 12; $i++) {
    $revenueData[$i-1]=\App\Models\Facture::where('statut', 'payee' )
    ->whereMonth('date_paiement', $i)
    ->whereYear('date_paiement', date('Y'))
    ->sum('montant_paye');
    $revenueLabels[] = date('M', mktime(0, 0, 0, $i, 1));
    }

    // Données pour les graphiques - 12 derniers mois
    $contratsParMois = [];
    $contratsExpirésParMois = [];
    for ($i = 1; $i <= 12; $i++) {
        $contratsParMois[]=\App\Models\ContratPrestation::whereMonth('created_at', $i)->whereYear('created_at', date('Y'))->count();
        $contratsExpirésParMois[] = \App\Models\ContratPrestation::where('statut', 'expiré')->whereMonth('date_fin', $i)->whereYear('date_fin', date('Y'))->count();
        }

        // Entreprises créées par mois
        $entreprisesParMois = [];
        for ($i = 1; $i <= 12; $i++) {
            $entreprisesParMois[]=\App\Models\Entreprise::whereMonth('created_at', $i)->whereYear('created_at', date('Y'))->count();
            }

            // Répartition par formule
            $formuleEssai = \App\Models\Entreprise::where('formule', 'essai')->count();
            $formuleBasic = \App\Models\Entreprise::where('formule', 'basic')->count();
            $formuleStandard = \App\Models\Entreprise::where('formule', 'standard')->count();
            $formulePremium = \App\Models\Entreprise::where('formule', 'premium')->count();
            $distributionParFormule = [$formuleEssai, $formuleBasic, $formuleStandard, $formulePremium];

            // Statut des factures
            $facturesPayees = \App\Models\Facture::where('statut', 'payee')->count();
            $facturesEnAttente = \App\Models\Facture::where('statut', 'en_attente')->count();
            $facturesImpayees = \App\Models\Facture::where('statut', 'impaye')->count();
            $facturesData = [$facturesPayees, $facturesEnAttente, $facturesImpayees];

            // Statut des contrats
            $contratsActifs = \App\Models\ContratPrestation::where('statut', 'actif')->count();
            $contratsEnCours = \App\Models\ContratPrestation::where('statut', 'en_cours')->count();
            $contratsExpirés = \App\Models\ContratPrestation::where('statut', 'expiré')->count();
            $contratsResiliés = \App\Models\ContratPrestation::where('statut', 'resilie')->count();
            $contratsStatusData = [$contratsActifs, $contratsEnCours, $contratsExpirés, $contratsResiliés ?? 0];

            // Statut des propositions
            $propositionsSoumis = \App\Models\PropositionContrat::where('statut', 'soumis')->count();
            $propositionsNegociation = \App\Models\PropositionContrat::where('statut', 'en_negociation')->count();
            $propositionsSignees = \App\Models\PropositionContrat::where('statut', 'signe')->count();
            $propositionsRefusees = \App\Models\PropositionContrat::where('statut', 'refuse')->count();
            $propositionsData = [$propositionsSoumis, $propositionsNegociation, $propositionsSignees, $propositionsRefusees ?? 0];
            @endphp

            @push('scripts')
            <script>
                // Données pour les graphiques - Revenus mensuels
                var REVENUE_DATA = @json($revenueData);
                var REVENUE_LABELS = @json($revenueLabels);

                // Données pour les graphiques - Entreprises créées par mois
                var ENTREPRISES_PAR_MOIS = @json($entreprisesParMois);

                // Données pour les graphiques - Contrats
                var CONTRATS_PAR_MOIS = @json($contratsParMois);
                var CONTRATS_EXPIRES_PAR_MOIS = @json($contratsExpirésParMois);

                // Données pour la distribution par formule
                var DISTRIBUTION_PAR_FORMULE = @json($distributionParFormule);

                // Données pour les factures
                var FACTURES_DATA = @json($facturesData);

                // Données pour le statut des contrats
                var CONTRATS_STATUS_DATA = @json($contratsStatusData);

                // Données pour les propositions
                var PROPOSITIONS_DATA = @json($propositionsData);
            </script>
            @endpush