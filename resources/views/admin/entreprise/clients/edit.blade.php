@extends('layouts.app')

@section('title', 'Modifier le Client - Entreprise')

@push('styles')
<style>
    /* ═══════════════════════════════════════════════════════════════
       ULTRA PRO DESIGN - Modifier Client
       ═══════════════════════════════════════════════════════════════ */

    /* ── Page Header ── */
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
        font-weight: 600;
    }

    .page-header .breadcrumb-item + .breadcrumb-item::before {
        color: rgba(255, 255, 255, 0.6);
    }

    /* ── Premium Card ── */
    .premium-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
        background: var(--bs-body-bg);
        overflow: hidden;
        margin-bottom: 1.5rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .premium-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
    }

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

    .premium-card .card-body {
        padding: 1.75rem;
    }

    /* ── Section Title ── */
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

    /* ── Form Elements ── */
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
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #198754;
        box-shadow: 0 0 0 4px rgba(25, 135, 84, 0.15);
        outline: none;
    }

    .form-control::placeholder {
        color: var(--bs-secondary-color);
        opacity: 0.7;
    }

    /* ── Required Indicator ── */
    .required-indicator {
        color: #dc3545;
        font-weight: 700;
        font-size: 0.75rem;
    }

    /* ── Help Text ── */
    .help-text {
        font-size: 0.8125rem;
        color: var(--bs-secondary-color);
        margin-top: 0.35rem;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .help-text.text-danger {
        color: #dc3545;
    }

    /* ── Toggle Switch ── */
    .form-check-input:checked {
        background-color: #198754;
        border-color: #198754;
    }

    .form-check-input:focus {
        border-color: #198754;
        box-shadow: 0 0 0 4px rgba(25, 135, 84, 0.15);
    }

    .form-check-label {
        font-weight: 500;
        color: var(--bs-body-color);
    }

    /* ── Type Client Cards ── */
    .type-client-card {
        cursor: pointer;
        border: 2px solid var(--bs-border-color);
        border-radius: 16px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
        background: var(--bs-body-bg);
    }

    .type-client-card:hover {
        border-color: #198754;
        background: rgba(25, 135, 84, 0.05);
    }

    .type-client-card.selected {
        border-color: #198754;
        background: rgba(25, 135, 84, 0.1);
    }

    .type-client-card i {
        font-size: 2.5rem;
        margin-bottom: 0.75rem;
        color: var(--bs-secondary-color);
    }

    .type-client-card.selected i {
        color: #198754;
    }

    .type-client-card .title {
        font-weight: 600;
        font-size: 1rem;
        color: var(--bs-body-color);
    }

    .type-client-card .subtitle {
        font-size: 0.8125rem;
        color: var(--bs-secondary-color);
        margin-top: 0.25rem;
    }

    /* ── Avatar ── */
    .avatar-lg {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        font-weight: 700;
    }

    /* ── Buttons ── */
    .btn-save {
        background: linear-gradient(135deg, #198754 0%, #20c997 100%);
        border: none;
        padding: 0.875rem 2rem;
        font-weight: 600;
        font-size: 1rem;
        border-radius: 12px;
        color: #fff;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(25, 135, 84, 0.35);
        color: #fff;
    }

    .btn-cancel {
        background: var(--bs-body-bg);
        border: 2px solid var(--bs-border-color);
        color: var(--bs-body-color);
        padding: 0.875rem 1.75rem;
        font-weight: 600;
        font-size: 1rem;
        border-radius: 12px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-cancel:hover {
        background: var(--bs-tertiary-bg);
        border-color: var(--bs-body-color);
        color: var(--bs-body-color);
        transform: translateY(-2px);
    }

    /* ── Info Box ── */
    .info-box {
        background: linear-gradient(135deg, rgba(25, 135, 84, 0.08) 0%, rgba(32, 201, 151, 0.08) 100%);
        border: 1px solid rgba(25, 135, 84, 0.2);
        border-radius: 16px;
        padding: 1.25rem;
    }

    .info-box .info-title {
        font-weight: 700;
        color: #198754;
        font-size: 0.9375rem;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-box ul {
        margin: 0;
        padding-left: 1.25rem;
        color: var(--bs-secondary-color);
        font-size: 0.875rem;
    }

    .info-box li {
        margin-bottom: 0.5rem;
    }

    .info-box li:last-child {
        margin-bottom: 0;
    }

    /* ── Nav Tabs ── */
    .nav-tabs-custom {
        border-bottom: 2px solid var(--bs-border-color);
        margin-bottom: 1.5rem;
    }

    .nav-tabs-custom .nav-link {
        border: none;
        color: var(--bs-secondary-color);
        padding: 0.75rem 1.25rem;
        font-weight: 600;
        border-radius: 10px 10px 0 0;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .nav-tabs-custom .nav-link:hover {
        color: #198754;
        background: rgba(25, 135, 84, 0.05);
    }

    .nav-tabs-custom .nav-link.active {
        color: #198754;
        background: var(--bs-body-bg);
        border: 2px solid var(--bs-border-color);
        border-bottom: 2px solid var(--bs-body-bg);
        margin-bottom: -2px;
    }

    /* ── Type Badge ── */
    .type-badge {
        padding: 0.3rem 0.7rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        display: inline-block;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .type-particulier {
        background: rgba(111, 66, 193, 0.12);
        color: #6f42c1;
    }

    .type-entreprise {
        background: rgba(13, 110, 253, 0.12);
        color: #0d6efd;
    }

    .type-institution {
        background: rgba(253, 126, 20, 0.12);
        color: #fd7e14;
    }

    /* ── Animation ── */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fadeInUp 0.4s ease-out both;
    }

    .animate-fade-in:nth-child(1) { animation-delay: 0.05s; }
    .animate-fade-in:nth-child(2) { animation-delay: 0.1s; }
    .animate-fade-in:nth-child(3) { animation-delay: 0.15s; }
    .animate-fade-in:nth-child(4) { animation-delay: 0.2s; }
    .animate-fade-in:nth-child(5) { animation-delay: 0.25s; }

    /* ── Responsive ── */
    @media (max-width: 768px) {
        .page-header {
            padding: 1.25rem 1.5rem;
        }

        .page-header h3 {
            font-size: 1.3rem;
        }

        .premium-card .card-body {
            padding: 1.25rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">

    {{-- ═══════════════════════════════════════════════════════
         HEADER
    ═══════════════════════════════════════════════════════ --}}
    <div class="page-header animate-fade-in">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h3>
                    <i class="bi bi-pencil-square"></i>
                    Modifier le Client
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
                    <li class="breadcrumb-item active">Modifier</li>
                </ol>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         FORMULAIRE
    ═══════════════════════════════════════════════════════ --}}
    <form action="{{ route('admin.entreprise.clients.update', $client->id) }}" method="POST" id="clientForm" class="animate-fade-in">
        @csrf
        @method('PUT')

        {{-- Header with Avatar --}}
        <div class="card premium-card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="avatar-lg text-white" style="background: linear-gradient(135deg, #198754 0%, #20c997 100%);">
                            @if($client->type_client == 'particulier')
                                {{ strtoupper(substr($client->prenoms ?? 'N', 0, 1)) }}{{ strtoupper(substr($client->nom ?? 'A', 0, 1)) }}
                            @else
                                {{ strtoupper(substr($client->raison_sociale ?? 'E', 0, 2)) }}
                            @endif
                        </div>
                    </div>
                    <div class="col">
                        <h4 class="mb-1">{{ $client->nom_affichage }}</h4>
                        <span class="type-badge type-{{ $client->type_client }}">
                            @switch($client->type_client)
                                @case('particulier') Particulier @break
                                @case('entreprise') Entreprise @break
                                @case('institution') Institution @break
                            @endswitch
                        </span>
                    </div>
                    <div class="col-auto">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="est_actif" id="est_actif" value="1" {{ $client->est_actif ? 'checked' : '' }}>
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
                <li>Les champs marqués d'un astérisque (*) sont obligatoires.</li>
                <li>Pour les entreprises et institutions, veuillez fournir le NIF et le RCCM.</li>
                <li>Laissez les champs mot de passe vides si vous ne souhaitez pas modifier le mot de passe.</li>
            </ul>
        </div>

        {{-- Type de client --}}
        <div class="card premium-card mb-4">
            <div class="card-header">
                <h5><i class="bi bi-tag-fill"></i>Type de client</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="type-client-card" onclick="selectType('particulier')" id="type-particulier">
                            <i class="bi bi-person"></i>
                            <div class="title">Particulier</div>
                            <div class="subtitle">Personne physique</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="type-client-card" onclick="selectType('entreprise')" id="type-entreprise">
                            <i class="bi bi-building"></i>
                            <div class="title">Entreprise</div>
                            <div class="subtitle">Personne morale</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="type-client-card" onclick="selectType('institution')" id="type-institution">
                            <i class="bi bi-bank"></i>
                            <div class="title">Institution</div>
                            <div class="subtitle">Organisation publique</div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="type_client" id="type_client" value="{{ old('type_client', $client->type_client) }}">
            </div>
        </div>

        {{-- Tabs --}}
        <ul class="nav nav-tabs-custom" id="clientTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="coordonnees-tab" data-bs-toggle="tab" data-bs-target="#coordonnees" type="button">
                    <i class="bi bi-person"></i> Coordonnées
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="entreprise-tab" data-bs-toggle="tab" data-bs-target="#entreprise-info" type="button">
                    <i class="bi bi-building"></i> Infos entreprise
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button">
                    <i class="bi bi-telephone"></i> Contact principal
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="acces-tab" data-bs-toggle="tab" data-bs-target="#acces" type="button">
                    <i class="bi bi-shield-lock"></i> Accès système
                </button>
            </li>
        </ul>

        <div class="tab-content" id="clientTabsContent">
            {{-- Tab 1: Coordonnées --}}
            <div class="tab-pane fade show active" id="coordonnees" role="tabpanel">
                <div class="card premium-card mb-4">
                    <div class="card-body">
                        <div class="section-title">
                            <i class="bi bi-person"></i>
                            Informations du client
                        </div>

                        {{-- Particulier fields --}}
                        <div id="particulier-fields">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">
                                        <i class="bi bi-person"></i>
                                        Nom <span class="required-indicator">*</span>
                                    </label>
                                    <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror"
                                        value="{{ old('nom', $client->nom) }}" required>
                                    @error('nom')
                                    <div class="help-text text-danger"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">
                                        <i class="bi bi-person"></i>
                                        Prénoms
                                    </label>
                                    <input type="text" name="prenoms" class="form-control"
                                        value="{{ old('prenoms', $client->prenoms) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">
                                        <i class="bi bi-calendar"></i>
                                        Date de naissance
                                    </label>
                                    <input type="date" name="date_naissance" class="form-control" 
                                        value="{{ old('date_naissance', $client->date_naissance?->format('Y-m-d')) }}">
                                </div>
                            </div>
                        </div>

                        {{-- Entreprise/Institution fields --}}
                        <div id="entreprise-fields">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="bi bi-building"></i>
                                        Raison sociale
                                    </label>
                                    <input type="text" name="raison_sociale" class="form-control"
                                        value="{{ old('raison_sociale', $client->raison_sociale) }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">
                                        <i class="bi bi-upc-scan"></i>
                                        NIF
                                    </label>
                                    <input type="text" name="nif" class="form-control"
                                        value="{{ old('nif', $client->nif) }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">
                                        <i class="bi bi-file-earmark-text"></i>
                                        RCCM
                                    </label>
                                    <input type="text" name="rc" class="form-control"
                                        value="{{ old('rc', $client->rc) }}">
                                </div>
                            </div>
                        </div>

                        <div class="section-title mt-4">
                            <i class="bi bi-envelope"></i>
                            Coordonnées
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="bi bi-envelope"></i>
                                    Email <span class="required-indicator">*</span>
                                </label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $client->email) }}" required>
                                @error('email')
                                <div class="help-text text-danger"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="bi bi-telephone"></i>
                                    Téléphone principal
                                </label>
                                <input type="text" name="telephone" class="form-control"
                                    value="{{ old('telephone', $client->telephone) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="bi bi-telephone"></i>
                                    Téléphone secondaire
                                </label>
                                <input type="text" name="telephone_secondaire" class="form-control"
                                    value="{{ old('telephone_secondaire', $client->telephone_secondaire) }}">
                            </div>
                        </div>

                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-geo-alt"></i>
                                    Adresse
                                </label>
                                <textarea name="adresse" class="form-control" rows="2">{{ old('adresse', $client->adresse) }}</textarea>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">
                                    <i class="bi bi-building"></i>
                                    Ville
                                </label>
                                <input type="text" name="ville" class="form-control"
                                    value="{{ old('ville', $client->ville) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">
                                    <i class="bi bi-globe"></i>
                                    Pays
                                </label>
                                <input type="text" name="pays" class="form-control"
                                    value="{{ old('pays', $client->pays) }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab 2: Informations entreprise --}}
            <div class="tab-pane fade" id="entreprise-info" role="tabpanel">
                <div class="card premium-card mb-4">
                    <div class="card-body">
                        <div class="section-title">
                            <i class="bi bi-person-badge"></i>
                            Représentant légal
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="bi bi-person"></i>
                                    Nom du représentant
                                </label>
                                <input type="text" name="representant_nom" class="form-control"
                                    value="{{ old('representant_nom', $client->representant_nom) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="bi bi-person"></i>
                                    Prénoms du représentant
                                </label>
                                <input type="text" name="representant_prenom" class="form-control"
                                    value="{{ old('representant_prenom', $client->representant_prenom) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="bi bi-briefcase"></i>
                                    Fonction
                                </label>
                                <input type="text" name="representant_fonction" class="form-control"
                                    value="{{ old('representant_fonction', $client->representant_fonction) }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab 3: Contact principal --}}
            <div class="tab-pane fade" id="contact" role="tabpanel">
                <div class="card premium-card mb-4">
                    <div class="card-body">
                        <div class="section-title">
                            <i class="bi bi-person-lines-fill"></i>
                            Personne à contacter
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="bi bi-person"></i>
                                    Nom complet
                                </label>
                                <input type="text" name="contact_principal_nom" class="form-control"
                                    value="{{ old('contact_principal_nom', $client->contact_principal_nom) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="bi bi-briefcase"></i>
                                    Fonction
                                </label>
                                <input type="text" name="contact_principal_fonction" class="form-control"
                                    value="{{ old('contact_principal_fonction', $client->contact_principal_fonction) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="bi bi-envelope"></i>
                                    Email
                                </label>
                                <input type="email" name="contact_email" class="form-control"
                                    value="{{ old('contact_email', $client->contact_email) }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab 4: Accès système --}}
            <div class="tab-pane fade" id="acces" role="tabpanel">
                <div class="card premium-card mb-4">
                    <div class="card-body">
                        <div class="section-title">
                            <i class="bi bi-shield-lock"></i>
                            Modifier le mot de passe
                        </div>

                        <div class="info-box mb-4">
                            <div class="info-title">
                                <i class="bi bi-info-circle"></i>
                                Information
                            </div>
                            <ul>
                                <li>Laissez les champs mot de passe vides si vous ne souhaitez pas modifier le mot de passe.</li>
                            </ul>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-key"></i>
                                    Nouveau mot de passe
                                </label>
                                <input type="password" name="password" class="form-control" id="password" placeholder="Nouveau mot de passe">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-key-fill"></i>
                                    Confirmer le mot de passe
                                </label>
                                <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Confirmer le mot de passe">
                            </div>
                        </div>

                        @if($client->last_login_at)
                        <hr class="my-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="help-text">
                                    <i class="bi bi-clock me-1"></i>
                                    Dernière connexion : {{ $client->last_login_at->format('d/m/Y à H:i') }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="help-text">
                                    <i class="bi bi-geo-alt me-1"></i>
                                    IP : {{ $client->last_login_ip ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="card premium-card animate-fade-in">
            <div class="card-body d-flex justify-content-between">
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

@push('scripts')
<script>
    function selectType(type) {
        document.querySelectorAll('.type-client-card').forEach(function(card) {
            card.classList.remove('selected');
        });
        document.getElementById('type-' + type).classList.add('selected');
        document.getElementById('type_client').value = type;

        var particulierFields = document.getElementById('particulier-fields');
        var entrepriseFields = document.getElementById('entreprise-fields');
        var entrepriseTab = document.getElementById('entreprise-tab');

        if (type === 'particulier') {
            particulierFields.style.display = 'block';
            entrepriseFields.style.display = 'none';
            entrepriseTab.style.display = 'none';
        } else {
            particulierFields.style.display = 'none';
            entrepriseFields.style.display = 'block';
            entrepriseTab.style.display = 'block';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        var currentType = "{{ old('type_client', $client->type_client) }}";
        selectType(currentType);
    });
</script>
@endpush
@endsection
