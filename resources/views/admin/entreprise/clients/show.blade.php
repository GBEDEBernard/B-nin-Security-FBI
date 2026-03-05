@extends('layouts.app')

@section('title', 'Détails du client - Entreprise')

@push('styles')
<style>
    .profile-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .avatar-xl {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 40px;
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

    .badge-type {
        font-size: 12px;
        padding: 6px 12px;
        border-radius: 20px;
    }

    .badge-particulier {
        background: #6f42c1;
        color: white;
    }

    .badge-entreprise {
        background: #0d6efd;
        color: white;
    }

    .badge-institution {
        background: #fd7e14;
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
</style>
@endpush

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0">
                    <i class="bi bi-person me-2 text-success"></i>
                    Fiche Client
                </h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.entreprise.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.entreprise.clients.index') }}">Clients</a></li>
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
                        <div class="avatar-xl bg-{{ $client->type_client == 'particulier' ? 'purple' : ($client->type_client == 'entreprise' ? 'primary' : 'warning') }} text-white">
                            @if($client->type_client == 'particulier')
                            {{ strtoupper(substr($client->prenoms ?? 'N', 0, 1)) }}{{ strtoupper(substr($client->nom ?? 'A', 0, 1)) }}
                            @else
                            {{ strtoupper(substr($client->raison_sociale ?? 'E', 0, 2)) }}
                            @endif
                        </div>
                    </div>
                    <div class="col">
                        <h3 class="mb-1">{{ $client->nom_affichage }}</h3>
                        <div class="mb-2">
                            <span class="badge badge-type badge-{{ $client->type_client }}">
                                @switch($client->type_client)
                                @case('particulier') Particulier @break
                                @case('entreprise') Entreprise @break
                                @case('institution') Institution @break
                                @endswitch
                            </span>
                            @if($client->est_actif)
                            <span class="badge bg-success ms-2">Actif</span>
                            @else
                            <span class="badge bg-secondary ms-2">Inactif</span>
                            @endif
                        </div>
                        @if($client->type_client != 'particulier' && $client->raison_sociale)
                        <div class="text-muted">
                            <i class="bi bi-building me-1"></i>
                            {{ $client->raison_sociale }}
                        </div>
                        @endif
                    </div>
                    <div class="col-auto">
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.entreprise.clients.edit', $client->id) }}" class="btn btn-warning">
                                <i class="bi bi-pencil me-1"></i> Modifier
                            </a>
                            <form action="{{ route('admin.entreprise.clients.destroy', $client->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce client ?');">
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
                    <div class="text-muted small">Sites</div>
                    <div class="fs-3 fw-bold text-primary">{{ $client->sites->count() }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card bg-light">
                    <div class="text-muted small">Contrats</div>
                    <div class="fs-3 fw-bold text-success">{{ $client->contrats->count() }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card bg-light">
                    <div class="text-muted small">Factures</div>
                    <div class="fs-3 fw-bold text-warning">{{ $client->factures->count() }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card bg-light">
                    <div class="text-muted small">Statut</div>
                    <div class="fs-6 fw-bold">
                        @if($client->est_actif)
                        <span class="badge bg-success">Actif</span>
                        @else
                        <span class="badge bg-secondary">Inactif</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabs --}}
        <ul class="nav nav-tabs-custom mb-4" id="clientTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="infos-tab" data-bs-toggle="tab" data-bs-target="#infos" type="button">
                    <i class="bi bi-person me-1"></i> Informations
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="sites-tab" data-bs-toggle="tab" data-bs-target="#sites" type="button">
                    <i class="bi bi-geo-alt me-1"></i> Sites
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="contrats-tab" data-bs-toggle="tab" data-bs-target="#contrats" type="button">
                    <i class="bi bi-file-earmark-check me-1"></i> Contrats
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="factures-tab" data-bs-toggle="tab" data-bs-target="#factures" type="button">
                    <i class="bi bi-receipt me-1"></i> Factures
                </button>
            </li>
        </ul>

        <div class="tab-content" id="clientTabsContent">
            {{-- Tab 1: Informations --}}
            <div class="tab-pane fade show active" id="infos" role="tabpanel">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card profile-card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="bi bi-person me-2"></i>Informations du client</h6>
                            </div>
                            <div class="card-body">
                                @if($client->type_client == 'particulier')
                                <div class="info-item">
                                    <div class="info-label">Nom complet</div>
                                    <div class="info-value">{{ $client->prenoms }} {{ $client->nom }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Date de naissance</div>
                                    <div class="info-value">
                                        {{ $client->date_naissance ? $client->date_naissance->format('d/m/Y') : 'Non définie' }}
                                    </div>
                                </div>
                                @else
                                <div class="info-item">
                                    <div class="info-label">Raison sociale</div>
                                    <div class="info-value">{{ $client->raison_sociale ?? 'Non définie' }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">NIF</div>
                                    <div class="info-value">{{ $client->nif ?? 'N/A' }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">RCCM</div>
                                    <div class="info-value">{{ $client->rc ?? 'N/A' }}</div>
                                </div>
                                @endif

                                <div class="info-item">
                                    <div class="info-label">Email</div>
                                    <div class="info-value">{{ $client->email }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Téléphone</div>
                                    <div class="info-value">{{ $client->telephone ?? 'Non défini' }}</div>
                                </div>
                                @if($client->telephone_secondaire)
                                <div class="info-item">
                                    <div class="info-label">Téléphone secondaire</div>
                                    <div class="info-value">{{ $client->telephone_secondaire }}</div>
                                </div>
                                @endif
                                <div class="info-item">
                                    <div class="info-label">Adresse</div>
                                    <div class="info-value">
                                        {{ $client->adresse ?? 'Non définie' }}
                                        @if($client->ville)
                                        , {{ $client->ville }}
                                        @endif
                                        @if($client->pays)
                                        , {{ $client->pays }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        @if($client->type_client != 'particulier')
                        <div class="card profile-card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="bi bi-person-badge me-2"></i>Représentant légal</h6>
                            </div>
                            <div class="card-body">
                                <div class="info-item">
                                    <div class="info-label">Nom</div>
                                    <div class="info-value">{{ $client->representant_nom ?? 'Non défini' }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Prénoms</div>
                                    <div class="info-value">{{ $client->representant_prenom ?? 'Non défini' }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Fonction</div>
                                    <div class="info-value">{{ $client->representant_fonction ?? 'Non définie' }}</div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="card profile-card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="bi bi-person-contact me-2"></i>Contact principal</h6>
                            </div>
                            <div class="card-body">
                                <div class="info-item">
                                    <div class="info-label">Nom</div>
                                    <div class="info-value">{{ $client->contact_principal_nom ?? 'Non défini' }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Fonction</div>
                                    <div class="info-value">{{ $client->contact_principal_fonction ?? 'Non définie' }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Email</div>
                                    <div class="info-value">{{ $client->contact_email ?? 'Non défini' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab 2: Sites --}}
            <div class="tab-pane fade" id="sites" role="tabpanel">
                <div class="card profile-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><i class="bi bi-geo-alt me-2"></i>Sites</h6>
                        <span class="badge bg-primary">{{ $client->sites->count() }}</span>
                    </div>
                    <div class="card-body">
                        @forelse($client->sites as $site)
                        <div class="info-item">
                            <div class="fw-semibold">{{ $site->nom_site }}</div>
                            <div class="text-muted small">
                                {{ $site->adresse ?? 'Adresse non définie' }}
                                @if($site->est_actif)
                                <span class="badge bg-success ms-2">Actif</span>
                                @else
                                <span class="badge bg-secondary ms-2">Inactif</span>
                                @endif
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-geo-alt fs-1 d-block mb-2"></i>
                            Aucun site trouvé
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Tab 3: Contrats --}}
            <div class="tab-pane fade" id="contrats" role="tabpanel">
                <div class="card profile-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><i class="bi bi-file-earmark-check me-2"></i>Contrats</h6>
                        <span class="badge bg-primary">{{ $client->contrats->count() }}</span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>N° Contrat</th>
                                        <th>Date début</th>
                                        <th>Date fin</th>
                                        <th>Montant</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($client->contrats as $contrat)
                                    <tr>
                                        <td>{{ $contrat->numero_contrat ?? 'N/A' }}</td>
                                        <td>{{ $contrat->date_debut ? $contrat->date_debut->format('d/m/Y') : 'N/A' }}</td>
                                        <td>{{ $contrat->date_fin ? $contrat->date_fin->format('d/m/Y') : 'N/A' }}</td>
                                        <td>{{ $contrat->montant ? number_format($contrat->montant, 0, ',', ' ') . ' FCA' : 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $contrat->statut == 'actif' ? 'success' : ($contrat->statut == 'en_cours' ? 'warning' : 'secondary') }}">
                                                {{ $contrat->statut }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            Aucun contrat trouvé
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab 4: Factures --}}
            <div class="tab-pane fade" id="factures" role="tabpanel">
                <div class="card profile-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><i class="bi bi-receipt me-2"></i>Factures</h6>
                        <span class="badge bg-primary">{{ $client->factures->count() }}</span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>N° Facture</th>
                                        <th>Date</th>
                                        <th>Montant</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($client->factures as $facture)
                                    <tr>
                                        <td>{{ $facture->numero_facture ?? 'N/A' }}</td>
                                        <td>{{ $facture->date_facture ? $facture->date_facture->format('d/m/Y') : 'N/A' }}</td>
                                        <td>{{ number_format($facture->montant_total, 0, ',', ' ') }} FCA</td>
                                        <td>
                                            <span class="badge bg-{{ $facture->statut == 'payee' ? 'success' : ($facture->statut == 'en_attente' ? 'warning' : 'danger') }}">
                                                {{ $facture->statut }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            Aucune facture trouvée
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