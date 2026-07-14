@extends('layouts.app')

@section('title', 'Logs Applicatifs - Super Admin')

@push('styles')
<style>
    /* ==================================================================
       DESIGN TOKENS — le terminal de logs garde une esthétique "code editor"
       dans les deux thèmes, mais claire en light et sombre en dark, pour
       rester cohérent avec le reste du panneau super admin.
       Le thème est piloté par data-bs-theme="dark" sur <html> (Bootstrap 5.3).
       ================================================================== */
    :root,
    [data-bs-theme="light"] {
        --logs-page-bg: #f4f6f9;
        --logs-bg: #ffffff;
        --logs-header-bg: #eef1f6;
        --logs-border: #dfe3ea;
        --logs-text: #2c2f3a;
        --logs-text-dim: #7a8296;
        --logs-timestamp: #2f6fd9;
        --logs-scrollbar-track: #eef1f6;
        --logs-scrollbar-thumb: #c7cddb;
        --logs-hover: rgba(47, 111, 217, 0.06);
        --logs-stat-bg: #ffffff;
        --logs-stat-border: #dfe3ea;
        --logs-search-bg: #ffffff;
        --logs-search-text: #2c2f3a;
        --logs-modal-bg: #ffffff;
        --logs-error: #c72e5c;
        --logs-error-bg: rgba(199, 46, 92, 0.1);
        --logs-warning: #b8790a;
        --logs-warning-bg: rgba(184, 121, 10, 0.12);
        --logs-info: #2f6fd9;
        --logs-info-bg: rgba(47, 111, 217, 0.1);
        --logs-debug: #6c7086;
        --logs-debug-bg: rgba(108, 112, 134, 0.1);
        --logs-vendor: #b3b8c4;
        --logs-file: #2f9e5c;
        --logs-msg: #b8324f;
        --logs-shadow: 0 2px 12px rgba(15, 23, 42, 0.06);
    }

    [data-bs-theme="dark"] {
        --logs-page-bg: #10121a;
        --logs-bg: #1e1e2e;
        --logs-header-bg: #181825;
        --logs-border: #313244;
        --logs-text: #cdd6f4;
        --logs-text-dim: #6c7086;
        --logs-timestamp: #89b4fa;
        --logs-scrollbar-track: #181825;
        --logs-scrollbar-thumb: #45475a;
        --logs-hover: rgba(69, 71, 90, 0.3);
        --logs-stat-bg: #1e1e2e;
        --logs-stat-border: #313244;
        --logs-search-bg: #181825;
        --logs-search-text: #cdd6f4;
        --logs-modal-bg: #11111b;
        --logs-error: #f38ba8;
        --logs-error-bg: rgba(243, 139, 168, 0.2);
        --logs-warning: #f9e2af;
        --logs-warning-bg: rgba(249, 226, 175, 0.2);
        --logs-info: #89b4fa;
        --logs-info-bg: rgba(137, 180, 250, 0.2);
        --logs-debug: #a6adc8;
        --logs-debug-bg: rgba(166, 173, 200, 0.2);
        --logs-vendor: #45475a;
        --logs-file: #a6e3a1;
        --logs-msg: #f5e0dc;
        --logs-shadow: 0 2px 14px rgba(0, 0, 0, 0.4);
    }

    body {
        background-color: var(--logs-page-bg);
    }

    .log-container {
        background: var(--logs-bg);
        border: 1px solid var(--logs-border);
        border-radius: 12px;
        overflow: hidden;
        font-family: 'SF Mono', 'Fira Code', 'Consolas', monospace;
        font-size: 0.8rem;
        line-height: 1.6;
        box-shadow: var(--logs-shadow);
    }
    .log-header {
        background: var(--logs-header-bg);
        padding: 0.75rem 1.25rem;
        color: var(--logs-text);
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid var(--logs-border);
    }
    .log-header .text-secondary {
        color: var(--logs-text-dim) !important;
    }
    .log-body {
        padding: 0;
        max-height: 70vh;
        overflow-y: auto;
    }
    .log-body::-webkit-scrollbar {
        width: 8px;
    }
    .log-body::-webkit-scrollbar-track {
        background: var(--logs-scrollbar-track);
    }
    .log-body::-webkit-scrollbar-thumb {
        background: var(--logs-scrollbar-thumb);
        border-radius: 4px;
    }
    .log-entry {
        padding: 0.5rem 1.25rem;
        border-bottom: 1px solid var(--logs-border);
        cursor: pointer;
        transition: background 0.15s ease;
        position: relative;
    }
    .log-entry:hover {
        background: var(--logs-hover);
    }
    .log-entry:hover .view-btn {
        opacity: 1;
    }
    .log-entry .timestamp {
        color: var(--logs-timestamp);
    }
    .log-entry .level-badge {
        font-weight: 700;
        padding: 0.1rem 0.4rem;
        border-radius: 4px;
        font-size: 0.7rem;
        text-transform: uppercase;
        display: inline-block;
        min-width: 60px;
        text-align: center;
    }
    .level-error .level-badge { background: var(--logs-error-bg); color: var(--logs-error); }
    .level-warning .level-badge { background: var(--logs-warning-bg); color: var(--logs-warning); }
    .level-info .level-badge { background: var(--logs-info-bg); color: var(--logs-info); }
    .level-debug .level-badge { background: var(--logs-debug-bg); color: var(--logs-debug); }
    .log-entry .message {
        color: var(--logs-text);
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .log-entry .trace-preview {
        font-size: 0.75rem;
        color: var(--logs-text-dim);
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin-top: 0.15rem;
    }
    .view-btn {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        opacity: 0;
        transition: opacity 0.2s ease;
        background: var(--logs-hover);
        border: none;
        color: var(--logs-text);
        width: 28px;
        height: 28px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
    }
    .view-btn:hover {
        background: var(--logs-scrollbar-thumb);
        color: var(--logs-text);
    }
    .empty-logs {
        padding: 3rem 1.25rem;
        text-align: center;
        color: var(--logs-text-dim);
    }
    .empty-logs i {
        font-size: 3rem;
        display: block;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .stat-item {
        background: var(--logs-stat-bg);
        border-radius: 10px;
        padding: 1rem;
        text-align: center;
        border: 1px solid var(--logs-stat-border);
        transition: box-shadow 0.2s ease, transform 0.2s ease;
    }
    .stat-item:hover {
        box-shadow: var(--logs-shadow);
        transform: translateY(-1px);
    }
    .stat-item .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        font-family: inherit;
    }
    .stat-item .stat-label {
        font-size: 0.75rem;
        color: var(--logs-text-dim);
        margin-top: 0.25rem;
    }
    .stat-error .stat-value { color: var(--logs-error); }
    .stat-warning .stat-value { color: var(--logs-warning); }
    .stat-info .stat-value { color: var(--logs-info); }
    .stat-total .stat-value { color: var(--logs-text); }
    .log-actions {
        display: flex;
        gap: 0.5rem;
    }

    .modal-log {
        background: var(--logs-bg);
        border-radius: 8px;
        padding: 0;
        overflow: hidden;
    }
    .modal-log-header {
        background: var(--logs-header-bg);
        padding: 0.75rem 1rem;
        border-bottom: 1px solid var(--logs-border);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .modal-log-body {
        padding: 1rem;
        max-height: 55vh;
        overflow-y: auto;
        color: var(--logs-text);
        font-size: 0.78rem;
        line-height: 1.7;
        white-space: pre-wrap;
        word-break: break-all;
    }
    .modal-log-body::-webkit-scrollbar {
        width: 6px;
    }
    .modal-log-body::-webkit-scrollbar-track {
        background: var(--logs-scrollbar-track);
    }
    .modal-log-body::-webkit-scrollbar-thumb {
        background: var(--logs-scrollbar-thumb);
        border-radius: 3px;
    }
    .modal-log-body .stack-line {
        color: var(--logs-text-dim);
    }
    .modal-log-body .stack-line.file {
        color: var(--logs-file);
    }
    .modal-log-body .stack-line.vendor {
        color: var(--logs-vendor);
    }
    .copy-btn {
        background: var(--logs-hover);
        border: 1px solid var(--logs-border);
        color: var(--logs-text);
        padding: 0.25rem 0.6rem;
        border-radius: 4px;
        font-size: 0.7rem;
        cursor: pointer;
        transition: background 0.2s;
    }
    .copy-btn:hover {
        background: var(--logs-scrollbar-thumb);
    }

    .search-bar {
        background: var(--logs-search-bg);
        border: 1px solid var(--logs-border);
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
        color: var(--logs-search-text);
        font-family: inherit;
        font-size: 0.8rem;
        width: 100%;
        transition: border-color 0.2s, background-color 0.2s;
    }
    .search-bar:focus {
        outline: none;
        border-color: var(--logs-info);
    }
    .search-bar::placeholder {
        color: var(--logs-text-dim);
    }
    .filter-chip {
        background: var(--logs-search-bg);
        border: 1px solid var(--logs-border);
        color: var(--logs-text);
        padding: 0.3rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        cursor: pointer;
        transition: all 0.2s;
        font-family: inherit;
    }
    .filter-chip:hover {
        border-color: var(--logs-text-dim);
    }
    .filter-chip.active {
        background: var(--logs-scrollbar-thumb);
        border-color: var(--logs-info);
        color: var(--logs-info);
    }
    .filter-chip.active-error {
        background: var(--logs-error-bg);
        border-color: var(--logs-error);
        color: var(--logs-error);
    }
    .filter-chip.active-warning {
        background: var(--logs-warning-bg);
        border-color: var(--logs-warning);
        color: var(--logs-warning);
    }
    .filter-chip.active-info {
        background: var(--logs-info-bg);
        border-color: var(--logs-info);
        color: var(--logs-info);
    }
    .search-clear {
        background: none;
        border: none;
        color: var(--logs-text-dim);
        position: absolute;
        right: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        padding: 0.25rem;
        display: none;
    }
    .search-clear:hover {
        color: var(--logs-text);
    }
    .search-wrapper {
        position: relative;
        flex: 1;
    }
    .search-icon {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--logs-text-dim);
        pointer-events: none;
    }
    .search-bar {
        padding-left: 2.25rem;
        padding-right: 2rem;
    }
    .filter-bar {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    .log-entry.hidden {
        display: none;
    }
    .result-count {
        font-size: 0.75rem;
        color: var(--logs-text-dim);
        white-space: nowrap;
    }

    /* Modal Bootstrap : on aligne les bords/texte sur nos tokens */
    #logDetailModal .modal-content {
        background: var(--logs-modal-bg);
        border: 1px solid var(--logs-border);
    }
    #logDetailModal .modal-header {
        border-bottom: 1px solid var(--logs-border);
    }
    #logDetailModal .modal-title {
        color: var(--logs-text);
    }
    [data-bs-theme="light"] #logDetailModal .btn-close,
    :root:not([data-bs-theme="dark"]) #logDetailModal .btn-close {
        filter: none;
    }
    [data-bs-theme="dark"] #logDetailModal .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }
