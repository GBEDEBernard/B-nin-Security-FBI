@extends('layouts.app')

@section('title', 'Nouveau Contrat - Entreprise')

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
        transition: all 0.2s;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #198754;
        box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.15);
    }

    .is-invalid {
        border-color: #dc3545 !important;
    }

    .invalid-feedback {
        color: #dc3545;
        font-size: 0.8rem;
    }

    .btn-create {
        padding: 0.75rem 2rem;
        border-radius: 8px;
        font-weight: 600;
    }

    .info-box {
        background: rgba(25, 135, 84, 0.1);
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .info-box i {
        color: #198754;
    }
</style>
@endpush

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0"><i class="bi bi-file-earmark-plus me-2"></i>Nouveau Contrat</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.entreprise.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.entreprise.contrats.index') }}">Contrats</a></li>
                    <li class="breadcrumb-item active">Nouveau</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <form action="{{ route('admin.entreprise.contrats.store') }}" method="POST">
            @csrf

            <div class="row">
                <!-- Informations générales -->
                <div class="col-lg-8">
                    <div class="card form-card mb-4">
                        <div class="card-body">
                            <div class="section-title">
                                <i class="bi bi-info-circle me-2"></i>Informations générales
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="client_id" class="form-label">Client <span class="text-danger">*</span></label>
                                    <select name="client_id" id="client_id" class="form-select @error('client_id') is-invalid @enderror" required>
                                        <option value="">Sélectionner un client</option>
                                        @foreach($clients as $client)
                                        <option value="{{ $client->id }}" {{ old('client_id', $clientId) == $client->id ? 'selected' : '' }}>
                                            {{ $client->nom }}
                                            @if($client->type_client !== 'particulier')
                                            - {{ $client->raison_sociale }}
                                            @endif
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('client_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if($clients->isEmpty())
                                    <small class="text-muted">Aucun client disponible. Créez d'abord un client.</small>
                                    @endif
                                </div>

                                <div class="col-md-6">
                                    <label for="numero_contrat" class="form-label">Numéro de contrat</label>
                                    <input type="text" name="numero_contrat" id="numero_contrat" class="form-control @error('numero_contrat') is-invalid @enderror" value="{{ old('numero_contrat') }}" placeholder="Auto-généré si vide">
                                    <small class="text-muted">Laisser vide pour auto-génération</small>
                                </div>

                                <div class="col-12">
                                    <label for="intitule" class="form-label">Intitulé du contrat <span class="text-danger">*</span></label>
                                    <input type="text" name="intitule" id="intitule" class="form-control @error('intitule') is-invalid @enderror" value="{{ old('intitule') }}" placeholder="Ex: Prestation de sécurité pour [nom du client]" required>
                                    @error('intitule')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="date_debut" class="form-label">Date de début <span class="text-danger">*</span></label>
                                    <input type="date" name="date_debut" id="date_debut" class="form-control @error('date_debut') is-invalid @enderror" value="{{ old('date_debut') }}" required>
                                    @error('date_debut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="date_fin" class="form-label">Date de fin <span class="text-danger">*</span></label>
                                    <input type="date" name="date_fin" id="date_fin" class="form-control @error('date_fin') is-invalid @enderror" value="{{ old('date_fin') }}" required>
                                    @error('date_fin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="est_renouvelable" class="form-label">Renouvelable</label>
                                    <div class="form-check mt-2">
                                        <input type="checkbox" name="est_renouvelable" id="est_renouvelable" class="form-check-input" value="1" {{ old('est_renouvelable') ? 'checked' : '' }}>
                                        <label for="est_renouvelable" class="form-check-label">Contrat renouvelable</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="duree_preavis" class="form-label">Durée de préavis (jours)</label>
                                    <input type="number" name="duree_preavis" id="duree_preavis" class="form-control @error('duree_preavis') is-invalid @enderror" value="{{ old('duree_preavis', 30) }}" min="0">
                                    @error('duree_preavis')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Aspect financier -->
                    <div class="card form-card mb-4">
                        <div class="card-body">
                            <div class="section-title">
                                <i class="bi bi-currency-exchange me-2"></i>Aspect financier
                            </div>

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="montant_mensuel_ht" class="form-label">Montant mensuel HT <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" name="montant_mensuel_ht" id="montant_mensuel_ht" class="form-control @error('montant_mensuel_ht') is-invalid @enderror" value="{{ old('montant_mensuel_ht') }}" min="0" step="100" required>
                                        <span class="input-group-text">FCFA</span>
                                    </div>
                                    @error('montant_mensuel_ht')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="tva" class="form-label">TVA (%)</label>
                                    <input type="number" name="tva" id="tva" class="form-control @error('tva') is-invalid @enderror" value="{{ old('tva', 18) }}" min="0" max="100">
                                    @error('tva')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="periodicite_facturation" class="form-label">Périodicité <span class="text-danger">*</span></label>
                                    <select name="periodicite_facturation" id="periodicite_facturation" class="form-select @error('periodicite_facturation') is-invalid @enderror" required>
                                        <option value="mensuel" {{ old('periodicite_facturation') == 'mensuel' ? 'selected' : '' }}>Mensuel</option>
                                        <option value="trimestriel" {{ old('periodicite_facturation') == 'trimestriel' ? 'selected' : '' }}>Trimestriel</option>
                                        <option value="semestriel" {{ old('periodicite_facturation') == 'semestriel' ? 'selected' : '' }}>Semestriel</option>
                                        <option value="annuel" {{ old('periodicite_facturation') == 'annuel' ? 'selected' : '' }}>Annuel</option>
                                    </select>
                                    @error('periodicite_facturation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="nombre_agents_requis" class="form-label">Nombre d'agents requis <span class="text-danger">*</span></label>
                                    <input type="number" name="nombre_agents_requis" id="nombre_agents_requis" class="form-control @error('nombre_agents_requis') is-invalid @enderror" value="{{ old('nombre_agents_requis', 1) }}" min="1" required>
                                    @error('nombre_agents_requis')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Détails prestation -->
                    <div class="card form-card mb-4">
                        <div class="card-body">
                            <div class="section-title">
                                <i class="bi bi-list-task me-2"></i>Détails de la prestation
                            </div>

                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="description_prestation" class="form-label">Description de la prestation</label>
                                    <textarea name="description_prestation" id="description_prestation" class="form-control @error('description_prestation') is-invalid @enderror" rows="4">{{ old('description_prestation') }}</textarea>
                                    @error('description_prestation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="conditions_particulieres" class="form-label">Conditions particulières</label>
                                    <textarea name="conditions_particulieres" id="conditions_particulieres" class="form-control @error('conditions_particulieres') is-invalid @enderror" rows="3">{{ old('conditions_particulieres') }}</textarea>
                                    @error('conditions_particulieres')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Signataires -->
                    <div class="card form-card mb-4">
                        <div class="card-body">
                            <div class="section-title">
                                <i class="bi bi-pen me-2"></i>Signataires
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="signataire_client_nom" class="form-label">Nom du signataire client</label>
                                    <input type="text" name="signataire_client_nom" id="signataire_client_nom" class="form-control @error('signataire_client_nom') is-invalid @enderror" value="{{ old('signataire_client_nom') }}">
                                    @error('signaire_client_nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="signataire_client_fonction" class="form-label">Fonction du signataire</label>
                                    <input type="text" name="signataire_client_fonction" id="signataire_client_fonction" class="form-control @error('signataire_client_fonction') is-invalid @enderror" value="{{ old('signataire_client_fonction') }}">
                                    @error('signataire_client_fonction')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="date_signature" class="form-label">Date de signature</label>
                                    <input type="date" name="date_signature" id="date_signature" class="form-control @error('date_signature') is-invalid @enderror" value="{{ old('date_signature') }}">
                                    @error('date_signature')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Colonne latérale -->
                <div class="col-lg-4">
                    <!-- Statut -->
                    <div class="card form-card mb-4">
                        <div class="card-body">
                            <div class="section-title">
                                <i class="bi bi-toggle-on me-2"></i>Statut
                            </div>

                            <div class="mb-3">
                                <label for="statut" class="form-label">Statut initial <span class="text-danger">*</span></label>
                                <select name="statut" id="statut" class="form-select @error('statut') is-invalid @enderror" required>
                                    <option value="brouillon" {{ old('statut', 'brouillon') == 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                                    <option value="en_cours" {{ old('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                                </select>
                                @error('statut')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="card form-card mb-4">
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success btn-create">
                                    <i class="bi bi-check-circle me-2"></i>Créer le contrat
                                </button>
                                <a href="{{ route('admin.entreprise.contrats.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>Retour à la liste
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Info -->
                    <div class="info-box">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Information :</strong>
                        <p class="mb-0 small">Un client ne peut avoir qu'un seul contrat actif à la fois.</p>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection