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
            <p class="text-muted mb-0">Gérez les plans d'abonnement et leurs limites</p>
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
                            <h6 class="card-title">Total Abonnements</h6>
                            <h2 class="mb-0">{{ $stats['total'] }}</h2>
                        </div>
                        <i class="bi bi-credit-card fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Actifs</h6>
                            <h2 class="mb-0">{{ $stats['actifs'] }}</h2>
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
                            <h2 class="mb-0">{{ $stats['en_essai'] }}</h2>
                        </div>
                        <i class="bi bi-hourglass-split fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">MRR (Revenu Mensuel)</h6>
                            <h5 class="mb-0">{{ number_format($stats['mrr'] ?? 0, 0, ',', ' ') }} CFA</h5>
                        </div>
                        <i class="bi bi-cash fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cartes analytics 2e rangée -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted">Churn Rate (mois)</h6>
                            <h3 class="mb-0 text-{{ $stats['churn_rate'] > 5 ? 'danger' : ($stats['churn_rate'] > 2 ? 'warning' : 'success') }}">
                                {{ $stats['churn_rate'] }}%
                            </h3>
                            <small class="text-muted">{{ $stats['resilies_ce_mois'] }} résilié(s) ce mois</small>
                        </div>
                        <i class="bi bi-graph-down-arrow fs-1 text-danger opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted">Alertes Agents</h6>
                            <h3 class="mb-0 text-warning">{{ $stats['total_alertes'] }}</h3>
                            <small class="text-muted">≥ 80% de la limite atteinte</small>
                        </div>
                        <i class="bi bi-people fs-1 text-warning opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-secondary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted">Expirés</h6>
                            <h3 class="mb-0 text-secondary">{{ $stats['expirés'] }}</h3>
                        </div>
                        <i class="bi bi-clock-history fs-1 text-secondary opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted">Entreprises liées</h6>
                            <h3 class="mb-0 text-info">{{ $stats['entreprises_total'] }}</h3>
                        </div>
                        <i class="bi bi-building fs-1 text-info opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Distribution par formule -->
    @if($stats['distribution_formules']->count() > 0)
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-pie-chart me-2"></i>Distribution par Formule</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Formule</th>
                                    <th>Total</th>
                                    <th>Actifs</th>
                                    <th>Taux d'activation</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stats['distribution_formules'] as $formule => $data)
                                <tr>
                                    <td><strong>{{ \App\Models\Abonnement::FORMULES[$formule] ?? $formule }}</strong></td>
                                    <td>{{ $data['total'] }}</td>
                                    <td>{{ $data['actifs'] }}</td>
                                    <td>
                                        @if($data['total'] > 0)
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-success" style="width: {{ ($data['actifs'] / $data['total']) * 100 }}%">
                                                {{ round(($data['actifs'] / $data['total']) * 100) }}%
                                            </div>
                                        </div>
                                        @else
                                        -
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alertes agents -->
        @if($stats['total_alertes'] > 0)
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-warning">
                    <h5 class="mb-0"><i class="bi bi-exclamation-triangle me-2"></i>Alertes Capacité Agents</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($stats['alertes_agents'] as $alerte)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <strong>{{ $alerte['entreprise'] }}</strong>
                                <span class="badge bg-{{ $alerte['pct'] >= 100 ? 'danger' : 'warning' }}">
                                    {{ $alerte['actifs'] }}/{{ $alerte['max'] }}
                                </span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-{{ $alerte['pct'] >= 100 ? 'danger' : 'warning' }}"
                                     style="width: {{ min($alerte['pct'], 100) }}%">
                                </div>
                            </div>
                            <small class="text-muted">{{ $alerte['pct'] }}% de la limite utilisée</small>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    @endif

    <!-- Tableau des abonnements -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Liste des Abonnements</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Formule</th>
                            <th>Description</th>
                            <th>Entreprises</th>
                            <th>Agents Max</th>
                            <th>Montant</th>
                            <th>Début</th>
                            <th>Fin</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($abonnements as $abonnement)
                        <tr>
                            <td>
                                <strong>{{ $abonnement->formule_label }}</strong>
                            </td>
                            <td>
                                <small>{{ $abonnement->description ?? 'Aucune description' }}</small>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $abonnement->entreprises_count ?? 0 }}</span>
                            </td>
                            <td>
                                {{ $abonnement->nombre_agents_max ?: 'Illimité' }}
                            </td>
                            <td>
                                {{ number_format($abonnement->montant_mensuel ?? 0, 0, ',', ' ') }} CFA
                                <small class="text-muted">/mois</small>
                            </td>
                            <td>
                                {{ $abonnement->date_debut?->format('d/m/Y') ?? '-' }}
                            </td>
                            <td>
                                @if($abonnement->date_fin)
                                @if($abonnement->date_fin->isPast())
                                <span class="text-danger">{{ $abonnement->date_fin->format('d/m/Y') }}</span>
                                @elseif($abonnement->date_fin->diffInDays(now()) < 30)
                                    <span class="text-warning">{{ $abonnement->date_fin->format('d/m/Y') }}</span>
                                    @else
                                    {{ $abonnement->date_fin->format('d/m/Y') }}
                                    @endif
                                    @else
                                    -
                                    @endif
                            </td>
                            <td>
                                @if($abonnement->est_en_essai)
                                <span class="badge bg-warning">Essai</span>
                                @elseif($abonnement->est_active && $abonnement->statut === 'actif')
                                <span class="badge bg-success">Actif</span>
                                @elseif($abonnement->statut === 'expire')
                                <span class="badge bg-danger">Expiré</span>
                                @elseif($abonnement->statut === 'suspendu')
                                <span class="badge bg-warning">Suspendu</span>
                                @elseif($abonnement->statut === 'resilie')
                                <span class="badge bg-secondary">Résilié</span>
                                @else
                                <span class="badge bg-secondary">{{ $abonnement->statut_label ?? 'Inactif' }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.superadmin.abonnements.show', $abonnement->id) }}" class="btn btn-sm btn-outline-info" title="Voir">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.superadmin.abonnements.edit', $abonnement->id) }}" class="btn btn-sm btn-outline-primary" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if($abonnement->est_active)
                                    <form action="{{ route('admin.superadmin.abonnements.suspend', $abonnement->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-warning" title="Suspendre">
                                            <i class="bi bi-pause-circle"></i>
                                        </button>
                                    </form>
                                    @else
                                    <form action="{{ route('admin.superadmin.abonnements.activate', $abonnement->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success" title="Activer">
                                            <i class="bi bi-play-circle"></i>
                                        </button>
                                    </form>
                                    @endif
                                    <form action="{{ route('admin.superadmin.abonnements.destroy', $abonnement->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet abonnement?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">
                                Aucun abonnement trouvé.
                                <a href="{{ route('admin.superadmin.abonnements.create') }}">Créer un abonnement</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
