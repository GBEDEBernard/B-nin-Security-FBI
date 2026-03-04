@extends('layouts.app')

@section('title', 'Activités par Utilisateur - Super Admin')

@section('content')
<div class="container-fluid p-4">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">
                <i class="bi bi-person text-primary me-2"></i>
                Activités par Utilisateur
            </h2>
            <p class="text-muted mb-0">Filtrer les activités par utilisateur</p>
        </div>
        <a href="{{ route('admin.superadmin.journal.index') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left me-1"></i> Retour au journal
        </a>
    </div>

    <div class="row">
        <!-- Liste des utilisateurs -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-people me-2"></i>Utilisateurs</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @php
                        $users = \App\Models\User::orderBy('name')->get();
                        $userActivities = \Illuminate\Support\Facades\DB::table('activity_log')
                        ->select('causer_id', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
                        ->whereNotNull('causer_id')
                        ->groupBy('causer_id')
                        ->orderByDesc('total')
                        ->get()
                        ->keyBy('causer_id');
                        @endphp
                        @forelse($users as $user)
                        @php
                        $activityCount = $userActivities->get($user->id)?->total ?? 0;
                        $isActive = request('user_id') == $user->id;
                        @endphp
                        <a href="{{ route('admin.superadmin.journal.par-utilisateur', ['user_id' => $user->id]) }}"
                            class="list-group-item list-group-item-action {{ $isActive ? 'bg-primary text-white' : '' }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-{{ $isActive ? 'white' : 'primary' }} text-{{ $isActive ? 'primary' : 'white' }} rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="{{ $isActive ? 'text-white' : '' }}">{{ $user->name }}</div>
                                        <small class="{{ $isActive ? 'text-white-50' : 'text-muted' }}">{{ $user->email }}</small>
                                    </div>
                                </div>
                                <span class="badge bg-{{ $isActive ? 'white text-primary' : 'secondary' }}">
                                    {{ $activityCount }}
                                </span>
                            </div>
                        </a>
                        @empty
                        <div class="list-group-item text-center text-muted py-4">
                            <i class="bi bi-people fs-1 d-block mb-2"></i>
                            Aucun utilisateur trouvé
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Activités de l'utilisateur sélectionné -->
        <div class="col-md-8">
            @if(request('user_id'))
            @php
            $selectedUser = \App\Models\User::find(request('user_id'));
            @endphp
            @if($selectedUser)
            <!-- Statistiques de l'utilisateur -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card border-success">
                        <div class="card-body">
                            <h6 class="card-title text-muted">Connexions</h6>
                            <h3 class="mb-0 text-success">
                                {{ \Illuminate\Support\Facades\DB::table('activity_log')
                                                ->where('causer_id', $selectedUser->id)
                                                ->where('description', 'like', '%connexion%')
                                                ->count() }}
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-primary">
                        <div class="card-body">
                            <h6 class="card-title text-muted">Créations</h6>
                            <h3 class="mb-0 text-primary">
                                {{ \Illuminate\Support\Facades\DB::table('activity_log')
                                                ->where('causer_id', $selectedUser->id)
                                                ->where('description', 'like', '%created%')
                                                ->count() }}
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-warning">
                        <div class="card-body">
                            <h6 class="card-title text-muted">Modifications</h6>
                            <h3 class="mb-0 text-warning">
                                {{ \Illuminate\Support\Facades\DB::table('activity_log')
                                                ->where('causer_id', $selectedUser->id)
                                                ->where('description', 'like', '%updated%')
                                                ->count() }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- En-tête utilisateur -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-lg bg-primary text-white rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px; font-size: 1.5rem;">
                            {{ substr($selectedUser->name, 0, 1) }}
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="mb-0">{{ $selectedUser->name }}</h4>
                            <p class="text-muted mb-0">{{ $selectedUser->email }}</p>
                            @if($selectedUser->estSuperAdmin())
                            <span class="badge bg-danger">Super Admin</span>
                            @endif
                        </div>
                        <div class="text-end">
                            <div class="text-muted">Total activités</div>
                            <h3 class="mb-0">{{ $activites->total() }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtres -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.superadmin.journal.par-utilisateur') }}" class="row g-3">
                        <input type="hidden" name="user_id" value="{{ request('user_id') }}">
                        <div class="col-md-3">
                            <label class="form-label">Date début</label>
                            <input type="date" name="date_debut" class="form-control" value="{{ request('date_debut') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date fin</label>
                            <input type="date" name="date_fin" class="form-control" value="{{ request('date_fin') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Action</label>
                            <select name="action" class="form-select">
                                <option value="">Toutes</option>
                                <option value="connexion" {{ request('action') == 'connexion' ? 'selected' : '' }}>Connexion</option>
                                <option value="created" {{ request('action') == 'created' ? 'selected' : '' }}>Création</option>
                                <option value="updated" {{ request('action') == 'updated' ? 'selected' : '' }}>Modification</option>
                                <option value="deleted" {{ request('action') == 'deleted' ? 'selected' : '' }}>Suppression</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="d-flex gap-2 w-100">
                                <button type="submit" class="btn btn-primary flex-grow-1">
                                    <i class="bi bi-search me-1"></i>
                                </button>
                                <a href="{{ route('admin.superadmin.journal.par-utilisateur', ['user_id' => request('user_id')]) }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-lg"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tableau des activités -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Historique des Actions</h5>
                    <span class="badge bg-secondary">{{ $activites->total() }} enregistrements</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date/Heure</th>
                                    <th>Action</th>
                                    <th>Module</th>
                                    <th>Description</th>
                                    <th>Détails</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activites as $activite)
                                <tr>
                                    <td>
                                        <small>{{ \Carbon\Carbon::parse($activite->created_at)->format('d/m/Y') }}</small><br>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($activite->created_at)->format('H:i:s') }}</small>
                                    </td>
                                    <td>
                                        @php
                                        $badgeClass = 'bg-secondary';
                                        if (stripos($activite->description, 'connexion') !== false) {
                                        $badgeClass = 'bg-success';
                                        } elseif (stripos($activite->description, 'created') !== false) {
                                        $badgeClass = 'bg-primary';
                                        } elseif (stripos($activite->description, 'updated') !== false) {
                                        $badgeClass = 'bg-warning';
                                        } elseif (stripos($activite->description, 'deleted') !== false) {
                                        $badgeClass = 'bg-danger';
                                        }
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">
                                            @if(stripos($activite->description, 'connexion') !== false)
                                            Connexion
                                            @elseif(stripos($activite->description, 'created') !== false)
                                            Création
                                            @elseif(stripos($activite->description, 'updated') !== false)
                                            Modification
                                            @elseif(stripos($activite->description, 'deleted') !== false)
                                            Suppression
                                            @else
                                            Action
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        @if($activite->subject_type)
                                        {{ class_basename($activite->subject_type) }}
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td><small>{{ $activite->description ?? '-' }}</small></td>
                                    <td>
                                        <a href="{{ route('admin.superadmin.journal.show', $activite->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        Aucune activité trouvée pour cet utilisateur
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted">
                            Affichage de {{ $activites->firstItem() ?? 0 }} à {{ $activites->lastItem() ?? 0 }} sur {{ $activites->total() }}
                        </div>
                        {{ $activites->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
            @else
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Utilisateur non trouvé
            </div>
            @endif
            @else
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                Sélectionnez un utilisateur dans la liste pour voir ses activités
            </div>
            @endif
        </div>
    </div>
</div>
@endSection