</style>
@endpush

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">
                    <i class="bi bi-journal-text me-2"></i>Logs Applicatifs
                </h3>
                <p class="text-muted mb-0">Journal d'activité et diagnostics techniques</p>
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
        @php
            $countError = 0;
            $countWarning = 0;
            $countInfo = 0;
            foreach ($entries as $entry) {
                if (in_array($entry['level'], ['error', 'critical', 'alert', 'emergency'])) $countError++;
                elseif (in_array($entry['level'], ['warning', 'notice'])) $countWarning++;
                elseif ($entry['level'] === 'info') $countInfo++;
            }
        @endphp

        <div class="stats-grid" id="statsGrid">
            <div class="stat-item stat-total">
                <div class="stat-value" id="statTotal">{{ count($entries) }}</div>
                <div class="stat-label">Entrées totales</div>
            </div>
            <div class="stat-item stat-error">
                <div class="stat-value" id="statError">{{ $countError }}</div>
                <div class="stat-label">Erreurs</div>
            </div>
            <div class="stat-item stat-warning">
                <div class="stat-value" id="statWarning">{{ $countWarning }}</div>
                <div class="stat-label">Avertissements</div>
            </div>
            <div class="stat-item stat-info">
                <div class="stat-value" id="statInfo">{{ $countInfo }}</div>
                <div class="stat-label">Informations</div>
            </div>
        </div>

        <div class="filter-bar mb-3">
            <div class="search-wrapper">
                <i class="bi bi-search search-icon"></i>
                <input type="text" id="logSearch" class="search-bar" placeholder="Recherche libre — tapez « aujourd'hui », « erreur sql », « now »..." oninput="applyFilters()">
                <button class="search-clear" id="searchClear" onclick="clearSearch()">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="d-flex gap-1 align-items-center flex-wrap">
                <div class="dropdown">
                    <button class="filter-chip active" id="timeDropdown" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-clock me-1"></i>Tout
                    </button>
                    <ul class="dropdown-menu dropdown-menu-dark" style="min-width:auto;font-size:0.8rem;">
                        <li><a class="dropdown-item" href="#" onclick="setTimeFilter('all', 'Tout');return false;">Tout</a></li>
                        <li><a class="dropdown-item" href="#" onclick="setTimeFilter('today', 'Aujourd\'hui');return false;">Aujourd'hui</a></li>
                        <li><a class="dropdown-item" href="#" onclick="setTimeFilter('hour', 'Cette heure');return false;">Cette heure</a></li>
                        <li><a class="dropdown-item" href="#" onclick="setTimeFilter('24h', 'Dernières 24h');return false;">Dernières 24h</a></li>
                    </ul>
                </div>
                <button class="filter-chip active" id="filterAll" onclick="setLevelFilter('all')">Tous</button>
                <button class="filter-chip" id="filterError" onclick="setLevelFilter('error')">Erreurs</button>
                <button class="filter-chip" id="filterWarning" onclick="setLevelFilter('warning')">Avertissements</button>
                <button class="filter-chip" id="filterInfo" onclick="setLevelFilter('info')">Infos</button>
                <span class="result-count ms-2" id="resultCount">{{ count($entries) }} / {{ count($entries) }}</span>
            </div>
        </div>

        <div class="log-container">
            <div class="log-header">
                <div>
                    <i class="bi bi-terminal me-2"></i>
                    <span>laravel.log</span>
                    <span class="ms-2 text-secondary" style="font-size:0.75rem;">({{ count($entries) }} entrées · 200 max)</span>
                </div>
                <div class="log-actions">
                    <form action="{{ route('admin.superadmin.parametres.clear-cache') }}" method="POST" class="d-inline"
                          onsubmit="return confirm('Vider le fichier de logs ?');">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Vider les logs">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                    <a href="{{ route('admin.superadmin.parametres.index', ['tab' => 'general']) }}" class="btn btn-sm btn-outline-secondary" title="Retour aux paramètres">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                </div>
            </div>
            <div class="log-body" id="logBody">
                @forelse($entries as $i => $entry)
                    @php
                        $levelClass = match($entry['level']) {
                            'error', 'critical', 'alert', 'emergency' => 'level-error',
                            'warning', 'notice' => 'level-warning',
                            'info' => 'level-info',
                            default => 'level-debug',
                        };
                        $tracePreview = !empty($entry['trace'])
                            ? preg_replace('/^#\d+\s+/m', '', implode(' · ', array_slice($entry['trace'], 0, 2)))
                            : '';
                    @endphp
                    <div class="log-entry {{ $levelClass }}" data-level="{{ $entry['level'] }}" data-index="{{ $i }}" onclick="openLogModal({{ $i }})">
                        <div class="d-flex align-items-start gap-2">
                            <span class="level-badge">{{ $entry['level'] }}</span>
                            <div class="flex-grow-1 min-w-0">
                                <div>
                                    <span class="timestamp">{{ $entry['timestamp'] }}</span>
                                </div>
                                <div class="message">{{ $entry['message'] }}</div>
                                @if($tracePreview)
                                    <div class="trace-preview">{{ $tracePreview }}</div>
                                @endif
                            </div>
                        </div>
                        <button class="view-btn" type="button" title="Voir les détails">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                @empty
                    <div class="empty-logs" id="emptyLogs">
                        <i class="bi bi-journal-check"></i>
                        <h5>Aucune entrée de log</h5>
                        <p>Le fichier de log est vide ou n'existe pas encore.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="mt-3 d-flex justify-content-between align-items-center">
            <small class="text-muted">
                <i class="bi bi-info-circle me-1"></i>
                Dernières {{ count($entries) }} entrées affichées.
                Fichier : <code>storage/logs/laravel.log</code>
            </small>
        </div>
    </div>
