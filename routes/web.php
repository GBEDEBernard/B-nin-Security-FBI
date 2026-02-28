<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuperAdminController;

/*|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which contains
| the "web" middleware group. Now create something great!
*/

// Routes d'authentification - utilise multi-guest pour vérifier tous les guards
Route::middleware('multi-guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/session/extend', [AuthController::class, 'extendSession'])->name('session.extend');
});

// Logout routes - accessible to all authenticated users (any guard)
// These routes are outside the auth middleware because we need to support multiple guards
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route GET pour la déconnexion via lien ou redirection automatique (redirige vers /login)
Route::get('/logout', function () {
    // Déconnecter tous les guards
    \Illuminate\Support\Facades\Auth::guard('web')->logout();
    \Illuminate\Support\Facades\Auth::guard('employe')->logout();
    \Illuminate\Support\Facades\Auth::guard('client')->logout();

    // Rediriger vers la page de connexion
    return redirect('/login');
})->name('logout.get');

// Page d'accueil - redirige vers la page de connexion pour les utilisateurs non connectés
Route::get('/', function () {
    // Si déjà connecté, rediriger vers le dashboard approprié
    if (Auth::guard('web')->check()) {
        $user = Auth::guard('web')->user();
        if ($user->estSuperAdmin()) {
            return redirect()->route('admin.superadmin.index');
        }
    }

    // Si connecté via guard employe
    if (Auth::guard('employe')->check()) {
        $employe = Auth::guard('employe')->user();
        return redirect()->to($employe->getDashboardUrl());
    }

    // Si connecté via guard client
    if (Auth::guard('client')->check()) {
        $client = Auth::guard('client')->user();
        return redirect()->to($client->getDashboardUrl());
    }

    // Pas connecté - afficher la page de connexion
    return redirect()->route('login');
})->name('home');

// Page d'accueil avec middleware role.redirect pour les utilisateurs connectés
Route::middleware(['role.redirect'])->group(function () {
    // Administration principal (redirige selon le rôle)
    Route::get('/admin', function () {
        // Vérifier si c'est un SuperAdmin
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->estSuperAdmin()) {
                return redirect()->route('admin.superadmin.index');
            }
        }

        // Vérifier si c'est un employé
        if (Auth::guard('employe')->check()) {
            $employe = Auth::guard('employe')->user();
            return redirect()->to($employe->getDashboardUrl());
        }

        // Vérifier si c'est un client
        if (Auth::guard('client')->check()) {
            $client = Auth::guard('client')->user();
            return redirect()->to($client->getDashboardUrl());
        }

        return redirect('/login');
    })->middleware('auth')->name('admin');
});

// Route publique pour le formulaire de devis
Route::get('/devis', function () {
    return view('public.devis');
})->name('devis');

// Route publique pour soumettre une proposition (accessible sans authentification)
Route::post('/devis', [\App\Http\Controllers\SuperAdmin\PropositionContratController::class, 'soumettre'])
    ->name('devis.soumettre');

// ═══════════════════════════════════════════════════════════════════════════
// ADMIN SUPER ADMIN
// ⚠️  PAS de 'role.redirect' dans ce groupe
// Note: On utilise 'superadmin' middleware qui vérifie l'authentification
// ═══════════════════════════════════════════════════════════════════════════

