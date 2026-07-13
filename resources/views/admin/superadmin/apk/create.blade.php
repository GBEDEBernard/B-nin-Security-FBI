@extends('layouts.app')

@section('title', 'Uploader une version APK - Super Admin')

@push('styles')
<style>
    .drop-zone {
        border: 2px dashed var(--bs-border-color);
        border-radius: 12px;
        padding: 40px 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        background: var(--bs-light);
    }
    .drop-zone:hover,
    .drop-zone.drag-over {
        border-color: var(--bs-primary);
        background: rgba(13, 110, 253, 0.05);
    }
    .drop-zone.has-file {
        border-color: var(--bs-success);
        background: rgba(25, 135, 84, 0.05);
        border-style: solid;
    }
    .changelog-item {
        transition: all 0.2s;
    }
    .changelog-item:hover {
        background: rgba(0,0,0,0.02);
    }
</style>
@endpush

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">
                <i class="bi bi-upload text-primary me-2"></i>
                Uploader une nouvelle version
            </h2>
            <p class="text-muted mb-0">Publiez une nouvelle version de l'application mobile</p>
        </div>
        <a href="{{ route('admin.superadmin.apk.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Retour
        </a>
    </div>

    <form action="{{ route('admin.superadmin.apk.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
        @csrf

        <div class="row g-4">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Informations de la version</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Version <span class="text-danger">*</span></label>
                                <input type="text" name="version" class="form-control @error('version') is-invalid @enderror"
                                       placeholder="ex: 1.0.0" value="{{ old('version') }}" required>
                                <small class="text-muted">Format : X.Y.Z</small>
                                @error('version') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Version Code <span class="text-danger">*</span></label>
                                <input type="number" name="version_code"
                                       class="form-control @error('version_code') is-invalid @enderror"
                                       value="{{ old('version_code', $prochainCode) }}" required>
                                <small class="text-muted">Entier incrémental (ex: 101, 102...)</small>
                                @error('version_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Type <span class="text-danger">*</span></label>
                                <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                    <option value="stable" {{ old('type') === 'stable' ? 'selected' : '' }}>Stable</option>
                                    <option value="beta" {{ old('type') === 'beta' ? 'selected' : '' }}>Beta</option>
                                    <option value="alpha" {{ old('type') === 'alpha' ? 'selected' : '' }}>Alpha</option>
                                </select>
                                @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Notes de version</label>
                                <textarea name="notes" class="form-control @error('notes') is-invalid @enderror"
                                          rows="3" placeholder="Décrivez brièvement cette version...">{{ old('notes') }}</textarea>
                                @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-list-check me-2"></i>Changelog</h5>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="addChangelog">
                            <i class="bi bi-plus-lg"></i> Ajouter un changement
                        </button>
                    </div>
                    <div class="card-body" id="changelogContainer">
                        <div class="text-muted text-center py-3" id="changelogEmpty">
                            <i class="bi bi-pencil fs-2 d-block mb-2"></i>
                            Aucun changement renseigné. Cliquez sur "Ajouter un changement".
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0"><i class="bi bi-file-earmark-arrow-up me-2"></i>Fichier APK</h5>
                    </div>
                    <div class="card-body">
                        <div class="drop-zone" id="dropZone">
                            <input type="file" name="fichier" id="fileInput"
                                   class="d-none" accept=".apk" required>
                            <div id="dropContent">
                                <i class="bi bi-cloud-upload fs-1 text-primary mb-3 d-block"></i>
                                <h6>Déposez votre fichier APK ici</h6>
                                <p class="text-muted small mb-0">ou cliquez pour parcourir</p>
                                <p class="text-muted small mb-0">Poids max : 100 Mo</p>
                            </div>
                            <div id="fileInfo" class="d-none">
                                <i class="bi bi-file-earmark-zip fs-1 text-success mb-3 d-block"></i>
                                <h6 id="fileName" class="mb-1"></h6>
                                <p class="text-muted small mb-0" id="fileSize"></p>
                                <button type="button" class="btn btn-sm btn-outline-danger mt-2" id="removeFile">
                                    <i class="bi bi-x"></i> Changer
                                </button>
                            </div>
                        </div>
                        @error('fichier') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0"><i class="bi bi-gear me-2"></i>Options de publication</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check form-switch mb-3">
                            <input type="checkbox" name="publier" class="form-check-input" id="publierSwitch"
                                   value="1" {{ old('publier') ? 'checked' : '' }}>
                            <label class="form-check-label" for="publierSwitch">
                                Publier immédiatement
                            </label>
                            <small class="text-muted d-block">Active cette version dès l'upload</small>
                        </div>
                        <div class="form-check form-switch">
                            <input type="checkbox" name="est_obligatoire" class="form-check-input" id="obligatoireSwitch"
                                   value="1" {{ old('est_obligatoire') ? 'checked' : '' }}>
                            <label class="form-check-label" for="obligatoireSwitch">
                                Mise à jour obligatoire
                            </label>
                            <small class="text-muted d-block">Les utilisateurs devront mettre à jour</small>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 mt-4 btn-lg" id="submitBtn">
                    <i class="bi bi-cloud-upload me-2"></i> Uploader la version
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const dropContent = document.getElementById('dropContent');
    const fileInfo = document.getElementById('fileInfo');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    const removeFile = document.getElementById('removeFile');
    const submitBtn = document.getElementById('submitBtn');

    dropZone.addEventListener('click', () => fileInput.click());

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('drag-over');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('drag-over');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('drag-over');
        if (e.dataTransfer.files.length) {
            fileInput.files = e.dataTransfer.files;
            handleFile(e.dataTransfer.files[0]);
        }
    });

    fileInput.addEventListener('change', function() {
        if (this.files.length) handleFile(this.files[0]);
    });

    function handleFile(file) {
        if (!file.name.endsWith('.apk')) {
            alert('Veuillez sélectionner un fichier .apk');
            fileInput.value = '';
            return;
        }
        if (file.size > 1024 * 1024 * 100) {
            alert('Le fichier ne doit pas dépasser 100 Mo');
            fileInput.value = '';
            return;
        }
        dropContent.classList.add('d-none');
        fileInfo.classList.remove('d-none');
        dropZone.classList.add('has-file');
        fileName.textContent = file.name;
        fileSize.textContent = formatSize(file.size);
    }

    removeFile.addEventListener('click', function(e) {
        e.stopPropagation();
        fileInput.value = '';
        fileInfo.classList.add('d-none');
        dropContent.classList.remove('d-none');
        dropZone.classList.remove('has-file');
    });

    function formatSize(bytes) {
        const units = ['o', 'Ko', 'Mo'];
        let i = 0;
        let size = bytes;
        while (size >= 1024 && i < 2) { size /= 1024; i++; }
        return size.toFixed(2) + ' ' + units[i];
    }

    // Changelog dynamic repeater
    let changelogCount = 0;
    const addBtn = document.getElementById('addChangelog');
    const container = document.getElementById('changelogContainer');
    const empty = document.getElementById('changelogEmpty');

    addBtn.addEventListener('click', () => {
        empty.classList.add('d-none');
        const div = document.createElement('div');
        div.className = 'input-group mb-2 changelog-item';
        div.innerHTML = `
            <input type="text" name="changelog[]" class="form-control"
                   placeholder="ex: Correction d'un crash sur l'écran d'accueil" required>
            <button type="button" class="btn btn-outline-danger remove-changelog">
                <i class="bi bi-trash"></i>
            </button>
        `;
        container.appendChild(div);
        changelogCount++;
        div.querySelector('.remove-changelog').addEventListener('click', () => {
            div.remove();
            changelogCount--;
            if (changelogCount === 0) empty.classList.remove('d-none');
        });
    });

    // Submit loading state
    submitBtn.addEventListener('click', function(e) {
        if (!fileInput.files.length) {
            e.preventDefault();
            alert('Veuillez sélectionner un fichier APK.');
            return;
        }
        this.disabled = true;
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Upload en cours...';
    });
});
</script>
@endpush
