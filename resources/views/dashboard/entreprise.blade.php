@extends('layouts.app')

@section('title', 'Dashboard Entreprise - Benin Security')

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Dashboard Entreprise</h3>
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
                        <h3>{{ \App\Models\Employe::where('entreprise_id', auth()->user()->entreprise_id)->count() }}</h3>
                        <p>Employés</p>
                    </div>
                    <div class="icon">
                        <i class="bi bi-person-badge-fill"></i>
                    </div>
                    <a href="{{ route('dashboard.entreprise.employes.index') }}" class="small-box-footer link-dark link-underline link-underline-opacity-0">
                        Voir liste <i class="bi bi-arrow-right-circle-fill"></i>
                    </a>
                </div>
                <!--end::Small Box Widget 1-->
            </div>
            <!--end::Col-->

            <div class="col-lg-3 col-6">
                <!--begin::Small Box Widget 2-->
                <div class="small-box text-bg-success">
                    <div class="inner">
                        <h3>{{ \App\Models\Client::where('entreprise_id', auth()->user()->entreprise_id)->count() }}</h3>
                        <p>Clients</p>
                    </div>
                    <div class="icon">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <a href="{{ route('dashboard.entreprise.clients.index') }}" class="small-box-footer link-dark link-underline link-underline-opacity-0">
                        Voir liste <i class="bi bi-arrow-right-circle-fill"></i>
                    </a>
                </div>
                <!--end::Small Box Widget 2-->
            </div>
            <!--end::Col-->

            <div class="col-lg-3 col-6">
                <!--begin::Small Box Widget 3-->
                <div class="small-box text-bg-warning">
                    <div class="inner">
                        <h3>{{ \App\Models\ContratPrestation::where('entreprise_id', auth()->user()->entreprise_id)->where('statut', 'actif')->count() }}</h3>
                        <p>Contrats Actifs</p>
                    </div>
                    <div class="icon">
                        <i class="bi bi-file-earmark-check-fill"></i>
                    </div>
                    <a href="{{ route('dashboard.entreprise.contrats.index') }}" class="small-box-footer link-dark link-underline link-underline-opacity-0">
                        Voir liste <i class="bi bi-arrow-right-circle-fill"></i>
                    </a>
                </div>
                <!--end::Small Box Widget 3-->
            </div>
            <!--end::Col-->

            <div class="col-lg-3 col-6">
                <!--begin::Small Box Widget 4-->
                <div class="small-box text-bg-danger">
                    <div class="inner">
                        <h3>{{ \App\Models\Incident::where('entreprise_id', auth()->user()->entreprise_id)->whereDate('created_at', today())->count() }}</h3>
                        <p>Incidents Aujourd'hui</p>
                    </div>
                    <div class="icon">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <a href="#" class="small-box-footer link-dark link-underline link-underline-opacity-0">
                        Voir liste <i class="bi bi-arrow-right-circle-fill"></i>
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
                        <h5 class="card-title">Affectations du Jour</h5>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <!--begin::Table-->
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Agent</th>
                                    <th>Site</th>
                                    <th>Client</th>
                                    <th>Horaire</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(\App\Models\Affectation::where('entreprise_id', auth()->user()->entreprise_id)->with(['employe', 'siteClient', 'contratPrestation.client'])->latest()->take(10)->get() as $affectation)
                                <tr>
                                    <td>{{ $affectation->employe->nom ?? 'N/A' }} {{ $affectation->employe->prenom ?? '' }}</td>
                                    <td>{{ $affectation->siteClient->nom_site ?? 'N/A' }}</td>
                                    <td>{{ $affectation->contratPrestation->client->nom ?? 'N/A' }}</td>
                                    <td>{{ $affectation->heure_debut ?? 'N/A' }} - {{ $affectation->heure_fin ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-success">Actif</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Aucune affectation trouvée</td>
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
                        <h5 class="card-title">Statistiques Rapides</h5>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Pointages aujourd'hui</span>
                                <span class="fw-bold">{{ \App\Models\Pointage::where('entreprise_id', auth()->user()->entreprise_id)->whereDate('date_pointage', today())->count() }}</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Congés en attente</span>
                                <span class="fw-bold">{{ \App\Models\Conge::where('entreprise_id', auth()->user()->entreprise_id)->where('statut', 'en_attente')->count() }}</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Factures impayées</span>
                                <span class="fw-bold">{{ \App\Models\Facture::where('entreprise_id', auth()->user()->entreprise_id)->where('statut', 'impayee')->count() }}</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Agents disponibles</span>
                                <span class="fw-bold text-success">
                                    {{ \App\Models\Employe::where('entreprise_id', auth()->user()->entreprise_id)->where('disponible', true)->count() }}
                                </span>
                            </div>
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
                            <a href="{{ route('dashboard.entreprise.employes.create') }}" class="btn btn-primary">
                                <i class="bi bi-person-plus-fill me-1"></i> Ajouter un employé
                            </a>
                            <a href="{{ route('dashboard.entreprise.clients.create') }}" class="btn btn-success">
                                <i class="bi bi-person-vcard-fill me-1"></i> Ajouter un client
                            </a>
                            <a href="{{ route('dashboard.entreprise.affectations.index') }}" class="btn btn-warning">
                                <i class="bi bi-calendar-check-fill me-1"></i> Gérer les affectations
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
            <div class="col-md-6">
                <!--begin::Card-->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title">Évolution des Contrats</h5>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="fs-1 text-success">
                                    <i class="bi bi-check-circle-fill"></i>
                                </div>
                                <div class="fw-bold fs-4">
                                    {{ \App\Models\ContratPrestation::where('entreprise_id', auth()->user()->entreprise_id)->where('statut', 'actif')->count() }}
                                </div>
                                <div class="text-secondary small">Actifs</div>
                            </div>
                            <div class="col-4">
                                <div class="fs-1 text-warning">
                                    <i class="bi bi-clock-fill"></i>
                                </div>
                                <div class="fw-bold fs-4">
                                    {{ \App\Models\ContratPrestation::where('entreprise_id', auth()->user()->entreprise_id)->where('statut', 'en_cours')->count() }}
                                </div>
                                <div class="text-secondary small">En cours</div>
                            </div>
                            <div class="col-4">
                                <div class="fs-1 text-danger">
                                    <i class="bi bi-x-circle-fill"></i>
                                </div>
                                <div class="fw-bold fs-4">
                                    {{ \App\Models\ContratPrestation::where('entreprise_id', auth()->user()->entreprise_id)->where('statut', 'expire')->count() }}
                                </div>
                                <div class="text-secondary small">Expirés</div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!--end::Card-->
            </div>
            <!--end::Col-->

            <!--begin::Col-->
            <div class="col-md-6">
                <!--begin::Card-->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title">Activité Récente</h5>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="timeline timeline-secondary">
                            <div class="time-label">
                                <span class="bg-success">Aujourd'hui</span>
                            </div>
                            <div>
                                <i class="fas fa-clock-in bg-info"></i>
                                <div class="timeline-item">
                                    <span class="time"><i class="fas fa-clock"></i> Il y a 1h</span>
                                    <h3 class="timeline-header">Nouveau pointage</h3>
                                    <div class="timeline-body">
                                        Agent: Jean Koffi - Site SBEE
                                    </div>
                                </div>
                            </div>
                            <div>
                                <i class="fas fa-file-invoice bg-warning"></i>
                                <div class="timeline-item">
                                    <span class="time"><i class="fas fa-clock"></i> Il y a 3h</span>
                                    <h3 class="timeline-header">Facture créée</h3>
                                    <div class="timeline-body">
                                        Facture #2024-001 - SONEB
                                    </div>
                                </div>
                            </div>
                            <div>
                                <i class="fas fa-exclamation-triangle bg-danger"></i>
                                <div class="timeline-item">
                                    <span class="time"><i class="fas fa-clock"></i> Hier</span>
                                    <h3 class="timeline-header">Incident signalé</h3>
                                    <div class="timeline-body">
                                        Incident sur le site de la SBEE
                                    </div>
                                </div>
                            </div>
                        </div>
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
@endsection