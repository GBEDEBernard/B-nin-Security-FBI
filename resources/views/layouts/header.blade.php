 <!--begin::Header-->
 <nav class="app-header navbar navbar-expand shadow-sm" style="min-height: 60px; background-color: var(--bs-body-bg); border-bottom: 1px solid var(--bs-border-color);">
   <!--begin::Container-->
   <div class="container-fluid">
     <!--begin::Start Navbar Links-->
     <ul class="navbar-nav align-items-center">
       <li class="nav-item">
         <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
           <i class="bi bi-list"></i>
         </a>
       </li>
        <li class="nav-item d-none d-md-block">
          <a href="{{ route('admin') }}" class="nav-link">
            <i class="bi bi-house-door me-1"></i> Accueil
          </a>
        </li>
        @php
        $currentRoute = request()->route()?->getName();
        $breadcrumb = '';
        if ($currentRoute && str_starts_with($currentRoute, 'admin.')) {
            $parts = explode('.', $currentRoute);
            array_shift($parts);
            $labels = [
                'superadmin' => 'Super Admin',
                'entreprise' => 'Entreprise',
                'roles' => 'Rôles',
                'index' => 'Liste',
                'create' => 'Création',
                'edit' => 'Modification',
                'show' => 'Détails',
                'clients' => 'Clients',
                'employes' => 'Employés',
                'contrats' => 'Contrats',
                'facturation' => 'Facturation',
                'rapports' => 'Rapports',
                'parametres' => 'Paramètres',
                'utilisateurs' => 'Utilisateurs',
                'notifications' => 'Notifications',
                'abonnements' => 'Abonnements',
                'propositions' => 'Propositions',
                'apk' => 'Application Mobile',
                'journal' => 'Journal',
                'modeles' => 'Modèles',
                'affectations' => 'Affectations',
                'incidents' => 'Incidents',
                'missions' => 'Missions',
                'pointages' => 'Pointages',
                'conges' => 'Congés',
            ];
            $mapped = array_map(fn($p) => $labels[$p] ?? ucfirst(str_replace(['_', '-'], ' ', $p)), $parts);
            $breadcrumb = implode(' › ', $mapped);
        }
        @endphp
        @if($breadcrumb)
        <li class="nav-item d-none d-md-block">
          <span class="nav-link text-muted" style="cursor: default;">
            <i class="bi bi-chevron-right" style="font-size: 0.7rem;"></i> {{ $breadcrumb }}
          </span>
        </li>
        @endif
      </ul>
      <!--end::Start Navbar Links-->

     <!--begin::End Navbar Links-->
     <ul class="navbar-nav ms-auto align-items-center">
       <!--begin::Navbar Search-->
       <li class="nav-item">
         <a class="nav-link" data-widget="navbar-search" href="#" role="button">
           <i class="bi bi-search"></i>
         </a>
       </li>
       <!--end::Navbar Search-->

       @auth
       @php
       $currentUser = Auth::user();
       $guardPrefix = 'superadmin';
       if ($currentUser instanceof \App\Models\Employe) {
           $guardPrefix = $currentUser->estAgent() ? 'agent' : 'entreprise';
       } elseif ($currentUser instanceof \App\Models\Client) {
           $guardPrefix = 'client';
       }
       $notifRoute = route("admin.{$guardPrefix}.mes-notifications.index");
       @endphp
       <!--begin::Notifications Dropdown Menu-->
       <li class="nav-item dropdown notifications-dropdown">
         <a class="nav-link" data-bs-toggle="dropdown" href="#" id="notifDropdownToggle">
           <i class="bi bi-bell-fill"></i>
           <span class="navbar-badge badge text-bg-warning" id="notifBadge" style="display:none;">0</span>
         </a>
         <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end" id="notifDropdown" aria-labelledby="notifDropdownToggle">
           <span class="dropdown-item dropdown-header" id="notifHeader">Notifications</span>
           <div id="notifList">
             <div class="dropdown-item text-center text-muted py-3">
               <div class="spinner-border spinner-border-sm me-2" role="status"></div> Chargement...
             </div>
           </div>
           <div class="dropdown-divider"></div>
           <a href="{{ $notifRoute }}" class="dropdown-item dropdown-footer">
             <i class="bi bi-bell me-1"></i> Voir toutes les notifications
           </a>
         </div>
       </li>
       @endauth
       <!--end::Notifications Dropdown Menu-->

       <!--begin::Fullscreen Toggle-->
       <li class="nav-item">
         <a class="nav-link" href="#" data-lte-toggle="fullscreen">
           <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
           <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
         </a>
       </li>
       <!--end::Fullscreen Toggle-->

        <!--begin::User Menu Dropdown-->
        @auth
        @php
        $userName = $currentUser->name ?? $currentUser->nomComplet ?? $currentUser->nomAffichage ?? 'Utilisateur';
        $userInitial = strtoupper(substr($userName, 0, 1));
        $userPhoto = $currentUser->photo ?? null;
        $roleName = $currentUser->roles->first()?->name ?? 'Membre';
        $roleLabel = match($roleName) {
            'super_admin' => 'Super Administrateur',
            'general_director' => 'Directeur Général',
            'deputy_director' => 'Directeur Adjoint',
            'operations_director' => 'Directeur des Opérations',
            'supervisor' => 'Superviseur',
            'controller' => 'Contrôleur',
            'agent' => 'Agent',
            'client_individual' => 'Client Particulier',
            'client_company' => 'Client Entreprise',
            default => ucfirst(str_replace(['_', '-'], ' ', $roleName)),
        };
        $profilRoute = route("admin.{$guardPrefix}.profil.index");
        $rolesRoute = $guardPrefix === 'superadmin' ? route('admin.superadmin.roles.index') : ($guardPrefix === 'entreprise' ? route('admin.entreprise.roles.index') : null);
        $settingsRoute = $guardPrefix === 'superadmin' ? route('admin.superadmin.parametres.index') : ($guardPrefix === 'entreprise' ? route('admin.entreprise.profile') : null);
        @endphp
        <li class="nav-item dropdown user-menu ms-2">
          <a href="#" class="nav-link dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="dropdown">
            <div class="user-image-wrapper">
              @if($userPhoto)
              <img
                src="{{ asset('storage/' . $userPhoto) }}"
                class="user-image rounded-circle shadow"
                alt="User Image" />
              @else
              <div class="user-avatar rounded-circle shadow">
                {{ $userInitial }}
              </div>
              @endif
            </div>
            <span class="d-none d-lg-inline text-truncate" style="max-width: 150px;" title="{{ $userName }}">{{ $userName }}</span>
          </a>
         <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end profile-dropdown">
            <div class="profile-dropdown-header">
              @if($userPhoto)
              <img
                src="{{ asset('storage/' . $userPhoto) }}"
                class="profile-dropdown-avatar"
                alt="User Image" />
              @else
              <div class="profile-dropdown-avatar profile-dropdown-avatar-initial">
                {{ strtoupper(substr($userName, 0, 2)) }}
              </div>
              @endif
              <div class="profile-dropdown-info">
                <p class="profile-dropdown-name">{{ $userName }}</p>
                <p class="profile-dropdown-email">{{ Auth::user()->email }}</p>
                <span class="profile-dropdown-badge">{{ $roleLabel }}</span>
              </div>
            </div>
            <div class="profile-dropdown-body">
              <div class="profile-dropdown-item">
                <div class="profile-dropdown-item-icon">
                  <i class="bi bi-person"></i>
                </div>
                <div class="profile-dropdown-item-content">
                  <span class="profile-dropdown-item-label">Compte</span>
                  <span class="profile-dropdown-item-value">{{ $roleLabel }}</span>
                </div>
              </div>
              <div class="profile-dropdown-item">
                <div class="profile-dropdown-item-icon">
                  <i class="bi bi-calendar"></i>
                </div>
                <div class="profile-dropdown-item-content">
                  <span class="profile-dropdown-item-label">Membre depuis</span>
                  <span class="profile-dropdown-item-value">{{ Auth::user()->created_at->format('d/m/Y') }}</span>
                </div>
              </div>
              @if(Auth::user()->last_login_at)
              <div class="profile-dropdown-item">
                <div class="profile-dropdown-item-icon">
                  <i class="bi bi-clock-history"></i>
                </div>
                <div class="profile-dropdown-item-content">
                  <span class="profile-dropdown-item-label">Dernière connexion</span>
                  <span class="profile-dropdown-item-value">{{ Auth::user()->last_login_at->diffForHumans() }}</span>
                </div>
              </div>
              @endif
              @if(Auth::user()->telephone)
              <div class="profile-dropdown-item">
                <div class="profile-dropdown-item-icon">
                  <i class="bi bi-telephone"></i>
                </div>
                <div class="profile-dropdown-item-content">
                  <span class="profile-dropdown-item-label">Téléphone</span>
                  <span class="profile-dropdown-item-value">{{ Auth::user()->telephone }}</span>
                </div>
              </div>
              @endif
            </div>
            <div class="profile-dropdown-footer">
              <a href="{{ $profilRoute }}" class="profile-dropdown-btn profile-dropdown-btn-primary">
                <i class="bi bi-person-circle"></i> Mon Profil
              </a>
              <a href="{{ $rolesRoute ?? '#' }}" class="profile-dropdown-btn profile-dropdown-btn-outline">
                <i class="bi bi-shield"></i> Rôles
              </a>
              <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="profile-dropdown-btn profile-dropdown-btn-danger">
                  <i class="bi bi-box-arrow-right"></i> Déconnexion
                </button>
              </form>
            </div>
          </div>
       </li>
       @else
       <li class="nav-item">
         <a href="{{ route('login') }}" class="nav-link">
           <i class="bi bi-box-arrow-in-right me-1"></i> Connexion
         </a>
       </li>
       <li class="nav-item">
         <a href="{{ route('register') }}" class="nav-link">
           <i class="bi bi-person-plus me-1"></i> Inscription
         </a>
       </li>
       @endauth
       <!--end::User Menu Dropdown-->

       <!--begin::Theme Toggle-->
       <li class="nav-item theme-toggle">
         <button
           class="btn btn-link nav-link p-2"
           id="bd-theme"
           type="button"
           aria-expanded="false"
           data-bs-toggle="dropdown"
           data-bs-display="static">
           <span class="theme-icon-active">
             <i class="bi bi-palette"></i>
           </span>
         </button>
         <ul
           class="dropdown-menu dropdown-menu-end"
           aria-labelledby="bd-theme-text"
           style="--bs-dropdown-min-width: 8rem;">
           <li>
             <button
               type="button"
               class="dropdown-item d-flex align-items-center active"
               data-bs-theme-value="light"
               aria-pressed="false">
               <i class="bi bi-sun-fill me-2"></i>
               Clair
               <i class="bi bi-check-lg ms-auto d-none"></i>
             </button>
           </li>
           <li>
             <button
               type="button"
               class="dropdown-item d-flex align-items-center"
               data-bs-theme-value="dark"
               aria-pressed="false">
               <i class="bi bi-moon-fill me-2"></i>
               Sombre
               <i class="bi bi-check-lg ms-auto d-none"></i>
             </button>
           </li>
           <li>
             <button
               type="button"
               class="dropdown-item d-flex align-items-center"
               data-bs-theme-value="auto"
               aria-pressed="true">
               <i class="bi bi-circle-fill-half-stroke me-2"></i>
               Auto
               <i class="bi bi-check-lg ms-auto d-none"></i>
             </button>
           </li>
         </ul>
       </li>
       <!--end::Theme Toggle-->

     </ul>
     <!--end::End Navbar Links-->

   </div>
   <!--end::Container-->
 </nav>
 <!--end::Header-->

 <style>
   /* Custom Header Styles - Responsive & Fixed Height */
   .app-header {
     min-height: 60px !important;
     max-height: 60px !important;
   }

   .app-header .navbar-nav {
     align-items: center;
   }

   .app-header .nav-link {
     padding-top: 0.5rem;
     padding-bottom: 0.5rem;
   }

   .user-image-wrapper {
     position: relative;
     width: 36px;
     height: 36px;
     overflow: hidden;
     flex-shrink: 0;
   }

   .user-image {
     width: 36px;
     height: 36px;
     object-fit: cover;
   }

   .user-avatar {
     width: 36px;
     height: 36px;
     display: flex;
     align-items: center;
     justify-content: center;
     background: linear-gradient(135deg, #198754 0%, #20c997 100%);
     color: white;
     font-weight: bold;
     font-size: 14px;
   }

    .user-avatar-lg {
      width: 60px;
      height: 60px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, #198754 0%, #20c997 100%);
      color: white;
      font-weight: bold;
      font-size: 20px;
    }

    .profile-dropdown {
      width: 320px !important;
      padding: 0 !important;
      border: none !important;
      border-radius: 14px !important;
      overflow: hidden;
      box-shadow: 0 8px 32px rgba(0,0,0,0.12);
    }

    .profile-dropdown-header {
      background: linear-gradient(135deg, #198754, #20c997);
      padding: 1.25rem;
      display: flex;
      align-items: center;
      gap: 1rem;
      position: relative;
      overflow: hidden;
    }

    .profile-dropdown-header::before {
      content: '';
      position: absolute;
      top: -50%;
      right: -20%;
      width: 200px;
      height: 200px;
      background: rgba(255,255,255,0.08);
      border-radius: 50%;
    }

    .profile-dropdown-avatar {
      width: 52px;
      height: 52px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid rgba(255,255,255,0.3);
      position: relative;
      z-index: 1;
    }

    .profile-dropdown-avatar-initial {
      display: flex;
      align-items: center;
      justify-content: center;
      background: white;
      color: #198754;
      font-weight: 700;
      font-size: 1.2rem;
    }

    .profile-dropdown-info {
      position: relative;
      z-index: 1;
      min-width: 0;
    }

    .profile-dropdown-name {
      margin: 0;
      color: white;
      font-weight: 600;
      font-size: 0.95rem;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .profile-dropdown-email {
      margin: 0;
      color: rgba(255,255,255,0.8);
      font-size: 0.78rem;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .profile-dropdown-badge {
      display: inline-block;
      margin-top: 0.3rem;
      background: rgba(255,255,255,0.2);
      color: white;
      padding: 0.15rem 0.6rem;
      border-radius: 10px;
      font-size: 0.68rem;
      font-weight: 500;
    }

    .profile-dropdown-body {
      padding: 0.5rem 0;
      background: var(--bs-body-bg);
    }

    .profile-dropdown-item {
      display: flex;
      align-items: center;
      padding: 0.5rem 1.25rem;
      gap: 0.75rem;
      transition: background 0.15s;
    }

    .profile-dropdown-item:hover {
      background: var(--bs-tertiary-bg);
    }

    .profile-dropdown-item-icon {
      width: 34px;
      height: 34px;
      border-radius: 8px;
      background: #f0fdf4;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #198754;
      flex-shrink: 0;
    }

    .profile-dropdown-item-content {
      min-width: 0;
    }

    .profile-dropdown-item-label {
      display: block;
      font-size: 0.68rem;
      color: #6c757d;
      text-transform: uppercase;
      letter-spacing: 0.4px;
    }

    .profile-dropdown-item-value {
      display: block;
      font-size: 0.85rem;
      color: #212529;
      font-weight: 500;
    }

    .profile-dropdown-footer {
      padding: 0.75rem 1.25rem;
      background: var(--bs-body-bg);
      border-top: 1px solid #e9ecef;
      display: flex;
      flex-wrap: wrap;
      gap: 0.4rem;
    }

    .profile-dropdown-btn {
      display: inline-flex;
      align-items: center;
      gap: 0.3rem;
      padding: 0.4rem 0.75rem;
      border-radius: 8px;
      font-size: 0.78rem;
      font-weight: 500;
      text-decoration: none;
      cursor: pointer;
      border: none;
      transition: all 0.15s;
    }

    .profile-dropdown-btn-primary {
      background: #198754;
      color: white;
    }

    .profile-dropdown-btn-primary:hover {
      background: #146c43;
      color: white;
    }

    .profile-dropdown-btn-outline {
      background: transparent;
      color: #495057;
      border: 1px solid #dee2e6;
    }

    .profile-dropdown-btn-outline:hover {
      background: #f8f9fa;
      color: #212529;
    }

    .profile-dropdown-btn-danger {
      background: transparent;
      color: #dc2626;
      margin-left: auto;
    }

    .profile-dropdown-btn-danger:hover {
      background: #fef2f2;
      color: #b91c1c;
    }

    [data-bs-theme="dark"] .profile-dropdown {
      box-shadow: 0 8px 32px rgba(0,0,0,0.5);
    }

    [data-bs-theme="dark"] .profile-dropdown-avatar-initial {
      background: #1a1d27;
      color: #20c997;
    }

    [data-bs-theme="dark"] .profile-dropdown-body {
      background: #1a1d27;
    }

    [data-bs-theme="dark"] .profile-dropdown-item-icon {
      background: rgba(25,135,84,0.15);
      color: #4ade80;
    }

    [data-bs-theme="dark"] .profile-dropdown-item-label {
      color: #8b90a8;
    }

    [data-bs-theme="dark"] .profile-dropdown-item-value {
      color: #f0f2f8;
    }

    [data-bs-theme="dark"] .profile-dropdown-footer {
      background: #1a1d27;
      border-top-color: #2a2d3a;
    }

    [data-bs-theme="dark"] .profile-dropdown-btn-outline {
      color: #c0c4d0;
      border-color: #2a2d3a;
    }

    [data-bs-theme="dark"] .profile-dropdown-btn-outline:hover {
      background: #2a2d3a;
      color: #f0f2f8;
    }

    [data-bs-theme="dark"] .profile-dropdown-btn-danger {
      color: #f87171;
    }

    [data-bs-theme="dark"] .profile-dropdown-btn-danger:hover {
      background: rgba(220,38,38,0.15);
      color: #fca5a5;
    }

    [data-bs-theme="dark"] .profile-dropdown-item:hover {
      background: #2a2d3a;
    }

    .navbar-badge {
     position: absolute;
     top: 2px;
     right: 2px;
     font-size: 0.6rem;
     padding: 0.15rem 0.35rem;
   }

   /* Text truncation for username */
   .text-truncate {
     overflow: hidden;
     text-overflow: ellipsis;
     white-space: nowrap;
   }

   /* Responsive adjustments */
   @media (max-width: 991.98px) {
     .app-header {
       min-height: 56px !important;
       max-height: 56px !important;
     }

     .user-image-wrapper {
       width: 32px;
       height: 32px;
     }

     .user-image {
       width: 32px;
       height: 32px;
     }

     .user-avatar {
       width: 32px;
       height: 32px;
       font-size: 12px;
     }
   }

   @media (max-width: 575.98px) {
     .app-header {
       min-height: 52px !important;
       max-height: 52px !important;
       padding-left: 0.5rem !important;
       padding-right: 0.5rem !important;
     }

     /* Hide non-essential elements on very small screens */
     .nav-link[data-lte-toggle="sidebar"] {
       padding: 0.25rem 0.5rem;
     }

     /* Theme toggle - compact on mobile */
     .theme-toggle .nav-link {
       padding: 0.25rem !important;
       font-size: 0.875rem;
     }

     .theme-toggle .dropdown-menu {
       position: absolute;
       right: 0;
       left: auto;
     }

     /* Messages & Notifications dropdowns - responsive */
     .messages-dropdown .dropdown-menu,
     .notifications-dropdown .dropdown-menu {
       position: absolute;
       left: 0;
       right: 0;
       width: 100%;
       max-width: 100%;
       margin: 0;
       border-radius: 0;
       border-left: none;
       border-right: none;
     }

     .messages-dropdown .dropdown-item,
     .notifications-dropdown .dropdown-item {
       padding: 0.75rem 1rem;
       font-size: 0.875rem;
     }

     .messages-dropdown .img-size-50,
     .notifications-dropdown .img-size-50 {
       width: 40px !important;
       height: 40px !important;
     }

     .messages-dropdown .dropdown-item-title,
     .notifications-dropdown .dropdown-item-title {
       font-size: 0.9rem;
     }

     .messages-dropdown .fs-7,
     .notifications-dropdown .fs-7 {
       font-size: 0.75rem !important;
     }

     .messages-dropdown .navbar-badge,
     .notifications-dropdown .navbar-badge {
       top: 0;
       right: 0;
       font-size: 0.5rem;
     }
   }
 </style>

 <!-- Session Timeout Warning Modal -->
 <div class="modal fade" id="sessionTimeoutModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="sessionTimeoutModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered">
     <div class="modal-content">
       <div class="modal-header bg-warning">
         <h5 class="modal-title" id="sessionTimeoutModalLabel">
           <i class="bi bi-clock-fill me-2"></i>Session expirant
         </h5>
       </div>
       <div class="modal-body text-center py-4">
         <p class="mb-3">Votre session va expirer dans</p>
         <div class="display-4 fw-bold text-danger" id="sessionCountdown">03:00</div>
         <p class="text-muted mt-3">Cliquez sur "Rester connecté" pour continuer</p>
       </div>
       <div class="modal-footer justify-content-center">
         <button type="button" class="btn btn-secondary" id="extendSessionBtn">
           <i class="bi bi-arrow-clockwise me-1"></i> Rester connecté
         </button>
         <a href="{{ route('logout') }}" class="btn btn-outline-danger">
           <i class="bi bi-box-arrow-right me-1"></i> Déconnexion
         </a>
       </div>
     </div>
   </div>
 </div>

 <script>
   // Configuration du timeout de session (3 minutes = 180 secondes)
   const SESSION_TIMEOUT = 3 * 60;
   const WARNING_TIME = 60; // Afficher l'avertissement 60 secondes avant l'expiration
   const HEARTBEAT_INTERVAL = 30000; // Heartbeat toutes les 30 secondes

   let countdownInterval;
   let heartbeatInterval;
   let sessionWillExpire = false;

   // Démarrer le suivi de la session
   function startSessionTracking() {
     // Envoyer un heartbeat toutes les 30 secondes pour maintenir la session active
     heartbeatInterval = setInterval(sendHeartbeat, HEARTBEAT_INTERVAL);
   }

   // Envoyer un heartbeat au serveur pour prolonger la session
   function sendHeartbeat() {
     fetch('{{ route("session.extend") }}', {
       method: 'POST',
       headers: {
         'X-CSRF-TOKEN': '{{ csrf_token() }}',
         'Content-Type': 'application/json'
       }
     }).catch(() => {
       // Ignorer les erreurs - la session pourrait être expirée
     });
   }

   // Afficher le modal d'avertissement
   function showTimeoutWarning() {
     sessionWillExpire = true;
     let timeLeft = WARNING_TIME;

     const modal = new bootstrap.Modal(document.getElementById('sessionTimeoutModal'));
     modal.show();

     countdownInterval = setInterval(() => {
       timeLeft--;

       const minutes = Math.floor(timeLeft / 60);
       const seconds = timeLeft % 60;
       document.getElementById('sessionCountdown').textContent =
         `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

       if (timeLeft <= 0) {
         clearInterval(countdownInterval);
         // Déconnecter automatiquement
         window.location.href = '{{ route("logout") }}';
       }
     }, 1000);
   }

   // Prolonger la session
   document.getElementById('extendSessionBtn').addEventListener('click', function() {
     clearInterval(countdownInterval);
     sessionWillExpire = false;

     // Masquer le modal
     const modal = bootstrap.Modal.getInstance(document.getElementById('sessionTimeoutModal'));
     modal.hide();

     // Envoyer une requête pour prolonger la session
     fetch('{{ route("session.extend") }}', {
       method: 'POST',
       headers: {
         'X-CSRF-TOKEN': '{{ csrf_token() }}',
         'Content-Type': 'application/json'
       }
     }).then(() => {
       // Redémarrer le suivi
       startSessionTracking();
     });
   });

   // Démarrer le suivi quand la page est chargée
   document.addEventListener('DOMContentLoaded', function() {
     // Vérifier si l'utilisateur est connecté
     @auth
     startSessionTracking();

     // Programmer l'avertissement
     setTimeout(showTimeoutWarning, (SESSION_TIMEOUT - WARNING_TIME) * 1000);
     @endauth
   });

   // Envoyer un heartbeat quand l'utilisateur interagit avec la page
   document.addEventListener('click', function() {
     if (!sessionWillExpire) {
       sendHeartbeat();
     }
   });

   document.addEventListener('keypress', function() {
     if (!sessionWillExpire) {
       sendHeartbeat();
     }
   });

   // ── Notifications dynamiques ──────────────────────────────────────
   function loadNotifications() {
     fetch('/notifications/unread-count')
       .then(r => r.json())
       .then(data => {
         const badge = document.getElementById('notifBadge');
         if (data.count > 0) {
           badge.textContent = data.count;
           badge.style.display = '';
         } else {
           badge.style.display = 'none';
         }

         const list = document.getElementById('notifList');
         const header = document.getElementById('notifHeader');
         header.textContent = data.count + ' notification' + (data.count > 1 ? 's' : '');

         if (!data.notifications || data.notifications.length === 0) {
           list.innerHTML = '<div class="dropdown-item text-center text-muted py-3"><i class="bi bi-bell-slash me-2"></i>Aucune notification</div>';
           return;
         }

         list.innerHTML = data.notifications.map(n => {
           const colors = { primary: 'var(--bs-primary)', success: '#16a34a', warning: '#d97706', danger: '#dc2626', info: '#0891b2' };
           const bgColors = { primary: 'rgba(37,99,235,.1)', success: 'rgba(22,163,74,.1)', warning: 'rgba(217,119,6,.1)', danger: 'rgba(220,38,38,.1)', info: 'rgba(8,145,178,.1)' };
           const c = colors[n.color] || colors.primary;
           const bg = bgColors[n.color] || bgColors.primary;
           return '<a href="#" class="dropdown-item">' +
             '<div class="d-flex align-items-center gap-2">' +
               '<div style="width:32px;height:32px;border-radius:50%;background:' + bg + ';color:' + c + ';display:flex;align-items:center;justify-content:center;flex-shrink:0;">' +
                 '<i class="bi bi-' + n.icon + '" style="font-size:.8rem;"></i>' +
               '</div>' +
               '<div class="flex-grow-1 min-w-0">' +
                 '<p class="mb-0 text-truncate" style="font-size:.85rem;">' + n.message + '</p>' +
                 '<small class="text-secondary">' + n.time + '</small>' +
               '</div>' +
             '</div>' +
           '</a><div class="dropdown-divider"></div>';
         }).join('');

         // Enlever le dernier divider
         const lastDivider = list.querySelector('.dropdown-divider:last-child');
         if (lastDivider) lastDivider.remove();
       })
       .catch(() => {
         document.getElementById('notifList').innerHTML = '<div class="dropdown-item text-center text-muted py-3">Erreur de chargement</div>';
       });
   }

   document.addEventListener('DOMContentLoaded', function() {
     @auth
     loadNotifications();
     setInterval(loadNotifications, 30000); // Rafraîchir toutes les 30s
     @endauth
   });
 </script>