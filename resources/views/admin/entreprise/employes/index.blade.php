@extends('layouts.app')

@section('title', 'Employés - Entreprise')

@push('styles')
<style>
    .employe-card {
        transition: all 0.3s ease;
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .employe-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }

    .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 14px;
    }

    .badge-categorie {
        font-size: 11px;
        padding: 4px 8px;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
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

    .badge-statut {
        font-size: 11px;
        padding: 4px 8px;
        border-radius: 20px;
    }

    .statut-en_poste {
        background: #d1e7dd;
        color: #0f5132;
    }

    .statut-conge {
        background: #fff3cd;
        color: #664d03;
    }

    .statut-suspendu {
        background: #f8d7da;
        color: #842029;
    }

    .statut-licencie {
        background: #6c757d;
        color: white;
    }

    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .action-btn:hover {
        background: #e9ecef;
    }

    .search-box {
        border-radius: 10px;
        border: 1px solid #e0e0e0;
        padding: 8px 15px;
    }

    .search-box:focus {
        border-color: #198754;
        box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.15);
    }

    .filter-btn {
        border-radius: 8px;
        padding: 6px 12px;
        font-size: 13px;
    }

    .table-employes th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.5px;
        color: #6c757d;
        border-bottom: 2px solid #e9ecef;
    }

    .table-employes td {
        vertical-align: middle;
    }

    .table-employes tbody tr:hover {
        background: rgba(25, 135, 84, 0.03);
    }
