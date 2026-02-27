<!--begin::Sidebar-->
<aside class="app-sidebar shadow" data-bs-theme="dark" style="background-color: #1a1a1a; border-right: 1px solid #2d2d2d;">
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

        {{-- Accès Rapide aux Tableaux de Bord (bouton flottant) --}}
        @php
        $entreprises = \App\Models\Entreprise::where('est_active', true)->orderBy('nom_entreprise')->limit(10)->get();
        @endphp
        @if($entreprises->count() > 0)
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-layout-text-window-reverse"></i>
            <p>
              Accès Entreprises
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
                <i class="nav-icon bi bi-box-arrow-in-right" style="color: var(--bs-success);"></i>
                <p style="color: var(--bs-body-color);">{{ $entreprise->nom_entreprise ?? $entreprise->nom }}</p>
              </button>
            </li>
            @endforeach
          </ul>
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

        {{-- Abonnements (contrôle des agents par entreprise) --}}
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-credit-card-2-front-fill"></i>
            <p>
              Abonnements
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('admin.superadmin.abonnements.index') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Liste des abonnements</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.superadmin.abonnements.create') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Nouvel abonnement</p>
              </a>
            </li>
          </ul>
        </li>

        {{-- Propositions de contrat (Super Admin) --}}
        <li class="nav-item">
          <a href="{{ route('admin.superadmin.propositions.index') }}" class="nav-link {{ request()->is('admin/superadmin/propositions*') ? 'active' : '' }}">
            <i class="nav-icon bi bi-file-earmark-ruled"></i>
            <p>
              Propositions
              @php
              $nouvelles = \App\Models\PropositionContrat::where('statut', 'soumis')->count();
              @endphp
              @if($nouvelles > 0)
              <span class="nav-badge bg-danger">{{ $nouvelles }}</span>
              @endif
            </p>
          </a>
        </li>

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

        {{-- Gestion des Rôles --}}
        <li class="nav-item">
          <a href="{{ route('admin.superadmin.roles.index') }}" class="nav-link {{ request()->routeIs('admin.superadmin.roles*') ? 'active' : '' }}">
            <i class="nav-icon bi bi-shield-lock-fill"></i>
            <p>Gestion des Rôles</p>
          </a>
        </li>

        <li class="nav-header text-uppercase fw-bold text-primary">Finance & Rapports</li>

        {{-- Facturation Globale --}}
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-cash-stack"></i>
            <p>
              Facturation
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('admin.superadmin.facturation.index') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Toutes les factures</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.superadmin.facturation.paiements') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Paiements</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.superadmin.facturation.creances') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Créances</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.superadmin.facturation.statistiques') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Statistiques</p>
              </a>
            </li>
          </ul>
        </li>

        {{-- Rapports Globaux --}}
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-graph-up"></i>
            <p>
              Rapports
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('admin.superadmin.rapports.index') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Tableau de bord</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.superadmin.rapports.par-entreprise') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Par entreprise</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.superadmin.rapports.financier') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Financier</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.superadmin.rapports.employes') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Employés</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.superadmin.rapports.clients') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Clients</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.superadmin.rapports.contrats') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Contrats</p>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-header text-uppercase fw-bold text-primary">Système</li>

        {{-- Gestion APK --}}
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-android"></i>
            <p>
              Application Mobile
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('admin.superadmin.apk.index') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Versions APK</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.superadmin.apk.configurations') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Configurations</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.superadmin.apk.create') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Nouvelle version</p>
              </a>
            </li>
          </ul>
        </li>

        {{-- Notifications Push --}}
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-bell-fill"></i>
            <p>
              Notifications
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('admin.superadmin.notifications.index') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Historique</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.superadmin.notifications.create') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Nouvelle notification</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.superadmin.notifications.statistiques') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Statistiques</p>
              </a>
            </li>
          </ul>
        </li>

        {{-- Journal d'Activité --}}
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-journal-text"></i>
            <p>
              Journal d'Activité
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('admin.superadmin.journal.index') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Historique</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.superadmin.journal.par-utilisateur') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Par utilisateur</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.superadmin.journal.par-module') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Par module</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.superadmin.journal.statistiques') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Statistiques</p>
              </a>
            </li>
          </ul>
        </li>

        {{-- Modèles --}}
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-file-earmark-text-fill"></i>
            <p>
              Modèles
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('admin.superadmin.modeles.index') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Liste des modèles</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.superadmin.modeles.create') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Nouveau modèle</p>
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
        <li class="nav-item mb-3">
          <a href="{{ route('admin.superadmin.return') }}"
            class="nav-link bg-warning text-dark rounded-3 mx-2 py-2 return-superadmin-btn"
            id="returnSuperAdminBtn"
            onclick="animateReturn(event)">
            <i class="nav-icon bi bi-arrow-left-circle me-2"></i>
            <span class="fw-bold">Retour Super Admin</span>
          </a>
        </li>

        {{-- Indicateur de l'entreprise courante --}}
        <li class="nav-item mb-3 mx-2">
          <div class="nav-link rounded-3" style="background: linear-gradient(135deg, rgba(25, 135, 84, 0.15) 0%, rgba(32, 201, 151, 0.15) 100%); border: 1px solid rgba(25, 135, 84, 0.3);">
            <div class="d-flex align-items-center">
              <div class="me-2">
                <i class="bi bi-building" style="color: var(--bs-success);"></i>
              </div>
              <div>
                <small class="text-muted d-block" style="font-size: 0.65rem;">Vue actuelle:</small>
                <strong style="color: var(--bs-success); font-size: 0.85rem;">
                  {{ auth()->user()->getEntrepriseContexte()?->nom_entreprise ?? 'Entreprise' }}
                </strong>
              </div>
            </div>
          </div>
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
  /* ========================================
     Custom Sidebar Styles with Animations
     ======================================== */

  /* Brand Logo Animation */
  .brand-image-container {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #198754 0%, #20c997 100%);
    border-radius: 8px;
    margin-right: 10px;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 15px rgba(25, 135, 84, 0.3);
  }

  .brand-image-container:hover {
    transform: rotate(10deg) scale(1.1);
    box-shadow: 0 6px 25px rgba(25, 135, 84, 0.5);
  }

  .brand-icon {
    font-size: 24px;
    color: white;
    transition: transform 0.4s ease;
  }

  .brand-image-container:hover .brand-icon {
    transform: scale(1.2);
  }

  .brand-link {
    display: flex;
    align-items: center;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
  }

  .brand-link:hover {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 8px;
  }

  .sidebar-brand {
    padding: 0.5rem 0;
  }

  /* Nav Headers Animation */
  .sidebar-menu>.nav-header {
    padding: 0.75rem 1rem 0.5rem;
    font-size: 0.7rem;
    letter-spacing: 0.5px;
    opacity: 0.8;
    animation: fadeInDown 0.5s ease forwards;
    position: relative;
  }

  .sidebar-menu>.nav-header::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 1rem;
    right: 1rem;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
  }

  @keyframes fadeInDown {
    from {
      opacity: 0;
      transform: translateY(-10px);
    }

    to {
      opacity: 0.8;
      transform: translateY(0);
    }
  }

  .nav-badge {
    font-size: 0.65rem;
    padding: 0.25em 0.5em;
    animation: pulse 2s infinite;
  }

  @keyframes pulse {

    0%,
    100% {
      transform: scale(1);
    }

    50% {
      transform: scale(1.1);
    }
  }

  /* ========================================
     Menu Items Base Styles
     ======================================== */
  .sidebar-menu .nav-item {
    position: relative;
    margin: 2px 8px;
    border-radius: 8px;
    overflow: hidden;
  }

  .sidebar-menu .nav-link {
    position: relative;
    display: flex;
    align-items: center;
    padding: 0.75rem 1rem;
    border-radius: 8px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
    z-index: 1;
  }

  .sidebar-menu .nav-link::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
    transition: left 0.5s;
    z-index: -1;
  }

  .sidebar-menu .nav-link:hover::before {
    left: 100%;
  }

  /* Active State Animation */
  .sidebar-menu .nav-link.active {
    background: linear-gradient(135deg, rgba(25, 135, 84, 0.3) 0%, rgba(32, 201, 151, 0.2) 100%);
    border-left: 3px solid #20c997;
    box-shadow: 0 4px 15px rgba(25, 135, 84, 0.2);
  }

  .sidebar-menu .nav-link.active .nav-icon,
  .sidebar-menu .nav-link.active p {
    color: #20c997 !important;
  }

  .sidebar-menu .nav-link.active::after {
    content: '';
    position: absolute;
    right: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 0;
    height: 0;
    border-style: solid;
    border-width: 6px 8px 6px 0;
    border-color: transparent #1a1a1a transparent transparent;
  }

  /* Hover State */
  .sidebar-menu .nav-link:hover {
    background: rgba(255, 255, 255, 0.08);
    transform: translateX(5px);
  }

  .sidebar-menu .nav-link:hover .nav-icon {
    transform: scale(1.1);
    color: #20c997;
  }

  .sidebar-menu .nav-icon {
    transition: all 0.3s ease;
    font-size: 1.1rem;
  }

  /* Nav Arrow Animation */
  .nav-arrow {
    transition: transform 0.3s ease;
    font-size: 0.75rem;
    margin-left: auto;
  }

  .nav-item.menu-open>.nav-link .nav-arrow,
  .nav-item.expand>.nav-link .nav-arrow {
    transform: rotate(90deg);
  }

  /* ========================================
     Submenu (Treeview) Animations
     ======================================== */
  .nav-treeview {
    display: none;
    padding-left: 0.5rem;
    animation: slideDown 0.3s ease forwards;
  }

  .nav-item.menu-open>.nav-treeview,
  .nav-item.expand>.nav-treeview {
    display: block;
  }

  @keyframes slideDown {
    from {
      opacity: 0;
      transform: translateY(-10px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  @keyframes slideUp {
    from {
      opacity: 1;
      transform: translateY(0);
    }

    to {
      opacity: 0;
      transform: translateY(-10px);
    }
  }

  .nav-treeview .nav-item {
    margin: 2px 0;
    opacity: 0;
    animation: fadeInLeft 0.3s ease forwards;
  }

  .nav-treeview .nav-item:nth-child(1) {
    animation-delay: 0.05s;
  }

  .nav-treeview .nav-item:nth-child(2) {
    animation-delay: 0.1s;
  }

  .nav-treeview .nav-item:nth-child(3) {
    animation-delay: 0.15s;
  }

  .nav-treeview .nav-item:nth-child(4) {
    animation-delay: 0.2s;
  }

  .nav-treeview .nav-item:nth-child(5) {
    animation-delay: 0.25s;
  }

  .nav-treeview .nav-item:nth-child(6) {
    animation-delay: 0.3s;
  }

  @keyframes fadeInLeft {
    from {
      opacity: 0;
      transform: translateX(-20px);
    }

    to {
      opacity: 1;
      transform: translateX(0);
    }
  }

  .nav-treeview .nav-link {
    padding: 0.6rem 1rem;
    font-size: 0.9rem;
  }

  .nav-treeview .nav-link:hover {
    background: rgba(255, 255, 255, 0.05);
    transform: translateX(10px);
  }

  .nav-treeview .nav-icon {
    font-size: 0.7rem;
    margin-right: 0.5rem;
  }

  /* ========================================
     Buttons with Animations
     ======================================== */
  .connect-entreprise-btn {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
  }

  .connect-entreprise-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(32, 201, 151, 0.2), transparent);
    transition: left 0.5s;
  }

  .connect-entreprise-btn:hover::before {
    left: 100%;
  }

  .connect-entreprise-btn:hover {
    transform: translateX(5px);
    background: rgba(255, 255, 255, 0.05);
  }

  /* Animation du bouton Retour Super Admin */
  .return-superadmin-btn {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
  }

  .return-superadmin-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    transition: left 0.5s;
  }

  .return-superadmin-btn:hover::before {
    left: 100%;
  }

  .return-superadmin-btn:hover {
    transform: translateX(5px);
    box-shadow: 0 4px 15px rgba(255, 193, 7, 0.4);
  }

  .return-superadmin-btn:active {
    transform: translateX(0) scale(0.98);
  }

  .return-superadmin-btn.loading {
    pointer-events: none;
    opacity: 0.8;
  }

  .return-superadmin-btn.loading .bi-arrow-left-circle {
    animation: bounce-left 0.6s ease-in-out infinite;
  }

  @keyframes bounce-left {

    0%,
    100% {
      transform: translateX(0);
    }

    50% {
      transform: translateX(-5px);
    }
  }

  /* ========================================
     Context Indicator Animation
     ======================================== */
  .entreprise-context-indicator {
    background: linear-gradient(135deg, rgba(25, 135, 84, 0.1) 0%, rgba(32, 201, 151, 0.1) 100%);
    border: 1px solid rgba(25, 135, 84, 0.2);
    transition: all 0.3s ease;
    animation: shimmer 3s infinite;
  }

  @keyframes shimmer {

    0%,
    100% {
      border-color: rgba(25, 135, 84, 0.2);
    }

    50% {
      border-color: rgba(25, 135, 84, 0.5);
    }
  }

  .entreprise-context-indicator:hover {
    background: linear-gradient(135deg, rgba(25, 135, 84, 0.15) 0%, rgba(32, 201, 151, 0.15) 100%);
    border-color: rgba(25, 135, 84, 0.4);
    transform: scale(1.02);
  }

  /* ========================================
     Smooth Scrollbar
     ======================================== */
  .sidebar-wrapper {
    scrollbar-width: thin;
    scrollbar-color: rgba(255, 255, 255, 0.2) transparent;
  }

  .sidebar-wrapper::-webkit-scrollbar {
    width: 6px;
  }

  .sidebar-wrapper::-webkit-scrollbar-track {
    background: transparent;
  }

  .sidebar-wrapper::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 3px;
  }

  .sidebar-wrapper::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.3);
  }

  /* ========================================
     Fade Animations
     ======================================== */
  .fade-out {
    animation: fadeOut 0.3s ease-out forwards;
  }

  .fade-in {
    animation: fadeIn 0.3s ease-in forwards;
  }

  @keyframes fadeOut {
    from {
      opacity: 1;
    }

    to {
      opacity: 0;
      transform: translateY(-10px);
    }
  }

  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(10px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  /* ========================================
     Ripple Effect
     ======================================== */
  .ripple {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: scale(0);
    animation: ripple 0.6s linear;
    pointer-events: none;
  }

  @keyframes ripple {
    to {
      transform: scale(4);
      opacity: 0;
    }
  }

  /* ========================================
     Icon Bounce on Active
     ======================================== */
  .sidebar-menu .nav-link.active .nav-icon {
    animation: iconBounce 0.5s ease;
  }

  @keyframes iconBounce {

    0%,
    100% {
      transform: scale(1);
    }

    50% {
      transform: scale(1.3);
    }
  }

  /* ========================================
     Menu Item Stagger Animation
     ======================================== */
  .sidebar-menu>.nav-item {
    opacity: 0;
    animation: fadeInUp 0.4s ease forwards;
  }

  .sidebar-menu>.nav-item:nth-child(1) {
    animation-delay: 0.05s;
  }

  .sidebar-menu>.nav-item:nth-child(2) {
    animation-delay: 0.1s;
  }

  .sidebar-menu>.nav-item:nth-child(3) {
    animation-delay: 0.15s;
  }

  .sidebar-menu>.nav-item:nth-child(4) {
    animation-delay: 0.2s;
  }

  .sidebar-menu>.nav-item:nth-child(5) {
    animation-delay: 0.25s;
  }

  .sidebar-menu>.nav-item:nth-child(6) {
    animation-delay: 0.3s;
  }

  .sidebar-menu>.nav-item:nth-child(7) {
    animation-delay: 0.35s;
  }

  .sidebar-menu>.nav-item:nth-child(8) {
    animation-delay: 0.4s;
  }

  .sidebar-menu>.nav-item:nth-child(9) {
    animation-delay: 0.45s;
  }

  .sidebar-menu>.nav-item:nth-child(10) {
    animation-delay: 0.5s;
  }

  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(20px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // ========================================
    // Accordion Animation Handler
    // ========================================
    const menuToggles = document.querySelectorAll('.sidebar-menu .nav-item > .nav-link:not([href="#"])');

    menuToggles.forEach(toggle => {
      toggle.addEventListener('click', function(e) {
        const parent = this.parentElement;
        const treeview = parent.querySelector('.nav-treeview');

        if (treeview) {
          e.preventDefault();
          const isOpen = parent.classList.contains('menu-open') || parent.classList.contains('expand');

          // Close other open menus in the same level
          const siblings = parent.parentElement.querySelectorAll(':scope > .nav-item');
          siblings.forEach(sibling => {
            if (sibling !== parent) {
              sibling.classList.remove('menu-open', 'expand');
              const siblingTreeview = sibling.querySelector('.nav-treeview');
              if (siblingTreeview) {
                siblingTreeview.style.animation = 'slideUp 0.3s ease forwards';
                setTimeout(() => {
                  siblingTreeview.style.display = 'none';
                  siblingTreeview.style.animation = '';
                }, 300);
              }
            }
          });

          // Toggle current menu
          if (isOpen) {
            treeview.style.animation = 'slideUp 0.3s ease forwards';
            setTimeout(() => {
              parent.classList.remove('menu-open', 'expand');
              treeview.style.display = 'none';
              treeview.style.animation = '';
            }, 300);
          } else {
            parent.classList.add('menu-open', 'expand');
            treeview.style.display = 'block';
            treeview.style.animation = 'slideDown 0.3s ease forwards';
          }
        }
      });
    });

    // ========================================
    // Click Ripple Effect
    // ========================================
    document.querySelectorAll('.sidebar-menu .nav-link').forEach(link => {
      link.addEventListener('click', function(e) {
        // Create ripple element
        const ripple = document.createElement('span');
        ripple.classList.add('ripple');

        // Get click position relative to the link
        const rect = this.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';

        // Add ripple to link
        this.appendChild(ripple);

        // Remove ripple after animation
        setTimeout(() => ripple.remove(), 600);
      });
    });

    // ========================================
    // Active State Management
    // ========================================
    const currentPath = window.location.pathname;
    document.querySelectorAll('.sidebar-menu .nav-link').forEach(link => {
      const href = link.getAttribute('href');
      if (href && (currentPath === href || currentPath.startsWith(href + '/'))) {
        link.classList.add('active');

        // Open parent menus if closed
        let parent = link.closest('.nav-treeview');
        while (parent) {
          const parentItem = parent.closest('.nav-item');
          if (parentItem) {
            parentItem.classList.add('menu-open', 'expand');
            parent.style.display = 'block';
          }
          parent = parent.closest('.nav-treeview');
        }
      }
    });

    // ========================================
    // Hover Sound Effect (Optional - commented out)
    // ========================================
    // Uncomment below to enable hover sound
    // const hoverSound = new Audio('/path/to/hover-sound.mp3');
    // document.querySelectorAll('.sidebar-menu .nav-link').forEach(link => {
    //   link.addEventListener('mouseenter', () => {
    //     hoverSound.volume = 0.1;
    //     hoverSound.play().catch(() => {});
    //   });
    // });
  });

  // Animation de retour au Super Admin
  function animateReturn(event) {
    event.preventDefault();
    const btn = document.getElementById('returnSuperAdminBtn');

    if (btn) {
      // Ajouter la classe de chargement
      btn.classList.add('loading');

      // Ajouter un effet visuel
      btn.innerHTML = '<i class="bi bi-arrow-repeat me-2 spin"></i><span class="fw-bold">Retour en cours...</span>';

      // Attendre un peu pour l'animation
      setTimeout(() => {
        // Ajouter l'animation de sortie
        const mainContent = document.querySelector('.app-main');
        if (mainContent) {
          mainContent.classList.add('fade-out');
        }

        // Rediriger après l'animation
        setTimeout(() => {
          window.location.href = btn.getAttribute('href');
        }, 300);
      }, 500);
    }
  }
</script>