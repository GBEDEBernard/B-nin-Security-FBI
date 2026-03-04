@extends('layouts.app')

@section('title', 'Journal d\'Activité - Super Admin')

@section('content')
    <div class="container-fluid p-4">
        <!-- En-tête -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0">
                    <i class="bi bi-clock-history text-secondary me-2"></i>
                    Journal d'Activité
                </h2>
                <p class="text-muted mb-0">Historique des actions et événements du système</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.superadmin.journal.statistiques') }}" class="btn btn-outline-info">
                    <i class="bi bi-bar-chart me-1"></i> Statistiques
                </a>
                <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#exportModal">
                    <i class="bi bi-download me-1"></i> Exporter
                </button>
                <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#purgeModal">
                    <i class="bi bi-trash me-1"></i> Purger
                </button>
            </div>
        </div>

        <!-- Filtres -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.superadmin.journal.index') }}" class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label">Date début</label>
                        <input type="date" name="date_debut" class="form-control" value="{{ request('date_debut') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Date fin</label>
                        <input type="date" name="date_fin" class="form-control" value="{{ request('date_fin') }}">
                    </div>
                    <div class="col-md-2">
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
                    <div class="col-md-2">
                        <label class="form-label">Action</label>
                        <select name="action" class="form-select">
                            <option value="">Toutes</option>
                            <option value="connexion" {{ request('action') == 'connexion' ? 'selected' : '' }}>Connexion</option>
                            <option value="created" {{ request('action') == 'created' ? 'selected' : '' }}>Création</option>
                            <option value="updated" {{ request('action') == 'updated' ? 'selected' : '' }}>Modification</option>
                            <option value="deleted" {{ request('action') == 'deleted' ? 'selected' : '' }}>Suppression</option>
                            <option value="login" {{ request('action') == 'login' ? 'selected' : '' }}>Authentification</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Module</label>
                        <select name="module" class="form-select">
                            <option value="">Tous</option>
                            <option value="Entreprise" {{ request('module') == 'Entreprise' ? 'selected' : '' }}>Entreprises</option>
                            <option value="User" {{ request('module') == 'User' ? 'selected' : '' }}>Utilisateurs</option>
                            <option value="Contrat" {{ request('module') == 'Contrat' ? 'selected' : '' }}>Contrats</option>
                            <option value="Facture" {{ request('module') == 'Facture' ? 'selected' : '' }}>Factures</option>
                            <option value="Employe" {{ request('module') == 'Employe' ? 'selected' : '' }}>Employés</option>
                            <option value="Client" {{ request('module') == 'Client' ? 'selected' : '' }}>Clients</option>
                            <option value="Abonnement" {{ request('module') == 'Abonnement' ? 'selected' : '' }}>Abonnements</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <div class="d-flex gap-2 w-100">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                <i class="bi bi-search me-1"></i> Rechercher
                            </button>
                            <a href="{{ route('admin.superadmin.journal.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Statistiques rapides -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted mb-1">Connexions aujourd'hui</h6>
                                <h3 class="mb-0 text-success">{{ $stats['connexions_today'] ?? 0 }}</h3>
                            </div>
                            <i class="bi bi-person-check fs-1 text-success opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted mb-1">Créations aujourd'hui</h6>
                                <h3 class="mb-0 text-primary">{{ $stats['creations_today'] ?? 0 }}</h3>
                            </div>
                            <i class="bi bi-plus-circle fs-1 text-primary opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted mb-1">Modifications aujourd'hui</h6>
                                <h3 class="mb-0 text-warning">{{ $stats['modifications_today'] ?? 0 }}</h3>
                            </div>
                            <i class="bi bi-pencil fs-1 text-warning opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-danger">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted mb-1">Erreurs aujourd'hui</h6>
                                <h3 class="mb-0 text-danger">{{ $stats['errors_today'] ?? 0 }}</h3>
                            </div>
                            <i class="bi bi-exclamation-triangle fs-1 text-danger opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation par onglets -->
        <ul class="nav nav-tabs mb-3">
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('admin.superadmin.journal.index') }}">
                    <i class="bi bi-list-ul me-1"></i> Toutes les activités
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.superadmin.journal.par-utilisateur') }}">
                    <i class="bi bi-person me-1"></i> Par utilisateur
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.superadmin.journal.par-module') }}">
                    <i class="bi bi-grid me-1"></i> Par module
                </a>
            </li>
        </ul>

        <!-- Tableau du journal -->
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
                                        @if($activite->causer_id)
                                            @php
                                                $causer = \App\Models\User::find($activite->causer_id);
                                            @endphp
                                            @if($causer)
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-primary text-white rounded-circle me-2">
                                                        {{ substr($causer->name, 0, 1) }}
                                                    </div>
                                                    <span>{{ $causer->name }}</span>
                                                </div>
                                            @else
                                                <span class="text-muted">Utilisateur #{{ $activite->causer_id }}</span>
                                            @endif
                                        @else
                                            <span class="text-muted">Système</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $badgeClass = 'bg-secondary';
                                            if (stripos($activite->description, 'connexion') !== false || stripos($activite->description, 'login') !== false) {
                                                $badgeClass = 'bg-success';
                                            } elseif (stripos($activite->description, 'created') !== false || stripos($activite->description, 'création') !== false) {
                                                $badgeClass = 'bg-primary';
                                            } elseif (stripos($activite->description, 'updated') !== false || stripos($activite->description, 'modification') !== false) {
                                                $badgeClass = 'bg-warning';
                                            } elseif (stripos($activite->description, 'deleted') !== false || stripos($activite->description, 'suppression') !== false || stripos($activite->description, 'deleted') !== false) {
                                                $badgeClass = 'bg-danger';
                                            } elseif (stripos($activite->description, 'error') !== false || stripos($activite->description, 'erreur') !== false) {
                                                $badgeClass = 'bg-danger';
                                            } elseif (stripos($activite->description, 'export') !== false) {
                                                $badgeClass = 'bg-info';
                                            }
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">
                                            @if(stripos($activite->description, 'connexion') !== false || stripos($activite->description, 'login') !== false)
                                                Connexion
                                            @elseif(stripos($activite->description, 'created') !== false || stripos($activite->description, 'création') !== false)
                                                Création
                                            @elseif(stripos($activite->description, 'updated') !== false || stripos($activite->description, 'modification') !== false)
                                                Modification
                                            @elseif(stripos($activite->description, 'deleted') !== false || stripos($activite->description, 'suppression') !== false)
                                                Suppression
                                            @elseif(stripos($activite->description, 'error') !== false || stripos($activite->description, 'erreur') !== false)
                                                Erreur
                                            @elseif(stripos($activite->description, 'export') !== false)
                                                Export
                                            @else
                                                Action
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        @if($activite->subject_type)
                                            @php
                                                $module = class_basename($activite->subject_type);
                                            @endphp
                                            @switch($module)
                                                @case('Entreprise')
                                                    <i class="bi bi-building text-primary me-1"></i>
                                                    @break
                                                @case('User')
                                                    <i class="bi bi-person text-info me-1"></i>
                                                    @break
                                                @case('Contrat')
                                                    <i class="bi bi-file-text text-success me-1"></i>
                                                    @break
                                                @case('Facture')
                                                    <i class="bi bi-receipt text-warning me-1"></i>
                                                    @break
                                                @case('Employe')
                                                    <i class="bi bi-people text-primary me-1"></i>
                                                    @break
                                                @case('Client')
                                                    <i class="bi bi-person-badge text-info me-1"></i>
                                                    @break
                                                @case('Abonnement')
                                                    <i class="bi bi-credit-card text-secondary me-1"></i>
                                                    @break
                                                @default
                                                    <i class="bi bi-folder text-muted me-1"></i>
                                            @endswitch
                                            {{ $module }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $activite->description ?? 'Aucune description' }}</small>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.superadmin.journal.show', $activite->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        Aucune activité trouvée
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Affichage de {{ $activites->firstItem() ?? 0 }} à {{ $activites->lastItem() ?? 0 }} sur {{ $activites->total() }} enregistrements
                    </div>
                    {{ $activites->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Export -->
    <div class="modal fade" id="exportModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Exporter le journal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('admin.superadmin.journal.export') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Date début</label>
                            <input type="date" name="date_debut" class="form-control" value="{{ request('date_debut') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Date fin</label>
                            <input type="date" name="date_fin" class="form-control" value="{{ request('date_fin') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Format d'export</label>
                            <select name="format" class="form-select">
                                <option value="csv">CSV</option>
                                <option value="excel">Excel</option>
                                <option value="pdf">PDF</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Exporter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Purge -->
    <div class="modal fade" id="purgeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger">Purger le journal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('admin.superadmin.journal.purge') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Cette action est irréversible. Toutes les activités older than the specified period will be permanently deleted.
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Conserver les activités des derniers</label>
                            <select name="jours" class="form-select">
                                <option value="7">7 jours</option>
                                <option value="30">30 jours</option>
                                <option value="60">60 jours</option>
                                <option value="90">90 jours</option>
                                <option value="180">6 mois</option>
                                <option value="365">1 an</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-danger">Purger</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endSection

