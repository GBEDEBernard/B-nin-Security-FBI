<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuperAdminController;

/*|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which contains the "web" middleware group. Now create something great!   
*/

// Routes d'authentification
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Page d'accueil publique
Route::get('/', function () {
    return view('welcome');
})->name('home');

// ═══════════════════════════════════════════════════════════════════════════
// ROUTES DES ADMINISTRATIONS PAR RÔLE
// ═══════════════════════════════════════════════════════════════════════════

// Administration principal (redirige selon le rôle)
Route::get('/admin', function () {
    if (Auth::check()) {
        return redirect()->to(Auth::user()->getAdminUrl());
    }
    return redirect('/login');
})->middleware('auth')->name('admin');

// Admin Super Admin
Route::middleware(['auth', 'tenant'])->prefix('admin/superadmin')->name('admin.superadmin.')->group(function () {
    // Dashboard
    Route::get('/', [SuperAdminController::class, 'index'])->name('index');

    // Routes pour la gestion des entreprises (tenants)
    Route::prefix('entreprises')->name('entreprises.')->group(function () {
        Route::get('/', [SuperAdminController::class, 'entreprisesIndex'])->name('index');
        Route::get('/create', [SuperAdminController::class, 'entreprisesCreate'])->name('create');
        Route::get('/{id}', [SuperAdminController::class, 'entreprisesShow'])->name('show');
        Route::get('/{id}/edit', [SuperAdminController::class, 'entreprisesEdit'])->name('edit');

        // Route pour se connecter au tableau de bord d'une entreprise
        Route::post('/{id}/connect', [SuperAdminController::class, 'switchToEntreprise'])->name('connect');
    });

    // Routes pour la gestion des utilisateurs globaux
    Route::prefix('utilisateurs')->name('utilisateurs.')->group(function () {
        Route::get('/', [SuperAdminController::class, 'utilisateursIndex'])->name('index');
        Route::get('/create', [SuperAdminController::class, 'utilisateursCreate'])->name('create');
    });

    // Paramètres globaux
    Route::prefix('parametres')->name('parametres.')->group(function () {
        Route::get('/', [SuperAdminController::class, 'parametresIndex'])->name('index');
    });

    // Route pour retourner au dashboard superadmin
    Route::get('/return', [SuperAdminController::class, 'returnToSuperAdmin'])->name('return');
});

// Admin Entreprise (Direction, Superviseur, Contrôleur)
Route::middleware(['auth', 'tenant'])->prefix('admin/entreprise')->name('admin.entreprise.')->group(function () {
    Route::get('/', function () {
        return view('admin.entreprise');
    })->name('index');

    // Gestion des employés
    Route::prefix('employes')->name('employes.')->group(function () {
        Route::get('/', function () {
            return view('admin.entreprise.employes.index');
        })->name('index');
        Route::get('/create', function () {
            return view('admin.entreprise.employes.create');
        })->name('create');
    });

    // Gestion des clients
    Route::prefix('clients')->name('clients.')->group(function () {
        Route::get('/', function () {
            return view('admin.entreprise.clients.index');
        })->name('index');
        Route::get('/create', function () {
            return view('admin.entreprise.clients.create');
        })->name('create');
    });

    // Contrats
    Route::prefix('contrats')->name('contrats.')->group(function () {
        Route::get('/', function () {
            return view('admin.entreprise.contrats.index');
        })->name('index');
    });

    // Affectations
    Route::prefix('affectations')->name('affectations.')->group(function () {
        Route::get('/', function () {
            return view('admin.entreprise.affectations.index');
        })->name('index');
    });

    // Rapports
    Route::prefix('rapports')->name('rapports.')->group(function () {
        Route::get('/', function () {
            return view('admin.entreprise.rapports.index');
        })->name('index');
    });
});

// Admin Agent
Route::middleware(['auth', 'tenant'])->prefix('admin/agent')->name('admin.agent.')->group(function () {
    Route::get('/', function () {
        return view('admin.agent');
    })->name('index');

    // Mes pointages
    Route::prefix('pointages')->name('pointages.')->group(function () {
        Route::get('/', function () {
            return view('admin.agent.pointages.index');
        })->name('index');
    });

    // Mes missions
    Route::prefix('missions')->name('missions.')->group(function () {
        Route::get('/', function () {
            return view('admin.agent.missions.index');
        })->name('index');
    });

    // Mes congés
    Route::prefix('conges')->name('conges.')->group(function () {
        Route::get('/', function () {
            return view('admin.agent.conges.index');
        })->name('index');
    });
});

// Admin Client
Route::middleware(['auth', 'tenant'])->prefix('admin/client')->name('admin.client.')->group(function () {
    Route::get('/', function () {
        return view('admin.client');
    })->name('index');

    // Mes contrats
    Route::prefix('contrats')->name('contrats.')->group(function () {
        Route::get('/', function () {
            return view('admin.client.contrats.index');
        })->name('index');
    });

    // Mes factures
    Route::prefix('factures')->name('factures.')->group(function () {
        Route::get('/', function () {
            return view('admin.client.factures.index');
        })->name('index');
    });

    // Signaler un incident
    Route::prefix('incidents')->name('incidents.')->group(function () {
        Route::get('/', function () {
            return view('admin.client.incidents.index');
        })->name('index');
        Route::get('/create', function () {
            return view('admin.client.incidents.create');
        })->name('create');
    });
});
