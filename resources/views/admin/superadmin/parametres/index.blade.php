@extends('layouts.app')

@section('title', 'Paramètres Système - Super Admin')

@php
$activeTab = request('tab', 'general');
$groupLabels = [
    'general' => ['Général', 'bi-gear'],
    'security' => ['Sécurité', 'bi-shield-lock'],
    'email' => ['Email', 'bi-envelope-at'],
    'api' => ['API', 'bi-code-slash'],
    'mobile' => ['Mobile', 'bi-phone'],
    'notification' => ['Notifications', 'bi-bell'],
    'facturation' => ['Facturation', 'bi-currency-exchange'],
];
@endphp

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">
                    <i class="bi bi-gear-fill me-2"></i>Paramètres Système
                </h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Paramètres</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-outline card-success">
                    <div class="card-header p-0 border-bottom-0">
                        <ul class="nav nav-tabs nav-fill" id="settingsTabs" role="tablist">
                            @foreach($groupLabels as $groupKey => [$label, $icon])
                            @php $settingsCount = $groups->get($groupKey)?->count() ?? 0 @endphp
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $activeTab === $groupKey ? 'active' : '' }}"
                                    id="tab-{{ $groupKey }}"
                                    data-bs-toggle="tab"
                                    data-bs-target="#content-{{ $groupKey }}"
                                    type="button"
                                    role="tab"
                                    aria-selected="{{ $activeTab === $groupKey ? 'true' : 'false' }}">
                                    <i class="bi {{ $icon }} me-1"></i>
                                    {{ $label }}
                                    <span class="badge bg-success ms-1 rounded-pill">{{ $settingsCount }}</span>
                                </button>
                            </li>
                            @endforeach
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $activeTab === 'maintenance' ? 'active' : '' }}"
                                    id="tab-maintenance"
                                    data-bs-toggle="tab"
                                    data-bs-target="#content-maintenance"
                                    type="button"
                                    role="tab"
                                    aria-selected="{{ $activeTab === 'maintenance' ? 'true' : 'false' }}">
                                    <i class="bi bi-tools me-1"></i>
                                    Maintenance
                                </button>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <div class="tab-content" id="settingsTabsContent">
                            @foreach($groupLabels as $groupKey => [$label, $icon])
                            <div class="tab-pane fade {{ $activeTab === $groupKey ? 'show active' : '' }}"
                                id="content-{{ $groupKey }}"
                                role="tabpanel"
                                aria-labelledby="tab-{{ $groupKey }}">
                                <form action="{{ route('admin.superadmin.parametres.update-group', $groupKey) }}"
                                    method="POST"
                                    class="settings-form">
                                    @csrf
                                    <div class="d-flex align-items-center mb-4 pb-2 border-bottom">
                                        <div class="flex-shrink-0">
                                            <div class="bg-success bg-opacity-10 rounded-3 p-3">
                                                <i class="bi {{ $icon }} fs-3 text-success"></i>
                                            </div>
                                        </div>
                                        <div class="ms-3">
                                            <h4 class="mb-1 fw-bold">{{ $label }}</h4>
                                            <p class="text-muted mb-0">Configurez les paramètres {{ strtolower($label) }} du système</p>
                                        </div>
                                        <div class="ms-auto">
                                            <button type="submit" class="btn btn-success px-4 btn-submit-form"
                                                data-action="save-{{ $groupKey }}">
                                                <i class="bi bi-check-lg me-1"></i>Enregistrer
                                            </button>
                                        </div>
                                    </div>

                                    <div class="row g-4">
                                        @forelse($groups->get($groupKey, collect()) as $setting)
                                        <div class="col-md-6">
                                            <div class="settings-field">
                                                <label for="setting-{{ $setting->key }}" class="form-label fw-medium">
                                                    {{ $setting->label }}
                                                    @if($setting->description)
                                                    <i class="bi bi-info-circle text-muted ms-1"
                                                        data-bs-toggle="tooltip"
                                                        title="{{ $setting->description }}"></i>
                                                    @endif
                                                </label>

                                                @if($setting->type === 'boolean')
                                                <div class="form-check form-switch form-switch-lg">
                                                    <input class="form-check-input"
                                                        type="checkbox"
                                                        role="switch"
                                                        id="setting-{{ $setting->key }}"
                                                        name="{{ $setting->key }}"
                                                        value="1"
                                                        {{ $setting->castedValue() ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="setting-{{ $setting->key }}">
                                                        {{ $setting->castedValue() ? 'Activé' : 'Désactivé' }}
                                                    </label>
                                                </div>
                                                @elseif($setting->type === 'password')
                                                <div class="input-group">
                                                    <input type="password"
                                                        class="form-control"
                                                        id="setting-{{ $setting->key }}"
                                                        name="{{ $setting->key }}"
                                                        value="{{ $setting->value }}"
                                                        placeholder="••••••••">
                                                    <button class="btn btn-outline-secondary toggle-password"
                                                        type="button"
                                                        data-target="setting-{{ $setting->key }}">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                </div>
                                                @elseif($setting->type === 'json' && $setting->key === 'mode_paiements_acceptes')
                                                @php
                                                $modes = $setting->castedValue() ?: [];
                                                $availableModes = [
                                                    'carte' => 'Carte bancaire',
                                                    'virement' => 'Virement bancaire',
                                                    'especes' => 'Espèces',
                                                    'mobile_money' => 'Mobile Money',
                                                    'cheque' => 'Chèque',
                                                ];
                                                @endphp
                                                <div class="row g-2">
                                                    @foreach($availableModes as $modeKey => $modeLabel)
                                                    <div class="col-6">
                                                        <div class="form-check">
                                                            <input class="form-check-input"
                                                                type="checkbox"
                                                                name="{{ $setting->key }}[]"
                                                                value="{{ $modeKey }}"
                                                                id="mode-{{ $modeKey }}"
                                                                {{ in_array($modeKey, $modes) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="mode-{{ $modeKey }}">
                                                                {{ $modeLabel }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                                @elseif($setting->type === 'number')
                                                <input type="number"
                                                    class="form-control"
                                                    id="setting-{{ $setting->key }}"
                                                    name="{{ $setting->key }}"
                                                    value="{{ $setting->value }}"
                                                    step="any">
                                                @elseif($setting->type === 'json' || strlen($setting->value ?? '') > 100)
                                                <textarea class="form-control"
                                                    id="setting-{{ $setting->key }}"
                                                    name="{{ $setting->key }}"
                                                    rows="3">{{ $setting->value }}</textarea>
                                                @else
                                                <input type="text"
                                                    class="form-control"
                                                    id="setting-{{ $setting->key }}"
                                                    name="{{ $setting->key }}"
                                                    value="{{ $setting->value }}">
                                                @endif
                                            </div>
                                        </div>
                                        @empty
                                        <div class="col-12">
                                            <div class="text-center py-5">
                                                <i class="bi bi-slash-circle fs-1 text-muted"></i>
                                                <p class="text-muted mt-2">Aucun paramètre disponible.</p>
                                            </div>
                                        </div>
                                        @endforelse
                                    </div>

                                    <div class="mt-4 pt-3 border-top text-end">
                                        <button type="submit" class="btn btn-success px-5 btn-submit-form"
                                            data-action="save-{{ $groupKey }}">
                                            <i class="bi bi-check-lg me-1"></i>Enregistrer les modifications
                                        </button>
                                    </div>
                                </form>
                            </div>
                            @endforeach

                            <div class="tab-pane fade {{ $activeTab === 'maintenance' ? 'show active' : '' }}"
                                id="content-maintenance"
                                role="tabpanel"
                                aria-labelledby="tab-maintenance">
                                <div class="d-flex align-items-center mb-4 pb-2 border-bottom">
                                    <div class="flex-shrink-0">
                                        <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                                            <i class="bi bi-tools fs-3 text-warning"></i>
                                        </div>
                                    </div>
                                    <div class="ms-3">
                                        <h4 class="mb-1 fw-bold">Maintenance</h4>
                                        <p class="text-muted mb-0">Outils de maintenance et d'optimisation du système</p>
                                    </div>
                                </div>

                                <div class="row g-4">
                                    <div class="col-md-6 col-lg-4">
                                        <div class="card h-100 border-0 shadow-sm maintenance-card">
                                            <div class="card-body text-center p-4">
                                                <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3">
                                                    <i class="bi bi-arrow-repeat fs-2 text-info"></i>
                                                </div>
                                                <h5 class="fw-bold">Vider le Cache</h5>
                                                <p class="text-muted small">Efface le cache de l'application, la configuration, les routes et les vues.</p>
                                                <button type="button"
                                                    class="btn btn-info text-white px-4 btn-action-modal"
                                                    data-action="cache"
                                                    data-title="Vider le cache"
                                                    data-icon="bi-arrow-repeat"
                                                    data-color="info"
                                                    data-description="Cette action va effacer le cache de l'application, la configuration, les routes et les vues compilées."
                                                    data-warning="Les performances pourraient être temporairement réduites après cette opération.">
                                                    <i class="bi bi-trash3 me-1"></i>Vider le cache
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-4">
                                        <div class="card h-100 border-0 shadow-sm maintenance-card">
                                            <div class="card-body text-center p-4">
                                                <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3">
                                                    <i class="bi bi-lightning-charge fs-2 text-success"></i>
                                                </div>
                                                <h5 class="fw-bold">Optimiser</h5>
                                                <p class="text-muted small">Met en cache la configuration, les routes et les vues pour de meilleures performances.</p>
                                                <button type="button"
                                                    class="btn btn-success px-4 btn-action-modal"
                                                    data-action="optimize"
                                                    data-title="Optimiser l'application"
                                                    data-icon="bi-lightning-charge"
                                                    data-color="success"
                                                    data-description="Cette action va mettre en cache la configuration, les routes et les vues pour améliorer les performances."
                                                    data-warning="Les modifications de configuration ne seront pas prises en compte immédiatement.">
                                                    <i class="bi bi-lightning me-1"></i>Optimiser
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-4">
                                        <div class="card h-100 border-0 shadow-sm maintenance-card">
                                            <div class="card-body text-center p-4">
                                                <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3">
                                                    <i class="bi bi-envelope-paper fs-2 text-danger"></i>
                                                </div>
                                                <h5 class="fw-bold">Tester Email</h5>
                                                <p class="text-muted small">Envoyer un email de test pour vérifier la configuration SMTP.</p>
                                                <form action="{{ route('admin.superadmin.parametres.test-email') }}" method="POST"
                                                    class="test-email-form">
                                                    @csrf
                                                    <div class="input-group">
                                                        <input type="email"
                                                            name="email"
                                                            class="form-control"
                                                            placeholder="test@exemple.com"
                                                            value="{{ auth()->user()->email }}"
                                                            required>
                                                        <button type="button"
                                                            class="btn btn-danger btn-action-modal"
                                                            data-action="test-email"
                                                            data-title="Tester la configuration email"
                                                            data-icon="bi-envelope-paper"
                                                            data-color="danger"
                                                            data-description="Un email de test sera envoyé à l'adresse indiquée."
                                                            data-warning="Assurez-vous que la configuration SMTP est correcte avant de tester.">
                                                            <i class="bi bi-send me-1"></i>Tester
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-4">
                                        <div class="card h-100 border-0 shadow-sm maintenance-card">
                                            <div class="card-body text-center p-4">
                                                <div class="bg-secondary bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3">
                                                    <i class="bi bi-journal-text fs-2 text-secondary"></i>
                                                </div>
                                                <h5 class="fw-bold">Logs Système</h5>
                                                <p class="text-muted small">Consulter les derniers logs de l'application Laravel.</p>
                                                <a href="{{ route('admin.superadmin.parametres.logs') }}"
                                                    class="btn btn-secondary px-4"
                                                    target="_blank">
                                                    <i class="bi bi-eye me-1"></i>Voir les logs
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header bg-transparent">
                                            <h5 class="mb-0 fw-bold">
                                                <i class="bi bi-info-circle me-1"></i>Informations Système
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-borderless mb-0">
                                                    <tbody>
                                                        @php
                                                        $infos = [
                                                            ['Application', config('app.name', 'Bénin Security')],
                                                            ['Environnement', ucfirst(config('app.env'))],
                                                            ['Version Laravel', app()->version()],
                                                            ['PHP Version', PHP_VERSION],
                                                            ['Debug Mode', config('app.debug') ? '<span class="badge bg-danger">Activé</span>' : '<span class="badge bg-success">Désactivé</span>'],
                                                            ['URL', config('app.url')],
                                                            ['Timezone', config('app.timezone')],
                                                            ['Locale', config('app.locale')],
                                                            ['Driver DB', config('database.default')],
                                                            ['Driver Cache', config('cache.default')],
                                                            ['Driver Session', config('session.driver')],
                                                            ['Driver Queue', config('queue.default')],
                                                            ['Driver Mail', config('mail.default')],
                                                        ];
                                                        @endphp
                                                        @foreach($infos as [$label, $value])
                                                        <tr>
                                                            <td class="fw-medium text-muted" style="width: 200px;">{{ $label }}</td>
                                                            <td>{!! $value !!}</td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal de confirmation ultra professionnel --}}
<div class="modal fade" id="actionConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:16px;overflow:hidden;">
            <div class="modal-header text-white border-0" id="modalHeader" style="background:linear-gradient(135deg,#198754 0%,#20c997 100%);">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <div class="bg-white bg-opacity-25 rounded-circle p-2" id="modalIconWrapper">
                            <i class="bi fs-5" id="modalIcon"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold" id="modalTitle">Confirmation</h5>
                        <p class="mb-0 small opacity-75" id="modalSubtitle">Action système</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body py-4 px-4">
                <div class="text-center mb-4">
                    <div class="mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle" id="modalIconContainer"
                            style="width:80px;height:80px;">
                            <i class="bi fs-1" id="modalBigIcon"></i>
                        </div>
                    </div>
                    <h6 class="mb-2" style="color: var(--bs-secondary-color);" id="modalDescription">Description</h6>
                    <div class="d-flex align-items-center justify-content-center py-2 px-3 mt-3"
                        style="border-radius:8px; background: var(--bs-warning-bg-subtle); border: 1px solid var(--bs-warning-border-subtle);"
                        id="modalWarningWrapper">
                        <i class="bi bi-exclamation-triangle me-2" style="color: var(--bs-warning-text-emphasis);"></i>
                        <small style="color: var(--bs-body-color);" id="modalWarning">Attention</small>
                    </div>
                </div>
                <div class="p-3" style="border-radius:12px; background: var(--bs-tertiary-bg);">
                    <h6 class="fw-bold mb-2"><i class="bi bi-list-check me-2"></i>Détails de l'action :</h6>
                    <ul class="list-unstyled mb-0 small" id="modalDetails">
                        <li class="mb-1"><i class="bi bi-arrow-right-circle me-1 text-success"></i> Action irréversible</li>
                        <li class="mb-1"><i class="bi bi-arrow-right-circle me-1 text-success"></i> Nécessite une confirmation</li>
                        <li class="mb-0"><i class="bi bi-arrow-right-circle me-1 text-success"></i> Sera exécutée immédiatement</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer border-0 px-4 pb-4" style="background: var(--bs-tertiary-bg);">
                <button type="button" class="btn btn-outline-secondary btn-lg px-4" data-bs-dismiss="modal" style="border-radius:8px;">
                    <i class="bi bi-x-circle me-2"></i>Annuler
                </button>
                <button type="button" class="btn btn-lg px-4 text-white" id="modalConfirmBtn" style="border-radius:8px;">
                    <i class="bi bi-check-circle me-2"></i>Confirmer
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .nav-tabs .nav-link {
        border: none;
        color: var(--bs-body-color);
        padding: 1rem 1.25rem;
        font-weight: 500;
        transition: all 0.3s ease;
        position: relative;
    }

    .nav-tabs .nav-link::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 0;
        height: 3px;
        background: var(--bs-success);
        border-radius: 3px 3px 0 0;
        transition: width 0.3s ease;
    }

    .nav-tabs .nav-link:hover::after {
        width: 60%;
    }

    .nav-tabs .nav-link.active::after {
        width: 80%;
    }

    .nav-tabs .nav-link.active {
        color: var(--bs-success) !important;
        background: transparent !important;
        border: none !important;
    }

    .nav-tabs .nav-link:hover {
        border: none;
        background: var(--bs-tertiary-bg);
    }

    .nav-tabs .badge {
        font-size: 0.65rem;
    }

    .form-switch-lg .form-check-input {
        width: 3rem;
        height: 1.5rem;
        cursor: pointer;
    }

    .form-switch-lg .form-check-input:checked {
        background-color: var(--bs-success);
        border-color: var(--bs-success);
    }

    .form-check-label {
        cursor: pointer;
        user-select: none;
    }

    .settings-field {
        padding: 1.25rem;
        border-radius: 8px;
        border: 1px solid var(--bs-border-color);
        background: var(--bs-body-bg);
        transition: all 0.3s ease;
        height: 100%;
    }

    .settings-field:hover {
        border-color: var(--bs-success);
        box-shadow: 0 2px 12px rgba(25, 135, 84, 0.1);
    }

    .settings-field .form-label {
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
        color: var(--bs-body-color);
    }

    .maintenance-card {
        transition: all 0.3s ease;
        border-radius: 12px !important;
    }

    .maintenance-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
    }

    .card {
        border-radius: 12px;
    }

    .card-header {
        border-radius: 12px 12px 0 0;
    }

    .table-borderless td {
        padding: 0.5rem 0;
    }

    .breadcrumb {
        margin-bottom: 0;
    }

    @keyframes fadeSlideIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .tab-pane.active {
        animation: fadeSlideIn 0.3s ease;
    }

    .modal-content {
        animation: modalSlideIn 0.3s ease;
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-20px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltips.forEach(el => new bootstrap.Tooltip(el));

        document.querySelectorAll('.toggle-password').forEach(btn => {
            btn.addEventListener('click', function() {
                const targetId = this.dataset.target;
                const input = document.getElementById(targetId);
                if (!input) return;
                const icon = this.querySelector('i');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.className = 'bi bi-eye-slash';
                } else {
                    input.type = 'password';
                    icon.className = 'bi bi-eye';
                }
            });
        });

        document.querySelectorAll('.form-switch-lg .form-check-input').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const label = this.closest('.form-check').querySelector('.form-check-label');
                if (label) {
                    label.textContent = this.checked ? 'Activé' : 'Désactivé';
                }
            });
        });

        const hash = window.location.hash.replace('#', '');
        if (hash) {
            const tab = document.querySelector(`[data-bs-target="#content-${hash}"]`);
            if (tab) {
                bootstrap.Tab.getInstance(tab)?.show();
            }
        }

        document.querySelectorAll('[data-bs-toggle="tab"]').forEach(tabEl => {
            tabEl.addEventListener('shown.bs.tab', function(e) {
                history.replaceState(null, '', `?tab=${this.id.replace('tab-', '')}`);
            });
        });

        const actionModal = new bootstrap.Modal(document.getElementById('actionConfirmModal'));

        const modalColors = {
            success: { header: 'linear-gradient(135deg,#198754 0%,#20c997 100%)', icon: '#198754', bg: 'rgba(25,135,84,0.1)' },
            info: { header: 'linear-gradient(135deg,#0dcaf0 0%,#0d6efd 100%)', icon: '#0dcaf0', bg: 'rgba(13,202,240,0.1)' },
            danger: { header: 'linear-gradient(135deg,#dc3545 0%,#e83e8c 100%)', icon: '#dc3545', bg: 'rgba(220,53,69,0.1)' },
            warning: { header: 'linear-gradient(135deg,#ffc107 0%,#fd7e14 100%)', icon: '#ffc107', bg: 'rgba(255,193,7,0.1)' },
            secondary: { header: 'linear-gradient(135deg,#6c757d 0%,#495057 100%)', icon: '#6c757d', bg: 'rgba(108,117,125,0.1)' },
        };

        let pendingForm = null;
        let pendingAction = null;

        document.querySelectorAll('.btn-action-modal').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();

                const action = this.dataset.action;
                const title = this.dataset.title;
                const icon = this.dataset.icon || 'bi-gear';
                const color = this.dataset.color || 'success';
                const description = this.dataset.description || 'Voulez-vous continuer ?';
                const warning = this.dataset.warning || 'Cette action est irréversible.';

                const colors = modalColors[color] || modalColors.success;

                document.getElementById('modalHeader').style.background = colors.header;
                document.getElementById('modalIcon').className = `bi ${icon}`;
                document.getElementById('modalBigIcon').className = `bi ${icon}`;
                document.getElementById('modalIconContainer').style.background = colors.bg;
                document.getElementById('modalIconContainer').style.color = colors.icon;

                document.getElementById('modalTitle').textContent = title;
                document.getElementById('modalDescription').textContent = description;
                document.getElementById('modalWarning').textContent = warning;

                const confirmBtn = document.getElementById('modalConfirmBtn');
                confirmBtn.style.background = colors.header;

                const form = this.closest('form');
                pendingForm = form;
                pendingAction = action;

                actionModal.show();
            });
        });

        document.getElementById('modalConfirmBtn').addEventListener('click', function() {
            if (pendingAction === 'test-email' && pendingForm) {
                const emailInput = pendingForm.querySelector('input[name="email"]');
                if (emailInput && !emailInput.value) {
                    alert('Veuillez saisir une adresse email valide.');
                    return;
                }
            }

            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Exécution...';

            if (pendingForm) {
                pendingForm.submit();
            } else {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '';

                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = document.querySelector('meta[name="csrf-token"]')?.content || '';
                form.appendChild(csrf);

                let route = '';
                if (pendingAction === 'cache') route = '{{ route("admin.superadmin.parametres.clear-cache") }}';
                else if (pendingAction === 'optimize') route = '{{ route("admin.superadmin.parametres.optimize") }}';

                form.action = route;
                document.body.appendChild(form);
                form.submit();
            }
        });

        document.getElementById('actionConfirmModal').addEventListener('hidden.bs.modal', function() {
            const confirmBtn = document.getElementById('modalConfirmBtn');
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Confirmer';
            pendingForm = null;
            pendingAction = null;
        });

        document.querySelectorAll('.btn-submit-form').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');

                const colors = modalColors.success;

                document.getElementById('modalHeader').style.background = colors.header;
                document.getElementById('modalIcon').className = 'bi bi-check-lg';
                document.getElementById('modalBigIcon').className = 'bi bi-check-lg';
                document.getElementById('modalIconContainer').style.background = colors.bg;
                document.getElementById('modalIconContainer').style.color = colors.icon;

                document.getElementById('modalTitle').textContent = 'Enregistrer les modifications';
                document.getElementById('modalDescription').textContent = 'Les paramètres de cet onglet seront sauvegardés dans le système.';
                document.getElementById('modalWarning').textContent = 'Vérifiez les valeurs avant de confirmer.';

                const confirmBtn = document.getElementById('modalConfirmBtn');
                confirmBtn.style.background = colors.header;

                pendingForm = form;
                pendingAction = 'save';

                actionModal.show();
            });
        });
    });
</script>
@endpush
@endsection