</style>
@endpush

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0">
                    <i class="bi bi-people me-2 text-success"></i>
                    Gestion des Employés
                </h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.entreprise.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Employés</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">

        {{-- Statistics Cards --}}
        @php
        $entrepriseId = null;
        if (Auth::guard('employe')->check()) {
        $entrepriseId = Auth::guard('employe')->user()->entreprise_id;
        } elseif (Auth::guard('web')->check() && session()->has('entreprise_id')) {
        $entrepriseId = session('entreprise_id');
        }

        $stats = [
        'total' => \App\Models\Employe::where('entreprise_id', $entrepriseId)->count(),
        'actifs' => \App\Models\Employe::where('entreprise_id', $entrepriseId)->where('est_actif', true)->where('statut', 'en_poste')->count(),
        'conge' => \App\Models\Employe::where('entreprise_id', $entrepriseId)->where('statut', 'conge')->count(),
        'disponibles' => \App\Models\Employe::where('entreprise_id', $entrepriseId)->where('disponible', true)->where('est_actif', true)->count(),
        ];
        @endphp

        <div class="row mb-4">
            <div class="col-lg-3 col-6">
                <div class="employe-card p-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar-circle bg-primary text-white me-3">
                            <i class="bi bi-people"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Total</div>
                            <div class="fw-bold fs-4">{{ $stats['total'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="employe-card p-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar-circle bg-success text-white me-3">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Actifs</div>
                            <div class="fw-bold fs-4">{{ $stats['actifs'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="employe-card p-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar-circle bg-warning text-dark me-3">
                            <i class="bi bi-calendar-event"></i>
                        </div>
                        <div>
                            <div class="text-muted small">En Congé</div>
                            <div class="fw-bold fs-4">{{ $stats['conge'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="employe-card p-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar-circle bg-info text-white me-3">
                            <i class="bi bi-person-check"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Disponibles</div>
                            <div class="fw-bold fs-4">{{ $stats['disponibles'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters and Search --}}
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.entreprise.employes.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <input type="text"
                            name="search"
                            class="form-control search-box"
                            placeholder="Rechercher (nom, prénom, email, matricule)..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="categorie" class="form-select search-box">
                            <option value="">Toutes catégories</option>
                            <option value="direction" {{ request('categorie') == 'direction' ? 'selected' : '' }}>Direction</option>
                            <option value="supervision" {{ request('categorie') == 'supervision' ? 'selected' : '' }}>Supervision</option>
                            <option value="controle" {{ request('categorie') == 'controle' ? 'selected' : '' }}>Contrôle</option>
                            <option value="agent" {{ request('categorie') == 'agent' ? 'selected' : '' }}>Agent</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="statut" class="form-select search-box">
                            <option value="">Tous statuts</option>
                            <option value="en_poste" {{ request('statut') == 'en_poste' ? 'selected' : '' }}>En poste</option>
                            <option value="conge" {{ request('statut') == 'conge' ? 'selected' : '' }}>En congé</option>
                            <option value="suspendu" {{ request('statut') == 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                            <option value="licencie" {{ request('statut') == 'licencie' ? 'selected' : '' }}>Licencié</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="disponible" class="form-select search-box">
                            <option value="">Tous</option>
                            <option value="1" {{ request('disponible') == '1' ? 'selected' : '' }}>Disponibles</option>
                            <option value="0" {{ request('disponible') == '0' ? 'selected' : '' }}>Non disponibles</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-search me-1"></i> Filtrer
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Employees Table --}}
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="bi bi-list-ul me-2"></i>
                    Liste des employés ({{ $employes->total() }})
                </h5>
                <a href="{{ route('admin.entreprise.employes.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Nouvel employé
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-employes mb-0">
                        <thead>
                            <tr>
                                <th>Employé</th>
                                <th>Matricule</th>
                                <th>Poste</th>
                                <th>Catégorie</th>
                                <th>Statut</th>
                                <th>Contact</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employes as $employe)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle bg-{{ $employe->categorie == 'direction' ? 'purple' : ($employe->categorie == 'supervision' ? 'primary' : ($employe->categorie == 'controle' ? 'warning' : 'success')) }} text-white me-3">
                                            {{ strtoupper(substr($employe->prenoms ?? 'N', 0, 1)) }}{{ strtoupper(substr($employe->nom ?? 'A', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $employe->prenoms }} {{ $employe->nom }}</div>
                                            <div class="text-muted small">{{ $employe->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $employe->matricule ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <div>{{ $employe->poste ?? 'Non défini' }}</div>
                                    @if($employe->departement)
                                    <div class="text-muted small">{{ $employe->departement }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-categorie badge-{{ $employe->categorie }}">
                                        @switch($employe->categorie)
                                        @case('direction') Direction @break
                                        @case('supervision') Supervision @break
                                        @case('controle') Contrôle @break
                                        @case('agent') Agent @break
                                        @default {{ $employe->categorie }}
                                        @endswitch
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-statut statut-{{ $employe->statut }}">
                                        @switch($employe->statut)
                                        @case('en_poste') En poste @break
                                        @case('conge') En congé @break
                                        @case('suspendu') Suspendu @break
                                        @case('licencie') Licencié @break
                                        @default {{ $employe->statut }}
                                        @endswitch
                                    </span>
                                    @if($employe->disponible && $employe->est_actif)
                                    <span class="badge bg-success ms-1" title="Disponible">
                                        <i class="bi bi-check-circle"></i>
                                    </span>
                                    @endif
                                </td>
                                <td>
                                    @if($employe->telephone)
                                    <div class="small">
                                        <i class="bi bi-phone me-1"></i>
                                        {{ $employe->telephone }}
                                    </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('admin.entreprise.employes.show', $employe->id) }}"
                                            class="action-btn text-primary"
                                            title="Voir">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.entreprise.employes.edit', $employe->id) }}"
                                            class="action-btn text-warning"
                                            title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.entreprise.employes.destroy', $employe->id) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet employé ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn text-danger" title="Supprimer">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        Aucun employé trouvé
                                    </div>
                                    <a href="{{ route('admin.entreprise.employes.create') }}" class="btn btn-success mt-2">
                                        <i class="bi bi-plus-circle me-1"></i> Créer le premier employé
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($employes->hasPages())
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Affichage de {{ $employes->firstItem() }} à {{ $employes->lastItem() }} sur {{ $employes->total() }} résultats
                    </div>
                    {{ $employes->links() }}
                </div>
            </div>
            @endif
        </div>

    </div>
</div>
@endsection