@extends('layouts.app')

@section('title', 'Dashboard - Benin Security')

@push('styles')
<style>
  /* Custom Dashboard Styles */
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

  @keyframes countUp {
    from {
      opacity: 0;
      transform: scale(0.5);
    }

    to {
      opacity: 1;
      transform: scale(1);
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

  /* Chart container */
  .chart-container {
    position: relative;
    min-height: 300px;
  }

  /* Dark mode adjustments */
  [data-bs-theme="dark"] .dashboard-card {
    background: #212529;
  }

  [data-bs-theme="dark"] .quick-action-btn {
    background: #2d3134;
  }

  [data-bs-theme="dark"] .quick-action-btn:hover {
    background: rgba(25, 135, 84, 0.1);
  }

  [data-bs-theme="dark"] .activity-timeline::before {
    background: linear-gradient(to bottom, #198754, #343a40);
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
    color: #ffc107;
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
</style>
@endpush

@section('content')
<!--begin::App Main-->
<main class="app-main">
  <!--begin::App Content Header-->
  <div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
      <!--begin::Row-->
      <div class="row align-items-center">
        <div class="col-sm-6">
          <h3 class="mb-0">
            <i class="bi bi-speedometer2 me-2 text-success"></i>
            Dashboard
          </h3>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">Accueil</a></li>
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
          </ol>
        </div>
      </div>
      <!--end::Row-->
    </div>
    <!--end::Container-->
  </div>
  <!--end::App Content Header-->

  <!--begin::App Content-->
  <div class="app-content">
    <!--begin::Container-->
    <div class="container-fluid">

      {{-- Welcome Banner --}}
      <div class="row mb-4">
        <div class="col-12">
          <div class="dashboard-card bg-gradient-success text-white p-4">
            <div class="d-flex align-items-center justify-content-between">
              <div>
                <h4 class="mb-1 fw-bold">
                  @auth
                  Bonjour, {{ Auth::user()->name }}! 👋
                  @else
                  Bienvenue sur Benin Security! 👋
                  @endauth
                </h4>
                <p class="mb-0 opacity-75">
                  Voici un aperçu de votre activité de sécurité
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
        {{-- Total Clients --}}
        <div class="col-lg-3 col-6">
          <div class="stat-card dashboard-card p-4 animate-fade-in-up" style="opacity: 0;">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <div class="stat-icon bg-gradient-primary text-white">
                <i class="bi bi-people"></i>
              </div>
              <span class="stat-trend text-success">
                <i class="bi bi-arrow-up"></i> 12%
              </span>
            </div>
            <div class="stat-number text-dark mb-1" data-count="150">0</div>
            <div class="text-muted small">Total Clients</div>
          </div>
        </div>

        {{-- Employés Actifs --}}
        <div class="col-lg-3 col-6">
          <div class="stat-card dashboard-card p-4 animate-fade-in-up" style="opacity: 0;">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <div class="stat-icon bg-gradient-success text-white">
                <i class="bi bi-person-badge"></i>
              </div>
              <span class="stat-trend text-success">
                <i class="bi bi-arrow-up"></i> 8%
              </span>
            </div>
            <div class="stat-number text-dark mb-1" data-count="89">0</div>
            <div class="text-muted small">Employés Actifs</div>
          </div>
        </div>

        {{-- Contrats Actifs --}}
        <div class="col-lg-3 col-6">
          <div class="stat-card dashboard-card p-4 animate-fade-in-up" style="opacity: 0;">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <div class="stat-icon bg-gradient-warning text-dark">
                <i class="bi bi-file-earmark-check"></i>
              </div>
              <span class="stat-trend text-warning">
                <i class="bi bi-dash"></i> 0%
              </span>
            </div>
            <div class="stat-number text-dark mb-1" data-count="45">0</div>
            <div class="text-muted small">Contrats Actifs</div>
          </div>
        </div>

        {{-- Revenus Mensuels --}}
        <div class="col-lg-3 col-6">
          <div class="stat-card dashboard-card p-4 animate-fade-in-up" style="opacity: 0;">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <div class="stat-icon bg-gradient-danger text-white">
                <i class="bi bi-currency-exchange"></i>
              </div>
              <span class="stat-trend text-success">
                <i class="bi bi-arrow-up"></i> 23%
              </span>
            </div>
            <div class="stat-number text-dark mb-1" data-count="12500000">0</div>
            <div class="text-muted small">Revenus (FCFA)</div>
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
                <a href="{{ route('devis') }}" class="quick-action-btn">
                  <i class="bi bi-file-earmark-plus text-warning"></i>
                  <span>Demande Devis</span>
                </a>
              </div>
              <div class="col-6 col-md-3">
                <a href="#" class="quick-action-btn">
                  <i class="bi bi-person-plus text-primary"></i>
                  <span>Nouveau Client</span>
                </a>
              </div>
              <div class="col-6 col-md-3">
                <a href="#" class="quick-action-btn">
                  <i class="bi bi-person-badge-add text-success"></i>
                  <span>Nouvel Employé</span>
                </a>
              </div>
              <div class="col-6 col-md-3">
                <a href="#" class="quick-action-btn">
                  <i class="bi bi-file-earmark-plus text-warning"></i>
                  <span>Nouveau Contrat</span>
                </a>
              </div>
              <div class="col-6 col-md-3">
                <a href="#" class="quick-action-btn">
                  <i class="bi bi-receipt text-info"></i>
                  <span>Nouvelle Facture</span>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Charts Row --}}
      <div class="row mb-4">
        {{-- Revenue Chart --}}
        <div class="col-lg-8">
          <div class="dashboard-card">
            <div class="card-header d-flex align-items-center justify-content-between">
              <span>
                <i class="bi bi-graph-up me-2 text-success"></i>
                Revenus mensuels
              </span>
              <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                  Cette année
                </button>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="#">Cette année</a></li>
                  <li><a class="dropdown-item" href="#">Année dernière</a></li>
                </ul>
              </div>
            </div>
            <div class="card-body">
              <div id="revenue-chart" class="chart-container"></div>
            </div>
          </div>
        </div>

        {{-- Distribution Chart --}}
        <div class="col-lg-4">
          <div class="dashboard-card h-100">
            <div class="card-header">
              <i class="bi bi-pie-chart me-2 text-primary"></i>
              Répartition
            </div>
            <div class="card-body">
              <div id="distribution-chart" class="chart-container"></div>
            </div>
          </div>
        </div>
      </div>

      {{-- Tables Row --}}
      <div class="row mb-4">
        {{-- Recent Contracts --}}
        <div class="col-lg-6">
          <div class="dashboard-card table-card">
            <div class="card-header d-flex align-items-center justify-content-between">
              <span>
                <i class="bi bi-file-earmark-text me-2 text-info"></i>
                Contrats Récents
              </span>
              <a href="#" class="btn btn-sm btn-outline-success">Voir tout</a>
            </div>
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-hover mb-0">
                  <thead>
                    <tr>
                      <th>Client</th>
                      <th>Type</th>
                      <th>Montant</th>
                      <th>Statut</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <div class="d-flex align-items-center">
                          <div class="avatar-sm me-2 bg-primary-subtle rounded-circle">SC</div>
                          <span>Société C...</span>
                        </div>
                      </td>
                      <td>Prestation</td>
                      <td class="fw-semibold">250.000 F</td>
                      <td><span class="status-badge status-active">Actif</span></td>
                    </tr>
                    <tr>
                      <td>
                        <div class="d-flex align-items-center">
                          <div class="avatar-sm me-2 bg-success-subtle rounded-circle">HB</div>
                          <span>Hotel B...</span>
                        </div>
                      </td>
                      <td>Garde</td>
                      <td class="fw-semibold">180.000 F</td>
                      <td><span class="status-badge status-active">Actif</span></td>
                    </tr>
                    <tr>
                      <td>
                        <div class="d-flex align-items-center">
                          <div class="avatar-sm me-2 bg-warning-subtle rounded-circle">MB</div>
                          <span>M. B...</span>
                        </div>
                      </td>
                      <td>Surveillance</td>
                      <td class="fw-semibold">95.000 F</td>
                      <td><span class="status-badge status-pending">En attente</span></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        {{-- Recent Employees --}}
        <div class="col-lg-6">
          <div class="dashboard-card table-card">
            <div class="card-header d-flex align-items-center justify-content-between">
              <span>
                <i class="bi bi-people me-2 text-success"></i>
                Nouveaux Employés
              </span>
              <a href="#" class="btn btn-sm btn-outline-success">Voir tout</a>
            </div>
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-hover mb-0">
                  <thead>
                    <tr>
                      <th>Employé</th>
                      <th>Poste</th>
                      <th>Date</th>
                      <th>Statut</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <div class="d-flex align-items-center">
                          <div class="avatar-sm me-2 bg-success-subtle rounded-circle">AK</div>
                          <span>Amè K.</span>
                        </div>
                      </td>
                      <td>Agent</td>
                      <td>15/02/2026</td>
                      <td><span class="status-badge status-active">En poste</span></td>
                    </tr>
                    <tr>
                      <td>
                        <div class="d-flex align-items-center">
                          <div class="avatar-sm me-2 bg-primary-subtle rounded-circle">DJ</div>
                          <span>Damien J.</span>
                        </div>
                      </td>
                      <td>Superviseur</td>
                      <td>12/02/2026</td>
                      <td><span class="status-badge status-active">En poste</span></td>
                    </tr>
                    <tr>
                      <td>
                        <div class="d-flex align-items-center">
                          <div class="avatar-sm me-2 bg-info-subtle rounded-circle">FT</div>
                          <span>Fatou T.</span>
                        </div>
                      </td>
                      <td>Contrôleur</td>
                      <td>10/02/2026</td>
                      <td><span class="status-badge status-pending">En formation</span></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Activity Timeline & Status --}}
      <div class="row">
        {{-- Activity Timeline --}}
        <div class="col-lg-6">
          <div class="dashboard-card">
            <div class="card-header">
              <i class="bi bi-activity me-2 text-danger"></i>
              Activités Récentes
            </div>
            <div class="card-body">
              <div class="activity-timeline">
                <div class="activity-item">
                  <div class="fw-semibold">Nouveau contrat signé</div>
                  <div class="text-muted small">Société Commerciale du Benin - 2h ago</div>
                </div>
                <div class="activity-item">
                  <div class="fw-semibold">Paiement reçu</div>
                  <div class="text-muted small">Hotel Bel Azur - 5h ago</div>
                </div>
                <div class="activity-item">
                  <div class="fw-semibold">Nouvel employé</div>
                  <div class="text-muted small">Alex K. ajouté comme agent - 1 jour ago</div>
                </div>
                <div class="activity-item">
                  <div class="fw-semibold">Incident signalé</div>
                  <div class="text-muted small">Site Super U - 2 jours ago</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- Status Overview --}}
        <div class="col-lg-6">
          <div class="dashboard-card">
            <div class="card-header">
              <i class="bi bi-bar-chart me-2 text-primary"></i>
              Aperçu des Performances
            </div>
            <div class="card-body">
              <div class="mb-4">
                <div class="d-flex justify-content-between mb-2">
                  <span class="fw-semibold">Contrats honorés</span>
                  <span class="text-success fw-bold">92%</span>
                </div>
                <div class="custom-progress">
                  <div class="progress-bar bg-success" style="width: 92%;"></div>
                </div>
              </div>
              <div class="mb-4">
                <div class="d-flex justify-content-between mb-2">
                  <span class="fw-semibold">Satisfaction client</span>
                  <span class="text-success fw-bold">88%</span>
                </div>
                <div class="custom-progress">
                  <div class="progress-bar bg-primary" style="width: 88%;"></div>
                </div>
              </div>
              <div class="mb-4">
                <div class="d-flex justify-content-between mb-2">
                  <span class="fw-semibold">Pointages effectués</span>
                  <span class="text-warning fw-bold">95%</span>
                </div>
                <div class="custom-progress">
                  <div class="progress-bar bg-warning" style="width: 95%;"></div>
                </div>
              </div>
              <div class="mb-0">
                <div class="d-flex justify-content-between mb-2">
                  <span class="fw-semibold">Paiements recus</span>
                  <span class="text-success fw-bold">78%</span>
                </div>
                <div class="custom-progress">
                  <div class="progress-bar bg-info" style="width: 78%;"></div>
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
</main>
<!--end::App Main-->
@endsection

