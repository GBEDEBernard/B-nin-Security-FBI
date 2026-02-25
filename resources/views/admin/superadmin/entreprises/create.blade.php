@extends('layouts.app')

@section('title', 'Nouvelle Entreprise - Super Admin')

@push('styles')
<style>
    .form-wizard .nav-tabs {
        border-bottom: 2px solid #e9ecef;
        margin-bottom: 2rem;
    }

    .form-wizard .nav-tabs .nav-link {
        border: none;
        color: #6c757d;
        font-weight: 500;
        padding: 0.75rem 1.5rem;
        position: relative;
        transition: all 0.3s ease;
    }

    .form-wizard .nav-tabs .nav-link::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 2px;
        background: #198754;
        transition: width 0.3s ease;
    }

    .form-wizard .nav-tabs .nav-link:hover {
        color: #198754;
    }

    .form-wizard .nav-tabs .nav-link.active {
        color: #198754;
        background: transparent;
    }

    .form-wizard .nav-tabs .nav-link.active::after {
        width: 100%;
    }

    .form-wizard .tab-content {
        min-height: 400px;
    }

    .form-section {
        display: none;
        animation: fadeIn 0.4s ease;
    }

    .form-section.active {
        display: block;
    }

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

    .preview-logo {
        width: 120px;
        height: 120px;
        border-radius: 12px;
        object-fit: cover;
        border: 2px dashed #dee2e6;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f9fa;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .preview-logo:hover {
        border-color: #198754;
        background: #e9f7ef;
    }

    .preview-logo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 10px;
    }

    .color-picker-wrapper {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .color-preview {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        border: 2px solid #dee2e6;
    }

    .form-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08);
    }

    .form-label {
        font-weight: 500;
        color: #495057;
        margin-bottom: 0.4rem;
    }

    .form-control,
    .form-select {
        border-radius: 10px;
        padding: 0.6rem 1rem;
        border: 1.5px solid #e9ecef;
        transition: all 0.2s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #198754;
        box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.15);
    }

    .btn-create {
        padding: 0.7rem 2rem;
        border-radius: 10px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-create:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(25, 135, 84, 0.3);
    }

    .required-indicator {
        color: #dc3545;
    }

    .wizard-buttons {
        display: flex;
        justify-content: space-between;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e9ecef;
    }

    .form-check-input:checked {
        background-color: #198754;
        border-color: #198754;
    }

    .package-card {
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 1.25rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
    }

    .package-card:hover {
        border-color: #198754;
        transform: translateY(-3px);
    }

    .package-card.selected {
        border-color: #198754;
        background: linear-gradient(135deg, rgba(25, 135, 84, 0.05) 0%, rgba(32, 201, 151, 0.05) 100%);
    }

    .package-card .package-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.75rem;
        font-size: 1.25rem;
    }
</style>
@endpush

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0"><i class="bi bi-building-fill-add me-2"></i>Nouvelle Entreprise</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.entreprises.index') }}">Entreprises</a></li>
                    <li class="breadcrumb-item active">Nouvelle</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!--end::App Content Header-->

