<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Employe;
use App\Models\Client;
use App\Models\Entreprise;
use App\Notifications\PushNotification;
use App\Services\OneSignalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'superadmin']);
    }

    public function index(Request $request)
    {
        $notifications = \App\Models\Notification::query()
            ->where('notifiable_type', User::class)
            ->orderByDesc('created_at')
            ->paginate(20);

        $stats = [
            'total' => \App\Models\Notification::count(),
            'envoyees' => \App\Models\Notification::count(),
            'today' => \App\Models\Notification::whereDate('created_at', today())->count(),
        ];

        return view('admin.superadmin.notifications.index', compact('notifications', 'stats'));
    }

    public function create()
    {
        $entreprises = Entreprise::orderBy('nom_entreprise')->get();
        $roles = ['direction', 'superviseur', 'controleur', 'agent', 'client'];

        return view('admin.superadmin.notifications.create', compact('entreprises', 'roles'));
    }

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

        $notifiables = collect();

        if ($validated['type_envoi'] === 'all') {
            $notifiables = User::where('is_active', true)->get()
                ->merge(Employe::where('est_actif', true)->get())
                ->merge(Client::where('est_actif', true)->get());
        } elseif ($validated['type_envoi'] === 'entreprise' && !empty($validated['entreprise_id'])) {
            $notifiables = User::where('is_active', true)
                ->where('entreprise_id', $validated['entreprise_id'])->get()
                ->merge(
                    Employe::where('est_actif', true)
                        ->where('entreprise_id', $validated['entreprise_id'])->get()
                )
                ->merge(
                    Client::where('est_actif', true)
                        ->where('entreprise_id', $validated['entreprise_id'])->get()
                );
        } elseif ($validated['type_envoi'] === 'role' && !empty($validated['role'])) {
            $notifiables = User::where('is_active', true)
                ->whereHas('roles', function ($q) use ($validated) {
                    $q->where('name', $validated['role']);
                })->get()
                ->merge(
                    Employe::where('est_actif', true)
                        ->where('categorie', $validated['role'])->get()
                );
        }

        $pushNotification = new PushNotification(
            title: $validated['titre'],
            message: $validated['message'],
            type: $validated['type'],
            url: $validated['url'] ?? null,
            data: ['type_envoi' => $validated['type_envoi']],
        );

        $oneSignal = app(OneSignalService::class);
        if ($oneSignal->isConfigured()) {
            $playerIds = $notifiables->map(fn ($n) => $n->routeNotificationForOneSignal())->flatten()->filter()->unique()->toArray();

            if (!empty($playerIds)) {
                $result = $oneSignal->sendToUsers(
                    playerIds: $playerIds,
                    title: $validated['titre'],
                    message: $validated['message'],
                    data: [
                        'url' => $validated['url'],
                        'type' => $validated['type'],
                        'type_envoi' => $validated['type_envoi'],
                    ],
                );

                if (!$result['success']) {
                    Log::warning('Échec envoi OneSignal', $result);
                }
            }
        }

        foreach ($notifiables as $notifiable) {
            $notifiable->notifications()->create([
                'type' => PushNotification::class,
                'donnees' => json_encode([
                    'titre' => $validated['titre'],
                    'message' => $validated['message'],
                    'type' => $validated['type'],
                    'url' => $validated['url'],
                ]),
            ]);
        }

        Log::info('Notification push envoyée', [
            'titre' => $validated['titre'],
            'destinataires' => $notifiables->count(),
            'type' => $validated['type'],
            'type_envoi' => $validated['type_envoi'],
        ]);

        return redirect()->route('admin.superadmin.notifications.index')
            ->with('success', 'Notification envoyée à ' . $notifiables->count() . ' destinataire(s).');
    }

    /**
     * Voir une notification
     */
    public function show($id)
    {
        $notification = \App\Models\Notification::findOrFail($id);

        $donnees = json_decode($notification->donnees, true) ?? [];

        return view('admin.superadmin.notifications.show', compact('notification', 'donnees'));
    }

    /**
     * Supprimer une notification
     */
    public function destroy($id)
    {
        $notification = \App\Models\Notification::findOrFail($id);
        $notification->delete();

        return redirect()->route('admin.superadmin.notifications.index')
            ->with('success', 'Notification supprimée.');
    }

    /**
     * Statistiques des notifications
     */
    public function statistiques()
    {
        $stats = [
            'total_envoyees' => \App\Models\Notification::count(),
            'aujourdhui' => \App\Models\Notification::whereDate('created_at', today())->count(),
            'this_week' => \App\Models\Notification::where('created_at', '>=', now()->startOfWeek())->count(),
            'this_month' => \App\Models\Notification::where('created_at', '>=', now()->startOfMonth())->count(),
            'taux_lecture' => \App\Models\Notification::whereNotNull('lu_le')->count() > 0
                ? round((\App\Models\Notification::whereNotNull('lu_le')->count() / \App\Models\Notification::count()) * 100)
                : 0,
            'par_type' => [
                'info' => \App\Models\Notification::where('type', 'info')->count(),
                'success' => \App\Models\Notification::where('type', 'success')->count(),
                'warning' => \App\Models\Notification::where('type', 'warning')->count(),
                'error' => \App\Models\Notification::where('type', 'error')->count(),
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
     * Envoyer un test (email + push)
     */
    public function test(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:info,success,warning,error',
            'email' => 'required|email',
        ]);

        $oneSignal = app(\App\Services\OneSignalService::class);
        if ($oneSignal->isConfigured()) {
            $oneSignal->sendToAll(
                title: $validated['titre'],
                message: $validated['message'],
                data: ['type' => $validated['type'], 'test' => true],
            );
        }

        return back()->with('success', 'Notification de test envoyée.');
    }
}
