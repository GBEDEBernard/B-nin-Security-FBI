@extends('layouts.app')

@section('title', 'Modifier le Contrat - Entreprise')

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
        padding: 0.75rem 1rem;
        font-size: 0.9375rem;
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

    /* Erreurs inline */
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

    /* Alerte globale */
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

    /* Session error/success */
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

    /* Input group */
    .input-group .form-control { border-radius: 12px 0 0 12px; }
    .input-group-text {
        border: 2px solid var(--bs-border-color); border-left: none;
        border-radius: 0 12px 12px 0;
        background: var(--bs-tertiary-bg); color: var(--bs-secondary-color);
        font-weight: 600; font-size: 0.875rem;
    }
    .input-group .form-control:focus ~ .input-group-text { border-color: #198754; }

    /* Switch */
    .form-check-input { width: 2.8em; height: 1.5em; }
    .form-check-input:checked { background-color: #198754; border-color: #198754; }
    .form-check-input:focus { border-color: #198754; box-shadow: 0 0 0 4px rgba(25,135,84,0.15); }
    .form-check-label { font-weight: 500; color: var(--bs-body-color); }

    /* Résiliation zone */
    .resiliation-zone {
        background: rgba(220,53,69,0.05);
        border: 1.5px dashed rgba(220,53,69,0.4);
        border-radius: 16px; padding: 1.5rem; margin-top: 1rem;
        animation: slideDown 0.3s ease both;
    }
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .resiliation-zone .zone-title {
        font-size: 0.75rem; font-weight: 700; color: #dc3545;
        text-transform: uppercase; letter-spacing: 1px;
        margin-bottom: 1rem; display: flex; align-items: center; gap: 0.4rem;
    }

    /* Contrat info badge */
    .contrat-badge {
        display: inline-flex; align-items: center; gap: 0.5rem;
        padding: 0.4rem 1rem; border-radius: 20px;
        font-size: 0.8125rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.5px;
    }
    .badge-brouillon { background: rgba(108,117,125,0.12); color: #6c757d; }
    .badge-en_cours  { background: rgba(25,135,84,0.12);   color: #198754; }
    .badge-suspendu  { background: rgba(255,193,7,0.15);   color: #c49a00; }
    .badge-termine   { background: rgba(13,110,253,0.12);  color: #0d6efd; }
    .badge-resilie   { background: rgba(220,53,69,0.12);   color: #dc3545; }

    /* Boutons */
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

    /* Info box */
    .info-box {
        background: linear-gradient(135deg, rgba(25,135,84,0.08) 0%, rgba(32,201,151,0.08) 100%);
        border: 1px solid rgba(25,135,84,0.2); border-radius: 16px; padding: 1.25rem;
    }
    .info-box .info-title { font-weight: 700; color: #198754; font-size: 0.9375rem; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem; }
    .info-box ul { margin: 0; padding-left: 1.25rem; color: var(--bs-secondary-color); font-size: 0.875rem; }
    .info-box li { margin-bottom: 0.5rem; }
    .info-box li:last-child { margin-bottom: 0; }

    /* Résumé contrat dans sidebar */
    .contrat-summary-item { padding: 0.6rem 0; border-bottom: 1px solid var(--bs-border-color); }
    .contrat-summary-item:last-child { border-bottom: none; padding-bottom: 0; }
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
                    Modifier le Contrat
                </h3>
                <div class="mt-1 opacity-75" style="font-size:0.9rem;">
                    {{ $contrat->numero_contrat }} — {{ $contrat->intitule }}
                </div>
            </div>
            <div class="col-md-6">
                <ol class="breadcrumb float-md-end">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.entreprise.index') }}"><i class="bi bi-house-door"></i> Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.entreprise.contrats.index') }}">Contrats</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.entreprise.contrats.show', $contrat->id) }}">{{ $contrat->numero_contrat }}</a>
                    </li>
                    <li class="breadcrumb-item active">Modifier</li>
                </ol>
            </div>
        </div>
    </div>

    {{-- ALERTES SESSION --}}
    @if(session('error'))
    <div class="session-error-alert animate-fade-in">
        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

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

    {{-- FORMULAIRE --}}
    <form action="{{ route('admin.entreprise.contrats.update', $contrat->id) }}" method="POST" id="contratForm">
        @csrf
        @method('PUT')

        <div class="row">

            {{-- ═══════════════════════════════════════════════
                 COLONNE PRINCIPALE (gauche)
            ═══════════════════════════════════════════════ --}}
            <div class="col-lg-8">

                {{-- Informations générales --}}
                <div class="card premium-card animate-fade-in">
                    <div class="card-header">
                        <h5><i class="bi bi-info-circle-fill"></i> Informations générales</h5>
                    </div>
                    <div class="card-body">

                        <div class="row g-3">

                            {{-- Client --}}
                            <div class="col-md-6">
                                <label class="form-label" for="client_id">
                                    <i class="bi bi-person"></i>
                                    Client <span class="required-indicator">*</span>
                                </label>
                                <select name="client_id" id="client_id"
                                    class="form-select @error('client_id') is-invalid @enderror" required>
                                    <option value="">Sélectionner un client</option>
                                    @foreach($clients as $client)
                                    <option value="{{ $client->id }}"
                                        {{ old('client_id', $contrat->client_id) == $client->id ? 'selected' : '' }}>
                                        {{ $client->nom }}
                                        @if($client->type_client !== 'particulier' && $client->raison_sociale)
                                            — {{ $client->raison_sociale }}
                                        @endif
                                    </option>
                                    @endforeach
                                </select>
                                @error('client_id')
                                    <div class="field-error">
                                        <i class="bi bi-exclamation-circle-fill"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>

                            {{-- Numéro contrat --}}
                            <div class="col-md-6">
                                <label class="form-label" for="numero_contrat">
                                    <i class="bi bi-hash"></i>
                                    Numéro de contrat
                                </label>
                                <input type="text" name="numero_contrat" id="numero_contrat"
                                    class="form-control @error('numero_contrat') is-invalid @enderror"
                                    value="{{ old('numero_contrat', $contrat->numero_contrat) }}"
                                    placeholder="Ex : CTR-2024-001">
                                @error('numero_contrat')
                                    <div class="field-error">
                                        <i class="bi bi-exclamation-circle-fill"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>

                            {{-- Intitulé --}}
                            <div class="col-12">
                                <label class="form-label" for="intitule">
                                    <i class="bi bi-file-text"></i>
                                    Intitulé du contrat <span class="required-indicator">*</span>
                                </label>
                                <input type="text" name="intitule" id="intitule"
                                    class="form-control @error('intitule') is-invalid @enderror"
                                    value="{{ old('intitule', $contrat->intitule) }}"
                                    placeholder="Ex : Gardiennage siège social" required>
                                @error('intitule')
                                    <div class="field-error">
                                        <i class="bi bi-exclamation-circle-fill"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>

                            {{-- Date début --}}
                            <div class="col-md-6">
                                <label class="form-label" for="date_debut">
                                    <i class="bi bi-calendar-event"></i>
                                    Date de début <span class="required-indicator">*</span>
                                </label>
                                <input type="date" name="date_debut" id="date_debut"
                                    class="form-control @error('date_debut') is-invalid @enderror"
                                    value="{{ old('date_debut', $contrat->date_debut?->format('Y-m-d')) }}" required>
                                @error('date_debut')
                                    <div class="field-error">
                                        <i class="bi bi-exclamation-circle-fill"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>

                            {{-- Date fin --}}
                            <div class="col-md-6">
                                <label class="form-label" for="date_fin">
                                    <i class="bi bi-calendar-x"></i>
                                    Date de fin <span class="required-indicator">*</span>
                                </label>
                                <input type="date" name="date_fin" id="date_fin"
                                    class="form-control @error('date_fin') is-invalid @enderror"
                                    value="{{ old('date_fin', $contrat->date_fin?->format('Y-m-d')) }}" required>
                                @error('date_fin')
                                    <div class="field-error">
                                        <i class="bi bi-exclamation-circle-fill"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                                <div id="dateRangeError" class="field-error" style="display:none;">
                                    <i class="bi bi-exclamation-circle-fill"></i>
                                    <span>La date de fin doit être postérieure à la date de début.</span>
                                </div>
                            </div>

                            {{-- Renouvelable --}}
                            <div class="col-md-6">
                                <label class="form-label" style="margin-bottom:0.75rem;">
                                    <i class="bi bi-arrow-repeat"></i>
                                    Renouvellement
                                </label>
                                <div class="form-check form-switch">
                                    <input type="hidden" name="est_renouvelable" value="0">
                                    <input class="form-check-input" type="checkbox"
                                        name="est_renouvelable" id="est_renouvelable" value="1"
                                        {{ old('est_renouvelable', $contrat->est_renouvelable) ? 'checked' : '' }}
                                        role="switch">
                                    <label class="form-check-label" for="est_renouvelable">Contrat renouvelable</label>
                                </div>
                            </div>

                            {{-- Durée préavis --}}
                            <div class="col-md-6">
                                <label class="form-label" for="duree_preavis">
                                    <i class="bi bi-clock"></i>
                                    Durée de préavis (jours)
                                </label>
                                <input type="number" name="duree_preavis" id="duree_preavis"
                                    class="form-control @error('duree_preavis') is-invalid @enderror"
                                    value="{{ old('duree_preavis', $contrat->duree_preavis ?? 30) }}" min="0">
                                @error('duree_preavis')
                                    <div class="field-error">
                                        <i class="bi bi-exclamation-circle-fill"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Aspect financier --}}
                <div class="card premium-card animate-fade-in">
                    <div class="card-header">
                        <h5><i class="bi bi-currency-exchange"></i> Aspect financier</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">

                            {{-- Montant mensuel HT --}}
                            <div class="col-md-4">
                                <label class="form-label" for="montant_mensuel_ht">
                                    <i class="bi bi-cash-stack"></i>
                                    Montant mensuel HT <span class="required-indicator">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number" name="montant_mensuel_ht" id="montant_mensuel_ht"
                                        class="form-control @error('montant_mensuel_ht') is-invalid @enderror"
                                        value="{{ old('montant_mensuel_ht', $contrat->montant_mensuel_ht) }}"
                                        min="0" step="100" required
                                        oninput="updateMontantPreview()">
                                    <span class="input-group-text">FCFA</span>
                                </div>
                                @error('montant_mensuel_ht')
                                    <div class="field-error">
                                        <i class="bi bi-exclamation-circle-fill"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>

                            {{-- TVA --}}
                            <div class="col-md-4">
                                <label class="form-label" for="tva">
                                    <i class="bi bi-percent"></i>
                                    TVA (%)
                                </label>
                                <input type="number" name="tva" id="tva"
                                    class="form-control @error('tva') is-invalid @enderror"
                                    value="{{ old('tva', $contrat->tva ?? 18) }}"
                                    min="0" max="100"
                                    oninput="updateMontantPreview()">
                                @error('tva')
                                    <div class="field-error">
                                        <i class="bi bi-exclamation-circle-fill"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>

                            {{-- Périodicité --}}
                            <div class="col-md-4">
                                <label class="form-label" for="periodicite_facturation">
                                    <i class="bi bi-calendar-range"></i>
                                    Périodicité <span class="required-indicator">*</span>
                                </label>
                                <select name="periodicite_facturation" id="periodicite_facturation"
                                    class="form-select @error('periodicite_facturation') is-invalid @enderror" required>
                                    <option value="mensuel"      {{ old('periodicite_facturation', $contrat->periodicite_facturation) == 'mensuel'      ? 'selected' : '' }}>Mensuel</option>
                                    <option value="trimestriel"  {{ old('periodicite_facturation', $contrat->periodicite_facturation) == 'trimestriel'  ? 'selected' : '' }}>Trimestriel</option>
                                    <option value="semestriel"   {{ old('periodicite_facturation', $contrat->periodicite_facturation) == 'semestriel'   ? 'selected' : '' }}>Semestriel</option>
                                    <option value="annuel"       {{ old('periodicite_facturation', $contrat->periodicite_facturation) == 'annuel'       ? 'selected' : '' }}>Annuel</option>
                                </select>
                                @error('periodicite_facturation')
                                    <div class="field-error">
                                        <i class="bi bi-exclamation-circle-fill"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>

                            {{-- Agents requis --}}
                            <div class="col-md-6">
                                <label class="form-label" for="nombre_agents_requis">
                                    <i class="bi bi-people"></i>
                                    Nombre d'agents requis <span class="required-indicator">*</span>
                                </label>
                                <input type="number" name="nombre_agents_requis" id="nombre_agents_requis"
                                    class="form-control @error('nombre_agents_requis') is-invalid @enderror"
                                    value="{{ old('nombre_agents_requis', $contrat->nombre_agents_requis) }}"
                                    min="1" required>
                                @error('nombre_agents_requis')
                                    <div class="field-error">
                                        <i class="bi bi-exclamation-circle-fill"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>

                            {{-- Aperçu montants --}}
                            <div class="col-md-6">
                                <div class="info-box" id="montantPreview">
                                    <div class="info-title">
                                        <i class="bi bi-calculator"></i>
                                        Aperçu des montants
                                    </div>
                                    <div class="row g-1">
                                        <div class="col-6">
                                            <small style="color:var(--bs-secondary-color);">Mensuel TTC</small>
                                            <div style="font-weight:700;color:#198754;font-size:0.9rem;" id="previewMensuelTTC">—</div>
                                        </div>
                                        <div class="col-6">
                                            <small style="color:var(--bs-secondary-color);">Annuel HT</small>
                                            <div style="font-weight:700;color:#0d6efd;font-size:0.9rem;" id="previewAnnuelHT">—</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Détails --}}
                <div class="card premium-card animate-fade-in">
                    <div class="card-header">
                        <h5><i class="bi bi-list-task"></i> Détails de la prestation</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">

                            <div class="col-12">
                                <label class="form-label" for="description_prestation">
                                    <i class="bi bi-card-text"></i>
                                    Description de la prestation
                                </label>
                                <textarea name="description_prestation" id="description_prestation"
                                    class="form-control @error('description_prestation') is-invalid @enderror"
                                    rows="4"
                                    placeholder="Décrivez les prestations incluses dans ce contrat…">{{ old('description_prestation', $contrat->description_prestation) }}</textarea>
                                @error('description_prestation')
                                    <div class="field-error">
                                        <i class="bi bi-exclamation-circle-fill"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label" for="conditions_particulieres">
                                    <i class="bi bi-file-earmark-ruled"></i>
                                    Conditions particulières
                                </label>
                                <textarea name="conditions_particulieres" id="conditions_particulieres"
                                    class="form-control @error('conditions_particulieres') is-invalid @enderror"
                                    rows="3"
                                    placeholder="Clauses spécifiques, exceptions, modalités particulières…">{{ old('conditions_particulieres', $contrat->conditions_particulieres) }}</textarea>
                                @error('conditions_particulieres')
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
                 COLONNE LATÉRALE (droite)
            ═══════════════════════════════════════════════ --}}
            <div class="col-lg-4">

                {{-- Résumé du contrat --}}
                <div class="card premium-card animate-fade-in">
                    <div class="card-header">
                        <h5><i class="bi bi-file-earmark-text"></i> Résumé actuel</h5>
                    </div>
                    <div class="card-body">
                        <div class="contrat-summary-item">
                            <div class="summary-label">Numéro</div>
                            <div class="summary-value">{{ $contrat->numero_contrat }}</div>
                        </div>
                        <div class="contrat-summary-item">
                            <div class="summary-label">Client</div>
                            <div class="summary-value">{{ $contrat->client?->nom ?? '—' }}</div>
                        </div>
                        <div class="contrat-summary-item">
                            <div class="summary-label">Statut actuel</div>
                            <div class="summary-value mt-1">
                                <span class="contrat-badge badge-{{ $contrat->statut }}">
                                    {{ match($contrat->statut) {
                                        'brouillon' => '● Brouillon',
                                        'en_cours'  => '● En cours',
                                        'suspendu'  => '● Suspendu',
                                        'termine'   => '● Terminé',
                                        'resilie'   => '● Résilié',
                                        default     => $contrat->statut
                                    } }}
                                </span>
                            </div>
                        </div>
                        <div class="contrat-summary-item">
                            <div class="summary-label">Montant mensuel HT</div>
                            <div class="summary-value">{{ number_format($contrat->montant_mensuel_ht ?? 0, 0, ',', ' ') }} FCFA</div>
                        </div>
                        <div class="contrat-summary-item">
                            <div class="summary-label">Créé le</div>
                            <div class="summary-value">{{ $contrat->created_at?->format('d/m/Y à H:i') }}</div>
                        </div>
                        <div class="contrat-summary-item">
                            <div class="summary-label">Dernière mise à jour</div>
                            <div class="summary-value">{{ $contrat->updated_at?->format('d/m/Y à H:i') }}</div>
                        </div>
                    </div>
                </div>

                {{-- Statut --}}
                <div class="card premium-card animate-fade-in">
                    <div class="card-header">
                        <h5><i class="bi bi-toggle-on"></i> Statut du contrat</h5>
                    </div>
                    <div class="card-body">

                        <div class="mb-3">
                            <label class="form-label" for="statut">
                                <i class="bi bi-flag"></i>
                                Statut <span class="required-indicator">*</span>
                            </label>
                            <select name="statut" id="statut"
                                class="form-select @error('statut') is-invalid @enderror" required>
                                <option value="brouillon" {{ old('statut', $contrat->statut) == 'brouillon' ? 'selected' : '' }}>📝 Brouillon</option>
                                <option value="en_cours"  {{ old('statut', $contrat->statut) == 'en_cours'  ? 'selected' : '' }}>✅ En cours</option>
                                <option value="suspendu"  {{ old('statut', $contrat->statut) == 'suspendu'  ? 'selected' : '' }}>⏸ Suspendu</option>
                                <option value="termine"   {{ old('statut', $contrat->statut) == 'termine'   ? 'selected' : '' }}>🏁 Terminé</option>
                                <option value="resilie"   {{ old('statut', $contrat->statut) == 'resilie'   ? 'selected' : '' }}>🚫 Résilié</option>
                            </select>
                            @error('statut')
                                <div class="field-error">
                                    <i class="bi bi-exclamation-circle-fill"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        {{-- Zone résiliation --}}
                        <div id="resiliationZone" style="display: {{ old('statut', $contrat->statut) == 'resilie' ? 'block' : 'none' }};">
                            <div class="resiliation-zone">
                                <div class="zone-title">
                                    <i class="bi bi-x-octagon-fill"></i>
                                    Informations de résiliation
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="date_resiliation">
                                        <i class="bi bi-calendar-x"></i>
                                        Date de résiliation
                                    </label>
                                    <input type="date" name="date_resiliation" id="date_resiliation"
                                        class="form-control @error('date_resiliation') is-invalid @enderror"
                                        value="{{ old('date_resiliation', $contrat->date_resiliation?->format('Y-m-d')) }}">
                                    @error('date_resiliation')
                                        <div class="field-error">
                                            <i class="bi bi-exclamation-circle-fill"></i>
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-0">
                                    <label class="form-label" for="motif_resiliation">
                                        <i class="bi bi-chat-text"></i>
                                        Motif de résiliation
                                    </label>
                                    <textarea name="motif_resiliation" id="motif_resiliation"
                                        class="form-control @error('motif_resiliation') is-invalid @enderror"
                                        rows="3"
                                        placeholder="Expliquez le motif de la résiliation…">{{ old('motif_resiliation', $contrat->motif_resiliation) }}</textarea>
                                    @error('motif_resiliation')
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

                {{-- Signataires --}}
                <div class="card premium-card animate-fade-in">
                    <div class="card-header">
                        <h5><i class="bi bi-pen"></i> Signataires</h5>
                    </div>
                    <div class="card-body">

                        <div class="mb-3">
                            <label class="form-label" for="signataire_client_nom">
                                <i class="bi bi-person-check"></i>
                                Nom du signataire
                            </label>
                            <input type="text" name="signataire_client_nom" id="signataire_client_nom"
                                class="form-control @error('signataire_client_nom') is-invalid @enderror"
                                value="{{ old('signataire_client_nom', $contrat->signataire_client_nom) }}"
                                placeholder="Nom complet du signataire">
                            @error('signataire_client_nom')
                                <div class="field-error">
                                    <i class="bi bi-exclamation-circle-fill"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="signataire_client_fonction">
                                <i class="bi bi-briefcase"></i>
                                Fonction
                            </label>
                            <input type="text" name="signataire_client_fonction" id="signataire_client_fonction"
                                class="form-control @error('signataire_client_fonction') is-invalid @enderror"
                                value="{{ old('signataire_client_fonction', $contrat->signataire_client_fonction) }}"
                                placeholder="Ex : Directeur Général">
                            @error('signataire_client_fonction')
                                <div class="field-error">
                                    <i class="bi bi-exclamation-circle-fill"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <div class="mb-0">
                            <label class="form-label" for="date_signature">
                                <i class="bi bi-calendar-check"></i>
                                Date de signature
                            </label>
                            <input type="date" name="date_signature" id="date_signature"
                                class="form-control @error('date_signature') is-invalid @enderror"
                                value="{{ old('date_signature', $contrat->date_signature?->format('Y-m-d')) }}">
                            @error('date_signature')
                                <div class="field-error">
                                    <i class="bi bi-exclamation-circle-fill"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- Boutons actions --}}
                <div class="card premium-card animate-fade-in">
                    <div class="card-body d-flex flex-column gap-3">
                        <button type="submit" class="btn btn-save">
                            <i class="bi bi-check-circle-fill"></i>
                            Enregistrer les modifications
                        </button>
                        <a href="{{ route('admin.entreprise.contrats.show', $contrat->id) }}" class="btn btn-cancel">
                            <i class="bi bi-arrow-left-circle"></i>
                            Retour au contrat
                        </a>
                        <a href="{{ route('admin.entreprise.contrats.index') }}"
                            class="btn btn-cancel" style="font-size:0.875rem; padding:0.6rem 1rem;">
                            <i class="bi bi-list-ul"></i>
                            Liste des contrats
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </form>

