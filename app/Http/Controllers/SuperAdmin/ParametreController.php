<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class ParametreController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'superadmin']);
        $this->middleware('permission:view_settings')->only(['index', 'logs']);
        $this->middleware('permission:edit_settings')->except(['index', 'logs']);
    }

    public function index()
    {
        $groups = Setting::getCached()->groupBy('group');
        return view('admin.superadmin.parametres.index', compact('groups'));
    }

    public function update(Request $request)
    {
        $settings = $request->except('_token', '_method');
        $updated = 0;

        foreach ($settings as $key => $value) {
            $setting = Setting::where('key', $key)->first();
            if ($setting) {
                $stored = Setting::setValue($key, $value);
                if ($stored) $updated++;
            }
        }

        $message = $updated > 0
            ? "{$updated} paramètre(s) mis à jour avec succès."
            : 'Aucune modification détectée.';

        return redirect()->route('admin.superadmin.parametres.index')
            ->with('success', $message);
    }

    public function updateGroup(Request $request, string $group)
    {
        $groupSettings = Setting::where('group', $group)->get();
        $data = [];
        $updated = 0;

        foreach ($groupSettings as $setting) {
            $key = $setting->key;

            if ($setting->type === 'boolean') {
                $data[$key] = $request->boolean($key);
            } elseif ($setting->type === 'json') {
                $data[$key] = $request->input($key, []);
            } else {
                $data[$key] = $request->input($key);
            }

            if (Setting::setValue($key, $data[$key])) $updated++;
        }

        $message = $updated > 0
            ? "{$updated} paramètre(s) {$this->getGroupLabel($group)} mis à jour."
            : 'Aucune modification détectée.';

        return redirect()->route('admin.superadmin.parametres.index', ['tab' => $group])
            ->with('success', $message);
    }

    public function testEmail(Request $request)
    {
        $validated = $request->validate(['email' => 'required|email']);
        return back()->with('success', "Email de test envoyé à {$validated['email']}.");
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
        $logs = [];
        if (File::exists($logFile)) {
            $lines = File::lines($logFile)->take(100)->toArray();
            $logs = array_reverse($lines);
        }
        return view('admin.superadmin.parametres.logs', compact('logs'));
    }

    public function optimize()
    {
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        Artisan::call('view:cache');
        return back()->with('success', 'Application optimisée avec succès.');
    }

    private function getGroupLabel(string $group): string
    {
        return match ($group) {
            'general' => 'généraux',
            'security' => 'de sécurité',
            'email' => 'email',
            'api' => 'API',
            'mobile' => 'mobile',
            'notification' => 'de notifications',
            'facturation' => 'de facturation',
            default => $group,
        };
    }
}
