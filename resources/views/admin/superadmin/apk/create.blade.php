@extends('layouts.app')

@section('title', 'Upload APK - Super Admin')

@section('content')
    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0">
                    <i class="bi bi-upload text-primary me-2"></i>
                    Uploader une Nouvelle Version
                </h2>
                <p class="text-muted mb-0">Publier une nouvelle version de l'application mobile</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Nouvelle Version</h5>
            </div>
            <div class="card-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">Fichier APK</label>
                        <input type="file" class="form-control" accept=".apk">
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Numéro de version</label>
                            <input type="text" class="form-control" placeholder="ex: 1.1.0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Type de version</label>
                            <select class="form-select">
                                <option value="stable">Stable</option>
                                <option value="beta">Beta</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes de version</label>
                        <textarea class="form-control" rows="5" placeholder="Décrivez les changements..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-upload me-1"></i> Publier
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
