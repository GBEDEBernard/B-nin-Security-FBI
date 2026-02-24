<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;

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
// ROUTES DES DASHBOARDS PAR RÔLE
// ═══════════════════════════════════════════════════════════════════════════

// Dashboard principal (redirige selon le rôle)
Route::get('/dashboard', function () {
    if (Auth::check()) {
        return redirect()->to(Auth::user()->getDashboardUrl());
    }
    return redirect('/login');
})->middleware('auth')->name('dashboard');

// Dashboard Super Admin
Route::middleware(['auth', 'tenant'])->prefix('dashboard/superadmin')->name('dashboard.superadmin.')->group(function () {
    Route::get('/', function () {
        return view('dashboard.superadmin');
    })->name('index');

    // Routes pour la gestion des entreprises (tenants)
    Route::prefix('entreprises')->name('entreprises.')->group(function () {
        Route::get('/', function () {
            return view('dashboard.superadmin.entreprises.index');
        })->name('index');
        Route::get('/create', function () {
            return view('dashboard.superadmin.entreprises.create');
        })->name('create');
        Route::get('/{id}', function ($id) {
            return view('dashboard.superadmin.entreprises.show', ['id' => $id]);
        })->name('show');
        Route::get('/{id}/edit', function ($id) {
            return view('dashboard.superadmin.entreprises.edit', ['id' => $id]);
        })->name('edit');
    });

    // Routes pour la gestion des utilisateurs globaux
    Route::prefix('utilisateurs')->name('utilisateurs.')->group(function () {
        Route::get('/', function () {
            return view('dashboard.superadmin.utilisateurs.index');
        })->name('index');
        Route::get('/create', function () {
            return view('dashboard.superadmin.utilisateurs.create');
        })->name('create');
    });

    // Paramètres globaux
    Route::prefix('parametres')->name('parametres.')->group(function () {
        Route::get('/', function () {
            return view('dashboard.superadmin.parametres.index');
        })->name('index');
    });
});

// Dashboard Entreprise (Direction, Superviseur, Contrôleur)
Route::middleware(['auth', 'tenant'])->prefix('dashboard/entreprise')->name('dashboard.entreprise.')->group(function () {
    Route::get('/', function () {
        return view('dashboard.entreprise');
    })->name('index');

    // Gestion des employés
    Route::prefix('employes')->name('employes.')->group(function () {
        Route::get('/', function () {
            return view('dashboard.entreprise.employes.index');
        })->name('index');
        Route::get('/create', function () {
            return view('dashboard.entreprise.employes.create');
        })->name('create');
    });

    // Gestion des clients
    Route::prefix('clients')->name('clients.')->group(function () {
        Route::get('/', function () {
            return view('dashboard.entreprise.clients.index');
        })->name('index');
        Route::get('/create', function () {
            return view('dashboard.entreprise.clients.create');
        })->name('create');
    });

    // Contrats
    Route::prefix('contrats')->name('contrats.')->group(function () {
        Route::get('/', function () {
            return view('dashboard.entreprise.contrats.index');
        })->name('index');
    });

    // Affectations
    Route::prefix('affectations')->name('affectations.')->group(function () {
        Route::get('/', function () {
            return view('dashboard.entreprise.affectations.index');
        })->name('index');
    });

    // Rapports
    Route::prefix('rapports')->name('rapports.')->group(function () {
        Route::get('/', function () {
            return view('dashboard.entreprise.rapports.index');
        })->name('index');
    });
});

// Dashboard Agent
Route::middleware(['auth', 'tenant'])->prefix('dashboard/agent')->name('dashboard.agent.')->group(function () {
    Route::get('/', function () {
        return view('dashboard.agent');
    })->name('index');

    // Mes pointages
    Route::prefix('pointages')->name('pointages.')->group(function () {
        Route::get('/', function () {
            return view('dashboard.agent.pointages.index');
        })->name('index');
    });

    // Mes missions
    Route::prefix('missions')->name('missions.')->group(function () {
        Route::get('/', function () {
            return view('dashboard.agent.missions.index');
        })->name('index');
    });

    // Mes congés
    Route::prefix('conges')->name('conges.')->group(function () {
        Route::get('/', function () {
            return view('dashboard.agent.conges.index');
        })->name('index');
    });
});

// Dashboard Client
Route::middleware(['auth', 'tenant'])->prefix('dashboard/client')->name('dashboard.client.')->group(function () {
    Route::get('/', function () {
        return view('dashboard.client');
    })->name('index');

    // Mes contrats
    Route::prefix('contrats')->name('contrats.')->group(function () {
        Route::get('/', function () {
            return view('dashboard.client.contrats.index');
        })->name('index');
    });

    // Mes factures
    Route::prefix('factures')->name('factures.')->group(function () {
        Route::get('/', function () {
            return view('dashboard.client.factures.index');
        })->name('index');
    });

    // Signaler un incident
    Route::prefix('incidents')->name('incidents.')->group(function () {
        Route::get('/', function () {
            return view('dashboard.client.incidents.index');
        })->name('index');
        Route::get('/create', function () {
            return view('dashboard.client.incidents.create');
        })->name('create');
    });
});
