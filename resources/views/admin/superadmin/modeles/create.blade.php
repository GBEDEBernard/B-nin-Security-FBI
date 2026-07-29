@extends('layouts.app')

@section('title', 'Créer un Modèle - Super Admin')

@section('content')
    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0">
                    <i class="bi bi-file-earmark-plus text-success me-2"></i>
                    Créer un Modèle
                </h2>
                <p class="text-muted mb-0">Ajouter un nouveau modèle de document</p>
            </div>
            <a href="{{ route('admin.superadmin.modeles.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Retour
            </a>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Nouveau Modèle</h5>
            </div>
            <div class="card-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">Nom du modèle</label>
                        <input type="text" class="form-control" placeholder="ex: Contrat de Prestation v2">
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Type</label>
                            <select class="form-select">
                                <option value="contrat">Contrat</option>
                                <option value="facture">Facture</option>
                                <option value="bulletin">Bulletin de paie</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Catégorie</label>
                            <select class="form-select">
                                <option value="systeme">Système</option>
                                <option value="personnalise">Personnalisé</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contenu du modèle</label>
                        <textarea class="form-control" rows="10" placeholder="Contenu du modèle avec variables..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Créer
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
