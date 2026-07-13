@extends('layouts.app')

@section('title', 'Nouvelle proposition - Super Admin')

@push('styles')
<style>
    .proposition-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08);
    }
</style>
@endpush

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0"><i class="bi bi-file-earmark-ruled me-2"></i>Nouvelle proposition</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.propositions.index') }}">Propositions</a></li>
                    <li class="breadcrumb-item active">Nouvelle</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="card proposition-card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-send me-2"></i>Créer une proposition pour une entreprise</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.superadmin.propositions.store') }}">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Entreprise <span class="text-danger">*</span></label>
                            <select name="entreprise_id" class="form-select @error('entreprise_id') is-invalid @enderror" required>
                                <option value="">Sélectionner une entreprise...</option>
                                @foreach($entreprises as $entreprise)
                                <option value="{{ $entreprise->id }}" {{ old('entreprise_id') == $entreprise->id ? 'selected' : '' }}>
                                    {{ $entreprise->nom_entreprise }} ({{ $entreprise->slug }})
                                </option>
                                @endforeach
                            </select>
                            @error('entreprise_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Type de service <span class="text-danger">*</span></label>
                            <select name="type_service" class="form-select @error('type_service') is-invalid @enderror" required>
                                <option value="">Sélectionner...</option>
                                @foreach(\App\Models\PropositionContrat::TYPES_SERVICE as $value => $label)
                                <option value="{{ $value }}" {{ old('type_service') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('type_service')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Nombre d'agents <span class="text-danger">*</span></label>
                            <input type="number" name="nombre_agents" class="form-control @error('nombre_agents') is-invalid @enderror"
                                value="{{ old('nombre_agents', 1) }}" min="1" required>
                            @error('nombre_agents')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Budget approximatif (CFA)</label>
                            <input type="number" name="budget_approx" class="form-control @error('budget_approx') is-invalid @enderror"
                                value="{{ old('budget_approx') }}" min="0" step="0.01">
                            @error('budget_approx')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Description des besoins</label>
                            <textarea name="description_besoins" class="form-control @error('description_besoins') is-invalid @enderror"
                                rows="4">{{ old('description_besoins') }}</textarea>
                            @error('description_besoins')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Notes internes</label>
                            <textarea name="notes" class="form-control @error('notes') is-invalid @enderror"
                                rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-send me-1"></i> Créer la proposition
                        </button>
                        <a href="{{ route('admin.superadmin.propositions.index') }}" class="btn btn-outline-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
