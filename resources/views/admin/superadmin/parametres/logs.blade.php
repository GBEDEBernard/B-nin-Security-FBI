@extends('layouts.app')

@section('title', 'Logs Applicatifs - Super Admin')

@push('styles')
<style>
    .log-container {
        background: #1e1e2e;
        border-radius: 12px;
        overflow: hidden;
        font-family: 'SF Mono', 'Fira Code', 'Consolas', monospace;
        font-size: 0.8rem;
        line-height: 1.6;
    }
    .log-header {
        background: #181825;
        padding: 0.75rem 1.25rem;
        color: #cdd6f4;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #313244;
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
        background: #181825;
    }
    .log-body::-webkit-scrollbar-thumb {
        background: #45475a;
        border-radius: 4px;
    }
    .log-entry {
        padding: 0.5rem 1.25rem;
        border-bottom: 1px solid #313244;
        cursor: pointer;
        transition: background 0.15s ease;
        position: relative;
    }
    .log-entry:hover {
        background: rgba(69, 71, 90, 0.3);
    }
    .log-entry:hover .view-btn {
        opacity: 1;
    }
    .log-entry .timestamp {
        color: #89b4fa;
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
    .level-error .level-badge { background: rgba(243, 139, 168, 0.2); color: #f38ba8; }
    .level-warning .level-badge { background: rgba(249, 226, 175, 0.2); color: #f9e2af; }
    .level-info .level-badge { background: rgba(137, 180, 250, 0.2); color: #89b4fa; }
    .level-debug .level-badge { background: rgba(166, 173, 200, 0.2); color: #a6adc8; }
    .log-entry .message {
        color: #cdd6f4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .log-entry .trace-preview {
        font-size: 0.75rem;
        color: #6c7086;
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
        background: rgba(69, 71, 90, 0.6);
        border: none;
        color: #cdd6f4;
        width: 28px;
        height: 28px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
    }
    .view-btn:hover {
        background: rgba(69, 71, 90, 0.9);
        color: #fff;
    }
    .empty-logs {
        padding: 3rem 1.25rem;
        text-align: center;
        color: #6c7086;
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
        background: #1e1e2e;
        border-radius: 10px;
        padding: 1rem;
        text-align: center;
        border: 1px solid #313244;
    }
    .stat-item .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        font-family: inherit;
    }
    .stat-item .stat-label {
        font-size: 0.75rem;
        color: #6c7086;
        margin-top: 0.25rem;
    }
    .stat-error .stat-value { color: #f38ba8; }
    .stat-warning .stat-value { color: #f9e2af; }
    .stat-info .stat-value { color: #89b4fa; }
    .stat-total .stat-value { color: #cdd6f4; }
    .log-actions {
        display: flex;
        gap: 0.5rem;
    }

    .modal-log {
        background: #1e1e2e;
        border-radius: 8px;
        padding: 0;
        overflow: hidden;
    }
    .modal-log-header {
        background: #181825;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #313244;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .modal-log-body {
        padding: 1rem;
        max-height: 55vh;
        overflow-y: auto;
        color: #cdd6f4;
        font-size: 0.78rem;
        line-height: 1.7;
        white-space: pre-wrap;
        word-break: break-all;
    }
    .modal-log-body::-webkit-scrollbar {
        width: 6px;
    }
    .modal-log-body::-webkit-scrollbar-track {
        background: #181825;
    }
    .modal-log-body::-webkit-scrollbar-thumb {
        background: #45475a;
        border-radius: 3px;
    }
    .modal-log-body .stack-line {
        color: #6c7086;
    }
    .modal-log-body .stack-line.file {
        color: #a6e3a1;
    }
    .modal-log-body .stack-line.vendor {
        color: #45475a;
    }
    .copy-btn {
        background: rgba(69, 71, 90, 0.6);
        border: none;
        color: #cdd6f4;
        padding: 0.25rem 0.6rem;
        border-radius: 4px;
        font-size: 0.7rem;
        cursor: pointer;
        transition: background 0.2s;
    }
    .copy-btn:hover {
        background: rgba(69, 71, 90, 0.9);
    }

    .search-bar {
        background: #181825;
        border: 1px solid #313244;
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
        color: #cdd6f4;
        font-family: inherit;
        font-size: 0.8rem;
        width: 100%;
        transition: border-color 0.2s;
    }
    .search-bar:focus {
        outline: none;
        border-color: #89b4fa;
    }
    .search-bar::placeholder {
        color: #6c7086;
    }
    .filter-chip {
        background: #181825;
        border: 1px solid #313244;
        color: #cdd6f4;
        padding: 0.3rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        cursor: pointer;
        transition: all 0.2s;
        font-family: inherit;
    }
    .filter-chip:hover {
        border-color: #6c7086;
    }
    .filter-chip.active {
        background: #45475a;
        border-color: #89b4fa;
        color: #89b4fa;
    }
    .filter-chip.active-error {
        background: rgba(243, 139, 168, 0.2);
        border-color: #f38ba8;
        color: #f38ba8;
    }
    .filter-chip.active-warning {
        background: rgba(249, 226, 175, 0.2);
        border-color: #f9e2af;
        color: #f9e2af;
    }
    .filter-chip.active-info {
        background: rgba(137, 180, 250, 0.2);
        border-color: #89b4fa;
        color: #89b4fa;
    }
    .search-clear {
        background: none;
        border: none;
        color: #6c7086;
        position: absolute;
        right: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        padding: 0.25rem;
        display: none;
    }
    .search-clear:hover {
        color: #cdd6f4;
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
        color: #6c7086;
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
        color: #6c7086;
        white-space: nowrap;
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
                <input type="text" id="logSearch" class="search-bar" placeholder="Rechercher dans les logs (message, niveau, timestamp, stack trace...)" oninput="filterLogs()">
                <button class="search-clear" id="searchClear" onclick="clearSearch()">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="d-flex gap-1 align-items-center">
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
        <div class="modal-content" style="background:#11111b; border:1px solid #313244;">
            <div class="modal-header" style="border-bottom:1px solid #313244;">
                <div>
                    <h5 class="modal-title" style="color:#cdd6f4;" id="logModalTitle">
                        <i class="bi bi-file-text me-2"></i>Détail de l'entrée de log
                    </h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
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
    const logEntries = @json($entries);
    let currentLevelFilter = 'all';
    let currentSearch = '';

    function filterLogs() {
        const searchInput = document.getElementById('logSearch');
        currentSearch = searchInput.value.toLowerCase().trim();
        const clearBtn = document.getElementById('searchClear');
        clearBtn.style.display = currentSearch ? 'block' : 'none';

        applyFilters();
    }

    function clearSearch() {
        document.getElementById('logSearch').value = '';
        document.getElementById('searchClear').style.display = 'none';
        currentSearch = '';
        applyFilters();
        document.getElementById('logSearch').focus();
    }

    function setLevelFilter(level) {
        currentLevelFilter = level;

        document.querySelectorAll('.filter-chip').forEach(function(btn) {
            btn.className = 'filter-chip';
        });

        if (level === 'all') {
            document.getElementById('filterAll').classList.add('active');
        } else if (level === 'error') {
            document.getElementById('filterError').classList.add('active-error');
        } else if (level === 'warning') {
            document.getElementById('filterWarning').classList.add('active-warning');
        } else if (level === 'info') {
            document.getElementById('filterInfo').classList.add('active-info');
        }

        applyFilters();
    }

    function applyFilters() {
        const entries = document.querySelectorAll('.log-entry');
        let visibleCount = 0;
        let errorCount = 0;
        let warningCount = 0;
        let infoCount = 0;

        entries.forEach(function(entry) {
            const level = entry.dataset.level;
            const index = parseInt(entry.dataset.index);
            const logEntry = logEntries[index];
            if (!logEntry) return;

            const fullText = (
                logEntry.level + ' ' +
                logEntry.timestamp + ' ' +
                logEntry.message + ' ' +
                (logEntry.trace ? logEntry.trace.join(' ') : '')
            ).toLowerCase();

            const matchesSearch = !currentSearch || fullText.includes(currentSearch);
            const matchesLevel = currentLevelFilter === 'all' || level === currentLevelFilter;

            const isVisible = matchesSearch && matchesLevel;
            entry.classList.toggle('hidden', !isVisible);

            if (isVisible) {
                visibleCount++;
                if (['error', 'critical', 'alert', 'emergency'].includes(level)) errorCount++;
                else if (['warning', 'notice'].includes(level)) warningCount++;
                else if (level === 'info') infoCount++;
            }
        });

        document.getElementById('statTotal').textContent = visibleCount;
        document.getElementById('statError').textContent = errorCount;
        document.getElementById('statWarning').textContent = warningCount;
        document.getElementById('statInfo').textContent = infoCount;

        const total = entries.length;
        document.getElementById('resultCount').textContent = visibleCount + ' / ' + total;

        const emptyMsg = document.getElementById('emptyLogs');
        if (emptyMsg) {
            emptyMsg.style.display = (visibleCount === 0 && total > 0) ? 'block' : 'none';
        }
    }

    function openLogModal(index) {
        const entry = logEntries[index];
        if (!entry) return;

        document.getElementById('modalLevel').textContent = entry.level.toUpperCase();
        document.getElementById('modalLevel').className = 'level-badge';
        const parentLevelClass = ['error','critical','alert','emergency'].includes(entry.level) ? 'level-error'
            : ['warning','notice'].includes(entry.level) ? 'level-warning'
            : entry.level === 'info' ? 'level-info' : 'level-debug';
        document.getElementById('modalHeader').className = 'modal-log-header ' + parentLevelClass;
        document.getElementById('modalTimestamp').textContent = entry.timestamp;

        const body = document.getElementById('modalBody');
        body.innerHTML = '';

        const msgDiv = document.createElement('div');
        msgDiv.style.cssText = 'color:#f5e0dc; font-weight:600; margin-bottom:0.75rem;';
        msgDiv.textContent = entry.message;
        body.appendChild(msgDiv);

        if (entry.trace && entry.trace.length > 0) {
            const traceTitle = document.createElement('div');
            traceTitle.style.cssText = 'color:#89b4fa; font-size:0.7rem; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:0.5rem; padding-top:0.5rem; border-top:1px solid #313244;';
            traceTitle.textContent = 'Pile d\'exécution (Stack Trace)';
            body.appendChild(traceTitle);

            entry.trace.forEach(function(line) {
                const d = document.createElement('div');
                d.className = 'stack-line';
                const trimmed = line.trim();
                if (trimmed.includes('/vendor/')) {
                    d.classList.add('vendor');
                } else if (trimmed.startsWith('#')) {
                    d.classList.add('file');
                }
                d.textContent = line;
                body.appendChild(d);
            });
        }

        const entryIndex = document.getElementById('logModalTitle');
        entryIndex.dataset.index = index;

        const modal = new bootstrap.Modal(document.getElementById('logDetailModal'));
        modal.show();
    }

    function copyLogContent() {
        const index = document.getElementById('logModalTitle').dataset.index;
        const entry = logEntries[index];
        if (!entry) return;

        const text = '[' + entry.timestamp + '] ' + entry.level.toUpperCase() + ': ' + entry.message
            + (entry.trace && entry.trace.length > 0 ? '\n' + entry.trace.join('\n') : '');

        navigator.clipboard.writeText(text).then(function() {
            const btn = document.querySelector('.copy-btn');
            btn.innerHTML = '<i class="bi bi-check me-1"></i>Copié !';
            setTimeout(function() {
                btn.innerHTML = '<i class="bi bi-clipboard me-1"></i>Copier';
            }, 2000);
        }).catch(function() {
            const btn = document.querySelector('.copy-btn');
            btn.innerHTML = '<i class="bi bi-x me-1"></i>Erreur';
            setTimeout(function() {
                btn.innerHTML = '<i class="bi bi-clipboard me-1"></i>Copier';
            }, 2000);
        });
    }
</script>
@endpush
@endsection
