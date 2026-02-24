@extends('layouts.app')

@section('title', 'Dashboard Super Admin - Benin Security')

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Dashboard Super Administrateur</h3>
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
                        <h3>{{ \App\Models\Entreprise::count() }}</h3>
                        <p>Entreprises</p>
                    </div>
                    <div class="icon">
                        <i class="bi bi-building-fill"></i>
                    </div>
                    <a href="{{ route('dashboard.superadmin.entreprises.index') }}" class="small-box-footer link-dark link-underline link-underline-opacity-0">
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
                        <h3>{{ \App\Models\User::where('is_superadmin', false)->count() }}</h3>
                        <p>Utilisateurs</p>
                    </div>
                    <div class="icon">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <a href="{{ route('dashboard.superadmin.utilisateurs.index') }}" class="small-box-footer link-dark link-underline link-underline-opacity-0">
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
                        <h3>{{ \App\Models\Client::count() }}</h3>
                        <p>Clients</p>
                    </div>
                    <div class="icon">
                        <i class="bi bi-person-vcard-fill"></i>
                    </div>
                    <a href="#" class="small-box-footer link-dark link-underline link-underline-opacity-0">
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
                        <h3>{{ \App\Models\ContratPrestation::where('statut', 'actif')->count() }}</h3>
                        <p>Contrats Actifs</p>
                    </div>
                    <div class="icon">
                        <i class="bi bi-file-earmark-check-fill"></i>
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
            <div class="col-md-12">
                <!--begin::Card-->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title">Entreprises de Sécurité</h5>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <!--begin::Table-->
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th>Téléphone</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(\App\Models\Entreprise::latest()->take(10)->get() as $entreprise)
                                <tr>
                                    <td>{{ $entreprise->nom }}</td>
                                    <td>{{ $entreprise->email }}</td>
                                    <td>{{ $entreprise->telephone }}</td>
                                    <td>
                                        @if($entreprise->est_active)
                                        <span class="badge bg-success">Actif</span>
                                        @else
                                        <span class="badge bg-danger">Inactif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('dashboard.superadmin.entreprises.show', $entreprise->id) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        <a href="{{ route('dashboard.superadmin.entreprises.edit', $entreprise->id) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Aucune entreprise trouvée</td>
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

        <!--begin::Row-->
        <div class="row">
            <!--begin::Col-->
            <div class="col-md-6">
                <!--begin::Card-->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title">Statistiques Globales</h5>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 text-center">
                                <div class="fs-1 text-primary">
                                    <i class="bi bi-person-badge-fill"></i>
                                </div>
                                <div class="fw-bold fs-4">{{ \App\Models\Employe::count() }}</div>
                                <div class="text-secondary">Employés</div>
                            </div>
                            <div class="col-6 text-center">
                                <div class="fs-1 text-success">
                                    <i class="bi bi-file-earmark-text-fill"></i>
                                </div>
                                <div class="fw-bold fs-4">{{ \App\Models\ContratPrestation::count() }}</div>
                                <div class="text-secondary">Contrats</div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-6 text-center">
                                <div class="fs-1 text-warning">
                                    <i class="bi bi-receipt"></i>
                                </div>
                                <div class="fw-bold fs-4">{{ \App\Models\Facture::count() }}</div>
                                <div class="text-secondary">Factures</div>
                            </div>
                            <div class="col-6 text-center">
                                <div class="fs-1 text-danger">
                                    <i class="bi bi-exclamation-triangle-fill"></i>
                                </div>
                                <div class="fw-bold fs-4">{{ \App\Models\Incident::count() }}</div>
                                <div class="text-secondary">Incidents</div>
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
                            <!-- Timeline items -->
                            <div class="time-label">
                                <span class="bg-success">Aujourd'hui</span>
                            </div>
                            <div>
                                <i class="fas fa-user-plus bg-info"></i>
                                <div class="timeline-item">
                                    <span class="time"><i class="fas fa-clock"></i> Il y a 2h</span>
                                    <h3 class="timeline-header">Nouvelle entreprise ajoutée</h3>
                                    <div class="timeline-body">
                                        Entreprise de Sécurité ABC
                                    </div>
                                </div>
                            </div>
                            <div>
                                <i class="fas fa-file-invoice bg-warning"></i>
                                <div class="timeline-item">
                                    <span class="time"><i class="fas fa-clock"></i> Il y a 5h</span>
                                    <h3 class="timeline-header">Nouveau contrat créé</h3>
                                    <div class="timeline-body">
                                        Contrat avec SBEE
                                    </div>
                                </div>
                            </div>
                            <div>
                                <i class="fas fa-check bg-success"></i>
                                <div class="timeline-item">
                                    <span class="time"><i class="fas fa-clock"></i> Hier</span>
                                    <h3 class="timeline-header">Utilisateur activé</h3>
                                    <div class="timeline-body">
                                        Jean Dupont - Agent
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