<!--begin::App Content-->
<div class="app-content">
    <div class="container-fluid">
        <!-- Messages d'erreur de validation -->
        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Erreurs de validation :</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <form action="{{ route('admin.superadmin.entreprises.store') }}" method="POST" enctype="multipart/form-data" class="form-wizard">
            @csrf

            <div class="card form-card">
                <div class="card-body p-4">
                    <!-- Navigation par onglets -->
                    <ul class="nav nav-tabs" id="entrepriseTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">
                                <i class="bi bi-building me-2"></i>Informations générales
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab">
                                <i class="bi bi-telephone me-2"></i>Contact
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="representant-tab" data-bs-toggle="tab" data-bs-target="#representant" type="button" role="tab">
                                <i class="bi bi-person-badge me-2"></i>Représentant légal
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="abonnement-tab" data-bs-toggle="tab" data-bs-target="#abonnement" type="button" role="tab">
                                <i class="bi bi-credit-card me-2"></i>Abonnement
                            </button>
                        </li>
                    </ul>

                    <!-- Contenu des onglets -->
                    <div class="tab-content" id="entrepriseTabsContent">

                        <!-- Onglet 1: Informations générales -->
                        <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                            <div class="row g-4">
                                <!-- Nom de l'entreprise -->
                                <div class="col-md-6">
                                    <label for="nom_entreprise" class="form-label">
                                        Nom de l'entreprise <span class="required-indicator">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('nom_entreprise') is-invalid @enderror"
                                        id="nom_entreprise" name="nom_entreprise"
                                        value="{{ old('nom_entreprise') }}" required>
                                    @error('nom_entreprise')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Nom commercial -->
                                <div class="col-md-6">
                                    <label for="nom_commercial" class="form-label">Nom commercial</label>
                                    <input type="text" class="form-control" id="nom_commercial" name="nom_commercial"
                                        value="{{ old('nom_commercial') }}">
                                </div>

                                <!-- Slug -->
                                <div class="col-md-6">
                                    <label for="slug" class="form-label">Slug (URL)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">/</span>
                                        <input type="text" class="form-control" id="slug" name="slug"
                                            value="{{ old('slug') }}" placeholder="mon-entreprise">
                                    </div>
                                    <small class="text-muted">Laissez vide pour générer automatiquement</small>
                                </div>

                                <!-- Forme juridique -->
                                <div class="col-md-6">
                                    <label for="forme_juridique" class="form-label">Forme juridique</label>
                                    <select class="form-select" id="forme_juridique" name="forme_juridique">
                                        <option value="">Sélectionner...</option>
                                        <option value="SARL" {{ old('forme_juridique') == 'SARL' ? 'selected' : '' }}>SARL</option>
                                        <option value="SA" {{ old('forme_juridique') == 'SA' ? 'selected' : '' }}>SA</option>
                                        <option value="SNC" {{ old('forme_juridique') == 'SNC' ? 'selected' : '' }}>SNC</option>
                                        <option value="EI" {{ old('forme_juridique') == 'EI' ? 'selected' : '' }}>EI (Entreprise Individuelle)</option>
                                        <option value="GIE" {{ old('forme_juridique') == 'GIE' ? 'selected' : '' }}>GIE</option>
                                        <option value="Association" {{ old('forme_juridique') == 'Association' ? 'selected' : '' }}>Association</option>
                                        <option value="Autre" {{ old('forme_juridique') == 'Autre' ? 'selected' : '' }}>Autre</option>
                                    </select>
                                </div>

                                <!-- Numéro de registre -->
                                <div class="col-md-4">
                                    <label for="numero_registre" class="form-label">N° Registre de commerce</label>
                                    <input type="text" class="form-control" id="numero_registre" name="numero_registre"
                                        value="{{ old('numero_registre') }}">
                                </div>

                                <!-- Numéro IF -->
                                <div class="col-md-4">
                                    <label for="numeroIdentificationFiscale" class="form-label">N° Identification Fiscale</label>
                                    <input type="text" class="form-control" id="numeroIdentificationFiscale"
                                        name="numeroIdentificationFiscale" value="{{ old('numeroIdentificationFiscale') }}">
                                </div>

                                <!-- Numéro contribuable -->
                                <div class="col-md-4">
                                    <label for="numeroContribuable" class="form-label">N° Contribuable</label>
                                    <input type="text" class="form-control" id="numeroContribuable"
                                        name="numeroContribuable" value="{{ old('numeroContribuable') }}">
                                </div>

                                <!-- Logo -->
                                <div class="col-md-12">
                                    <label class="form-label">Logo de l'entreprise</label>
                                    <div class="d-flex align-items-start gap-4">
                                        <div class="preview-logo" onclick="document.getElementById('logo').click()">
                                            <img id="logoPreview" src="" style="display: none;">
                                            <div id="logoPlaceholder" class="text-center text-muted">
                                                <i class="bi bi-image fs-3"></i>
                                                <p class="mb-0 small">Cliquez pour uploader</p>
                                            </div>
                                        </div>
                                        <input type="file" id="logo" name="logo" accept="image/*" style="display: none;"
                                            onchange="previewLogo(event)">
                                        <div>
                                            <p class="mb-1 small text-muted">Formats acceptés: JPG, PNG, GIF, SVG</p>
                                            <p class="mb-0 small text-muted">Taille max: 2MB</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Couleurs -->
                                <div class="col-md-6">
                                    <label class="form-label">Couleur primaire</label>
                                    <div class="color-picker-wrapper">
                                        <input type="color" class="form-control form-control-color"
                                            id="couleur_primaire" name="couleur_primaire"
                                            value="{{ old('couleur_primaire', '#198754') }}">
                                        <input type="text" class="form-control"
                                            value="{{ old('couleur_primaire', '#198754') }}"
                                            onchange="document.getElementById('couleur_primaire').value = this.value">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Couleur secondaire</label>
                                    <div class="color-picker-wrapper">
                                        <input type="color" class="form-control form-control-color"
                                            id="couleur_secondaire" name="couleur_secondaire"
                                            value="{{ old('couleur_secondaire', '#20c997') }}">
                                        <input type="text" class="form-control"
                                            value="{{ old('couleur_secondaire', '#20c997') }}"
                                            onchange="document.getElementById('couleur_secondaire').value = this.value">
                                    </div>
                                </div>

                                <!-- Statut -->
                                <div class="col-md-12">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="est_active"
                                            name="est_active" value="1" {{ old('est_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="est_active">
                                            Entreprise active
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Onglet 2: Contact -->
                        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                            <div class="row g-4">
                                <!-- Email -->
                                <div class="col-md-6">
                                    <label for="email" class="form-label">
                                        Email <span class="required-indicator">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email') }}" required>
                                        @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Téléphone -->
                                <div class="col-md-6">
                                    <label for="telephone" class="form-label">
                                        Téléphone <span class="required-indicator">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                        <input type="tel" class="form-control @error('telephone') is-invalid @enderror"
                                            id="telephone" name="telephone" value="{{ old('telephone') }}" required>
                                        @error('telephone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Téléphone alternatif -->
                                <div class="col-md-6">
                                    <label for="telephone_alternatif" class="form-label">Téléphone alternatif</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-phone"></i></span>
                                        <input type="tel" class="form-control" id="telephone_alternatif"
                                            name="telephone_alternatif" value="{{ old('telephone_alternatif') }}">
                                    </div>
                                </div>

                                <!-- Adresse -->
                                <div class="col-12">
                                    <label for="adresse" class="form-label">Adresse</label>
                                    <textarea class="form-control" id="adresse" name="adresse" rows="2">{{ old('adresse') }}</textarea>
                                </div>

                                <!-- Ville -->
                                <div class="col-md-4">
                                    <label for="ville" class="form-label">Ville</label>
                                    <input type="text" class="form-control" id="ville" name="ville" value="{{ old('ville') }}">
                                </div>

                                <!-- Pays -->
                                <div class="col-md-4">
                                    <label for="pays" class="form-label">Pays</label>
                                    <select class="form-select" id="pays" name="pays">
                                        <option value="Bénin" {{ old('pays', 'Bénin') == 'Bénin' ? 'selected' : '' }}>Bénin</option>
                                        <option value="Togo" {{ old('pays') == 'Togo' ? 'selected' : '' }}>Togo</option>
                                        <option value="Nigeria" {{ old('pays') == 'Nigeria' ? 'selected' : '' }}>Nigeria</option>
                                        <option value="Ghana" {{ old('pays') == 'Ghana' ? 'selected' : '' }}>Ghana</option>
                                        <option value="Côte d'Ivoire" {{ old('pays') == 'Côte d\'Ivoire' ? 'selected' : '' }}>Côte d'Ivoire</option>
                                        <option value="France" {{ old('pays') == 'France' ? 'selected' : '' }}>France</option>
                                        <option value="Autre" {{ old('pays') == 'Autre' ? 'selected' : '' }}>Autre</option>
                                    </select>
                                </div>

                                <!-- Code postal -->
                                <div class="col-md-4">
                                    <label for="code_postal" class="form-label">Code postal</label>
                                    <input type="text" class="form-control" id="code_postal" name="code_postal"
                                        value="{{ old('code_postal') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Onglet 3: Représentant légal -->
                        <div class="tab-pane fade" id="representant" role="tabpanel" aria-labelledby="representant-tab">
                            <div class="row g-4">
                                <div class="col-12">
                                    <h6 class="text-muted mb-3"><i class="bi bi-person-vcard me-2"></i>Informations du représentant légal</h6>
                                </div>

                                <!-- Nom du représentant -->
                                <div class="col-md-6">
                                    <label for="nom_representant_legal" class="form-label">Nom complet</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                                        <input type="text" class="form-control" id="nom_representant_legal"
                                            name="nom_representant_legal" value="{{ old('nom_representant_legal') }}">
                                    </div>
                                </div>

                                <!-- Email représentant -->
                                <div class="col-md-6">
                                    <label for="email_representant_legal" class="form-label">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                        <input type="email" class="form-control" id="email_representant_legal"
                                            name="email_representant_legal" value="{{ old('email_representant_legal') }}">
                                    </div>
                                </div>

                                <!-- Téléphone représentant -->
                                <div class="col-md-6">
                                    <label for="telephone_representant_legal" class="form-label">Téléphone</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                        <input type="tel" class="form-control" id="telephone_representant_legal"
                                            name="telephone_representant_legal" value="{{ old('telephone_representant_legal') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Onglet 4: Abonnement -->
                        <div class="tab-pane fade" id="abonnement" role="tabpanel" aria-labelledby="abonnement-tab">
                            <div class="row g-4">
                                <!-- Formule d'abonnement -->
                                <div class="col-12">
                                    <label class="form-label">Formule d'abonnement <span class="required-indicator">*</span></label>
                                    <div class="row g-3 mt-2">
                                        <div class="col-md-3">
                                            <div class="package-card" onclick="selectPackage('essai')">
                                                <div class="package-icon bg-warning bg-opacity-10 text-warning">
                                                    <i class="bi bi-clock"></i>
                                                </div>
                                                <h6 class="mb-1">Essai</h6>
                                                <small class="text-muted">15 jours gratuits</small>
                                                <input type="radio" name="formule" value="essai"
                                                    id="formule_essai" {{ old('formule') == 'essai' ? 'checked' : '' }}
                                                    class="d-none">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="package-card" onclick="selectPackage('basic')">
                                                <div class="package-icon bg-primary bg-opacity-10 text-primary">
                                                    <i class="bi bi-star"></i>
                                                </div>
                                                <h6 class="mb-1">Basic</h6>
                                                <small class="text-muted">Gestion de base</small>
                                                <input type="radio" name="formule" value="basic"
                                                    id="formule_basic" {{ old('formule', 'basic') == 'basic' ? 'checked' : '' }}
                                                    class="d-none">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="package-card" onclick="selectPackage('standard')">
                                                <div class="package-icon bg-success bg-opacity-10 text-success">
                                                    <i class="bi bi-award"></i>
                                                </div>
                                                <h6 class="mb-1">Standard</h6>
                                                <small class="text-muted">Toutes fonctionnalités</small>
                                                <input type="radio" name="formule" value="standard"
                                                    id="formule_standard" {{ old('formule') == 'standard' ? 'checked' : '' }}
                                                    class="d-none">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="package-card" onclick="selectPackage('premium')">
                                                <div class="package-icon bg-purple bg-opacity-10 text-purple" style="color: #6f42c1;">
                                                    <i class="bi bi-gem"></i>
                                                </div>
                                                <h6 class="mb-1">Premium</h6>
                                                <small class="text-muted">Support dédié</small>
                                                <input type="radio" name="formule" value="premium"
                                                    id="formule_premium" {{ old('formule') == 'premium' ? 'checked' : '' }}
                                                    class="d-none">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Période d'essai -->
                                <div class="col-md-12 mt-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="est_en_essai"
                                            name="est_en_essai" value="1" {{ old('est_en_essai') ? 'checked' : '' }}
                                            onchange="toggleEssaiFields()">
                                        <label class="form-check-label" for="est_en_essai">
                                            Mettre en période d'essai
                                        </label>
                                    </div>
                                </div>

                                <!-- Nombre d'agents max -->
                                <div class="col-md-6">
                                    <label for="nombre_agents_max" class="form-label">
                                        Nombre d'agents max <span class="required-indicator">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-people"></i></span>
                                        <input type="number" class="form-control @error('nombre_agents_max') is-invalid @enderror"
                                            id="nombre_agents_max" name="nombre_agents_max"
                                            value="{{ old('nombre_agents_max', 10) }}" min="1" required>
                                        @error('nombre_agents_max')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Nombre de sites max -->
                                <div class="col-md-6">
                                    <label for="nombre_sites_max" class="form-label">
                                        Nombre de sites max <span class="required-indicator">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                        <input type="number" class="form-control @error('nombre_sites_max') is-invalid @enderror"
                                            id="nombre_sites_max" name="nombre_sites_max"
                                            value="{{ old('nombre_sites_max', 5) }}" min="1" required>
                                        @error('nombre_sites_max')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Date de début contrat -->
                                <div class="col-md-4">
                                    <label for="date_debut_contrat" class="form-label">Date de début</label>
                                    <input type="date" class="form-control" id="date_debut_contrat"
                                        name="date_debut_contrat" value="{{ old('date_debut_contrat') }}">
                                </div>

                                <!-- Date de fin contrat -->
                                <div class="col-md-4">
                                    <label for="date_fin_contrat" class="form-label">Date de fin</label>
                                    <input type="date" class="form-control" id="date_fin_contrat"
                                        name="date_fin_contrat" value="{{ old('date_fin_contrat') }}">
                                </div>

                                <!-- Montant mensuel -->
                                <div class="col-md-4">
                                    <label for="montant_mensuel" class="form-label">Montant mensuel (FCFA)</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-currency-exchange"></i></span>
                                        <input type="number" class="form-control" id="montant_mensuel"
                                            name="montant_mensuel" value="{{ old('montant_mensuel') }}" min="0" step="100">
                                    </div>
                                </div>

                                <!-- Cycle de facturation -->
                                <div class="col-md-6">
                                    <label for="cycle_facturation" class="form-label">Cycle de facturation</label>
                                    <select class="form-select" id="cycle_facturation" name="cycle_facturation">
                                        <option value="mensuel" {{ old('cycle_facturation', 'mensuel') == 'mensuel' ? 'selected' : '' }}>Mensuel</option>
                                        <option value="trimestriel" {{ old('cycle_facturation') == 'trimestriel' ? 'selected' : '' }}>Trimestriel</option>
                                        <option value="annuel" {{ old('cycle_facturation') == 'annuel' ? 'selected' : '' }}>Annuel</option>
                                    </select>
                                </div>

                                <!-- Notes -->
                                <div class="col-12">
                                    <label for="notes" class="form-label">Notes</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="3"
                                        placeholder="Notes complémentaires...">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Boutons de navigation -->
                    <div class="wizard-buttons">
                        <div>
                            <button type="button" class="btn btn-outline-secondary" onclick="history.back()">
                                <i class="bi bi-arrow-left me-1"></i> Annuler
                            </button>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-success btn-create">
                                <i class="bi bi-check-circle me-1"></i> Créer l'entreprise
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<!--end::App Content-->

@push('scripts')
<script>
    // Générer automatiquement le slug à partir du nom
    document.getElementById('nom_entreprise').addEventListener('input', function() {
        const slugInput = document.getElementById('slug');
        if (!slugInput.value || slugInput.dataset.auto === 'true') {
            slugInput.value = this.value.toLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/(^-|-$)/g, '');
            slugInput.dataset.auto = 'true';
        }
    });

    document.getElementById('slug').addEventListener('input', function() {
        this.dataset.auto = 'false';
    });

    // Prévisualisation du logo
    function previewLogo(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('logoPreview').src = e.target.result;
                document.getElementById('logoPreview').style.display = 'block';
                document.getElementById('logoPlaceholder').style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    }

    // Sélection de package
    function selectPackage(packageName) {
        // Retirer la sélection précédente
        document.querySelectorAll('.package-card').forEach(card => {
            card.classList.remove('selected');
        });

        // Ajouter la sélection
        const radio = document.getElementById('formule_' + packageName);
        if (radio) {
            radio.checked = true;
            radio.closest('.package-card').classList.add('selected');
        }
    }

    // Initialiser la sélection du package
    document.addEventListener('DOMContentLoaded', function() {
        const selectedPackage = document.querySelector('input[name="formule"]:checked');
        if (selectedPackage) {
            selectedPackage.closest('.package-card').classList.add('selected');
        }
    });

    // Synchroniser les couleurs
    document.getElementById('couleur_primaire').addEventListener('input', function() {
        this.nextElementSibling.value = this.value;
    });

    document.getElementById('couleur_secondaire').addEventListener('input', function() {
        this.nextElementSibling.value = this.value;
    });
</script>
@endpush
@endsection