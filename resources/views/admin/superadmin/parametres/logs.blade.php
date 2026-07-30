@extends('layouts.app')

@section('title', 'Logs Système - Super Admin')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0"><i class="bi bi-journal-text me-2"></i>Logs Système</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.parametres.index') }}">Paramètres</a></li>
                    <li class="breadcrumb-item active">Logs</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title fw-bold">
                            <i class="bi bi-terminal me-1"></i>
                            Dernières entrées du journal (100 lignes)
                        </h5>
                        <div class="card-tools">
                            <a href="{{ route('admin.superadmin.parametres.index', ['tab' => 'maintenance']) }}" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-arrow-left me-1"></i>Retour
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if(count($logs) > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="width: 40px;">#</th>
                                        <th>Contenu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($logs as $index => $line)
                                    <tr>
                                        <td class="text-muted small">{{ $index + 1 }}</td>
                                        <td>
                                            <code style="font-size: 0.75rem; word-break: break-all; white-space: pre-wrap;"
                                                class="{{ str_contains($line, 'ERROR') ? 'text-danger' : (str_contains($line, 'WARNING') ? 'text-warning' : '') }}">
                                                {{ $line }}
                                            </code>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-5">
                            <i class="bi bi-journal-check fs-1 text-muted"></i>
                            <p class="text-muted mt-2">Aucun log trouvé.</p>
                        </div>
                        @endif
                    </div>
                    <div class="card-footer text-end">
                        <form action="{{ route('admin.superadmin.parametres.clear-cache') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-info btn-sm text-white">
                                <i class="bi bi-arrow-repeat me-1"></i>Vider le cache
                            </button>
                        </form>
                        <a href="{{ route('admin.superadmin.parametres.index') }}" class="btn btn-success btn-sm">
                            <i class="bi bi-gear me-1"></i>Paramètres
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
