@extends('layouts.app')

@section('title', 'Détails de la Notification - Super Admin')

@section('content')
<div class="container-fluid p-4">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">
                <i class="bi bi-bell text-warning me-2"></i>
                Détails de la Notification
            </h2>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.superadmin.notifications.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Retour
            </a>
            @if(is_null($notification->lu_le))
            <form method="POST" action="{{ route('admin.superadmin.notifications.mark-read', $notification->id) }}">
                @csrf
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check me-1"></i> Marquer comme lu
                </button>
            </form>
            @endif
            <form method="POST" action="{{ route('admin.superadmin.notifications.destroy', $notification->id) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette notification?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-trash me-1"></i> Supprimer
                </button>
            </form>
        </div>
    </div>

    <div class="row">
        <!-- Détails de la notification -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex align-items-center">
                        @switch($notification->type)
                        @case('info')
                        <span class="badge bg-info fs-6 me-2"><i class="bi bi-info-circle me-1"></i>Information</span>
                        @break
                        @case('success')
                        <span class="badge bg-success fs-6 me-2"><i class="bi bi-check-circle me-1"></i>Succès</span>
                        @break
                        @case('warning')
                        <span class="badge bg-warning fs-6 me-2"><i class="bi bi-exclamation-triangle me-1"></i>Avertissement</span>
                        @break
                        @case('error')
                        <span class="badge bg-danger fs-6 me-2"><i class="bi bi-x-circle me-1"></i>Erreur</span>
                        @break
                        @endswitch
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
                    </div>
                </div>
                <div class="card-body">
                    <h4 class="mb-3">{{ $notification->titre }}</h4>
                    <p class="mb-4" style="white-space: pre-wrap;">{{ $notification->message }}</p>

                    @if($notification->url)
                    <hr>
                    <div class="mb-3">
                        <label class="form-label text-muted">URL cible</label>
                        <a href="{{ $notification->url }}" target="_blank" class="d-block">
                            {{ $notification->url }}
                            <i class="bi bi-box-arrow-up-right ms-1"></i>
                        </a>
                    </div>
                    @endif

                    @if($notification->donnees)
                    <hr>
                    <div class="mb-3">
                        <label class="form-label text-muted">Données supplémentaires</label>
                        <pre class="bg-light p-3 rounded"><code>{{ json_encode($notification->donnees, JSON_PRETTY_PRINT) }}</code></pre>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Informations supplémentaires -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>Informations
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">Statut de lecture</small>
                        @if($notification->lu_le)
                        <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Lu le {{ $notification->lu_le->format('d/m/Y à H:i') }}</span>
                        @else
                        <span class="badge bg-warning text-dark"><i class="bi bi-eye-slash me-1"></i>Non lu</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block">Date de création</small>
                        <strong>{{ $notification->created_at->format('d/m/Y à H:i:s') }}</strong>
                    </div>

                    @if($notification->entreprise)
                    <div class="mb-3">
                        <small class="text-muted d-block">Entreprise</small>
                        <strong>{{ $notification->entreprise->nom_entreprise }}</strong>
                    </div>
                    @else
                    <div class="mb-3">
                        <small class="text-muted d-block">Cible</small>
                        <strong>Tous les utilisateurs</strong>
                    </div>
                    @endif

                    @if($notification->envoyeur)
                    <div class="mb-3">
                        <small class="text-muted d-block">Envoyé par</small>
                        <strong>{{ $notification->envoyeur->name ?? 'Système' }}</strong>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Destinataires -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-people me-2"></i>Destinataires
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">Type de destinataire</small>
                        @if($notification->cible_type)
                        <strong>{{ class_basename($notification->cible_type) }}</strong>
                        @else
                        <strong>Tous les utilisateurs</strong>
                        @endif
                    </div>

                    @if($notification->cible_id)
                    <div class="mb-3">
                        <small class="text-muted d-block">ID Destinataire</small>
                        <strong>{{ $notification->cible_id }}</strong>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection