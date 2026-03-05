@extends('layouts.app')

@section('title', 'Modifier le client - Entreprise')

@push('styles')
<style>
    .form-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
    }

    .nav-tabs-custom {
        border-bottom: 2px solid #e9ecef;
        margin-bottom: 20px;
    }

    .nav-tabs-custom .nav-link {
        border: none;
        color: #6c757d;
        padding: 12px 20px;
        font-weight: 500;
        border-radius: 8px 8px 0 0;
        transition: all 0.2s;
    }

    .nav-tabs-custom .nav-link:hover {
        color: #198754;
        background: rgba(25, 135, 84, 0.05);
    }

    .nav-tabs-custom .nav-link.active {
        color: #198754;
        background: white;
        border: 2px solid #e9ecef;
        border-bottom: 2px solid white;
        margin-bottom: -2px;
    }

    .form-label {
        font-weight: 500;
        color: #495057;
        margin-bottom: 5px;
    }

    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #ced4da;
        padding: 10px 12px;
    }

    .form-control:focus, .form-select:focus {
        border-color: #198754;
        box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.15);
    }

    .section-title {
        font-size: 14px;
        font-weight: 600;
        color: #198754;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 15px;
        padding-bottom: 8px;
        border-bottom: 2px solid #198754;
    }

    .required-field::after {
        content: ' *';
        color: #dc3545;
    }

    .type-client-card {
        cursor: pointer;
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        transition: all 0.3s;
    }

    .type-client-card:hover {
        border-color: #198754;
        background: rgba(25, 135, 84, 0.05);
    }

    .type-client-card.selected {
        border-color: #198754;
        background: rgba(25, 135, 84, 0.1);
    }

    .type-client-card i {
        font-size: 2.5rem;
        margin-bottom: 10px;
        color: #6c757d;
    }

    .type-client-card.selected i {
        color: #198754;
    }

    .info-box {
        background: #e7f5ff;
        border-left: 4px solid #0d6efd;
        padding: 12px 15px;
        border-radius: 4px;
        margin-bottom: 20px;
    }

    .info-box i {
        color: #0d6efd;
        margin-right: 8px;
    }

    .avatar-lg {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 40px;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0">
                    <i class="bi bi-person-pen me-2 text-success"></i>
                    Modifier le Client
                </h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.entreprise.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.entreprise.clients.index') }}">Clients</a></li>
                    <li class="breadcrumb-item active">Modifier</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">

        <form action="{{ route('admin.entreprise.clients.update', $client->id) }}" method="POST" id="clientForm">
            @csrf
            @method('PUT')

            {{-- Header with Avatar --}}
            <div class="card form-card mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="avatar-lg bg-{{ $client->type_client == 'particulier' ? 'purple' : ($client->type_client == 'entreprise' ? 'primary' : 'warning') }} text-white">
                                @if($client->type_client == 'particulier')
                                    {{ strtoupper(substr($client->prenoms ?? 'N', 0, 1)) }}{{ strtoupper(substr($client->nom ?? 'A', 0, 1)) }}
                                @else
                                    {{ strtoupper(substr($client->raison_sociale ?? 'E', 0, 2)) }}
                                @endif
                            </div>
                        </div>
                        <div class="col">
                            <h4 class="mb-1">{{ $client->nom_affichage }}</h4>
                            <div class="text-muted">
                                <span class="badge badge-type badge-{{ $client->type_client }}">
                                    @switch($client->type_client)
                                        @case('particulier') Particulier @break
                                        @case('entreprise') Entreprise @break
                                        @case('institution') Institution @break
                                    @endswitch
                                </span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="est_actif" id="est_actif" value="1" {{ $client->est_actif ? 'checked' : '' }}>
                                <label class="form-check-label" for="est_actif">Client actif</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Type de client selection --}}
            <div class="info-box">
                <i class="bi bi-info-circle"></i>
                Les champs marqués d'un astérisque (*) sont obligatoires.
            </div>

            <div class="card form-card mb-4">
                <div class="card-body">
                    <div class="section-title">Type de client</div>
                    
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="type-client-card" onclick="selectType('particulier')" id="type-particulier">
                                <i class="bi bi-person"></i>
                                <div class="fw-semibold">Particulier</div>
                                <div class="text-muted small">Personne physique</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="type-client-card" onclick="selectType('entreprise')" id="type-entreprise">
                                <i class="bi bi-building"></i>
                                <div class="fw-semibold">Entreprise</div>
                                <div class="text-muted small">Personne morale</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="type-client-card" onclick="selectType('institution')" id="type-institution">
                                <i class="bi bi-bank"></i>
                                <div class="fw-semibold">Institution</div>
                                <div class="text-muted small">Organisation publique</div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="type_client" id="type_client" value="{{ old('type_client', $client->type_client) }}">
                </div>
            </div>

            {{-- Tabs --}}
            <ul class="nav nav-tabs-custom" id="clientTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="coordonnees-tab" data-bs-toggle="tab" data-bs-target="#coordonnees" type="button">
                        <i class="bi bi-person me-1"></i> Coordonnées
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="entreprise-tab" data-bs-toggle="tab" data-bs-target="#entreprise-info" type="button">
                        <i class="bi bi-building me-1"></i> Informations entreprise
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button">
                        <i class="bi bi-telephone me-1"></i> Contact principal
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="acces-tab" data-bs-toggle="tab" data-bs-target="#acces" type="button">
                        <i class="bi bi-shield-lock me-1"></i> Accès système
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="clientTabsContent">
                {{-- Tab 1: Coordonnées --}}
                <div class="tab-pane fade show active" id="coordonnees" role="tabpanel">
                    <div class="card form-card mb-4">
                        <div class="card-body">
                            <div class="section-title">Informations du client</div>
                            
                            {{-- Particulier fields --}}
                            <div id="particulier-fields">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label required-field">Nom</label>
                                        <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror" 
                                               value="{{ old('nom', $client->nom) }}">
                                        @error('nom')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label required-field">Prénoms</label>
                                        <input type="text" name="prenoms" class="form-control @error('prenoms') is-invalid @enderror" 
                                               value="{{ old('prenoms', $client->prenoms) }}">
                                        @error('prenoms')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Date de naissance</label>
                                        <input type="date" name="date_naissance" class="form-control" value="{{ old('date_naissance', $client->date_naissance?->format('Y-m-d')) }}">
                                    </div>
                                </div>
                            </div>

                            {{-- Entreprise/Institution fields --}}
                            <div id="entreprise-fields">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required-field">Raison sociale</label>
                                        <input type="text" name="raison_sociale" class="form-control @error('raison_sociale') is-invalid @enderror" 
                                               value="{{ old('raison_sociale', $client->raison_sociale) }}">
                                        @error('raison_sociale')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">NIF</label>
                                        <input type="text" name="nif" class="form-control" value="{{ old('nif', $client->nif) }}">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">RCCM</label>
                                        <input type="text" name="rc" class="form-control" value="{{ old('rc', $client->rc) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label required-field">Email</label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                           value="{{ old('email', $client->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Téléphone principal</label>
                                    <input type="text" name="telephone" class="form-control" 
                                           value="{{ old('telephone', $client->telephone) }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Téléphone secondaire</label>
                                    <input type="text" name="telephone_secondaire" class="form-control" 
                                           value="{{ old('telephone_secondaire', $client->telephone_secondaire) }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Adresse</label>
                                    <textarea name="adresse" class="form-control" rows="2">{{ old('adresse', $client->adresse) }}</textarea>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Ville</label>
                                    <input type="text" name="ville" class="form-control" value="{{ old('ville', $client->ville) }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Pays</label>
                                    <input type="text" name="pays" class="form-control" value="{{ old('pays', $client->pays) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tab 2: Informations entreprise --}}
                <div class="tab-pane fade" id="entreprise-info" role="tabpanel">
                    <div class="card form-card mb-4">
                        <div class="card-body">
                            <div class="section-title">Représentant légal</div>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Nom du représentant</label>
                                    <input type="text" name="representant_nom" class="form-control" value="{{ old('representant_nom', $client->representant_nom) }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Prénoms du représentant</label>
                                    <input type="text" name="representant_prenom" class="form-control" value="{{ old('representant_prenom', $client->representant_prenom) }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Fonction</label>
                                    <input type="text" name="representant_fonction" class="form-control" value="{{ old('representant_fonction', $client->representant_fonction) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tab 3: Contact principal --}}
                <div class="tab-pane fade" id="contact" role="tabpanel">
                    <div class="card form-card mb-4">
                        <div class="card-body">
                            <div class="section-title">Personne à contacter</div>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Nom complet</label>
                                    <input type="text" name="contact_principal_nom" class="form-control" value="{{ old('contact_principal_nom', $client->contact_principal_nom) }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Fonction</label>
                                    <input type="text" name="contact_principal_fonction" class="form-control" value="{{ old('contact_principal_fonction', $client->contact_principal_fonction) }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="contact_email" class="form-control" value="{{ old('contact_email', $client->contact_email) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tab 4: Accès système --}}
                <div class="tab-pane fade" id="acces" role="tabpanel">
                    <div class="card form-card mb-4">
                        <div class="card-body">
                            <div class="section-title">Modifier le mot de passe</div>
                            
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                Laissez les champs mot de passe vides si vous ne souhaitez pas modifier le mot de passe.
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nouveau mot de passe</label>
                                    <input type="password" name="password" class="form-control" id="password" placeholder="Nouveau mot de passe">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Confirmer le mot de passe</label>
                                    <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Confirmer le mot de passe">
                                </div>
                            </div>

                            @if($client->last_login_at)
                            <hr class="my-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="text-muted small">
                                        <i class="bi bi-clock me-1"></i>
                                        Dernière connexion : {{ $client->last_login_at->format('d/m/Y à H:i') }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-muted small">
                                        <i class="bi bi-geo-alt me-1"></i>
                                        IP : {{ $client->last_login_ip ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="card form-card mb-4">
                <div class="card-body d-flex justify-content-between">
                    <a href="{{ route('admin.entreprise.clients.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Retour
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i> Enregistrer les modifications
                    </button>
                </div>
            </div>
        </form>

    </div>
</div>

@push('scripts')
<script>
    function selectType(type) {
        document.querySelectorAll('.type-client-card').forEach(function(card) {
            card.classList.remove('selected');
        });
        document.getElementById('type-' + type).classList.add('selected');
        document.getElementById('type_client').value = type;

        var particulierFields = document.getElementById('particulier-fields');
        var entrepriseFields = document.getElementById('entreprise-fields');
        var entrepriseTab = document.getElementById('entreprise-tab');

        if (type === 'particulier') {
            particulierFields.style.display = 'block';
            entrepriseFields.style.display = 'none';
            entrepriseTab.style.display = 'none';
        } else {
            particulierFields.style.display = 'none';
            entrepriseFields.style.display = 'block';
            entrepriseTab.style.display = 'block';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        var currentType = "{{ old('type_client', $client->type_client) }}";
        selectType(currentType);
    });
</script>
@endpush
@endsection

