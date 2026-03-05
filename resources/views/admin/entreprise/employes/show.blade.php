@extends('layouts.app')

@section('title', 'Détails de l\'employé - Entreprise')

@push('styles')
<style>
    .profile-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .avatar-xl {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        font-weight: 600;
    }

    .info-item {
        padding: 12px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 600;
        color: #6c757d;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-value {
        color: #212529;
        margin-top: 4px;
    }

    .badge-categorie {
        font-size: 12px;
        padding: 6px 12px;
        border-radius: 20px;
    }

    .badge-direction {
        background: #6f42c1;
        color: white;
    }

    .badge-supervision {
        background: #0d6efd;
        color: white;
    }

    .badge-controle {
        background: #fd7e14;
        color: white;
    }

    .badge-agent {
        background: #198754;
        color: white;
    }

    .stat-card {
        border-radius: 12px;
        padding: 15px;
        text-align: center;
    }

    .nav-tabs-custom {
        border-bottom: 2px solid #e9ecef;
    }

    .nav-tabs-custom .nav-link {
        border: none;
        color: #6c757d;
        padding: 12px 20px;
        font-weight: 500;
    }

    .nav-tabs-custom .nav-link:hover {
        color: #198754;
    }

    .nav-tabs-custom .nav-link.active {
        color: #198754;
        background: white;
        border-bottom: 2px solid #198754;
    }

    .timeline-item {
        position: relative;
        padding-left: 30px;
        padding-bottom: 20px;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: 8px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }

    .timeline-item:last-child {
        padding-bottom: 0;
    }

    .timeline-dot {
        position: absolute;
        left: 0;
        top: 0;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #198754;
        border: 3px solid white;
    }
