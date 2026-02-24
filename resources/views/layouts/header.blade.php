 <!--begin::Header-->
 <nav class="app-header navbar navbar-expand bg-dark shadow-sm" data-bs-theme="dark">
   <!--begin::Container-->
   <div class="container-fluid">
     <!--begin::Start Navbar Links-->
     <ul class="navbar-nav">
       <li class="nav-item">
         <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
           <i class="bi bi-list"></i>
         </a>
       </li>
       <li class="nav-item d-none d-md-block">
         <a href="{{ route('dashboard') }}" class="nav-link">
           <i class="bi bi-house-door me-1"></i> Accueil
         </a>
       </li>
     </ul>
     <!--end::Start Navbar Links-->

     <!--begin::End Navbar Links-->
     <ul class="navbar-nav ms-auto">
       <!--begin::Navbar Search-->
       <li class="nav-item">
         <a class="nav-link" data-widget="navbar-search" href="#" role="button">
           <i class="bi bi-search"></i>
         </a>
       </li>
       <!--end::Navbar Search-->

       <!--begin::Messages Dropdown Menu-->
       <li class="nav-item dropdown">
         <a class="nav-link" data-bs-toggle="dropdown" href="#">
           <i class="bi bi-chat-text"></i>
           <span class="navbar-badge badge text-bg-danger">3</span>
         </a>
         <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
           <a href="#" class="dropdown-item">
             <!--begin::Message-->
             <div class="d-flex">
               <div class="flex-shrink-0">
                 <img
                   src="{{ asset('dist/assets/img/user1-128x128.jpg') }}"
                   alt="User Avatar"
                   class="img-size-50 rounded-circle me-3" />
               </div>
               <div class="flex-grow-1">
                 <h3 class="dropdown-item-title">
                   Brad Diesel
                   <span class="float-end fs-7 text-danger"><i class="bi bi-star-fill"></i></span>
                 </h3>
                 <p class="fs-7">Call me whenever you can...</p>
                 <p class="fs-7 text-secondary">
                   <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                 </p>
               </div>
             </div>
             <!--end::Message-->
           </a>
           <div class="dropdown-divider"></div>
           <a href="#" class="dropdown-item">
             <!--begin::Message-->
             <div class="d-flex">
               <div class="flex-shrink-0">
                 <img
                   src="{{ asset('dist/assets/img/user8-128x128.jpg') }}"
                   alt="User Avatar"
                   class="img-size-50 rounded-circle me-3" />
               </div>
               <div class="flex-grow-1">
                 <h3 class="dropdown-item-title">
                   John Pierce
                   <span class="float-end fs-7 text-secondary">
                     <i class="bi bi-star-fill"></i>
                   </span>
                 </h3>
                 <p class="fs-7">I got your message bro</p>
                 <p class="fs-7 text-secondary">
                   <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                 </p>
               </div>
             </div>
             <!--end::Message-->
           </a>
           <div class="dropdown-divider"></div>
           <a href="#" class="dropdown-item">
             <!--begin::Message-->
             <div class="d-flex">
               <div class="flex-shrink-0">
                 <img
                   src="{{ asset('dist/assets/img/user3-128x128.jpg') }}"
                   alt="User Avatar"
                   class="img-size-50 rounded-circle me-3" />
               </div>
               <div class="flex-grow-1">
                 <h3 class="dropdown-item-title">
                   Nora Silvester
                   <span class="float-end fs-7 text-warning">
                     <i class="bi bi-star-fill"></i>
                   </span>
                 </h3>
                 <p class="fs-7">The subject goes here</p>
                 <p class="fs-7 text-secondary">
                   <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                 </p>
               </div>
             </div>
             <!--end::Message-->
           </a>
           <div class="dropdown-divider"></div>
           <a href="#" class="dropdown-item dropdown-footer">Voir tous les messages</a>
         </div>
       </li>
       <!--end::Messages Dropdown Menu-->

       <!--begin::Notifications Dropdown Menu-->
       <li class="nav-item dropdown">
         <a class="nav-link" data-bs-toggle="dropdown" href="#">
           <i class="bi bi-bell-fill"></i>
           <span class="navbar-badge badge text-bg-warning">5</span>
         </a>
         <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
           <span class="dropdown-item dropdown-header">5 Notifications</span>
           <div class="dropdown-divider"></div>
           <a href="#" class="dropdown-item">
             <i class="bi bi-envelope me-2"></i> 4 nouveaux messages
             <span class="float-end text-secondary fs-7">3 mins</span>
           </a>
           <div class="dropdown-divider"></div>
           <a href="#" class="dropdown-item">
             <i class="bi bi-people-fill me-2"></i> 8 demandes de congés
             <span class="float-end text-secondary fs-7">12 heures</span>
           </a>
           <div class="dropdown-divider"></div>
           <a href="#" class="dropdown-item">
             <i class="bi bi-exclamation-triangle me-2"></i> 3 nouveaux incidents
             <span class="float-end text-secondary fs-7">2 jours</span>
           </a>
           <div class="dropdown-divider"></div>
           <a href="#" class="dropdown-item dropdown-footer">Voir toutes les notifications</a>
         </div>
       </li>
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
       <li class="nav-item dropdown user-menu">
         <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
           <div class="user-image-wrapper">
             @if(Auth::user()->photo)
             <img
               src="{{ asset('storage/' . Auth::user()->photo) }}"
               class="user-image rounded-circle shadow"
               alt="User Image" />
             @else
             <div class="user-avatar rounded-circle shadow">
               {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
             </div>
             @endif
           </div>
           <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
         </a>
         <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
           <!--begin::User Image-->
           <li class="user-header text-bg-dark">
             @if(Auth::user()->photo)
             <img
               src="{{ asset('storage/' . Auth::user()->photo) }}"
               class="rounded-circle shadow"
               alt="User Image" />
             @else
             <div class="user-avatar-lg rounded-circle shadow mx-auto mb-2">
               {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
             </div>
             @endif
             <p>
               {{ Auth::user()->name }}
               @if(Auth::user()->roles && count(Auth::user()->roles) > 0)
               <small>{{ Auth::user()->roles[0]->name }}</small>
               @else
               <small>Membre</small>
               @endif
               <small>Membre depuis {{ Auth::user()->created_at->format('M. Y') }}</small>
             </p>
           </li>
           <!--end::User Image-->
           <!--begin::Menu Body-->
           <li class="user-body">
             <!--begin::Row-->
             <div class="row">
               <div class="col-4 text-center">
                 <a href="#">Profil</a>
               </div>
               <div class="col-4 text-center">
                 <a href="#">Rôles</a>
               </div>
               <div class="col-4 text-center">
                 <a href="#">Paramètres</a>
               </div>
             </div>
             <!--end::Row-->
           </li>
           <!--end::Menu Body-->
           <!--begin::Menu Footer-->
           <li class="user-footer">
             <a href="#" class="btn btn-outline-secondary">
               <i class="bi bi-person-circle me-1"></i> Profil
             </a>
             <form method="POST" action="{{ route('logout') }}" class="d-inline">
               @csrf
               <button type="submit" class="btn btn-outline-danger float-end">
                 <i class="bi bi-box-arrow-right me-1"></i> Déconnexion
               </button>
             </form>
           </li>
           <!--end::Menu Footer-->
         </ul>
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
       <li class="nav-item">
         <button
           class="btn btn-link nav-link py-2 px-0 px-lg-2 dropdown-toggle d-flex align-items-center"
           id="bd-theme"
           type="button"
           aria-expanded="false"
           data-bs-toggle="dropdown"
           data-bs-display="static">
           <span class="theme-icon-active">
             <i class="my-1"></i>
           </span>
           <span class="d-lg-none ms-2" id="bd-theme-text">Changer le thème</span>
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
   /* Custom Header Styles */
   .user-image-wrapper {
     position: relative;
     width: 32px;
     height: 32px;
     overflow: hidden;
   }

   .user-image {
     width: 32px;
     height: 32px;
     object-fit: cover;
   }

   .user-avatar {
     width: 32px;
     height: 32px;
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

   .navbar-badge {
     position: absolute;
     top: 2px;
     right: 2px;
     font-size: 0.6rem;
     padding: 0.15rem 0.35rem;
   }
 </style>