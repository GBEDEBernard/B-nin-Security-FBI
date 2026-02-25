<!doctype html>
<html lang="fr">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>@yield('title', 'Bénin Security - Gestion de Sécurité')</title>

  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
  <meta name="color-scheme" content="light dark" />
  <meta name="theme-color" content="#198754" media="(prefers-color-scheme: light)" />
  <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
  {{-- CSRF token obligatoire pour les requêtes AJAX --}}
  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <meta name="title" content="Bénin Security - Gestion de Sécurité Professionnelle" />
  <meta name="author" content="Bénin Security" />
  <meta name="description" content="Plateforme de gestion de sécurité professionnelle au Bénin." />

  {{-- Fonts --}}
  <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
    integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
    crossorigin="anonymous"
    media="print" onload="this.media='all'" />

  {{-- OverlayScrollbars --}}
  <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
    crossorigin="anonymous" />

  {{-- Bootstrap Icons --}}
  <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
    crossorigin="anonymous" />

  {{-- AdminLTE CSS --}}
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.css') }}">

  {{--
    ══════════════════════════════════════════════════════
    ApexCharts CSS — chargé UNE SEULE FOIS ici.
    NE PAS le recharger dans les pages enfants.
    ══════════════════════════════════════════════════════
  --}}
  <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
    crossorigin="anonymous" />

  {{-- Styles spécifiques aux pages --}}
  @stack('styles')

  <style>
    :root {
      --bs-primary: #198754;
      --bs-success: #198754;
    }

    .text-primary {
      color: #198754 !important;
    }

    .bg-primary {
      background-color: #198754 !important;
    }

    .btn-primary {
      background-color: #198754;
      border-color: #198754;
    }

    .btn-primary:hover {
      background-color: #146c43;
      border-color: #146c43;
    }

    .app-wrapper {
      transition: all 0.3s ease;
    }

    ::-webkit-scrollbar {
      width: 8px;
      height: 8px;
    }

    ::-webkit-scrollbar-track {
      background: #f1f1f1;
    }

    ::-webkit-scrollbar-thumb {
      background: #198754;
      border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
      background: #146c43;
    }

    /* ═════════════════════════════════════════════════════════════
       STYLES GLOBAUX POUR LE MODE SOMBRE - Lisibilité optimale
       ═════════════════════════════════════════════════════════════ */

    /* Texte principal adaptatif */
    body,
    .app-content,
    .app-content-header,
    .card-body,
    .table,
    .modal-body,
    .dropdown-menu,
    .nav-link,
    .sidebar-menu>.nav-header {
      color: var(--bs-body-color, #212529);
    }

    /* Titres et textes secondaires */
    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    .h1,
    .h2,
    .h3,
    .h4,
    .h5,
    .h6,
    .card-title,
    .dropdown-item,
    .nav-link p {
      color: var(--bs-heading-color, var(--bs-body-color));
    }

    /* Texte en gris/grisé adaptatif */
    .text-muted,
    .text-secondary {
      color: var(--bs-secondary-color) !important;
    }

    /* Liens */
    a {
      color: var(--bs-link-color);
    }

    a:hover {
      color: var(--bs-link-hover-color);
    }

    /* Cartes et conteneurs */
    .card,
    .dashboard-card,
    .modal-content {
      background-color: var(--bs-body-bg);
      border-color: var(--bs-border-color);
    }

    /* Tableaux */
    .table {
      --bs-table-color-type: var(--bs-body-color);
      --bs-table-bg-type: var(--bs-body-bg);
      --bs-table-border-color: var(--bs-border-color);
    }

    .table-hover>tbody>tr:hover>* {
      --bs-table-hover-bg: var(--bs-tertiary-bg);
    }

    /* Inputs et formulaires */
    .form-control,
    .form-select {
      background-color: var(--bs-body-bg);
      border-color: var(--bs-border-color);
      color: var(--bs-body-color);
    }

    /* Alerts et badges */
    .alert {
      background-color: var(--bs-tertiary-bg);
      border-color: var(--bs-border-color);
    }

    .badge {
      color: #fff;
    }

    /* Sidebar en mode sombre */
    [data-bs-theme="dark"] .app-sidebar {
      background-color: #1a1a1a !important;
      border-right: 1px solid #2d2d2d;
    }

    [data-bs-theme="dark"] .sidebar-menu>.nav-header {
      color: #a0a0a0 !important;
    }

    [data-bs-theme="dark"] .nav-link {
      color: #e0e0e0 !important;
    }

    [data-bs-theme="dark"] .nav-link:hover {
      background-color: #2d2d2d !important;
    }

    [data-bs-theme="dark"] .nav-link.active {
      background-color: #198754 !important;
      color: #fff !important;
    }

    /* Header en mode sombre */
    [data-bs-theme="dark"] .app-header {
      background-color: #1a1a1a !important;
      border-bottom: 1px solid #2d2d2d;
    }

    /* Footer en mode sombre */
    [data-bs-theme="dark"] .app-footer {
      background-color: #1a1a1a !important;
      border-top: 1px solid #2d2d2d;
      color: #e0e0e0 !important;
    }

    /* Main content en mode sombre */
    [data-bs-theme="dark"] .app-main {
      background-color: #121212 !important;
    }

    /* Quick action buttons */
    .quick-action-btn {
      background-color: var(--bs-tertiary-bg);
      border-color: var(--bs-border-color);
      color: var(--bs-body-color);
    }

    .quick-action-btn:hover {
      border-color: var(--bs-primary);
      color: var(--bs-primary);
    }

    /* Modal adaptatif */
    .modal-content {
      background-color: var(--bs-body-bg);
    }

    /* Stat cards en mode sombre */
    [data-bs-theme="dark"] .stat-card {
      background-color: var(--bs-body-bg);
      border: 1px solid var(--bs-border-color);
    }

    [data-bs-theme="dark"] .stat-card .stat-number {
      color: #fff;
    }

    [data-bs-theme="dark"] .text-muted {
      color: #a0a0a0 !important;
    }
  </style>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
  <div class="app-wrapper">

    @include('layouts.header')
    @include('layouts.sidebar')

    @if(session('error'))
    <div class="alert alert-danger m-3">{{ session('error') }}</div>
    @endif
    @if(session('success'))
    <div class="alert alert-success m-3">{{ session('success') }}</div>
    @endif

    <main class="app-main">
      @yield('content')
    </main>

    @include('layouts.footer')

    {{-- Modal connexion entreprise --}}
    <div class="modal fade" id="connectModal" tabindex="-1" aria-labelledby="connectModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg" style="border-radius:16px;overflow:hidden;">
          <div class="modal-header text-white border-0" style="background:linear-gradient(135deg,#198754 0%,#20c997 100%);">
            <div class="d-flex align-items-center">
              <div class="me-3">
                <div class="bg-white bg-opacity-25 rounded-circle p-2">
                  <i class="bi bi-building fs-5"></i>
                </div>
              </div>
              <div>
                <h5 class="modal-title fw-bold" id="connectModalLabel">Connexion à l'Entreprise</h5>
                <p class="mb-0 small opacity-75">Confirmation de changement de contexte</p>
              </div>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
          </div>
          <div class="modal-body py-4 px-4">
            <div class="text-center mb-4">
              <div class="mb-3">
                <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width:80px;height:80px;">
                  <i class="bi bi-box-arrow-in-right fs-1" style="color: var(--bs-success);"></i>
                </div>
              </div>
              <h6 class="mb-2" style="color: var(--bs-secondary-color);">Vous allez accéder au tableau de bord de :</h6>
              <h4 class="fw-bold mb-3" style="color: var(--bs-success);" id="entrepriseNom"></h4>
              <div class="d-flex align-items-center justify-content-center py-2 px-3" style="border-radius:8px; background: var(--bs-info-bg-subtle); border: 1px solid var(--bs-info-border-subtle);">
                <i class="bi bi-info-circle me-2" style="color: var(--bs-info-text-emphasis);"></i>
                <small style="color: var(--bs-body-color);">Vous pourrez retourner au Super Admin à tout moment</small>
              </div>
            </div>
            <div class="p-3" style="border-radius:12px; background: var(--bs-tertiary-bg);">
              <h6 class="fw-bold mb-2"><i class="bi bi-list-check me-2"></i>Accès disponibles :</h6>
              <div class="row text-center small">
                <div class="col-6 mb-2"><i class="bi bi-people me-1" style="color: var(--bs-primary-text-emphasis);"></i> <span style="color: var(--bs-body-color);">Employés</span></div>
                <div class="col-6 mb-2"><i class="bi bi-person-badge me-1" style="color: #0dcaf0;"></i> <span style="color: var(--bs-body-color);">Clients</span></div>
                <div class="col-6 mb-2"><i class="bi bi-file-earmark-text me-1" style="color: #ffc107;"></i> <span style="color: var(--bs-body-color);">Contrats</span></div>
                <div class="col-6 mb-2"><i class="bi bi-calendar-check me-1" style="color: var(--bs-success);"></i> <span style="color: var(--bs-body-color);">Affectations</span></div>
                <div class="col-6 mb-0"><i class="bi bi-receipt me-1" style="color: #dc3545;"></i> <span style="color: var(--bs-body-color);">Facturation</span></div>
                <div class="col-6 mb-0"><i class="bi bi-graph-up me-1" style="color: #6f42c1;"></i> <span style="color: var(--bs-body-color);">Rapports</span></div>
              </div>
            </div>
          </div>
          <div class="modal-footer border-0 px-4 pb-4" style="background: var(--bs-tertiary-bg);">
            <button type="button" class="btn btn-outline-secondary btn-lg px-4" data-bs-dismiss="modal" style="border-radius:8px;">
              <i class="bi bi-x-circle me-2"></i>Annuler
            </button>
            <button type="button" class="btn btn-success btn-lg px-4" id="confirmConnectBtn" style="border-radius:8px;">
              <i class="bi bi-check-circle me-2"></i>Confirmer
            </button>
          </div>
        </div>
      </div>
    </div>

  </div>{{-- /app-wrapper --}}

  {{-- ══════════════════════════════════════════════════════
       SCRIPTS — Ordre impératif :
       1. OverlayScrollbars
       2. Popper
       3. Bootstrap
       4. AdminLTE
       5. ApexCharts  ← UNE SEULE FOIS ICI
       6. @stack('scripts')  ← scripts des pages enfants
  ══════════════════════════════════════════════════════ --}}
  <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
  <script src="{{ asset('dist/js/adminlte.js') }}"></script>

  {{--
    ApexCharts chargé ICI, après AdminLTE, avant @stack('scripts').
    Les pages enfants utilisent ApexCharts directement dans leur @push('scripts')
    sans avoir à le recharger.
  --}}
  <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
    integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8="
    crossorigin="anonymous"></script>

  {{-- OverlayScrollbars configure --}}
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const sidebarWrapper = document.querySelector('.sidebar-wrapper');
      if (sidebarWrapper && OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined && window.innerWidth > 992) {
        OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
          scrollbars: {
            theme: 'os-theme-light',
            autoHide: 'leave',
            clickScroll: true
          }
        });
      }
    });
  </script>

  {{-- Theme toggler --}}
  <script>
    (() => {
      "use strict";
      const storedTheme = localStorage.getItem("theme");
      const getPreferredTheme = () => storedTheme || (window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light");
      const setTheme = (theme) => {
        document.documentElement.setAttribute("data-bs-theme",
          theme === "auto" && window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : theme);

        // Émettre un événement pour notifier les composants (comme les graphiques)
        window.dispatchEvent(new Event('theme-changed'));
      };
      setTheme(getPreferredTheme());
      window.addEventListener("DOMContentLoaded", () => {
        document.querySelectorAll("[data-bs-theme-value]").forEach(toggle => {
          toggle.addEventListener("click", () => {
            const theme = toggle.getAttribute("data-bs-theme-value");
            localStorage.setItem("theme", theme);
            setTheme(theme);
          });
        });
      });
    })();
  </script>

  {{-- Modal connexion entreprise --}}
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      let entrepriseId = null;
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

      const connectModal = document.getElementById('connectModal');
      if (connectModal) {
        connectModal.addEventListener('show.bs.modal', function(event) {
          const button = event.relatedTarget;
          if (button) {
            entrepriseId = button.getAttribute('data-entreprise-id');
            const nomEl = document.getElementById('entrepriseNom');
            if (nomEl) nomEl.textContent = button.getAttribute('data-entreprise-nom');
          }
        });
      }

      const confirmBtn = document.getElementById('confirmConnectBtn');
      if (confirmBtn) {
        confirmBtn.addEventListener('click', function() {
          if (!entrepriseId) return;
          confirmBtn.disabled = true;
          confirmBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Connexion...';

          fetch(`/admin/superadmin/entreprises/${entrepriseId}/connect`, {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
              }
            })
            .then(response => {
              if (response.redirected) {
                window.location.href = response.url;
              } else {
                return response.json().then(data => {
                  window.location.href = data.redirect || '/admin/entreprise';
                });
              }
            })
            .catch(() => {
              confirmBtn.disabled = false;
              confirmBtn.innerHTML = '<i class="bi bi-check-circle me-1"></i>Confirmer';
            });
        });
      }
    });
  </script>

  {{-- Scripts spécifiques aux pages (injectés APRÈS ApexCharts) --}}
  @stack('scripts')

</body>

</html>