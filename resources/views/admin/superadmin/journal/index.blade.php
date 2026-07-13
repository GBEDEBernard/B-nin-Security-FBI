@extends('layouts.app')

@section('title', "Journal d'Activité - Super Admin")

@push('styles')
<style>
    .stat-card {
        transition: transform 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-2px);
    }
</style>
@endpush

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">
                <i class="bi bi-clock-history text-secondary me-2"></i>
                Journal d'Activité
            </h2>
            <p class="text-muted mb-0">Historique des actions et événements du système</p>
        </div>
        <div class="btn-group">
            <form action="{{ route('admin.superadmin.journal.export') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-secondary">
                    <i class="bi bi-download me-1"></i> Exporter CSV
                </button>
            </form>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.superadmin.journal.index') }}">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3 align-items-end">
                    <div class="col-md-2">
                        <label class="form-label">Date début</label>
                        <input type="date" name="date_debut" class="form-control"
                               value="{{ request('date_debut') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Date fin</label>
                        <input type="date" name="date_fin" class="form-control"
                               value="{{ request('date_fin') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Utilisateur</label>
                        <select name="user_id" class="form-select">
                            <option value="">Tous</option>
                            @foreach($utilisateurs as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Action</label>
                        <select name="action" class="form-select">
                            <option value="">Toutes</option>
                            <option value="connexion" {{ request('action') === 'connexion' ? 'selected' : '' }}>Connexion</option>
                            <option value="creation" {{ request('action') === 'creation' ? 'selected' : '' }}>Création</option>
                            <option value="modification" {{ request('action') === 'modification' ? 'selected' : '' }}>Modification</option>
                            <option value="suppression" {{ request('action') === 'suppression' ? 'selected' : '' }}>Suppression</option>
                            <option value="erreur" {{ request('action') === 'erreur' ? 'selected' : '' }}>Erreur</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Module</label>
                        <select name="module" class="form-select">
                            <option value="">Tous</option>
                            @foreach($modules as $module)
                                <option value="{{ $module['value'] }}" {{ request('module') === $module['value'] ? 'selected' : '' }}>
                                    {{ $module['label'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i> Rechercher
                            </button>
                            <a href="{{ route('admin.superadmin.journal.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-x me-1"></i> Réinitialiser
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center stat-card h-100">
                <div class="card-body">
                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex p-3 mb-2">
                        <i class="bi bi-person-check fs-3 text-success"></i>
                    </div>
                    <h3 class="fw-bold mb-0">{{ number_format($stats['connexions_today']) }}</h3>
                    <p class="text-muted mb-0 small">Connexions aujourd'hui</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center stat-card h-100">
                <div class="card-body">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex p-3 mb-2">
                        <i class="bi bi-plus-circle fs-3 text-primary"></i>
                    </div>
                    <h3 class="fw-bold mb-0">{{ number_format($stats['creations_today']) }}</h3>
                    <p class="text-muted mb-0 small">Créations aujourd'hui</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center stat-card h-100">
                <div class="card-body">
                    <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex p-3 mb-2">
                        <i class="bi bi-pencil fs-3 text-warning"></i>
                    </div>
                    <h3 class="fw-bold mb-0">{{ number_format($stats['modifications_today']) }}</h3>
                    <p class="text-muted mb-0 small">Modifications aujourd'hui</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center stat-card h-100">
                <div class="card-body">
                    <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex p-3 mb-2">
                        <i class="bi bi-exclamation-triangle fs-3 text-danger"></i>
                    </div>
                    <h3 class="fw-bold mb-0">{{ number_format($stats['errors_today']) }}</h3>
                    <p class="text-muted mb-0 small">Erreurs aujourd'hui</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Historique des Actions</h5>
            <span class="badge bg-secondary">{{ $activites->total() }} entrée(s)</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date/Heure</th>
                            <th>Utilisateur</th>
                            <th>Action</th>
                            <th>Module</th>
                            <th>Description</th>
                            <th>IP</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activites as $activite)
                            <tr>
                                <td class="text-nowrap">
                                    <small>{{ $activite->created_at?->format('d/m/Y H:i:s') }}</small>
                                </td>
                                <td>
                                    <small>{{ $activite->causer_name }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $activite->action_badge }}">
                                        {{ $activite->action_label }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $activite->module_label }}</small>
                                </td>
                                <td>
                                    <small>{{ $activite->description }}</small>
                                </td>
                                <td>
                                    <code class="small">{{ $activite->ip_address ?? '—' }}</code>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.superadmin.journal.show', $activite->id) }}"
                                       class="btn btn-sm btn-outline-primary" title="Détails">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="bi bi-inbox fs-2 text-muted d-block mb-2"></i>
                                    <p class="text-muted mb-0">Aucune activité trouvée.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($activites->hasPages())
            <div class="card-footer bg-white">
                {{ $activites->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
