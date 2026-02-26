@extends('layouts.app')

@section('title', 'Gestion des Abonnements - Super Admin')

@section('content')
<div class="container-fluid p-4">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">
                <i class="bi bi-credit-card-2-front-fill text-primary me-2"></i>
                Gestion des Abonnements
            </h2>
            <p class="text-muted mb-0">Gérez les abonnements et limitez le nombre d'agents par entreprise</p>
        </div>
        <a href="{{ route('admin.superadmin.abonnements.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Nouvel Abonnement
        </a>
    </div>

    <!-- Cartes de statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Total Entreprises</h6>
                            <h2 class="mb-0">{{ $entreprises->count() }}</h2>
                        </div>
                        <i class="bi bi-building fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Actives</h6>
                            <h2 class="mb-0">{{ $entreprises->where('est_active', true)->count() }}</h2>
                        </div>
                        <i class="bi bi-check-circle fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">En Essai</h6>
                            <h2 class="mb-0">{{ $entreprises->where('est_en_essai', true)->count() }}</h2>
                        </div>
                        <i class="bi bi-hourglass-split fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-secondary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Revenu Mensuel</h6>
                            <h5 class="mb-0">{{ number_format($entreprises->sum('montant_mensuel'), 0, ',', ' ') }} CFA</h5>
                        </div>
                        <i class="bi bi-cash fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des entreprises -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Liste des Abonnements</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Entreprise</th>
                            <th>Formule</th>
                            <th>Agents (Actuel / Max)</th>
                            <th>Début</th>
                            <th>Fin</th>
                            <th>Montant</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($entreprises as $entreprise)
                        <tr>
                            <td>
                                <strong>{{ $entreprise->nom_entreprise }}</strong><br>
                                <small class="text-muted">{{ $entreprise->email }}</small>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $entreprise->formule ?? 'Standard' }}</span>
                            </td>
                            <td>
                                @php
                                $employesCount = $entreprise->employes()->count();
                                $maxAgents = $entreprise->nombre_agents_max;
                                $percentage = $maxAgents ? min(($employesCount / $maxAgents) * 100, 100) : 0;
                                $colorClass = $percentage >= 90 ? 'bg-danger' : ($percentage >= 70 ? 'bg-warning' : 'bg-success');
                                @endphp
                                <div class="d-flex align-items-center">
                                    <span class="me-2">{{ $employesCount }} / {{ $maxAgents ?? '∞' }}</span>
                                    @if($maxAgents)
                                    <div class="progress flex-grow-1" style="height: 6px; width: 60px;">
                                        <div class="progress-bar {{ $colorClass }}" role="progressbar"
                                            style="width: {{ $percentage }}%"
                                            aria-valuenow="{{ $percentage }}"
                                            aria-valuemin="0"
                                            aria-valuemax="100">
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                @if($maxAgents && $employesCount >= $maxAgents)
                                <small class="text-danger"><i class="bi bi-exclamation-triangle"></i> Limite atteinte</small>
                                @endif
                            </td>
                            <td>{{ $entreprise->date_debut_contrat?->format('d/m/Y') ?? '-' }}</td>
                            <td>
                                @if($entreprise->date_fin_contrat)
                                @if($entreprise->date_fin_contrat->isPast())
                                <span class="text-danger">{{ $entreprise->date_fin_contrat->format('d/m/Y') }}</span>
                                @elseif($entreprise->date_fin_contrat->diffInDays(now()) < 30)
                                    <span class="text-warning">{{ $entreprise->date_fin_contrat->format('d/m/Y') }}</span>
                                    @else
                                    {{ $entreprise->date_fin_contrat->format('d/m/Y') }}
                                    @endif
                                    @else
                                    -
                                    @endif
                            </td>
                            <td>{{ number_format($entreprise->montant_mensuel ?? 0, 0, ',', ' ') }} CFA</td>
                            <td>
                                @if($entreprise->est_en_essai)
                                <span class="badge bg-warning">Essai</span>
                                @elseif($entreprise->est_active)
                                <span class="badge bg-success">Actif</span>
                                @else
                                <span class="badge bg-danger">Inactif</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.superadmin.abonnements.show', $entreprise->id) }}" class="btn btn-sm btn-outline-info" title="Voir">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.superadmin.abonnements.edit', $entreprise->id) }}" class="btn btn-sm btn-outline-primary" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if($entreprise->est_active)
                                    <form action="{{ route('admin.superadmin.abonnements.suspend', $entreprise->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-warning" title="Suspendre">
                                            <i class="bi bi-pause-circle"></i>
                                        </button>
                                    </form>
                                    @else
                                    <form action="{{ route('admin.superadmin.abonnements.activate', $entreprise->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success" title="Activer">
                                            <i class="bi bi-play-circle"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Aucune entreprise trouvée</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection