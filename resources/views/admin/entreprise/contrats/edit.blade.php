@extends('layouts.app')

@section('title', 'Modifier le Contrat - Entreprise')

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

    .btn-update {
        padding: 0.75rem 2rem;
        border-radius: 8px;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0"><i class="bi bi-pencil me-2"></i>Modifier le Contrat</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.entreprise.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.entreprise.contrats.index') }}">Contrats</a></li>
                    <li class="breadcrumb-item active">Modifier</li>
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

        <form action="{{ route('admin.entreprise.contrats.update', $contrat->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-lg-8">
                    <!-- Informations générales -->
                    <div class="card form-card mb-4">
                        <div class="card-body">
                            <div class="section-title">
                                <i class="bi bi-info-circle me-2"></i>Informations générales
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="client_id" class="form-label">Client <span class="text-danger">*</span></label>
                                    <select name="client_id" id="client_id" class="form-select" required>
                                        <option value="">Sélectionner un client</option>
                                        @foreach($clients as $client)
                                        <option value="{{ $client->id }}" {{ old('client_id', $contrat->client_id) == $client->id ? 'selected' : '' }}>
                                            {{ $client->nom }}
                                            @if($client->type_client !== 'particulier')
                                            - {{ $client->raison_sociale }}
                                            @endif
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="numero_contrat" class="form-label">Numéro de contrat</label>
                                    <input type="text" name="numero_contrat" id="numero_contrat" class="form-control" value="{{ old('numero_contrat', $contrat->numero_contrat) }}">
                                </div>

                                <div class="col-12">
                                    <label for="intitule" class="form-label">Intitulé du contrat <span class="text-danger">*</span></label>
                                    <input type="text" name="intitule" id="intitule" class="form-control" value="{{ old('intitule', $contrat->intitule) }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="date_debut" class="form-label">Date de début <span class="text-danger">*</span></label>
                                    <input type="date" name="date_debut" id="date_debut" class="form-control" value="{{ old('date_debut', $contrat->date_debut?->format('Y-m-d')) }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="date_fin" class="form-label">Date de fin <span class="text-danger">*</span></label>
                                    <input type="date" name="date_fin" id="date_fin" class="form-control" value="{{ old('date_fin', $contrat->date_fin?->format('Y-m-d')) }}" required>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-check mt-4">
                                        <input type="checkbox" name="est_renouvelable" id="est_renouvelable" class="form-check-input" value="1" {{ old('est_renouvelable', $contrat->est_renouvelable) ? 'checked' : '' }}>
                                        <label for="est_renouvelable" class="form-check-label">Contrat renouvelable</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="duree_preavis" class="form-label">Durée de préavis (jours)</label>
                                    <input type="number" name="duree_preavis" id="duree_preavis" class="form-control" value="{{ old('duree_preavis', $contrat->duree_preavis ?? 30) }}" min="0">
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
                                        <input type="number" name="montant_mensuel_ht" id="montant_mensuel_ht" class="form-control" value="{{ old('montant_mensuel_ht', $contrat->montant_mensuel_ht) }}" min="0" step="100" required>
                                        <span class="input-group-text">FCFA</span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label for="tva" class="form-label">TVA (%)</label>
                                    <input type="number" name="tva" id="tva" class="form-control" value="{{ old('tva', $contrat->tva ?? 18) }}" min="0" max="100">
                                </div>

                                <div class="col-md-4">
                                    <label for="periodicite_facturation" class="form-label">Périodicité <span class="text-danger">*</span></label>
                                    <select name="periodicite_facturation" id="periodicite_facturation" class="form-select" required>
                                        <option value="mensuel" {{ old('periodicite_facturation', $contrat->periodicite_facturation) == 'mensuel' ? 'selected' : '' }}>Mensuel</option>
                                        <option value="trimestriel" {{ old('periodicite_facturation', $contrat->periodicite_facturation) == 'trimestriel' ? 'selected' : '' }}>Trimestriel</option>
                                        <option value="semestriel" {{ old('periodicite_facturation', $contrat->periodicite_facturation) == 'semestriel' ? 'selected' : '' }}>Semestriel</option>
                                        <option value="annuel" {{ old('periodicite_facturation', $contrat->periodicite_facturation) == 'annuel' ? 'selected' : '' }}>Annuel</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="nombre_agents_requis" class="form-label">Nombre d'agents requis <span class="text-danger">*</span></label>
                                    <input type="number" name="nombre_agents_requis" id="nombre_agents_requis" class="form-control" value="{{ old('nombre_agents_requis', $contrat->nombre_agents_requis) }}" min="1" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Détails -->
                    <div class="card form-card mb-4">
                        <div class="card-body">
                            <div class="section-title">
                                <i class="bi bi-list-task me-2"></i>Détails
                            </div>

                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="description_prestation" class="form-label">Description de la prestation</label>
                                    <textarea name="description_prestation" id="description_prestation" class="form-control" rows="4">{{ old('description_prestation', $contrat->description_prestation) }}</textarea>
                                </div>

                                <div class="col-12">
                                    <label for="conditions_particulieres" class="form-label">Conditions particulières</label>
                                    <textarea name="conditions_particulieres" id="conditions_particulieres" class="form-control" rows="3">{{ old('conditions_particulieres', $contrat->conditions_particulieres) }}</textarea>
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
                                <label for="statut" class="form-label">Statut <span class="text-danger">*</span></label>
                                <select name="statut" id="statut" class="form-select" required>
                                    <option value="brouillon" {{ old('statut', $contrat->statut) == 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                                    <option value="en_cours" {{ old('statut', $contrat->statut) == 'en_cours' ? 'selected' : '' }}>En cours</option>
                                    <option value="suspendu" {{ old('statut', $contrat->statut) == 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                                    <option value="termine" {{ old('statut', $contrat->statut) == 'termine' ? 'selected' : '' }}>Terminé</option>
                                    <option value="resilie" {{ old('statut', $contrat->statut) == 'resilie' ? 'selected' : '' }}>Résilié</option>
                                </select>
                            </div>

                            <div id="resiliationFields" style="display: {{ old('statut', $contrat->statut) == 'resilie' ? 'block' : 'none' }}">
                                <div class="mb-3">
                                    <label for="date_resiliation" class="form-label">Date de résiliation</label>
                                    <input type="date" name="date_resiliation" id="date_resiliation" class="form-control" value="{{ old('date_resiliation', $contrat->date_resiliation?->format('Y-m-d')) }}">
                                </div>
                                <div class="mb-3">
                                    <label for="motif_resiliation" class="form-label">Motif</label>
                                    <textarea name="motif_resiliation" id="motif_resiliation" class="form-control" rows="3">{{ old('motif_resiliation', $contrat->motif_resiliation) }}</textarea>
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

                            <div class="mb-3">
                                <label for="signataire_client_nom" class="form-label">Nom du signataire</label>
                                <input type="text" name="signataire_client_nom" id="signataire_client_nom" class="form-control" value="{{ old('signataire_client_nom', $contrat->signataire_client_nom) }}">
                            </div>

                            <div class="mb-3">
                                <label for="signataire_client_fonction" class="form-label">Fonction</label>
                                <input type="text" name="signataire_client_fonction" id="signataire_client_fonction" class="form-control" value="{{ old('signataire_client_fonction', $contrat->signataire_client_fonction) }}">
                            </div>

                            <div class="mb-3">
                                <label for="date_signature" class="form-label">Date de signature</label>
                                <input type="date" name="date_signature" id="date_signature" class="form-control" value="{{ old('date_signature', $contrat->date_signature?->format('Y-m-d')) }}">
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="card form-card mb-4">
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success btn-update">
                                    <i class="bi bi-check-circle me-2"></i>Mettre à jour
                                </button>
                                <a href="{{ route('admin.entreprise.contrats.show', $contrat->id) }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>Retour
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('statut').addEventListener('change', function() {
        const fields = document.getElementById('resiliationFields');
        if (this.value === 'resilie') {
            fields.style.display = 'block';
        } else {
            fields.style.display = 'none';
        }
    });
</script>
@endpush
@endsection