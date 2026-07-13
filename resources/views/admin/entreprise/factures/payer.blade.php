@extends('layouts.app')

@section('title', 'Payer la facture ' . $facture->numero_facture)

@push('styles')
<style>
    .payment-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 0.25rem 0.5rem rgba(0,0,0,0.05);
    }
    .payment-header {
        background: linear-gradient(135deg, #19875422, #19875411);
        border-bottom: 2px solid #19875433;
    }
    .mode-card {
        cursor: pointer;
        border: 2px solid #e9ecef;
        border-radius: 12px;
        transition: all 0.2s ease;
        padding: 1rem;
        text-align: center;
    }
    .mode-card:hover {
        border-color: #198754;
        background: #1987540a;
    }
    .mode-card.selected {
        border-color: #198754;
        background: #19875411;
    }
    .mode-card .icon {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }
    @keyframes checkBounce {
        0% { transform: scale(0); opacity: 0; }
        50% { transform: scale(1.2); }
        70% { transform: scale(0.9); }
        100% { transform: scale(1); opacity: 1; }
    }
    @keyframes xMark {
        0% { transform: scale(0) rotate(-90deg); opacity: 0; }
        60% { transform: scale(1.15) rotate(-10deg); }
        100% { transform: scale(1) rotate(0deg); opacity: 1; }
    }
    @keyframes fadeSlideUp {
        0% { transform: translateY(30px); opacity: 0; }
        100% { transform: translateY(0); opacity: 1; }
    }
    @keyframes confetti {
        0% { transform: translateY(0) rotate(0deg); opacity: 1; }
        100% { transform: translateY(-200px) rotate(720deg); opacity: 0; }
    }
    .modal-icon-success {
        animation: checkBounce 0.6s ease forwards;
    }
    .modal-icon-error {
        animation: xMark 0.5s ease forwards;
    }
    .modal-content-animate {
        animation: fadeSlideUp 0.4s ease forwards;
    }
    .confetti-piece {
        position: absolute;
        width: 10px;
        height: 10px;
        border-radius: 2px;
        animation: confetti 1.5s ease-out forwards;
    }
    .btn-loading {
        position: relative;
        pointer-events: none;
    }
    .btn-loading .btn-text {
        visibility: hidden;
    }
    .btn-loading::after {
        content: '';
        position: absolute;
        inset: 0;
        margin: auto;
        width: 24px;
        height: 24px;
        border: 3px solid #ffffff80;
        border-top-color: #fff;
        border-radius: 50%;
        animation: spinner 0.6s linear infinite;
    }
    @keyframes spinner {
        to { transform: rotate(360deg); }
    }
    .error-border {
        border-color: #dc3545 !important;
    }
</style>
@endpush

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0"><i class="bi bi-credit-card me-2"></i>Paiement de la facture {{ $facture->numero_facture }}</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.entreprise.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.entreprise.factures.index') }}">Factures</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.entreprise.factures.show', $facture->id) }}">{{ $facture->numero_facture }}</a></li>
                    <li class="breadcrumb-item active">Paiement</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div id="errorAlert" class="alert alert-danger alert-dismissible fade show d-none" role="alert">
            <i class="bi bi-exclamation-triangle me-1"></i> <span id="errorAlertMsg"></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card payment-card">
                    <div class="card-header payment-header">
                        <h5 class="mb-0"><i class="bi bi-credit-card me-2"></i>Formulaire de paiement</h5>
                    </div>
                    <div class="card-body">
                        <form id="paymentForm" method="POST" action="{{ route('admin.entreprise.factures.traiterPaiement', $facture->id) }}">
                            @csrf

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Montant à payer</label>
                                <div class="input-group input-group-lg">
                                    <input type="number" name="montant" id="montantInput"
                                           class="form-control"
                                           value="{{ $facture->montant_restant }}"
                                           min="100" max="{{ $facture->montant_restant }}" step="1" required>
                                    <span class="input-group-text">FCFA</span>
                                </div>
                                <div id="montantError" class="text-danger small mt-1 d-none"></div>
                                <div class="d-flex justify-content-between mt-2">
                                    <small class="text-muted">Solde restant : <strong>{{ number_format($facture->montant_restant, 0, ',', ' ') }} CFA</strong></small>
                                    <button type="button" class="btn btn-sm btn-outline-success" onclick="setMontantTotal()">
                                        Payer la totalité
                                    </button>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Mode de paiement</label>
                                <div class="row g-3">
                                    <div class="col-6 col-md-3">
                                        <div class="mode-card" data-value="mobile_money" onclick="selectMode(this)">
                                            <div class="icon">📱</div>
                                            <div class="fw-semibold small">Mobile Money</div>
                                            <small class="text-muted">MTN / Moov / Celtiis</small>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="mode-card" data-value="carte" onclick="selectMode(this)">
                                            <div class="icon">💳</div>
                                            <div class="fw-semibold small">Carte Bancaire</div>
                                            <small class="text-muted">Visa / Mastercard</small>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="mode-card" data-value="virement" onclick="selectMode(this)">
                                            <div class="icon">🏦</div>
                                            <div class="fw-semibold small">Virement</div>
                                            <small class="text-muted">Bancaire</small>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="mode-card" data-value="especes" onclick="selectMode(this)">
                                            <div class="icon">💵</div>
                                            <div class="fw-semibold small">Espèces</div>
                                            <small class="text-muted">Paiement comptant</small>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="mode_paiement" id="mode_paiement">
                                <div id="modeError" class="text-danger small mt-1 d-none">Veuillez sélectionner un mode de paiement.</div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Référence de transaction <small class="text-muted">(optionnel)</small></label>
                                <input type="text" name="reference" id="referenceInput"
                                       class="form-control"
                                       placeholder="Ex: MTN-123456, Virement n°...">
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Notes <small class="text-muted">(optionnel)</small></label>
                                <textarea name="notes" id="notesInput" class="form-control"
                                          rows="2" placeholder="Informations complémentaires..."></textarea>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success btn-lg flex-grow-1" id="submitBtn">
                                    <span class="btn-text"><i class="bi bi-check2-circle me-1"></i> Confirmer le paiement</span>
                                </button>
                                <a href="{{ route('admin.entreprise.factures.show', $facture->id) }}" class="btn btn-outline-secondary btn-lg">
                                    <i class="bi bi-x-circle me-1"></i> Annuler
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card payment-card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-receipt me-2"></i>Récapitulatif de la facture</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted">N° Facture</small>
                            <p class="fw-semibold mb-0">{{ $facture->numero_facture }}</p>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">Abonnement</small>
                            <p class="mb-0">{{ $facture->abonnement?->formule_label ?? 'Prestation de sécurité' }}</p>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">Période</small>
                            <p class="mb-0">{{ str_pad($facture->mois, 2, '0', STR_PAD_LEFT) . '/' . $facture->annee }}</p>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">Date d'émission</small>
                            <p class="mb-0">{{ $facture->date_emission?->format('d/m/Y') ?? '-' }}</p>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">Échéance</small>
                            <p class="mb-0">{{ $facture->date_echeance?->format('d/m/Y') ?? '-' }}</p>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Montant TTC</span>
                            <strong>{{ number_format($facture->montant_ttc, 0, ',', ' ') }} CFA</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Déjà payé</span>
                            <strong class="text-success">{{ number_format($facture->montant_paye, 0, ',', ' ') }} CFA</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-0">
                            <span class="fw-semibold">Restant dû</span>
                            <strong class="text-danger fs-5">{{ number_format($facture->montant_restant, 0, ',', ' ') }} CFA</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Succès --}}
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 overflow-hidden modal-content-animate">
            <div class="modal-body text-center py-5 position-relative" style="background: linear-gradient(180deg, #f0fdf4 0%, #fff 40%);">
                <div class="position-absolute top-0 start-0 end-0" style="height: 4px; background: linear-gradient(90deg, #22c55e, #16a34a, #15803d);"></div>
                <div class="position-relative" style="z-index: 1;">
                    <div class="mb-3 position-relative d-inline-block" id="confettiContainer"></div>
                    <div class="mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center"
                             style="width: 88px; height: 88px; border-radius: 50%; background: linear-gradient(135deg, #dcfce7, #bbf7d0);">
                            <i class="bi bi-check-circle-fill text-success modal-icon-success" style="font-size: 3.2rem;"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold mb-2" style="color: #166534;">Paiement réussi !</h4>
                    <p class="text-muted mb-4" id="successMessage" style="max-width: 320px; margin: 0 auto;">Votre paiement a été enregistré avec succès.</p>
                    <div class="bg-light rounded-3 p-3 mb-4 mx-auto" style="max-width: 340px;">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted small">Montant</span>
                            <span class="fw-semibold" id="successMontant"></span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted small">Mode</span>
                            <span id="successMode"></span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted small">Référence</span>
                            <span class="small" id="successReference"></span>
                        </div>
                        <div class="d-flex justify-content-between mb-0">
                            <span class="text-muted small">Date</span>
                            <span id="successDate"></span>
                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-center">
                        <a href="#" class="btn btn-success px-4" id="successViewBtn">
                            <i class="bi bi-eye me-1"></i> Voir la facture
                        </a>
                        <a href="{{ route('admin.entreprise.factures.index') }}" class="btn btn-outline-secondary px-4">
                            <i class="bi bi-list-ul me-1"></i> Mes factures
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Erreur --}}
<div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 overflow-hidden modal-content-animate">
            <div class="modal-body text-center py-5 position-relative" style="background: linear-gradient(180deg, #fef2f2 0%, #fff 40%);">
                <div class="position-absolute top-0 start-0 end-0" style="height: 4px; background: linear-gradient(90deg, #ef4444, #dc2626, #b91c1c);"></div>
                <div class="mb-3">
                    <div class="d-inline-flex align-items-center justify-content-center"
                         style="width: 88px; height: 88px; border-radius: 50%; background: linear-gradient(135deg, #fee2e2, #fecaca);">
                        <i class="bi bi-x-circle-fill text-danger modal-icon-error" style="font-size: 3.2rem;"></i>
                    </div>
                </div>
                <h4 class="fw-bold mb-2" style="color: #991b1b;">Paiement échoué</h4>
                <p class="text-muted mb-4" id="errorMessage" style="max-width: 360px; margin: 0 auto;">Une erreur est survenue lors du traitement du paiement.</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-danger px-4" data-bs-dismiss="modal">
                        <i class="bi bi-arrow-left me-1"></i> Réessayer
                    </button>
                    <a href="{{ route('admin.entreprise.factures.index') }}" class="btn btn-outline-secondary px-4">
                        Mes factures
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function selectMode(el) {
        document.querySelectorAll('.mode-card').forEach(c => c.classList.remove('selected'));
        el.classList.add('selected');
        document.getElementById('mode_paiement').value = el.dataset.value;
        document.getElementById('modeError').classList.add('d-none');
    }

    function setMontantTotal() {
        document.getElementById('montantInput').value = '{{ $facture->montant_restant }}';
        document.getElementById('montantError').classList.add('d-none');
    }

    function spawnConfetti() {
        const container = document.getElementById('confettiContainer');
        const colors = ['#22c55e', '#16a34a', '#15803d', '#4ade80', '#86efac', '#fbbf24', '#f59e0b'];
        for (let i = 0; i < 20; i++) {
            const piece = document.createElement('div');
            piece.className = 'confetti-piece';
            const size = 6 + Math.random() * 8;
            piece.style.width = size + 'px';
            piece.style.height = size + 'px';
            piece.style.background = colors[Math.floor(Math.random() * colors.length)];
            piece.style.left = (Math.random() * 100) + '%';
            piece.style.bottom = '0';
            piece.style.position = 'absolute';
            piece.style.animationDelay = (Math.random() * 0.3) + 's';
            piece.style.borderRadius = Math.random() > 0.5 ? '50%' : '2px';
            container.appendChild(piece);
        }
    }

    function showSuccessModal(data) {
        document.getElementById('successMontant').textContent = data.paiement.montant;
        document.getElementById('successMode').textContent = data.paiement.mode;
        document.getElementById('successReference').textContent = data.paiement.reference || '-';
        document.getElementById('successDate').textContent = data.paiement.date;
        document.getElementById('successViewBtn').href = data.facture_url;
        spawnConfetti();
        new bootstrap.Modal(document.getElementById('successModal')).show();
    }

    function showErrorModal(message) {
        document.getElementById('errorMessage').textContent = message;
        new bootstrap.Modal(document.getElementById('errorModal')).show();
    }

    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const submitBtn = document.getElementById('submitBtn');
        const montant = document.getElementById('montantInput').value;
        const mode = document.getElementById('mode_paiement').value;
        const maxMontant = {{ $facture->montant_restant }};
        let hasError = false;

        document.getElementById('montantError').classList.add('d-none');
        document.getElementById('montantInput').classList.remove('error-border');
        document.getElementById('modeError').classList.add('d-none');

        if (!montant || montant < 100) {
            document.getElementById('montantError').textContent = 'Le montant minimum est de 100 FCFA.';
            document.getElementById('montantError').classList.remove('d-none');
            document.getElementById('montantInput').classList.add('error-border');
            hasError = true;
        } else if (parseFloat(montant) > maxMontant) {
            document.getElementById('montantError').textContent = 'Le montant ne peut pas dépasser ' + maxMontant.toLocaleString() + ' FCFA.';
            document.getElementById('montantError').classList.remove('d-none');
            document.getElementById('montantInput').classList.add('error-border');
            hasError = true;
        }

        if (!mode) {
            document.getElementById('modeError').classList.remove('d-none');
            hasError = true;
        }

        if (hasError) return;

        submitBtn.classList.add('btn-loading');

        fetch(this.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: new FormData(this),
        })
        .then(response => response.json().then(data => ({ status: response.status, data })))
        .then(({ status, data }) => {
            submitBtn.classList.remove('btn-loading');
            if (data.success) {
                showSuccessModal(data);
            } else {
                showErrorModal(data.message || 'Une erreur inattendue est survenue.');
            }
        })
        .catch(() => {
            submitBtn.classList.remove('btn-loading');
            showErrorModal('Une erreur réseau est survenue. Veuillez réessayer.');
        });
    });
</script>
@endpush