@extends('layouts.app')

@section('title', 'Sites - Entreprise')

@push('styles')
<style>
    .site-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease;
    }

    .site-card:hover {
        transform: translateY(-5px);
    }

    .stat-card {
        border: none;
        border-radius: 12px;
    }

    .badge-risque {
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-weight: 500;
    }

    .badge-faible {
        background: rgba(25, 135, 84, 0.15);
        color: #198754;
    }

    .badge-moyen {
        background: rgba(255, 193, 7, 0.15);
        color: #ffc107;
    }

    .badge-haut {
        background: rgba(253, 126, 20, 0.15);
        color: #fd7e14;
    }

    .badge-critique {
        background: rgba(220, 53, 69, 0.15);
        color: #dc3545;
    }

    .search-box {
        border-radius: 10px;
        border: 1.5px solid #e9ecef;
        padding: 0.6rem 1rem;
    }

    .btn-action {
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
    }
</style>
@endpush

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0"><i class="bi bi-geo-alt-fill me-2"></i>Sites</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.entreprise.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Sites</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <!-- Statistiques -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="stat-card card bg-primary bg-opacity-10 h-100">
                    <div class="card-body">
                        <h5 class="mb-0">{{ $stats['total'] }}</h5>
                        <small class="text-muted">Total Sites</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card card bg-success bg-opacity-10 h-100">
                    <div class="card-body">
                        <h5 class="mb-0">{{ $stats['actifs'] }}</h5>
                        <small class="text-muted">Actifs</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card card bg-secondary bg-opacity-10 h-100">
                    <div class="card-body">
                        <h5 class="mb-0">{{ $stats['inactifs'] }}</h5>
                        <small class="text-muted">Inactifs</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card card bg-danger bg-opacity-10 h-100">
                    <div class="card-body">
                        <h5 class="mb-0">{{ $stats['haut_risque'] }}</h5>
                        <small class="text-muted">Haut Risque</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="card site-card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control search-box" placeholder="Rechercher..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="client_id" class="form-select">
                            <option value="">Tous les clients</option>
                            @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>{{ $client->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="niveau_risque" class="form-select">
                            <option value="">Tous risques</option>
                            <option value="faible" {{ request('niveau_risque') == 'faible' ? 'selected' : '' }}>Faible</option>
                            <option value="moyen" {{ request('niveau_risque') == 'moyen' ? 'selected' : '' }}>Moyen</option>
                            <option value="haut" {{ request('niveau_risque') == 'haut' ? 'selected' : '' }}>Haut</option>
                            <option value="critique" {{ request('niveau_risque') == 'critique' ? 'selected' : '' }}>Critique</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-success w-100"><i class="bi bi-filter me-1"></i> Filtrer</button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('admin.entreprise.sites.create') }}" class="btn btn-primary w-100"><i class="bi bi-plus-circle me-1"></i> Nouveau</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Liste -->
        <div class="card site-card">
            <div class="card-body p-0">
                @if($sites->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Site</th>
                                <th>Client</th>
                                <th>Adresse</th>
                                <th>Contact</th>
                                <th>Niveau Risque</th>
                                <th>Statut</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sites as $site)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $site->nom_site }}</div>
                                    <small class="text-muted">{{ $site->code_site }}</small>
                                </td>
                                <td>{{ $site->client?->nom ?? 'N/A' }}</td>
                                <td>{{ $site->adresse }}, {{ $site->ville }}</td>
                                <td>
                                    @if($site->contact_nom)
                                    <div>{{ $site->contact_nom }}</div>
                                    <small class="text-muted">{{ $site->contact_telephone }}</small>
                                    @else
                                    <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge-risque badge-{{ $site->niveau_risque ?? 'faible' }}">
                                        {{ ucfirst($site->niveau_risque ?? 'faible') }}
                                    </span>
                                </td>
                                <td>
                                    @if($site->est_actif)
                                    <span class="badge bg-success">Actif</span>
                                    @else
                                    <span class="badge bg-secondary">Inactif</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.entreprise.sites.show', $site->id) }}" class="btn btn-sm btn-outline-primary btn-action"><i class="bi bi-eye"></i></a>
                                        <a href="{{ route('admin.entreprise.sites.edit', $site->id) }}" class="btn btn-sm btn-outline-success btn-action"><i class="bi bi-pencil"></i></a>
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-action" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $site->id }}"><i class="bi bi-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <!-- Modal -->
                            <div class="modal fade" id="deleteModal{{ $site->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Supprimer le site</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Êtes-vous sûr de vouloir supprimer <strong>{{ $site->nom_site }}</strong> ?</p>
                                            @if($site->contrats()->count() > 0 || $site->affectations()->count() > 0)
                                            <div class="alert alert-warning">Ce site est associé à des contrats ou des agents.</div>
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                            @if($site->contrats()->count() == 0 && $site->affectations()->count() == 0)
                                            <form action="{{ route('admin.entreprise.sites.destroy', $site->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Supprimer</button>
                                            </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="bi bi-geo-alt fs-1 text-muted"></i>
                    <h5 class="mt-3 text-muted">Aucun site trouvé</h5>
                    <a href="{{ route('admin.entreprise.sites.create') }}" class="btn btn-success mt-2">Créer un site</a>
                </div>
                @endif
            </div>
            @if($sites->hasPages())
            <div class="card-footer">{{ $sites->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection