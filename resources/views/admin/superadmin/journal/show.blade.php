@extends('layouts.app')

@section('title', 'Détails de l\'Activité - Super Admin')

@section('content')
    <div class="container-fluid p-4">
        <!-- En-tête -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0">
                    <i class="bi bi-clock-history text-secondary me-2"></i>
                    Détails de l'Activité
                </h2>
                <p class="text-muted mb-0">Information complète sur l'action effectuée</p>
            </div>
            <a href="{{ route('admin.superadmin.journal.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left me-1"></i> Retour au journal
            </a>
        </div>

        <div class="row">
            <div class="col-md-8">
                <!-- Informations principales -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Informations de l'Activité</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td class="text-muted" style="width: 200px;">ID</td>
                                <td><strong>#{{ $activite->id }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Date et Heure</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($activite->created_at)->format('d/m/Y à H:i:s') }}
                                    <span class="text-muted">({{ \Carbon\Carbon::parse($activite->created_at)->diffForHumans() }})</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Description</td>
                                <td>
                                    <span class="badge bg-primary fs-6">{{ $activite->description ?? 'Aucune description' }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Type d'action</td>
                                <td>
                                    @php
                                        $badgeClass = 'bg-secondary';
                                        if (stripos($activite->description, 'connexion') !== false || stripos($activite->description, 'login') !== false) {
                                            $badgeClass = 'bg-success';
                                        } elseif (stripos($activite->description, 'created') !== false || stripos($activite->description, 'création') !== false) {
                                            $badgeClass = 'bg-primary';
                                        } elseif (stripos($activite->description, 'updated') !== false || stripos($activite->description, 'modification') !== false) {
                                            $badgeClass = 'bg-warning';
                                        } elseif (stripos($activite->description, 'deleted') !== false || stripos($activite->description, 'suppression') !== false) {
                                            $badgeClass = 'bg-danger';
                                        } elseif (stripos($activite->description, 'error') !== false || stripos($activite->description, 'erreur') !== false) {
                                            $badgeClass = 'bg-danger';
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
                                        @else
                                            Action
                                        @endif
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Informations sur l'utilisateur -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-person me-2"></i>Utilisateur</h5>
                    </div>
                    <div class="card-body">
                        @if($activite->causer_id)
                            @php
                                $causer = \App\Models\User::find($activite->causer_id);
                            @endphp
                            @if($causer)
                                <div class="d-flex align-items-center">
                                    <div bg-primary text-white class="avatar-lg rounded-circle me-3 d-flex align-items-center justify-content-center">
                                        {{ substr($causer->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h5 class="mb-0">{{ $causer->name }}</h5>
                                        <p class="text-muted mb-0">{{ $causer->email }}</p>
                                        @if($causer->estSuperAdmin())
                                            <span class="badge bg-danger">Super Admin</span>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    Utilisateur #{{ $activite->causer_id }} non trouvé (peut avoir été supprimé)
                                </div>
                            @endif
                        @else
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                Action effectuée par le système (pas d'utilisateur connecté)
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Informations sur l'objet -->
                @if($activite->subject_type || $activite->subject_id)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-folder me-2"></i>Objet Concerné</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-muted" style="width: 200px;">Type</td>
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
                                </tr>
                                <tr>
                                    <td class="text-muted">ID de l'objet</td>
                                    <td><strong>#{{ $activite->subject_id ?? '-' }}</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-md-4">
                <!-- Propriétés supplémentaires -->
                @if($activite->properties)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Propriétés</h5>
                        </div>
                        <div class="card-body">
                            <pre class="bg-light p-3 rounded" style="font-size: 0.85rem;">{{ json_encode(json_decode($activite->properties), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                    </div>
                @endif

                <!-- Actions rapides -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-lightning me-2"></i>Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.superadmin.journal.index', ['user_id' => $activite->causer_id]) }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-person me-1"></i> Voir toutes les activités de cet utilisateur
                            </a>
                            @if($activite->subject_type && $activite->subject_id)
                                @php
                                    $module = class_basename($activite->subject_type);
                                @endphp
                                @if($module == 'User')
                                    <a href="{{ route('admin.superadmin.utilisateurs.show', $activite->subject_id) }}" class="btn btn-outline-info btn-sm">
                                        <i class="bi bi-eye me-1"></i> Voir le profil utilisateur
                                    </a>
                                @elseif($module == 'Entreprise')
                                    <a href="{{ route('admin.superadmin.entreprises.show', $activite->subject_id) }}" class="btn btn-outline-info btn-sm">
                                        <i class="bi bi-building me-1"></i> Voir l'entreprise
                                    </a>
                                @elseif($module == 'Contrat')
                                    <a href="{{ route('admin.superadmin.contrats.show', $activite->subject_id) }}" class="btn btn-outline-info btn-sm">
                                        <i class="bi bi-file-text me-1"></i> Voir le contrat
                                    </a>
                                @endif
                            @endif
                            <button class="btn btn-outline-secondary btn-sm" onclick="window.print()">
                                <i class="bi bi-printer me-1"></i> Imprimer cette page
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Activités récentes du même utilisateur -->
                @if($activite->causer_id)
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-clock me-2"></i>Activités récentes</h5>
                        </div>
                        <div class="card-body p-0">
                            @php
                                $recentActivities = \Illuminate\Support\Facades\DB::table('activity_log')
                                    ->where('causer_id', $activite->causer_id)
                                    ->where('id', '!=', $activite->id)
                                    ->orderBy('created_at', 'desc')
                                    ->limit(5)
                                    ->get();
                            @endphp
                            @if($recentActivities->count() > 0)
                                <div class="list-group list-group-flush">
                                    @foreach($recentActivities as $recent)
                                        <a href="{{ route('admin.superadmin.journal.show', $recent->id) }}" class="list-group-item list-group-item-action">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small>{{ Str::limit($recent->description, 40) }}</small>
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($recent->created_at)->format('H:i') }}</small>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <div class="card-body text-muted text-center">
                                    <i class="bi bi-inbox d-block mb-2"></i>
                                    Aucune autre activité
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endSection

