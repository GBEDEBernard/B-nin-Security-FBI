@extends('layouts.app')

@section('title', 'Modifier le Site - Entreprise')

@push('styles')
<style>
    /* ═════════════════════════════════════════════════════════════
       STYLES MODERNES POUR LE FORMULAIRE DE MODIFICATION DE SITE
       Compatible mode clair et sombre
       ═════════════════════════════════════════════════════════════ */

    .page-header {
        background: linear-gradient(135deg, #0d6efd 0%, #6ea8fe 100%);
        padding: 1.5rem;
        border-radius: 16px;
        margin-bottom: 1.5rem;
        color: white;
    }

    .page-header h3 {
        margin: 0;
        font-weight: 600;
        font-size: 1.5rem;
    }

    .page-header .breadcrumb {
        margin: 0;
        padding: 0;
        background: transparent;
    }

    .page-header .breadcrumb-item,
    .page-header .breadcrumb-item a {
        color: rgba(255, 255, 255, 0.85);
        font-size: 0.875rem;
    }

    .page-header .breadcrumb-item.active {
        color: white;
        font-weight: 500;
    }

    .page-header .breadcrumb-item+.breadcrumb-item::before {
        color: rgba(255, 255, 255, 0.6);
    }

    .form-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.08);
        background: var(--bs-body-bg);
        overflow: hidden;
    }

    .form-card .card-header {
        background: linear-gradient(135deg, #0d6efd 0%, #6ea8fe 100%);
        padding: 1rem 1.5rem;
        border: none;
        color: white;
    }

    .form-card .card-header h5 {
        margin: 0;
        font-weight: 600;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-card .card-body {
        padding: 1.5rem;
    }

    .section-title {
        font-size: 0.85rem;
        font-weight: 700;
        color: #0d6efd;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 1.25rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #0d6efd;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-label {
        font-weight: 600;
        color: var(--bs-body-color);
        font-size: 0.9rem;
        margin-bottom: 0.4rem;
    }

    .form-control,
    .form-select {
        border-radius: 10px;
        border: 1.5px solid var(--bs-border-color);
        padding: 0.65rem 0.9rem;
        font-size: 0.95rem;
        background-color: var(--bs-body-bg);
        color: var(--bs-body-color);
        transition: all 0.2s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    }

    /* Custom Select with Icons */
    .custom-select-green {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%230d6efd' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 12px 12px;
        padding-right: 2.5rem;
    }

    .form-select optgroup {
        font-weight: 600;
        color: #0d6efd;
    }

    .form-select option {
        padding: 0.5rem 0.75rem;
    }

    /* Style spécifique pour le select afin que le texte soit bien visible */
    .form-select {
        color: var(--bs-body-color);
        background-color: var(--bs-body-bg);
    }

    /* Force le style pour le mode sombre - background sombre avec texte clair */
    [data-bs-theme="dark"] .form-select {
        background-color: #1e1e1e !important;
        color: #ffffff !important;
        border-color: #444444;
    }

    /* Options de select en mode sombre */
    [data-bs-theme="dark"] .form-select option {
        background-color: #1e1e1e;
        color: #ffffff;
        padding: 0.5rem;
    }

    /* En mode clair, on garde un fond clair */
    [data-bs-theme="light"] .form-select {
        background-color: #ffffff;
        color: #212529;
        border-color: #dee2e6;
    }

    [data-bs-theme="light"] .form-select option {
        background-color: #ffffff;
        color: #212529;
    }

    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    .form-check-input:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    }

    .btn-update {
        background: linear-gradient(135deg, #0d6efd 0%, #6ea8fe 100%);
        border: none;
        padding: 0.75rem 2rem;
        font-weight: 600;
        border-radius: 10px;
        transition: all 0.2s ease;
    }

    .btn-update:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
    }

    .btn-cancel {
        background: var(--bs-tertiary-bg);
        border: 1.5px solid var(--bs-border-color);
        color: var(--bs-body-color);
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        border-radius: 10px;
        transition: all 0.2s ease;
    }

    .btn-cancel:hover {
        background: var(--bs-secondary-bg);
        border-color: var(--bs-border-color);
        color: var(--bs-body-color);
    }

    .btn-show {
        background: var(--bs-tertiary-bg);
        border: 1.5px solid #0d6efd;
        color: #0d6efd;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        border-radius: 10px;
        transition: all 0.2s ease;
    }

    .btn-show:hover {
        background: #0d6efd;
        color: white;
    }

    .required-indicator {
        color: #dc3545;
        font-weight: 700;
    }

    .help-text {
        font-size: 0.8rem;
        color: var(--bs-secondary-color);
        margin-top: 0.25rem;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--bs-border-color);
    }

    /* Animation sutilité */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fadeIn 0.3s ease-out;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- En-tête de page -->
    <div class="page-header animate-fade-in">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h3><i class="bi bi-pencil-square me-2"></i>Modifier le Site</h3>
            </div>
            <div class="col-md-6">
                <ol class="breadcrumb float-md-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.entreprise.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.entreprise.sites.index') }}">Sites</a></li>
                    <li class="breadcrumb-item active">Modifier</li>
                </ol>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.entreprise.sites.update', $site->id) }}" method="POST" class="animate-fade-in" style="animation-delay: 0.1s;">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Colonne principale -->
            <div class="col-lg-8">
                <!-- Informations générales -->
                <div class="card form-card mb-4">
                    <div class="card-header">
                        <h5><i class="bi bi-info-circle-fill"></i> Informations générales</h5>
                    </div>
                    <div class="card-body">
                        <div class="section-title"><i class="bi bi-building"></i> Client & Site</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">
                                    Client <span class="required-indicator">*</span>
                                </label>

                                @if($clients->isEmpty())
                                <div class="alert alert-warning py-2 px-3 mb-2">
                                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                    <small>Aucun client avec contrat actif disponible.</small>
                                </div>
                                @endif

                                <select name="client_id" class="form-select custom-select-green" required>
                                    <option value="">— Sélectionner un client —</option>

                                    @php
                                    $particuliers = $clients->where('type_client', 'particulier');
                                    $entreprises = $clients->whereIn('type_client', ['entreprise', 'institution']);
                                    @endphp

                                    @if($particuliers->isNotEmpty())
                                    <optgroup label="Particuliers">
                                        @foreach($particuliers as $client)
                                        <option value="{{ $client->id }}"
                                            {{ old('client_id', $site->client_id) == $client->id ? 'selected' : '' }}>
                                            {{ trim(($client->prenoms ? $client->prenoms . ' ' : '') . strtoupper($client->nom ?? '')) }}
                                        </option>
                                        @endforeach
                                    </optgroup>
                                    @endif

                                    @if($entreprises->isNotEmpty())
                                    <optgroup label="Entreprises & Institutions">
                                        @foreach($entreprises as $client)
                                        <option value="{{ $client->id }}"
                                            {{ old('client_id', $site->client_id) == $client->id ? 'selected' : '' }}>
                                            {{ $client->raison_sociale ?? $client->nom ?? 'Sans nom' }}
                                        </option>
                                        @endforeach
                                    </optgroup>
                                    @endif

                                </select>
                                <div class="help-text">
                                    <i class="bi bi-info-circle"></i>
                                    Seuls les clients avec contrat actif apparaissent
                                </div>

                                @error('client_id')
                                <div class="help-text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">
                                    Nom du site <span class="required-indicator">*</span>
                                </label>
                                <input type="text" name="nom_site" class="form-control" value="{{ old('nom_site', $site->nom_site) }}" placeholder="Ex: Site principal" required>
                                @error('nom_site')
                                <div class="help-text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Code site</label>
                                <input type="text" name="code_site" class="form-control" value="{{ old('code_site', $site->code_site) }}" placeholder="Code unique">
                                <div class="help-text">Laissez vide pour auto-génération</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Niveau de risque</label>
                                <select name="niveau_risque" class="form-select">
                                    <option value="faible" {{ old('niveau_risque', $site->niveau_risque) == 'faible' ? 'selected' : '' }}>Faible</option>
                                    <option value="moyen" {{ old('niveau_risque', $site->niveau_risque) == 'moyen' ? 'selected' : '' }}>Moyen</option>
                                    <option value="haut" {{ old('niveau_risque', $site->niveau_risque) == 'haut' ? 'selected' : '' }}>Haut</option>
                                    <option value="critique" {{ old('niveau_risque', $site->niveau_risque) == 'critique' ? 'selected' : '' }}>Critique</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Adresse -->
                <div class="card form-card mb-4">
                    <div class="card-header">
                        <h5><i class="bi bi-geo-fill"></i> Adresse</h5>
                    </div>
                    <div class="card-body">
                        <div class="section-title"><i class="bi bi-pin-map"></i> Localisation</div>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">
                                    Adresse <span class="required-indicator">*</span>
                                </label>
                                <input type="text" name="adresse" class="form-control" value="{{ old('adresse', $site->adresse) }}" placeholder="Adresse complète" required>
                                @error('adresse')
                                <div class="help-text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Ville</label>
                                <input type="text" name="ville" class="form-control" value="{{ old('ville', $site->ville) }}" placeholder="Ex: Cotonou">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Commune</label>
                                <input type="text" name="commune" class="form-control" value="{{ old('commune', $site->commune) }}" placeholder="Ex: Cotonou">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Quartier</label>
                                <input type="text" name="quartier" class="form-control" value="{{ old('quartier', $site->quartier) }}" placeholder="Ex: Akando">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact -->
                <div class="card form-card mb-4">
                    <div class="card-header">
                        <h5><i class="bi bi-person-lines-fill"></i> Contact</h5>
                    </div>
                    <div class="card-body">
                        <div class="section-title"><i class="bi bi-person-badge"></i> Responsable site</div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Nom du contact</label>
                                <input type="text" name="contact_nom" class="form-control" value="{{ old('contact_nom', $site->contact_nom) }}" placeholder="Nom complet">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Téléphone</label>
                                <input type="text" name="contact_telephone" class="form-control" value="{{ old('contact_telephone', $site->contact_telephone) }}" placeholder="+229 XX XX XX XX">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Email</label>
                                <input type="email" name="contact_email" class="form-control" value="{{ old('contact_email', $site->contact_email) }}" placeholder="email@exemple.com">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne latérale -->
            <div class="col-lg-4">
                <!-- Statut -->
                <div class="card form-card mb-4">
                    <div class="card-header">
                        <h5><i class="bi bi-toggle-on"></i> Statut</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check form-switch mb-3">
                            <input type="checkbox" name="est_actif" id="est_actif" class="form-check-input" value="1" {{ old('est_actif', $site->est_actif) ? 'checked' : '' }}>
                            <label class="form-check-label" for="est_actif">Site actif</label>
                        </div>
                        <div class="help-text mb-3">
                            <i class="bi bi-info-circle me-1"></i>
                            Un site inactif n'apparaît pas dans les listes de sélection
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card form-card mb-4">
                    <div class="card-header">
                        <h5><i class="bi bi-gear-fill"></i> Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-update text-white">
                                <i class="bi bi-check-circle me-2"></i>Mettre à jour
                            </button>
                            <a href="{{ route('admin.entreprise.sites.show', $site->id) }}" class="btn btn-show">
                                <i class="bi bi-eye me-2"></i>Voir les détails
                            </a>
                            <a href="{{ route('admin.entreprise.sites.index') }}" class="btn btn-cancel">
                                <i class="bi bi-arrow-left me-2"></i>Retour à la liste
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection