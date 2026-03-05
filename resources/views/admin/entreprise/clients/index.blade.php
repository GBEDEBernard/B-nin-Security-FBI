@extends('layouts.app')

@section('title', 'Clients - Entreprise')

@push('styles')
<style>
    .client-card {
        transition: all 0.3s ease;
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .client-card:hover {
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

    .badge-type {
        font-size: 11px;
        padding: 4px 8px;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
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

    .table-clients th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.5px;
        color: #6c757d;
        border-bottom: 2px solid #e9ecef;
    }

    .table-clients td {
        vertical-align: middle;
    }

    .table-clients tbody tr:hover {
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
                    Gestion des Clients
                </h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.entreprise.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Clients</li>
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
        'total' => \App\Models\Client::where('entreprise_id', $entrepriseId)->count(),
        'actifs' => \App\Models\Client::where('entreprise_id', $entrepriseId)->where('est_actif', true)->count(),
        'particuliers' => \App\Models\Client::where('entreprise_id', $entrepriseId)->where('type_client', 'particulier')->count(),
        'entreprises' => \App\Models\Client::where('entreprise_id', $entrepriseId)->where('type_client', 'entreprise')->count(),
        ];
        @endphp

        <div class="row mb-4">
            <div class="col-lg-3 col-6">
                <div class="client-card p-3">
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
                <div class="client-card p-3">
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
                <div class="client-card p-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar-circle bg-purple text-white me-3" style="background: #6f42c1;">
                            <i class="bi bi-person"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Particuliers</div>
                            <div class="fw-bold fs-4">{{ $stats['particuliers'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="client-card p-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar-circle bg-info text-white me-3">
                            <i class="bi bi-building"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Entreprises</div>
                            <div class="fw-bold fs-4">{{ $stats['entreprises'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters and Search --}}
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.entreprise.clients.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <input type="text"
                            name="search"
                            class="form-control search-box"
                            placeholder="Rechercher (nom, email, téléphone)..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="type_client" class="form-select search-box">
                            <option value="">Tous types</option>
                            <option value="particulier" {{ request('type_client') == 'particulier' ? 'selected' : '' }}>Particulier</option>
                            <option value="entreprise" {{ request('type_client') == 'entreprise' ? 'selected' : '' }}>Entreprise</option>
                            <option value="institution" {{ request('type_client') == 'institution' ? 'selected' : '' }}>Institution</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="est_actif" class="form-select search-box">
                            <option value="">Tous statuts</option>
                            <option value="1" {{ request('est_actif') == '1' ? 'selected' : '' }}>Actif</option>
                            <option value="0" {{ request('est_actif') == '0' ? 'selected' : '' }}>Inactif</option>
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

        {{-- Clients Table --}}
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="bi bi-list-ul me-2"></i>
                    Liste des clients ({{ $clients->total() }})
                </h5>
                <a href="{{ route('admin.entreprise.clients.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Nouveau client
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-clients mb-0">
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th>Type</th>
                                <th>Contact</th>
                                <th>Sites</th>
                                <th>Contrats</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($clients as $client)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle bg-{{ $client->type_client == 'particulier' ? 'purple' : ($client->type_client == 'entreprise' ? 'primary' : 'warning') }} text-white me-3">
                                            @if($client->type_client == 'particulier')
                                            {{ strtoupper(substr($client->prenoms ?? 'N', 0, 1)) }}{{ strtoupper(substr($client->nom ?? 'A', 0, 1)) }}
                                            @else
                                            {{ strtoupper(substr($client->raison_sociale ?? 'E', 0, 2)) }}
                                            @endif
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $client->nom_affichage }}</div>
                                            @if($client->type_client != 'particulier' && $client->raison_sociale)
                                            <div class="text-muted small">{{ $client->raison_sociale }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-type badge-{{ $client->type_client }}">
                                        @switch($client->type_client)
                                        @case('particulier') Particulier @break
                                        @case('entreprise') Entreprise @break
                                        @case('institution') Institution @break
                                        @default {{ $client->type_client }}
                                        @endswitch
                                    </span>
                                </td>
                                <td>
                                    @if($client->email)
                                    <div class="small">
                                        <i class="bi bi-envelope me-1"></i>
                                        {{ $client->email }}
                                    </div>
                                    @endif
                                    @if($client->telephone)
                                    <div class="small text-muted">
                                        <i class="bi bi-phone me-1"></i>
                                        {{ $client->telephone }}
                                    </div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $client->sites->count() }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $client->contrats->count() }}</span>
                                </td>
                                <td>
                                    @if($client->est_actif)
                                    <span class="badge bg-success">Actif</span>
                                    @else
                                    <span class="badge bg-secondary">Inactif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('admin.entreprise.clients.show', $client->id) }}"
                                            class="action-btn text-primary"
                                            title="Voir">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.entreprise.clients.edit', $client->id) }}"
                                            class="action-btn text-warning"
                                            title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.entreprise.clients.destroy', $client->id) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce client ?');">
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
                                        Aucun client trouvé
                                    </div>
                                    <a href="{{ route('admin.entreprise.clients.create') }}" class="btn btn-success mt-2">
                                        <i class="bi bi-plus-circle me-1"></i> Créer le premier client
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($clients->hasPages())
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Affichage de {{ $clients->firstItem() }} à {{ $clients->lastItem() }} sur {{ $clients->total() }} résultats
                    </div>
                    {{ $clients->links() }}
                </div>
            </div>
            @endif
        </div>

    </div>
</div>
@endsection