@extends('layouts.app')

@section('title', 'Gestion des Applications Mobiles - Super Admin')

@push('styles')
<style>
    .version-card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .version-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    .quick-action-card {
        cursor: pointer;
        transition: all 0.3s;
        border: 2px solid transparent;
    }
    .quick-action-card:hover {
        border-color: var(--bs-primary);
        transform: scale(1.02);
    }
    .qr-modal-body {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 350px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">
                <i class="bi bi-phone-fill text-dark me-2"></i>
                Gestion de l'Application Mobile
            </h2>
            <p class="text-muted mb-0">Gérez les versions et configurations de l'application APK</p>
        </div>
        <div>
            <a href="{{ route('admin.superadmin.apk.create') }}" class="btn btn-primary">
                <i class="bi bi-upload me-1"></i> Uploader une nouvelle version
            </a>
            <a href="{{ route('admin.superadmin.apk.configurations') }}" class="btn btn-outline-secondary ms-2">
                <i class="bi bi-gear me-1"></i> Configurations
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm version-card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 rounded-3 p-3 me-3">
                            <i class="bi bi-phone-fill fs-3 text-primary"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">Version actuelle</h5>
                            <small class="text-muted">Application Mobile Bénin Security</small>
                        </div>
                        @if($versionActive)
                            <span class="ms-auto badge bg-{{ $versionActive->type_color }} fs-6">
                                {{ $versionActive->type_label }}
                            </span>
                        @endif
                    </div>
                    <div class="row g-3">
                        <div class="col-md-3 col-6">
                            <small class="text-muted d-block">Version</small>
                            <strong class="fs-5">{{ $stats['version_active'] }}</strong>
                        </div>
                        <div class="col-md-3 col-6">
                            <small class="text-muted d-block">Date de publication</small>
                            <strong class="fs-5">
                                {{ $versionActive?->date_publication?->format('d/m/Y') ?? '—' }}
                            </strong>
                        </div>
                        <div class="col-md-3 col-6">
                            <small class="text-muted d-block">Total téléchargements</small>
                            <strong class="fs-5">{{ number_format($stats['total_telechargements'], 0, ',', ' ') }}</strong>
                        </div>
                        <div class="col-md-3 col-6">
                            <small class="text-muted d-block">Statut</small>
                            @if($versionActive)
                                <span class="badge bg-{{ $versionActive->statut_color }} fs-6">
                                    <i class="bi bi-check-circle me-1"></i>{{ $versionActive->statut_label }}
                                </span>
                            @else
                                <span class="badge bg-warning text-dark fs-6">Aucune version active</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center h-100 quick-action-card"
                 onclick="{{ $versionActive ? "window.location='" . route('admin.superadmin.apk.download', $versionActive) . "'" : '' }}">
                <div class="card-body py-4">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3">
                        <i class="bi bi-download fs-1 text-primary"></i>
                    </div>
                    <h5>Télécharger APK</h5>
                    <p class="text-muted small mb-3">
                        @if($versionActive)
                            Dernière version : {{ $versionActive->version }} ({{ $versionActive->taille_format }})
                        @else
                            Aucune version disponible
                        @endif
                    </p>
                    @if($versionActive)
                        <a href="{{ route('admin.superadmin.apk.download', $versionActive) }}" class="btn btn-primary">
                            <i class="bi bi-download me-1"></i> Télécharger
                        </a>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center h-100 quick-action-card" data-bs-toggle="modal" data-bs-target="#qrModal">
                <div class="card-body py-4">
                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3">
                        <i class="bi bi-qr-code fs-1 text-success"></i>
                    </div>
                    <h5>QR Code</h5>
                    <p class="text-muted small mb-3">Générer un QR code pour le téléchargement</p>
                    <button class="btn btn-success">
                        <i class="bi bi-qr-code me-1"></i> Générer
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center h-100 quick-action-card"
                 onclick="window.location='{{ route('admin.superadmin.apk.configurations') }}'">
                <div class="card-body py-4">
                    <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3">
                        <i class="bi bi-gear fs-1 text-warning"></i>
                    </div>
                    <h5>Configurations</h5>
                    <p class="text-muted small mb-3">Configurer les paramètres de l'application</p>
                    <a href="{{ route('admin.superadmin.apk.configurations') }}" class="btn btn-warning">
                        <i class="bi bi-gear me-1"></i> Configurer
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-clock-history me-2"></i>Historique des Versions
                </h5>
                <span class="badge bg-secondary">{{ $versions->total() }} version(s)</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Version</th>
                            <th>Date</th>
                            <th>Code</th>
                            <th>Type</th>
                            <th>Taille</th>
                            <th>Téléchargements</th>
                            <th>Statut</th>
                            <th>Publié par</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($versions as $version)
                            <tr>
                                <td>
                                    <strong>{{ $version->version }}</strong>
                                    @if($version->est_obligatoire)
                                        <span class="badge bg-danger ms-1" title="Mise à jour obligatoire">
                                            <i class="bi bi-exclamation-triangle-fill"></i>
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $version->date_publication?->format('d/m/Y') ?? '—' }}</td>
                                <td><code>{{ $version->version_code }}</code></td>
                                <td>
                                    <span class="badge bg-{{ $version->type_color }}">
                                        {{ $version->type_label }}
                                    </span>
                                </td>
                                <td>{{ $version->taille_format }}</td>
                                <td>
                                    <i class="bi bi-download me-1 text-muted"></i>
                                    {{ number_format($version->telechargements, 0, ',', ' ') }}
                                </td>
                                <td>
                                    <span class="badge bg-{{ $version->statut_color }}">
                                        {{ $version->statut_label }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $version->publieur?->name ?? '—' }}</small>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.superadmin.apk.show', $version) }}"
                                           class="btn btn-outline-primary" title="Détails">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if($version->fichier_path)
                                            <a href="{{ route('admin.superadmin.apk.download', $version) }}"
                                               class="btn btn-outline-secondary" title="Télécharger">
                                                <i class="bi bi-download"></i>
                                            </a>
                                        @endif
                                        @if(!$version->est_active && !$version->trashed())
                                            <form action="{{ route('admin.superadmin.apk.activate', $version) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-success"
                                                        title="Activer" onclick="return confirm('Activer cette version ?')">
                                                    <i class="bi bi-check-circle"></i>
                                                </button>
                                            </form>
                                        @elseif($version->est_active)
                                            <form action="{{ route('admin.superadmin.apk.deactivate', $version) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-warning"
                                                        title="Désactiver" onclick="return confirm('Désactiver cette version ?')">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.superadmin.apk.destroy', $version) }}"
                                              method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger"
                                                    title="Supprimer" onclick="return confirm('Supprimer cette version ? Cette action est réversible.')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                    Aucune version APK uploadée.
                                    <a href="{{ route('admin.superadmin.apk.create') }}" class="d-block mt-2">
                                        Uploader la première version
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($versions->hasPages())
            <div class="card-footer bg-white">
                {{ $versions->links() }}
            </div>
        @endif
    </div>
