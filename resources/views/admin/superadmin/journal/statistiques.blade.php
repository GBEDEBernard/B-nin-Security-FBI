@extends('layouts.app')

@section('title', 'Statistiques du Journal - Super Admin')

@section('content')
<div class="container-fluid p-4">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">
                <i class="bi bi-bar-chart text-info me-2"></i>
                Statistiques du Journal d'Activité
            </h2>
            <p class="text-muted mb-0">Vue d'ensemble et analyses des activités du système</p>
        </div>
        <a href="{{ route('admin.superadmin.journal.index') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left me-1"></i> Retour au journal
        </a>
    </div>

    <!-- Stats générales -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-1">Total 7 jours</h6>
                            <h2 class="mb-0 text-primary">{{ $stats['total_7_jours'] ?? 0 }}</h2>
                        </div>
                        <i class="bi bi-clock-history fs-1 text-primary opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-1">Connexions 7 jours</h6>
                            <h2 class="mb-0 text-success">{{ $stats['connexions_7_jours'] ?? 0 }}</h2>
                        </div>
                        <i class="bi bi-person-check fs-1 text-success opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-1">Moyenne/Jour</h6>
                            <h2 class="mb-0 text-warning">{{ round(($stats['total_7_jours'] ?? 0) / 7) }}</h2>
                        </div>
                        <i class="bi bi-calculator fs-1 text-warning opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-1">Utilisateurs actifs</h6>
                            <h2 class="mb-0 text-info">{{ $topUsers->count() }}</h2>
                        </div>
                        <i class="bi bi-people fs-1 text-info opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Évolution quotidienne -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>Évolution des activités (7 derniers jours)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Connexions</th>
                                    <th class="text-center">Actions</th>
                                    <th class="text-center">Tendance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($semaine as $jour)
                                <tr>
                                    <td>
                                        <strong>{{ $jour['date'] }}</strong>
                                        <br>
                                        <small class="text-muted">{{ \Carbon\Carbon::createFromFormat('d/m', $jour['date'])->format('l') }}</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary fs-6">{{ $jour['actions'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success">{{ $jour['connexions'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="progress" style="height: 20px; width: 100px;">
                                            @php
                                            $maxActions = max(array_column($semaine, 'actions'));
                                            $percentage = $maxActions > 0 ? ($jour['actions'] / $maxActions) * 100 : 0;
                                            @endphp
                                            <div class="progress-bar bg-info" role="progressbar" style="width: {{ $percentage }}%">
                                            </div>
                                        </div>
                                        <small class="text-muted">{{ $jour['actions'] - $jour['connexions'] }}</small>
                                    </td>
                                    <td class="text-center">
                                        @if($loop->first)
                                        <i class="bi bi-dash text-muted"></i>
                                        @else
                                        @php
                                        $prevJour = $semaine[$loop->index - 1];
                                        $diff = $jour['actions'] - $prevJour['actions'];
                                        @endphp
                                        @if($diff > 0)
                                        <i class="bi bi-arrow-up text-success"></i>
                                        @elseif($diff < 0)
                                            <i class="bi bi-arrow-down text-danger"></i>
                                            @else
                                            <i class="bi bi-dash text-muted"></i>
                                            @endif
                                            @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        Aucune donnée disponible
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Graphique visuel simple -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-bar-chart-steps me-2"></i>Représentation graphique</h5>
                </div>
                <div class="card-body">
                    @php
                    $maxActions = max(array_column($semaine, 'actions'));
                    @endphp
                    <div class="d-flex align-items-end justify-content-between" style="height: 200px;">
                        @foreach($semaine as $jour)
                        @php
                        $height = $maxActions > 0 ? ($jour['actions'] / $maxActions) * 100 : 0;
                        @endphp
                        <div class="text-center flex-fill">
                            <div class="bg-primary rounded" style="height: {{ $height }}%; width: 80%; margin: 0 auto;"></div>
                            <div class="mt-2 small fw-bold">{{ $jour['date'] }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Top utilisateurs -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-trophy text-warning me-2"></i>Top Utilisateurs</h5>
                </div>
                <div class="card-body p-0">
                    @if($topUsers->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($topUsers as $index => $user)
                        @php
                        $userModel = \App\Models\User::find($user->causer_id);
                        @endphp
                        <div class="list-group-item">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    @if($index === 0)
                                    <i class="bi bi-trophy-fill text-warning fs-5"></i>
                                    @elseif($index === 1)
                                    <i class="bi bi-trophy text-secondary fs-5"></i>
                                    @elseif($index === 2)
                                    <i class="bi bi-trophy text-brown fs-5" style="color: #cd7f32;"></i>
                                    @else
                                    <span class="badge bg-secondary">{{ $index + 1 }}</span>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    @if($userModel)
                                    <div class="fw-bold">{{ $userModel->name }}</div>
                                    <small class="text-muted">{{ $userModel->email }}</small>
                                    @else
                                    <div class="fw-bold">Utilisateur #{{ $user->causer_id }}</div>
                                    <small class="text-muted">Compte supprimé</small>
                                    @endif
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-primary">{{ $user->total }}</span>
                                    <small class="text-muted d-block">actions</small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="card-body text-center text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        Aucune donnée disponible
                    </div>
                    @endif
                </div>
            </div>

            <!-- Répartition par type d'action -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-pie-chart me-2"></i>Répartition par type</h5>
                </div>
                <div class="card-body">
                    @php
                    $total = max(1, $stats['total_7_jours'] ?? 1);
                    $connexions = $stats['connexions_7_jours'] ?? 0;
                    $autres = $total - $connexions;
                    $pourcentageConnexions = $total > 0 ? round(($connexions / $total) * 100) : 0;
                    $pourcentageAutres = $total > 0 ? round(($autres / $total) * 100) : 0;
                    @endphp

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span><i class="bi bi-person-check text-success me-1"></i> Connexions</span>
                            <span class="fw-bold">{{ $pourcentageConnexions }}%</span>
                        </div>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $pourcentageConnexions }}%"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span><i class="bi bi-gear text-primary me-1"></i> Autres actions</span>
                            <span class="fw-bold">{{ $pourcentageAutres }}%</span>
                        </div>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $pourcentageAutres }}%"></div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <div class="row">
                            <div class="col-6">
                                <div class="text-muted small">Connexions</div>
                                <h4 class="text-success mb-0">{{ $connexions }}</h4>
                            </div>
                            <div class="col-6">
                                <div class="text-muted small">Autres</div>
                                <h4 class="text-primary mb-0">{{ $autres }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-lightning me-2"></i>Actions rapides</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.superadmin.journal.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-list-ul me-1"></i> Voir toutes les activités
                        </a>
                        <a href="{{ route('admin.superadmin.journal.par-utilisateur') }}" class="btn btn-outline-info btn-sm">
                            <i class="bi bi-person me-1"></i> Activités par utilisateur
                        </a>
                        <a href="{{ route('admin.superadmin.journal.par-module') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-grid me-1"></i> Activités par module
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endSection