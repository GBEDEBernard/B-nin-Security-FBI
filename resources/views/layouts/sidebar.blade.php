<!--begin::Sidebar-->
<aside class="app-sidebar bg-dark shadow" data-bs-theme="dark">
  <!--begin::Sidebar Brand-->
  <div class="sidebar-brand">
    <!--begin::Brand Link-->
    <a href="{{ auth()->check() ? route(auth()->user()->getAdminRoute()) : route('home') }}" class="brand-link">
      <!--begin::Brand Image-->
      <div class="brand-image-container">
        <i class="bi bi-shield-check brand-icon"></i>
      </div>
      <!--end::Brand Image-->
      <!--begin::Brand Text-->
      <span class="brand-text fw-bold">Bénin <span class="text-success">Security</span></span>
      <!--end::Brand Text-->
    </a>
    <!--end::Brand Link-->
  </div>
  <!--end::Sidebar Brand-->
  <!--begin::Sidebar Wrapper-->
  <div class="sidebar-wrapper">
    <nav class="mt-2">
      <!--begin::Sidebar Menu-->
      <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" aria-label="Main navigation" data-accordion="false" id="navigation">

        {{-- Admin --}}
        <li class="nav-item">
          <a href="{{ auth()->check() ? route(auth()->user()->getAdminRoute()) : route('home') }}" class="nav-link {{ request()->routeIs('admin*') ? 'active' : '' }}">
            <i class="nav-icon bi bi-speedometer2"></i>
            <p>Admin</p>
          </a>
        </li>

        {{-- =========================================================== --}}
        {{-- MENU SUPER ADMIN --}}
        {{-- Caché quand le super admin est en contexte entreprise --}}
        {{-- =========================================================== --}}
        @if(auth()->check() && auth()->user()->estSuperAdmin() && !auth()->user()->estEnContexteEntreprise())

        <li class="nav-header text-uppercase fw-bold text-primary">Administration</li>

        {{-- Bouton de retour au SuperAdmin (si en contexte entreprise) --}}
        @if(auth()->user()->estEnContexteEntreprise())
        <li class="nav-item">
          <a href="{{ route('admin.superadmin.return') }}" class="nav-link bg-warning text-dark">
            <i class="nav-icon bi bi-arrow-left-circle"></i>
            <p class="fw-bold">Retour Super Admin</p>
          </a>
        </li>

        {{-- Indicateur de l'entreprise courante --}}
        <li class="nav-item">
          <a href="#" class="nav-link bg-info bg-opacity-25">
            <i class="nav-icon bi bi-building text-info"></i>
            <p class="text-info">
              <strong>{{ auth()->user()->getEntrepriseContexte()?->nom_entreprise ?? 'Entreprise' }}</strong>
            </p>
          </a>
        </li>
        @endif

        {{-- Gestion des Entreprises --}}
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-building-fill"></i>
            <p>
              Entreprises
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('admin.superadmin.entreprises.index') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Liste des entreprises</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.superadmin.entreprises.create') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Ajouter une entreprise</p>
              </a>
            </li>
          </ul>
        </li>

        {{-- Accès Rapide aux Tableaux de Bord --}}
        @php
        $entreprises = \App\Models\Entreprise::where('est_active', true)->orderBy('nom_entreprise')->limit(10)->get();
        @endphp
        @if($entreprises->count() > 0)
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-layout-text-window-reverse"></i>
            <p>
              Accès Rapide
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @foreach($entreprises as $entreprise)
            <li class="nav-item">
              <button type="button" class="nav-link btn btn-link text-start w-100 connect-entreprise-btn"
                data-bs-toggle="modal"
                data-bs-target="#connectModal"
                data-entreprise-id="{{ $entreprise->id }}"
                data-entreprise-nom="{{ $entreprise->nom_entreprise ?? $entreprise->nom }}">
                <i class="nav-icon bi bi-box-arrow-in-right text-success"></i>
                <p>{{ $entreprise->nom_entreprise ?? $entreprise->nom }}</p>
              </button>
            </li>
            @endforeach
          </ul>
        </li>
        @endif

        {{-- Utilisateurs Globaux --}}
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-people-gear"></i>
            <p>
              Utilisateurs
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('admin.superadmin.utilisateurs.index') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Liste des utilisateurs</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.superadmin.utilisateurs.create') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Nouvel utilisateur</p>
              </a>
            </li>
          </ul>
        </li>

        {{-- Paramètres --}}
        <li class="nav-item">
          <a href="{{ route('admin.superadmin.parametres.index') }}" class="nav-link">
            <i class="nav-icon bi bi-gear-fill"></i>
            <p>Paramètres Système</p>
          </a>
        </li>

        @endif

        {{-- =========================================================== --}}
        {{-- MENU ENTREPRISE (Direction, Superviseur, Contrôleur) --}}
        {{-- Affiché aussi quand le super admin est en contexte entreprise --}}
        {{-- =========================================================== --}}
        @php
        $estEnContexteEntreprise = auth()->check() && (
        (auth()->user()->estUtilisateurEntreprise() && !auth()->user()->estSuperAdmin()) ||
        (auth()->user()->estSuperAdmin() && auth()->user()->estEnContexteEntreprise())
        );
        $estSuperAdminEnContexte = auth()->check() && auth()->user()->estSuperAdmin() && auth()->user()->estEnContexteEntreprise();
        @endphp
        @if($estEnContexteEntreprise)

        <li class="nav-header text-uppercase fw-bold text-primary">
          @if($estSuperAdminEnContexte)
          <i class="bi bi-shield-lock me-1"></i> Super Admin
          @else
          Gestion
          @endif
        </li>

        {{-- Bouton de retour au SuperAdmin (si super admin en contexte entreprise) --}}
        @if($estSuperAdminEnContexte)
        <li class="nav-item">
          <a href="{{ route('admin.superadmin.return') }}" class="nav-link bg-warning text-dark">
            <i class="nav-icon bi bi-arrow-left-circle"></i>
            <p class="fw-bold">Retour Super Admin</p>
          </a>
        </li>

        {{-- Indicateur de l'entreprise courante --}}
        <li class="nav-item">
          <a href="#" class="nav-link bg-info bg-opacity-25">
            <i class="nav-icon bi bi-building text-info"></i>
            <p class="text-info">
              <strong>{{ auth()->user()->getEntrepriseContexte()?->nom_entreprise ?? 'Entreprise' }}</strong>
            </p>
          </a>
        </li>
        @endif

        {{-- Clients --}}
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-people-fill"></i>
            <p>
              Clients
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('admin.entreprise.clients.index') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Liste des clients</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.entreprise.clients.create') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Ajouter un client</p>
              </a>
            </li>
          </ul>
        </li>

        {{-- Employés --}}
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-person-badge-fill"></i>
            <p>
              Employés
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('admin.entreprise.employes.index') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Liste des employés</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.entreprise.employes.create') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Ajouter un employé</p>
              </a>
            </li>
          </ul>
        </li>

        {{-- Contrats --}}
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-file-earmark-text-fill"></i>
            <p>
              Contrats
              <span class="nav-badge badge bg-success ms-2">Actifs</span>
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('admin.entreprise.contrats.index') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Tous les contrats</p>
              </a>
            </li>
          </ul>
        </li>

        {{-- Sites --}}
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-building-fill"></i>
            <p>
              Sites
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Tous les sites</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Ajouter un site</p>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-header text-uppercase fw-bold text-primary">Opérations</li>

        {{-- Pointages --}}
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-clock-fill"></i>
            <p>
              Pointages
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Historique</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Today's pointage</p>
              </a>
            </li>
          </ul>
        </li>

        {{-- Affectations --}}
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-calendar-check-fill"></i>
            <p>
              Affectations
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('admin.entreprise.affectations.index') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Planning</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Créer une affectation</p>
              </a>
            </li>
          </ul>
        </li>

        {{-- Incidents --}}
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-exclamation-triangle-fill"></i>
            <p>
              Incidents
              <span class="nav-badge badge bg-danger ms-2">3</span>
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Tous les incidents</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Signaler un incident</p>
              </a>
            </li>
          </ul>
        </li>

        {{-- Congés --}}
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-calendar-event-fill"></i>
            <p>
              Congés
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Demandes</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Soldes</p>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-header text-uppercase fw-bold text-primary">Comptabilité</li>

        {{-- Facturation --}}
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-receipt"></i>
            <p>
              Facturation
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Factures</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Paiements</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Créer une facture</p>
              </a>
            </li>
          </ul>
        </li>

        {{-- Paie --}}
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-currency-exchange"></i>
            <p>
              Paie
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Bulletins de paie</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Générer bulletin</p>
              </a>
            </li>
          </ul>
        </li>

        {{-- Rapports --}}
        <li class="nav-item">
          <a href="{{ route('admin.entreprise.rapports.index') }}" class="nav-link">
            <i class="nav-icon bi bi-graph-up"></i>
            <p>Rapports</p>
          </a>
        </li>

        <li class="nav-header text-uppercase fw-bold text-primary">Paramètres</li>

        {{-- Entreprise --}}
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-gear-fill"></i>
            <p>
              Entreprise
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Informations</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Services</p>
              </a>
            </li>
          </ul>
        </li>

        @endif

        {{-- =========================================================== --}}
        {{-- MENU AGENT --}}
        {{-- =========================================================== --}}
        @if(auth()->check() && auth()->user()->estAgent())

        <li class="nav-header text-uppercase fw-bold text-primary">Mon Activité</li>

        {{-- Mes Missions --}}
        <li class="nav-item">
          <a href="{{ route('admin.agent.missions.index') }}" class="nav-link">
            <i class="nav-icon bi bi-briefcase-fill"></i>
            <p>Mes Missions</p>
          </a>
        </li>

        {{-- Pointages --}}
        <li class="nav-item">
          <a href="{{ route('admin.agent.pointages.index') }}" class="nav-link">
            <i class="nav-icon bi bi-clock-fill"></i>
            <p>Mes Pointages</p>
          </a>
        </li>

        {{-- Congés --}}
        <li class="nav-item">
          <a href="{{ route('admin.agent.conges.index') }}" class="nav-link">
            <i class="nav-icon bi bi-calendar-event-fill"></i>
            <p>Mes Congés</p>
          </a>
        </li>

        <li class="nav-header text-uppercase fw-bold text-primary">Mon Compte</li>

        {{-- Profil --}}
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-person-circle"></i>
            <p>Mon Profil</p>
          </a>
        </li>

        @endif

        {{-- =========================================================== --}}
        {{-- MENU CLIENT --}}
        {{-- =========================================================== --}}
        @if(auth()->check() && auth()->user()->estClient())

        <li class="nav-header text-uppercase fw-bold text-primary">Mon Espace</li>

        {{-- Mes Contrats --}}
        <li class="nav-item">
          <a href="{{ route('admin.client.contrats.index') }}" class="nav-link">
            <i class="nav-icon bi bi-file-earmark-check-fill"></i>
            <p>Mes Contrats</p>
          </a>
        </li>

        {{-- Mes Factures --}}
        <li class="nav-item">
          <a href="{{ route('admin.client.factures.index') }}" class="nav-link">
            <i class="nav-icon bi bi-receipt"></i>
            <p>Mes Factures</p>
          </a>
        </li>

        {{-- Incidents --}}
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-exclamation-triangle-fill"></i>
            <p>
              Incidents
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('admin.client.incidents.index') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Historique</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.client.incidents.create') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Signaler un incident</p>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-header text-uppercase fw-bold text-primary">Mon Compte</li>

        {{-- Profil --}}
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-person-circle"></i>
            <p>Mon Profil</p>
          </a>
        </li>

        @endif

      </ul>
      <!--end::Sidebar Menu-->
    </nav>
  </div>
  <!--end::Sidebar Wrapper-->
</aside>
<!--end::Sidebar-->

<style>
  /* Custom Sidebar Styles */
  .brand-image-container {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #198754 0%, #20c997 100%);
    border-radius: 8px;
    margin-right: 10px;
  }

  .brand-icon {
    font-size: 24px;
    color: white;
  }

  .brand-link {
    display: flex;
    align-items: center;
    padding: 0.75rem 1rem;
  }

  .sidebar-brand {
    padding: 0.5rem 0;
  }

  .sidebar-menu>.nav-header {
    padding: 0.75rem 1rem 0.5rem;
    font-size: 0.7rem;
    letter-spacing: 0.5px;
    opacity: 0.8;
  }

  .nav-badge {
    font-size: 0.65rem;
    padding: 0.25em 0.5em;
  }
</style>