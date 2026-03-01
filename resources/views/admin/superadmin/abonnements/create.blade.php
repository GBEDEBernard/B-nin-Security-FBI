@extends('layouts.app')

@section('title', 'Créer un Abonnement - Super Admin')

@push('styles')
<style>
    .form-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 0 40px rgba(0, 0, 0, 0.08);
    }

    .form-card .card-header {
        background: linear-gradient(135deg, #198754 0%, #20c997 100%);
        color: white;
        border-radius: 16px 16px 0 0;
        padding: 1.5rem;
    }

    .form-card .card-body {
        padding: 2rem;
    }

    .form-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
    }

    .form-control,
    .form-select {
        border-radius: 10px;
        padding: 0.75rem 1rem;
        border: 2px solid #e9ecef;
        transition: all 0.3s;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #198754;
        box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.15);
    }

    .input-group-text {
        border-radius: 10px;
        background: #f8f9fa;
        border: 2px solid #e9ecef;
        border-left: none;
    }

    .form-control-with-icon {
        border-right: none;
        border-radius: 10px 0 0 10px !important;
    }

    .btn-save {
        padding: 0.75rem 2rem;
        border-radius: 10px;
        font-weight: 600;
    }

    .plan-preview {
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        transition: all 0.3s;
    }

    .plan-preview:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .plan-basic {
        background: linear-gradient(135deg, #e7f1ff 0%, #d0e3ff 100%);
        border-left: 4px solid #0d6efd;
    }

    .plan-premium {
        background: linear-gradient(135deg, #f3e8ff 0%, #e0c3fc 100%);
        border-left: 4px solid #6f42c1;
    }

    .plan-enterprise {
        background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
        border-left: 4px solid #198754;
    }

    .section-title {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #6c757d;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e9ecef;
    }

    .is-invalid {
        border-color: #dc3545 !important;
    }

    .invalid-feedback {
        font-size: 0.8rem;
    }
</style>
@endpush

@section('content')
<div class="content-wrapper p-4">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card form-card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0">
                                    <i class="bi bi-plus-circle me-2"></i>
                                    Créer un Nouveau Plan
                                </h4>
                                <p class="mb-0 opacity-75">Définissez les caractéristiques de votre abonnement</p>
                            </div>
                            <a href="{{ route('admin.superadmin.abonnements.index') }}" class="btn btn-light btn-sm">
                                <i class="bi bi-arrow-left me-1"></i> Retour
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.superadmin.abonnements.store') }}" method="POST">
                            @csrf

                            <!-- Type de formule -->
                            <div class="mb-4">
                                <label class="section-title">Choisir le type de plan</label>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="plan-preview plan-basic w-100 cursor-pointer" onclick="selectPlan('basic')">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="formule" id="formule_basic" value="basic" {{ old('formule') == 'basic' ? 'checked' : '' }}>
                                                <div class="text-center">
                                                    <h5 class="text-primary mb-1">BASIC</h5>
                                                    <div class="h4 text-primary mb-0">100 000 F<span class="fs-6">/mois</span></div>
                                                    <small class="text-muted">20-40 employés</small>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="plan-preview plan-premium w-100 cursor-pointer" onclick="selectPlan('premium')">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="formule" id="formule_premium" value="premium" {{ old('formule') == 'premium' ? 'checked' : '' }}>
                                                <div class="text-center">
                                                    <h5 class="text-purple mb-1" style="color: #6f42c1;">PREMIUM</h5>
                                                    <div class="h4 mb-0" style="color: #6f42c1;">150 000 F<span class="fs-6 text-muted">/mois</span></div>
                                                    <small class="text-muted">41-100 employés</small>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="plan-preview plan-enterprise w-100 cursor-pointer" onclick="selectPlan('enterprise')">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="formule" id="formule_enterprise" value="enterprise" {{ old('formule') == 'enterprise' ? 'checked' : '' }}>
                                                <div class="text-center">
                                                    <h5 class="text-success mb-1">ENTERPRISE</h5>
                                                    <div class="h4 text-success mb-0">200 000 F<span class="fs-6">/mois</span></div>
                                                    <small class="text-muted">101-300 employés</small>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                @error('formule')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row g-4">
                                <!-- Colonne gauche -->
                                <div class="col-md-6">
                                    <div class="section-title">Informations du Plan</div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror"
                                            id="description" name="description" rows="3"
                                            placeholder="Décrivez les avantages de ce plan...">{{ old('description') }}</textarea>
                                        @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="type_formule" class="form-label">Type de formule</label>
                                        <select class="form-select @error('type_formule') is-invalid @enderror"
                                            id="type_formule" name="type_formule">
                                            <option value="">Sélectionner...</option>
                                            <option value="basic" {{ old('type_formule') == 'basic' ? 'selected' : '' }}>Basic</option>
                                            <option value="premium" {{ old('type_formule') == 'premium' ? 'selected' : '' }}>Premium</option>
                                            <option value="enterprise" {{ old('type_formule') == 'enterprise' ? 'selected' : '' }}>Enterprise</option>
                                        </select>
                                        @error('type_formule')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Colonne droite -->
                                <div class="col-md-6">
                                    <div class="section-title">Limites & Tarification</div>

                                    <div class="row g-3">
                                        <div class="col-6">
                                            <label for="employes_min" class="form-label">Employés Min</label>
                                            <input type="number" class="form-control @error('employes_min') is-invalid @enderror"
                                                id="employes_min" name="employes_min" value="{{ old('employes_min', 1) }}" min="1">
                                            @error('employes_min')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-6">
                                            <label for="employes_max" class="form-label">Employés Max</label>
                                            <input type="number" class="form-control @error('employes_max') is-invalid @enderror"
                                                id="employes_max" name="employes_max" value="{{ old('employes_max', 10) }}" min="1">
                                            @error('employes_max')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3 mt-3">
                                        <label for="sites_max" class="form-label">Nombre de sites maximum</label>
                                        <input type="number" class="form-control @error('sites_max') is-invalid @enderror"
                                            id="sites_max" name="sites_max" value="{{ old('sites_max', 1) }}" min="1">
                                        @error('sites_max')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Dates & Facturation -->
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="section-title">Dates du Contrat</div>

                                    <div class="mb-3">
                                        <label for="date_debut" class="form-label">Date de début</label>
                                        <input type="date" class="form-control @error('date_debut') is-invalid @enderror"
                                            id="date_debut" name="date_debut" value="{{ old('date_debut', date('Y-m-d')) }}">
                                        @error('date_debut')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="date_fin" class="form-label">Date de fin (optionnel)</label>
                                        <input type="date" class="form-control @error('date_fin') is-invalid @enderror"
                                            id="date_fin" name="date_fin" value="{{ old('date_fin') }}">
                                        <small class="text-muted">Laissez vide pour une durée illimitée</small>
                                        @error('date_fin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="duree_mois" class="form-label">Durée (mois)</label>
                                        <input type="number" class="form-control @error('duree_mois') is-invalid @-error"
                                            id="duree_mois" name="duree_mois" value="{{ old('duree_mois') }}" min="1">
                                        <small class="text-muted">Laissez vide pour illimité</small>
                                        @error('duree_mois')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="section-title">Facturation</div>

                                    <div class="mb-3">
                                        <label for="montant_mensuel" class="form-label">Montant mensuel (F CFA)</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control @error('montant_mensuel') is-invalid @enderror"
                                                id="montant_mensuel" name="montant_mensuel" value="{{ old('montant_mensuel') }}" min="0" step="1000">
                                            <span class="input-group-text">F</span>
                                        </div>
                                        @error('montant_mensuel')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="cycle_facturation" class="form-label">Cycle de facturation</label>
                                        <select class="form-select @error('cycle_facturation') is-invalid @enderror"
                                            id="cycle_facturation" name="cycle_facturation">
                                            <option value="mensuel" {{ old('cycle_facturation') == 'mensuel' ? 'selected' : '' }}>Mensuel</option>
                                            <option value="trimestriel" {{ old('cycle_facturation') == 'trimestriel' ? 'selected' : '' }}>Trimestriel</option>
                                            <option value="semestriel" {{ old('cycle_facturation') == 'semestriel' ? 'selected' : '' }}>Semestriel</option>
                                            <option value="annuel" {{ old('cycle_facturation') == 'annuel' ? 'selected' : '' }}>Annuel</option>
                                        </select>
                                        @error('cycle_facturation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="jours_essai" class="form-label">Jours d'essai gratuit</label>
                                        <input type="number" class="form-control @error('jours_essai') is-invalid @enderror"
                                            id="jours_essai" name="jours_essai" value="{{ old('jours_essai', 7) }}" min="0" max="30">
                                        @error('jours_essai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Statut -->
                            <div class="row g-4">
                                <div class="col-md-12">
                                    <div class="section-title">Statut</div>

                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="est_active" name="est_active" value="1" {{ old('est_active', true) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="est_active">
                                                    Plan actif
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="est_en_essai" name="est_en_essai" value="1" {{ old('est_en_essai') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="est_en_essai">
                                                    Période d'essai
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="est_renouvele_auto" name="est_renouvele_auto" value="1" {{ old('est_renouvele_auto', true) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="est_renouvele_auto">
                                                    Renouvellement automatique
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Notes -->
                            <div class="mt-4">
                                <label for="notes" class="form-label">Notes additionnelles</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror"
                                    id="notes" name="notes" rows="2"
                                    placeholder="Informations complémentaires...">{{ old('notes') }}</textarea>
                            </div>

                            <!-- Boutons -->
                            <div class="d-flex justify-content-end gap-3 mt-4 pt-4 border-top">
                                <a href="{{ route('admin.superadmin.abonnements.index') }}" class="btn btn-secondary btn-save">
                                    <i class="bi bi-x-circle me-2"></i>Annuler
                                </a>
                                <button type="submit" class="btn btn-success btn-save">
                                    <i class="bi bi-check-circle me-2"></i>Créer le Plan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function selectPlan(plan) {
        document.getElementById('formule_' + plan).checked = true;

        // Update form values based on plan
        const planData = {
            basic: {
                min: 20,
                max: 40,
                sites: 5,
                amount: 100000,
                duration: 3
            },
            premium: {
                min: 41,
                max: 100,
                sites: 15,
                amount: 150000,
                duration: null
            },
            enterprise: {
                min: 101,
                max: 300,
                sites: 50,
                amount: 200000,
                duration: null
            }
        };

        const data = planData[plan];
        document.getElementById('employes_min').value = data.min;
        document.getElementById('employes_max').value = data.max;
        document.getElementById('sites_max').value = data.sites;
        document.getElementById('montant_mensuel').value = data.amount;
        document.getElementById('type_formule').value = plan;

        if (data.duration) {
            document.getElementById('duree_mois').value = data.duration;
        }
    }
</script>
@endpush
@endsection