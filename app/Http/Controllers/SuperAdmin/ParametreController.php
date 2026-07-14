<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;

class ParametreController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'superadmin']);
    }

    public function index()
    {
        $settings = SystemSetting::all()->groupBy('group');

        return view('admin.superadmin.parametres.index', compact('settings'));
    }

    public function general(Request $request)
    {
        $validated = $request->validate([
            'app_name'     => 'required|string|max:255',
            'app_env'      => 'required|in:production,local,staging',
            'app_debug'    => 'boolean',
            'app_url'      => 'required|url',
            'app_timezone' => 'required|timezone',
            'app_locale'   => 'required|size:2',
        ]);

        foreach ($validated as $key => $value) {
            SystemSetting::set($key, $value, 'general');
        }

        return redirect()->route('admin.superadmin.parametres.index', ['tab' => 'general'])
            ->with('success', 'Paramètres généraux mis à jour avec succès.');
    }

    public function email(Request $request)
    {
        $validated = $request->validate([
            'mail_driver'       => 'required|in:smtp,mailgun,postmark,ses,log,array',
            'mail_host'         => 'required|string',
            'mail_port'         => 'required|integer',
            'mail_encryption'   => 'nullable|in:tls,ssl,null',
            'mail_username'     => 'nullable|string',
            'mail_password'     => 'nullable|string',
            'mail_from_address' => 'required|email',
            'mail_from_name'    => 'required|string',
        ]);

        foreach ($validated as $key => $value) {
            $encrypted = in_array($key, ['mail_password']);
            SystemSetting::set($key, $value, 'email', $encrypted);
        }

        return redirect()->route('admin.superadmin.parametres.index', ['tab' => 'email'])
            ->with('success', 'Paramètres email mis à jour avec succès.');
    }

    public function security(Request $request)
    {
        $validated = $request->validate([
            'password_min_length' => 'required|integer|min:6',
            'session_lifetime'    => 'required|integer|min:15',
            'max_login_attempts'  => 'required|integer|min:3',
            'maintenance_mode'    => 'boolean',
        ]);

        foreach ($validated as $key => $value) {
            SystemSetting::set($key, $value, 'security');
        }

        return redirect()->route('admin.superadmin.parametres.index', ['tab' => 'security'])
            ->with('success', 'Paramètres de sécurité mis à jour avec succès.');
    }

    public function api(Request $request)
    {
        $validated = $request->validate([
            'api_token_expiration' => 'required|integer|min:1',
            'api_rate_limit'       => 'required|integer|min:10',
        ]);

        foreach ($validated as $key => $value) {
            SystemSetting::set($key, $value, 'api');
        }

        return redirect()->route('admin.superadmin.parametres.index', ['tab' => 'api'])
            ->with('success', 'Paramètres API mis à jour avec succès.');
    }

    public function mobile(Request $request)
    {
        $validated = $request->validate([
            'app_version_minimum'  => 'required|string',
            'notification_enabled' => 'boolean',
            'geolocation_enabled'  => 'boolean',
        ]);

        foreach ($validated as $key => $value) {
            SystemSetting::set($key, $value, 'mobile');
        }

        return redirect()->route('admin.superadmin.parametres.index', ['tab' => 'mobile'])
            ->with('success', 'Paramètres mobile mis à jour avec succès.');
    }

    public function testEmail(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        try {
            Mail::raw('Ceci est un email de test depuis Bénin Security FBI.', function ($message) use ($validated) {
                $message->to($validated['email'])
                    ->subject('Test de configuration email');
            });

            return back()->with('success', 'Email de test envoyé avec succès à ' . $validated['email'] . '.');
        } catch (\Exception $e) {
            return back()->with('error', 'Échec de l\'envoi : ' . $e->getMessage());
        }
    }

    public function clearCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');

        return back()->with('success', 'Cache vidé avec succès.');
    }

    public function logs()
    {
        $logFile = storage_path('logs/laravel.log');
        $entries = [];

        if (File::exists($logFile)) {
            $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $current = null;

            foreach ($lines as $line) {
                if (preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\].*?\.([A-Z_]+):\s*(.*)$/', $line, $m)) {
                    if ($current) {
                        $entries[] = $current;
                    }
                    $current = [
                        'timestamp' => $m[1],
                        'level'     => strtolower($m[2]),
                        'message'   => $m[3],
                        'trace'     => [],
                        'full'      => $line,
                    ];
                } elseif ($current) {
                    $current['trace'][] = $line;
                    $current['full'] .= "\n" . $line;
                }
            }
            if ($current) {
                $entries[] = $current;
            }

            $entries = array_reverse($entries);
            $entries = array_slice($entries, 0, 200);
        }

        return view('admin.superadmin.parametres.logs', compact('entries'));
    }

    public function optimize()
    {
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        Artisan::call('view:cache');

        return back()->with('success', 'Application optimisée avec succès.');
    }
}
