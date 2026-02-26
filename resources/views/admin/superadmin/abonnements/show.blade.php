@extends('layouts.app')

@section('title', 'Détails de l\'Abonnement - ' . ($entreprise->nom_entreprise ?? $entreprise->nom))

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0">
                    <i class="bi bi-credit-card-2-front-fill me-2 text-success"></i>
                    Détails de l'Abonnement
                </h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.abonnements.index') }}">Abonnements</a></li>
                    <li class="breadcrumb-item active">{{ $entreprise->nom_entreprise ?? $entreprise->nom }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-building me-2"></i>
                            {{ $entreprise->nom_entreprise ?? $entreprise->nom }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <div class="avatar-lg mx-auto mb-3" style="background: rgba(25, 135, 84, 0.1); width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                                <span class="fs-2 text-success">{{ substr($entreprise->nom_entreprise ?? $entreprise->nom ?? 'EN', 0, 2) }}</span>
                            </div>
                            <h5>{{ $entreprise->nom_entreprise ?? $entreprise->nom }}</h5>
                            <span class="badge bg-{{ $entreprise->formule == 'premium' ? 'purple' : ($entreprise->formule == 'standard' ? 'info' : ($entreprise->formule == 'basic' ? 'warning' : 'secondary')) }}">
                                {{ ucfirst($entreprise->formule ?? 'Standard') }}
                            </span>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label class="text-muted small">Email</label>
                            <div>{{ $entreprise->email }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small">Téléphone</label>
                            <div>{{ $entreprise->telephone ?? '-' }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small">Statut</label>
                            <div>
                                @if($entreprise->est_active)
                                <span class="badge bg-success">Actif</span>
                                @elseif($entreprise->est_en_essai)
                                <span class="badge bg-warning">En essai</span>
                                @else
                                <span class="badge bg-danger">Inactif</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('admin.superadmin.abonnements.edit', $entreprise->id) }}" class="btn btn-primary w-100">
                            <i class="bi bi-pencil me-1"></i> Modifier l'abonnement
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <i class="bi bi-people fs-1 mb-2 d-block opacity-75"></i>
                                <h3>{{ $entreprise->employes->count() }}</h3>
                                <small>Agents</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <i class="bi bi-person-vcard fs-1 mb-2 d-block opacity-75"></i>
                                <h3>{{ $entreprise->clients->count() }}</h3>
                                <small>Clients</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-warning text-dark">
                            <div class="card-body text-center">
                                <i class="bi bi-file-earmark-text fs-1 mb-2 d-block opacity-75"></i>
                                <h3>{{ $entreprise->contratsPrestation->count() }}</h3>
                                <small>Contrats</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-credit-card-2-front me-2"></i>Détails de l'Abonnement</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td class="text-muted" style="width: 40%;">Formule</td>
                                <td>
                                    <span class="badge bg-{{ $entreprise->formule == 'premium' ? 'purple' : ($entreprise->formule == 'standard' ? 'info' : ($entreprise->formule == 'basic' ? 'warning' : 'secondary')) }}">
                                        {{ ucfirst($entreprise->formule ?? 'Standard') }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Nombre d'agents maximum</td>
                                <td>
                                    @if($entreprise->nombre_agents_max)
                                    {{ $entreprise->nombre_agents_max }} agents
                                    @else
                                    <span class="text-success">Illimité</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Nombre de sites maximum</td>
                                <td>
                                    @if($entreprise->nombre_sites_max)
                                    {{ $entreprise->nombre_sites_max }} sites
                                    @else
                                    <span class="text-success">Illimité</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Montant mensuel</td>
                                <td class="fw-bold">{{ number_format($entreprise->montant_mensuel ?? 0, 0, ',', ' ') }} CFA</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Date de début</td>
                                <td>{{ $entreprise->date_debut_contrat?->format('d/m/Y') ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Date de fin</td>
                                <td>
                                    @if($entreprise->date_fin_contrat)
                                    @if($entreprise->date_fin_contrat->isPast())
                                    <span class="text-danger">{{ $entreprise->date_fin_contrat->format('d/m/Y') }} (Expiré)</span>
                                    @elseif($entreprise->date_fin_contrat->diffInDays(now()) < 30)
                                        <span class="text-warning">{{ $entreprise->date_fin_contrat->format('d/m/Y') }} (Expire bientôt)</span>
                                        @else
                                        {{ $entreprise->date_fin_contrat->format('d/m/Y') }}
                                        @endif
                                        @else
                                        -
                                        @endif
                                </td>
                            </tr>
                        </table>

                        @if($entreprise->nombre_agents_max)
                        <div class="mt-4">
                            <label class="text-muted small mb-2">Utilisation des agents</label>
                            @php
                            $percentage = min(($entreprise->employes->count() / $entreprise->nombre_agents_max) * 100, 100);
                            $colorClass = $percentage >= 90 ? 'bg-danger' : ($percentage >= 70 ? 'bg-warning' : 'bg-success');
                            @endphp
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar {{ $colorClass }}" role="progressbar" style="width: {{ $percentage }}%">
                                    {{ $entreprise->employes->count() }} / {{ $entreprise->nombre_agents_max }}
                                </div>
                            </div>
                            @if($entreprise->employes->count() >= $entreprise->nombre_agents_max)
                            <small class="text-danger"><i class="bi bi-exclamation-triangle"></i> Limite d'agents atteinte!</small>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-receipt me-2"></i>Historique des Factures</h5>
                    </div>
                    <div class="card-body">
                        @if($entreprise->factures->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>N° Facture</th>
                                        <th>Date</th>
                                        <th>Montant</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($entreprise->factures->take(5) as $facture)
                                    <tr>
                                        <td>{{ $facture->numero_facture ?? $facture->id }}</td>
                                        <td>{{ $facture->created_at->format('d/m/Y') }}</td>
                                        <td>{{ number_format($facture->montant ?? 0, 0, ',', ' ') }} CFA</td>
                                        <td>
                                            @if($facture->statut == 'payee')
                                            <span class="badge bg-success">Payée</span>
                                            @elseif($facture->statut == 'en_attente')
                                            <span class="badge bg-warning">En attente</span>
                                            @else
                                            <span class="badge bg-danger">Impayée</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <p class="text-muted text-center mb-0">Aucune facture trouvée</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection