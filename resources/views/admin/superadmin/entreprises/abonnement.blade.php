@extends('layouts.app')

@section('title', 'Choisir un abonnement - ' . $entreprise->nom_entreprise)

@push('styles')
<style>
    .pricing-card {
        border: 2px solid #e9ecef;
        border-radius: 16px;
        padding: 1.5rem;
        transition: all 0.3s ease;
        cursor: pointer;
        height: 100%;
        background: #fff;
    }

    .pricing-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .pricing-card.selected {
        border-color: #198754;
        background: linear-gradient(135deg, rgba(25, 135, 84, 0.05) 0%, rgba(32, 201, 151, 0.05) 100%);
    }

    .pricing-card .price {
        font-size: 2rem;
        font-weight: 700;
        color: #198754;
    }

    .pricing-card .price span {
        font-size: 1rem;
        font-weight: 400;
        color: #6c757d;
    }

    .pricing-card .features-list {
        list-style: none;
        padding: 0;
        margin: 1.5rem 0;
    }

    .pricing-card .features-list li {
        padding: 0.5rem 0;
        display: flex;
        align-items: center;
    }

    .pricing-card .features-list li i {
        color: #198754;
        margin-right: 0.5rem;
    }

    .package-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .bg-success-light {
        background: rgba(25, 135, 84, 0.1);
        color: #198754;
    }

    .bg-primary-light {
        background: rgba(13, 110, 253, 0.1);
        color: #0d6efd;
    }

    .bg-info-light {
        background: rgba(13, 202, 240, 0.1);
        color: #0dcaf0;
    }

    .bg-warning-light {
        background: rgba(255, 193, 7, 0.1);
        color: #ffc107;
    }

    .form-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08);
    }
</style>
@endpush

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0"><i class="bi bi-credit-card me-2"></i>Choisir un abonnement</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.entreprises.index') }}">Entreprises</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.entreprises.show', $entreprise->id) }}">{{ $entreprise->nom_entreprise }}</a></li>
                    <li class="breadcrumb-item active">Abonnement</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!--end::App Content Header-->

<!--begin::App Content-->
<div class="app-content">
    <div class="container-fluid">
        <!-- Messages -->
        @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="bi bi-info-circle me-2"></i>{{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-primary">
                    <h5><i class="bi bi-building me-2"></i>Entreprise: {{ $entreprise->nom_entreprise }}</h5>
                    <p class="mb-0">Choisissez un abonnement adapté à vos besoins. Vous pouvez commencer avec l'essai gratuit.</p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.superadmin.entreprises.formule', $entreprise->id) }}" method="POST" id="abonnementForm">
            @csrf

            <div class="row g-4">
                @foreach($formules as $key => $formule)
                <div class="col-md-6 col-lg-3">
                    <div class="pricing-card" onclick="selectFormule('{{ $key }}')" id="card-{{ $key }}">
                        <div class="package-icon bg-{{ $formule['couleur'] }}-light">
                            <i class="bi {{ $formule['icone'] }}"></i>
                        </div>
                        <h4 class="mb-1">{{ $formule['nom'] }}</h4>
                        <p class="text-muted small mb-3">{{ $formule['description'] }}</p>

                        <div class="price">
                            {{ number_format($formule['prix'], 0, ',', ' ') }} <span>CFA</span>
                            @if($formule['prix'] > 0)
                            <small>/{{ $formule['duree'] }}</small>
                            @endif
                        </div>

                        <ul class="features-list">
                            <li><i class="bi bi-check-circle-fill"></i> até {{ $formule['agents_max'] }} agents</li>
                            <li><i class="bi bi-check-circle-fill"></i> até {{ $formule['sites_max'] }} sites</li>
                            <li><i class="bi bi-check-circle-fill"></i> Support {{ $key === 'premium' ? 'prioritaire' : 'standard' }}</li>
                            @if($key === 'premium')
                            <li><i class="bi bi-check-circle-fill"></i> Accès API</li>
                            @endif
                        </ul>

                        <div class="form-check mt-3">
                            <input class="form-check-input" type="radio" name="formule" id="formule_{{ $key }}"
                                value="{{ $key }}" {{ $key === 'essai' ? 'checked' : '' }}>
                            <label class="form-check-label" for="formule_{{ $key }}">
                                Choisir {{ $formule['nom'] }}
                            </label>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Configuration selon le choix -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card form-card">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="bi bi-sliders me-2"></i>Configuration</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="nombre_agents" class="form-label">Nombre d'agents maximum</label>
                                <input type="number" class="form-control" id="nombre_agents" name="nombre_agents"
                                    value="5" min="1" max="300">
                                <small class="text-muted">Le nombre d'agents que vous souhaitez gérer</small>
                            </div>
                            <div class="mb-3">
                                <label for="nombre_sites" class="form-label">Nombre de sites maximum</label>
                                <input type="number" class="form-control" id="nombre_sites" name="nombre_sites"
                                    value="2" min="1" max="100">
                                <small class="text-muted">Le nombre de sites à surveiller</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card form-card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="bi bi-receipt me-2"></i>Récapitulatif</h5>
                        </div>
                        <div class="card-body">
                            <div id="recap-formule">
                                <p><strong>Formule:</strong> <span id="recap-nom">Essai Gratuit</span></p>
                                <p><strong>Prix:</strong> <span id="recap-prix" class="text-success fw-bold">0 CFA</span></p>
                                <p><strong>Agents max:</strong> <span id="recap-agents">5</span></p>
                                <p><strong>Sites max:</strong> <span id="recap-sites">2</span></p>
                                <p><strong>Durée:</strong> <span id="recap-duree">30 jours</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12 text-end">
                    <a href="{{ route('admin.superadmin.entreprises.index') }}" class="btn btn-secondary me-2">
                        <i class="bi bi-arrow-left me-1"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="bi bi-check-circle me-1"></i> Confirmer mon choix
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<!--end::App Content-->