@push('scripts')
<script>
  // Counter Animation
  function animateCounters() {
    const counters = document.querySelectorAll('[data-count]');

    counters.forEach(counter => {
      const target = parseInt(counter.getAttribute('data-count'));
      const duration = 2000;
      const step = target / (duration / 16);
      let current = 0;

      const updateCounter = () => {
        current += step;
        if (current < target) {
          if (target >= 1000000) {
            counter.textContent = Math.floor(current / 1000000).toLocaleString() + 'M';
          } else if (target >= 1000) {
            counter.textContent = Math.floor(current / 1000).toLocaleString() + 'K';
          } else {
            counter.textContent = Math.floor(current);
          }
          requestAnimationFrame(updateCounter);
        } else {
          if (target >= 1000000) {
            counter.textContent = (target / 1000000).toFixed(1) + 'M';
          } else if (target >= 1000) {
            counter.textContent = target.toLocaleString();
          } else {
            counter.textContent = target;
          }
        }
      };

      updateCounter();
    });
  }

  // Initialize animations on load
  document.addEventListener('DOMContentLoaded', function() {
    animateCounters();
  });

  // Revenue Chart
  const revenueChartOptions = {
    series: [{
      name: 'Revenus',
      data: [2800000, 3200000, 2900000, 3800000, 3500000, 4200000, 3900000, 4500000, 4800000, 5200000, 4900000, 5500000]
    }, {
      name: 'Dépenses',
      data: [1800000, 2100000, 1900000, 2400000, 2200000, 2600000, 2500000, 2800000, 3000000, 3200000, 3100000, 3400000]
    }],
    chart: {
      type: 'area',
      height: 300,
      fontFamily: 'Source Sans 3, sans-serif',
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
    dataLabels: {
      enabled: false
    },
    stroke: {
      curve: 'smooth',
      width: 2
    },
    fill: {
      type: 'gradient',
      gradient: {
        shadeIntensity: 1,
        opacityFrom: 0.4,
        opacityTo: 0.1,
        stops: [0, 90, 100]
      }
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
        },
        formatter: (value) => (value / 1000000).toFixed(1) + 'M'
      }
    },
    grid: {
      borderColor: '#e9ecef'
    },
    legend: {
      position: 'top',
      horizontalAlign: 'right'
    },
    tooltip: {
      y: {
        formatter: (value) => value.toLocaleString() + ' F CFA'
      }
    }
  };

  const revenueChart = new ApexCharts(
    document.querySelector('#revenue-chart'),
    revenueChartOptions
  );
  revenueChart.render();

  // Distribution Chart
  const distributionChartOptions = {
    series: [45, 30, 15, 10],
    labels: ['Gardiennage', 'Surveillance', 'Formation', 'Conseil'],
    chart: {
      type: 'donut',
      height: 280,
      fontFamily: 'Source Sans 3, sans-serif'
    },
    colors: ['#198754', '#0d6efd', '#ffc107', '#6f42c1'],
    plotOptions: {
      pie: {
        donut: {
          size: '65%',
          labels: {
            show: true,
            total: {
              show: true,
              label: 'Total',
              formatter: () => '100%'
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

  const distributionChart = new ApexCharts(
    document.querySelector('#distribution-chart'),
    distributionChartOptions
  );
  distributionChart.render();
</script>
@endpush