@extends('layouts.app')

@section('title', 'Dashboard Client - Benin Security')

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Mon Espace Client</h3>
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
                        <h3>{{ \App\Models\ContratPrestation::where('client_id', auth()->user()->client_id)->count() }}</h3>
                        <p>Mes Contrats</p>
                    </div>
                    <div class="icon">
                        <i class="bi bi-file-earmark-check-fill"></i>
                    </div>
                    <a href="{{ route('dashboard.client.contrats.index') }}" class="small-box-footer link-dark link-underline link-underline-opacity-0">
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
                        <h3>{{ \App\Models\Facture::where('client_id', auth()->user()->client_id)->where('statut', 'impayee')->count() }}</h3>
                        <p>Factures Impayées</p>
                    </div>
                    <div class="icon">
                        <i class="bi bi-receipt"></i>
                    </div>
                    <a href="{{ route('dashboard.client.factures.index') }}" class="small-box-footer link-dark link-underline link-underline-opacity-0">
                        Voir détail <i class="bi bi-arrow-right-circle-fill"></i>
                    </a>
                </div>
                <!--end::Small Box Widget 2-->
            </div>
            <!--end::Col-->

            <div class="col-lg-3 col-6">
                <!--begin::Small Box Widget 3-->
                <div class="small-box text-bg-warning">
                    <div class="inner">
                        <h3>{{ \App\Models\Incident::where('entreprise_id', auth()->user()->entreprise_id)->whereHas('affectation', function($q) {
                            $q->whereHas('contratPrestation', function($q2) {
                                $q2->where('client_id', auth()->user()->client_id);
                            });
                        })->whereDate('created_at', today())->count() }}</h3>
                        <p>Incidents Aujourd'hui</p>
                    </div>
                    <div class="icon">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <a href="{{ route('dashboard.client.incidents.index') }}" class="small-box-footer link-dark link-underline link-underline-opacity-0">
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
                        <h3>{{ \App\Models\SiteClient::where('client_id', auth()->user()->client_id)->count() }}</h3>
                        <p>Mes Sites</p>
                    </div>
                    <div class="icon">
                        <i class="bi bi-building-fill"></i>
                    </div>
                    <a href="#" class="small-box-footer link-dark link-underline link-underline-opacity-0">
                        Voir détail <i class="bi bi-arrow-right-circle-fill"></i>
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
                        <h5 class="card-title">Mes Contrats</h5>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <!--begin::Table-->
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>N° Contrat</th>
                                    <th>Type</th>
                                    <th>Date début</th>
                                    <th>Date fin</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(\App\Models\ContratPrestation::where('client_id', auth()->user()->client_id)->latest()->take(10)->get() as $contrat)
                                <tr>
                                    <td>{{ $contrat->numero_contrat ?? 'N/A' }}</td>
                                    <td>{{ $contrat->type_contrat ?? 'Prestation' }}</td>
                                    <td>{{ $contrat->date_debut ? \Carbon\Carbon::parse($contrat->date_debut)->format('d/m/Y') : 'N/A' }}</td>
                                    <td>{{ $contrat->date_fin ? \Carbon\Carbon::parse($contrat->date_fin)->format('d/m/Y') : 'N/A' }}</td>
                                    <td>
                                        @if($contrat->statut === 'actif')
                                        <span class="badge bg-success">Actif</span>
                                        @elseif($contrat->statut === 'en_cours')
                                        <span class="badge bg-warning">En cours</span>
                                        @elseif($contrat->statut === 'expire')
                                        <span class="badge bg-danger">Expiré</span>
                                        @else
                                        <span class="badge bg-secondary">{{ $contrat->statut }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        <a href="#" class="btn btn-sm btn-info">
                                            <i class="bi bi-download"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Aucun contrat trouvé</td>
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
                        <h5 class="card-title">Informations du Compte</h5>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @php
                        $client = auth()->user()->client;
                        @endphp

                        <div class="mb-3">
                            <label class="text-secondary small">Nom</label>
                            <div class="fw-bold">{{ $client->nom ?? 'N/A' }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="text-secondary small">Email</label>
                            <div>{{ auth()->user()->email }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="text-secondary small">Téléphone</label>
                            <div>{{ auth()->user()->telephone ?? 'N/A' }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="text-secondary small">Type de client</label>
                            <div>
                                @if($client && $client->type_client === 'entreprise')
                                <span class="badge bg-primary">Entreprise</span>
                                @else
                                <span class="badge bg-info">Particulier</span>
                                @endif
                            </div>
                        </div>
                        <hr>
                        <div class="d-grid">
                            <a href="#" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-pencil-fill me-1"></i> Modifier mes informations
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
                            <a href="{{ route('dashboard.client.incidents.create') }}" class="btn btn-danger">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i> Signaler un incident
                            </a>
                            <a href="{{ route('dashboard.client.factures.index') }}" class="btn btn-warning">
                                <i class="bi bi-receipt me-1"></i> Voir mes factures
                            </a>
                            <a href="#" class="btn btn-info">
                                <i class="bi bi-chat-dots-fill me-1"></i> Contacter le support
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
                        <h5 class="card-title">Mes Factures Récentes</h5>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <!--begin::Table-->
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>N° Facture</th>
                                    <th>Date</th>
                                    <th>Montant</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(\App\Models\Facture::where('client_id', auth()->user()->client_id)->latest()->take(5)->get() as $facture)
                                <tr>
                                    <td>{{ $facture->numero_facture ?? 'N/A' }}</td>
                                    <td>{{ $facture->date_facture ? \Carbon\Carbon::parse($facture->date_facture)->format('d/m/Y') : 'N/A' }}</td>
                                    <td>{{ number_format($facture->montant_total ?? 0, 0, ',', ' ') }} FCFA</td>
                                    <td>
                                        @if($facture->statut === 'payee')
                                        <span class="badge bg-success">Payée</span>
                                        @elseif($facture->statut === 'impayee')
                                        <span class="badge bg-danger">Impayée</span>
                                        @else
                                        <span class="badge bg-warning">En attente</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Aucune facture trouvée</td>
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
            <div class="col-md-6">
                <!--begin::Card-->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title">Historique des Incidents</h5>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="timeline timeline-secondary">
                            <div class="time-label">
                                <span class="bg-success">Récents</span>
                            </div>
                            @forelse(\App\Models\Incident::where('entreprise_id', auth()->user()->entreprise_id)->whereHas('affectation', function($q) {
                            $q->whereHas('contratPrestation', function($q2) {
                            $q2->where('client_id', auth()->user()->client_id);
                            });
                            })->latest()->take(5)->get() as $incident)
                            <div>
                                @if($incident->niveau === 'critique')
                                <i class="fas fa-exclamation-circle bg-danger"></i>
                                @elseif($incident->niveau === 'eleve')
                                <i class="fas fa-exclamation-triangle bg-warning"></i>
                                @else
                                <i class="fas fa-info-circle bg-info"></i>
                                @endif
                                <div class="timeline-item">
                                    <span class="time"><i class="fas fa-clock"></i> {{ $incident->created_at->diffForHumans() }}</span>
                                    <h3 class="timeline-header">{{ $incident->titre }}</h3>
                                    <div class="timeline-body">
                                        {{ Str::limit($incident->description, 100) }}
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center text-muted py-3">
                                <i class="bi bi-check-circle fs-1"></i>
                                <p>Aucun incident récent</p>
                            </div>
                            @endforelse
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