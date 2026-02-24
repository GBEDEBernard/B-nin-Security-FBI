<!doctype html>
<html lang="fr">
<!--begin::Head-->

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>@yield('title', 'Bénin Security - Gestion de Sécurité')</title>

  <!--begin::Accessibility Meta Tags-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
  <meta name="color-scheme" content="light dark" />
  <meta name="theme-color" content="#198754" media="(prefers-color-scheme: light)" />
  <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
  <!--end::Accessibility Meta Tags-->

  <!--begin::Primary Meta Tags-->
  <meta name="title" content="Bénin Security - Gestion de Sécurité Professionnelle" />
  <meta name="author" content="Bénin Security" />
  <meta
    name="description"
    content="Plateforme de gestion de sécurité professionnelle au Bénin - Gestion des clients, employés, contrats, pointages et facturation." />
  <meta
    name="keywords"
    content="sécurité, Benin, gestion, surveillance, gardiennage,企业管理" />
  <!--end::Primary Meta Tags-->

  <!--begin::Accessibility Features-->
  <meta name="supported-color-schemes" content="light dark" />
  <!--end::Accessibility Features-->

  <!--begin::Fonts-->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
    integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
    crossorigin="anonymous"
    media="print"
    onload="this.media = 'all'" />
  <!--end::Fonts-->

  <!--begin::Third Party Plugin(OverlayScrollbars)-->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
    crossorigin="anonymous" />
  <!--end::Third Party Plugin(OverlayScrollbars)-->

  <!--begin::Third Party Plugin(Bootstrap Icons)-->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
    crossorigin="anonymous" />
  <!--end::Third Party Plugin(Bootstrap Icons)-->

  <!--begin::Required Plugin(AdminLTE)-->
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.css') }}">
  <!--end::Required Plugin(AdminLTE)-->

  <!-- apexcharts -->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
    integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
    crossorigin="anonymous" />

  <!-- Custom Styles -->
  @stack('styles')

  <style>
    /* Custom Global Styles */
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

    /* Smooth transitions */
    .app-wrapper {
      transition: all 0.3s ease;
    }

    /* Custom scrollbar */
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
  </style>
