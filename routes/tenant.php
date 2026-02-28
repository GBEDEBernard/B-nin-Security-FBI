<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Ces routes sont accessibles via *.benin-security.com
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    'tenant',
])->group(function () {

    // ═══════════════════════════════════════════════════════════════════
    // AUTHENTIFICATION TENANT
    // ═══════════════════════════════════════════════════════════════════

    // Login/Logout - utilise le même contrôleur mais avec logique tenant
    Route::middleware('multi-guest')->group(function () {
        Route::get('/login', [\App\Http\Controllers\AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
    });

    // ═══════════════════════════════════════════════════════════════════
    // DASHBOARD TENANT - Redirection selon rôle
    // ═══════════════════════════════════════════════════════════════════

    Route::middleware(['role.redirect'])->group(function () {
        Route::get('/', function () {
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

            // Pas connecté - afficher la page de connexion
            return view('auth.login');
        })->name('home');
    });

    // ═══════════════════════════════════════════════════════════════════
    // ADMIN ENTREPRISE (Direction, Superviseur, Contrôleur)
    // ═══════════════════════════════════════════════════════════════════

    Route::middleware(['auth:employe', 'entreprise'])->prefix('admin/entreprise')->name('admin.entreprise.')->group(function () {
        // Dashboard
        Route::get('/', [\App\Http\Controllers\Entreprise\DashboardController::class, 'index'])->name('index');
        Route::get('/statistiques', [\App\Http\Controllers\Entreprise\DashboardController::class, 'statistiques'])->name('statistiques');

        // Gestion des employés
        Route::prefix('employes')->name('employes.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Entreprise\EmployeController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Entreprise\EmployeController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Entreprise\EmployeController::class, 'store'])->name('store');
            Route::get('/{id}', [\App\Http\Controllers\Entreprise\EmployeController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [\App\Http\Controllers\Entreprise\EmployeController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Entreprise\EmployeController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Entreprise\EmployeController::class, 'destroy'])->name('destroy');
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
        });

        // Affectations
        Route::prefix('affectations')->name('affectations.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Entreprise\AffectationController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Entreprise\AffectationController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Entreprise\AffectationController::class, 'store'])->name('store');
            Route::get('/{id}', [\App\Http\Controllers\Entreprise\AffectationController::class, 'show'])->name('show');
            Route::get('/planning', [\App\Http\Controllers\Entreprise\AffectationController::class, 'planning'])->name('planning');
        });

        // Rapports
        Route::prefix('rapports')->name('rapports.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Entreprise\RapportController::class, 'index'])->name('index');
            Route::get('/employes', [\App\Http\Controllers\Entreprise\RapportController::class, 'employes'])->name('employes');
            Route::get('/clients', [\App\Http\Controllers\Entreprise\RapportController::class, 'clients'])->name('clients');
        });
    });

    // ═══════════════════════════════════════════════════════════════════
    // ADMIN AGENT
    // ═══════════════════════════════════════════════════════════════════

    Route::middleware(['auth:employe', 'entreprise'])->prefix('admin/agent')->name('admin.agent.')->group(function () {
        Route::get('/', function () {
            return view('admin.agent');
        })->name('index');

        // Mes missions
        Route::prefix('missions')->name('missions.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Agent\MissionController::class, 'index'])->name('index');
            Route::get('/{id}', [\App\Http\Controllers\Agent\MissionController::class, 'show'])->name('show');
        });

        // Mes pointages
        Route::prefix('pointages')->name('pointages.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Agent\PointageController::class, 'index'])->name('index');
            Route::post('/entree', [\App\Http\Controllers\Agent\PointageController::class, 'pointerEntree'])->name('entree');
        });

        // Mes congés
        Route::prefix('conges')->name('conges.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Agent\CongeController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Agent\CongeController::class, 'store'])->name('store');
        });
    });

    // ═══════════════════════════════════════════════════════════════════
    // ADMIN CLIENT
    // ═══════════════════════════════════════════════════════════════════

    Route::middleware(['auth:client'])->prefix('admin/client')->name('admin.client.')->group(function () {
        Route::get('/', function () {
            return view('admin.client');
        })->name('index');

        // Mes contrats
        Route::prefix('contrats')->name('contrats.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Client\ContratController::class, 'index'])->name('index');
            Route::get('/{id}', [\App\Http\Controllers\Client\ContratController::class, 'show'])->name('show');
        });

        // Mes factures
        Route::prefix('factures')->name('factures.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Client\FactureController::class, 'index'])->name('index');
            Route::get('/{id}', [\App\Http\Controllers\Client\FactureController::class, 'show'])->name('show');
        });

        // Incidents
        Route::prefix('incidents')->name('incidents.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Client\IncidentController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Client\IncidentController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Client\IncidentController::class, 'store'])->name('store');
        });
    });
});
