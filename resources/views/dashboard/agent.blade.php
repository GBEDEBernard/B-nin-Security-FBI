@extends('layouts.app')

@section('title', 'Dashboard Agent - Benin Security')

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Mon Dashboard</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
            </div>
        </div>
        <!--end::Row-->
    </div>
    <!--end::Container-->
</div>
<!--end::App Content Header-->

<!--begin::App Content-->
<div class="app-content">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <!--begin::Col-->
            <div class="col-lg-3 col-6">
                <!--begin::Small Box Widget 1-->
                <div class="small-box text-bg-primary">
                    <div class="inner">
                        <h3>{{ \App\Models\Affectation::whereHas('employe', function($q) { $q->where('user_id', auth()->id()); })->whereDate('date_debut', '<=', now())->whereDate('date_fin', '>=', now())->count() }}</h3>
                        <p>Mes Missions</p>
                    </div>
                    <div class="icon">
                        <i class="bi bi-briefcase-fill"></i>
                    </div>
                    <a href="{{ route('dashboard.agent.missions.index') }}" class="small-box-footer link-dark link-underline link-underline-opacity-0">
                        Voir détail <i class="bi bi-arrow-right-circle-fill"></i>
                    </a>
                </div>
                <!--end::Small Box Widget 1-->
            </div>
            <!--end::Col-->

            <div class="col-lg-3 col-6">
                <!--begin::Small Box Widget 2-->
                <div class="small-box text-bg-success">
                    <div class="inner">
                        <h3>{{ \App\Models\Pointage::whereHas('employe', function($q) { $q->where('user_id', auth()->id()); })->whereDate('date_pointage', today())->count() }}</h3>
                        <p>Pointages Aujourd'hui</p>
                    </div>
                    <div class="icon">
                        <i class="bi bi-clock-fill"></i>
                    </div>
                    <a href="{{ route('dashboard.agent.pointages.index') }}" class="small-box-footer link-dark link-underline link-underline-opacity-0">
                        Voir historique <i class="bi bi-arrow-right-circle-fill"></i>
                    </a>
                </div>
                <!--end::Small Box Widget 2-->
            </div>
            <!--end::Col-->

            <div class="col-lg-3 col-6">
                <!--begin::Small Box Widget 3-->
                <div class="small-box text-bg-warning">
                    <div class="inner">
                        <h3>{{ \App\Models\Conge::whereHas('employe', function($q) { $q->where('user_id', auth()->id()); })->where('statut', 'en_attente')->count() }}</h3>
                        <p>Congés en Attente</p>
                    </div>
                    <div class="icon">
                        <i class="bi bi-calendar-event-fill"></i>
                    </div>
                    <a href="{{ route('dashboard.agent.conges.index') }}" class="small-box-footer link-dark link-underline link-underline-opacity-0">
                        Voir détail <i class="bi bi-arrow-right-circle-fill"></i>
                    </a>
                </div>
                <!--end::Small Box Widget 3-->
            </div>
            <!--end::Col-->

            <div class="col-lg-3 col-6">
                <!--begin::Small Box Widget 4-->
                <div class="small-box text-bg-danger">
                    <div class="inner">
                        <h3>{{ \App\Models\Incident::whereHas('employe', function($q) { $q->where('user_id', auth()->id()); })->whereDate('created_at', today())->count() }}</h3>
                        <p>Incidents Aujourd'hui</p>
                    </div>
                    <div class="icon">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <a href="#" class="small-box-footer link-dark link-underline link-underline-opacity-0">
                        Signaler <i class="bi bi-arrow-right-circle-fill"></i>
                    </a>
                </div>
                <!--end::Small Box Widget 4-->
            </div>
            <!--end::Col-->
        </div>
        <!--end::Row-->

        <!--begin::Row-->
        <div class="row">
            <!--begin::Col-->
            <div class="col-md-8">
                <!--begin::Card-->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title">Mes Missions en Cours</h5>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <!--begin::Table-->
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Site</th>
                                    <th>Client</th>
                                    <th>Date</th>
                                    <th>Horaire</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(\App\Models\Affectation::whereHas('employe', function($q) { $q->where('user_id', auth()->id()); })->with(['siteClient', 'contratPrestation.client'])->whereDate('date_debut', '<=', now())->whereDate('date_fin', '>=', now())->latest()->take(10)->get() as $affectation)
                                    <tr>
                                        <td>{{ $affectation->siteClient->nom_site ?? 'N/A' }}</td>
                                        <td>{{ $affectation->contratPrestation->client->nom ?? 'N/A' }}</td>
                                        <td>{{ $affectation->date_debut ? \Carbon\Carbon::parse($affectation->date_debut)->format('d/m/Y') : 'N/A' }}</td>
                                        <td>{{ $affectation->heure_debut ?? 'N/A' }} - {{ $affectation->heure_fin ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-success">En cours</span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Aucune mission en cours</td>
                                    </tr>
                                    @endforelse
                            </tbody>
                        </table>
                        <!--end::Table-->
                    </div>
                    <!-- /.card-body -->
                </div>
                <!--end::Card-->
            </div>
            <!--end::Col-->

            <!--begin::Col-->
            <div class="col-md-4">
                <!--begin::Card-->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title">Mon Solde de Congés</h5>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @php
                        $employe = auth()->user()->employe;
                        $soldeConge = $employe ? $employe->soldeConge : null;
                        @endphp

                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Solde actuel</span>
                                <span class="fw-bold text-success">{{ $soldeConge ? $soldeConge->jours_restants : 0 }} jours</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Taken this year</span>
                                <span class="fw-bold">{{ $soldeConge ? $soldeConge->jours_pris : 0 }} jours</span>
                            </div>
                        </div>
                        <hr>
                        <div class="d-grid">
                            <a href="{{ route('dashboard.agent.conges.index') }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-plus-circle me-1"></i> Demander un congés
                            </a>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!--end::Card-->

                <!--begin::Card-->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title">Actions Rapides</h5>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#pointageModal">
                                <i class="bi bi-check-circle-fill me-1"></i> Pointer maintenant
                            </button>
                            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#incidentModal">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i> Signaler un incident
                            </button>
                            <a href="{{ route('dashboard.agent.pointages.index') }}" class="btn btn-primary">
                                <i class="bi bi-clock-history me-1"></i> Mon historique de pointage
                            </a>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!--end::Card-->
            </div>
            <!--end::Col-->
        </div>
        <!--end::Row-->

        <!--begin::Row-->
        <div class="row">
            <!--begin::Col-->
            <div class="col-md-12">
                <!--begin::Card-->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title">Mon Historique de Pointage</h5>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <!--begin::Table-->
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Site</th>
                                    <th>Arrivée</th>
                                    <th>Départ</th>
                                    <th>Durée</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(\App\Models\Pointage::whereHas('employe', function($q) { $q->where('user_id', auth()->id()); })->with('affectation.siteClient')->latest()->take(10)->get() as $pointage)
                                <tr>
                                    <td>{{ $pointage->date_pointage ? \Carbon\Carbon::parse($pointage->date_pointage)->format('d/m/Y') : 'N/A' }}</td>
                                    <td>{{ $pointage->affectation->siteClient->nom_site ?? 'N/A' }}</td>
                                    <td>{{ $pointage->heure_arrivee ?? 'N/A' }}</td>
                                    <td>{{ $pointage->heure_depart ?? 'N/A' }}</td>
                                    <td>{{ $pointage->duree ?? 'N/A' }}</td>
                                    <td>
                                        @if($pointage->statut === 'valide')
                                        <span class="badge bg-success">Validé</span>
                                        @elseif($pointage->statut === 'en_attente')
                                        <span class="badge bg-warning">En attente</span>
                                        @else
                                        <span class="badge bg-danger">Rejeté</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Aucun pointage trouvé</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <!--end::Table-->
                    </div>
                    <!-- /.card-body -->
                </div>
                <!--end::Card-->
            </div>
            <!--end::Col-->
        </div>
        <!--end::Row-->
    </div>
    <!--end::Container-->
</div>
<!--end::App Content-->

<!-- Modal Pointage -->
<div class="modal fade" id="pointageModal" tabindex="-1" aria-labelledby="pointageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pointageModalLabel">Pointer mon arrivée</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sur le site? Cliquez sur pointer pour enregistrer votre arrivée.</p>
                <form action="#" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="site" class="form-label">Site</label>
                        <select class="form-select" id="site" required>
                            <option value="">Sélectionner un site</option>
                            @foreach(\App\Models\Affectation::whereHas('employe', function($q) { $q->where('user_id', auth()->id()); })->with('siteClient')->get() as $affectation)
                            <option value="{{ $affectation->siteClient->id }}">{{ $affectation->siteClient->nom_site }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="heure_arrivee" class="form-label">Heure d'arrivée</label>
                        <input type="time" class="form-control" id="heure_arrivee" value="{{ now()->format('H:i') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="observation" class="form-label">Observation</label>
                        <textarea class="form-control" id="observation" rows="3" placeholder="Observation optionnelle..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-success">Pointer</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Incident -->
<div class="modal fade" id="incidentModal" tabindex="-1" aria-labelledby="incidentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="incidentModalLabel">Signaler un incident</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="#" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="titre" class="form-label">Titre de l'incident</label>
                        <input type="text" class="form-control" id="titre" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="niveau" class="form-label">Niveau de gravité</label>
                        <select class="form-select" id="niveau" required>
                            <option value="faible">Faible</option>
                            <option value="moyen">Moyen</option>
                            <option value="eleve">Élevé</option>
                            <option value="critique">Critique</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger">Signaler</button>
            </div>
        </div>
    </div>
</div>
@endsection