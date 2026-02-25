@extends('layouts.app')

@section('title', 'Modifier Contrat - Super Admin')

@push('styles')
<style>
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

    .nav-tabs-custom {
        border-bottom: 2px solid #e9ecef;
        margin-bottom: 2rem;
    }

    .nav-tabs-custom .nav-link {
        border: none;
        color: #6c757d;
        font-weight: 500;
        padding: 0.75rem 1.5rem;
        position: relative;
    }

    .nav-tabs-custom .nav-link::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 2px;
        background: #198754;
        transition: width 0.3s ease;
    }

    .nav-tabs-custom .nav-link:hover {
        color: #198754;
    }

    .nav-tabs-custom .nav-link.active {
        color: #198754;
        background: transparent;
    }

    .nav-tabs-custom .nav-link.active::after {
        width: 100%;
    }

    .section-title {
        font-size: 1rem;
        font-weight: 600;
        color: #495057;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #e9ecef;
    }

    .btn-update {
        padding: 0.7rem 2rem;
        border-radius: 10px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-update:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(25, 135, 84, 0.3);
    }
</style>
@endpush

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Modifier le Contrat</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.contrats.index') }}">Contrats</a></li>
                    <li class="breadcrumb-item active">Modifier</li>
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

        <form action="{{ route('admin.superadmin.contrats.update', $contrat->id) }}" method="POST" class="form-wizard">
            @csrf
            @method('PUT')

            <div class="card form-card">
                <div class="card-body p-4">

                    <!-- Navigation par onglets -->
                    <ul class="nav nav-tabs-custom" id="contratTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">
                                <i class="bi bi-building me-2"></i>Général
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="periode-tab" data-bs-toggle="tab" data-bs-target="#periode" type="button" role="tab">
                                <i class="bi bi-calendar me-2"></i>Période
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="financier-tab" data-bs-toggle="tab" data-bs-target="#financier" type="button" role="tab">
                                <i class="bi bi-currency-exchange me-2"></i>Financier
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="signataires-tab" data-bs-toggle="tab" data-bs-target="#signataires" type="button" role="tab">
                                <i class="bi bi-pen me-2"></i>Signataires
                            </button>
                        </li>
                    </ul>

                    <!-- Contenu des onglets -->
                    <div class="tab-content" id="contratTabsContent">

                        <!-- Onglet 1: Général -->
                        <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                            <div class="row g-4">
                                <div class="col-12">
                                    <h6 class="section-title"><i class="bi bi-info-circle me-2 text-success"></i>Informations générales</h6>
                                </div>

                                <!-- Entreprise -->
                                <div class="col-md-6">
                                    <label for="entreprise_id" class="form-label">
                                        Entreprise de sécurité <span class="required-indicator">*</span>
                                    </label>
                                    <select class="form-select @error('entreprise_id') is-invalid @enderror"
                                        id="entreprise_id" name="entreprise_id" required
                                        onchange="loadClients(this.value)">
                                        <option value="">Sélectionner une entreprise...</option>
                                        @foreach($entreprises as $entreprise)
                                        <option value="{{ $entreprise->id }}" {{ old('entreprise_id', $contrat->entreprise_id) == $entreprise->id ? 'selected' : '' }}>
                                            {{ $entreprise->nom_entreprise }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('entreprise_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Client -->
                                <div class="col-md-6">
                                    <label for="client_id" class="form-label">
                                        Client <span class="required-indicator">*</span>
                                    </label>
                                    <select class="form-select @error('client_id') is-invalid @enderror"
                                        id="client_id" name="client_id" required>
                                        <option value="">Sélectionner un client...</option>
                                        @foreach($clients as $client)
                                        <option value="{{ $client->id }}" {{ old('client_id', $contrat->client_id) == $client->id ? 'selected' : '' }}>
                                            {{ $client->nom }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('client_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Numéro de contrat -->
                                <div class="col-md-6">
                                    <label for="numero_contrat" class="form-label">
                                        N° Contrat <span class="required-indicator">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('numero_contrat') is-invalid @enderror"
                                        id="numero_contrat" name="numero_contrat"
                                        value="{{ old('numero_contrat', $contrat->numero_contrat) }}" required>
                                    @error('numero_contrat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Intitulé -->
                                <div class="col-md-6">
                                    <label for="intitule" class="form-label">
                                        Intitulé du contrat <span class="required-indicator">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('intitule') is-invalid @enderror"
                                        id="intitule" name="intitule"
                                        value="{{ old('intitule', $contrat->intitule) }}" required>
                                    @error('intitule')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Statut -->
                                <div class="col-md-6">
                                    <label for="statut" class="form-label">
                                        Statut <span class="required-indicator">*</span>
                                    </label>
                                    <select class="form-select @error('statut') is-invalid @enderror"
                                        id="statut" name="statut" required>
                                        <option value="brouillon" {{ old('statut', $contrat->statut) == 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                                        <option value="en_cours" {{ old('statut', $contrat->statut) == 'en_cours' ? 'selected' : '' }}>En cours</option>
                                        <option value="suspendu" {{ old('statut', $contrat->statut) == 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                                        <option value="termine" {{ old('statut', $contrat->statut) == 'termine' ? 'selected' : '' }}>Terminé</option>
                                        <option value="resilie" {{ old('statut', $contrat->statut) == 'resilie' ? 'selected' : '' }}>Résilié</option>
                                    </select>
                                    @error('statut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div class="col-12">
                                    <label for="description_prestation" class="form-label">Description de la prestation</label>
                                    <textarea class="form-control" id="description_prestation" name="description_prestation"
                                        rows="4">{{ old('description_prestation', $contrat->description_prestation) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Onglet 2: Période -->
                        <div class="tab-pane fade" id="periode" role="tabpanel" aria-labelledby="periode-tab">
                            <div class="row g-4">
                                <div class="col-12">
                                    <h6 class="section-title"><i class="bi bi-calendar-event me-2 text-success"></i>Période du contrat</h6>
                                </div>

                                <!-- Date de début -->
                                <div class="col-md-6">
                                    <label for="date_debut" class="form-label">
                                        Date de début <span class="required-indicator">*</span>
                                    </label>
                                    <input type="date" class="form-control @error('date_debut') is-invalid @enderror"
                                        id="date_debut" name="date_debut"
                                        value="{{ old('date_debut', $contrat->date_debut?->format('Y-m-d')) }}" required>
                                    @error('date_debut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Date de fin -->
                                <div class="col-md-6">
                                    <label for="date_fin" class="form-label">
                                        Date de fin <span class="required-indicator">*</span>
                                    </label>
                                    <input type="date" class="form-control @error('date_fin') is-invalid @enderror"
                                        id="date_fin" name="date_fin"
                                        value="{{ old('date_fin', $contrat->date_fin?->format('Y-m-d')) }}" required>
                                    @error('date_fin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Renouvelable -->
                                <div class="col-md-6">
                                    <div class="form-check form-switch mt-4">
                                        <input class="form-check-input" type="checkbox" id="est_renouvelable"
                                            name="est_renouvelable" value="1" {{ old('est_renouvelable', $contrat->est_renouvelable) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="est_renouvelable">
                                            contrat renouvelable
                                        </label>
                                    </div>
                                </div>

                                <!-- Durée du préavis -->
                                <div class="col-md-6">
                                    <label for="duree_preavis" class="form-label">Durée du préavis (jours)</label>
                                    <input type="number" class="form-control" id="duree_preavis"
                                        name="duree_preavis" value="{{ old('duree_preavis', $contrat->duree_preavis ?? 30) }}" min="0">
                                </div>

                                <!-- Nombre d'agents requis -->
                                <div class="col-md-6">
                                    <label for="nombre_agents_requis" class="form-label">
                                        Nombre d'agents requis <span class="required-indicator">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-people"></i></span>
                                        <input type="number" class="form-control @error('nombre_agents_requis') is-invalid @enderror"
                                            id="nombre_agents_requis" name="nombre_agents_requis"
                                            value="{{ old('nombre_agents_requis', $contrat->nombre_agents_requis) }}" min="1" required>
                                    </div>
                                    @error('nombre_agents_requis')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Onglet 3: Financier -->
                        <div class="tab-pane fade" id="financier" role="tabpanel" aria-labelledby="financier-tab">
                            <div class="row g-4">
                                <div class="col-12">
                                    <h6 class="section-title"><i class="bi bi-cash-stack me-2 text-success"></i>Aspects financiers</h6>
                                </div>

                                <!-- Montant mensuel HT -->
                                <div class="col-md-6">
                                    <label for="montant_mensuel_ht" class="form-label">
                                        Montant mensuel HT <span class="required-indicator">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-currency-exchange"></i></span>
                                        <input type="number" class="form-control @error('montant_mensuel_ht') is-invalid @enderror"
                                            id="montant_mensuel_ht" name="montant_mensuel_ht"
                                            value="{{ old('montant_mensuel_ht', $contrat->montant_mensuel_ht) }}" min="0" step="100" required>
                                        <span class="input-group-text">FCFA</span>
                                    </div>
                                    @error('montant_mensuel_ht')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- TVA -->
                                <div class="col-md-6">
                                    <label for="tva" class="form-label">TVA (%)</label>
                                    <input type="number" class="form-control" id="tva"
                                        name="tva" value="{{ old('tva', $contrat->tva ?? 18) }}" min="0" max="100" step="0.5">
                                </div>

                                <!-- Périodicité -->
                                <div class="col-md-6">
                                    <label for="periodicite_facturation" class="form-label">
                                        Périodicité de facturation <span class="required-indicator">*</span>
                                    </label>
                                    <select class="form-select @error('periodicite_facturation') is-invalid @enderror"
                                        id="periodicite_facturation" name="periodicite_facturation" required>
                                        <option value="mensuel" {{ old('periodicite_facturation', $contrat->periodicite_facturation ?? 'mensuel') == 'mensuel' ? 'selected' : '' }}>Mensuel</option>
                                        <option value="trimestriel" {{ old('periodicite_facturation', $contrat->periodicite_facturation) == 'trimestriel' ? 'selected' : '' }}>Trimestriel</option>
                                        <option value="semestriel" {{ old('periodicite_facturation', $contrat->periodicite_facturation) == 'semestriel' ? 'selected' : '' }}>Semestriel</option>
                                        <option value="annuel" {{ old('periodicite_facturation', $contrat->periodicite_facturation) == 'annuel' ? 'selected' : '' }}>Annuel</option>
                                    </select>
                                    @error('periodicite_facturation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Conditions particulières -->
                                <div class="col-12">
                                    <label for="conditions_particulieres" class="form-label">Conditions particulières</label>
                                    <textarea class="form-control" id="conditions_particulieres" name="conditions_particulieres"
                                        rows="4">{{ old('conditions_particulieres', $contrat->conditions_particulieres) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Onglet 4: Signataires -->
                        <div class="tab-pane fade" id="signataires" role="tabpanel" aria-labelledby="signataires-tab">
                            <div class="row g-4">
                                <div class="col-12">
                                    <h6 class="section-title"><i class="bi bi-pen-fill me-2 text-success"></i>Signataires du contrat</h6>
                                </div>

                                <!-- Signataire client - Nom -->
                                <div class="col-md-6">
                                    <label for="signataire_client_nom" class="form-label">Nom du signataire client</label>
                                    <input type="text" class="form-control" id="signataire_client_nom"
                                        name="signataire_client_nom" value="{{ old('signataire_client_nom', $contrat->signataire_client_nom) }}">
                                </div>

                                <!-- Signataire client - Fonction -->
                                <div class="col-md-6">
                                    <label for="signataire_client_fonction" class="form-label">Fonction du signataire client</label>
                                    <input type="text" class="form-control" id="signataire_client_fonction"
                                        name="signataire_client_fonction" value="{{ old('signataire_client_fonction', $contrat->signataire_client_fonction) }}">
                                </div>

                                <!-- Date de signature -->
                                <div class="col-md-6">
                                    <label for="date_signature" class="form-label">Date de signature</label>
                                    <input type="date" class="form-control" id="date_signature"
                                        name="date_signature" value="{{ old('date_signature', $contrat->date_signature?->format('Y-m-d')) }}">
                                </div>

                                <!-- Motif de résiliation (si résilié) -->
                                @if($contrat->statut === 'resilie')
                                <div class="col-md-6">
                                    <label for="motif_resiliation" class="form-label">Motif de résiliation</label>
                                    <textarea class="form-control" id="motif_resiliation" name="motif_resiliation" rows="2">{{ old('motif_resiliation', $contrat->motif_resiliation) }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="date_resiliation" class="form-label">Date de résiliation</label>
                                    <input type="date" class="form-control" id="date_resiliation"
                                        name="date_resiliation" value="{{ old('date_resiliation', $contrat->date_resiliation?->format('Y-m-d')) }}">
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Boutons de navigation -->
                    <div class="wizard-buttons">
                        <div>
                            <a href="{{ route('admin.superadmin.contrats.show', $contrat->id) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Annuler
                            </a>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-success btn-update">
                                <i class="bi bi-check-circle me-1"></i> Enregistrer les modifications
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
    // Charger les clients lors du changement d'entreprise
    function loadClients(entrepriseId) {
        const clientSelect = document.getElementById('client_id');

        // Effacer les options existantes
        clientSelect.innerHTML = '<option value="">Sélectionner un client...</option>';

        if (!entrepriseId) {
            return;
        }

        // Requête AJAX pour récupérer les clients
        fetch(`/admin/superadmin/contrats/clients?entreprise_id=${entrepriseId}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(client => {
                    const option = document.createElement('option');
                    option.value = client.id;
                    option.textContent = client.nom;
                    clientSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Erreur lors du chargement des clients:', error);
            });
    }
</script>
@endpush
@endsection