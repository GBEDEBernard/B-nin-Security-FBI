@extends('layouts.app')

@section('title', 'Entreprises - Super Admin')

@push('styles')
<style>
    /* Styles spécifiques pour la page des entreprises */
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

    .entreprises-table .badge {
        font-weight: 500;
    }

    .entreprises-table .btn-group .btn {
        padding: 0.25rem 0.5rem;
    }

    .connect-btn {
        transition: all 0.2s ease;
    }

    .connect-btn:hover {
        transform: scale(1.05);
    }
</style>
@endpush

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0"><i class="bi bi-building-fill me-2"></i>Gestion des Entreprises</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Entreprises</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!--end::App Content Header-->

<!--begin::App Content-->
<div class="app-content">
    <div class="container-fluid">
        <!-- Messages de session -->
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

        <!--begin::Card-->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title m-0"><i class="bi bi-list-ul me-2"></i>Liste des Entreprises</h5>
                <a href="{{ route('admin.superadmin.entreprises.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle me-1"></i> Nouvelle entreprise
                </a>
            </div>
            <div class="card-body">
                @if($entreprises->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-striped entreprises-table">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Statut</th>
                                <th>Employés</th>
                                <th>Clients</th>
                                <th>Contrats</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($entreprises as $entreprise)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-2 bg-success-subtle rounded-circle d-flex align-items-center justify-content-center" style="background: rgba(25, 135, 84, 0.1);">
                                            <span class="text-success fw-bold">{{ strtoupper(substr($entreprise->nom_entreprise ?? $entreprise->nom, 0, 2)) }}</span>
                                        </div>
                                        <span class="fw-semibold">{{ $entreprise->nom_entreprise ?? $entreprise->nom }}</span>
                                    </div>
                                </td>
                                <td>{{ $entreprise->email }}</td>
                                <td>{{ $entreprise->telephone }}</td>
                                <td>
                                    @if($entreprise->est_active)
                                    <span class="badge bg-success">Actif</span>
                                    @else
                                    <span class="badge bg-secondary">Inactif</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info text-dark">{{ $entreprise->employes_count ?? 0 }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-warning text-dark">{{ $entreprise->clients_count ?? 0 }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $entreprise->contratsPrestation_count ?? $entreprise->contrats_count ?? 0 }}</span>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group" role="group">
                                        <!-- Bouton de connexion à l'entreprise -->
                                        @if($entreprise->est_active)
                                        <button type="button" class="btn btn-sm btn-outline-success connect-btn"
                                            title="Se connecter au tableau de bord"
                                            data-bs-toggle="modal"
                                            data-bs-target="#connectModal"
                                            data-entreprise-id="{{ $entreprise->id }}"
                                            data-entreprise-nom="{{ $entreprise->nom_entreprise ?? $entreprise->nom }}">
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
                    {{ $entreprises->links() }}
                </div>
                @endif

                @else
                <div class="text-center py-5">
                    <i class="bi bi-building fs-1 text-muted"></i>
                    <h5 class="mt-3 text-muted">Aucune entreprise trouvée</h5>
                    <p class="text-muted">Commencez par créer une nouvelle entreprise.</p>
                    <a href="{{ route('admin.superadmin.entreprises.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i> Nouvelle entreprise
                    </a>
                </div>
                @endif
            </div>
        </div>
        <!--end::Card-->
    </div>
</div>
<!--end::App Content-->
@endsection