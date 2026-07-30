@extends('layouts.app')

@section('title', 'Dashboard Entreprises - Super Admin')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">
                    <i class="bi bi-building-fill text-success me-2"></i>
                    Tableau de Bord des Entreprises
                </h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Entreprises</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        {{-- Stats Cards --}}
        <div class="row g-3 mb-4">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $stats['total'] }}</h3>
                        <p>Total Entreprises</p>
                    </div>
                    <div class="icon"><i class="bi bi-building"></i></div>
                    <a href="#" class="small-box-footer">Voir tout <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $stats['actives'] }}</h3>
                        <p>Actives</p>
                    </div>
                    <div class="icon"><i class="bi bi-check-circle"></i></div>
                    <a href="#" class="small-box-footer">Voir tout <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $stats['essai'] }}</h3>
                        <p>En Essai</p>
                    </div>
                    <div class="icon"><i class="bi bi-flask"></i></div>
                    <a href="#" class="small-box-footer">Voir tout <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $stats['expired'] }}</h3>
                        <p>Expirées / Suspendues</p>
                    </div>
                    <div class="icon"><i class="bi bi-exclamation-triangle"></i></div>
                    <a href="#" class="small-box-footer">Voir tout <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-secondary">
                    <div class="inner">
                        <h3>{{ $stats['total_employes'] }}</h3>
                        <p>Total Employés</p>
                    </div>
                    <div class="icon"><i class="bi bi-people"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ $stats['total_clients'] }}</h3>
                        <p>Total Clients</p>
                    </div>
                    <div class="icon"><i class="bi bi-person-badge"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-teal">
                    <div class="inner">
                        <h3>{{ $stats['total_contrats'] }}</h3>
                        <p>Contrats Actifs</p>
                    </div>
                    <div class="icon"><i class="bi bi-file-earmark-text"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-purple">
                    <div class="inner">
                        <h3>{{ number_format($stats['revenu_mensuel'], 0, ',', ' ') }}</h3>
                        <p>Revenu Mensuel (FCFA)</p>
                    </div>
                    <div class="icon"><i class="bi bi-cash-stack"></i></div>
                </div>
            </div>
        </div>

        {{-- Charts --}}
        <div class="row g-3 mb-4">
            <div class="col-lg-8">
                <div class="card card-outline card-success">
                    <div class="card-header">
                        <h5 class="card-title fw-bold">
                            <i class="bi bi-graph-up me-1"></i> Créations d'Entreprises (12 mois)
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="monthlyChart" style="height: 280px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card card-outline card-success">
                    <div class="card-header">
                        <h5 class="card-title fw-bold">
                            <i class="bi bi-pie-chart me-1"></i> Répartition par Formule
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="formulesChart" style="height: 280px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-lg-6">
                <div class="card card-outline card-success">
                    <div class="card-header">
                        <h5 class="card-title fw-bold">
                            <i class="bi bi-trophy me-1"></i> Top 10 Entreprises (Employés)
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="topChart" style="height: 280px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card card-outline card-success">
                    <div class="card-header">
                        <h5 class="card-title fw-bold">
                            <i class="bi bi-geo-alt me-1"></i> Répartition par Ville
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="villesChart" style="height: 280px;"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="row g-3 mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title fw-bold">
                            <i class="bi bi-lightning me-1"></i> Actions Rapides
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('admin.superadmin.entreprises.create') }}" class="btn btn-success">
                                <i class="bi bi-plus-circle me-1"></i> Nouvelle Entreprise
                            </a>
                            <a href="{{ route('admin.superadmin.abonnements.create') }}" class="btn btn-info text-white">
                                <i class="bi bi-credit-card me-1"></i> Nouvel Abonnement
                            </a>
                            <a href="{{ route('admin.superadmin.facturation.generer') }}" class="btn btn-warning text-dark">
                                <i class="bi bi-receipt me-1"></i> Générer Factures
                            </a>
                            <a href="{{ route('admin.superadmin.rapports.index') }}" class="btn btn-secondary">
                                <i class="bi bi-graph-up me-1"></i> Rapports
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters + Table --}}
        <div class="card">
            <div class="card-header">
                <h5 class="card-title fw-bold">
                    <i class="bi bi-list-ul me-1"></i> Liste des Entreprises
                </h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.superadmin.entreprises.index') }}" class="row g-3 mb-3">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-transparent"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select name="statut" class="form-select" onchange="this.form.submit()">
                            <option value="">Tous</option>
                            <option value="actif" {{ request('statut') == 'actif' ? 'selected' : '' }}>Actifs</option>
                            <option value="inactif" {{ request('statut') == 'inactif' ? 'selected' : '' }}>Inactifs</option>
                            <option value="essai" {{ request('statut') == 'essai' ? 'selected' : '' }}>Essai</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="formule" class="form-select" onchange="this.form.submit()">
                            <option value="">Toutes</option>
                            <option value="essai" {{ request('formule') == 'essai' ? 'selected' : '' }}>Essai</option>
                            <option value="basic" {{ request('formule') == 'basic' ? 'selected' : '' }}>Basic</option>
                            <option value="standard" {{ request('formule') == 'standard' ? 'selected' : '' }}>Standard</option>
                            <option value="premium" {{ request('formule') == 'premium' ? 'selected' : '' }}>Premium</option>
                            <option value="enterprise" {{ request('formule') == 'enterprise' ? 'selected' : '' }}>Enterprise</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-outline-success w-100">
                            <i class="bi bi-search me-1"></i>Filtrer
                        </button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('admin.superadmin.entreprises.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-x-circle me-1"></i>Réinitialiser
                        </a>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Entreprise</th>
                                <th>Contact</th>
                                <th>Statut</th>
                                <th class="text-center">Employés</th>
                                <th class="text-center">Clients</th>
                                <th class="text-center">Contrats</th>
                                <th class="text-center">Factures</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($entreprises as $entreprise)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            @if($entreprise->logo)
                                            <img src="{{ $entreprise->logoUrl }}" class="rounded" style="width:38px;height:38px;object-fit:cover;">
                                            @else
                                            <div class="rounded d-flex align-items-center justify-content-center"
                                                style="width:38px;height:38px;background:rgba(25,135,84,0.1);">
                                                <span class="fw-bold text-success small">{{ strtoupper(substr($entreprise->nom_entreprise, 0, 2)) }}</span>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="ms-2">
                                            <span class="fw-semibold d-block">{{ $entreprise->nom_entreprise }}</span>
                                            <small class="text-muted">{{ $entreprise->ville ?? 'Non localisée' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <small>{{ $entreprise->email }}</small><br>
                                    <small class="text-muted">{{ $entreprise->telephone }}</small>
                                </td>
                                <td>
                                    @if($entreprise->est_active)
                                    <span class="badge bg-success">Actif</span>
                                    @else
                                    <span class="badge bg-secondary">Inactif</span>
                                    @endif
                                    @if($entreprise->est_en_essai)
                                    <span class="badge bg-warning text-dark">Essai</span>
                                    @endif
                                </td>
                                <td class="text-center"><span class="badge bg-info">{{ $entreprise->employes_count }}</span></td>
                                <td class="text-center"><span class="badge bg-primary">{{ $entreprise->clients_count }}</span></td>
                                <td class="text-center"><span class="badge bg-warning text-dark">{{ $entreprise->contratsPrestation_count }}</span></td>
                                <td class="text-center"><span class="badge bg-danger">{{ $entreprise->factures_count }}</span></td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        @if($entreprise->est_active)
                                        <button type="button" class="btn btn-outline-success"
                                            title="Connecter"
                                            data-bs-toggle="modal"
                                            data-bs-target="#connectModal"
                                            data-entreprise-id="{{ $entreprise->id }}"
                                            data-entreprise-nom="{{ $entreprise->nom_entreprise }}">
                                            <i class="bi bi-box-arrow-in-right"></i>
                                        </button>
                                        @endif
                                        <a href="{{ route('admin.superadmin.entreprises.show', $entreprise->id) }}"
                                            class="btn btn-outline-info" title="Détails">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.superadmin.entreprises.edit', $entreprise->id) }}"
                                            class="btn btn-outline-warning" title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <i class="bi bi-building fs-3 d-block mb-2"></i>
                                    Aucune entreprise trouvée
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(method_exists($entreprises, 'links'))
                <div class="mt-3">{{ $entreprises->appends(request()->query())->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .small-box {
        border-radius: 12px;
        position: relative;
        display: block;
        margin-bottom: 0;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        overflow: hidden;
    }
    .small-box:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }
    .small-box>.inner {
        padding: 16px;
        position: relative;
        z-index: 2;
    }
    .small-box>.inner h3 {
        font-size: 2rem;
        font-weight: 700;
        margin: 0 0 4px;
        color: #fff;
    }
    .small-box>.inner p {
        font-size: 0.85rem;
        margin: 0;
        color: rgba(255,255,255,0.85);
    }
    .small-box .icon {
        position: absolute;
        top: 12px;
        right: 12px;
        z-index: 1;
        font-size: 3.5rem;
        color: rgba(255,255,255,0.2);
        transition: all 0.3s ease;
    }
    .small-box:hover .icon {
        font-size: 4rem;
        color: rgba(255,255,255,0.3);
    }
    .small-box>.small-box-footer {
        display: block;
        padding: 4px 0;
        text-align: center;
        text-decoration: none;
        background: rgba(0,0,0,0.1);
        color: rgba(255,255,255,0.7);
        font-size: 0.75rem;
        transition: all 0.2s;
    }
    .small-box>.small-box-footer:hover {
        background: rgba(0,0,0,0.15);
        color: #fff;
    }
    .bg-teal { background: #20c997 !important; color: #fff !important; }
    .bg-purple { background: #6f42c1 !important; color: #fff !important; }
    [data-bs-theme="dark"] .small-box { box-shadow: 0 2px 10px rgba(0,0,0,0.3); }
    [data-bs-theme="dark"] .table-light { background: var(--bs-dark); color: var(--bs-light); }
    [data-bs-theme="dark"] .table-light th { color: var(--bs-light); background: #2d2d2d; }
    .badge { font-weight: 500; }
    .card { border-radius: 12px; }
    .card-header { border-radius: 12px 12px 0 0; }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const t = () => {
            const d = document.documentElement.getAttribute('data-bs-theme') === 'dark';
            return {
                isDark: d,
                text: d ? '#e0e0e0' : '#212529',
                textMuted: d ? '#a0a0a0' : '#6c757d',
                grid: d ? '#2d2d2d' : '#e9ecef',
                bg: d ? '#1a1a1a' : '#ffffff',
            };
        };

        function renderAll() {
            const theme = t();
            const opts = (extra) => ({
                chart: { toolbar: { show: false }, background: theme.bg, ...extra },
                grid: { borderColor: theme.grid },
                xaxis: { labels: { style: { colors: theme.textMuted, fontSize: '11px' } } },
                yaxis: { labels: { style: { colors: theme.textMuted } } },
                tooltip: { theme: theme.isDark ? 'dark' : 'light' },
            });

            const m = document.getElementById('monthlyChart');
            if (m) new ApexCharts(m, {
                series: [{ name: 'Créations', data: @json($chartMonthly['data']) }],
                chart: { type: 'bar', height: 280, ...opts().chart },
                colors: ['#198754'],
                plotOptions: { bar: { borderRadius: 6, columnWidth: '55%' } },
                dataLabels: { enabled: false },
                xaxis: { categories: @json($chartMonthly['labels']), ...opts().xaxis },
                yaxis: { min: 0, ...opts().yaxis },
                grid: opts().grid,
                tooltip: opts().tooltip,
            }).render();

            const f = document.getElementById('formulesChart');
            if (f && @json($chartFormules['data']).length) new ApexCharts(f, {
                series: @json($chartFormules['data']),
                labels: @json($chartFormules['labels']),
                chart: { type: 'donut', height: 280, background: theme.bg },
                colors: ['#ffc107', '#0d6efd', '#198754', '#6f42c1', '#dc3545'],
                plotOptions: { pie: { donut: { size: '60%', labels: { show: true, total: { show: true, color: theme.text } } } } },
                legend: { position: 'bottom', labels: { colors: theme.text } },
                dataLabels: { enabled: false },
                stroke: { width: 0 },
                tooltip: opts().tooltip,
            }).render();

            const top = document.getElementById('topChart');
            if (top && @json($chartTop['data']).length) new ApexCharts(top, {
                series: [{ name: 'Employés', data: @json($chartTop['data']) }],
                chart: { type: 'bar', height: 280, ...opts().chart },
                colors: ['#20c997'],
                plotOptions: { bar: { borderRadius: 4, horizontal: true } },
                dataLabels: { enabled: true, style: { colors: ['#fff'] } },
                xaxis: { categories: @json($chartTop['labels']), ...opts().xaxis },
                yaxis: opts().yaxis,
                grid: opts().grid,
                tooltip: opts().tooltip,
            }).render();

            const v = document.getElementById('villesChart');
            if (v && @json($chartVilles['data']).length) new ApexCharts(v, {
                series: [{ name: 'Entreprises', data: @json($chartVilles['data']) }],
                chart: { type: 'bar', height: 280, ...opts().chart },
                colors: ['#0d6efd'],
                plotOptions: { bar: { borderRadius: 6, columnWidth: '50%', distributed: true } },
                dataLabels: { enabled: false },
                xaxis: { categories: @json($chartVilles['labels']), ...opts().xaxis },
                yaxis: opts().yaxis,
                grid: opts().grid,
                tooltip: opts().tooltip,
            }).render();
        }

        renderAll();
        window.addEventListener('theme-changed', () => setTimeout(renderAll, 100));
    });
</script>
@endpush
@endsection
