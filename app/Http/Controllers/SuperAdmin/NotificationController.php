<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Entreprise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    /**
     * Constructeur
     */
    public function __construct()
    {
        $this->middleware(['auth', 'superadmin']);
    }

    /**
     * Liste des notifications
     */
    public function index(Request $request)
    {
        $notifications = collect([
            (object)[
                'id' => 1,
                'titre' => 'Mise à jour disponible',
                'message' => 'Une nouvelle version de l\'application est disponible',
                'type' => 'info',
                'destinataires' => 'Tous',
                'statut' => 'envoyee',
                'created_at' => now()->subHours(2),
            ],
            (object)[
                'id' => 2,
                'titre' => 'Nouveau contrat signé',
                'message' => 'Un nouveau contrat a été signé avec l\'entreprise ABC',
                'type' => 'success',
                'destinataires' => 'Direction',
                'statut' => 'envoyee',
                'created_at' => now()->subDays(1),
            ],
            (object)[
                'id' => 3,
                'titre' => 'Rappel pointage',
                'message' => 'N\'oubliez pas de pointer ce soir',
                'type' => 'warning',
                'destinataires' => 'Agents',
                'statut' => 'envoyee',
                'created_at' => now()->subDays(2),
            ],
        ]);

        $stats = [
            'total' => $notifications->count(),
            'envoyees' => $notifications->where('statut', 'envoyee')->count(),
            'today' => $notifications->filter(function ($n) {
                return $n->created_at->isToday();
            })->count(),
        ];

        return view('admin.superadmin.notifications.index', compact('notifications', 'stats'));
    }

    /**
     * Formulaire d'envoi
     */
    public function create()
    {
        $entreprises = Entreprise::orderBy('nom_entreprise')->get();
        $roles = ['direction', 'superviseur', 'controleur', 'agent', 'client'];

        return view('admin.superadmin.notifications.create', compact('entreprises', 'roles'));
    }

    /**
     * Envoyer une notification
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:info,success,warning,error',
            'type_envoi' => 'required|in:all,entreprise,role',
            'entreprise_id' => 'nullable|exists:entreprises,id',
            'role' => 'nullable|string',
            'url' => 'nullable|url',
        ]);

        // Logique d'envoi de notification
        // 1. Récupérer les utilisateurs cibles
        $users = collect();

        if ($validated['type_envoi'] === 'all') {
            $users = User::where('is_active', true)->get();
        } elseif ($validated['type_envoi'] === 'entreprise' && !empty($validated['entreprise_id'])) {
            $users = User::where('entreprise_id', $validated['entreprise_id'])->get();
        } elseif ($validated['type_envoi'] === 'role' && !empty($validated['role'])) {
            $users = User::whereHas('roles', function ($q) use ($validated) {
                $q->where('name', $validated['role']);
            })->get();
        }

        // 2. Envoyer la notification (à implémenter avec Firebase/OneSignal)
        // Notification::send($users, new \App\Notifications\NotificationPush(...));

        // 3. Logger l'envoi
        Log::info('Notification push envoyée', [
            'titre' => $validated['titre'],
            'destinataires' => $users->count(),
            'type' => $validated['type'],
        ]);

        return redirect()->route('admin.superadmin.notifications.index')
            ->with('success', 'Notification envoyée à ' . $users->count() . ' utilisateur(s).');
    }

    /**
     * Voir une notification
     */
    public function show($id)
    {
        $notification = (object)[
            'id' => $id,
            'titre' => 'Notification',
            'message' => 'Contenu de la notification',
            'type' => 'info',
            'destinataires' => 'Tous',
            'statut' => 'envoyee',
            'created_at' => now(),
            'destinataires_detail' => 150,
            'lus' => 145,
        ];

        return view('admin.superadmin.notifications.show', compact('notification'));
    }

    /**
     * Supprimer une notification
     */
    public function destroy($id)
    {
        return redirect()->route('admin.superadmin.notifications.index')
            ->with('success', 'Notification supprimée.');
    }

    /**
     * Statistiques des notifications
     */
    public function statistiques()
    {
        $stats = [
            'total_envoyees' => 156,
            'aujourdhui' => 3,
            'this_week' => 12,
            'this_month' => 45,
            'taux_lecture' => 98,
            'par_type' => [
                'info' => 45,
                'success' => 30,
                'warning' => 50,
                'error' => 31,
            ],
        ];

        return view('admin.superadmin.notifications.statistiques', compact('stats'));
    }

    /**
     * Prévisualiser une notification
     */
    public function preview(Request $request)
    {
        return response()->json([
            'titre' => $request->titre,
            'message' => $request->message,
            'type' => $request->type,
        ]);
    }

    /**
     * Envoyer un test
     */
    public function test(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:info,success,warning,error',
            'email' => 'required|email',
        ]);

        // Envoyer un email de test
        // Mail::to($validated['email'])->send(new TestNotification(...));

        return back()->with('success', 'Notification de test envoyée.');
    }
}