</div>

<div class="modal fade" id="qrModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-qr-code me-2"></i>QR Code de téléchargement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body qr-modal-body">
                <div id="qrLoading" class="text-center text-muted">
                    <div class="spinner-border mb-3" role="status"></div>
                    <p>Génération du QR code...</p>
                </div>
                <div id="qrResult" class="text-center d-none">
                    <img id="qrImage" src="" alt="QR Code" class="img-fluid" style="max-width: 280px;">
                    <p class="mt-2 mb-0 text-muted small" id="qrUrl"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <a id="downloadUrl" href="#" class="btn btn-primary d-none">
                    <i class="bi bi-download me-1"></i> Télécharger l'APK
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const qrModal = document.getElementById('qrModal');
    if (!qrModal) return;

    qrModal.addEventListener('show.bs.modal', function () {
        const loading = document.getElementById('qrLoading');
        const result = document.getElementById('qrResult');
        const img = document.getElementById('qrImage');
        const urlLabel = document.getElementById('qrUrl');
        const downloadBtn = document.getElementById('downloadUrl');

        loading.classList.remove('d-none');
        result.classList.add('d-none');
        downloadBtn.classList.add('d-none');

        @if($versionActive && $versionActive->fichier_url)
        const url = '{{ $versionActive->fichier_url }}';
        fetch('{{ route('admin.superadmin.apk.qrcode') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ url: window.location.origin + '/storage/apk/' + '{{ $versionActive->fichier_path }}' })
        })
        .then(res => res.json())
        .then(data => {
            loading.classList.add('d-none');
            result.classList.remove('d-none');
            img.src = data.qrcode;
            urlLabel.textContent = url;
            downloadBtn.href = '{{ route('admin.superadmin.apk.download', $versionActive) }}';
            downloadBtn.classList.remove('d-none');
        })
        .catch(() => {
            loading.innerHTML = '<p class="text-danger">Erreur de génération du QR code.</p>';
        });
        @else
        loading.innerHTML = '<p class="text-warning">Aucune version active disponible.</p>';
        @endif
    });
});
</script>
@endpush