</style>
@endpush

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0">
                    <i class="bi bi-person me-2 text-success"></i>
                    Fiche Employé
                </h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.entreprise.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.entreprise.employes.index') }}">Employés</a></li>
                    <li class="breadcrumb-item active">Détails</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">

        {{-- Header Card --}}
        <div class="card profile-card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="avatar-xl bg-{{ $employe->categorie == 'direction' ? 'purple' : ($employe->categorie == 'supervision' ? 'primary' : ($employe->categorie == 'controle' ? 'warning' : 'success')) }} text-white">
                            {{ strtoupper(substr($employe->prenoms ?? 'N', 0, 1)) }}{{ strtoupper(substr($employe->nom ?? 'A', 0, 1)) }}
                        </div>
                    </div>
                    <div class="col">
                        <h3 class="mb-1">{{ $employe->prenoms }} {{ $employe->nom }}</h3>
                        <div class="mb-2">
                            <span class="badge bg-secondary me-2">{{ $employe->matricule ?? 'N/A' }}</span>
                            <span class="badge badge-categorie badge-{{ $employe->categorie }}">
                                @switch($employe->categorie)
                                @case('direction') Direction @break
                                @case('supervision') Supervision @break
                                @case('controle') Contrôle @break
                                @case('agent') Agent @break
                                @default {{ $employe->categorie }}
                                @endswitch
                            </span>
                        </div>
                        <div class="text-muted">
                            <i class="bi bi-briefcase me-1"></i>
                            {{ $employe->poste ?? 'Non défini' }}
                            @if($employe->departement)
                            <span class="mx-2">|</span>
                            <i class="bi bi-building me-1"></i>
                            {{ $employe->departement }}
                            @endif
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.entreprise.employes.edit', $employe->id) }}" class="btn btn-warning">
                                <i class="bi bi-pencil me-1"></i> Modifier
                            </a>
                            <form action="{{ route('admin.entreprise.employes.destroy', $employe->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet employé ?');">
                                    <i class="bi bi-trash me-1"></i> Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats Row --}}
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card bg-light">
                    <div class="text-muted small">Affectations</div>
                    <div class="fs-3 fw-bold text-success">{{ $employe->affectations->count() }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card bg-light">
                    <div class="text-muted small">Pointages</div>
                    <div class="fs-3 fw-bold text-primary">{{ $employe->pointages->count() }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card bg-light">
                    <div class="text-muted small">Congés</div>
                    <div class="fs-3 fw-bold text-warning">{{ $employe->conges->count() }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card bg-light">
                    <div class="text-muted small">Statut</div>
                    <div class="fs-6 fw-bold">
                        @switch($employe->statut)
                        @case('en_poste')
                        <span class="badge bg-success">En poste</span>
                        @break
                        @case('conge')
                        <span class="badge bg-warning">En congé</span>
                        @break
                        @case('suspendu')
                        <span class="badge bg-danger">Suspendu</span>
                        @break
                        @case('licencie')
                        <span class="badge bg-secondary">Licencié</span>
                        @break
                        @default
                        <span class="badge bg-secondary">{{ $employe->statut }}</span>
                        @endswitch
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabs --}}
        <ul class="nav nav-tabs-custom mb-4" id="employeTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="infos-tab" data-bs-toggle="tab" data-bs-target="#infos" type="button">
                    <i class="bi bi-person me-1"></i> Informations
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="affectations-tab" data-bs-toggle="tab" data-bs-target="#affectations" type="button">
                    <i class="bi bi-calendar-check me-1"></i> Affectations
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pointages-tab" data-bs-toggle="tab" data-bs-target="#pointages" type="button">
                    <i class="bi bi-clock me-1"></i> Pointages
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="conges-tab" data-bs-toggle="tab" data-bs-target="#conges" type="button">
                    <i class="bi bi-calendar-minus me-1"></i> Congés
                </button>
            </li>
        </ul>

        <div class="tab-content" id="employeTabsContent">
            {{-- Tab 1: Informations --}}
            <div class="tab-pane fade show active" id="infos" role="tabpanel">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card profile-card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="bi bi-person me-2"></i>Informations personnelles</h6>
                            </div>
                            <div class="card-body">
                                <div class="info-item">
                                    <div class="info-label">Civilité</div>
                                    <div class="info-value">
                                        @switch($employe->civilite)
                                        @case('M') Monsieur @break
                                        @case('Mme') Madame @break
                                        @case('Mlle') Mademoiselle @break
                                        @default {{ $employe->civilite }}
                                        @endswitch
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Nom complet</div>
                                    <div class="info-value">{{ $employe->prenoms }} {{ $employe->nom }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Email</div>
                                    <div class="info-value">{{ $employe->email }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Téléphone</div>
                                    <div class="info-value">{{ $employe->telephone ?? 'Non défini' }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Adresse</div>
                                    <div class="info-value">{{ $employe->adresse ?? 'Non définie' }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Date de naissance</div>
                                    <div class="info-value">
                                        {{ $employe->date_naissance ? $employe->date_naissance->format('d/m/Y') : 'Non définie' }}
                                        @if($employe->lieu_naissance)
                                        <span class="text-muted"> à {{ $employe->lieu_naissance }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card profile-card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="bi bi-briefcase me-2"></i>Informations professionnelles</h6>
                            </div>
                            <div class="card-body">
                                <div class="info-item">
                                    <div class="info-label">Matricule</div>
                                    <div class="info-value">{{ $employe->matricule ?? 'N/A' }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Poste</div>
                                    <div class="info-value">{{ $employe->poste ?? 'Non défini' }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Catégorie</div>
                                    <div class="info-value">
                                        <span class="badge badge-categorie badge-{{ $employe->categorie }}">
                                            @switch($employe->categorie)
                                            @case('direction') Direction @break
                                            @case('supervision') Supervision @break
                                            @case('controle') Contrôle @break
                                            @case('agent') Agent @break
                                            @endswitch
                                        </span>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Type de contrat</div>
                                    <div class="info-value">
                                        @switch($employe->type_contrat)
                                        @case('cdi') CDI @break
                                        @case('cdd') CDD @break
                                        @case('stage') Stage @break
                                        @case('prestation') Prestation @break
                                        @default {{ $employe->type_contrat }}
                                        @endswitch
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Date d'embauche</div>
                                    <div class="info-value">
                                        {{ $employe->date_embauche ? $employe->date_embauche->format('d/m/Y') : 'Non définie' }}
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Salaire de base</div>
                                    <div class="info-value">
                                        {{ $employe->salaire_base ? number_format($employe->salaire_base, 0, ',', ' ') . ' FCA' : 'Non défini' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card profile-card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="bi bi-telephone me-2"></i>Contact d'urgence</h6>
                            </div>
                            <div class="card-body">
                                <div class="info-item">
                                    <div class="info-label">Nom</div>
                                    <div class="info-value">{{ $employe->contact_urgence_nom ?? 'Non défini' }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Téléphone</div>
                                    <div class="info-value">{{ $employe->contact_urgence_tel ?? 'Non défini' }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Lien de parenté</div>
                                    <div class="info-value">
                                        @switch($employe->contact_urgence_lien)
                                        @case('conjoint') Conjoint(e) @break
                                        @case('parent') Parent @break
                                        @case('frere') Frère/Sœur @break
                                        @case('ami') Ami(e) @break
                                        @case('autre') Autre @break
                                        @default Non défini
                                        @endswitch
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab 2: Affectations --}}
            <div class="tab-pane fade" id="affectations" role="tabpanel">
                <div class="card profile-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><i class="bi bi-calendar-check me-2"></i>Affectations</h6>
                        <span class="badge bg-primary">{{ $employe->affectations->count() }}</span>
                    </div>
                    <div class="card-body">
                        @forelse($employe->affectations as $affectation)
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="fw-semibold">{{ $affectation->siteClient->nom_site ?? 'Site N/A' }}</div>
                            <div class="text-muted small">
                                {{ $affectation->contratPrestation->client->nom ?? 'Client N/A' }}
                                <span class="mx-2">|</span>
                                {{ $affectation->heure_debut ?? 'N/A' }} - {{ $affectation->heure_fin ?? 'N/A' }}
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-calendar-x fs-1 d-block mb-2"></i>
                            Aucune affectation trouvée
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Tab 3: Pointages --}}
            <div class="tab-pane fade" id="pointages" role="tabpanel">
                <div class="card profile-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><i class="bi bi-clock me-2"></i>Historique des pointages</h6>
                        <span class="badge bg-primary">{{ $employe->pointages->count() }}</span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Entrée</th>
                                        <th>Sortie</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($employe->pointages->take(10) as $pointage)
                                    <tr>
                                        <td>{{ $pointage->date_pointage->format('d/m/Y') }}</td>
                                        <td>{{ $pointage->heure_entree ?? 'N/A' }}</td>
                                        <td>{{ $pointage->heure_sortie ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $pointage->statut == 'complet' ? 'success' : 'warning' }}">
                                                {{ $pointage->statut }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            Aucun pointage trouvé
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab 4: Congés --}}
            <div class="tab-pane fade" id="conges" role="tabpanel">
                <div class="card profile-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><i class="bi bi-calendar-minus me-2"></i>Historique des congés</h6>
                        <span class="badge bg-primary">{{ $employe->conges->count() }}</span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Début</th>
                                        <th>Fin</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($employe->conges as $conge)
                                    <tr>
                                        <td>
                                            @switch($conge->type_conge)
                                            @case('annuel') Congé annuel @break
                                            @case('maladie') Maladie @break
                                            @case('maternite') Maternité @break
                                            @case('paternite') Paternité @break
                                            @case('sans_solde') Sans solde @break
                                            @default {{ $conge->type_conge }}
                                            @endswitch
                                        </td>
                                        <td>{{ $conge->date_debut->format('d/m/Y') }}</td>
                                        <td>{{ $conge->date_fin->format('d/m/Y') }}</td>
                                        <td>
                                            @switch($conge->statut)
                                            @case('approuve')
                                            <span class="badge bg-success">Approuvé</span>
                                            @break
                                            @case('en_attente')
                                            <span class="badge bg-warning">En attente</span>
                                            @break
                                            @case('rejete')
                                            <span class="badge bg-danger">Rejeté</span>
                                            @break
                                            @default
                                            <span class="badge bg-secondary">{{ $conge->statut }}</span>
                                            @endswitch
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            Aucun congés trouvé
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection