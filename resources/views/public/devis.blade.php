@extends('layouts.app')

@section('title', 'Demande de Devis - Benin Security')

@push('styles')
<style>
    .devis-header {
        background: linear-gradient(135deg, #198754 0%, #20c997 100%);
        border-radius: 20px;
        padding: 3rem 2rem;
        color: white;
        margin-bottom: 2rem;
    }

    .devis-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .devis-card .card-header {
        background: #198754;
        color: white;
        border: none;
        padding: 1.5rem;
        font-weight: 600;
    }

    .form-label {
        font-weight: 500;
        color: #495057;
        margin-bottom: 0.5rem;
    }

    .form-control,
    .form-select {
        border-radius: 10px;
        padding: 0.75rem 1rem;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #198754;
        box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.15);
    }

    .btn-devis {
        background: linear-gradient(135deg, #198754 0%, #20c997 100%);
        border: none;
        border-radius: 10px;
        padding: 1rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-devis:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(25, 135, 84, 0.4);
    }

    .service-option {
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
    }

    .service-option:hover {
        border-color: #198754;
        background: rgba(25, 135, 84, 0.05);
    }

    .service-option.selected {
        border-color: #198754;
        background: rgba(25, 135, 84, 0.1);
    }

    .service-option i {
        font-size: 2rem;
        color: #198754;
        margin-bottom: 0.5rem;
    }

    .info-box {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .info-box i {
        color: #198754;
        font-size: 1.5rem;
    }

    .step-indicator {
        display: flex;
        justify-content: space-between;
        margin-bottom: 2rem;
    }

    .step {
        flex: 1;
        text-align: center;
        position: relative;
    }

    .step:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 20px;
        right: -50%;
        width: 100%;
        height: 2px;
        background: #e9ecef;
    }

    .step.completed:not(:last-child)::after {
        background: #198754;
    }

    .step-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.5rem;
        font-weight: 600;
        color: #6c757d;
    }

    .step.completed .step-circle {
        background: #198754;
        color: white;
    }

    .step.active .step-circle {
        background: #20c997;
        color: white;
    }

    .step-label {
        font-size: 0.85rem;
        color: #6c757d;
    }

    .step.completed .step-label,
    .step.active .step-label {
        color: #198754;
        font-weight: 500;
    }

    .contact-card {
        background: linear-gradient(135deg, #0d6efd 0%, #6ea8fe 100%);
        border-radius: 16px;
        padding: 1.5rem;
        color: white;
    }

    .contact-card a {
        color: white;
        text-decoration: none;
    }

    .contact-card a:hover {
        text-decoration: underline;
    }
</style>
@endpush

@section('content')
<!--begin::App Main-->
<main class="app-main">
    <!--begin::App Content Header-->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0 text-white">Demande de Devis</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">Accueil</a></li>
                        <li class="breadcrumb-item active text-white-50">Devis</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!--end::App Content Header-->

    <!--begin::App Content-->
    <div class="app-content">
        <div class="container-fluid">

            <!-- En-tête -->
            <div class="devis-header text-center">
                <h1 class="fw-bold mb-3">
                    <i class="bi bi-shield-check me-2"></i>
                    Benin Security
                </h1>
                <p class="lead mb-0">Votre partenaire de confiance pour la sécurité</p>
            </div>

            <!-- Indicateur de étapes -->
            <div class="step-indicator mb-4">
                <div class="step active">
                    <div class="step-circle">1</div>
                    <div class="step-label">Informations</div>
                </div>
                <div class="step">
                    <div class="step-circle">2</div>
                    <div class="step-label">Besoins</div>
                </div>
                <div class="step">
                    <div class="step-circle">3</div>
                    <div class="step-label">Validation</div>
                </div>
            </div>

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                <strong>Merci !</strong> Votre demande de devis a été soumise avec succès.
                <br>Notre équipe va traiter votre demande et vous contactera sous 24h-48h.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <strong>Erreur !</strong> Veuillez corriger les erreurs ci-dessous.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <div class="row g-4">
                <!-- Formulaire -->
                <div class="col-lg-8">
                    <div class="devis-card card">
                        <div class="card-header">
                            <i class="bi bi-file-earmark-text me-2"></i>
                            Formulaire de demande de devis
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('devis.soumettre') }}" id="devisForm">
                                @csrf

                                <!-- Informations de l'entreprise -->
                                <h5 class="mb-3 text-success">
                                    <i class="bi bi-building me-2"></i>Informations de l'Entreprise
                                </h5>

                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label for="nom_entreprise" class="form-label">Nom de l'entreprise *</label>
                                        <input type="text" class="form-control @error('nom_entreprise') is-invalid @enderror"
                                            id="nom_entreprise" name="nom_entreprise"
                                            value="{{ old('nom_entreprise') }}" required>
                                        @error('nom_entreprise')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="nom_commercial" class="form-label">Nom commercial</label>
                                        <input type="text" class="form-control" id="nom_commercial" name="nom_commercial"
                                            value="{{ old('nom_commercial') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="forme_juridique" class="form-label">Forme juridique</label>
                                        <select class="form-select" id="forme_juridique" name="forme_juridique">
                                            <option value="">Sélectionner...</option>
                                            <option value="SARL" {{ old('forme_juridique') == 'SARL' ? 'selected' : '' }}>SARL</option>
                                            <option value="SA" {{ old('forme_juridique') == 'SA' ? 'selected' : '' }}>SA</option>
                                            <option value="EURL" {{ old('forme_juridique') == 'EURL' ? 'selected' : '' }}>EURL</option>
                                            <option value="SNC" {{ old('forme_juridique') == 'SNC' ? 'selected' : '' }}>SNC</option>
                                            <option value="SCI" {{ old('forme_juridique') == 'SCI' ? 'selected' : '' }}>SCI</option>
                                            <option value="Particulier" {{ old('forme_juridique') == 'Particulier' ? 'selected' : '' }}>Particulier</option>
                                            <option value="Autre" {{ old('forme_juridique') == 'Autre' ? 'selected' : '' }}>Autre</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="numero_registre" class="form-label">N° Registre de commerce</label>
                                        <input type="text" class="form-control" id="numero_registre" name="numero_registre"
                                            value="{{ old('numero_registre') }}">
                                    </div>
                                </div>

                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email *</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email') }}" required>
                                        @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="telephone" class="form-label">Téléphone *</label>
                                        <input type="text" class="form-control @error('telephone') is-invalid @enderror"
                                            id="telephone" name="telephone" value="{{ old('telephone') }}" required>
                                        @error('telephone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <label for="adresse" class="form-label">Adresse</label>
                                        <input type="text" class="form-control" id="adresse" name="adresse"
                                            value="{{ old('adresse') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="ville" class="form-label">Ville</label>
                                        <input type="text" class="form-control" id="ville" name="ville"
                                            value="{{ old('ville') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="pays" class="form-label">Pays</label>
                                        <input type="text" class="form-control" id="pays" name="pays"
                                            value="{{ old('pays', 'Bénin') }}">
                                    </div>
                                </div>

                                <!-- Représentant légal -->
                                <h5 class="mb-3 text-success mt-4">
                                    <i class="bi bi-person-vcard me-2"></i>Représentant Légal
                                </h5>

                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label for="representant_nom" class="form-label">Nom complet</label>
                                        <input type="text" class="form-control" id="representant_nom" name="representant_nom"
                                            value="{{ old('representant_nom') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="representant_fonction" class="form-label">Fonction</label>
                                        <input type="text" class="form-control" id="representant_fonction" name="representant_fonction"
                                            value="{{ old('representant_fonction') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="representant_email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="representant_email" name="representant_email"
                                            value="{{ old('representant_email') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="representant_telephone" class="form-label">Téléphone</label>
                                        <input type="text" class="form-control" id="representant_telephone" name="representant_telephone"
                                            value="{{ old('representant_telephone') }}">
                                    </div>
                                </div>

                                <!-- Besoins -->
                                <h5 class="mb-3 text-success mt-4">
                                    <i class="bi bi-list-check me-2"></i>Vos Besoins
                                </h5>

                                <div class="mb-4">
                                    <label class="form-label">Type de service souhaité *</label>
                                    <div class="row g-2">
                                        <div class="col-6 col-md-4">
                                            <div class="service-option" onclick="selectService('garde_renforcee', this)">
                                                <i class="bi bi-shield-fill-check"></i>
                                                <div>Garde Renforcée</div>
                                            </div>
                                            <input type="radio" name="type_service" value="garde_renforcee"
                                                {{ old('type_service') == 'garde_renforcee' ? 'checked' : '' }} required hidden>
                                        </div>
                                        <div class="col-6 col-md-4">
                                            <div class="service-option" onclick="selectService('garde_simple', this)">
                                                <i class="bi bi-shield"></i>
                                                <div>Garde Simple</div>
                                            </div>
                                            <input type="radio" name="type_service" value="garde_simple"
                                                {{ old('type_service') == 'garde_simple' ? 'checked' : '' }} hidden>
                                        </div>
                                        <div class="col-6 col-md-4">
                                            <div class="service-option" onclick="selectService('surveillance_electronique', this)">
                                                <i class="bi bi-camera-video-fill"></i>
                                                <div>Surveillance Électronique</div>
                                            </div>
                                            <input type="radio" name="type_service" value="surveillance_electronique"
                                                {{ old('type_service') == 'surveillance_electronique' ? 'checked' : '' }} hidden>
                                        </div>
                                        <div class="col-6 col-md-4">
                                            <div class="service-option" onclick="selectService('garde_evenementiel', this)">
                                                <i class="bi bi-calendar-event-fill"></i>
                                                <div>Garde Événementiel</div>
                                            </div>
                                            <input type="radio" name="type_service" value="garde_evenementiel"
                                                {{ old('type_service') == 'garde_evenementiel' ? 'checked' : '' }} hidden>
                                        </div>
                                        <div class="col-6 col-md-4">
                                            <div class="service-option" onclick="selectService('conseil', this)">
                                                <i class="bi bi-lightbulb-fill"></i>
                                                <div>Conseil</div>
                                            </div>
                                            <input type="radio" name="type_service" value="conseil"
                                                {{ old('type_service') == 'conseil' ? 'checked' : '' }} hidden>
                                        </div>
                                        <div class="col-6 col-md-4">
                                            <div class="service-option" onclick="selectService('autre', this)">
                                                <i class="bi bi-three-dots"></i>
                                                <div>Autre</div>
                                            </div>
                                            <input type="radio" name="type_service" value="autre"
                                                {{ old('type_service') == 'autre' ? 'checked' : '' }} hidden>
                                        </div>
                                    </div>
                                    @error('type_service')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label for="nombre_agents" class="form-label">Nombre d'agents souhaités *</label>
                                        <input type="number" class="form-control @error('nombre_agents') is-invalid @enderror"
                                            id="nombre_agents" name="nombre_agents"
                                            value="{{ old('nombre_agents') }}" min="1" required>
                                        @error('nombre_agents')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="budget_approx" class="form-label">Budget approximatif (FCFA)</label>
                                        <input type="number" class="form-control" id="budget_approx" name="budget_approx"
                                            value="{{ old('budget_approx') }}" min="0">
                                    </div>
                                    <div class="col-12">
                                        <label for="description_besoins" class="form-label">Description de vos besoins</label>
                                        <textarea class="form-control" id="description_besoins" name="description_besoins"
                                            rows="4" placeholder="Décrivez vos besoins en détail...">{{ old('description_besoins') }}</textarea>
                                    </div>
                                </div>

                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="reset" class="btn btn-outline-secondary">Réinitialiser</button>
                                    <button type="submit" class="btn btn-devis btn-lg">
                                        <i class="bi bi-send me-2"></i>Soumettre ma demande
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Colonne latérale -->
                <div class="col-lg-4">
                    <!-- Pourquoi nous choisir -->
                    <div class="devis-card card mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-3">
                                <i class="bi bi-star-fill text-warning me-2"></i>Pourquoi nous choisir ?
                            </h5>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    <strong>Plus de 10 ans</strong> d'expérience
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    <strong>Agents formés</strong> et certifiés
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    <strong>Disponibilité 24h/24</strong> 7j/7
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    <strong>Certification ISO</strong> 9001
                                </li>
                                <li class="mb-0">
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    <strong>Support client</strong> réactif
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Nos services -->
                    <div class="devis-card card mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-3">
                                <i class="bi bi-list-ul text-success me-2"></i>Nos Services
                            </h5>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <i class="bi bi-shield-fill text-success me-2"></i>
                                    Gardeveillance humaine
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-camera-video text-success me-2"></i>
                                    Surveillance électronique
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-calendar-event text-success me-2"></i>
                                    Sécurité événementielle
                                </li>
                                <li class="mb-0">
                                    <i class="bi bi-lightbulb text-success me-2"></i>
                                    Conseil en sécurité
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Contact -->
                    <div class="contact-card mb-4">
                        <h5 class="mb-3">
                            <i class="bi bi-telephone me-2"></i>Contactez-nous
                        </h5>
                        <p class="mb-2">
                            <i class="bi bi-telephone me-2"></i>
                            <a href="tel:+22912345678">+229 12 34 56 78</a>
                        </p>
                        <p class="mb-2">
                            <i class="bi bi-envelope me-2"></i>
                            <a href="mailto:contact@beninsecurity.com">contact@beninsecurity.com</a>
                        </p>
                        <p class="mb-0">
                            <i class="bi bi-geo-alt me-2"></i>
                            Cotonou, Bénin
                        </p>
                    </div>

                    <!-- Processus -->
                    <div class="info-box">
                        <h5 class="mb-3">
                            <i class="bi bi-arrow-right-circle me-2"></i>Notre Processus
                        </h5>
                        <ol class="ps-3 mb-0">
                            <li class="mb-2">Vous soumettez votre demande</li>
                            <li class="mb-2">Nous analysons vos besoins</li>
                            <li class="mb-2">Nous vous envoyons un devis</li>
                            <li class="mb-2">Signature du contrat</li>
                            <li class="mb-0">Mise en place du service</li>
                        </ol>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!--end::App Content-->
</main>
<!--end::App Main-->
@endsection

@push('scripts')
<script>
    function selectService(value, element) {
        // Remove selected class from all options
        document.querySelectorAll('.service-option').forEach(opt => {
            opt.classList.remove('selected');
        });

        // Add selected class to clicked option
        element.classList.add('selected');

        // Select the radio button
        const radio = element.parentElement.querySelector('input[type="radio"]');
        radio.checked = true;
    }

    // Initialize selected service on page load
    document.addEventListener('DOMContentLoaded', function() {
        const selectedService = document.querySelector('input[name="type_service"]:checked');
        if (selectedService) {
            const option = selectedService.closest('.col-6, .col-md-4').querySelector('.service-option');
            if (option) {
                option.classList.add('selected');
            }
        }
    });
</script>
@endpush