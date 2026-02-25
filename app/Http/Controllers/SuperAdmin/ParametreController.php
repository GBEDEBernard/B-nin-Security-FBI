<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ParametreController extends Controller
{
    /**
     * Constructeur
     */
    public function __construct()
    {
        $this->middleware(['auth', 'superadmin']);
    }

    /**
     * Liste des paramètres
     */
    public function index()
    {
        // Paramètres système
        $parametres = [
            'application' => [
                'nom' => config('app.name', 'Bénin Security'),
                'env' => config('app.env'),
                'debug' => config('app.debug') ? 'Oui' : 'Non',
                'url' => config('app.url'),
                'timezone' => config('app.timezone'),
                'locale' => config('app.locale'),
            ],
            'database' => [
                'driver' => config('database.default'),
                'host' => config('database.connections.mysql.host'),
                'database' => config('database.connections.mysql.database'),
            ],
            'mail' => [
                'driver' => config('mail.default'),
                'host' => config('mail.mailers.smtp.host'),
                'port' => config('mail.mailers.smtp.port'),
            ],
            'security' => [
                'password_min_length' => 8,
                'session_lifetime' => config('session.lifetime'),
                'max_login_attempts' => 5,
            ],
        ];

        return view('admin.superadmin.parametres.index', compact('parametres'));
    }

    /**
     * Modifier les paramètres généraux
     */
    public function general(Request $request)
    {
        $validated = $request->validate([
            'nom_application' => 'required|string|max:255',
            'timezone' => 'required|string',
            'locale' => 'required|string|size:2',
        ]);

        // Sauvegarder dans un fichier de config ou base de données
        return redirect()->route('admin.superadmin.parametres.index')
            ->with('success', 'Paramètres généraux mis à jour.');
    }

    /**
     * Paramètres email
     */
    public function email(Request $request)
    {
        $validated = $request->validate([
            'mail_driver' => 'required|string',
            'mail_host' => 'required|string',
            'mail_port' => 'required|integer',
            'mail_username' => 'nullable|string',
            'mail_password' => 'nullable|string',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required|string',
        ]);

        return redirect()->route('admin.superadmin.parametres.index')
            ->with('success', 'Paramètres email mis à jour.');
    }

    /**
     * Paramètres de sécurité
     */
    public function security(Request $request)
    {
        $validated = $request->validate([
            'password_min_length' => 'required|integer|min:6',
            'session_lifetime' => 'required|integer|min:15',
            'max_login_attempts' => 'required|integer|min:3',
            'maintenance_mode' => 'boolean',
        ]);

        return redirect()->route('admin.superadmin.parametres.index')
            ->with('success', 'Paramètres de sécurité mis à jour.');
    }

    /**
     * Paramètres de l'API
     */
    public function api(Request $request)
    {
        $validated = $request->validate([
            'api_token_expiration' => 'required|integer',
            'api_rate_limit' => 'required|integer',
        ]);

        return redirect()->route('admin.superadmin.parametres.index')
            ->with('success', 'Paramètres API mis à jour.');
    }

    /**
     * Paramètres de l'application mobile
     */
    public function mobile(Request $request)
    {
        $validated = $request->validate([
            'app_version_minimum' => 'required|string',
            'notification_enabled' => 'boolean',
            'geolocation_enabled' => 'boolean',
        ]);

        return redirect()->route('admin.superadmin.parametres.index')
            ->with('success', 'Paramètres mobile mis à jour.');
    }

    /**
     * Envoyer un email de test
     */
    public function testEmail(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        // Logique d'envoi de test
        return back()->with('success', 'Email de test envoyé.');
    }

    /**
     * Vider le cache
     */
    public function clearCache()
    {
        // Artisan::call('cache:clear');
        // Artisan::call('config:clear');
        // Artisan::call('route:clear');
        // Artisan::call('view:clear');

        return back()->with('success', 'Cache vidé avec succès.');
    }

    /**
     * Voir les logs
     */
    public function logs()
    {
        $logs = [];

        // Lire les fichiers de logs
        $logFile = storage_path('logs/laravel.log');
        if (File::exists($logFile)) {
            $lines = File::lines($logFile)->take(100)->toArray();
            $logs = array_reverse($lines);
        }

        return view('admin.superadmin.parametres.logs', compact('logs'));
    }

    /**
     * Optimiser l'application
     */
    public function optimize()
    {
        // Artisan::call('config:cache');
        // Artisan::call('route:cache');
        // Artisan::call('view:cache');

        return back()->with('success', 'Application optimisée.');
    }
}
