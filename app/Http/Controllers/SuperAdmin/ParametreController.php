<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Parametre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
     * Liste des paramètres avec organisation par catégorie
     */
    public function index(Request $request)
    {
        $categorie = $request->get('categorie', 'general');

        // Stats pour le dashboard
        $stats = [
            'total' => Parametre::count(),
            'categories' => Parametre::distinct()->count('categorie'),
            'modifiables' => Parametre::where('est_modifiable', true)->count(),
        ];

        // Paramètres par catégorie
        $parametres = Parametre::where('categorie', $categorie)
            ->orderBy('cle')
            ->get();

        // Toutes les catégories
        $categories = Parametre::CATEGORIES;

        return view('admin.superadmin.parametres.index', compact(
            'parametres',
            'categories',
            'categorie',
            'stats'
        ));
    }

    /**
     * Mettre à jour un paramètre spécifique
     */
    public function update(Request $request, $id)
    {
        $parametre = Parametre::findOrFail($id);

        if (!$parametre->est_modifiable) {
            return back()->with('error', 'Ce paramètre ne peut pas être modifié.');
        }

        $validated = $request->validate([
            'valeur' => 'required',
        ]);

        // Traitement selon le type
        $valeur = $validated['valeur'];

        if ($parametre->type === 'boolean') {
            $valeur = $request->has('valeur') && $request->valeur === 'on' ? '1' : '0';
        } elseif ($parametre->type === 'integer') {
            $valeur = (int) $validated['valeur'];
        } elseif ($parametre->type === 'json') {
            $valeur = json_encode($request->input('valeur_json', []));
        }

        $parametre->update(['valeur' => (string) $valeur]);

        // Effacer le cache global des paramètres
        Cache::flush();

        return back()->with('success', 'Paramètre mis à jour avec succès.');
    }

    /**
     * Mettre à jour plusieurs paramètres d'une catégorie
     */
    public function updateCategorie(Request $request, $categorie)
    {
        $parametres = Parametre::where('categorie', $categorie)
            ->where('est_modifiable', true)
            ->get();

        foreach ($parametres as $parametre) {
            $key = str_replace('.', '_', $parametre->cle);

            if ($request->has($key)) {
                $valeur = $request->input($key);

                if ($parametre->type === 'boolean') {
                    $valeur = $valeur === 'on' || $valeur === '1' ? '1' : '0';
                } elseif ($parametre->type === 'integer') {
                    $valeur = (int) $valeur;
                } elseif ($parametre->type === 'json') {
                    $valeur = json_encode($request->input($key . '_json', []));
                }

                $parametre->update(['valeur' => (string) $valeur]);
            }
        }

        // Effacer le cache global des paramètres
        Cache::flush();

        return back()->with('success', 'Paramètres de la catégorie "' . ($parametres->first()?->categorie ?? $categorie) . '" mis à jour avec succès.');
    }

    /**
     * Réinitialiser un paramètre à sa valeur par défaut
     */
    public function reset($id)
    {
        $parametre = Parametre::findOrFail($id);

        // Valeurs par défaut
        $defaults = [
            'app.nom' => 'Bénin Security',
            'app.timezone' => 'Africa/Porto-Novo',
            'app.locale' => 'fr',
            'app.devise' => 'XOF',
            'mail.driver' => 'smtp',
            'mail.port' => '587',
            'mail.encryption' => 'tls',
            'security.password_min_length' => '8',
            'security.session_lifetime' => '120',
            'security.max_login_attempts' => '5',
            'mobile.app_version' => '1.0.0',
        ];

        if (isset($defaults[$parametre->cle])) {
            $parametre->update(['valeur' => $defaults[$parametre->cle]]);
            Cache::forget("parametre_{$parametre->cle}");

            return back()->with('success', 'Paramètre réinitialisé à sa valeur par défaut.');
        }

        return back()->with('error', 'Aucune valeur par défaut définie pour ce paramètre.');
    }

    /**
     * Réinitialiser tous les paramètres d'une catégorie
     */
    public function resetCategorie($categorie)
    {
        // Ici on pourrait implémenter une réinitialisation complète
        return back()->with('info', 'Fonctionnalité de réinitialisation en cours de développement.');
    }

    /**
     * Envoyer un email de test
     */
    public function testEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        try {
            $to = $request->input('email');

            // Configuration temporaire pour le test
            $config = [
                'driver' => Parametre::get('mail.driver', 'smtp'),
                'host' => Parametre::get('mail.host', 'smtp.mailgun.org'),
                'port' => Parametre::get('mail.port', 587),
                'username' => Parametre::get('mail.username'),
                'password' => Parametre::get('mail.password'),
                'encryption' => Parametre::get('mail.encryption', 'tls'),
                'from' => [
                    'address' => Parametre::get('mail.from.address', 'noreply@benin-security.com'),
                    'name' => Parametre::get('mail.from.name', 'Bénin Security'),
                ],
            ];

            // Utiliser le config temporairement
            config(['mail.mailers.smtp' => [
                'transport' => $config['driver'],
                'host' => $config['host'],
                'port' => $config['port'],
                'encryption' => $config['encryption'],
                'username' => $config['username'],
                'password' => $config['password'],
            ]]);

            config(['mail.from' => $config['from']]);

            // Envoyer l'email de test
            \Mail::raw('Ceci est un email de test depuis Benin Security.', function ($message) use ($to) {
                $message->to($to)
                    ->subject('Test de configuration email - Benin Security');
            });

            return back()->with('success', 'Email de test envoyé avec succès à ' . $to);
        } catch (\Exception $e) {
            Log::error('Erreur envoi email test: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de l\'envoi de l\'email de test: ' . $e->getMessage());
        }
    }

    /**
     * Vider le cache
     */
    public function clearCache()
    {
        try {
            // Vider le cache Laravel
            Artisan::call('cache:clear');

            // Vider les configs compilées
            Artisan::call('config:clear');

            // Vider les routes compilées
            Artisan::call('route:clear');

            // Vider les vues compilées
            Artisan::call('view:clear');

            return back()->with('success', 'Cache vidé avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors du vidage du cache: ' . $e->getMessage());
        }
    }

    /**
     * Optimiser l'application
     */
    public function optimize()
    {
        try {
            // Compiler les configs
            Artisan::call('config:cache');

            // Compiler les routes
            Artisan::call('route:cache');

            return back()->with('success', 'Application optimisée avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'optimisation: ' . $e->getMessage());
        }
    }

    /**
     * Voir les logs
     */
    public function logs(Request $request)
    {
        $level = $request->get('level', 'all');
        $lines = $request->get('lines', 100);

        $logFile = storage_path('logs/laravel.log');

        $logs = [];
        if (File::exists($logFile)) {
            $allLines = File::lines($logFile);
            $logs = $allLines->take($lines * 2)->toArray();
            $logs = array_reverse($logs);

            // Filtrer par niveau si nécessaire
            if ($level !== 'all') {
                $logs = array_filter($logs, function ($line) use ($level) {
                    return stripos($line, $level) !== false;
                });
            }
        }

        // Statistiques des logs
        $stats = [
            'total' => count($logs),
            'error' => count(array_filter($logs, fn($l) => stripos($l, 'ERROR') !== false)),
            'warning' => count(array_filter($logs, fn($l) => stripos($l, 'WARNING') !== false)),
            'info' => count(array_filter($logs, fn($l) => stripos($l, 'INFO') !== false)),
        ];

        return view('admin.superadmin.parametres.logs', compact('logs', 'stats', 'level'));
    }

    /**
     * Télécharger les logs
     */
    public function downloadLogs()
    {
        $logFile = storage_path('logs/laravel.log');

        if (!File::exists($logFile)) {
            return back()->with('error', 'Aucun fichier de log trouvé.');
        }

        return response()->download($logFile, 'laravel-' . date('Y-m-d') . '.log');
    }

    /**
     * Purger les logs
     */
    public function purgeLogs()
    {
        $logFile = storage_path('logs/laravel.log');

        if (File::exists($logFile)) {
            File::put($logFile, '');
            return back()->with('success', 'Logs purgés avec succès.');
        }

        return back()->with('error', 'Aucun fichier de log trouvé.');
    }

    /**
     * Mode maintenance - Activer
     */
    public function enableMaintenance(Request $request)
    {
        $message = $request->get('message', 'Le site est en maintenance. Veuillez revenir plus tard.');

        Parametre::set('security.maintenance_mode', true);
        Parametre::set('security.maintenance_message', $message);

        try {
            Artisan::call('down', [
                '--message' => $message,
                '--retry' => 60,
            ]);

            return back()->with('success', 'Mode maintenance activé.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Mode maintenance - Désactiver
     */
    public function disableMaintenance()
    {
        Parametre::set('security.maintenance_mode', false);

        try {
            Artisan::call('up');
            return back()->with('success', 'Mode maintenance désactivé.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Statut du système
     */
    public function status()
    {
        $systemStatus = [
            'php' => phpversion(),
            'laravel' => app()->version(),
            'environment' => app()->environment(),
            'debug' => config('app.debug') ? 'Activé' : 'Désactivé',
            'timezone' => config('app.timezone'),
            'locale' => config('app.locale'),
            'database' => config('database.default'),
            'cache' => config('cache.default'),
            'session' => config('session.driver'),
            'maintenance' => app()->isDownForMaintenance() ? 'Activé' : 'Désactivé',
        ];

        // Statistiques
        $stats = [
            'entreprises' => \App\Models\Entreprise::count(),
            'utilisateurs' => \App\Models\User::count(),
            'employes' => \App\Models\Employe::count(),
            'abonnements' => \App\Models\Abonnement::count(),
            'factures' => \App\Models\Facture::count(),
        ];

        // Dernière sauvegarde
        $backupPath = storage_path('app/backups');
        $lastBackup = null;
        if (File::exists($backupPath)) {
            $files = File::files($backupPath);
            if (!empty($files)) {
                usort($files, fn($a, $b) => File::lastModified($b) - File::lastModified($a));
                $lastBackup = [
                    'nom' => $files[0]->getFilename(),
                    'date' => date('Y-m-d H:i:s', File::lastModified($files[0])),
                    'taille' => round(File::size($files[0]) / 1024 / 1024, 2) . ' MB',
                ];
            }
        }

        return view('admin.superadmin.parametres.status', compact('systemStatus', 'stats', 'lastBackup'));
    }

    /**
     * Créer une sauvegarde
     */
    public function createBackup(Request $request)
    {
        $type = $request->get('type', 'full'); // full, database, files

        try {
            $filename = 'backup-' . $type . '-' . date('Y-m-d-H-i-s') . '.zip';

            // Créer le répertoire si nécessaire
            $backupPath = storage_path('app/backups');
            if (!File::exists($backupPath)) {
                File::makeDirectory($backupPath, 0755, true);
            }

            // Exécuter la sauvegarde
            if ($type === 'database' || $type === 'full') {
                Artisan::call('backup:run', ['--only-db' => true, '--filename' => $filename]);
            }

            if ($type === 'files' || $type === 'full') {
                // Sauvegarde des fichiers
            }

            return back()->with('success', 'Sauvegarde créée avec succès: ' . $filename);
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la sauvegarde: ' . $e->getMessage());
        }
    }

    /**
     * Liste des sauvegardes
     */
    public function backups()
    {
        $backupPath = storage_path('app/backups');

        $backups = [];
        if (File::exists($backupPath)) {
            $files = File::files($backupPath);
            foreach ($files as $file) {
                $backups[] = [
                    'nom' => $file->getFilename(),
                    'date' => date('Y-m-d H:i:s', File::lastModified($file)),
                    'taille' => round(File::size($file) / 1024 / 1024, 2) . ' MB',
                ];
            }

            // Trier par date décroissante
            usort($backups, fn($a, $b) => strtotime($b['date']) - strtotime($a['date']));
        }

        return view('admin.superadmin.parametres.backups', compact('backups'));
    }

    /**
     * Supprimer une sauvegarde
     */
    public function deleteBackup($filename)
    {
        $backupPath = storage_path('app/backups/' . $filename);

        if (File::exists($backupPath)) {
            File::delete($backupPath);
            return back()->with('success', 'Sauvegarde supprimée.');
        }

        return back()->with('error', 'Sauvegarde non trouvée.');
    }

    /**
     * Exporter les paramètres
     */
    public function export()
    {
        $parametres = Parametre::all();

        $data = $parametres->mapWithKeys(function ($item) {
            return [$item->cle => $item->valeur];
        });

        $filename = 'parametres-export-' . date('Y-m-d') . '.json';

        return response()->streamDownload(function () use ($data) {
            echo json_encode($data, JSON_PRETTY_PRINT);
        }, $filename);
    }

    /**
     * Importer les paramètres
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:json',
        ]);

        try {
            $content = file_get_contents($request->file('file'));
            $data = json_decode($content, true);

            foreach ($data as $cle => $valeur) {
                Parametre::set($cle, $valeur);
            }

            Cache::flush();

            return back()->with('success', count($data) . ' paramètres importés avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'import: ' . $e->getMessage());
        }
    }
}
