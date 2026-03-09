@extends('layouts.app')

@section('title', 'Nouveau Client - Entreprise')

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #198754 0%, #20c997 100%);
        padding: 1.75rem 2rem;
        border-radius: 20px;
        margin-bottom: 2rem;
        color: white;
        box-shadow: 0 4px 20px rgba(25, 135, 84, 0.3);
    }
    .page-header h3 {
        margin: 0;
        font-weight: 700;
        font-size: 1.6rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .page-header .breadcrumb { margin: 0; padding: 0; background: transparent; }
    .page-header .breadcrumb-item,
    .page-header .breadcrumb-item a { color: rgba(255,255,255,0.85); font-size: 0.875rem; }
    .page-header .breadcrumb-item.active { color: white; font-weight: 600; }
    .page-header .breadcrumb-item+.breadcrumb-item::before { color: rgba(255,255,255,0.6); }

    .premium-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.08);
        background: var(--bs-body-bg);
        overflow: hidden;
        margin-bottom: 1.5rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .premium-card:hover { transform: translateY(-2px); box-shadow: 0 8px 32px rgba(0,0,0,0.12); }
    .premium-card .card-header {
        background: linear-gradient(135deg, #198754 0%, #20c997 100%);
        padding: 1rem 1.5rem;
        border: none;
        color: white;
    }
    .premium-card .card-header h5 {
        margin: 0;
        font-weight: 600;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .premium-card .card-body { padding: 1.75rem; }

    .section-title {
        font-size: 0.75rem;
        font-weight: 700;
        color: #198754;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #198754;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-label {
        font-weight: 600;
        font-size: 0.875rem;
        color: var(--bs-body-color);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .form-control,
    .form-select {
        border-radius: 12px;
        border: 2px solid var(--bs-border-color);
        padding: 0.75rem 1rem;
        font-size: 0.9375rem;
        transition: all 0.25s ease;
        background-color: var(--bs-body-bg);
        color: var(--bs-body-color);
    }
    .form-control:focus,
    .form-select:focus {
        border-color: #198754;
        box-shadow: 0 0 0 4px rgba(25,135,84,0.15);
        outline: none;
    }
    .form-control.is-invalid,
    .form-select.is-invalid { border-color: #dc3545 !important; box-shadow: none; }
    .form-control.is-invalid:focus,
    .form-select.is-invalid:focus { box-shadow: 0 0 0 4px rgba(220,53,69,0.15); }
    .form-control::placeholder { color: var(--bs-secondary-color); opacity: 0.7; }

    .required-indicator { color: #dc3545; font-weight: 700; font-size: 0.75rem; }

    /* Erreurs inline sous les inputs */
    .field-error {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        margin-top: 0.4rem;
        font-size: 0.8125rem;
        color: #dc3545;
        font-weight: 500;
        animation: fadeInError 0.25s ease both;
    }
    .field-error i { font-size: 0.875rem; flex-shrink: 0; }
    @keyframes fadeInError {
        from { opacity: 0; transform: translateY(-4px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .help-text {
        font-size: 0.8125rem;
        color: var(--bs-secondary-color);
        margin-top: 0.35rem;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    /* Type de client cards */
    .type-client-card {
        cursor: pointer;
        border: 2px solid var(--bs-border-color);
        border-radius: 16px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
        background: var(--bs-body-bg);
    }
    .type-client-card:hover { border-color: #198754; background: rgba(25,135,84,0.05); }
    .type-client-card.selected { border-color: #198754; background: rgba(25,135,84,0.1); }
    .type-client-card i { font-size: 2.5rem; margin-bottom: 0.75rem; color: var(--bs-secondary-color); display: block; }
    .type-client-card.selected i { color: #198754; }
    .type-client-card .title { font-weight: 600; font-size: 1rem; color: var(--bs-body-color); }
    .type-client-card .subtitle { font-size: 0.8125rem; color: var(--bs-secondary-color); margin-top: 0.25rem; }

    /* Avatar */
    .avatar-lg {
        width: 80px; height: 80px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.75rem; font-weight: 700;
    }

    /* Bouton créer compte — visible et stylé */
    .compte-toggle-card {
        border: 2px solid var(--bs-border-color);
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
        transition: all 0.3s ease;
        background: var(--bs-body-bg);
        cursor: pointer;
    }
    .compte-toggle-card.active {
        border-color: #198754;
        background: rgba(25,135,84,0.06);
    }
    .compte-toggle-card .toggle-icon {
        width: 48px; height: 48px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.35rem;
        background: rgba(25,135,84,0.1);
        color: #198754;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }
    .compte-toggle-card.active .toggle-icon {
        background: #198754;
        color: #fff;
    }
    .compte-toggle-card .toggle-title {
        font-weight: 700;
        font-size: 0.9375rem;
        color: var(--bs-body-color);
    }
    .compte-toggle-card .toggle-sub {
        font-size: 0.8125rem;
        color: var(--bs-secondary-color);
        margin-top: 0.2rem;
    }
    /* Switch stylé */
    .form-check-input { width: 2.8em; height: 1.5em; }
    .form-check-input:checked { background-color: #198754; border-color: #198754; }
    .form-check-input:focus { border-color: #198754; box-shadow: 0 0 0 4px rgba(25,135,84,0.15); }
    .form-check-label { font-weight: 500; color: var(--bs-body-color); }

    /* Champs mot de passe — zone visuelle distincte */
    .password-zone {
        background: rgba(25,135,84,0.04);
        border: 1.5px dashed rgba(25,135,84,0.35);
        border-radius: 16px;
        padding: 1.5rem;
        margin-top: 1rem;
        animation: slideDown 0.3s ease both;
    }
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .password-zone .zone-title {
        font-size: 0.75rem;
        font-weight: 700;
        color: #198754;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }
    /* Indicateur force mot de passe */
    .password-strength { margin-top: 0.5rem; }
    .strength-bar {
        height: 4px; border-radius: 4px;
        background: var(--bs-border-color);
        overflow: hidden;
        margin-bottom: 0.25rem;
    }
    .strength-fill { height: 100%; border-radius: 4px; transition: width 0.3s ease, background 0.3s ease; width: 0; }
    .strength-label { font-size: 0.75rem; font-weight: 600; }

    /* Eye toggle password */
    .input-pwd-wrapper { position: relative; }
    .input-pwd-wrapper .form-control { padding-right: 3rem; }
    .btn-eye {
        position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%);
        background: none; border: none; color: var(--bs-secondary-color);
        cursor: pointer; padding: 0.25rem;
        transition: color 0.2s;
        z-index: 5;
    }
    .btn-eye:hover { color: #198754; }

    /* Boutons actions */
    .btn-save {
        background: linear-gradient(135deg, #198754 0%, #20c997 100%);
        border: none;
        padding: 0.875rem 2rem;
        font-weight: 600; font-size: 1rem;
        border-radius: 12px; color: #fff;
        transition: all 0.3s ease;
        display: flex; align-items: center; justify-content: center; gap: 0.5rem;
    }
    .btn-save:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(25,135,84,0.35); color: #fff; }
    .btn-cancel {
        background: var(--bs-body-bg);
        border: 2px solid var(--bs-border-color);
        color: var(--bs-body-color);
        padding: 0.875rem 1.75rem;
        font-weight: 600; font-size: 1rem;
        border-radius: 12px;
        transition: all 0.3s ease;
        display: flex; align-items: center; justify-content: center; gap: 0.5rem;
    }
    .btn-cancel:hover { background: var(--bs-tertiary-bg); border-color: var(--bs-body-color); color: var(--bs-body-color); transform: translateY(-2px); }

    .info-box {
        background: linear-gradient(135deg, rgba(25,135,84,0.08) 0%, rgba(32,201,151,0.08) 100%);
        border: 1px solid rgba(25,135,84,0.2);
        border-radius: 16px;
        padding: 1.25rem;
    }
    .info-box .info-title { font-weight: 700; color: #198754; font-size: 0.9375rem; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem; }
    .info-box ul { margin: 0; padding-left: 1.25rem; color: var(--bs-secondary-color); font-size: 0.875rem; }
    .info-box li { margin-bottom: 0.5rem; }
    .info-box li:last-child { margin-bottom: 0; }

    /* Alerte globale erreurs */
    .validation-alert {
        background: rgba(220,53,69,0.08);
        border: 1px solid rgba(220,53,69,0.3);
        border-radius: 16px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
    }
    .validation-alert .alert-title { font-weight: 700; color: #dc3545; font-size: 0.9375rem; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem; }
    .validation-alert ul { margin: 0; padding-left: 1.25rem; color: #dc3545; font-size: 0.875rem; }

    /* Session error */
    .session-error-alert {
        background: rgba(220,53,69,0.08);
        border: 1px solid rgba(220,53,69,0.3);
        border-radius: 16px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        display: flex; align-items: center; gap: 0.75rem;
        color: #dc3545; font-weight: 600;
    }

    /* Tabs */
    .nav-tabs-custom { border-bottom: 2px solid var(--bs-border-color); margin-bottom: 1.5rem; }
    .nav-tabs-custom .nav-link {
        border: none; color: var(--bs-secondary-color);
        padding: 0.75rem 1.25rem; font-weight: 600;
        border-radius: 10px 10px 0 0; transition: all 0.2s;
        display: flex; align-items: center; gap: 0.5rem;
    }
    .nav-tabs-custom .nav-link:hover { color: #198754; background: rgba(25,135,84,0.05); }
    .nav-tabs-custom .nav-link.active {
        color: #198754; background: var(--bs-body-bg);
        border: 2px solid var(--bs-border-color);
        border-bottom: 2px solid var(--bs-body-bg);
        margin-bottom: -2px;
    }
    .nav-tabs-custom .nav-link.has-error { color: #dc3545; }
    .nav-tabs-custom .nav-link.has-error::after {
        content: '●'; font-size: 0.5rem; vertical-align: super;
        margin-left: 0.25rem; color: #dc3545;
    }

    /* Type badges */
    .type-badge { padding: 0.3rem 0.7rem; border-radius: 20px; font-size: 0.7rem; font-weight: 700; display: inline-block; text-transform: uppercase; letter-spacing: 0.5px; }
    .type-particulier { background: rgba(111,66,193,0.12); color: #6f42c1; }
    .type-entreprise  { background: rgba(13,110,253,0.12);  color: #0d6efd; }
    .type-institution { background: rgba(253,126,20,0.12);  color: #fd7e14; }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fadeInUp 0.4s ease-out both; }
    .animate-fade-in:nth-child(1) { animation-delay: 0.05s; }
    .animate-fade-in:nth-child(2) { animation-delay: 0.1s;  }
    .animate-fade-in:nth-child(3) { animation-delay: 0.15s; }
    .animate-fade-in:nth-child(4) { animation-delay: 0.2s;  }
    .animate-fade-in:nth-child(5) { animation-delay: 0.25s; }

    @media (max-width: 768px) {
        .page-header { padding: 1.25rem 1.5rem; }
        .page-header h3 { font-size: 1.3rem; }
        .premium-card .card-body { padding: 1.25rem; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">

    {{-- HEADER --}}
    <div class="page-header animate-fade-in">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h3>
                    <i class="bi bi-person-plus-fill"></i>
                    Nouveau Client
                </h3>
            </div>
            <div class="col-md-6">
                <ol class="breadcrumb float-md-end">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.entreprise.index') }}">
                            <i class="bi bi-house-door"></i> Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.entreprise.clients.index') }}">Clients</a>
                    </li>
                    <li class="breadcrumb-item active">Nouveau</li>
                </ol>
            </div>
        </div>
    </div>

    {{-- ERREUR SESSION (ex: erreur DB) --}}
    @if(session('error'))
    <div class="session-error-alert animate-fade-in">
        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    {{-- ERREURS DE VALIDATION GLOBALES --}}
    @if($errors->any())
    <div class="validation-alert animate-fade-in">
        <div class="alert-title">
            <i class="bi bi-exclamation-triangle-fill"></i>
            Veuillez corriger les erreurs suivantes :
        </div>
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- FORMULAIRE --}}
    <form action="{{ route('admin.entreprise.clients.store') }}" method="POST" id="clientForm" class="animate-fade-in">
        @csrf

        {{-- Avatar + Statut --}}
        <div class="card premium-card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="avatar-lg text-white"
                            style="background: linear-gradient(135deg, #6f42c1 0%, #a855f7 100%);"
                            id="avatarPreview">
                            ?
                        </div>
                    </div>
                    <div class="col">
                        <h4 class="mb-1" id="clientNamePreview">Nouveau client</h4>
                        <span class="type-badge type-particulier" id="typeBadge">Particulier</span>
                    </div>
                    <div class="col-auto">
                        <div class="form-check form-switch">
                            <input type="hidden" name="est_actif" value="0">
                            <input class="form-check-input" type="checkbox"
                                name="est_actif" id="est_actif" value="1"
                                {{ old('est_actif', '1') == '1' ? 'checked' : '' }}
                                role="switch">
                            <label class="form-check-label" for="est_actif">Client actif</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Info Box --}}
        <div class="info-box mb-4">
            <div class="info-title">
                <i class="bi bi-info-circle-fill"></i>
                Informations importantes
            </div>
            <ul>
                <li>Les champs marqués d'un astérisque (<span style="color:#dc3545;font-weight:700;">*</span>) sont obligatoires.</li>
                <li>Pour les entreprises et institutions, veuillez fournir le NIF et le RCCM.</li>
                <li>Activez "Créer un compte" pour permettre au client de se connecter à son espace personnel.</li>
            </ul>
        </div>

        {{-- Type de client --}}
        <div class="card premium-card mb-4">
            <div class="card-header">
                <h5><i class="bi bi-tag-fill"></i> Type de client</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="type-client-card" onclick="selectType('particulier')" id="type-particulier-card">
                            <i class="bi bi-person"></i>
                            <div class="title">Particulier</div>
                            <div class="subtitle">Personne physique</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="type-client-card" onclick="selectType('entreprise')" id="type-entreprise-card">
                            <i class="bi bi-building"></i>
                            <div class="title">Entreprise</div>
                            <div class="subtitle">Personne morale</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="type-client-card" onclick="selectType('institution')" id="type-institution-card">
                            <i class="bi bi-bank"></i>
                            <div class="title">Institution</div>
                            <div class="subtitle">Organisation publique</div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="type_client" id="type_client" value="{{ old('type_client', 'particulier') }}">
            </div>
        </div>

        {{-- Tabs --}}
        <ul class="nav nav-tabs-custom" id="clientTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active {{ $errors->hasAny(['nom','prenoms','date_naissance','raison_sociale','nif','rc','email','telephone','telephone_secondaire','adresse','ville','pays']) ? 'has-error' : '' }}"
                    id="coordonnees-tab" data-bs-toggle="tab" data-bs-target="#coordonnees" type="button">
                    <i class="bi bi-person"></i> Coordonnées
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $errors->hasAny(['representant_nom','representant_prenom','representant_fonction']) ? 'has-error' : '' }}"
                    id="entreprise-tab" data-bs-toggle="tab" data-bs-target="#entreprise-info" type="button">
                    <i class="bi bi-building"></i> Infos entreprise
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $errors->hasAny(['contact_principal_nom','contact_principal_fonction','contact_email']) ? 'has-error' : '' }}"
                    id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button">
                    <i class="bi bi-telephone"></i> Contact principal
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $errors->hasAny(['password']) ? 'has-error' : '' }}"
                    id="acces-tab" data-bs-toggle="tab" data-bs-target="#acces" type="button">
                    <i class="bi bi-shield-lock"></i> Accès système
                </button>
            </li>
        </ul>

        <div class="tab-content" id="clientTabsContent">

            {{-- ═══════════════════════════════════════════════
                 Tab 1 : Coordonnées
            ═══════════════════════════════════════════════ --}}
            <div class="tab-pane fade show active" id="coordonnees" role="tabpanel">
                <div class="card premium-card mb-4">
                    <div class="card-body">

                        <div class="section-title">
                            <i class="bi bi-person"></i>
                            Informations du client
                        </div>

                        {{-- Particulier --}}
                        <div id="particulier-fields">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="nom">
                                        <i class="bi bi-person"></i>
                                        Nom <span class="required-indicator">*</span>
                                    </label>
                                    <input type="text" name="nom" id="nom"
                                        class="form-control @error('nom') is-invalid @enderror"
                                        value="{{ old('nom') }}"
                                        placeholder="Ex : DOSSOU">
                                    @error('nom')
                                        <div class="field-error">
                                            <i class="bi bi-exclamation-circle-fill"></i>
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="prenoms">
                                        <i class="bi bi-person"></i>
                                        Prénoms
                                    </label>
                                    <input type="text" name="prenoms" id="prenoms"
                                        class="form-control @error('prenoms') is-invalid @enderror"
                                        value="{{ old('prenoms') }}"
                                        placeholder="Ex : Jean Paul">
                                    @error('prenoms')
                                        <div class="field-error">
                                            <i class="bi bi-exclamation-circle-fill"></i>
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="date_naissance">
                                        <i class="bi bi-calendar"></i>
                                        Date de naissance
                                    </label>
                                    <input type="date" name="date_naissance" id="date_naissance"
                                        class="form-control @error('date_naissance') is-invalid @enderror"
                                        value="{{ old('date_naissance') }}">
                                    @error('date_naissance')
                                        <div class="field-error">
                                            <i class="bi bi-exclamation-circle-fill"></i>
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Entreprise / Institution --}}
                        <div id="entreprise-fields" style="display: none;">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="raison_sociale">
                                        <i class="bi bi-building"></i>
                                        Raison sociale <span class="required-indicator">*</span>
                                    </label>
                                    <input type="text" name="raison_sociale" id="raison_sociale"
                                        class="form-control @error('raison_sociale') is-invalid @enderror"
                                        value="{{ old('raison_sociale') }}"
                                        placeholder="Ex : Société Béninoise SA">
                                    @error('raison_sociale')
                                        <div class="field-error">
                                            <i class="bi bi-exclamation-circle-fill"></i>
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label" for="nif">
                                        <i class="bi bi-upc-scan"></i>
                                        NIF
                                    </label>
                                    <input type="text" name="nif" id="nif"
                                        class="form-control @error('nif') is-invalid @enderror"
                                        value="{{ old('nif') }}"
                                        placeholder="Ex : NIF-2020-XXXXX">
                                    @error('nif')
                                        <div class="field-error">
                                            <i class="bi bi-exclamation-circle-fill"></i>
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label" for="rc">
                                        <i class="bi bi-file-earmark-text"></i>
                                        RCCM
                                    </label>
                                    <input type="text" name="rc" id="rc"
                                        class="form-control @error('rc') is-invalid @enderror"
                                        value="{{ old('rc') }}"
                                        placeholder="Ex : RC-COT/2020/XXXX">
                                    @error('rc')
                                        <div class="field-error">
                                            <i class="bi bi-exclamation-circle-fill"></i>
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="section-title mt-4">
                            <i class="bi bi-envelope"></i>
                            Coordonnées
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label" for="email">
                                    <i class="bi bi-envelope"></i>
                                    Email
                                </label>
                                <input type="email" name="email" id="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}"
                                    placeholder="email@exemple.com">
                                @error('email')
                                    <div class="field-error">
                                        <i class="bi bi-exclamation-circle-fill"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label" for="telephone">
                                    <i class="bi bi-telephone"></i>
                                    Téléphone principal <span class="required-indicator">*</span>
                                </label>
                                <input type="text" name="telephone" id="telephone"
                                    class="form-control @error('telephone') is-invalid @enderror"
                                    value="{{ old('telephone') }}"
                                    placeholder="+229 XX XX XX XX">
                                @error('telephone')
                                    <div class="field-error">
                                        <i class="bi bi-exclamation-circle-fill"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label" for="telephone_secondaire">
                                    <i class="bi bi-telephone"></i>
                                    Téléphone secondaire
                                </label>
                                <input type="text" name="telephone_secondaire" id="telephone_secondaire"
                                    class="form-control @error('telephone_secondaire') is-invalid @enderror"
                                    value="{{ old('telephone_secondaire') }}"
                                    placeholder="+229 XX XX XX XX">
                                @error('telephone_secondaire')
                                    <div class="field-error">
                                        <i class="bi bi-exclamation-circle-fill"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label class="form-label" for="adresse">
                                    <i class="bi bi-geo-alt"></i>
                                    Adresse <span class="required-indicator">*</span>
                                </label>
                                <textarea name="adresse" id="adresse" rows="2"
                                    class="form-control @error('adresse') is-invalid @enderror"
                                    placeholder="N° rue, quartier, commune…">{{ old('adresse') }}</textarea>
                                @error('adresse')
                                    <div class="field-error">
                                        <i class="bi bi-exclamation-circle-fill"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="ville">
                                    <i class="bi bi-building"></i>
                                    Ville
                                </label>
                                <input type="text" name="ville" id="ville"
                                    class="form-control @error('ville') is-invalid @enderror"
                                    value="{{ old('ville') }}"
                                    placeholder="Ex : Cotonou">
                                @error('ville')
                                    <div class="field-error">
                                        <i class="bi bi-exclamation-circle-fill"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="pays">
                                    <i class="bi bi-globe"></i>
                                    Pays
                                </label>
                                <input type="text" name="pays" id="pays"
                                    class="form-control @error('pays') is-invalid @enderror"
                                    value="{{ old('pays', 'Bénin') }}"
                                    placeholder="Ex : Bénin">
                                @error('pays')
                                    <div class="field-error">
                                        <i class="bi bi-exclamation-circle-fill"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ═══════════════════════════════════════════════
                 Tab 2 : Infos entreprise
            ═══════════════════════════════════════════════ --}}
            <div class="tab-pane fade" id="entreprise-info" role="tabpanel">
                <div class="card premium-card mb-4">
                    <div class="card-body">
                        <div class="section-title">
                            <i class="bi bi-person-badge"></i>
                            Représentant légal
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label" for="representant_nom">
                                    <i class="bi bi-person"></i>
                                    Nom du représentant
                                </label>
                                <input type="text" name="representant_nom" id="representant_nom"
                                    class="form-control @error('representant_nom') is-invalid @enderror"
                                    value="{{ old('representant_nom') }}"
                                    placeholder="Nom de famille">
                                @error('representant_nom')
                                    <div class="field-error">
                                        <i class="bi bi-exclamation-circle-fill"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="representant_prenom">
                                    <i class="bi bi-person"></i>
                                    Prénoms du représentant
                                </label>
                                <input type="text" name="representant_prenom" id="representant_prenom"
                                    class="form-control @error('representant_prenom') is-invalid @enderror"
                                    value="{{ old('representant_prenom') }}"
                                    placeholder="Prénoms">
                                @error('representant_prenom')
                                    <div class="field-error">
                                        <i class="bi bi-exclamation-circle-fill"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="representant_fonction">
                                    <i class="bi bi-briefcase"></i>
                                    Fonction
                                </label>
                                <input type="text" name="representant_fonction" id="representant_fonction"
                                    class="form-control @error('representant_fonction') is-invalid @enderror"
                                    value="{{ old('representant_fonction') }}"
                                    placeholder="Ex : Directeur Général">
                                @error('representant_fonction')
                                    <div class="field-error">
                                        <i class="bi bi-exclamation-circle-fill"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ═══════════════════════════════════════════════
                 Tab 3 : Contact principal
            ═══════════════════════════════════════════════ --}}
            <div class="tab-pane fade" id="contact" role="tabpanel">
                <div class="card premium-card mb-4">
                    <div class="card-body">
                        <div class="section-title">
                            <i class="bi bi-person-lines-fill"></i>
                            Personne à contacter
                        </div>

                        <div class="info-box mb-4">
                            <div class="info-title">
                                <i class="bi bi-info-circle"></i>
                                Note
                            </div>
                            <ul>
                                <li>Cette personne sera le contact principal pour les communications relatives aux contrats et services.</li>
                            </ul>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label" for="contact_principal_nom">
                                    <i class="bi bi-person"></i>
                                    Nom complet
                                </label>
                                <input type="text" name="contact_principal_nom" id="contact_principal_nom"
                                    class="form-control @error('contact_principal_nom') is-invalid @enderror"
                                    value="{{ old('contact_principal_nom') }}"
                                    placeholder="Nom complet">
                                @error('contact_principal_nom')
                                    <div class="field-error">
                                        <i class="bi bi-exclamation-circle-fill"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="contact_principal_fonction">
                                    <i class="bi bi-briefcase"></i>
                                    Fonction
                                </label>
                                <input type="text" name="contact_principal_fonction" id="contact_principal_fonction"
                                    class="form-control @error('contact_principal_fonction') is-invalid @enderror"
                                    value="{{ old('contact_principal_fonction') }}"
                                    placeholder="Ex : Directeur des Opérations">
                                @error('contact_principal_fonction')
                                    <div class="field-error">
                                        <i class="bi bi-exclamation-circle-fill"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="contact_email">
                                    <i class="bi bi-envelope"></i>
                                    Email
                                </label>
                                <input type="email" name="contact_email" id="contact_email"
                                    class="form-control @error('contact_email') is-invalid @enderror"
                                    value="{{ old('contact_email') }}"
                                    placeholder="contact@exemple.com">
                                @error('contact_email')
                                    <div class="field-error">
                                        <i class="bi bi-exclamation-circle-fill"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ═══════════════════════════════════════════════
                 Tab 4 : Accès système
            ═══════════════════════════════════════════════ --}}
            <div class="tab-pane fade" id="acces" role="tabpanel">
                <div class="card premium-card mb-4">
                    <div class="card-body">

                        <div class="section-title">
                            <i class="bi bi-shield-lock"></i>
                            Paramètres de connexion
                        </div>

                        {{-- ✅ BOUTON CRÉER UN COMPTE — visible, cliquable, stylé --}}
                        <div id="compteToggleCard" class="compte-toggle-card {{ old('creer_compte') == '1' ? 'active' : '' }}"
                            onclick="toggleCompteFields()">
                            <div class="d-flex align-items-center gap-3">
                                <div class="toggle-icon" id="compteToggleIcon">
                                    <i class="bi bi-person-plus-fill"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="toggle-title">Créer un compte de connexion</div>
                                    <div class="toggle-sub">Permet au client d'accéder à son espace personnel sécurisé</div>
                                </div>
                                <div>
                                    {{-- Hidden + checkbox réelle (pas de hidden value="0" ici !) --}}
                                    <input type="hidden" name="creer_compte" value="0" id="creer_compte_hidden">
                                    <input class="form-check-input" type="checkbox"
                                        name="creer_compte" id="creer_compte" value="1"
                                        {{ old('creer_compte') == '1' ? 'checked' : '' }}
                                        role="switch"
                                        onclick="event.stopPropagation(); syncToggleCard();"
                                        style="width:2.8em; height:1.5em; cursor:pointer;">
                                </div>
                            </div>
                        </div>

                        {{-- Zone mot de passe — visible seulement si compte activé --}}
                        <div id="compte-fields" style="display: {{ old('creer_compte') == '1' ? 'block' : 'none' }};">
                            <div class="password-zone">
                                <div class="zone-title">
                                    <i class="bi bi-key-fill"></i>
                                    Définir le mot de passe
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label" for="password">
                                            <i class="bi bi-key"></i>
                                            Mot de passe <span class="required-indicator">*</span>
                                        </label>
                                        <div class="input-pwd-wrapper">
                                            <input type="password" name="password" id="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                placeholder="Minimum 8 caractères"
                                                oninput="checkPasswordStrength(this.value)">
                                            <button type="button" class="btn-eye" onclick="toggleEye('password', 'eyeIcon1')" tabindex="-1">
                                                <i class="bi bi-eye" id="eyeIcon1"></i>
                                            </button>
                                        </div>
                                        @error('password')
                                            <div class="field-error">
                                                <i class="bi bi-exclamation-circle-fill"></i>
                                                <span>{{ $message }}</span>
                                            </div>
                                        @enderror
                                        <div class="password-strength mt-2" id="strengthContainer" style="display:none;">
                                            <div class="strength-bar">
                                                <div class="strength-fill" id="strengthFill"></div>
                                            </div>
                                            <span class="strength-label" id="strengthLabel"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="password_confirmation">
                                            <i class="bi bi-key-fill"></i>
                                            Confirmer le mot de passe <span class="required-indicator">*</span>
                                        </label>
                                        <div class="input-pwd-wrapper">
                                            <input type="password" name="password_confirmation" id="password_confirmation"
                                                class="form-control"
                                                placeholder="Confirmer le mot de passe">
                                            <button type="button" class="btn-eye" onclick="toggleEye('password_confirmation', 'eyeIcon2')" tabindex="-1">
                                                <i class="bi bi-eye" id="eyeIcon2"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>{{-- /tab-content --}}

        {{-- Actions --}}
        <div class="card premium-card animate-fade-in">
            <div class="card-body d-flex justify-content-between align-items-center">
                <a href="{{ route('admin.entreprise.clients.index') }}" class="btn btn-cancel">
                    <i class="bi bi-arrow-left-circle"></i>
                    Retour à la liste
                </a>
                <button type="submit" class="btn btn-save">
                    <i class="bi bi-check-circle-fill"></i>
                    Enregistrer le client
                </button>
            </div>
        </div>

    </form>

</div>
@endsection

@push('scripts')
<script>
    // ── Sélection du type de client ──────────────────────────────────────────
    function selectType(type) {
        document.querySelectorAll('.type-client-card').forEach(c => c.classList.remove('selected'));
        var card = document.getElementById('type-' + type + '-card');
        if (card) card.classList.add('selected');
        document.getElementById('type_client').value = type;

        var pf = document.getElementById('particulier-fields');
        var ef = document.getElementById('entreprise-fields');
        var et = document.getElementById('entreprise-tab');

        if (type === 'particulier') {
            pf.style.display = 'block';
            ef.style.display = 'none';
            if (et) et.closest('.nav-item').style.display = 'none';
            document.getElementById('avatarPreview').style.background = 'linear-gradient(135deg, #6f42c1 0%, #a855f7 100%)';
            document.getElementById('avatarPreview').textContent     = '?';
            document.getElementById('clientNamePreview').textContent = 'Nouveau particulier';
            document.getElementById('typeBadge').className           = 'type-badge type-particulier';
            document.getElementById('typeBadge').textContent         = 'Particulier';
        } else {
            pf.style.display = 'none';
            ef.style.display = 'block';
            if (et) et.closest('.nav-item').style.display = 'block';
            if (type === 'entreprise') {
                document.getElementById('avatarPreview').style.background = 'linear-gradient(135deg, #0d6efd 0%, #3d8bfd 100%)';
                document.getElementById('clientNamePreview').textContent   = 'Nouvelle entreprise';
                document.getElementById('typeBadge').className             = 'type-badge type-entreprise';
                document.getElementById('typeBadge').textContent           = 'Entreprise';
                document.getElementById('avatarPreview').textContent       = 'EN';
            } else {
                document.getElementById('avatarPreview').style.background = 'linear-gradient(135deg, #fd7e14 0%, #feb47b 100%)';
                document.getElementById('clientNamePreview').textContent   = 'Nouvelle institution';
                document.getElementById('typeBadge').className             = 'type-badge type-institution';
                document.getElementById('typeBadge').textContent           = 'Institution';
                document.getElementById('avatarPreview').textContent       = 'IN';
            }
        }
    }

    // ── Toggle carte "Créer un compte" ────────────────────────────────────────
    function toggleCompteFields() {
        var checkbox    = document.getElementById('creer_compte');
        checkbox.checked = !checkbox.checked;
        syncToggleCard();
    }

    function syncToggleCard() {
        var checkbox    = document.getElementById('creer_compte');
        var card        = document.getElementById('compteToggleCard');
        var fields      = document.getElementById('compte-fields');
        var pwd         = document.getElementById('password');
        var pwdConf     = document.getElementById('password_confirmation');

        if (checkbox.checked) {
            card.classList.add('active');
            fields.style.display = 'block';
            pwd.setAttribute('required', 'required');
            pwdConf.setAttribute('required', 'required');
        } else {
            card.classList.remove('active');
            fields.style.display = 'none';
            pwd.removeAttribute('required');
            pwdConf.removeAttribute('required');
            pwd.value    = '';
            pwdConf.value = '';
        }
    }

    // ── Afficher/masquer mot de passe ─────────────────────────────────────────
    function toggleEye(inputId, iconId) {
        var input = document.getElementById(inputId);
        var icon  = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'bi bi-eye';
        }
    }

    // ── Indicateur de force du mot de passe ───────────────────────────────────
    function checkPasswordStrength(val) {
        var container = document.getElementById('strengthContainer');
        var fill      = document.getElementById('strengthFill');
        var label     = document.getElementById('strengthLabel');

        if (!val) { container.style.display = 'none'; return; }
        container.style.display = 'block';

        var score = 0;
        if (val.length >= 8)  score++;
        if (/[A-Z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;

        var levels = [
            { width: '25%', color: '#dc3545', text: 'Très faible' },
            { width: '50%', color: '#fd7e14', text: 'Faible' },
            { width: '75%', color: '#ffc107', text: 'Moyen' },
            { width: '100%', color: '#198754', text: 'Fort' },
        ];
        var lvl = levels[score - 1] || levels[0];
        fill.style.width      = lvl.width;
        fill.style.background = lvl.color;
        label.style.color     = lvl.color;
        label.textContent     = lvl.text;
    }

    // ── Mise à jour de l'avatar en temps réel ────────────────────────────────
    function updateAvatarPreview() {
        var type = document.getElementById('type_client').value;
        if (type === 'particulier') {
            var nom     = (document.querySelector('input[name="nom"]').value     || '').trim();
            var prenoms = (document.querySelector('input[name="prenoms"]').value || '').trim();
            if (nom || prenoms) {
                document.getElementById('avatarPreview').textContent     = (prenoms ? prenoms.charAt(0).toUpperCase() : '') + (nom ? nom.charAt(0).toUpperCase() : '?');
                document.getElementById('clientNamePreview').textContent = (prenoms + ' ' + nom).trim();
            } else {
                document.getElementById('avatarPreview').textContent     = '?';
                document.getElementById('clientNamePreview').textContent = 'Nouveau particulier';
            }
        } else {
            var rs = (document.querySelector('input[name="raison_sociale"]').value || '').trim();
            if (rs) {
                document.getElementById('avatarPreview').textContent     = rs.substring(0, 2).toUpperCase();
                document.getElementById('clientNamePreview').textContent = rs;
            } else {
                document.getElementById('avatarPreview').textContent     = type === 'entreprise' ? 'EN' : 'IN';
                document.getElementById('clientNamePreview').textContent = type === 'entreprise' ? 'Nouvelle entreprise' : 'Nouvelle institution';
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Initialiser le type
        selectType("{{ old('type_client', 'particulier') }}");

        // Initialiser le toggle compte
        syncToggleCard();

        // Listeners avatar
        var nomInput           = document.querySelector('input[name="nom"]');
        var prenomsInput       = document.querySelector('input[name="prenoms"]');
        var raisonSocialeInput = document.querySelector('input[name="raison_sociale"]');
        if (nomInput)           nomInput.addEventListener('input', updateAvatarPreview);
        if (prenomsInput)       prenomsInput.addEventListener('input', updateAvatarPreview);
        if (raisonSocialeInput) raisonSocialeInput.addEventListener('input', updateAvatarPreview);

        // Ouvrir le bon onglet si erreurs de validation
        @if($errors->any())
        var tabsWithErrors = {
            'coordonnees':     {{ $errors->hasAny(['nom','prenoms','date_naissance','raison_sociale','nif','rc','email','telephone','telephone_secondaire','adresse','ville','pays']) ? 'true' : 'false' }},
            'entreprise-info': {{ $errors->hasAny(['representant_nom','representant_prenom','representant_fonction']) ? 'true' : 'false' }},
            'contact':         {{ $errors->hasAny(['contact_principal_nom','contact_principal_fonction','contact_email']) ? 'true' : 'false' }},
            'acces':           {{ $errors->hasAny(['password']) ? 'true' : 'false' }},
        };
        var firstErrorTab = Object.keys(tabsWithErrors).find(k => tabsWithErrors[k]);
        if (firstErrorTab) {
            var tabEl = document.querySelector('[data-bs-target="#' + firstErrorTab + '"]');
            if (tabEl) { var tab = new bootstrap.Tab(tabEl); tab.show(); }
        }
        @endif
    });
</script>
@endpush