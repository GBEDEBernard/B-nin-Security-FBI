@extends('layouts.app')

@section('title', 'Configurations APK - Super Admin')

@section('content')
    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0">
                    <i class="bi bi-gear text-secondary me-2"></i>
                    Configurations APK
                </h2>
                <p class="text-muted mb-0">Paramètres de configuration de l'application mobile</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Paramètres Généraux</h5>
            </div>
            <div class="card-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">URL de l'API</label>
                        <input type="text" class="form-control" value="{{ config('app.url') }}/api">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Version minimale requise</label>
                        <input type="text" class="form-control" value="1.0.0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Délai de pointage (minutes)</label>
                        <input type="number" class="form-control" value="30">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Distance max. pointage (mètres)</label>
                        <input type="number" class="form-control" value="100">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="maintenance" checked>
                        <label class="form-check-label" for="maintenance">Mode maintenance</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>
@endsection
