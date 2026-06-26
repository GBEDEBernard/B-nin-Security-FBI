@extends('layouts.app')

@section('title', 'Abonnement requis')

@section('content')
<div class="container-fluid p-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card text-center">
                <div class="card-body py-5">
                    <div class="mb-4">
                        <i class="bi bi-credit-card-2-front-fill text-warning" style="font-size: 4rem;"></i>
                    </div>
                    <h3 class="mb-3">Abonnement requis</h3>
                    <p class="text-muted mb-4">
                        Votre entreprise n'a pas d'abonnement actif. Veuillez contacter l'administrateur pour souscrire à un plan d'abonnement.
                    </p>
                    <a href="{{ route('login') }}" class="btn btn-primary">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Retour à la connexion
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
