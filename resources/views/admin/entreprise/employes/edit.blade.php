@extends('layouts.app')

@section('title', 'Modifier l\'employé - Entreprise')

@push('styles')
<style>
    .form-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
    }

    .nav-tabs-custom {
        border-bottom: 2px solid #e9ecef;
        margin-bottom: 20px;
    }

    .nav-tabs-custom .nav-link {
        border: none;
        color: #6c757d;
        padding: 12px 20px;
        font-weight: 500;
        border-radius: 8px 8px 0 0;
        transition: all 0.2s;
    }

    .nav-tabs-custom .nav-link:hover {
        color: #198754;
        background: rgba(25, 135, 84, 0.05);
    }

    .nav-tabs-custom .nav-link.active {
        color: #198754;
        background: white;
        border: 2px solid #e9ecef;
        border-bottom: 2px solid white;
        margin-bottom: -2px;
    }

    .form-label {
        font-weight: 500;
        color: #495057;
        margin-bottom: 5px;
    }

    .form-control,
    .form-select {
        border-radius: 8px;
        border: 1px solid #ced4da;
        padding: 10px 12px;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #198754;
        box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.15);
    }

    .section-title {
        font-size: 14px;
        font-weight: 600;
        color: #198754;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 15px;
        padding-bottom: 8px;
        border-bottom: 2px solid #198754;
    }

    .required-field::after {
        content: ' *';
        color: #dc3545;
    }

    .password-toggle {
        cursor: pointer;
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }

    .info-box {
        background: #e7f5ff;
        border-left: 4px solid #0d6efd;
        padding: 12px 15px;
        border-radius: 4px;
        margin-bottom: 20px;
    }

    .info-box i {
        color: #0d6efd;
        margin-right: 8px;
    }

    .avatar-lg {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0">
                    <i class="bi bi-person-pen me-2 text-success"></i>
                    Modifier l'Employé
                </h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.entreprise.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.entreprise.employes.index') }}">Employés</a></li>
                    <li class="breadcrumb-item active">Modifier</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">

        <form action="{{ route('admin.entreprise.employes.update', $employe->id) }}" method="POST" id="employeForm">
            @csrf
            @method('PUT')

            {{-- Header with Avatar --}}
            <div class="card form-card mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="avatar-lg bg-{{ $employe->categorie == 'direction' ? 'purple' : ($employe->categorie == 'supervision' ? 'primary' : ($employe->categorie == 'controle' ? 'warning' : 'success')) }} text-white">
                                {{ strtoupper(substr($employe->prenoms ?? 'N', 0, 1)) }}{{ strtoupper(substr($employe->nom ?? 'A', 0, 1)) }}
                            </div>
                        </div>
                        <div class="col">
                            <h4 class="mb-1">{{ $employe->prenoms }} {{ $employe->nom }}</h4>
                            <div class="text-muted">
                                <span class="badge bg-secondary">{{ $employe->matricule ?? 'N/A' }}</span>
                                <span class="mx-2">|</span>
                                <span>{{ $employe->poste ?? 'Non défini' }}</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="est_actif" id="est_actif" value="1" {{ $employe->est_actif ? 'checked' : '' }}>
                                <label class="form-check-label" for="est_actif">Employé actif</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabs --}}
            <ul class="nav nav-tabs-custom" id="employeTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="personnel-tab" data-bs-toggle="tab" data-bs-target="#personnel" type="button">
                        <i class="bi bi-person me-1"></i> Informations personnelles
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="professionnel-tab" data-bs-toggle="tab" data-bs-target="#professionnel" type="button">
                        <i class="bi bi-briefcase me-1"></i> Informations professionnelles
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="urgence-tab" data-bs-toggle="tab" data-bs-target="#urgence" type="button">
                        <i class="bi bi-telephone me-1"></i> Contact d'urgence
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="connexion-tab" data-bs-toggle="tab" data-bs-target="#connexion" type="button">
                        <i class="bi bi-shield-lock me-1"></i> Accès système
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="employeTabsContent">
                {{-- Tab 1: Informations personnelles --}}
                <div class="tab-pane fade show active" id="personnel" role="tabpanel">
                    <div class="card form-card mb-4">
                        <div class="card-body">
                            <div class="section-title">Informations personnelles</div>

                            <div class="row">
                                <div class="col-md-2 mb-3">
                                    <label class="form-label required-field">Civilité</label>
                                    <select name="civilite" class="form-select @error('civilite') is-invalid @enderror" required>
                                        <option value="">Sélectionner</option>
                                        <option value="M" {{ old('civilite', $employe->civilite) == 'M' ? 'selected' : '' }}>M.</option>
                                        <option value="Mme" {{ old('civilite', $employe->civilite) == 'Mme' ? 'selected' : '' }}>Mme</option>
                                        <option value="Mlle" {{ old('civilite', $employe->civilite) == 'Mlle' ? 'selected' : '' }}>Mlle</option>
                                    </select>
                                    @error('civilite')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-5 mb-3">
                                    <label class="form-label required-field">Nom</label>
                                    <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror"
                                        value="{{ old('nom', $employe->nom) }}" required>
                                    @error('nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-5 mb-3">
                                    <label class="form-label required-field">Prénoms</label>
                                    <input type="text" name="prenoms" class="form-control @error('prenoms') is-invalid @enderror"
                                        value="{{ old('prenoms', $employe->prenoms) }}" required>
                                    @error('prenoms')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label required-field">Email</label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email', $employe->email) }}" required>
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label required-field">Téléphone</label>
                                    <input type="text" name="telephone" class="form-control @error('telephone') is-invalid @enderror"
                                        value="{{ old('telephone', $employe->telephone) }}" required>
                                    @error('telephone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Téléphone Urgence</label>
                                    <input type="text" name="telephone_urgence" class="form-control"
                                        value="{{ old('telephone_urgence', $employe->telephone_urgence) }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Date de naissance</label>
                                    <input type="date" name="date_naissance" class="form-control"
                                        value="{{ old('date_naissance', $employe->date_naissance?->format('Y-m-d')) }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Lieu de naissance</label>
                                    <input type="text" name="lieu_naissance" class="form-control"
                                        value="{{ old('lieu_naissance', $employe->lieu_naissance) }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Sexe</label>
                                    <select name="sexe" class="form-select">
                                        <option value="">Sélectionner</option>
                                        <option value="M" {{ old('sexe', $employe->sexe) == 'M' ? 'selected' : '' }}>Masculin</option>
                                        <option value="F" {{ old('sexe', $employe->sexe) == 'F' ? 'selected' : '' }}>Féminin</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Statut matrimonial</label>
                                    <select name="statut_matrimonial" class="form-select">
                                        <option value="">Sélectionner</option>
                                        <option value="celibataire" {{ old('statut_matrimonial', $employe->statut_matrimonial) == 'celibataire' ? 'selected' : '' }}>Célibataire</option>
                                        <option value="marie" {{ old('statut_matrimonial', $employe->statut_matrimonial) == 'marie' ? 'selected' : '' }}>Marié(e)</option>
                                        <option value="divorce" {{ old('statut_matrimonial', $employe->statut_matrimonial) == 'divorce' ? 'selected' : '' }}>Divorcé(e)</option>
                                        <option value="veuf" {{ old('statut_matrimonial', $employe->statut_matrimonial) == 'veuf' ? 'selected' : '' }}>Veuf/Veuve</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Adresse</label>
                                    <textarea name="adresse" class="form-control" rows="2">{{ old('adresse', $employe->adresse) }}</textarea>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">N° CNI</label>
                                    <input type="text" name="cni" class="form-control" value="{{ old('cni', $employe->cni) }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">N° CNSS</label>
                                    <input type="text" name="numero_cnss" class="form-control" value="{{ old('numero_cnss', $employe->numero_cnss) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tab 2: Informations professionnelles --}}
                <div class="tab-pane fade" id="professionnel" role="tabpanel">
                    <div class="card form-card mb-4">
                        <div class="card-body">
                            <div class="section-title">Informations professionnelles</div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Matricule</label>
                                    <input type="text" name="matricule" class="form-control"
                                        value="{{ old('matricule', $employe->matricule) }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label required-field">Catégorie</label>
                                    <select name="categorie" class="form-select @error('categorie') is-invalid @enderror" required>
                                        <option value="">Sélectionner</option>
                                        <option value="direction" {{ old('categorie', $employe->categorie) == 'direction' ? 'selected' : '' }}>Direction</option>
                                        <option value="supervision" {{ old('categorie', $employe->categorie) == 'supervision' ? 'selected' : '' }}>Supervision</option>
                                        <option value="controle" {{ old('categorie', $employe->categorie) == 'controle' ? 'selected' : '' }}>Contrôle</option>
                                        <option value="agent" {{ old('categorie', $employe->categorie) == 'agent' ? 'selected' : '' }}>Agent</option>
                                    </select>
                                    @error('categorie')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label required-field">Poste</label>
                                    <select name="poste" class="form-select @error('poste') is-invalid @enderror" required>
                                        <option value="">Sélectionner</option>
                                        <optgroup label="Direction">
                                            <option value="directeur_general" {{ old('poste', $employe->poste) == 'directeur_general' ? 'selected' : '' }}>Directeur Général</option>
                                            <option value="directeur_adjoint" {{ old('poste', $employe->poste) == 'directeur_adjoint' ? 'selected' : '' }}>Directeur Adjoint</option>
                                        </optgroup>
                                        <optgroup label="Supervision">
                                            <option value="superviseur_general" {{ old('poste', $employe->poste) == 'superviseur_general' ? 'selected' : '' }}>Superviseur Général</option>
                                            <option value="superviseur_adjoint" {{ old('poste', $employe->poste) == 'superviseur_adjoint' ? 'selected' : '' }}>Superviseur Adjoint</option>
                                        </optgroup>
                                        <optgroup label="Contrôle">
                                            <option value="controleur_principal" {{ old('poste', $employe->poste) == 'controleur_principal' ? 'selected' : '' }}>Contrôleur Principal</option>
                                            <option value="controleur" {{ old('poste', $employe->poste) == 'controleur' ? 'selected' : '' }}>Contrôleur</option>
                                        </optgroup>
                                        <optgroup label="Agents">
                                            <option value="agent_terrain" {{ old('poste', $employe->poste) == 'agent_terrain' ? 'selected' : '' }}>Agent de Terrain</option>
                                            <option value="agent_mobile" {{ old('poste', $employe->poste) == 'agent_mobile' ? 'selected' : '' }}>Agent Mobile</option>
                                            <option value="agent_poste_fixe" {{ old('poste', $employe->poste) == 'agent_poste_fixe' ? 'selected' : '' }}>Agent Poste Fixe</option>
                                        </optgroup>
                                    </select>
                                    @error('poste')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label required-field">Type de contrat</label>
                                    <select name="type_contrat" class="form-select @error('type_contrat') is-invalid @enderror" required>
                                        <option value="">Sélectionner</option>
                                        <option value="cdi" {{ old('type_contrat', $employe->type_contrat) == 'cdi' ? 'selected' : '' }}>CDI</option>
                                        <option value="cdd" {{ old('type_contrat', $employe->type_contrat) == 'cdd' ? 'selected' : '' }}>CDD</option>
                                        <option value="stage" {{ old('type_contrat', $employe->type_contrat) == 'stage' ? 'selected' : '' }}>Stage</option>
                                        <option value="prestation" {{ old('type_contrat', $employe->type_contrat) == 'prestation' ? 'selected' : '' }}>Prestation</option>
                                    </select>
                                    @error('type_contrat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label required-field">Date d'embauche</label>
                                    <input type="date" name="date_embauche" class="form-control @error('date_embauche') is-invalid @enderror"
                                        value="{{ old('date_embauche', $employe->date_embauche?->format('Y-m-d')) }}" required>
                                    @error('date_embauche')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Date fin de contrat</label>
                                    <input type="date" name="date_fin_contrat" class="form-control"
                                        value="{{ old('date_fin_contrat', $employe->date_fin_contrat?->format('Y-m-d')) }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Département</label>
                                    <input type="text" name="departement" class="form-control"
                                        value="{{ old('departement', $employe->departement) }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Service</label>
                                    <input type="text" name="service" class="form-control"
                                        value="{{ old('service', $employe->service) }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Salaire de base (FCFA)</label>
                                    <input type="number" name="salaire_base" class="form-control"
                                        value="{{ old('salaire_base', $employe->salaire_base) }}" min="0" step="100">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Statut</label>
                                    <select name="statut" class="form-select">
                                        <option value="en_poste" {{ old('statut', $employe->statut) == 'en_poste' ? 'selected' : '' }}>En poste</option>
                                        <option value="conge" {{ old('statut', $employe->statut) == 'conge' ? 'selected' : '' }}>En congé</option>
                                        <option value="suspendu" {{ old('statut', $employe->statut) == 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                                        <option value="licencie" {{ old('statut', $employe->statut) == 'licencie' ? 'selected' : '' }}>Licencié</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Disponible</label>
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox" name="disponible" id="disponible" value="1" {{ $employe->disponible ? 'checked' : '' }}>
                                        <label class="form-check-label" for="disponible">Disponible pour les missions</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tab 3: Contact d'urgence --}}
                <div class="tab-pane fade" id="urgence" role="tabpanel">
                    <div class="card form-card mb-4">
                        <div class="card-body">
                            <div class="section-title">Personne à contacter en cas d'urgence</div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Nom complet</label>
                                    <input type="text" name="contact_urgence_nom" class="form-control"
                                        value="{{ old('contact_urgence_nom', $employe->contact_urgence_nom) }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Téléphone</label>
                                    <input type="text" name="contact_urgence_tel" class="form-control"
                                        value="{{ old('contact_urgence_tel', $employe->contact_urgence_tel) }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Lien de parenté</label>
                                    <select name="contact_urgence_lien" class="form-select">
                                        <option value="">Sélectionner</option>
                                        <option value="conjoint" {{ old('contact_urgence_lien', $employe->contact_urgence_lien) == 'conjoint' ? 'selected' : '' }}>Conjoint(e)</option>
                                        <option value="parent" {{ old('contact_urgence_lien', $employe->contact_urgence_lien) == 'parent' ? 'selected' : '' }}>Parent</option>
                                        <option value="frere" {{ old('contact_urgence_lien', $employe->contact_urgence_lien) == 'frere' ? 'selected' : '' }}>Frère/Sœur</option>
                                        <option value="ami" {{ old('contact_urgence_lien', $employe->contact_urgence_lien) == 'ami' ? 'selected' : '' }}>Ami</option>
                                        <option value="autre" {{ old('contact_urgence_lien', $employe->contact_urgence_lien) == 'autre' ? 'selected' : '' }}>Autre</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tab 4: Accès système --}}
                <div class="tab-pane fade" id="connexion" role="tabpanel">
                    <div class="card form-card mb-4">
                        <div class="card-body">
                            <div class="section-title">Modifier le mot de passe</div>

                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                Laissez les champs mot de passe vides si vous ne souhaitez pas modifier le mot de passe.
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nouveau mot de passe</label>
                                    <div class="input-group">
                                        <input type="password" name="password" class="form-control"
                                            id="password" placeholder="Nouveau mot de passe">
                                        <span class="input-group-text password-toggle" onclick="togglePassword('password')">
                                            <i class="bi bi-eye" id="eye-password"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Confirmer le mot de passe</label>
                                    <div class="input-group">
                                        <input type="password" name="password_confirmation" class="form-control"
                                            id="password_confirmation" placeholder="Confirmer le mot de passe">
                                        <span class="input-group-text password-toggle" onclick="togglePassword('password_confirmation')">
                                            <i class="bi bi-eye" id="eye-password_confirmation"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-12">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="generatePassword()">
                                        <i class="bi bi-magic me-1"></i> Générer un mot de passe
                                    </button>
                                </div>
                            </div>

                            @if($employe->last_login_at)
                            <hr class="my-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="text-muted small">
                                        <i class="bi bi-clock me-1"></i>
                                        Dernière connexion : {{ $employe->last_login_at->format('d/m/Y à H:i') }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-muted small">
                                        <i class="bi bi-geo-alt me-1"></i>
                                        IP : {{ $employe->last_login_ip ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="card form-card mb-4">
                <div class="card-body d-flex justify-content-between">
                    <a href="{{ route('admin.entreprise.employes.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Retour
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i> Enregistrer les modifications
                    </button>
                </div>
            </div>
        </form>

    </div>
</div>

@push('scripts')
<script>
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const eye = document.getElementById('eye-' + fieldId);

        if (field.type === 'password') {
            field.type = 'text';
            eye.classList.remove('bi-eye');
            eye.classList.add('bi-eye-slash');
        } else {
            field.type = 'password';
            eye.classList.remove('bi-eye-slash');
            eye.classList.add('bi-eye');
        }
    }

    function generatePassword() {
        const chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
        let password = '';
        for (let i = 0; i < 12; i++) {
            password += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.getElementById('password').value = password;
        document.getElementById('password_confirmation').value = password;
    }

    document.getElementById('employeForm').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const passwordConfirm = document.getElementById('password_confirmation').value;

        if (password && password !== passwordConfirm) {
            e.preventDefault();
            alert('Les mots de passe ne correspondent pas');
            return false;
        }

        return true;
    });
</script>
@endpush
@endsection