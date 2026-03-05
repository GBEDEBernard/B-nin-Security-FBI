@extends('layouts.app')

@section('title', 'Modifier le Site - Entreprise')

@push('styles')
<style>
    .form-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08);
    }

    .section-title {
        font-size: 0.9rem;
        font-weight: 600;
        color: #198754;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #198754;
    }

    .form-label {
        font-weight: 500;
        color: #495057;
        font-size: 0.9rem;
    }

    .form-control,
    .form-select {
        border-radius: 8px;
        border: 1.5px solid #e0e0e0;
        padding: 0.6rem 0.75rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #198754;
        box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.15);
    }
</style>
@endpush

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0"><i class="bi bi-pencil me-2"></i>Modifier le Site</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.entreprise.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.entreprise.sites.index') }}">Sites</a></li>
                    <li class="breadcrumb-item active">Modifier</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <form action="{{ route('admin.entreprise.sites.update', $site->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-lg-8">
                    <div class="card form-card mb-4">
                        <div class="card-body">
                            <div class="section-title"><i class="bi bi-info-circle me-2"></i>Informations générales</div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Client <span class="text-danger">*</span></label>
                                    <select name="client_id" class="form-select" required>
                                        <option value="">Sélectionner</option>
                                        @foreach($clients as $client)
                                        <option value="{{ $client->id }}" {{ old('client_id', $site->client_id) == $client->id ? 'selected' : '' }}>{{ $client->nom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Nom du site <span class="text-danger">*</span></label>
                                    <input type="text" name="nom_site" class="form-control" value="{{ old('nom_site', $site->nom_site) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Code site</label>
                                    <input type="text" name="code_site" class="form-control" value="{{ old('code_site', $site->code_site) }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card form-card mb-4">
                        <div class="card-body">
                            <div class="section-title"><i class="bi bi-geo me-2"></i>Adresse</div>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Adresse <span class="text-danger">*</span></label>
                                    <input type="text" name="adresse" class="form-control" value="{{ old('adresse', $site->adresse) }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Ville</label>
                                    <input type="text" name="ville" class="form-control" value="{{ old('ville', $site->ville) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Commune</label>
                                    <input type="text" name="commune" class="form-control" value="{{ old('commune', $site->commune) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Quartier</label>
                                    <input type="text" name="quartier" class="form-control" value="{{ old('quartier', $site->quartier) }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card form-card mb-4">
                        <div class="card-body">
                            <div class="section-title"><i class="bi bi-person me-2"></i>Contact</div>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Nom contact</label>
                                    <input type="text" name="contact_nom" class="form-control" value="{{ old('contact_nom', $site->contact_nom) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Téléphone</label>
                                    <input type="text" name="contact_telephone" class="form-control" value="{{ old('contact_telephone', $site->contact_telephone) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="contact_email" class="form-control" value="{{ old('contact_email', $site->contact_email) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card form-card mb-4">
                        <div class="card-body">
                            <div class="section-title"><i class="bi bi-exclamation-triangle me-2"></i>Risque & Statut</div>
                            <div class="mb-3">
                                <label class="form-label">Niveau de risque</label>
                                <select name="niveau_risque" class="form-select">
                                    <option value="faible" {{ old('niveau_risque', $site->niveau_risque) == 'faible' ? 'selected' : '' }}>Faible</option>
                                    <option value="moyen" {{ old('niveau_risque', $site->niveau_risque) == 'moyen' ? 'selected' : '' }}>Moyen</option>
                                    <option value="haut" {{ old('niveau_risque', $site->niveau_risque) == 'haut' ? 'selected' : '' }}>Haut</option>
                                    <option value="critique" {{ old('niveau_risque', $site->niveau_risque) == 'critique' ? 'selected' : '' }}>Critique</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" name="est_actif" id="est_actif" class="form-check-input" value="1" {{ old('est_actif', $site->est_actif) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="est_actif">Site actif</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card form-card mb-4">
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success"><i class="bi bi-check-circle me-2"></i>Mettre à jour</button>
                                <a href="{{ route('admin.entreprise.sites.show', $site->id) }}" class="btn btn-outline-secondary">Retour</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection