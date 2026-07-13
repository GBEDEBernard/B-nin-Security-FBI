@extends('layouts.app')

@section('title', "Version {$apkVersion->version} - Détails")

@push('styles')
<style>
    .stat-card {
        transition: transform 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-2px);
    }
    .changelog-timeline {
        position: relative;
        padding-left: 30px;
    }
    .changelog-timeline::before {
        content: '';
        position: absolute;
        left: 10px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: var(--bs-border-color);
    }
    .changelog-timeline .item {
        position: relative;
        padding: 8px 0;
    }
    .changelog-timeline .item::before {
        content: '';
        position: absolute;
        left: -24px;
        top: 14px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: var(--bs-primary);
        border: 2px solid #fff;
    }
</style>
@endpush

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('admin.superadmin.apk.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h2 class="mb-0">
                    <i class="bi bi-phone-fill text-primary me-2"></i>
                    Version {{ $apkVersion->version }}
                </h2>
                <span class="badge bg-{{ $apkVersion->type_color }} fs-6">{{ $apkVersion->type_label }}</span>
                <span class="badge bg-{{ $apkVersion->statut_color }} fs-6">
                    {{ $apkVersion->statut_label }}
                </span>
            </div>
            <p class="text-muted mb-0">Code version : <code>{{ $apkVersion->version_code }}</code></p>
        </div>
        <div class="btn-group">
            @if($apkVersion->fichier_path)
                <a href="{{ route('admin.superadmin.apk.download', $apkVersion) }}"
                   class="btn btn-primary">
                    <i class="bi bi-download me-1"></i> Télécharger
                </a>
            @endif
            @if(!$apkVersion->est_active)
                <form action="{{ route('admin.superadmin.apk.activate', $apkVersion) }}"
                      method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success"
                            onclick="return confirm('Activer cette version ?')">
                        <i class="bi bi-check-circle me-1"></i> Activer
                    </button>
                </form>
            @else
                <form action="{{ route('admin.superadmin.apk.deactivate', $apkVersion) }}"
                      method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-warning"
                            onclick="return confirm('Désactiver cette version ?')">
                        <i class="bi bi-x-circle me-1"></i> Désactiver
                    </button>
                </form>
            @endif
            <form action="{{ route('admin.superadmin.apk.destroy', $apkVersion) }}"
                  method="POST" class="d-inline">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger"
                        onclick="return confirm('Supprimer cette version ?')">
                    <i class="bi bi-trash me-1"></i> Supprimer
                </button>
            </form>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Informations</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="stat-card p-3 bg-light rounded-3">
                                <small class="text-muted d-block">Version</small>
                                <strong class="fs-5">{{ $apkVersion->version }}</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stat-card p-3 bg-light rounded-3">
                                <small class="text-muted d-block">Version Code</small>
                                <strong class="fs-5"><code>{{ $apkVersion->version_code }}</code></strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stat-card p-3 bg-light rounded-3">
                                <small class="text-muted d-block">Date de publication</small>
                                <strong class="fs-5">{{ $apkVersion->date_publication?->format('d/m/Y à H:i') ?? 'Non publiée' }}</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stat-card p-3 bg-light rounded-3">
                                <small class="text-muted d-block">Publié par</small>
                                <strong class="fs-5">{{ $apkVersion->publieur?->name ?? '—' }}</strong>
                            </div>
                        </div>
                        @if($apkVersion->est_obligatoire)
                            <div class="col-12">
                                <div class="alert alert-danger mb-0 py-2">
                                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                    Cette version est marquée comme <strong>mise à jour obligatoire</strong>.
                                </div>
                            </div>
                        @endif
                    </div>

                    @if($apkVersion->notes)
                        <div class="mt-4">
                            <h6 class="fw-bold">Notes de version</h6>
                            <p class="mb-0">{{ nl2br(e($apkVersion->notes)) }}</p>
                        </div>
                    @endif
                </div>
            </div>

            @if($apkVersion->changelog)
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0"><i class="bi bi-list-check me-2"></i>Changelog</h5>
                    </div>
                    <div class="card-body">
                        <div class="changelog-timeline">
                            @foreach($apkVersion->changelog as $item)
                                <div class="item">
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    {{ $item }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="bi bi-bar-chart me-2"></i>Statistiques</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex p-4 mb-2">
                            <i class="bi bi-download fs-2 text-primary"></i>
                        </div>
                        <h2 class="mb-0 fw-bold">{{ number_format($apkVersion->telechargements, 0, ',', ' ') }}</h2>
                        <small class="text-muted">Téléchargements</small>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <small class="text-muted">Taille du fichier</small>
                            <strong>{{ $apkVersion->taille_format }}</strong>
                        </div>
                        @if($apkVersion->checksum)
                            <div class="mt-2">
                                <small class="text-muted d-block">SHA256</small>
                                <code style="font-size: 0.65rem; word-break: break-all;">{{ $apkVersion->checksum }}</code>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="bi bi-tag me-2"></i>Métadonnées</h5>
                </div>
                <div class="card-body">
                    <dl class="mb-0">
                        <dt class="text-muted small">Créé le</dt>
                        <dd>{{ $apkVersion->created_at->format('d/m/Y H:i') }}</dd>
                        <dt class="text-muted small mt-2">Dernière modif.</dt>
                        <dd>{{ $apkVersion->updated_at->format('d/m/Y H:i') }}</dd>
                        @if($apkVersion->date_publication)
                            <dt class="text-muted small mt-2">Publié le</dt>
                            <dd>{{ $apkVersion->date_publication->format('d/m/Y H:i') }}</dd>
                        @endif
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
