@extends('layouts.app')

@section('title', 'Statistiques des Notifications - Super Admin')

@section('content')
<div class="container-fluid p-4">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">
                <i class="bi bi-bell text-info me-2"></i>
                Statistiques des Notifications
            </h2>
            <p class="text-muted mb-0">Vue d'ensemble et analyses des notifications push</p>
        </div>
        <a href="{{ route('admin.superadmin.notifications.index') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left me-1"></i> Retour aux notifications
        </a>
    </div>

    <!-- Stats générales -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-1">Total des notifications</h6>
                            <h2 class="mb-0 text-primary">{{ $stats['total'] ?? 0 }}</h2>
                        </div>
                        <i class="bi bi-bell fs-1 text-primary opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-1">Envoyées</h6>
                            <h2 class="mb-0 text-success">{{ $stats['total_envoyees'] ?? 0 }}</h2>
                        </div>
                        <i class="bi bi-check-circle fs-1 text-success opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-1">Aujourd'hui</h6>
                            <h2 class="mb-0 text-warning">{{ $stats['aujourdhui'] ?? 0 }}</h2>
                        </div>
                        <i class="bi bi-calendar-day fs-1 text-warning opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-1">Non lues</h6>
                            <h2 class="mb-0 text-info">{{ $stats['non_lues'] ?? 0 }}</h2>
                        </div>
                        <i class="bi bi-bell-slash fs-1 text-info opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Répartition par type -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-pie-chart me-2"></i>Répartition par type</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-3">
                            <div class="p-3 bg-info bg-opacity-10 rounded">
                                <i class="bi bi-info-circle text-info fs-2 d-block mb-2"></i>
                                <strong>Info</strong>
                                <div class="h4 text-info">{{ $stats['par_type']['info'] ?? 0 }}</div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="p-3 bg-success bg-opacity-10 rounded">
                                <i class="bi bi-check-circle text-success fs-2 d-block mb-2"></i>
                                <strong>Success</strong>
                                <div class="h4 text-success">{{ $stats['par_type']['success'] ?? 0 }}</div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="p-3 bg-warning bg-opacity-10 rounded">
                                <i class="bi bi-exclamation-triangle text-warning fs-2 d-block mb-2"></i>
                                <strong>Warning</strong>
                                <div class="h4 text-warning">{{ $stats['par_type']['warning'] ?? 0 }}</div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="p-3 bg-danger bg-opacity-10 rounded">
                                <i class="bi bi-x-circle text-danger fs-2 d-block mb-2"></i>
                                <strong>Error</strong>
                                <div class="h4 text-danger">{{ $stats['par_type']['error'] ?? 0 }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Évolution hebdomadaire -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>Évolution hebdomadaire</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center mb-3">
                        <div class="col-4">
                            <div class="text-muted small">Cette semaine</div>
                            <h4 class="text-primary">{{ $stats['this_week'] ?? 0 }}</h4>
                        </div>
                        <div class="col-4">
                            <div class="text-muted small">Ce mois</div>
                            <h4 class="text-success">{{ $stats['this_month'] ?? 0 }}</h4>
                        </div>
                        <div class="col-4">
                            <div class="text-muted small">Aujourd'hui</div>
                            <h4 class="text-warning">{{ $stats['aujourdhui'] ?? 0 }}</h4>
                        </div>
                    </div>

                    <!-- Barre de progression simple -->
                    @php
                    $maxValue = max($stats['this_week'] ?? 1, $stats['this_month'] ?? 1, $stats['aujourdhui'] ?? 1);
                    $weekPercent = $maxValue > 0 ? round(($stats['this_week'] ?? 0) / $maxValue * 100) : 0;
                    $monthPercent = $maxValue > 0 ? round(($stats['this_month'] ?? 0) / $maxValue * 100) : 0;
                    $todayPercent = $maxValue > 0 ? round(($stats['aujourdhui'] ?? 0) / $maxValue * 100) : 0;
                    @endphp

                    <div class="mb-2">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Semaine</span>
                            <span class="badge bg-primary">{{ $stats['this_week'] ?? 0 }}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $weekPercent }}%"></div>
                        </div>
                    </div>
                    <div class="mb-2">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Mois</span>
                            <span class="badge bg-success">{{ $stats['this_month'] ?? 0 }}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $monthPercent }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>Aujourd'hui</span>
                            <span class="badge bg-warning">{{ $stats['aujourdhui'] ?? 0 }}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $todayPercent }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications par entreprise -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-building me-2"></i>Notifications par entreprise</h5>
                </div>
                <div class="card-body p-0">
                    @if($stats['par_entreprise']->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Entreprise</th>
                                    <th class="text-center">Nombre</th>
                                    <th class="text-end">Pourcentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stats['par_entreprise'] as $item)
                                @php
                                $entreprise = \App\Models\Entreprise::find($item->entreprise_id);
                                $pourcentage = $stats['total'] > 0 ? round(($item->total / $stats['total']) * 100) : 0;
                                @endphp
                                <tr>
                                    <td>
                                        @if($entreprise)
                                        <strong>{{ $entreprise->nom_entreprise }}</strong>
                                        @else
                                        <span class="text-muted">Entreprise #{{ $item->entreprise_id }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary">{{ $item->total }}</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center justify-content-end">
                                            <div class="progress flex-grow-1 me-2" style="height: 6px; width: 50px;">
                                                <div class="progress-bar bg-info" role="progressbar" style="width: {{ $pourcentage }}%"></div>
                                            </div>
                                            <small class="text-muted">{{ $pourcentage }}%</small>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="card-body text-center text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        Aucune donnée disponible
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-lightning me-2"></i>Actions rapides</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.superadmin.notifications.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-list-ul me-1"></i> Voir toutes les notifications
                        </a>
                        <a href="{{ route('admin.superadmin.notifications.create') }}" class="btn btn-outline-success btn-sm">
                            <i class="bi bi-plus-circle me-1"></i> Créer une notification
                        </a>
                        <form action="{{ route('admin.superadmin.notifications.mark-all-read') }}" method="POST" class="d-grid">
                            @csrf
                            <button type="submit" class="btn btn-outline-info btn-sm">
                                <i class="bi bi-check-all me-1"></i> Tout marquer comme lu
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endSection