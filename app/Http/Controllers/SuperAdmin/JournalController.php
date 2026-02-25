<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class JournalController extends Controller
{
    /**
     * Constructeur
     */
    public function __construct()
    {
        $this->middleware(['auth', 'superadmin']);
    }

    /**
     * Liste des activités
     */
    public function index(Request $request)
    {
        $query = DB::table('activity_log')->orderBy('created_at', 'desc');

        // Filtres
        if ($request->filled('user_id')) {
            $query->where('causer_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->where('description', 'like', '%' . $request->action . '%');
        }

        if ($request->filled('module')) {
            $query->where('subject_type', 'like', '%' . $request->module . '%');
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }

        $activites = $query->paginate(30);

        // Statistiques
        $stats = [
            'connexions_today' => DB::table('activity_log')
                ->whereDate('created_at', today())
                ->where('description', 'like', '%connexion%')
                ->count(),
            'creations_today' => DB::table('activity_log')
                ->whereDate('created_at', today())
                ->where('description', 'like', '%created%')
                ->count(),
            'modifications_today' => DB::table('activity_log')
                ->whereDate('created_at', today())
                ->where('description', 'like', '%updated%')
                ->count(),
            'errors_today' => DB::table('activity_log')
                ->whereDate('created_at', today())
                ->where('description', 'like', '%error%')
                ->count(),
        ];

        return view('admin.superadmin.journal.index', compact('activites', 'stats'));
    }

    /**
     * Voir les détails d'une activité
     */
    public function show($id)
    {
        $activite = DB::table('activity_log')->find($id);

        return view('admin.superadmin.journal.show', compact('activite'));
    }

    /**
     * Activités par utilisateur
     */
    public function parUtilisateur(Request $request)
    {
        $userId = $request->get('user_id');

        $activites = DB::table('activity_log')
            ->where('causer_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(30);

        return view('admin.superadmin.journal.par-utilisateur', compact('activites', 'userId'));
    }

    /**
     * Activités par module
     */
    public function parModule(Request $request)
    {
        $module = $request->get('module');

        $activites = DB::table('activity_log')
            ->where('subject_type', 'like', '%' . $module . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(30);

        return view('admin.superadmin.journal.par-module', compact('activites', 'module'));
    }

    /**
     * Exporter le journal
     */
    public function export(Request $request)
    {
        $query = DB::table('activity_log')->orderBy('created_at', 'desc');

        if ($request->filled('date_debut')) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }

        $activites = $query->get();

        // Logique d'export CSV/Excel
        return back()->with('info', 'Export en cours de développement.');
    }

    /**
     * Statistiques du journal
     */
    public function statistiques()
    {
        // Activités des 7 derniers jours
        $semaine = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $semaine[] = [
                'date' => $date->format('d/m'),
                'connexions' => DB::table('activity_log')
                    ->whereDate('created_at', $date)
                    ->where('description', 'like', '%connexion%')
                    ->count(),
                'actions' => DB::table('activity_log')
                    ->whereDate('created_at', $date)
                    ->count(),
            ];
        }

        // Top utilisateurs
        $topUsers = DB::table('activity_log')
            ->select('causer_id', DB::raw('count(*) as total'))
            ->whereNotNull('causer_id')
            ->groupBy('causer_id')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $stats = [
            'total_7_jours' => DB::table('activity_log')
                ->where('created_at', '>=', now()->subDays(7))
                ->count(),
            'connexions_7_jours' => DB::table('activity_log')
                ->where('created_at', '>=', now()->subDays(7))
                ->where('description', 'like', '%connexion%')
                ->count(),
        ];

        return view('admin.superadmin.journal.statistiques', compact('semaine', 'topUsers', 'stats'));
    }

    /**
     * Purger le journal
     */
    public function purge(Request $request)
    {
        $validated = $request->validate([
            'jours' => 'required|integer|min:1|max:365',
        ]);

        $dateLimite = now()->subDays($validated['jours']);

        DB::table('activity_log')
            ->where('created_at', '<', $dateLimite)
            ->delete();

        return redirect()->route('admin.superadmin.journal.index')
            ->with('success', 'Journal purgé avec succès.');
    }
}
