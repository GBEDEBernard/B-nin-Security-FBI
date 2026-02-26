@extends('layouts.app')

@section('title', 'Détails de l\'Abonnement - ' . ($abonnement->formule_label ?? 'Abonnement'))

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
                    <li class="breadcrumb-item active">{{ $abonnement->formule_label }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <!-- Colonne gauche: Infos abonnement -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-credit-card me-2"></i>
                            {{ $abonnement->formule_label }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <div class="avatar-lg mx-auto mb-3" style="background: rgba(25, 135, 84, 0.1); width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                                <span class="fs-2 text-success">{{ strtoupper(substr($abonnement->formule, 0, 2)) }}</span>
                            </div>
                            <h5>{{ $abonnement->formule_label }}</h5>
                            <span class="badge bg-{{ $abonnement->formule == 'premium' ? 'purple' : ($abonnement->formule == 'standard' ? 'info' : ($abonnement->formule == 'basic' ? 'warning' : 'secondary')) }}">
                                {{ $abonnement->formule_label }}
                            </span>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label class="text-muted small">Statut</label>
                            <div>
                                @if($abonnement->est_en_essai)
                                <span class="badge bg-warning">En essai</span>
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
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small">Entreprises liées</label>
                            <div class="fw-bold">{{ $abonnement->entreprises_count ?? 0 }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small">Montant mensuel</label>
                            <div class="fw-bold">{{ number_format($abonnement->montant_mensuel ?? 0, 0, ',', ' ') }} CFA</div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('admin.superadmin.abonnements.edit', $abonnement->id) }}" class="btn btn-primary w-100 mb-2">
                            <i class="bi bi-pencil me-1"></i> Modifier l'abonnement
                        </a>

                        @if($abonnement->est_active)
                        <form action="{{ route('admin.superadmin.abonnements.suspend', $abonnement->id) }}" method="POST" class="d-inline w-100">
                            @csrf
                            <button type="submit" class="btn btn-warning w-100">
                                <i class="bi bi-pause-circle me-1"></i> Suspendre
                            </button>
                        </form>
                        @else
                        <form action="{{ route('admin.superadmin.abonnements.activate', $abonnement->id) }}" method="POST" class="d-inline w-100">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-play-circle me-1"></i> Activer
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Colonne droite: Détails -->
            <div class="col-md-8">
                <!-- Cartes de stats -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <i class="bi bi-building fs-1 mb-2 d-block opacity-75"></i>
                                <h3>{{ $abonnement->entreprises_count ?? 0 }}</h3>
                                <small>Entreprises</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <i class="bi bi-people fs-1 mb-2 d-block opacity-75"></i>
                                <h3>{{ $abonnement->nombre_agents_max ?? '∞' }}</h3>
                                <small>Agents Max</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-warning text-dark">
                            <div class="card-body text-center">
                                <i class="bi bi-calendar fs-1 mb-2 d-block opacity-75"></i>
                                <h3>{{ $abonnement->jours_restants ?? '∞' }}</h3>
                                <small>Jours restants</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Détails de l'abonnement -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Détails de l'Abonnement</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td class="text-muted" style="width: 40%;">Formule</td>
                                <td>
                                    <span class="badge bg-{{ $abonnement->formule == 'premium' ? 'purple' : ($abonnement->formule == 'standard' ? 'info' : ($abonnement->formule == 'basic' ? 'warning' : 'secondary')) }}">
                                        {{ $abonnement->formule_label }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Description</td>
                                <td>{{ $abonnement->description ?? 'Aucune description' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Nombre d'agents maximum</td>
                                <td>
                                    @if($abonnement->nombre_agents_max)
                                    {{ $abonnement->nombre_agents_max }} agents
                                    @else
                                    <span class="text-success">Illimité</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Nombre de sites maximum</td>
                                <td>
                                    @if($abonnement->nombre_sites_max)
                                    {{ $abonnement->nombre_sites_max }} sites
                                    @else
                                    <span class="text-success">Illimité</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Limite utilisateurs</td>
                                <td>{{ $abonnement->limite_utilisateurs ?? 'Non défini' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Cycle de facturation</td>
                                <td>{{ $abonnement->cycle_label ?? 'Mensuel' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Montant mensuel</td>
                                <td class="fw-bold">{{ number_format($abonnement->montant_mensuel ?? 0, 0, ',', ' ') }} CFA</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Montant période</td>
                                <td class="fw-bold">{{ number_format($abonnement->montant_periode ?? 0, 0, ',', ' ') }} CFA</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Date de début</td>
                                <td>{{ $abonnement->date_debut?->format('d/m/Y') ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Date de fin</td>
                                <td>
                                    @if($abonnement->date_fin)
                                    @if($abonnement->date_fin->isPast())
                                    <span class="text-danger">{{ $abonnement->date_fin->format('d/m/Y') }} (Expiré)</span>
                                    @elseif($abonnement->date_fin->diffInDays(now()) < 30)
                                        <span class="text-warning">{{ $abonnement->date_fin->format('d/m/Y') }} (Expire bientôt)</span>
                                        @else
                                        {{ $abonnement->date_fin->format('d/m/Y') }}
                                        @endif
                                        @else
                                        -
                                        @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Fin de l'essai</td>
                                <td>{{ $abonnement->date_fin_essai?->format('d/m/Y') ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Mode de paiement</td>
                                <td>{{ $abonnement->mode_paiement ?? 'Non défini' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Renouvellement auto</td>
                                <td>
                                    @if($abonnement->est_renouvele_auto)
                                    <span class="badge bg-success">Oui</span>
                                    @else
                                    <span class="badge bg-secondary">Non</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Entreprises liées -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-building me-2"></i>Entreprises liées à cet abonnement</h5>
                    </div>
                    <div class="card-body">
                        @if($abonnement->entreprises->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Entreprise</th>
                                        <th>Email</th>
                                        <th>Téléphone</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($abonnement->entreprises as $entreprise)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.superadmin.entreprises.show', $entreprise->id) }}">
                                                {{ $entreprise->nom_entreprise }}
                                            </a>
                                        </td>
                                        <td>{{ $entreprise->email }}</td>
                                        <td>{{ $entreprise->telephone ?? '-' }}</td>
                                        <td>
                                            @if($entreprise->est_active)
                                            <span class="badge bg-success">Actif</span>
                                            @else
                                            <span class="badge bg-danger">Inactif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.superadmin.abonnements.retirer', ['id' => $abonnement->id, 'entrepriseId' => $entreprise->id]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Retirer l'abonnement">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <p class="text-muted text-center mb-0">
                            Aucune entreprise n'est liée à cet abonnement.
                        </p>
                        @endif

                        <!-- Formulaire pour assigner une entreprise -->
                        <div class="mt-3 border-top pt-3">
                            <form action="{{ route('admin.superadmin.abonnements.assigner', $abonnement->id) }}" method="POST" class="d-flex gap-2">
                                @csrf
                                <select class="form-select" name="entreprise_id" required>
                                    <option value="">Sélectionner une entreprise...</option>
                                    @foreach(\App\Models\Entreprise::whereNull('abonnement_id')->orWhere('abonnement_id', $abonnement->id)->get() as $entreprise)
                                    <option value="{{ $entreprise->id }}">{{ $entreprise->nom_entreprise }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-plus-lg"></i> Assigner
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Factures liées -->
                @if($abonnement->factures->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-receipt me-2"></i>Historique des Factures</h5>
                    </div>
                    <div class="card-body">
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
                                    @foreach($abonnement->factures->take(5) as $facture)
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
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection