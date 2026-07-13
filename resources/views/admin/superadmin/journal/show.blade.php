@extends('layouts.app')

@section('title', "Détail d'activité - Journal")

@push('styles')
<style>
    .prop-table td:first-child {
        width: 180px;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('admin.superadmin.journal.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h2 class="mb-0">
                    <i class="bi bi-clock-history text-secondary me-2"></i>
                    Détail de l'activité
                </h2>
            </div>
            <p class="text-muted mb-0">ID : #{{ $activite->id }}</p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Informations générales</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless prop-table mb-0">
                        <tr>
                            <td>Date/Heure</td>
                            <td>{{ $activite->created_at?->format('d/m/Y à H:i:s') }}</td>
                        </tr>
                        <tr>
                            <td>Description</td>
                            <td>{{ $activite->description }}</td>
                        </tr>
                        <tr>
                            <td>Action</td>
                            <td><span class="badge bg-{{ $activite->action_badge }}">{{ $activite->action_label }}</span></td>
                        </tr>
                        <tr>
                            <td>Module</td>
                            <td>{{ $activite->module_label }}</td>
                        </tr>
                        <tr>
                            <td>Utilisateur</td>
                            <td>{{ $activite->causer_name }}</td>
                        </tr>
                        <tr>
                            <td>Adresse IP</td>
                            <td><code>{{ $activite->ip_address ?? 'Non enregistrée' }}</code></td>
                        </tr>
                        @if($activite->log_name)
                            <tr>
                                <td>Catégorie</td>
                                <td><span class="badge bg-secondary">{{ $activite->log_name }}</span></td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>

            @if($activite->properties)
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0"><i class="bi bi-diagram-3 me-2"></i>Propriétés (JSON)</h5>
                    </div>
                    <div class="card-body">
                        <pre class="mb-0" style="max-height: 400px; overflow-y: auto;"><code>{{ json_encode($activite->properties, JSON_PRETTY_PRINT) }}</code></pre>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="bi bi-link-45deg me-2"></i>Références</h5>
                </div>
                <div class="card-body">
                    <dl class="mb-0">
                        <dt class="text-muted small">Sujet (type)</dt>
                        <dd class="mb-3">{{ $activite->subject_type ?? '—' }}</dd>
                        <dt class="text-muted small">Sujet (ID)</dt>
                        <dd class="mb-3">{{ $activite->subject_id ?? '—' }}</dd>
                        <dt class="text-muted small">Auteur (type)</dt>
                        <dd class="mb-3">{{ $activite->causer_type ?? '—' }}</dd>
                        <dt class="text-muted small">Auteur (ID)</dt>
                        <dd class="mb-0">{{ $activite->causer_id ?? '—' }}</dd>
                    </dl>
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="bi bi-arrow-left-right me-2"></i>Navigation</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.superadmin.journal.index') }}" class="btn btn-outline-secondary w-100 mb-2">
                        <i class="bi bi-list-ul me-1"></i> Retour au journal
                    </a>
                    <a href="{{ route('admin.superadmin.journal.statistiques') }}" class="btn btn-outline-primary w-100">
                        <i class="bi bi-bar-chart me-1"></i> Voir les statistiques
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
