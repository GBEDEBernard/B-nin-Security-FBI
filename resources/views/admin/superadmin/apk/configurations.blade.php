@extends('layouts.app')

@section('title', 'Configurations Application Mobile - Super Admin')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">
                <i class="bi bi-gear text-warning me-2"></i>
                Configurations de l'Application Mobile
            </h2>
            <p class="text-muted mb-0">Paramètres généraux de l'application mobile Bénin Security</p>
        </div>
        <a href="{{ route('admin.superadmin.apk.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Retour
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('admin.superadmin.apk.update-configurations') }}" method="POST">
        @csrf @method('PUT')

        <div class="row g-4">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0"><i class="bi bi-sliders me-2"></i>Paramètres généraux</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="form-label fw-semibold">URL de l'API</label>
                            <input type="url" name="url_api" class="form-control"
                                   value="{{ $configurations['url_api'] }}" required>
                            <small class="text-muted">URL de base que l'application mobile utilise pour les appels API</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Version minimum requise</label>
                            <div class="input-group">
                                <input type="text" name="version_minimum" class="form-control"
                                       value="{{ $configurations['version_minimum'] }}" required>
                                <span class="input-group-text">ou supérieure</span>
                            </div>
                            <small class="text-muted">Les utilisateurs avec une version inférieure seront invités à mettre à jour</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Message de maintenance</label>
                            <textarea name="message_maintenance" class="form-control" rows="3"
                                      placeholder="Laissez vide si aucun message...">{{ $configurations['message_maintenance'] }}</textarea>
                            <small class="text-muted">Message affiché aux utilisateurs quand le mode maintenance est activé</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0"><i class="bi bi-toggle-on me-2"></i>Fonctionnalités</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Notifications push</strong>
                                    <small class="text-muted d-block">Envoyer des notifications aux utilisateurs</small>
                                </div>
                                <div class="form-check form-switch">
                                    <input type="checkbox" name="notification_active" class="form-check-input"
                                           id="notificationSwitch" value="1"
                                           {{ $configurations['notification_active'] ? 'checked' : '' }}>
                                    <label class="form-check-label" for="notificationSwitch"></label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Géolocalisation</strong>
                                    <small class="text-muted d-block">Suivi de position des agents</small>
                                </div>
                                <div class="form-check form-switch">
                                    <input type="checkbox" name="geolocalisation_active" class="form-check-input"
                                           id="geolocSwitch" value="1"
                                           {{ $configurations['geolocalisation_active'] ? 'checked' : '' }}>
                                    <label class="form-check-label" for="geolocSwitch"></label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Mode maintenance</strong>
                                    <small class="text-muted d-block">Désactiver temporairement l'accès</small>
                                </div>
                                <div class="form-check form-switch">
                                    <input type="checkbox" name="maintenance_mode" class="form-check-input"
                                           id="maintenanceSwitch" value="1"
                                           {{ $configurations['maintenance_mode'] ? 'checked' : '' }}>
                                    <label class="form-check-label" for="maintenanceSwitch"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-warning w-100 mt-4 btn-lg">
                    <i class="bi bi-save me-2"></i> Enregistrer les modifications
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
