@extends('layouts.app')

@section('title', 'Gestion des Notifications - Super Admin')

@section('content')
<div class="container-fluid p-4">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">
                <i class="bi bi-bell-fill text-warning me-2"></i>
                Notifications Push
            </h2>
            <p class="text-muted mb-0">Envoyez et gérez les notifications aux utilisateurs</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.superadmin.notifications.statistiques') }}" class="btn btn-outline-primary">
                <i class="bi bi-graph-up me-1"></i> Statistiques
            </a>
            <a href="{{ route('admin.superadmin.notifications.create') }}" class="btn btn-success">
                <i class="bi bi-send me-1"></i> Nouvelle Notification
            </a>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-0">Total</p>
                            <h3 class="mb-0">{{ $stats['total'] }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-bell fs-4 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-0">Envoyées</p>
                            <h3 class="mb-0 text-success">{{ $stats['envoyees'] }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-check-circle fs-4 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-0">Aujourd'hui</p>
                            <h3 class="mb-0 text-info">{{ $stats['today'] }}</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-calendar-day fs-4 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-0">Non lues</p>
                            <h3 class="mb-0 text-warning">{{ $stats['non_lues'] }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-bell-slash fs-4 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="type" class="form-select">
                        <option value="">Tous les types</option>
                        <option value="info" {{ request('type') == 'info' ? 'selected' : '' }}>Information</option>
                        <option value="success" {{ request('type') == 'success' ? 'selected' : '' }}>Succès</option>
                        <option value="warning" {{ request('type') == 'warning' ? 'selected' : '' }}>Avertissement</option>
                        <option value="error" {{ request('type') == 'error' ? 'selected' : '' }}>Erreur</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="statut" class="form-select">
                        <option value="">Tous les statuts</option>
                        <option value="envoyee" {{ request('statut') == 'envoyee' ? 'selected' : '' }}>Envoyée</option>
                        <option value="echouee" {{ request('statut') == 'echouee' ? 'selected' : '' }}>Échouée</option>
                        <option value="brouillon" {{ request('statut') == 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="entreprise_id" class="form-select">
                        <option value="">Toutes les entreprises</option>
                        @foreach($entreprises as $entreprise)
                        <option value="{{ $entreprise->id }}" {{ request('entreprise_id') == $entreprise->id ? 'selected' : '' }}>
                            {{ $entreprise->nom_entreprise }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i> Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des notifications -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-list-ul me-2"></i>Historique des Notifications
                </h5>
                @if($notifications->count() > 0)
                <form method="POST" action="{{ route('admin.superadmin.notifications.destroyMultiple') }}" id="bulkDeleteForm">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger" id="bulkDeleteBtn" disabled>
                        <i class="bi bi-trash me-1"></i> Supprimer la sélection
                    </button>
                </form>
                @endif
            </div>
        </div>
        <div class="card-body p-0">
            @if($notifications->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="40">
                                <input type="checkbox" id="selectAll" class="form-check-input">
                            </th>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Titre</th>
                            <th>Message</th>
                            <th>Destinataires</th>
                            <th>Statut</th>
                            <th width="120">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($notifications as $notification)
                        <tr class="{{ is_null($notification->lu_le) ? 'table-warning' : '' }}">
                            <td>
                                <input type="checkbox" name="notifications[]" value="{{ $notification->id }}" class="form-check-input notification-checkbox">
                            </td>
                            <td>
                                <small class="text-muted d-block">{{ $notification->created_at->format('d/m/Y') }}</small>
                                <small class="text-muted">{{ $notification->created_at->format('H:i') }}</small>
                            </td>
                            <td>
                                @switch($notification->type)
                                    @case('info')
                                        <span class="badge bg-info"><i class="bi bi-info-circle me-1"></i>Info</span>
                                        @break
                                    @case('success')
                                        <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Succès</span>
                                        @break
                                    @case('warning')
                                        <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-triangle me-1"></i>Attention</span>
                                        @break
                                    @case('error')
                                        <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Erreur</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary"><i class="bi bi-bell me-1"></i>Notification</span>
                                @endswitch
                            </td>
                            <td>
                                <strong>{{ $notification->titre }}</strong>
                                @if(is_null($notification->lu_le))
                                <span class="badge bg-danger ms-1">Nouveau</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $notification->message }}">
                                    {{ Str::limit($notification->message, 50) }}
                                </span>
                            </td>
                            <td>
                                @if($notification->entreprise)
                                <span class="badge bg-outline-primary">
                                    <i class="bi bi-building me-1"></i>{{ Str::limit($notification->entreprise->nom_entreprise, 15) }}
                                </span>
                                @else
                                <span class="badge bg-outline-secondary">
                                    <i class="bi bi-globe me-1"></i>Tous
                                </span>
                                @endif
                            </td>
                            <td>
                                @switch($notification->statut)
                                    @case('envoyee')
                                        <span class="badge bg-success">Envoyée</span>
                                        @break
                                    @case('echouee')
                                        <span class="badge bg-danger">Échouée</span>
                                        @break
                                    @case('brouillon')
                                        <span class="badge bg-secondary">Brouillon</span>
                                        @break
                                @endswitch
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.superadmin.notifications.show', $notification->id) }}" class="btn btn-sm btn-outline-primary" title="Voir">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if(is_null($notification->lu_le))
                                    <form method="POST" action="{{ route('admin.superadmin.notifications.mark-read', $notification->id) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success" title="Marquer comme lu">
                                            <i class="bi bi-check"></i>
                                        </button>
                                    </form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.superadmin.notifications.destroy', $notification->id) }}" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette notification?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5">
                <i class="bi bi-bell-slash fs-1 text-muted mb-3"></i>
                <h5 class="text-muted">Aucune notification</h5>
                <p class="text-muted">Aucune notification ne correspond aux critères de recherche.</p>
                <a href="{{ route('admin.superadmin.notifications.create') }}" class="btn btn-primary">
                    <i class="bi bi-send me-1"></i> Envoyer une notification
                </a>
            </div>
            @endif
        </div>
        @if($notifications->hasPages())
        <div class="card-footer bg-white">
            {{ $notifications->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select all checkbox
    var selectAll = document.getElementById('selectAll');
    var checkboxes = document.querySelectorAll('.notification-checkbox');
    var bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    var bulkDeleteForm = document.getElementById('bulkDeleteForm');
    
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = selectAll.checked;
            });
            updateBulkDeleteButton();
        });
    }
    
    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            updateBulkDeleteButton();
        });
    });
    
    function updateBulkDeleteButton() {
        var checkedCount = document.querySelectorAll('.notification-checkbox:checked').length;
        if (bulkDeleteBtn) {
            bulkDeleteBtn.disabled = checkedCount === 0;
        }
    }
});
</script>
@endpush

@endsection

