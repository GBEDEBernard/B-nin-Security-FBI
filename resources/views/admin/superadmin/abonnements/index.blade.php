@extends('layouts.app')

@section('title', 'Gestion des Abonnements - Super Admin')

@section('content')
    <div class="container-fluid p-4">
        <!-- En-tête -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0">
                    <i class="bi bi-file-contract-fill text-primary me-2"></i>
                    Gestion des Abonnements
                </h2>
                <p class="text-muted mb-0">Gérez les abonnements et contrats de toutes les entreprises</p>
            </div>
            <button class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Nouvel Abonnement
            </button>
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
                                <h6 class="card-title">Inactives</h6>
                                <h2 class="mb-0">{{ $entreprises->where('est_active', false)->count() }}</h2>
                            </div>
                            <i class="bi bi-x-circle fs-1 opacity-50"></i>
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
                                <th>Agents Max</th>
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
                                <td>{{ $entreprise->nombre_agents_max ?? 'Illimité' }}</td>
                                <td>{{ $entreprise->date_debut_contrat?->format('d/m/Y') ?? '-' }}</td>
                                <td>{{ $entreprise->date_fin_contrat?->format('d/m/Y') ?? '-' }}</td>
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
                                    <button class="btn btn-sm btn-outline-primary" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-info" title="Voir">
                                        <i class="bi bi-eye"></i>
                                    </button>
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