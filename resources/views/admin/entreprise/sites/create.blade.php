@extends('layouts.app')

@section('title', 'Nouveau Site - Entreprise')

@push('styles')
<style>
    /* ═══════════════════════════════════════════════════════════════
       ULTRA PRO DESIGN - Nouveau Site
       ═══════════════════════════════════════════════════════════════ */
    
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

    /* ── Cards Premium ── */
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

    /* ── Form Elements Ultra Pro ── */
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
        background: linear-gradient(135deg, #146c43 0%, #1aa179 100%);
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

    /* Stagger animations */
    .animate-fade-in:nth-child(1) { animation-delay: 0.05s; }
    .animate-fade-in:nth-child(2) { animation-delay: 0.1s; }
    .animate-fade-in:nth-child(3) { animation-delay: 0.15s; }
    .animate-fade-in:nth-child(4) { animation-delay: 0.2s; }
    .animate-fade-in:nth-child(5) { animation-delay: 0.25s; }

    /* ── Icon Boxes ── */
    .icon-box {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(25, 135, 84, 0.1);
        color: #198754;
    }

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
         HEADER - Titre et Fil d'Ariane
    ═══════════════════════════════════════════════════════ --}}
    <div class="page-header animate-fade-in">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h3>
                    <i class="bi bi-geo-alt-fill"></i>
                    Nouveau Site
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
                        <a href="{{ route('admin.entreprise.sites.index') }}">Sites</a>
                    </li>
                    <li class="breadcrumb-item active">Nouveau</li>
                </ol>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         FORMULAIRE
    ═══════════════════════════════════════════════════════ --}}
    <form action="{{ route('admin.entreprise.sites.store') }}" method="POST" class="animate-fade-in">
        @csrf

        <div class="row g-4">

            {{-- ═══════════════════════════════════════════════
                 COLONNE PRINCIPALE - Informations
            ═══════════════════════════════════════════════ --}}
            <div class="col-lg-8">

                {{-- Card: Informations générales --}}
                <div class="card premium-card animate-fade-in">
                    <div class="card-header">
                        <h5><i class="bi bi-info-circle-fill"></i>Informations générales</h5>
                    </div>
                    <div class="card-body">
                        <div class="section-title">
                            <i class="bi bi-building"></i>
                            Client & Site
                        </div>
                        <div class="row g-3">

                            {{-- Client --}}
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-person-vcard"></i>
                                    Client <span class="required-indicator">*</span>
                                </label>
                                <select name="client_id" class="form-select" required>
                                    <option value="">— Sélectionner un client —</option>
                                    @foreach($clients as $client)
                                    <option value="{{ $client->id }}"
                                        {{ old('client_id', $clientId ?? '') == $client->id ? 'selected' : '' }}>
                                        {{ $client->nom }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('client_id')
                                <div class="help-text text-danger">
                                    <i class="bi bi-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            {{-- Nom du site --}}
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-geo"></i>
                                    Nom du site <span class="required-indicator">*</span>
                                </label>
                                <input type="text" name="nom_site" class="form-control"
                                    value="{{ old('nom_site') }}"
                                    placeholder="Ex : Siège principal" required>
                                @error('nom_site')
                                <div class="help-text text-danger">
                                    <i class="bi bi-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            {{-- Code site --}}
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-upc-scan"></i>
                                    Code site
                                </label>
                                <input type="text" name="code_site" class="form-control"
                                    value="{{ old('code_site') }}"
                                    placeholder="Auto-généré si vide">
                                <div class="help-text">
                                    <i class="bi bi-magic"></i>
                                    Laissez vide pour une génération automatique
                                </div>
                            </div>

                            {{-- Niveau de risque --}}
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-shield-exclamation"></i>
                                    Niveau de risque
                                </label>
                                <select name="niveau_risque" class="form-select">
                                    <option value="faible" {{ old('niveau_risque','faible') == 'faible' ? 'selected' : '' }}>
                                        🟢 Faible
                                    </option>
                                    <option value="moyen" {{ old('niveau_risque') == 'moyen' ? 'selected' : '' }}>
                                        🟡 Moyen
                                    </option>
                                    <option value="haut" {{ old('niveau_risque') == 'haut' ? 'selected' : '' }}>
                                        🟠 Haut
                                    </option>
                                    <option value="critique" {{ old('niveau_risque') == 'critique' ? 'selected' : '' }}>
                                        🔴 Critique
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card: Adresse --}}
                <div class="card premium-card animate-fade-in">
                    <div class="card-header">
                        <h5><i class="bi bi-geo-fill"></i>Adresse</h5>
                    </div>
                    <div class="card-body">
                        <div class="section-title">
                            <i class="bi bi-pin-map"></i>
                            Localisation
                        </div>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">
                                    <i class="bi bi-signpost"></i>
                                    Adresse complète <span class="required-indicator">*</span>
                                </label>
                                <input type="text" name="adresse" class="form-control"
                                    value="{{ old('adresse') }}"
                                    placeholder="N° rue, quartier, commune…" required>
                                @error('adresse')
                                <div class="help-text text-danger">
                                    <i class="bi bi-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="bi bi-building"></i>
                                    Ville
                                </label>
                                <input type="text" name="ville" class="form-control"
                                    value="{{ old('ville') }}" placeholder="Ex : Cotonou">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="bi bi-diagram-3"></i>
                                    Commune
                                </label>
                                <input type="text" name="commune" class="form-control"
                                    value="{{ old('commune') }}" placeholder="Ex : Cotonou">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="bi bi-sign-intersection-y"></i>
                                    Quartier
                                </label>
                                <input type="text" name="quartier" class="form-control"
                                    value="{{ old('quartier') }}" placeholder="Ex : Akando">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card: Contact --}}
                <div class="card premium-card animate-fade-in">
                    <div class="card-header">
                        <h5><i class="bi bi-person-lines-fill"></i>Contact sur site</h5>
                    </div>
                    <div class="card-body">
                        <div class="section-title">
                            <i class="bi bi-person-badge"></i>
                            Responsable
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="bi bi-person"></i>
                                    Nom du contact
                                </label>
                                <input type="text" name="contact_nom" class="form-control"
                                    value="{{ old('contact_nom') }}" placeholder="Nom complet">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="bi bi-telephone"></i>
                                    Téléphone
                                </label>
                                <input type="text" name="contact_telephone" class="form-control"
                                    value="{{ old('contact_telephone') }}" placeholder="+229 XX XX XX XX">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="bi bi-envelope"></i>
                                    Email
                                </label>
                                <input type="email" name="contact_email" class="form-control"
                                    value="{{ old('contact_email') }}" placeholder="email@exemple.com">
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ═══════════════════════════════════════════════
                 COLONNE LATÉRALE - Statut & Actions
            ═══════════════════════════════════════════════ --}}
            <div class="col-lg-4">

                {{-- Card: Statut --}}
                <div class="card premium-card animate-fade-in">
                    <div class="card-header">
                        <h5><i class="bi bi-toggle-on"></i>Statut</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check form-switch mb-3">
                            <input type="checkbox" name="est_actif" id="est_actif"
                                class="form-check-input" value="1"
                                {{ old('est_actif', true) ? 'checked' : '' }}
                                role="switch">
                            <label class="form-check-label" for="est_actif">
                                Site actif
                            </label>
                        </div>
                        <div class="help-text">
                            <i class="bi bi-info-circle text-success"></i>
                            Un site inactif n'apparaît pas dans les listes de sélection.
                        </div>
                    </div>
                </div>

                {{-- Card: Actions --}}
                <div class="card premium-card animate-fade-in">
                    <div class="card-header">
                        <h5><i class="bi bi-send-fill"></i>Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-3">
                            <button type="submit" class="btn btn-save">
                                <i class="bi bi-check-circle-fill"></i>
                                Créer le site
                            </button>
                            <a href="{{ route('admin.entreprise.sites.index') }}" class="btn btn-cancel">
                                <i class="bi bi-arrow-left-circle"></i>
                                Retour à la liste
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Card: Aide --}}
                <div class="info-box animate-fade-in">
                    <div class="info-title">
                        <i class="bi bi-lightbulb-fill"></i>
                        Conseils utiles
                    </div>
                    <ul>
                        <li>Le code site est généré automatiquement si laissé vide.</li>
                        <li>Renseignez le contact pour faciliter la coordination des agents.</li>
                        <li>Le niveau de risque influence la priorité des affectations.</li>
                    </ul>
                </div>

            </div>
        </div>
    </form>

</div>
@endsection