@push('scripts')
<script>
    const formules = @json($formules);

    function selectFormule(key) {
        // Retirer la sélection précédente
        document.querySelectorAll('.pricing-card').forEach(card => {
            card.classList.remove('selected');
        });

        // Ajouter la sélection
        document.getElementById('card-' + key).classList.add('selected');

        // Cocher le radio
        document.getElementById('formule_' + key).checked = true;

        // Mettre à jour le récapitulatif
        document.getElementById('recap-nom').textContent = formules[key].nom;
        document.getElementById('recap-prix').textContent = (formules[key].prix === 0 ? '0' : numberWithSpaces(formules[key].prix)) + ' CFA' + (formules[key].prix > 0 ? '/mois' : '');
        document.getElementById('recap-agents').textContent = formules[key].agents_max;
        document.getElementById('recap-sites').textContent = formules[key].sites_max;
        document.getElementById('recap-duree').textContent = formules[key].duree;

        // Mettre à jour les valeurs max des inputs
        document.getElementById('nombre_agents').max = formules[key].agents_max;
        document.getElementById('nombre_sites').max = formules[key].sites_max;
        document.getElementById('nombre_agents').value = Math.min(document.getElementById('nombre_agents').value, formules[key].agents_max);
        document.getElementById('nombre_sites').value = Math.min(document.getElementById('nombre_sites').value, formules[key].sites_max);
    }

    function numberWithSpaces(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
    }

    // Écouter les changements sur les inputs
    document.getElementById('nombre_agents').addEventListener('input', function() {
        document.getElementById('recap-agents').textContent = this.value;
    });

    document.getElementById('nombre_sites').addEventListener('input', function() {
        document.getElementById('recap-sites').textContent = this.value;
    });

    // Écouter les changements de formule
    document.querySelectorAll('input[name="formule"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            selectFormule(this.value);
        });
    });

    // Initialiser
    document.addEventListener('DOMContentLoaded', function() {
        const selectedFormule = document.querySelector('input[name="formule"]:checked').value;
        selectFormule(selectedFormule);
    });
</script>
@endpush
@endsection