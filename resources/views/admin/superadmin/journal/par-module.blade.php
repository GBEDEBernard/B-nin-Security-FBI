@extends('layouts.app')

@section('title', 'Activités par Module - Super Admin')

@section('content')
<div class="container-fluid p-4">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">
                <i class="bi bi-grid text-info me-2"></i>
                Activités par Module
            </h2>
            <p class="text-muted mb-0">Filtrer les activités par module/application</p>
        </div>
        <a href="{{ route('admin.superadmin.journal.index') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left me-1"></i> Retour au journal
        </a>
    </div>

    <div class="row">
        <!-- Liste des modules -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-grid me-2"></i>Modules</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @php
                        $modules = [
                        ['name' => 'Entreprise', 'icon' => 'bi-building', 'color' => 'primary'],
                        ['name' => 'User', 'icon' => 'bi-person', 'color' => 'info'],
                        ['name' => 'Contrat', 'icon' => 'bi-file-text', 'color' => 'success'],
                        ['name' => 'Facture', 'icon' => 'bi-receipt', 'color' => 'warning'],
                        ['name' => 'Employe', 'icon' => 'bi-people', 'color' => 'primary'],
                        ['name' => 'Client', 'icon' => 'bi-person-badge', 'color' => 'info'],
                        ['name' => 'Abonnement', 'icon' => 'bi-credit-card', 'color' => 'secondary'],
                        ['name' => 'Notification', 'icon' => 'bi-bell', 'color' => 'warning'],
                        ['name' => 'Role', 'icon' => 'bi-shield', 'color' => 'danger'],
                        ];

                        $moduleActivities = [];
                        foreach ($modules as $mod) {
                        $moduleActivities[$mod['name']] = \Illuminate\Support\Facades\DB::table('activity_log')
                        ->where('subject_type', 'like', '%' . $mod['name'] . '%')
                        ->count();
                        }
                        @endphp

                        @foreach($modules as $mod)
                        @php
                        $activityCount = $moduleActivities[$mod['name']] ?? 0;
                        $isActive = request('module') == $mod['name'];
                        @endphp
                        <a href="{{ route('admin.superadmin.journal.par-module', ['module' => $mod['name']]) }}"
                            class="list-group-item list-group-item-action {{ $isActive ? 'bg-primary text-white' : '' }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class="bi {{ $mod['icon'] }} me-2 fs-5"></i>
                                    <span>{{ $mod['name'] }}</span>
                                </div>
                                <span class="badge bg-{{ $isActive ? 'white text-' . $mod['color'] : $mod['color'] }}">
                                    {{ $activityCount }}
                                </span>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Activités du module sélectionné -->
        <div class="col-md-8">
            @if(request('module'))
            <!-- Statistiques du module -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-primary">
                        <div class="card-body">
                            <h6 class="card-title text-muted">Total</h6>
                            <h3 class="mb-0 text-primary">{{ $activites->total() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-success">
                        <div class="card-body">
                            <h6 class="card-title text-muted">Créations</h6>
                            <h3 class="mb-0 text-success">
                                {{ \Illuminate\Support\Facades\DB::table('activity_log')
                                            ->where('subject_type', 'like', '%' . request('module') . '%')
                                            ->where('description', 'like', '%created%')
                                            ->count() }}
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-warning">
                        <div class="card-body">
                            <h6 class="card-title text-muted">Modifications</h6>
                            <h3 class="mb-0 text-warning">
                                {{ \Illuminate\Support\Facades\DB::table('activity_log')
                                            ->where('subject_type', 'like', '%' . request('module') . '%')
                                            ->where('description', 'like', '%updated%')
                                            ->count() }}
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-danger">
                        <div class="card-body">
                            <h6 class="card-title text-muted">Suppressions</h6>
                            <h3 class="mb-0 text-danger">
                                {{ \Illuminate\Support\Facades\DB::table('activity_log')
                                            ->where('subject_type', 'like', '%' . request('module') . '%')
                                            ->where('description', 'like', '%deleted%')
                                            ->count() }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- En-tête module -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        @php
                        $currentModule = collect($modules)->firstWhere('name', request('module'));
                        $icon = $currentModule['icon'] ?? 'bi-folder';
                        $color = $currentModule['color'] ?? 'secondary';
                        @endphp
                        <div class="avatar-lg bg-{{ $color }} text-white rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px; font-size: 1.5rem;">
                            <i class="bi {{ $icon }}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="mb-0">Module {{ request('module') }}</h4>
                            <p class="text-muted mb-0">Gestion des {{ strtolower(request('module')) }}s</p>
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
                    <form method="GET" action="{{ route('admin.superadmin.journal.par-module') }}" class="row g-3">
                        <input type="hidden" name="module" value="{{ request('module') }}">
                        <div class="col-md-3">
                            <label class="form-label">Date début</label>
                            <input type="date" name="date_debut" class="form-control" value="{{ request('date_debut') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date fin</label>
                            <input type="date" name="date_fin" class="form-control" value="{{ request('date_fin') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Utilisateur</label>
                            <select name="user_id" class="form-select">
                                <option value="">Tous</option>
                                @foreach(\App\Models\User::all() as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="d-flex gap-2 w-100">
                                <button type="submit" class="btn btn-primary flex-grow-1">
                                    <i class="bi bi-search me-1"></i>
                                </button>
                                <a href="{{ route('admin.superadmin.journal.par-module', ['module' => request('module')]) }}" class="btn btn-outline-secondary">
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
                                    <th>Utilisateur</th>
                                    <th>Action</th>
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
                                        @if($activite->causer_id)
                                        @php
                                        $causer = \App\Models\User::find($activite->causer_id);
                                        @endphp
                                        @if($causer)
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                                                {{ substr($causer->name, 0, 1) }}
                                            </div>
                                            <small>{{ $causer->name }}</small>
                                        </div>
                                        @else
                                        <small class="text-muted">#{{ $activite->causer_id }}</small>
                                        @endif
                                        @else
                                        <span class="text-muted">Système</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                        $badgeClass = 'bg-secondary';
                                        if (stripos($activite->description, 'created') !== false) {
                                        $badgeClass = 'bg-primary';
                                        } elseif (stripos($activite->description, 'updated') !== false) {
                                        $badgeClass = 'bg-warning';
                                        } elseif (stripos($activite->description, 'deleted') !== false) {
                                        $badgeClass = 'bg-danger';
                                        }
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">
                                            @if(stripos($activite->description, 'created') !== false)
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
                                        Aucune activité trouvée pour ce module
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
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                Sélectionnez un module dans la liste pour voir ses activités
            </div>
            @endif
        </div>
    </div>
</div>
@endSection