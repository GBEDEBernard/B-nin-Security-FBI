@extends('layouts.app')

@section('title', 'Résultats de recherche - Benin Security')

@push('styles')
<style>
    .search-result-card {
        transition: all 0.2s ease;
        border-left: 3px solid transparent;
    }
    .search-result-card:hover {
        border-left-color: var(--bs-primary);
        transform: translateX(4px);
    }
    .search-result-card .result-icon {
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        font-size: 16px;
        flex-shrink: 0;
    }
    .search-section-title {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        opacity: 0.7;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center gap-3 mb-4">
        <div class="flex-grow-1">
            <form action="{{ route('search') }}" method="GET" class="d-flex align-items-center">
                <div class="input-group input-group-lg">
                    <span class="input-group-text bg-transparent border-end-0">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="q" class="form-control form-control-lg border-start-0 ps-0" placeholder="Rechercher..." value="{{ $query }}" autofocus>
                    <button class="btn btn-primary" type="submit">Rechercher</button>
                </div>
            </form>
        </div>
    </div>

    @if($total > 0)
    <p class="text-muted mb-4">{{ $total }} résultat(s) pour « {{ $query }} »</p>
    @endif

    @if($total === 0)
    <div class="text-center py-5">
        <i class="bi bi-search" style="font-size: 4rem; opacity: 0.2;"></i>
        <h4 class="mt-3">Aucun résultat trouvé</h4>
        <p class="text-muted">Aucun résultat pour « {{ $query }} ». Essayez d'autres termes.</p>
    </div>
    @else
    <div class="row g-4">
        @php $currentType = ''; @endphp
        @foreach($results as $item)
            @if($item['type'] !== $currentType)
                @php $currentType = $item['type']; @endphp
                <div class="col-12">
                    <h6 class="search-section-title mb-3">
                        <i class="bi bi-{{ $item['icone'] }} me-2"></i>{{ $item['type'] }}s
                    </h6>
                    <div class="list-group">
            @endif
            <a href="{{ $item['url'] }}" class="list-group-item list-group-item-action search-result-card d-flex align-items-center gap-3">
                <div class="result-icon" style="background-color: rgba(13, 110, 253, 0.1); color: #0d6efd;">
                    <i class="bi bi-{{ $item['icone'] }}"></i>
                </div>
                <div class="flex-grow-1 min-w-0">
                    <div class="fw-semibold text-truncate">{{ $item['titre'] }}</div>
                    <small class="text-muted">{{ $item['sous_titre'] }}</small>
                </div>
                @if($item['badge'])
                <span class="badge {{ $item['badge']['class'] }} rounded-pill">{{ $item['badge']['text'] }}</span>
                @endif
            </a>
            @php $next = $loop->iteration < $loop->count ? $results[$loop->index + 1] : null; @endphp
            @if(!$next || $next['type'] !== $currentType)
                    </div>
                </div>
            @endif
        @endforeach
    </div>
    @endif
</div>
@endsection