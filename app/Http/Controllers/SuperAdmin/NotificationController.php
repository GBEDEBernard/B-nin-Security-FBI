<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Employe;
use App\Models\Client;
use App\Models\Entreprise;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:web', 'superadmin']);
    }

    // ─────────────────────────────────────────────────────────────────
    // INDEX — liste de toutes les notifications
    // ─────────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Notification::with(['entreprise'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        if ($request->filled('entreprise_id')) {
            $query->where('entreprise_id', $request->entreprise_id);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $notifications = $query->paginate(15);
        $entreprises   = Entreprise::orderBy('nom_entreprise')->get();

        $stats = [
            'total'     => Notification::count(),
            'envoyees'  => Notification::where('statut', 'envoyee')->count(),
            'echouees'  => Notification::where('statut', 'echouee')->count(),
            'today'     => Notification::whereDate('created_at', today())->count(),
            'non_lues'  => Notification::whereNull('lu_le')->count(),
        ];

        return view('admin.superadmin.notifications.index',
            compact('notifications', 'entreprises', 'stats'));
    }

    // ─────────────────────────────────────────────────────────────────
    // CREATE — formulaire d'envoi
    // ─────────────────────────────────────────────────────────────────
    public function create()
    {
        $entreprises = Entreprise::where('est_active', true)
            ->orderBy('nom_entreprise')
            ->get();

        $roles = ['superadmin', 'admin', 'direction', 'superviseur', 'controleur', 'agent', 'client'];

        return view('admin.superadmin.notifications.create',
            compact('entreprises', 'roles'));
    }

    // ─────────────────────────────────────────────────────────────────
    // STORE — enregistrer et envoyer
    // ─────────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre'            => 'required|string|max:255',
            'message'          => 'required|string',
            'type'             => 'required|in:info,success,warning,error',
            'type_envoi'       => 'required|in:all,entreprise,role,utilisateur',
            'entreprise_id'    => 'nullable|exists:entreprises,id',
            'role'             => 'nullable|string',
            'utilisateur_ids'  => 'nullable|array',
            'utilisateur_ids.*'=> 'integer',
            'url'              => 'nullable|string|max:500',
        ]);

        $envoyeur          = Auth::user();
        $destinataires     = collect();
        $destinatairesType = '';

        switch ($validated['type_envoi']) {

            case 'all':
                $destinataires     = User::where('is_active', true)->get();
                $destinatairesType = 'Tous les utilisateurs';
                break;

            case 'entreprise':
                if (!empty($validated['entreprise_id'])) {
                    $entreprise = Entreprise::find($validated['entreprise_id']);

                    // Employés de l'entreprise
                    $employes = Employe::where('entreprise_id', $validated['entreprise_id'])
                        ->where('est_actif', true)->get();

                    // Clients de l'entreprise
                    $clients = Client::where('entreprise_id', $validated['entreprise_id'])
                        ->where('est_actif', true)->get();

                    $destinataires     = $employes->concat($clients);
                    $destinatairesType = $entreprise->nom_entreprise ?? 'Entreprise';
                }
                break;

            case 'role':
                if (!empty($validated['role'])) {
                    $users = User::whereHas('roles', function ($q) use ($validated) {
                        $q->where('name', $validated['role']);
                    })->where('is_active', true)->get();

                    $destinataires     = $users;
                    $destinatairesType = 'Rôle : ' . $validated['role'];
                }
                break;

            case 'utilisateur':
                if (!empty($validated['utilisateur_ids'])) {
                    $destinataires = User::whereIn('id', $validated['utilisateur_ids'])
                        ->where('is_active', true)->get();
                    $destinatairesType = count($validated['utilisateur_ids']) . ' utilisateur(s)';
                }
                break;
        }

        // ── Créer une notification par destinataire ──────────────────
        $created = 0;
        foreach ($destinataires as $destinataire) {
            Notification::create([
                'notifiable_type' => get_class($destinataire),   // ex: App\Models\Employe
                'notifiable_id'   => $destinataire->id,
                'type'            => $validated['type'],
                'titre'           => $validated['titre'],
                'message'         => $validated['message'],
                'statut'          => 'envoyee',
                'url'             => $validated['url'] ?? null,
                'entreprise_id'   => $validated['entreprise_id'] ?? null,
                'envoyeur_id'     => $envoyeur->id,
                // envoyeur_type n'est pas dans $fillable → on l'ajoute via donnees
                'donnees'         => [
                    'envoyeur_type' => get_class($envoyeur),
                    'envoyeur_nom'  => $envoyeur->name,
                ],
            ]);
            $created++;
        }

        // Si aucun destinataire trouvé, on crée quand même une entrée globale
        if ($created === 0) {
            Notification::create([
                'notifiable_type' => get_class($envoyeur),
                'notifiable_id'   => $envoyeur->id,
                'type'            => $validated['type'],
                'titre'           => $validated['titre'],
                'message'         => $validated['message'],
                'statut'          => 'envoyee',
                'url'             => $validated['url'] ?? null,
                'entreprise_id'   => $validated['entreprise_id'] ?? null,
                'envoyeur_id'     => $envoyeur->id,
                'donnees'         => ['envoyeur_type' => get_class($envoyeur)],
            ]);
            $created = 1;
        }

        Log::info('Notification envoyée', [
            'titre'        => $validated['titre'],
            'destinataires'=> $created,
            'type'         => $validated['type'],
            'envoyeur'     => $envoyeur->name,
        ]);

        return redirect()->route('admin.superadmin.notifications.index')
            ->with('success', "Notification envoyée à {$created} destinataire(s) — {$destinatairesType}");
    }

    // ─────────────────────────────────────────────────────────────────
    // SHOW
    // ─────────────────────────────────────────────────────────────────
    public function show($id)
    {
        $notification = Notification::with(['entreprise'])->findOrFail($id);

        return view('admin.superadmin.notifications.show', compact('notification'));
    }

    // ─────────────────────────────────────────────────────────────────
    // DESTROY
    // ─────────────────────────────────────────────────────────────────
    public function destroy($id)
    {
        Notification::findOrFail($id)->delete();

        return redirect()->route('admin.superadmin.notifications.index')
            ->with('success', 'Notification supprimée.');
    }

    public function destroyMultiple(Request $request)
    {
        $request->validate([
            'notifications'   => 'required|array',
            'notifications.*' => 'exists:notifications,id',
        ]);

        Notification::whereIn('id', $request->notifications)->delete();

        return redirect()->route('admin.superadmin.notifications.index')
            ->with('success', count($request->notifications) . ' notification(s) supprimée(s).');
    }

    // ─────────────────────────────────────────────────────────────────
    // MARQUER LU
    // ─────────────────────────────────────────────────────────────────
    public function markAsRead(Request $request, $id)
    {
        $notification = Notification::findOrFail($id);
        $notification->marquerLue();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'notification_id' => $id]);
        }

        return back()->with('success', 'Notification marquée comme lue.');
    }

    public function markAllAsRead(Request $request)
    {
        Notification::whereNull('lu_le')->update(['lu_le' => now()]);

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }

    // ─────────────────────────────────────────────────────────────────
    // STATISTIQUES
    // ─────────────────────────────────────────────────────────────────
    public function statistiques()
    {
        $stats = [
            'total'          => Notification::count(),
            'total_envoyees' => Notification::where('statut', 'envoyee')->count(),
            'aujourdhui'     => Notification::whereDate('created_at', today())->count(),
            'this_week'      => Notification::whereBetween('created_at', [
                                    now()->startOfWeek(), now()->endOfWeek()
                                ])->count(),
            'this_month'     => Notification::whereMonth('created_at', now()->month)
                                    ->whereYear('created_at', now()->year)->count(),
            'non_lues'       => Notification::whereNull('lu_le')->count(),
            'par_type'       => [
                'info'    => Notification::where('type', 'info')->count(),
                'success' => Notification::where('type', 'success')->count(),
                'warning' => Notification::where('type', 'warning')->count(),
                'error'   => Notification::where('type', 'error')->count(),
            ],
            'par_entreprise' => Notification::selectRaw('entreprise_id, COUNT(*) as total')
                                    ->groupBy('entreprise_id')
                                    ->get(),
        ];

        return view('admin.superadmin.notifications.statistiques', compact('stats'));
    }

    // ─────────────────────────────────────────────────────────────────
    // API — header notifications non lues
    // ─────────────────────────────────────────────────────────────────
    public function apiNonLues(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['notifications' => [], 'count' => 0]);
        }

        $notifications = Notification::where('notifiable_type', get_class($user))
            ->where('notifiable_id', $user->id)
            ->whereNull('lu_le')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'notifications' => $notifications,
            'count'         => $notifications->count(),
        ]);
    }

    public function apiMarkAsRead(Request $request, $id)
    {
        Notification::findOrFail($id)->marquerLue();

        return response()->json(['success' => true]);
    }

    public function apiMarkAllAsRead(Request $request)
    {
        $user = Auth::user();

        Notification::where('notifiable_type', get_class($user))
            ->where('notifiable_id', $user->id)
            ->whereNull('lu_le')
            ->update(['lu_le' => now()]);

        return response()->json(['success' => true]);
    }

    // ─────────────────────────────────────────────────────────────────
    // API — utilisateurs pour la sélection
    // ─────────────────────────────────────────────────────────────────
    public function apiUtilisateurs(Request $request)
    {
        $query = User::where('is_active', true);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        return response()->json(
            $query->limit(20)->get(['id', 'name', 'email'])
        );
    }
}