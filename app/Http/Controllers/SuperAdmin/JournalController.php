<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use App\Models\Employe;
use Illuminate\Http\Request;

class JournalController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'superadmin']);

        $this->middleware('permission:view_audit_logs')->only([
            'index', 'show', 'parUtilisateur', 'parModule', 'export', 'statistiques',
        ]);

        $this->middleware('permission:edit_settings')->only([
            'purge',
        ]);
    }

    public function index(Request $request)
    {
        $query = ActivityLog::recent();

        if ($request->filled('user_id')) {
            $query->where('causer_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->where('log_name', $request->action);
        }

        if ($request->filled('module')) {
            $query->where('subject_type', $request->module);
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }

        $activites = $query->paginate(30)->withQueryString();

        $stats = [
            'connexions_today' => ActivityLog::whereDate('created_at', today())
                ->where('log_name', 'connexion')->count(),
            'creations_today' => ActivityLog::whereDate('created_at', today())
                ->where('log_name', 'creation')->count(),
            'modifications_today' => ActivityLog::whereDate('created_at', today())
                ->where('log_name', 'modification')->count(),
            'errors_today' => ActivityLog::whereDate('created_at', today())
                ->where('log_name', 'erreur')->count(),
        ];

        $utilisateurs = User::select('id', 'name', 'email')
            ->orderBy('name')->get();
        $modules = ActivityLog::whereNotNull('subject_type')
            ->selectRaw('DISTINCT subject_type')
            ->pluck('subject_type')
            ->map(fn ($type) => [
                'value' => $type,
                'label' => class_basename($type),
            ]);

        return view('admin.superadmin.journal.index', compact('activites', 'stats', 'utilisateurs', 'modules'));
    }

    public function show($id)
    {
        $activite = ActivityLog::findOrFail($id);
        return view('admin.superadmin.journal.show', compact('activite'));
    }

    public function parUtilisateur(Request $request)
    {
        $userId = $request->get('user_id');
        $utilisateur = User::find($userId);

        $activites = ActivityLog::where('causer_id', $userId)
            ->recent()->paginate(30);

        return view('admin.superadmin.journal.par-utilisateur', compact('activites', 'utilisateur', 'userId'));
    }

    public function parModule(Request $request)
    {
        $moduleType = $request->get('module');
        $moduleLabel = $moduleType ? class_basename($moduleType) : null;

        $activites = ActivityLog::where('subject_type', $moduleType)
            ->recent()->paginate(30);

        return view('admin.superadmin.journal.par-module', compact('activites', 'moduleType', 'moduleLabel'));
    }

    public function export(Request $request)
    {
        $query = ActivityLog::recent();

        if ($request->filled('date_debut')) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }

        $rows = $query->get();

        $filename = 'journal-activite-' . now()->format('Y-m-d-Hi') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($rows) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, ['Date/Heure', 'Utilisateur', 'Action', 'Module', 'Description', 'IP', 'Détails']);

            foreach ($rows as $row) {
                fputcsv($handle, [
                    $row->created_at?->format('d/m/Y H:i:s'),
                    $row->causer_name,
                    $row->action_label,
                    $row->module_label,
                    $row->description,
                    $row->ip_address ?? '—',
                    $row->properties ? json_encode($row->properties) : '',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function statistiques()
    {
        $semaine = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $total = ActivityLog::whereDate('created_at', $date)->count();
            $connexions = ActivityLog::whereDate('created_at', $date)
                ->where('log_name', 'connexion')->count();
            $semaine[] = [
                'date' => $date->format('d/m'),
                'total' => $total,
                'connexions' => $connexions,
                'creations' => ActivityLog::whereDate('created_at', $date)
                    ->where('log_name', 'creation')->count(),
                'modifications' => ActivityLog::whereDate('created_at', $date)
                    ->where('log_name', 'modification')->count(),
            ];
        }

        $topUsers = ActivityLog::selectRaw('causer_id, count(*) as total')
            ->whereNotNull('causer_id')
            ->groupBy('causer_id')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                $user = User::find($item->causer_id);
                $item->user_name = $user?->name ?? $user?->email ?? "ID: {$item->causer_id}";
                return $item;
            });

        $stats = [
            'total_7_jours' => ActivityLog::where('created_at', '>=', now()->subDays(7))->count(),
            'connexions_7_jours' => ActivityLog::where('created_at', '>=', now()->subDays(7))
                ->where('log_name', 'connexion')->count(),
            'creations_7_jours' => ActivityLog::where('created_at', '>=', now()->subDays(7))
                ->where('log_name', 'creation')->count(),
            'modifications_7_jours' => ActivityLog::where('created_at', '>=', now()->subDays(7))
                ->where('log_name', 'modification')->count(),
        ];

        return view('admin.superadmin.journal.statistiques', compact('semaine', 'topUsers', 'stats'));
    }

    public function purge(Request $request)
    {
        $validated = $request->validate([
            'jours' => 'required|integer|min:1|max:365',
        ]);

        $count = ActivityLog::where('created_at', '<', now()->subDays($validated['jours']))
            ->delete();

        return redirect()->route('admin.superadmin.journal.index')
            ->with('success', "Journal purgé : {$count} entrée(s) supprimée(s).");
    }
}
