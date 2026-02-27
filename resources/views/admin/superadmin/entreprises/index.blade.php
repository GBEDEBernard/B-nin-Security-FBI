@extends('layouts.app')

@section('title', 'Gestion des Entreprises - Super Admin')

@push('styles')
<style>
    .search-box {
        position: relative;
    }
    
    .search-box .search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }
    
    .search-box input {
        padding-left: 40px;
        border-radius: 10px;
    }
    
    .filter-dropdown {
        border-radius: 10px;
    }
    
    .entreprises-table .avatar-sm {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-weight: 600;
        font-size: 0.75rem;
    }
    
    .badge-formule {
        padding: 0.35rem 0.65rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .badge-essai {
        background: rgba(255, 193, 7, 0.15);
        color: #ffc107;
    }
    
    .badge-basic {
        background: rgba(13, 110, 253, 0.15);
        color: #0d6efd;
    }
    
    .badge-standard {
        background: rgba(25, 135, 84, 0.15);
        color: #198754;
    }
    
    .badge-premium {
        background: rgba(111, 66, 193, 0.15);
        color: #6f42c1;
    }
    
    .stat-badge {
        padding: 0.35rem 0.65rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<div class="container-fluid p-4">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">
                <i class="bi bi-building-fill text-success me-2"></i>
                Gestion des Entreprises
            </h2>
            <p class="text-muted mb-0">Gérez les entreprises de sécurité</p>
        </div>
        <a href="{{ route('admin.superadmin.entreprises.create') }}" class="btn btn-success">
            <i class="bi bi-plus-lg me-1"></i> Nouvelle Entreprise
        </a>
    </div>


    <!-- Filtres et Recherche -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.superadmin.entreprises.index') }}" class="row g-3">
                <div class="col-md-4">
                    <div class="search-box">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Rechercher une entreprise..." 
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="statut" class="form-select" onchange="this.form.submit()">
                        <option value="">Tous les statuts</option>
                        <option value="actif" {{ request('statut') == 'actif' ? 'selected' : '' }}>Actifs</option>
                        <option value="inactif" {{ request('statut') == 'inactif' ? 'selected' : '' }}>Inactifs</option>
                        <option value="essai" {{ request('statut') == 'essai' ? 'selected' : '' }}>En essai</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="formule" class="form-select" onchange="this.form.submit()">
                        <option value="">Toutes les formules</option>
                        <option value="essai" {{ request('formule') == 'essai' ? 'selected' : '' }}>Essai</option>
                        <option value="basic" {{ request('formule') == 'basic' ? 'selected' : '' }}>Basic</option>
                        <option value="standard" {{ request('formule') == 'standard' ? 'selected' : '' }}>Standard</option>
                        <option value="premium" {{ request('formule') == 'premium' ? 'selected' : '' }}>Premium</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary w-100">
                        <i class="bi bi-search me-1"></i> Rechercher
                    </button>
                </div>
            </form>
            
            <!-- Filtres actifs -->
            @if(request('search') || request('statut') || request('formule'))
            <div class="mt-3">
                <small class="text-muted">Filtres actifs :</small>
                @if(request('search'))
                <span class="badge bg-primary ms-2">Recherche: "{{ request('search') }}" <a href="{{ route('admin.superadmin.entreprises.index', request()->except('search')) }}" class="text-white ms-1">×</a></span>
                @endif
                @if(request('statut'))
                <span class="badge bg-info ms-2">Statut: {{ request('statut') }} <a href="{{ route('admin.superadmin.entreprises.index', request()->except('statut')) }}" class="text-white ms-1">×</a></span>
                @endif
                @if(request('formule'))
                <span class="badge bg-warning text-dark ms-2">Formule: {{ request('formule') }} <a href="{{ route('admin.superadmin.entreprises.index', request()->except('formule')) }}" class="text-dark ms-1">×</a></span>
                @endif
                <a href="{{ route('admin.superadmin.entreprises.index') }}" class="btn btn-sm btn-link">Tout effacer</a>
            </div>
            @endif
        </div>
    </div>

    <!-- Tableau des entreprises -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Liste des Entreprises</h5>
        </div>
        <div class="card-body">
            @if($entreprises->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Entreprise</th>
                            <th>Contact</th>
                            <th>Statut</th>
                            <th>Formule</th>
                            <th class="text-center">Employés</th>
                            <th class="text-center">Clients</th>
                            <th class="text-center">Contrats</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($entreprises as $entreprise)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($entreprise->logo)
                                    <img src="{{ $entreprise->logoUrl }}" alt="Logo" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                    <div class="avatar-sm me-2 bg-success-subtle rounded-circle d-flex align-items-center justify-content-center" style="background: rgba(25, 135, 84, 0.1); width: 40px; height: 40px;">
                                        <span class="text-success fw-bold">{{ strtoupper(substr($entreprise->nom_entreprise, 0, 2)) }}</span>
                                    </div>
                                    @endif
                                    <div>
                                        <span class="fw-semibold d-block">{{ $entreprise->nom_entreprise }}</span>
                                        <small class="text-muted">{{ $entreprise->ville ?? 'Non localisée' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <a href="mailto:{{ $entreprise->email }}" class="text-decoration-none">{{ $entreprise->email }}</a>
                                </div>
                                <small class="text-muted">{{ $entreprise->telephone }}</small>
                            </td>
                            <td>
                                @if($entreprise->est_active)
                                <span class="badge bg-success">Actif</span>
                                @else
                                <span class="badge bg-secondary">Inactif</span>
                                @endif
                                @if($entreprise->est_en_essai)
                                <span class="badge bg-warning text-dark ms-1">Essai</span>
                                @endif
                            </td>
                            <td>
                                @php $formule = $entreprise->formule ?? 'basic'; @endphp
                                <span class="badge-formule badge-{{ $formule }}">
                                    {{ ucfirst($formule) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="stat-badge bg-primary bg-opacity-10 text-primary">
                                    {{ $entreprise->employes_count ?? 0 }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="stat-badge bg-info bg-opacity-10 text-info">
                                    {{ $entreprise->clients_count ?? 0 }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="stat-badge bg-warning bg-opacity-10 text-warning">
                                    {{ $entreprise->contratsPrestation_count ?? 0 }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group" role="group">
                                    @if($entreprise->est_active)
                                    <button type="button" class="btn btn-sm btn-outline-success"
                                        title="Se connecter au tableau de bord"
                                        data-bs-toggle="modal"
                                        data-bs-target="#connectModal"
                                        data-entreprise-id="{{ $entreprise->id }}"
                                        data-entreprise-nom="{{ $entreprise->nom_entreprise }}">
                                        <i class="bi bi-box-arrow-in-right"></i>
                                    </button>
                                    @else
                                    <button type="button" class="btn btn-sm btn-outline-secondary" disabled title="Entreprise inactive">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                    @endif

                                    <a href="{{ route('admin.superadmin.entreprises.show', $entreprise->id) }}" class="btn btn-sm btn-outline-info" title="Voir les détails">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.superadmin.entreprises.edit', $entreprise->id) }}" class="btn btn-sm btn-outline-warning" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if(method_exists($entreprises, 'links'))
            <div class="mt-3">
                {{ $entreprises->appends(request()->query())->links() }}
            </div>
            @endif

            @else
            <div class="text-center py-5">
                <i class="bi bi-building fs-1 text-muted"></i>
                <h5 class="mt-3 text-muted">Aucune entreprise trouvée</h5>
                @if(request('search') || request('statut') || request('formule'))
                <p class="text-muted">Essayez avec d'autres filtres.</p>
                <a href="{{ route('admin.superadmin.entreprises.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle me-1"></i> Effacer les filtres
                </a>
                @else
                <p class="text-muted">Commencez par créer une nouvelle entreprise.</p>
                <a href="{{ route('admin.superadmin.entreprises.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Nouvelle entreprise
                </a>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal de connexion à l'entreprise -->
<div class="modal fade" id="connectModal" tabindex="-1" aria-labelledby="connectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="connectModalLabel">Se connecter à l'entreprise</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir vous connecter au tableau de bord de l'entreprise <strong id="entrepriseName"></strong> ?</p>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    Vous pourrez revenir au dashboard Super Admin à tout moment.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="connectForm" method="POST" action="">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Se connecter
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Gérer le modal de connexion
    document.getElementById('connectModal').addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const entrepriseId = button.getAttribute('data-entreprise-id');
        const entrepriseNom = button.getAttribute('data-entreprise-nom');

        document.getElementById('entrepriseName').textContent = entrepriseNom;
        document.getElementById('connectForm').action = '/admin/superadmin/entreprises/' + entrepriseId + '/connect';
    });
</script>
@endpush
@endsection

