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



    <main class="app-main">
      @yield('content')
    </main>

    @include('layouts.footer')

    {{-- Modal de succès --}}
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:16px;overflow:hidden;">
          <div class="modal-header text-white border-0" style="background:linear-gradient(135deg,#198754 0%,#20c997 100%);">
            <div class="d-flex align-items-center">
              <div class="me-3">
                <div class="bg-white bg-opacity-25 rounded-circle p-2">
                  <i class="bi bi-check-circle-fill fs-4"></i>
                </div>
              </div>
              <div>
                <h5 class="modal-title fw-bold" id="successModalLabel">Opération Réussie</h5>
                <p class="mb-0 small opacity-75">Votre action a été effectuée avec succès</p>
              </div>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
          </div>
          <div class="modal-body py-4 px-4 text-center">
            <div class="mb-3">
              <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width:80px;height:80px;">
                <i class="bi bi-check-circle-fill fs-1 text-success"></i>
              </div>
            </div>
            <h6 class="fw-bold mb-3" id="successMessage">Message de succès</h6>
            <div class="d-flex justify-content-center">
              <button type="button" class="btn btn-success btn-lg px-4" data-bs-dismiss="modal" style="border-radius:8px;">
                <i class="bi bi-check-circle me-2"></i>Continuer
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Modal d'erreur --}}
    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:16px;overflow:hidden;">
          <div class="modal-header text-white border-0" style="background:linear-gradient(135deg,#dc3545 0%,#c82333 100%);">
            <div class="d-flex align-items-center">
              <div class="me-3">
                <div class="bg-white bg-opacity-25 rounded-circle p-2">
                  <i class="bi bi-exclamation-circle-fill fs-4"></i>
                </div>
              </div>
              <div>
                <h5 class="modal-title fw-bold" id="errorModalLabel">Erreur</h5>
                <p class="mb-0 small opacity-75">Une erreur s'est produite</p>
              </div>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
          </div>
          <div class="modal-body py-4 px-4 text-center">
            <div class="mb-3">
              <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width:80px;height:80px;">
                <i class="bi bi-exclamation-triangle-fill fs-1 text-danger"></i>
              </div>
            </div>
            <h6 class="fw-bold mb-3" id="errorMessage">Message d'erreur</h6>
            <div class="d-flex justify-content-center">
              <button type="button" class="btn btn-danger btn-lg px-4" data-bs-dismiss="modal" style="border-radius:8px;">
                <i class="bi bi-x-circle me-2"></i>Fermer
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

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

  {{-- Session Timeout Handler --}}
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Only run on authenticated pages
      if (!document.getElementById('session-countdown')) return;

      const sessionLifetime = {
        {
          config('session.lifetime')
        }
      }; // en minutes
      const warningTime = 1; // minutes avant expiration pour afficher le warning
      const countdownElement = document.getElementById('session-countdown');
      const sessionIndicator = document.querySelector('.session-timeout-indicator');

      let remainingSeconds = sessionLifetime * 60;
      let timerInterval = null;
      let warningShown = false;

      // Fonction pour formater le temps
      function formatTime(seconds) {
        const mins = Math.floor(seconds / 60);
        const secs = seconds % 60;
        return `${mins}:${secs.toString().padStart(2, '0')}`;
      }

      // Fonction pour mettre à jour l'affichage
      function updateCountdown() {
        if (countdownElement) {
          countdownElement.textContent = formatTime(remainingSeconds);
        }

        // Afficher le warning quand il reste moins de X minutes
        if (remainingSeconds <= warningTime * 60 && !warningShown) {
          warningShown = true;
          if (countdownElement) {
            countdownElement.classList.remove('bg-warning', 'text-dark');
            countdownElement.classList.add('bg-danger', 'text-white');
            countdownElement.parentElement.classList.add('animate-pulse');
          }
        }

        // Déconnexion automatique quand le temps est écoulé
        if (remainingSeconds <= 0) {
          clearInterval(timerInterval);
          // Envoyer une requête pour déconnecter
          fetch('{{ route("logout") }}', {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
              'Content-Type': 'application/json'
            }
          }).then(() => {
            window.location.href = '{{ route("login") }}?timeout=1';
          }).catch(() => {
            window.location.href = '{{ route("login") }}?timeout=1';
          });
          return;
        }

        remainingSeconds--;
      }

      // Fonction pour prolonger la session (ping au serveur)
      function extendSession() {
        fetch('/session/extend', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
          }
        }).then(response => {
          if (response.ok) {
            // Réinitialiser le compte à rebours
            remainingSeconds = sessionLifetime * 60;
            warningShown = false;
            if (countdownElement) {
              countdownElement.classList.remove('bg-danger', 'text-white');
              countdownElement.classList.add('bg-warning', 'text-dark');
              countdownElement.parentElement.classList.remove('animate-pulse');
            }
          }
        }).catch(err => {
          console.error('Erreur lors de la prolongation de session:', err);
        });
      }

      // Démarrer le timer
      timerInterval = setInterval(updateCountdown, 1000);

      // Prolonger la session lors des interactions utilisateur
      const extendOnActivity = () => {
        // Seulement si on est dans les dernières minutes
        if (remainingSeconds <= warningTime * 60 * 2) {
          extendSession();
        }
      };

      // Écouter les événements utilisateur pour prolonger la session
      document.addEventListener('click', extendOnActivity, {
        passive: true
      });
      document.addEventListener('keypress', extendOnActivity, {
        passive: true
      });
      document.addEventListener('mousemove', extendOnActivity, {
        passive: true
      });
      document.addEventListener('scroll', extendOnActivity, {
        passive: true
      });

      // Prolonger automatiquement la session toutes les minutes
      setInterval(extendSession, (sessionLifetime - 1) * 60 * 1000);
    });
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

  {{-- Modal de succès --}}
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      @if(session('success'))
      const successMessage = '{{ session('
      success ') }}';
      const successModalEl = document.getElementById('successModal');
      const successMessageEl = document.getElementById('successMessage');

      if (successMessageEl) {
        successMessageEl.textContent = successMessage;
      }

      if (successModalEl) {
        const modal = new bootstrap.Modal(successModalEl);
        modal.show();
      }
      @endif

      @if(session('error'))
      const errorMessage = '{{ session('
      error ') }}';
      const errorModalEl = document.getElementById('errorModal');
      const errorMessageEl = document.getElementById('errorMessage');

      if (errorMessageEl) {
        errorMessageEl.textContent = errorMessage;
      }

      if (errorModalEl) {
        const modal = new bootstrap.Modal(errorModalEl);
        modal.show();
      }
      @endif
    });
  </script>

  {{-- ========================================================= --}}
  {{-- FONCTIONS COMMUNES POUR LES GRAPHIQUES SUPER ADMIN    --}}
  {{-- ========================================================= --}}
  <script>
    // Fonction pour détecter le thème actuel
    function getThemeColors() {
      const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
      return {
        isDark: isDark,
        text: isDark ? '#e0e0e0' : '#212529',
        textMuted: isDark ? '#a0a0a0' : '#6c757d',
        grid: isDark ? '#2d2d2d' : '#e9ecef',
        bg: isDark ? '#1a1a1a' : '#ffffff',
        legend: isDark ? '#ffffff' : '#212529'
      };
    }

    // Initialisation des graphiques du SuperAdmin
    function initSuperAdminCharts() {
      if (typeof ApexCharts === 'undefined') {
        console.warn('ApexCharts pas encore chargé');
        return false;
      }

      const themeColors = getThemeColors();

      // Vérifier si les éléments existent
      const revenueChartEl = document.getElementById('revenue-chart');
      const contractsChartEl = document.getElementById('contracts-chart');
      const distributionChartEl = document.getElementById('distribution-chart');
      const facturesChartEl = document.getElementById('factures-chart');
      const contratsStatusChartEl = document.getElementById('contrats-status-chart');
      const propositionsChartEl = document.getElementById('propositions-chart');

      // 1. Graphique des Revenus Mensuels
      if (revenueChartEl && typeof REVENUE_DATA !== 'undefined') {
        new ApexCharts(revenueChartEl, {
          series: [{
            name: 'Revenus',
            data: REVENUE_DATA
          }],
          chart: {
            type: 'area',
            height: 300,
            toolbar: {
              show: false
            },
            animations: {
              enabled: true,
              easing: 'easeinout',
              speed: 800
            },
            parentHeightOffset: 0,
            background: themeColors.bg
          },
          colors: ['#0d6efd'],
          dataLabels: {
            enabled: false
          },
          stroke: {
            curve: 'smooth',
            width: 2
          },
          fill: {
            type: 'gradient',
            gradient: {
              shadeIntensity: 1,
              opacityFrom: 0.4,
              opacityTo: 0.05,
              stops: [0, 100]
            }
          },
          xaxis: {
            categories: REVENUE_LABELS || ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'],
            labels: {
              style: {
                colors: themeColors.textMuted
              }
            }
          },
          yaxis: {
            labels: {
              style: {
                colors: themeColors.textMuted
              },
              formatter: (val) => val >= 1000 ? (val / 1000).toFixed(0) + 'K' : val
            }
          },
          tooltip: {
            theme: themeColors.isDark ? 'dark' : 'light',
            y: {
              formatter: (val) => val.toLocaleString() + ' FCA'
            }
          },
          grid: {
            borderColor: themeColors.grid
          }
        }).render();
      }

      // 1b. Graphique des Entreprises Créées par Mois (Courbe)
      if (document.getElementById('entreprises-chart') && typeof ENTREPRISES_PAR_MOIS !== 'undefined') {
        new ApexCharts(document.getElementById('entreprises-chart'), {
          series: [{
            name: 'Entreprises',
            data: ENTREPRISES_PAR_MOIS
          }],
          chart: {
            type: 'line',
            height: 300,
            toolbar: {
              show: false
            },
            animations: {
              enabled: true,
              easing: 'easeinout',
              speed: 800,
              animateGradually: {
                enabled: true,
                delay: 150
              },
              dynamicAnimation: {
                enabled: true,
                speed: 350
              }
            },
            parentHeightOffset: 0,
            background: themeColors.bg,
            zoom: {
              enabled: false
            }
          },
          colors: ['#0d6efd'],
          dataLabels: {
            enabled: true,
            style: {
              colors: ['#fff'],
              fontWeight: 'bold',
              fontSize: '12px'
            },
            background: {
              enabled: true,
              foreColor: '#e83e8c',
              borderRadius: 4,
              padding: 4,
              opacity: 0.9
            },
          },
          stroke: {
            curve: 'smooth',
            width: 4,
            lineCap: 'round'
          },
          markers: {
            size: 8,
            colors: ['#e83e8c'],
            strokeColors: '#fff',
            strokeWidth: 3,
            hover: {
              size: 10
            }
          },
          fill: {
            type: 'gradient',
            gradient: {
              shadeIntensity: 1,
              opacityFrom: 0.4,
              opacityTo: 0.15,
              stops: [0, 100],
              colorStops: [{
                offset: 0,
                color: '#0d6efd',
                opacity: 0.4
              }, {
                offset: 100,
                color: '#0d6efd',
                opacity: 0
              }]
            }
          },
          xaxis: {
            categories: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'],
            labels: {
              style: {
                colors: themeColors.textMuted,
                fontSize: '11px'
              }
            },
            axisBorder: {
              color: themeColors.grid
            },
            axisTicks: {
              color: themeColors.grid
            }
          },
          yaxis: {
            labels: {
              style: {
                colors: themeColors.textMuted,
                fontSize: '11px'
              },
              min: 0
            },
            min: 0
          },
          tooltip: {
            theme: themeColors.isDark ? 'dark' : 'light',
            y: {
              formatter: (val) => val + ' entreprises'
            }
          },
          grid: {
            borderColor: themeColors.grid,
            strokeDashArray: 4
          },
          legend: {
            show: true,
            position: 'top',
            horizontalAlign: 'right',
            labels: {
              colors: themeColors.legend
            }
          }
        }).render();
      }

      // 2. Graphique Évolution des Contrats
      if (contractsChartEl && typeof CONTRATS_PAR_MOIS !== 'undefined') {
        new ApexCharts(contractsChartEl, {
          series: [{
              name: 'Contrats Créés',
              data: CONTRATS_PAR_MOIS
            },
            {
              name: 'Contrats Expirés',
              data: CONTRATS_EXPIRES_PAR_MOIS || Array(12).fill(0)
            }
          ],
          chart: {
            type: 'bar',
            height: 300,
            toolbar: {
              show: false
            },
            animations: {
              enabled: true,
              easing: 'easeinout',
              speed: 800
            },
            parentHeightOffset: 0,
            background: themeColors.bg
          },
          colors: ['#198754', '#dc3545'],
          plotOptions: {
            bar: {
              horizontal: false,
              columnWidth: '55%',
              borderRadius: 8
            }
          },
          dataLabels: {
            enabled: false
          },
          stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
          },
          xaxis: {
            categories: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'],
            labels: {
              style: {
                colors: themeColors.textMuted
              }
            }
          },
          yaxis: {
            labels: {
              style: {
                colors: themeColors.textMuted
              }
            }
          },
          fill: {
            opacity: 1
          },
          tooltip: {
            theme: themeColors.isDark ? 'dark' : 'light'
          },
          legend: {
            position: 'top',
            horizontalAlign: 'right',
            labels: {
              colors: themeColors.legend
            }
          },
          grid: {
            borderColor: themeColors.grid
          }
        }).render();
      }

      // 3. Graphique Répartition par Formule
      if (distributionChartEl && typeof DISTRIBUTION_PAR_FORMULE !== 'undefined') {
        const total = DISTRIBUTION_PAR_FORMULE.reduce((a, b) => a + b, 0);
        let seriesData = DISTRIBUTION_PAR_FORMULE;
        if (total === 0) seriesData = [1, 1, 1, 1];

        new ApexCharts(distributionChartEl, {
          series: seriesData,
          labels: ['Essai', 'Basic', 'Standard', 'Premium'],
          chart: {
            type: 'donut',
            height: 280,
            parentHeightOffset: 0,
            background: themeColors.bg
          },
          colors: ['#0d6efd', '#ffc107', '#198754', '#6f42c1'],
          plotOptions: {
            pie: {
              donut: {
                size: '65%',
                labels: {
                  show: true,
                  total: {
                    show: true,
                    label: 'Total',
                    color: themeColors.text,
                    formatter: () => total.toString()
                  },
                  value: {
                    color: themeColors.text
                  }
                }
              }
            }
          },
          dataLabels: {
            enabled: false
          },
          legend: {
            position: 'bottom',
            labels: {
              colors: themeColors.legend
            }
          },
          stroke: {
            width: 0
          },
          tooltip: {
            theme: themeColors.isDark ? 'dark' : 'light'
          }
        }).render();
      }

      // 4. Graphique Statut des Factures
      if (facturesChartEl && typeof FACTURES_DATA !== 'undefined') {
        const totalFactures = FACTURES_DATA.reduce((a, b) => a + b, 0);
        let seriesFactures = FACTURES_DATA;
        if (totalFactures === 0) seriesFactures = [1, 1, 1];

        new ApexCharts(facturesChartEl, {
          series: seriesFactures,
          labels: ['Payées', 'En attente', 'Impayées'],
          chart: {
            type: 'donut',
            height: 280,
            parentHeightOffset: 0,
            background: themeColors.bg
          },
          colors: ['#198754', '#ffc107', '#dc3545'],
          plotOptions: {
            pie: {
              donut: {
                size: '65%',
                labels: {
                  show: true,
                  total: {
                    show: true,
                    label: 'Total',
                    color: themeColors.text,
                    formatter: () => totalFactures.toString()
                  },
                  value: {
                    color: themeColors.text
                  }
                }
              }
            }
          },
          dataLabels: {
            enabled: false
          },
          legend: {
            position: 'bottom',
            labels: {
              colors: themeColors.legend
            }
          },
          stroke: {
            width: 0
          },
          tooltip: {
            theme: themeColors.isDark ? 'dark' : 'light'
          }
        }).render();
      }

      // 5. Graphique Statut des Contrats
      if (contratsStatusChartEl && typeof CONTRATS_STATUS_DATA !== 'undefined') {
        const totalContrats = CONTRATS_STATUS_DATA.reduce((a, b) => a + b, 0);
        let seriesContrats = CONTRATS_STATUS_DATA;
        if (totalContrats === 0) seriesContrats = [1, 1, 1, 1];

        new ApexCharts(contratsStatusChartEl, {
          series: seriesContrats,
          labels: ['Actifs', 'En cours', 'Expirés', 'Résiliés'],
          chart: {
            type: 'donut',
            height: 280,
            parentHeightOffset: 0,
            background: themeColors.bg
          },
          colors: ['#198754', '#0d6efd', '#dc3545', '#6c757d'],
          plotOptions: {
            pie: {
              donut: {
                size: '65%',
                labels: {
                  show: true,
                  total: {
                    show: true,
                    label: 'Total',
                    color: themeColors.text,
                    formatter: () => totalContrats.toString()
                  },
                  value: {
                    color: themeColors.text
                  }
                }
              }
            }
          },
          dataLabels: {
            enabled: false
          },
          legend: {
            position: 'bottom',
            labels: {
              colors: themeColors.legend
            }
          },
          stroke: {
            width: 0
          },
          tooltip: {
            theme: themeColors.isDark ? 'dark' : 'light'
          }
        }).render();
      }

      // 6. Graphique Propositions
      if (propositionsChartEl && typeof PROPOSITIONS_DATA !== 'undefined') {
        const totalPropositions = PROPOSITIONS_DATA.reduce((a, b) => a + b, 0);
        let seriesPropositions = PROPOSITIONS_DATA;
        if (totalPropositions === 0) seriesPropositions = [1, 1, 1, 1];

        new ApexCharts(propositionsChartEl, {
          series: seriesPropositions,
          labels: ['Soumis', 'En négociation', 'Signé', 'Refusé'],
          chart: {
            type: 'donut',
            height: 280,
            parentHeightOffset: 0,
            background: themeColors.bg
          },
          colors: ['#ffc107', '#0d6efd', '#198754', '#dc3545'],
          plotOptions: {
            pie: {
              donut: {
                size: '65%',
                labels: {
                  show: true,
                  total: {
                    show: true,
                    label: 'Total',
                    color: themeColors.text,
                    formatter: () => totalPropositions.toString()
                  },
                  value: {
                    color: themeColors.text
                  }
                }
              }
            }
          },
          dataLabels: {
            enabled: false
          },
          legend: {
            position: 'bottom',
            labels: {
              colors: themeColors.legend
            }
          },
          stroke: {
            width: 0
          },
          tooltip: {
            theme: themeColors.isDark ? 'dark' : 'light'
          }
        }).render();
      }

      return true;
    }

    // Mise à jour des graphiques lors du changement de thème
    function updateSuperAdminChartsTheme() {
      // Les graphiques sont recréés automatiquement
      initSuperAdminCharts();
    }

    // Écouter les changements de thème
    document.addEventListener('theme-changed', updateSuperAdminChartsTheme);
  </script>

  {{-- Scripts spécifiques aux pages (injectés APRÈS ApexCharts) --}}
  @stack('scripts')

  {{-- Initialisation des graphiques SuperAdmin si présent --}}
  @if(request()->routeIs('admin.superadmin') || request()->is('admin/superadmin*'))
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Attendre un peu pour s'assurer que les données sont chargées
      setTimeout(function() {
        initSuperAdminCharts();
      }, 100);
    });
  </script>
  @endif

</body>

</html>