Route::middleware(['tenant', 'superadmin'])->prefix('admin/superadmin')->name('admin.superadmin.')->group(function () {
    // Dashboard
    Route::get('/', [SuperAdminController::class, 'index'])->name('index');

    // Gestion des entreprises (tenants)
    Route::prefix('entreprises')->name('entreprises.')->group(function () {
        Route::get('/', [SuperAdminController::class, 'entreprisesIndex'])->name('index');
        Route::get('/create', [SuperAdminController::class, 'entreprisesCreate'])->name('create');
        Route::post('/', [\App\Http\Controllers\SuperAdmin\EntrepriseController::class, 'store'])->name('store');
        Route::get('/{id}', [SuperAdminController::class, 'entreprisesShow'])->name('show');
        Route::get('/{id}/edit', [SuperAdminController::class, 'entreprisesEdit'])->name('edit');
        Route::put('/{id}', [\App\Http\Controllers\SuperAdmin\EntrepriseController::class, 'update'])->name('update');
        Route::delete('/{id}', [\App\Http\Controllers\SuperAdmin\EntrepriseController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/connect', [SuperAdminController::class, 'switchToEntreprise'])->name('connect');
        Route::post('/{id}/activate', [\App\Http\Controllers\SuperAdmin\EntrepriseController::class, 'activate'])->name('activate');
        Route::post('/{id}/deactivate', [\App\Http\Controllers\SuperAdmin\EntrepriseController::class, 'deactivate'])->name('deactivate');
    });

    // Gestion des contrats (Super Admin - toutes entreprises)
    Route::prefix('contrats')->name('contrats.')->group(function () {
        Route::get('/', [\App\Http\Controllers\SuperAdmin\ContratController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\SuperAdmin\ContratController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\SuperAdmin\ContratController::class, 'store'])->name('store');
        Route::get('/clients', [\App\Http\Controllers\SuperAdmin\ContratController::class, 'getClients'])->name('clients');
        Route::get('/{id}', [\App\Http\Controllers\SuperAdmin\ContratController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [\App\Http\Controllers\SuperAdmin\ContratController::class, 'edit'])->name('edit');
        Route::put('/{id}', [\App\Http\Controllers\SuperAdmin\ContratController::class, 'update'])->name('update');
        Route::delete('/{id}', [\App\Http\Controllers\SuperAdmin\ContratController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/statut', [\App\Http\Controllers\SuperAdmin\ContratController::class, 'changerStatut'])->name('changerStatut');
        Route::get('/{id}/dupliquer', [\App\Http\Controllers\SuperAdmin\ContratController::class, 'dupliquer'])->name('dupliquer');
    });

    // Gestion des propositions de contrat
    Route::prefix('propositions')->name('propositions.')->group(function () {
        Route::get('/', [\App\Http\Controllers\SuperAdmin\PropositionContratController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\SuperAdmin\PropositionContratController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\SuperAdmin\PropositionContratController::class, 'soumettre'])->name('soumettre');
        Route::get('/{id}', [\App\Http\Controllers\SuperAdmin\PropositionContratController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [\App\Http\Controllers\SuperAdmin\PropositionContratController::class, 'edit'])->name('edit');
        Route::put('/{id}', [\App\Http\Controllers\SuperAdmin\PropositionContratController::class, 'update'])->name('update');
        Route::delete('/{id}', [\App\Http\Controllers\SuperAdmin\PropositionContratController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/telecharger', [\App\Http\Controllers\SuperAdmin\PropositionContratController::class, 'telechargerContrat'])->name('telecharger');
        Route::post('/{id}/envoyer', [\App\Http\Controllers\SuperAdmin\PropositionContratController::class, 'envoyerContrat'])->name('envoyer');
        Route::post('/{id}/soumettre-signe', [\App\Http\Controllers\SuperAdmin\PropositionContratController::class, 'soumettreSigne'])->name('soumettreSigne');
        Route::post('/{id}/creer-entreprise', [\App\Http\Controllers\SuperAdmin\PropositionContratController::class, 'creerEntreprise'])->name('creerEntreprise');
        Route::post('/{id}/rejeter', [\App\Http\Controllers\SuperAdmin\PropositionContratController::class, 'rejeter'])->name('rejeter');
    });

    // Gestion des utilisateurs globaux
    Route::prefix('utilisateurs')->name('utilisateurs.')->group(function () {
        Route::get('/', [\App\Http\Controllers\SuperAdmin\UtilisateurController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\SuperAdmin\UtilisateurController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\SuperAdmin\UtilisateurController::class, 'store'])->name('store');
        Route::get('/{id}', [\App\Http\Controllers\SuperAdmin\UtilisateurController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [\App\Http\Controllers\SuperAdmin\UtilisateurController::class, 'edit'])->name('edit');
        Route::put('/{id}', [\App\Http\Controllers\SuperAdmin\UtilisateurController::class, 'update'])->name('update');
        Route::delete('/{id}', [\App\Http\Controllers\SuperAdmin\UtilisateurController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/activate', [\App\Http\Controllers\SuperAdmin\UtilisateurController::class, 'activate'])->name('activate');
        Route::post('/{id}/deactivate', [\App\Http\Controllers\SuperAdmin\UtilisateurController::class, 'deactivate'])->name('deactivate');
        Route::post('/{id}/reset-password', [\App\Http\Controllers\SuperAdmin\UtilisateurController::class, 'resetPassword'])->name('reset-password');
    });

    // Paramètres globaux
    Route::prefix('parametres')->name('parametres.')->group(function () {
        Route::get('/', [\App\Http\Controllers\SuperAdmin\ParametreController::class, 'index'])->name('index');
        Route::put('/general', [\App\Http\Controllers\SuperAdmin\ParametreController::class, 'general'])->name('general');
        Route::put('/email', [\App\Http\Controllers\SuperAdmin\ParametreController::class, 'email'])->name('email');
        Route::put('/security', [\App\Http\Controllers\SuperAdmin\ParametreController::class, 'security'])->name('security');
        Route::put('/api', [\App\Http\Controllers\SuperAdmin\ParametreController::class, 'api'])->name('api');
        Route::put('/mobile', [\App\Http\Controllers\SuperAdmin\ParametreController::class, 'mobile'])->name('mobile');
        Route::post('/test-email', [\App\Http\Controllers\SuperAdmin\ParametreController::class, 'testEmail'])->name('test-email');
        Route::post('/clear-cache', [\App\Http\Controllers\SuperAdmin\ParametreController::class, 'clearCache'])->name('clear-cache');
        Route::get('/logs', [\App\Http\Controllers\SuperAdmin\ParametreController::class, 'logs'])->name('logs');
        Route::post('/optimize', [\App\Http\Controllers\SuperAdmin\ParametreController::class, 'optimize'])->name('optimize');
    });

    // Abonnements
    Route::prefix('abonnements')->name('abonnements.')->group(function () {
        Route::get('/', [\App\Http\Controllers\SuperAdmin\AbonnementController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\SuperAdmin\AbonnementController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\SuperAdmin\AbonnementController::class, 'store'])->name('store');
        Route::get('/{id}', [\App\Http\Controllers\SuperAdmin\AbonnementController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [\App\Http\Controllers\SuperAdmin\AbonnementController::class, 'edit'])->name('edit');
        Route::put('/{id}', [\App\Http\Controllers\SuperAdmin\AbonnementController::class, 'update'])->name('update');
        Route::delete('/{id}', [\App\Http\Controllers\SuperAdmin\AbonnementController::class, 'destroy'])->name('destroy');

        // Actions sur les abonnements
        Route::post('/{id}/renew', [\App\Http\Controllers\SuperAdmin\AbonnementController::class, 'renew'])->name('renew');
        Route::post('/{id}/suspend', [\App\Http\Controllers\SuperAdmin\AbonnementController::class, 'suspend'])->name('suspend');
        Route::post('/{id}/activate', [\App\Http\Controllers\SuperAdmin\AbonnementController::class, 'activate'])->name('activate');
        Route::post('/{id}/resilier', [\App\Http\Controllers\SuperAdmin\AbonnementController::class, 'resilier'])->name('resilier');
        Route::post('/{id}/essai', [\App\Http\Controllers\SuperAdmin\AbonnementController::class, 'mettreEnEssai'])->name('essai');

        // Assignation aux entreprises
        Route::post('/{id}/assigner', [\App\Http\Controllers\SuperAdmin\AbonnementController::class, 'assigner'])->name('assigner');
        Route::delete('/{id}/retirer/{entrepriseId}', [\App\Http\Controllers\SuperAdmin\AbonnementController::class, 'retirer'])->name('retirer');
    });

    // Facturation globale
    Route::prefix('facturation')->name('facturation.')->group(function () {
        Route::get('/', [\App\Http\Controllers\SuperAdmin\FacturationController::class, 'index'])->name('index');
        Route::get('/paiements', [\App\Http\Controllers\SuperAdmin\FacturationController::class, 'paiements'])->name('paiements');
        Route::get('/creances', [\App\Http\Controllers\SuperAdmin\FacturationController::class, 'creances'])->name('creances');
        Route::get('/statistiques', [\App\Http\Controllers\SuperAdmin\FacturationController::class, 'statistiques'])->name('statistiques');
        Route::get('/{id}', [\App\Http\Controllers\SuperAdmin\FacturationController::class, 'show'])->name('show');
    });

    // Rapports globaux
    Route::prefix('rapports')->name('rapports.')->group(function () {
        Route::get('/', [\App\Http\Controllers\SuperAdmin\RapportController::class, 'index'])->name('index');
        Route::get('/par-entreprise', [\App\Http\Controllers\SuperAdmin\RapportController::class, 'parEntreprise'])->name('par-entreprise');
        Route::get('/financier', [\App\Http\Controllers\SuperAdmin\RapportController::class, 'financier'])->name('financier');
        Route::get('/employes', [\App\Http\Controllers\SuperAdmin\RapportController::class, 'employes'])->name('employes');
        Route::get('/clients', [\App\Http\Controllers\SuperAdmin\RapportController::class, 'clients'])->name('clients');
        Route::get('/contrats', [\App\Http\Controllers\SuperAdmin\RapportController::class, 'contrats'])->name('contrats');
    });

    // Gestion APK
    Route::prefix('apk')->name('apk.')->group(function () {
        Route::get('/', [\App\Http\Controllers\SuperAdmin\ApkController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\SuperAdmin\ApkController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\SuperAdmin\ApkController::class, 'store'])->name('store');
        Route::get('/{id}', [\App\Http\Controllers\SuperAdmin\ApkController::class, 'show'])->name('show');
        Route::post('/{id}/activate', [\App\Http\Controllers\SuperAdmin\ApkController::class, 'activate'])->name('activate');
        Route::post('/{id}/deactivate', [\App\Http\Controllers\SuperAdmin\ApkController::class, 'deactivate'])->name('deactivate');
        Route::delete('/{id}', [\App\Http\Controllers\SuperAdmin\ApkController::class, 'destroy'])->name('destroy');
        Route::get('/configurations', [\App\Http\Controllers\SuperAdmin\ApkController::class, 'configurations'])->name('configurations');
        Route::put('/configurations', [\App\Http\Controllers\SuperAdmin\ApkController::class, 'updateConfigurations'])->name('update-configurations');
        Route::get('/{id}/download', [\App\Http\Controllers\SuperAdmin\ApkController::class, 'download'])->name('download');
    });

    // Notifications Push
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [\App\Http\Controllers\SuperAdmin\NotificationController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\SuperAdmin\NotificationController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\SuperAdmin\NotificationController::class, 'store'])->name('store');
        Route::get('/{id}', [\App\Http\Controllers\SuperAdmin\NotificationController::class, 'show'])->name('show');
        Route::delete('/{id}', [\App\Http\Controllers\SuperAdmin\NotificationController::class, 'destroy'])->name('destroy');
        Route::get('/statistiques', [\App\Http\Controllers\SuperAdmin\NotificationController::class, 'statistiques'])->name('statistiques');
    });

    // Journal d'activité
    Route::prefix('journal')->name('journal.')->group(function () {
        Route::get('/', [\App\Http\Controllers\SuperAdmin\JournalController::class, 'index'])->name('index');
        Route::get('/{id}', [\App\Http\Controllers\SuperAdmin\JournalController::class, 'show'])->name('show');
        Route::get('/par-utilisateur', [\App\Http\Controllers\SuperAdmin\JournalController::class, 'parUtilisateur'])->name('par-utilisateur');
        Route::get('/par-module', [\App\Http\Controllers\SuperAdmin\JournalController::class, 'parModule'])->name('par-module');
        Route::get('/statistiques', [\App\Http\Controllers\SuperAdmin\JournalController::class, 'statistiques'])->name('statistiques');
        Route::post('/export', [\App\Http\Controllers\SuperAdmin\JournalController::class, 'export'])->name('export');
        Route::post('/purge', [\App\Http\Controllers\SuperAdmin\JournalController::class, 'purge'])->name('purge');
    });

    // Modèles
    Route::prefix('modeles')->name('modeles.')->group(function () {
        Route::get('/', [\App\Http\Controllers\SuperAdmin\ModeleController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\SuperAdmin\ModeleController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\SuperAdmin\ModeleController::class, 'store'])->name('store');
        Route::get('/{id}', [\App\Http\Controllers\SuperAdmin\ModeleController::class, 'show'])->name('show');
        Route::get('/{id}/preview', [\App\Http\Controllers\SuperAdmin\ModeleController::class, 'preview'])->name('preview');
        Route::get('/{id}/edit', [\App\Http\Controllers\SuperAdmin\ModeleController::class, 'edit'])->name('edit');
        Route::put('/{id}', [\App\Http\Controllers\SuperAdmin\ModeleController::class, 'update'])->name('update');
        Route::delete('/{id}', [\App\Http\Controllers\SuperAdmin\ModeleController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/download', [\App\Http\Controllers\SuperAdmin\ModeleController::class, 'download'])->name('download');
        Route::post('/{id}/duplicate', [\App\Http\Controllers\SuperAdmin\ModeleController::class, 'duplicate'])->name('duplicate');
    });

    // Route pour retourner au dashboard superadmin
    Route::get('/return', [SuperAdminController::class, 'returnToSuperAdmin'])->name('return');

    // Gestion des rôles
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [\App\Http\Controllers\SuperAdmin\RoleController::class, 'index'])->name('index');
        Route::get('/search', [\App\Http\Controllers\SuperAdmin\RoleController::class, 'search'])->name('search');
        Route::get('/listes', [\App\Http\Controllers\SuperAdmin\RoleController::class, 'roles'])->name('roles-list');
        Route::post('/roles', [\App\Http\Controllers\SuperAdmin\RoleController::class, 'createRole'])->name('create-role');
        Route::delete('/roles/{id}', [\App\Http\Controllers\SuperAdmin\RoleController::class, 'deleteRole'])->name('delete-role');
        Route::get('/{id}', [\App\Http\Controllers\SuperAdmin\RoleController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [\App\Http\Controllers\SuperAdmin\RoleController::class, 'edit'])->name('edit');
        Route::put('/{id}', [\App\Http\Controllers\SuperAdmin\RoleController::class, 'update'])->name('update');
        Route::post('/{id}/assign-role', [\App\Http\Controllers\SuperAdmin\RoleController::class, 'assignRole'])->name('assign-role');
        Route::delete('/{id}/remove-role', [\App\Http\Controllers\SuperAdmin\RoleController::class, 'removeRole'])->name('remove-role');
    });
});

// ═══════════════════════════════════════════════════════════════════════════
// ADMIN ENTREPRISE (Direction, Superviseur, Contrôleur)
// ⚠️  PAS de 'role.redirect' dans ce groupe
// Note: On utilise 'entreprise' middleware qui vérifie l'authentification
// ═══════════════════════════════════════════════════════════════════════════

Route::middleware(['tenant', 'entreprise'])->prefix('admin/entreprise')->name('admin.entreprise.')->group(function () {
    // Dashboard
    Route::get('/', [\App\Http\Controllers\Entreprise\DashboardController::class, 'index'])->name('index');
    Route::get('/statistiques', [\App\Http\Controllers\Entreprise\DashboardController::class, 'statistiques'])->name('statistiques');
    Route::get('/profile', [\App\Http\Controllers\Entreprise\DashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [\App\Http\Controllers\Entreprise\DashboardController::class, 'updateProfile'])->name('profile.update');

    // Gestion des employés
    Route::prefix('employes')->name('employes.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Entreprise\EmployeController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Entreprise\EmployeController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Entreprise\EmployeController::class, 'store'])->name('store');
        Route::get('/{id}', [\App\Http\Controllers\Entreprise\EmployeController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [\App\Http\Controllers\Entreprise\EmployeController::class, 'edit'])->name('edit');
        Route::put('/{id}', [\App\Http\Controllers\Entreprise\EmployeController::class, 'update'])->name('update');
        Route::delete('/{id}', [\App\Http\Controllers\Entreprise\EmployeController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/conge', [\App\Http\Controllers\Entreprise\EmployeController::class, 'mettreEnConge'])->name('conge');
        Route::post('/{id}/reprendre', [\App\Http\Controllers\Entreprise\EmployeController::class, 'reprendre'])->name('reprendre');
        Route::get('/disponibles', [\App\Http\Controllers\Entreprise\EmployeController::class, 'disponibles'])->name('disponibles');
    });

    // Gestion des clients
    Route::prefix('clients')->name('clients.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Entreprise\ClientController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Entreprise\ClientController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Entreprise\ClientController::class, 'store'])->name('store');
        Route::get('/{id}', [\App\Http\Controllers\Entreprise\ClientController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [\App\Http\Controllers\Entreprise\ClientController::class, 'edit'])->name('edit');
        Route::put('/{id}', [\App\Http\Controllers\Entreprise\ClientController::class, 'update'])->name('update');
        Route::delete('/{id}', [\App\Http\Controllers\Entreprise\ClientController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/sites', [\App\Http\Controllers\Entreprise\ClientController::class, 'sites'])->name('sites');
        Route::get('/{id}/contrats', [\App\Http\Controllers\Entreprise\ClientController::class, 'contrats'])->name('contrats');
        Route::get('/{id}/factures', [\App\Http\Controllers\Entreprise\ClientController::class, 'factures'])->name('factures');
    });

    // Contrats
    Route::prefix('contrats')->name('contrats.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Entreprise\ContratController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Entreprise\ContratController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Entreprise\ContratController::class, 'store'])->name('store');
        Route::get('/{id}', [\App\Http\Controllers\Entreprise\ContratController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [\App\Http\Controllers\Entreprise\ContratController::class, 'edit'])->name('edit');
        Route::put('/{id}', [\App\Http\Controllers\Entreprise\ContratController::class, 'update'])->name('update');
        Route::delete('/{id}', [\App\Http\Controllers\Entreprise\ContratController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/resilier', [\App\Http\Controllers\Entreprise\ContratController::class, 'resilier'])->name('resilier');
        Route::post('/{id}/renouveler', [\App\Http\Controllers\Entreprise\ContratController::class, 'renouveler'])->name('renouveler');
        Route::post('/{id}/suspendre', [\App\Http\Controllers\Entreprise\ContratController::class, 'suspendre'])->name('suspendre');
        Route::post('/{id}/reprendre', [\App\Http\Controllers\Entreprise\ContratController::class, 'reprendre'])->name('reprendre');
    });

    // Affectations
    Route::prefix('affectations')->name('affectations.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Entreprise\AffectationController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Entreprise\AffectationController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Entreprise\AffectationController::class, 'store'])->name('store');
        Route::get('/{id}', [\App\Http\Controllers\Entreprise\AffectationController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [\App\Http\Controllers\Entreprise\AffectationController::class, 'edit'])->name('edit');
        Route::put('/{id}', [\App\Http\Controllers\Entreprise\AffectationController::class, 'update'])->name('update');
        Route::delete('/{id}', [\App\Http\Controllers\Entreprise\AffectationController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/terminer', [\App\Http\Controllers\Entreprise\AffectationController::class, 'terminer'])->name('terminer');
        Route::post('/{id}/annuler', [\App\Http\Controllers\Entreprise\AffectationController::class, 'annuler'])->name('annuler');
        Route::get('/planning', [\App\Http\Controllers\Entreprise\AffectationController::class, 'planning'])->name('planning');
    });

    // Rapports
    Route::prefix('rapports')->name('rapports.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Entreprise\RapportController::class, 'index'])->name('index');
        Route::get('/employes', [\App\Http\Controllers\Entreprise\RapportController::class, 'employes'])->name('employes');
        Route::get('/clients', [\App\Http\Controllers\Entreprise\RapportController::class, 'clients'])->name('clients');
        Route::get('/financier', [\App\Http\Controllers\Entreprise\RapportController::class, 'financier'])->name('financier');
        Route::get('/incidents', [\App\Http\Controllers\Entreprise\RapportController::class, 'incidents'])->name('incidents');
        Route::get('/affectations', [\App\Http\Controllers\Entreprise\RapportController::class, 'affectations'])->name('affectations');
    });
});

// ═══════════════════════════════════════════════════════════════════════════
// ADMIN AGENT
// ⚠️  PAS de 'role.redirect' dans ce groupe
// Note: On utilise 'auth.employe' pour authenticate avec le guard employe
// ═══════════════════════════════════════════════════════════════════════════

Route::middleware(['tenant', 'entreprise'])->prefix('admin/agent')->name('admin.agent.')->group(function () {
    // Dashboard
    Route::get('/', function () {
        return view('admin.agent');
    })->name('index');

    // Mes missions
    Route::prefix('missions')->name('missions.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Agent\MissionController::class, 'index'])->name('index');
        Route::get('/{id}', [\App\Http\Controllers\Agent\MissionController::class, 'show'])->name('show');
        Route::post('/{id}/accepter', [\App\Http\Controllers\Agent\MissionController::class, 'accepter'])->name('accepter');
        Route::post('/{id}/refuser', [\App\Http\Controllers\Agent\MissionController::class, 'refuser'])->name('refuser');
        Route::post('/{id}/terminer', [\App\Http\Controllers\Agent\MissionController::class, 'terminer'])->name('terminer');
    });

    // Mes pointages
    Route::prefix('pointages')->name('pointages.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Agent\PointageController::class, 'index'])->name('index');
        Route::get('/today', [\App\Http\Controllers\Agent\PointageController::class, 'today'])->name('today');
        Route::post('/entree', [\App\Http\Controllers\Agent\PointageController::class, 'pointerEntree'])->name('entree');
        Route::post('/{id}/sortie', [\App\Http\Controllers\Agent\PointageController::class, 'pointerSortie'])->name('sortie');
        Route::get('/{id}', [\App\Http\Controllers\Agent\PointageController::class, 'show'])->name('show');
        Route::post('/{id}/probleme', [\App\Http\Controllers\Agent\PointageController::class, 'signalerProbleme'])->name('probleme');
    });

    // Mes congés
    Route::prefix('conges')->name('conges.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Agent\CongeController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\Agent\CongeController::class, 'store'])->name('store');
        Route::get('/{id}', [\App\Http\Controllers\Agent\CongeController::class, 'show'])->name('show');
        Route::post('/{id}/annuler', [\App\Http\Controllers\Agent\CongeController::class, 'annuler'])->name('annuler');
        Route::get('/soldes', [\App\Http\Controllers\Agent\CongeController::class, 'soldes'])->name('soldes');
        Route::get('/calendrier', [\App\Http\Controllers\Agent\CongeController::class, 'calendrier'])->name('calendrier');
    });
});

// ═══════════════════════════════════════════════════════════════════════════
// ADMIN CLIENT
// ⚠️  PAS de 'role.redirect' dans ce groupe
// Note: On utilise 'client' middleware pour authenticate avec le guard client
// ═══════════════════════════════════════════════════════════════════════════

Route::middleware(['tenant', 'client'])->prefix('admin/client')->name('admin.client.')->group(function () {
    // Dashboard
    Route::get('/', function () {
        return view('admin.client');
    })->name('index');

    // Mes contrats
    Route::prefix('contrats')->name('contrats.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Client\ContratController::class, 'index'])->name('index');
        Route::get('/{id}', [\App\Http\Controllers\Client\ContratController::class, 'show'])->name('show');
        Route::post('/{id}/resilier', [\App\Http\Controllers\Client\ContratController::class, 'resilier'])->name('resilier');
        Route::post('/{id}/signer-avenant', [\App\Http\Controllers\Client\ContratController::class, 'signerAvenant'])->name('signer-avenant');
    });

    // Mes factures
    Route::prefix('factures')->name('factures.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Client\FactureController::class, 'index'])->name('index');
        Route::get('/{id}', [\App\Http\Controllers\Client\FactureController::class, 'show'])->name('show');
        Route::get('/{id}/download', [\App\Http\Controllers\Client\FactureController::class, 'downloadPdf'])->name('download');
        Route::post('/{id}/payer', [\App\Http\Controllers\Client\FactureController::class, 'payer'])->name('payer');
        Route::post('/{id}/contester', [\App\Http\Controllers\Client\FactureController::class, 'contester'])->name('contester');
    });

    // Incidents
    Route::prefix('incidents')->name('incidents.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Client\IncidentController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Client\IncidentController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Client\IncidentController::class, 'store'])->name('store');
        Route::get('/{id}', [\App\Http\Controllers\Client\IncidentController::class, 'show'])->name('show');
        Route::post('/{id}/completer', [\App\Http\Controllers\Client\IncidentController::class, 'completer'])->name('completer');
        Route::post('/{id}/clore', [\App\Http\Controllers\Client\IncidentController::class, 'clore'])->name('clore');
    });
});