</div>

{{-- Modal de détail --}}
<div class="modal fade" id="logDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="logModalTitle">
                        <i class="bi bi-file-text me-2"></i>Détail de l'entrée de log
                    </h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="modal-log" id="logDetailContent">
                    <div class="modal-log-header" id="modalHeader">
                        <span>
                            <span class="level-badge" id="modalLevel"></span>
                        </span>
                        <span class="timestamp" id="modalTimestamp"></span>
                        <div class="ms-auto">
                            <button class="copy-btn" onclick="copyLogContent()">
                                <i class="bi bi-clipboard me-1"></i>Copier
                            </button>
                        </div>
                    </div>
                    <div class="modal-log-body" id="modalBody"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function() {
    const entries = @json($entries);
    const domEntries = Array.from(document.querySelectorAll('.log-entry'));
    const $ = function(id) { return document.getElementById(id); };
    const chips = {
        all: $('filterAll'),
        error: $('filterError'),
        warning: $('filterWarning'),
        info: $('filterInfo'),
    };
    const stats = {
        total: $('statTotal'),
        error: $('statError'),
        warning: $('statWarning'),
        info: $('statInfo'),
    };
    const resultCount = $('resultCount');
    const searchInput = $('logSearch');
    const searchClear = $('searchClear');
    const timeBtn = $('timeDropdown');
    const emptyMsg = $('emptyLogs');

    let levelFilter = 'all';
    let timeFilter = 'all';

    function parseDate(ts) {
        const d = new Date(ts.replace(' ', 'T'));
        return isNaN(d.getTime()) ? null : d;
    }

    function inRange(d, range) {
        if (!d) return true;
        const now = new Date();
        if (range === 'today') return d.getFullYear() === now.getFullYear() && d.getMonth() === now.getMonth() && d.getDate() === now.getDate();
        if (range === 'hour') return (now - d) <= 3600000;
        if (range === '24h') return (now - d) <= 86400000;
        return true;
    }

    window.applyFilters = function() {
        const q = searchInput.value.toLowerCase().trim();
        searchClear.style.display = q ? 'block' : 'none';

        const keywords = q ? q.split(/\s+/) : [];
        const hasDateKw = keywords.some(function(k) { return k === 'aujourd\'hui' || k === 'today' || k === 'now' || k === 'maintenant' || k === 'recent' || k === 'récent' || k === 'hier' || k === 'yesterday'; });

        var vTotal = 0, vErr = 0, vWarn = 0, vInfo = 0;

        for (var i = 0; i < domEntries.length; i++) {
            var el = domEntries[i];
            var idx = parseInt(el.dataset.index);
            var e = entries[idx];
            if (!e) { el.classList.add('hidden'); continue; }

            var show = true;

            if (levelFilter !== 'all' && e.level !== levelFilter) show = false;

            if (show && timeFilter !== 'all') {
                var d = parseDate(e.timestamp);
                show = inRange(d, timeFilter);
            }

            if (show && keywords.length > 0) {
                var full = (e.level + ' ' + e.timestamp + ' ' + e.message + ' ' + (e.trace ? e.trace.join(' ') : '')).toLowerCase();
                for (var k = 0; k < keywords.length; k++) {
                    var kw = keywords[k];
                    if (kw === 'aujourd\'hui' || kw === 'today') {
                        var dd = parseDate(e.timestamp);
                        show = dd && dd.getFullYear() === (new Date()).getFullYear() && dd.getMonth() === (new Date()).getMonth() && dd.getDate() === (new Date()).getDate();
                    } else if (kw === 'now' || kw === 'maintenant' || kw === 'recent' || kw === 'récent') {
                        var dd = parseDate(e.timestamp);
                        show = dd && (new Date() - dd) <= 3600000;
                    } else if (kw === 'hier' || kw === 'yesterday') {
                        var dd = parseDate(e.timestamp);
                        var y = new Date(); y.setDate(y.getDate() - 1);
                        show = dd && dd.getFullYear() === y.getFullYear() && dd.getMonth() === y.getMonth() && dd.getDate() === y.getDate();
                    } else {
                        show = full.indexOf(kw) !== -1;
                    }
                    if (!show) break;
                }
            }

            el.classList.toggle('hidden', !show);

            if (show) {
                vTotal++;
                if (e.level === 'error' || e.level === 'critical' || e.level === 'alert' || e.level === 'emergency') vErr++;
                else if (e.level === 'warning' || e.level === 'notice') vWarn++;
                else if (e.level === 'info') vInfo++;
            }
        }

        stats.total.textContent = vTotal;
        stats.error.textContent = vErr;
        stats.warning.textContent = vWarn;
        stats.info.textContent = vInfo;
        resultCount.textContent = vTotal + ' / ' + domEntries.length;
        if (emptyMsg) emptyMsg.style.display = (vTotal === 0 && domEntries.length > 0) ? 'block' : 'none';
    };

    window.clearSearch = function() {
        searchInput.value = '';
        searchClear.style.display = 'none';
        searchInput.focus();
        applyFilters();
    };

    window.setLevelFilter = function(level) {
        levelFilter = level;
        for (var key in chips) chips[key].className = 'filter-chip';
        if (level === 'all') chips.all.classList.add('active');
        else if (level === 'error') chips.error.classList.add('active-error');
        else if (level === 'warning') chips.warning.classList.add('active-warning');
        else if (level === 'info') chips.info.classList.add('active-info');
        applyFilters();
    };

    window.setTimeFilter = function(value, label) {
        timeFilter = value;
        timeBtn.innerHTML = '<i class="bi bi-clock me-1"></i>' + label;
        var dd = bootstrap.Dropdown.getInstance(timeBtn);
        if (dd) dd.hide();
        applyFilters();
    };

    function getErrorExplanation(message) {
        var m = message.toLowerCase();
        if (m.indexOf('sqlstate') !== -1 || m.indexOf('column not found') !== -1 || m.indexOf('unknown column') !== -1) return { type:'base de données', icon:'bi-database', summary:'Colonne introuvable dans la base de données', detail:'Une requête SQL tente d\'accéder à une colonne qui n\'existe pas dans la table. Cela arrive après une migration incomplète, un rollback, ou quand le code référence une colonne qui n\'a pas été ajoutée.', causes:['Une migration a été rollbackée et les colonnes ont été supprimées','Une migration n\'a pas encore été exécutée','Le code fait référence à une colonne renommée ou supprimée'], solutions:['Exécutez les migrations manquantes : php artisan migrate','Vérifiez que toutes les colonnes sont bien créées dans la base','Comparez le schéma de la base avec les fichiers de migration']};
        if (m.indexOf('duplicate column') !== -1 || m.indexOf('column already exists') !== -1) return { type:'base de données', icon:'bi-database', summary:'Colonne en double', detail:'Une migration tente de créer une colonne qui existe déjà dans la table.', causes:['La migration a déjà été exécutée mais est rejouée','Conflit entre deux migrations différentes'], solutions:['Vérifiez les migrations en attente avec php artisan migrate:status','Supprimez la migration en conflit ou corrigez-la']};
        if (m.indexOf('duplicate key') !== -1 || m.indexOf('duplicate entry') !== -1) return { type:'base de données', icon:'bi-database', summary:'Doublon : contrainte d\'unicité violée', detail:'Tentative d\'insérer une valeur qui viole une contrainte d\'unicité (clé unique ou index).', causes:['Un enregistrement avec la même valeur unique existe déjà','Un index a déjà été créé sur la même colonne'], solutions:['Corrigez les données en doublon dans la table','Supprimez l\'index en double si la migration a déjà été exécutée']};
        if (m.indexOf('view') !== -1 && m.indexOf('not found') !== -1) return { type:'vue manquante', icon:'bi-file-earmark', summary:'Vue Blade introuvable', detail:'Le contrôleur tente de charger une vue Blade qui n\'existe pas dans le dossier resources/views.', causes:['Le fichier .blade.php n\'a pas été créé','Le nom de la vue est mal orthographié dans le contrôleur','La vue a été déplacée ou supprimée'], solutions:['Créez le fichier de vue manquant','Vérifiez l\'orthographe du nom de vue dans le contrôleur','Utilisez php artisan view:clear après avoir créé la vue']};
        if (m.indexOf('undefined method') !== -1 || m.indexOf('call to undefined method') !== -1) return { type:'code', icon:'bi-code-slash', summary:'Méthode appelée mais non définie', detail:'Le code tente d\'appeler une méthode qui n\'existe pas sur l\'objet ou le modèle.', causes:['La méthode n\'a pas encore été implémentée dans la classe','L\'objet n\'est pas du type attendu','Faute de frappe dans le nom de la méthode'], solutions:['Ajoutez la méthode manquante dans le modèle concerné','Vérifiez le type de l\'objet avant d\'appeler la méthode','Corrigez le nom de la méthode si c\'est une faute de frappe']};
        if (m.indexOf('class') !== -1 && m.indexOf('not found') !== -1) return { type:'autoloading', icon:'bi-box', summary:'Classe introuvable par l\'autoloader', detail:'PHP ne parvient pas à charger une classe. Le namespace ou le chemin du fichier est incorrect.', causes:['Le fichier de la classe n\'existe pas','Le namespace déclaré dans le fichier ne correspond pas au chemin','L\'autoloader n\'a pas été mis à jour après la création de la classe'], solutions:['Exécutez composer dump-autoload pour régénérer l\'autoloader','Vérifiez que le namespace et le chemin du fichier correspondent','Vérifiez l\'orthographe du nom et du namespace de la classe']};
        if (m.indexOf('does not exist') !== -1 && m.indexOf('option') !== -1) return { type:'console', icon:'bi-terminal', summary:'Option de commande invalide', detail:'Une commande Artisan a été appelée avec une option qui n\'existe pas dans sa définition.', causes:['L\'option a été supprimée ou renommée','La commande est mal orthographiée'], solutions:['Consultez l\'aide : php artisan help <commande>','Corrigez l\'option utilisée']};
        if (m.indexOf('could not find driver') !== -1) return { type:'base de données', icon:'bi-database', summary:'Pilote de base de données manquant', detail:'PHP ne dispose pas du pilote (driver) nécessaire pour se connecter à la base de données configurée.', causes:['L\'extension PHP requise (pdo_mysql, pdo_sqlite) n\'est pas installée','Le fichier .env configure un driver non disponible'], solutions:['Installez l\'extension PHP manquante','Vérifiez DB_CONNECTION dans .env']};
        if (m.indexOf('doesn\'t have a default value') !== -1 || m.indexOf('default value') !== -1) return { type:'base de données', icon:'bi-database', summary:'Champ obligatoire sans valeur par défaut', detail:'Une insertion en base échoue car un champ NOT NULL n\'a pas de valeur.', causes:['La colonne est NOT NULL mais non remplie','Le modèle n\'inclut pas ce champ dans $fillable'], solutions:['Ajoutez une valeur par défaut dans la migration','Assurez-vous que le champ est fourni','Ajoutez le champ dans $fillable du modèle']};
        if (m.indexOf('read property') !== -1 && m.indexOf('on null') !== -1) return { type:'code', icon:'bi-code-slash', summary:'Lecture sur une valeur nulle', detail:'Le code accède à une propriété sur une variable qui vaut null.', causes:['Une relation Eloquent n\'a pas retourné de résultat','Un utilisateur connecté est null','La variable n\'a pas été initialisée'], solutions:['Vérifiez que l\'objet existe avant d\'accéder','Utilisez ?-> (null safe) de PHP 8','Ajoutez une vérification conditionnelle']};
        if (m.indexOf('syntax error') !== -1 || m.indexOf('parse error') !== -1) return { type:'code', icon:'bi-code-slash', summary:'Erreur de syntaxe PHP', detail:'PHP rencontre une erreur de syntaxe : parenthèse manquante, point-virgule oublié, etc.', causes:['Faute de frappe dans le code PHP','Fermeture de parenthèse manquante','Syntaxe incorrecte dans un fichier Blade'], solutions:['Utilisez php -l <fichier> pour détecter l\'erreur','Vérifiez les parenthèses et points-virgules','Regardez le fichier et la ligne indiqués']};
        if (m.indexOf('route') !== -1 && m.indexOf('not defined') !== -1) return { type:'routing', icon:'bi-signpost-2', summary:'Route nommée introuvable', detail:'Le code tente de générer une URL avec route() mais le nom de route n\'existe pas.', causes:['La route n\'a pas été définie','Le nom de route est mal orthographié','La route a été supprimée ou renommée'], solutions:['Vérifiez avec php artisan route:list','Corrigez le nom de route dans l\'appel','Ajoutez la route manquante']};
        if (m.indexOf('undefined variable') !== -1) return { type:'vue', icon:'bi-file-earmark', summary:'Variable indéfinie dans une vue', detail:'Une vue Blade tente d\'afficher une variable non passée par le contrôleur.', causes:['Le contrôleur n\'a pas passé la variable','Faute de frappe dans le nom de la variable'], solutions:['Vérifiez que le contrôleur passe bien la variable','Corrigez le nom dans la vue']};
        return null;
    }

    window.openLogModal = function(index) {
        var e = entries[index];
        if (!e) return;

        $('modalLevel').textContent = e.level.toUpperCase();
        $('modalLevel').className = 'level-badge';
        var plc = (e.level === 'error'||e.level==='critical'||e.level==='alert'||e.level==='emergency') ? 'level-error' : (e.level==='warning'||e.level==='notice') ? 'level-warning' : e.level==='info' ? 'level-info' : 'level-debug';
        $('modalHeader').className = 'modal-log-header ' + plc;
        $('modalTimestamp').textContent = e.timestamp;

        var body = $('modalBody');
        body.innerHTML = '';

        var msg = document.createElement('div');
        msg.style.cssText = 'color:var(--logs-msg);font-weight:600;margin-bottom:0.75rem;';
        msg.textContent = e.message;
        body.appendChild(msg);

        var expl = getErrorExplanation(e.message);
        if (expl) {
            var box = document.createElement('div');
            box.style.cssText = 'background:var(--logs-info-bg);border:1px solid var(--logs-border);border-radius:8px;padding:0.75rem 1rem;margin-bottom:0.75rem;';
            box.innerHTML = '<div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.4rem;"><i class="' + expl.icon + '" style="color:var(--logs-info);font-size:1rem;"></i><span style="font-weight:700;font-size:0.75rem;text-transform:uppercase;color:var(--logs-info);">' + expl.type + '</span><span style="margin-left:auto;background:var(--logs-info-bg);padding:0.1rem 0.4rem;border-radius:4px;font-size:0.65rem;color:var(--logs-info);">? Explication</span></div><div style="font-weight:600;font-size:0.82rem;color:var(--logs-text);margin-bottom:0.35rem;">' + expl.summary + '</div><div style="font-size:0.78rem;color:var(--logs-text-dim);margin-bottom:0.5rem;">' + expl.detail + '</div><div style="font-size:0.75rem;color:var(--logs-warning);margin-bottom:0.25rem;"><i class="bi bi-exclamation-triangle me-1"></i>Causes possibles :</div><ul style="margin:0 0 0.5rem 1.2rem;padding:0;font-size:0.75rem;color:var(--logs-text-dim);">' + expl.causes.map(function(c){return '<li>'+c+'</li>';}).join('') + '</ul><div style="font-size:0.75rem;color:var(--logs-file);margin-bottom:0.25rem;"><i class="bi bi-check-circle me-1"></i>Solutions :</div><ul style="margin:0 0 0 1.2rem;padding:0;font-size:0.75rem;color:var(--logs-text-dim);">' + expl.solutions.map(function(s){return '<li>'+s+'</li>';}).join('') + '</ul>';
            body.appendChild(box);
        }

        if (e.trace && e.trace.length > 0) {
            var ttl = document.createElement('div');
            ttl.style.cssText = 'color:var(--logs-info);font-size:0.7rem;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:0.5rem;padding-top:0.5rem;border-top:1px solid var(--logs-border);';
            ttl.textContent = 'Pile d\'exécution (Stack Trace)';
            body.appendChild(ttl);
            for (var t = 0; t < e.trace.length; t++) {
                var d = document.createElement('div');
                d.className = 'stack-line';
                var tr = e.trace[t].trim();
                if (tr.indexOf('/vendor/') !== -1) d.classList.add('vendor');
                else if (tr.indexOf('#') === 0) d.classList.add('file');
                d.textContent = e.trace[t];
                body.appendChild(d);
            }
        }

        $('logModalTitle').dataset.index = index;
        new bootstrap.Modal($('logDetailModal')).show();
    };

    window.copyLogContent = function() {
        var idx = $('logModalTitle').dataset.index;
        var e = entries[parseInt(idx)];
        if (!e) return;
        var txt = '[' + e.timestamp + '] ' + e.level.toUpperCase() + ': ' + e.message + (e.trace && e.trace.length ? '\n' + e.trace.join('\n') : '');
        navigator.clipboard.writeText(txt).then(function() {
            var btn = document.querySelector('.copy-btn');
            btn.innerHTML = '<i class="bi bi-check me-1"></i>Copié !';
            setTimeout(function(){ btn.innerHTML = '<i class="bi bi-clipboard me-1"></i>Copier'; }, 2000);
        }).catch(function() {
            var btn = document.querySelector('.copy-btn');
            btn.innerHTML = '<i class="bi bi-x me-1"></i>Erreur';
            setTimeout(function(){ btn.innerHTML = '<i class="bi bi-clipboard me-1"></i>Copier'; }, 2000);
        });
    };
})();
</script>
@endpush
@endsection