</head>
<!--end::Head-->
<!--begin::Body-->

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
  <!--begin::App Wrapper-->
  <div class="app-wrapper">

    <!--begin::App Main-->
    @include('layouts.header')
    @include('layouts.sidebar')

    @if(session('error'))
    <div class="alert alert-danger m-3" role="alert">
      {{ session('error') }}
    </div>
    @endif

    @if(session('success'))
    <div class="alert alert-success m-3" role="alert">
      {{ session('success') }}
    </div>
    @endif

    <!-- Contenu principal de la page -->
    <main class="app-main">
      @yield('content')
    </main>

    @include('layouts.footer')

    <!-- Modal de confirmation de connexion à l'entreprise -->
    <div class="modal fade" id="connectModal" tabindex="-1" aria-labelledby="connectModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="connectModalLabel">
              <i class="bi bi-building me-2"></i>Confirmer la connexion
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
          </div>
          <div class="modal-body">
            <p class="mb-2">
              Voulez-vous vous connecter au tableau de bord de l'entreprise
            </p>
            <p class="mb-0">
              <strong id="entrepriseNom" class="text-success"></strong> ?
            </p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
              <i class="bi bi-x-circle me-1"></i>Annuler
            </button>
            <button type="button" class="btn btn-success btn-sm" id="confirmConnectBtn">
              <i class="bi bi-check-circle me-1"></i>Confirmer
            </button>
          </div>
        </div>
      </div>
    </div>
    <!--end::Modal-->
  </div>

  <!--begin::Third Party Plugin(OverlayScrollbars)-->
  <script
    src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"
    crossorigin="anonymous"></script>
  <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
  <script
    src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    crossorigin="anonymous"></script>
  <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"
    crossorigin="anonymous"></script>
  <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
  <script src="{{ asset('dist/js/adminlte.js') }}"></script>

  <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
  <script>
    const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
    const Default = {
      scrollbarTheme: 'os-theme-light',
      scrollbarAutoHide: 'leave',
      scrollbarClickScroll: true,
    };
    document.addEventListener('DOMContentLoaded', function() {
      const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);

      // Disable OverlayScrollbars on mobile devices to prevent touch interference
      const isMobile = window.innerWidth <= 992;

      if (
        sidebarWrapper &&
        OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined &&
        !isMobile
      ) {
        OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
          scrollbars: {
            theme: Default.scrollbarTheme,
            autoHide: Default.scrollbarAutoHide,
            clickScroll: Default.scrollbarClickScroll,
          },
        });
      }
    });
  </script>
  <!--end::OverlayScrollbars Configure-->

  <!-- Color Mode Toggler -->
  <script>
    (() => {
      "use strict";

      const storedTheme = localStorage.getItem("theme");

      const getPreferredTheme = () => {
        if (storedTheme) {
          return storedTheme;
        }

        return window.matchMedia("(prefers-color-scheme: dark)").matches ?
          "dark" :
          "light";
      };

      const setTheme = function(theme) {
        if (
          theme === "auto" &&
          window.matchMedia("(prefers-color-scheme: dark)").matches
        ) {
          document.documentElement.setAttribute("data-bs-theme", "dark");
        } else {
          document.documentElement.setAttribute("data-bs-theme", theme);
        }
      };

      setTheme(getPreferredTheme());

      const showActiveTheme = (theme, focus = false) => {
        const themeSwitcher = document.querySelector("#bd-theme");

        if (!themeSwitcher) {
          return;
        }

        const themeSwitcherText = document.querySelector("#bd-theme-text");
        const activeThemeIcon = document.querySelector(".theme-icon-active i");
        const btnToActive = document.querySelector(
          `[data-bs-theme-value="${theme}"]`
        );

        if (!btnToActive) return;

        const svgOfActiveBtn = btnToActive.querySelector("i").getAttribute("class");

        for (const element of document.querySelectorAll("[data-bs-theme-value]")) {
          element.classList.remove("active");
          element.setAttribute("aria-pressed", "false");
        }

        btnToActive.classList.add("active");
        btnToActive.setAttribute("aria-pressed", "true");

        if (activeThemeIcon) {
          activeThemeIcon.setAttribute("class", svgOfActiveBtn);
        }

        if (themeSwitcherText) {
          const themeSwitcherLabel = `${themeSwitcherText.textContent} (${btnToActive.dataset.bsThemeValue})`;
          themeSwitcher.setAttribute("aria-label", themeSwitcherLabel);
        }

        if (focus) {
          themeSwitcher.focus();
        }
      };

      window
        .matchMedia("(prefers-color-scheme: dark)")
        .addEventListener("change", () => {
          if (storedTheme !== "light" || storedTheme !== "dark") {
            setTheme(getPreferredTheme());
          }
        });

      window.addEventListener("DOMContentLoaded", () => {
        showActiveTheme(getPreferredTheme());

        for (const toggle of document.querySelectorAll("[data-bs-theme-value]")) {
          toggle.addEventListener("click", () => {
            const theme = toggle.getAttribute("data-bs-theme-value");
            localStorage.setItem("theme", theme);
            setTheme(theme);
            showActiveTheme(theme, true);
          });
        }
      });
    })();
  </script>

  <!-- ApexCharts -->
  <script
    src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
    integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8="
    crossorigin="anonymous"></script>

  <!-- Page Specific Scripts -->
  @stack('scripts')

  <!-- Script pour le modal de connexion aux entreprises -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      let entrepriseId = null;
      let csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

      // Si pas de token CSRF dans meta, on utilise le token du formulaire
      if (!csrfToken) {
        csrfToken = '{{ csrf_token() }}';
      }

      // Récupérer les données de l'entreprise lors de l'ouverture du modal
      const connectModal = document.getElementById('connectModal');
      if (connectModal) {
        connectModal.addEventListener('show.bs.modal', function(event) {
          const button = event.relatedTarget;
          if (button) {
            entrepriseId = button.getAttribute('data-entreprise-id');
            const entrepriseNom = button.getAttribute('data-entreprise-nom');

            const nomElement = document.getElementById('entrepriseNom');
            if (nomElement) {
              nomElement.textContent = entrepriseNom;
            }
          }
        });
      }

      // Gérer le clic sur le bouton confirmer
      const confirmBtn = document.getElementById('confirmConnectBtn');
      if (confirmBtn) {
        confirmBtn.addEventListener('click', function() {
          if (!entrepriseId) {
            alert('Erreur: ID de l\'entreprise manquant');
            return;
          }

          // Désactiver le bouton pendant la requête
          confirmBtn.disabled = true;
          confirmBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Connexion...';

          // Effectuer la requête AJAX
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
                // Si redirection, suivre la redirection
                window.location.href = response.url;
              } else {
                return response.json().then(data => {
                  if (data.success || data.message) {
                    // Rediriger vers le dashboard de l'entreprise
                    window.location.href = data.redirect || '/admin/entreprise';
                  } else {
                    throw new Error(data.message || 'Erreur lors de la connexion');
                  }
                }).catch(() => {
                  // Si ce n'est pas du JSON, essayer de suivre la redirection
                  window.location.href = '/admin/entreprise';
                });
              }
            })
            .catch(error => {
              console.error('Erreur:', error);
              alert('Une erreur est survenue lors de la connexion. Veuillez réessayer.');
              confirmBtn.disabled = false;
              confirmBtn.innerHTML = '<i class="bi bi-check-circle me-1"></i>Confirmer';

              // Fermer le modal
              const modalEl = document.getElementById('connectModal');
              if (modalEl) {
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) {
                  modal.hide();
                }
              }
            });
        });
      }
    });
  </script>

</body>
<!--end::Body-->

</html>