</div>
@endsection

@push('scripts')
<script>
    // ── Afficher/masquer la zone résiliation ──────────────────────────────────
    document.getElementById('statut').addEventListener('change', function () {
        var zone = document.getElementById('resiliationZone');
        zone.style.display = this.value === 'resilie' ? 'block' : 'none';
    });

    // ── Calcul aperçu montants ────────────────────────────────────────────────
    function updateMontantPreview() {
        var ht  = parseFloat(document.getElementById('montant_mensuel_ht').value) || 0;
        var tva = parseFloat(document.getElementById('tva').value) || 0;

        var mensuelTTC = ht * (1 + tva / 100);
        var annuelHT   = ht * 12;

        document.getElementById('previewMensuelTTC').textContent =
            ht > 0 ? formatFCFA(mensuelTTC) : '—';
        document.getElementById('previewAnnuelHT').textContent =
            ht > 0 ? formatFCFA(annuelHT) : '—';
    }

    function formatFCFA(val) {
        return new Intl.NumberFormat('fr-FR').format(Math.round(val)) + ' FCFA';
    }

    // ── Validation date fin > date début ─────────────────────────────────────
    function checkDateRange() {
        var debut = document.getElementById('date_debut').value;
        var fin   = document.getElementById('date_fin').value;
        var errEl = document.getElementById('dateRangeError');
        var finEl = document.getElementById('date_fin');

        if (debut && fin && fin <= debut) {
            errEl.style.display = 'flex';
            finEl.classList.add('is-invalid');
        } else {
            errEl.style.display = 'none';
            finEl.classList.remove('is-invalid');
        }
    }

    document.getElementById('date_debut').addEventListener('change', checkDateRange);
    document.getElementById('date_fin').addEventListener('change', checkDateRange);

    // ── Init au chargement ────────────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', function () {
        updateMontantPreview();
    });
</script>
@endpush