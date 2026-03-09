@extends('layouts.app')

@section('title', 'Modifier le Client - Entreprise')

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
    .page-header h3 { margin: 0; font-weight: 700; font-size: 1.6rem; display: flex; align-items: center; gap: 0.75rem; }
    .page-header .breadcrumb { margin: 0; padding: 0; background: transparent; }
    .page-header .breadcrumb-item,
    .page-header .breadcrumb-item a { color: rgba(255,255,255,0.85); font-size: 0.875rem; }
    .page-header .breadcrumb-item.active { color: white; font-weight: 600; }
    .page-header .breadcrumb-item+.breadcrumb-item::before { color: rgba(255,255,255,0.6); }

    .premium-card {
        border: none; border-radius: 20px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.08);
        background: var(--bs-body-bg); overflow: hidden;
        margin-bottom: 1.5rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .premium-card:hover { transform: translateY(-2px); box-shadow: 0 8px 32px rgba(0,0,0,0.12); }
    .premium-card .card-header {
        background: linear-gradient(135deg, #198754 0%, #20c997 100%);
        padding: 1rem 1.5rem; border: none; color: white;
    }
    .premium-card .card-header h5 { margin: 0; font-weight: 600; font-size: 1rem; display: flex; align-items: center; gap: 0.5rem; }
    .premium-card .card-body { padding: 1.75rem; }

    .section-title {
        font-size: 0.75rem; font-weight: 700; color: #198754;
        text-transform: uppercase; letter-spacing: 1.5px;
        margin-bottom: 1.5rem; padding-bottom: 0.75rem;
        border-bottom: 2px solid #198754;
        display: flex; align-items: center; gap: 0.5rem;
    }

    .form-label {
        font-weight: 600; font-size: 0.875rem;
        color: var(--bs-body-color); margin-bottom: 0.5rem;
        display: flex; align-items: center; gap: 0.35rem;
    }

    .form-control, .form-select {
        border-radius: 12px;
        border: 2px solid var(--bs-border-color);
        padding: 0.75rem 1rem; font-size: 0.9375rem;
        transition: all 0.25s ease;
        background-color: var(--bs-body-bg);
        color: var(--bs-body-color);
    }
    .form-control:focus, .form-select:focus {
        border-color: #198754;
        box-shadow: 0 0 0 4px rgba(25,135,84,0.15);
        outline: none;
    }
    .form-control.is-invalid, .form-select.is-invalid {
        border-color: #dc3545 !important; box-shadow: none;
    }
    .form-control.is-invalid:focus, .form-select.is-invalid:focus {
        box-shadow: 0 0 0 4px rgba(220,53,69,0.15);
    }
    .form-control::placeholder { color: var(--bs-secondary-color); opacity: 0.7; }

    .required-indicator { color: #dc3545; font-weight: 700; font-size: 0.75rem; }

    /* ── Erreurs inline sous chaque champ ── */
    .field-error {
        display: flex; align-items: center; gap: 0.4rem;
        margin-top: 0.4rem; font-size: 0.8125rem;
        color: #dc3545; font-weight: 500;
        animation: fadeInError 0.25s ease both;
    }
    .field-error i { font-size: 0.875rem; flex-shrink: 0; }
    @keyframes fadeInError {
        from { opacity: 0; transform: translateY(-4px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ── Alerte globale ── */
    .validation-alert {
        background: rgba(220,53,69,0.08);
        border: 1px solid rgba(220,53,69,0.3);
        border-radius: 16px; padding: 1.25rem; margin-bottom: 1.5rem;
    }
    .validation-alert .alert-title {
        font-weight: 700; color: #dc3545; font-size: 0.9375rem;
        margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;
    }
    .validation-alert ul { margin: 0; padding-left: 1.25rem; color: #dc3545; font-size: 0.875rem; }

    /* ── Session alerts ── */
    .session-error-alert {
        background: rgba(220,53,69,0.08); border: 1px solid rgba(220,53,69,0.3);
        border-radius: 16px; padding: 1rem 1.25rem; margin-bottom: 1.5rem;
        display: flex; align-items: center; gap: 0.75rem;
        color: #dc3545; font-weight: 600;
    }
    .session-success-alert {
        background: rgba(25,135,84,0.08); border: 1px solid rgba(25,135,84,0.3);
        border-radius: 16px; padding: 1rem 1.25rem; margin-bottom: 1.5rem;
        display: flex; align-items: center; gap: 0.75rem;
        color: #198754; font-weight: 600;
    }

    /* ── Switch ── */
    .form-check-input { width: 2.8em; height: 1.5em; }
    .form-check-input:checked { background-color: #198754; border-color: #198754; }
    .form-check-input:focus { border-color: #198754; box-shadow: 0 0 0 4px rgba(25,135,84,0.15); }
    .form-check-label { font-weight: 500; color: var(--bs-body-color); }

    /* ── Type de client cards ── */
    .type-client-card {
        cursor: pointer; border: 2px solid var(--bs-border-color);
        border-radius: 16px; padding: 1.5rem; text-align: center;
        transition: all 0.3s ease; background: var(--bs-body-bg);
    }
    .type-client-card:hover { border-color: #198754; background: rgba(25,135,84,0.05); }
    .type-client-card.selected { border-color: #198754; background: rgba(25,135,84,0.1); }
    .type-client-card i { font-size: 2.5rem; margin-bottom: 0.75rem; color: var(--bs-secondary-color); display: block; }
    .type-client-card.selected i { color: #198754; }
    .type-client-card .title { font-weight: 600; font-size: 1rem; color: var(--bs-body-color); }
    .type-client-card .subtitle { font-size: 0.8125rem; color: var(--bs-secondary-color); margin-top: 0.25rem; }

    /* ── Avatar ── */
    .avatar-lg {
        width: 80px; height: 80px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.75rem; font-weight: 700;
    }

    /* ── Zone mot de passe ── */
    .password-zone {
        background: rgba(25,135,84,0.04);
        border: 1.5px dashed rgba(25,135,84,0.35);
        border-radius: 16px; padding: 1.5rem;
    }
    .password-zone .zone-title {
        font-size: 0.75rem; font-weight: 700; color: #198754;
        text-transform: uppercase; letter-spacing: 1px;
        margin-bottom: 1rem; display: flex; align-items: center; gap: 0.4rem;
    }
    /* Eye toggle */
    .input-pwd-wrapper { position: relative; }
    .input-pwd-wrapper .form-control { padding-right: 3rem; }
    .btn-eye {
        position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%);
        background: none; border: none; color: var(--bs-secondary-color);
        cursor: pointer; padding: 0.25rem; transition: color 0.2s; z-index: 5;
    }
    .btn-eye:hover { color: #198754; }

    /* ── Info box ── */
    .info-box {
        background: linear-gradient(135deg, rgba(25,135,84,0.08) 0%, rgba(32,201,151,0.08) 100%);
        border: 1px solid rgba(25,135,84,0.2); border-radius: 16px; padding: 1.25rem;
    }
    .info-box .info-title { font-weight: 700; color: #198754; font-size: 0.9375rem; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem; }
    .info-box ul { margin: 0; padding-left: 1.25rem; color: var(--bs-secondary-color); font-size: 0.875rem; }
    .info-box li { margin-bottom: 0.5rem; }
    .info-box li:last-child { margin-bottom: 0; }

    /* ── Boutons ── */
    .btn-save {
        background: linear-gradient(135deg, #198754 0%, #20c997 100%);
        border: none; padding: 0.875rem 2rem;
        font-weight: 600; font-size: 1rem; border-radius: 12px; color: #fff;
        transition: all 0.3s ease;
        display: flex; align-items: center; justify-content: center; gap: 0.5rem;
    }
    .btn-save:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(25,135,84,0.35); color: #fff; }
    .btn-cancel {
        background: var(--bs-body-bg); border: 2px solid var(--bs-border-color);
        color: var(--bs-body-color); padding: 0.875rem 1.75rem;
        font-weight: 600; font-size: 1rem; border-radius: 12px;
        transition: all 0.3s ease;
        display: flex; align-items: center; justify-content: center; gap: 0.5rem;
    }
    .btn-cancel:hover { background: var(--bs-tertiary-bg); border-color: var(--bs-body-color); color: var(--bs-body-color); transform: translateY(-2px); }

    /* ── Tabs ── */
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
        border-bottom: 2px solid var(--bs-body-bg); margin-bottom: -2px;
    }
    .nav-tabs-custom .nav-link.has-error { color: #dc3545; }
    .nav-tabs-custom .nav-link.has-error::after {
        content: '●'; font-size: 0.5rem; vertical-align: super;
        margin-left: 0.25rem; color: #dc3545;
    }

    /* ── Type badges ── */
    .type-badge { padding: 0.3rem 0.7rem; border-radius: 20px; font-size: 0.7rem; font-weight: 700; display: inline-block; text-transform: uppercase; letter-spacing: 0.5px; }
    .type-particulier { background: rgba(111,66,193,0.12); color: #6f42c1; }
    .type-entreprise  { background: rgba(13,110,253,0.12);  color: #0d6efd; }
    .type-institution { background: rgba(253,126,20,0.12);  color: #fd7e14; }

    /* ── Résumé client sidebar ── */
    .client-summary-item { padding: 0.6rem 0; border-bottom: 1px solid var(--bs-border-color); }
    .client-summary-item:last-child { border-bottom: none; padding-bottom: 0; }
    .summary-label { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; color: var(--bs-secondary-color); margin-bottom: 0.15rem; }
    .summary-value { font-size: 0.9rem; font-weight: 600; color: var(--bs-body-color); }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fadeInUp 0.4s ease-out both; }
    .animate-fade-in:nth-child(1) { animation-delay: 0.05s; }
    .animate-fade-in:nth-child(2) { animation-delay: 0.1s; }
    .animate-fade-in:nth-child(3) { animation-delay: 0.15s; }
    .animate-fade-in:nth-child(4) { animation-delay: 0.2s; }

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
                    <i class="bi bi-pencil-square"></i>
                    Modifier le Client
                </h3>
                <div class="mt-1 opacity-75" style="font-size:0.9rem;">
                    {{ $client->nom_affichage }}
                </div>
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
                    <li class="breadcrumb-item active">Modifier</li>
                </ol>
            </div>
        </div>
    </div>

    {{-- SESSION ERROR --}}
    @if(session('error'))
    <div class="session-error-alert animate-fade-in">
        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    {{-- SESSION SUCCESS --}}
    @if(session('success'))
    <div class="session-success-alert animate-fade-in">
        <i class="bi bi-check-circle-fill fs-5"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    {{-- ERREURS GLOBALES --}}
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

    <div class="row">

        {{-- ═══════════════════════════════════════════════
             COLONNE PRINCIPALE
        ═══════════════════════════════════════════════ --}}
        <div class="col-lg-8">

            <form action="{{ route('admin.entreprise.clients.update', $client->id) }}"
                  method="POST" id="clientForm">
                @csrf
                @method('PUT')

                {{-- Avatar + Statut --}}
                <div class="card premium-card animate-fade-in">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="avatar-lg text-white"
                                    style="background: linear-gradient(135deg, #198754 0%, #20c997 100%);"
                                    id="avatarPreview">
                                    @if($client->type_client == 'particulier')
                                        {{ strtoupper(substr($client->prenoms ?? 'N', 0, 1)) }}{{ strtoupper(substr($client->nom ?? 'A', 0, 1)) }}
                                    @else
                                        {{ strtoupper(substr($client->raison_sociale ?? 'EN', 0, 2)) }}
                                    @endif
                                </div>
                            </div>
                            <div class="col">
                                <h4 class="mb-1" id="clientNamePreview">{{ $client->nom_affichage }}</h4>
                                <span class="type-badge type-{{ $client->type_client }}" id="typeBadge">
                                    @switch($client->type_client)
                                        @case('particulier') Particulier @break
                                        @case('entreprise')  Entreprise  @break
                                        @case('institution') Institution @break
                                    @endswitch
                                </span>
                            </div>
                            <div class="col-auto">
                                {{-- ✅ hidden AVANT checkbox pour gérer le décoché --}}
                                <div class="form-check form-switch">
                                    <input type="hidden" name="est_actif" value="0">
                                    <input class="form-check-input" type="checkbox"
                                        name="est_actif" id="est_actif" value="1"
                                        {{ old('est_actif', $client->est_actif) ? 'checked' : '' }}
                                        role="switch">
                                    <label class="form-check-label" for="est_actif">Client actif</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Type de client --}}
                <div class="card premium-card animate-fade-in">
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
                        <input type="hidden" name="type_client" id="type_client"
                            value="{{ old('type_client', $client->type_client) }}">
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
                    <li class="nav-item" id="entreprise-tab-item" role="presentation">
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

                    {{-- ════════════════════════════
                         Tab 1 : Coordonnées
                    ════════════════════════════ --}}
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
                                                Nom
                                            </label>
                                            <input type="text" name="nom" id="nom"
                                                class="form-control @error('nom') is-invalid @enderror"
                                                value="{{ old('nom', $client->nom) }}"
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
                                                value="{{ old('prenoms', $client->prenoms) }}"
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
                                                value="{{ old('date_naissance', $client->date_naissance?->format('Y-m-d')) }}">
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
                                <div id="entreprise-fields">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label" for="raison_sociale">
                                                <i class="bi bi-building"></i>
                                                Raison sociale <span class="required-indicator">*</span>
                                            </label>
                                            <input type="text" name="raison_sociale" id="raison_sociale"
                                                class="form-control @error('raison_sociale') is-invalid @enderror"
                                                value="{{ old('raison_sociale', $client->raison_sociale) }}"
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
                                                value="{{ old('nif', $client->nif) }}"
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
                                                value="{{ old('rc', $client->rc) }}"
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
                                            value="{{ old('email', $client->email) }}"
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
                                            value="{{ old('telephone', $client->telephone) }}"
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
                                            value="{{ old('telephone_secondaire', $client->telephone_secondaire) }}"
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
                                            placeholder="N° rue, quartier, commune…">{{ old('adresse', $client->adresse) }}</textarea>
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
                                            value="{{ old('ville', $client->ville) }}"
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
                                            value="{{ old('pays', $client->pays ?? 'Bénin') }}"
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

                    {{-- ════════════════════════════
                         Tab 2 : Infos entreprise
                    ════════════════════════════ --}}
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
                                            value="{{ old('representant_nom', $client->representant_nom) }}"
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
                                            Prénoms
                                        </label>
                                        <input type="text" name="representant_prenom" id="representant_prenom"
                                            class="form-control @error('representant_prenom') is-invalid @enderror"
                                            value="{{ old('representant_prenom', $client->representant_prenom) }}"
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
                                            value="{{ old('representant_fonction', $client->representant_fonction) }}"
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

                    {{-- ════════════════════════════
                         Tab 3 : Contact principal
                    ════════════════════════════ --}}
                    <div class="tab-pane fade" id="contact" role="tabpanel">
                        <div class="card premium-card mb-4">
                            <div class="card-body">
                                <div class="section-title">
                                    <i class="bi bi-person-lines-fill"></i>
                                    Personne à contacter
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label" for="contact_principal_nom">
                                            <i class="bi bi-person"></i>
                                            Nom complet
                                        </label>
                                        <input type="text" name="contact_principal_nom" id="contact_principal_nom"
                                            class="form-control @error('contact_principal_nom') is-invalid @enderror"
                                            value="{{ old('contact_principal_nom', $client->contact_principal_nom) }}"
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
                                            value="{{ old('contact_principal_fonction', $client->contact_principal_fonction) }}"
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
                                            value="{{ old('contact_email', $client->contact_email) }}"
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

                    {{-- ════════════════════════════
                         Tab 4 : Accès système
                    ════════════════════════════ --}}
                    <div class="tab-pane fade" id="acces" role="tabpanel">
                        <div class="card premium-card mb-4">
                            <div class="card-body">
                                <div class="section-title">
                                    <i class="bi bi-shield-lock"></i>
                                    Modifier le mot de passe
                                </div>

                                <div class="info-box mb-4">
                                    <div class="info-title">
                                        <i class="bi bi-info-circle-fill"></i>
                                        Information
                                    </div>
                                    <ul>
                                        <li><strong>Laissez les deux champs vides</strong> si vous ne souhaitez pas changer le mot de passe — la modification s'enregistrera quand même.</li>
                                        <li>Si vous remplissez un champ, vous devez remplir les deux et ils doivent correspondre.</li>
                                    </ul>
                                </div>

                                <div class="password-zone">
                                    <div class="zone-title">
                                        <i class="bi bi-key-fill"></i>
                                        Nouveau mot de passe (optionnel)
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label" for="password">
                                                <i class="bi bi-key"></i>
                                                Nouveau mot de passe
                                            </label>
                                            <div class="input-pwd-wrapper">
                                                <input type="password" name="password" id="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    placeholder="Laisser vide pour ne pas changer"
                                                    autocomplete="new-password">
                                                <button type="button" class="btn-eye"
                                                    onclick="toggleEye('password','eyeIcon1')" tabindex="-1">
                                                    <i class="bi bi-eye" id="eyeIcon1"></i>
                                                </button>
                                            </div>
                                            @error('password')
                                                <div class="field-error">
                                                    <i class="bi bi-exclamation-circle-fill"></i>
                                                    <span>{{ $message }}</span>
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="password_confirmation">
                                                <i class="bi bi-key-fill"></i>
                                                Confirmer le mot de passe
                                            </label>
                                            <div class="input-pwd-wrapper">
                                                <input type="password" name="password_confirmation"
                                                    id="password_confirmation"
                                                    class="form-control"
                                                    placeholder="Confirmer le nouveau mot de passe"
                                                    autocomplete="new-password">
                                                <button type="button" class="btn-eye"
                                                    onclick="toggleEye('password_confirmation','eyeIcon2')" tabindex="-1">
                                                    <i class="bi bi-eye" id="eyeIcon2"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if($client->last_login_at)
                                <hr class="my-4">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <div style="font-size:0.8125rem; color:var(--bs-secondary-color); display:flex; align-items:center; gap:0.4rem;">
                                            <i class="bi bi-clock"></i>
                                            Dernière connexion : {{ $client->last_login_at->format('d/m/Y à H:i') }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div style="font-size:0.8125rem; color:var(--bs-secondary-color); display:flex; align-items:center; gap:0.4rem;">
                                            <i class="bi bi-geo-alt"></i>
                                            IP : {{ $client->last_login_ip ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                                @endif

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
                            Enregistrer les modifications
                        </button>
                    </div>
                </div>

            </form>

        </div>

        {{-- ═══════════════════════════════════════════════
             COLONNE LATÉRALE — Résumé client
        ═══════════════════════════════════════════════ --}}
        <div class="col-lg-4">
            <div class="card premium-card animate-fade-in" style="position:sticky; top:1rem;">
                <div class="card-header">
                    <h5><i class="bi bi-person-lines-fill"></i> Résumé du client</h5>
                </div>
                <div class="card-body">
                    <div class="client-summary-item">
                        <div class="summary-label">Type</div>
                        <div class="summary-value mt-1">
                            <span class="type-badge type-{{ $client->type_client }}">
                                {{ ucfirst($client->type_client) }}
                            </span>
                        </div>
                    </div>
                    <div class="client-summary-item">
                        <div class="summary-label">Nom / Raison sociale</div>
                        <div class="summary-value">{{ $client->nom_affichage }}</div>
                    </div>
                    @if($client->email)
                    <div class="client-summary-item">
                        <div class="summary-label">Email</div>
                        <div class="summary-value" style="font-size:0.8125rem;">{{ $client->email }}</div>
                    </div>
                    @endif
                    <div class="client-summary-item">
                        <div class="summary-label">Téléphone</div>
                        <div class="summary-value">{{ $client->telephone }}</div>
                    </div>
                    @if($client->ville)
                    <div class="client-summary-item">
                        <div class="summary-label">Ville</div>
                        <div class="summary-value">{{ $client->ville }}</div>
                    </div>
                    @endif
                    <div class="client-summary-item">
                        <div class="summary-label">Statut</div>
                        <div class="summary-value mt-1">
                            @if($client->est_actif)
                                <span style="background:rgba(25,135,84,0.12);color:#198754;padding:0.25rem 0.65rem;border-radius:20px;font-size:0.75rem;font-weight:700;">
                                    <i class="bi bi-check-circle-fill me-1"></i>Actif
                                </span>
                            @else
                                <span style="background:rgba(108,117,125,0.12);color:#6c757d;padding:0.25rem 0.65rem;border-radius:20px;font-size:0.75rem;font-weight:700;">
                                    <i class="bi bi-pause-circle-fill me-1"></i>Inactif
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="client-summary-item">
                        <div class="summary-label">Client depuis</div>
                        <div class="summary-value">{{ $client->created_at?->format('d/m/Y') }}</div>
                    </div>
                    <div class="client-summary-item">
                        <div class="summary-label">Dernière mise à jour</div>
                        <div class="summary-value">{{ $client->updated_at?->format('d/m/Y à H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    // ── Sélection du type ─────────────────────────────────────────────────────
    function selectType(type) {
        document.querySelectorAll('.type-client-card').forEach(c => c.classList.remove('selected'));
        var card = document.getElementById('type-' + type + '-card');
        if (card) card.classList.add('selected');
        document.getElementById('type_client').value = type;

        var pf  = document.getElementById('particulier-fields');
        var ef  = document.getElementById('entreprise-fields');
        var eti = document.getElementById('entreprise-tab-item');

        if (type === 'particulier') {
            pf.style.display  = 'block';
            ef.style.display  = 'none';
            if (eti) eti.style.display = 'none';
        } else {
            pf.style.display  = 'none';
            ef.style.display  = 'block';
            if (eti) eti.style.display = 'block';
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

    document.addEventListener('DOMContentLoaded', function () {
        // ── Initialiser le type au chargement ────────────────────────────────
        selectType("{{ old('type_client', $client->type_client) }}");

        // ── Ouvrir le bon onglet si erreurs ──────────────────────────────────
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