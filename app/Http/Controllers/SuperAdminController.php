<?php

namespace App\Http\Controllers;

use App\Models\Entreprise;
use App\Models\User;
use App\Models\Employe;
use App\Models\Client;
use App\Models\ContratPrestation;
use App\Models\Abonnement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SuperAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::guard('web')->check() || !Auth::guard('web')->user()->estSuperAdmin()) {
                return redirect('/login')->with('error', 'Accès refusé. Vous devez être Super Admin.');
            }
            return $next($request);
        });

        $this->middleware('permission:view_dashboard')->only(['index']);
        $this->middleware('permission:view_tenants')->only([
            'entreprisesIndex', 'entreprisesCreate', 'entreprisesEdit',
            'entreprisesShow', 'switchToEntreprise', 'returnToSuperAdmin',
        ]);
        $this->middleware('permission:view_users')->only([
            'utilisateursIndex', 'utilisateursCreate',
        ]);
        $this->middleware('permission:view_settings')->only(['parametresIndex']);
    }

    public function index()
    {
        $entreprises = Entreprise::withCount(['employes', 'clients', 'contratsPrestation'])
            ->orderBy('nom_entreprise')
            ->get();

        $stats = [
            'total_entreprises'   => Entreprise::count(),
            'entreprises_actives' => Entreprise::where('est_active', true)->count(),
            'total_utilisateurs'  => User::where('is_superadmin', false)->count(),
            'entreprises_essai'   => Entreprise::whereHas('abonnement', fn($q) => $q->where('est_en_essai', true))->count(),
        ];

        return view('admin.superadmin', compact('entreprises', 'stats'));
    }

    public function entreprisesIndex(Request $request)
    {
        $query = Entreprise::withCount(['employes', 'clients', 'contratsPrestation', 'factures', 'incidents']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nom_entreprise', 'like', "%{$search}%")
                    ->orWhere('nom_commercial', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('telephone', 'like', "%{$search}%")
                    ->orWhere('ville', 'like', "%{$search}%");
            });
        }

        if ($request->filled('statut')) {
            match ($request->statut) {
                'actif'   => $query->where('est_active', true),
                'inactif' => $query->where('est_active', false),
                'essai'   => $query->whereHas('abonnement', fn($q) => $q->where('est_en_essai', true)),
                default   => null,
            };
        }

        if ($request->filled('formule')) {
            $query->whereHas('abonnement', fn($q) => $q->where('formule', $request->formule));
        }

        $allowedSorts = ['nom_entreprise', 'created_at', 'updated_at', 'est_active'];
        $sortBy    = in_array($request->get('sort'), $allowedSorts) ? $request->get('sort') : 'nom_entreprise';
        $sortOrder = $request->get('order') === 'desc' ? 'desc' : 'asc';
        $query->orderBy($sortBy, $sortOrder);

        $entreprises = $query->paginate(10)->appends($request->query());

        // Statistiques du tableau de bord
        $stats = [
            'total' => Entreprise::count(),
            'actives' => Entreprise::where('est_active', true)->count(),
            'inactives' => Entreprise::where('est_active', false)->count(),
            'essai' => Entreprise::whereHas('abonnement', fn($q) => $q->where('est_en_essai', true))->count(),
            'total_employes' => Employe::count(),
            'total_clients' => Client::count(),
            'total_contrats' => ContratPrestation::count(),
            'revenu_mensuel' => Entreprise::where('est_active', true)
                ->with('abonnement')->get()
                ->sum(fn($e) => $e->montant_mensuel ?? 0),
            'expired' => Entreprise::whereHas('abonnement', fn($q) => $q
                ->where('statut', 'expire')
                ->orWhere('date_fin', '<', now())
            )->count(),
        ];

        // Répartition par formule
        $formules = Abonnement::selectRaw('formule, count(*) as total')
            ->groupBy('formule')->pluck('total', 'formule')->toArray();
        $chartFormules = [
            'labels' => array_keys($formules),
            'data' => array_values($formules),
        ];

        // Créations par mois (12 derniers mois)
        $monthly = Entreprise::selectRaw("strftime('%m', created_at) as mois, strftime('%Y', created_at) as annee, count(*) as total")
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('annee', 'mois')
            ->orderBy('annee')->orderBy('mois')
            ->get()->keyBy(fn($m) => $m->annee . '-' . $m->mois);

        $chartMonthly = ['labels' => [], 'data' => []];
        for ($i = 11; $i >= 0; $i--) {
            $d = now()->subMonths($i);
            $key = $d->format('Y-m');
            $chartMonthly['labels'][] = $d->format('M Y');
            $chartMonthly['data'][] = $monthly->get($key)?->total ?? 0;
        }

        // Top 10 entreprises par employés
        $topEntreprises = Entreprise::withCount('employes')
            ->where('est_active', true)
            ->orderByDesc('employes_count')
            ->limit(10)
            ->get();
        $chartTop = [
            'labels' => $topEntreprises->pluck('nom_entreprise')->toArray(),
            'data' => $topEntreprises->pluck('employes_count')->toArray(),
        ];

        // Répartition par ville
        $villes = Entreprise::selectRaw('ville, count(*) as total')
            ->whereNotNull('ville')->where('ville', '!=', '')
            ->groupBy('ville')->orderByDesc('total')->limit(8)
            ->pluck('total', 'ville')->toArray();
        $chartVilles = [
            'labels' => array_keys($villes),
            'data' => array_values($villes),
        ];

        return view('admin.superadmin.entreprises.index', compact(
            'entreprises', 'stats',
            'chartFormules', 'chartMonthly', 'chartTop', 'chartVilles'
        ));
    }

    public function entreprisesCreate()
    {
        return view('admin.superadmin.entreprises.create');
    }
    public function entreprisesEdit($id)
    {
        return view('admin.superadmin.entreprises.edit', ['entreprise' => Entreprise::findOrFail($id)]);
    }

    public function entreprisesShow($id)
    {
        $entreprise = Entreprise::withCount(['employes', 'clients', 'contratsPrestation'])
            ->with(['employes', 'clients', 'contratsPrestation'])
            ->findOrFail($id);
        return view('admin.superadmin.entreprises.show', compact('entreprise'));
    }

    public function utilisateursIndex()
    {
        $utilisateurs = User::with('entreprise')->orderBy('name')->paginate(15);
        return view('admin.superadmin.utilisateurs.index', compact('utilisateurs'));
    }

    public function utilisateursCreate()
    {
        $entreprises = Entreprise::orderBy('nom_entreprise')->get();
        return view('admin.superadmin.utilisateurs.create', compact('entreprises'));
    }

    public function parametresIndex()
    {
        return view('admin.superadmin.parametres.index');
    }

    public function switchToEntreprise(Request $request, $entrepriseId)
    {
        $entreprise = Entreprise::findOrFail($entrepriseId);

        if (!$entreprise->est_active) {
            return redirect()->back()->with('error', 'Cette entreprise est inactive.');
        }

        // Stocker les infos originales du superadmin
        Session::put('superadmin_original', ['user_id' => Auth::id(), 'is_superadmin' => true]);

        // Stocker l'ID de l'entreprise en contexte (clé utilisée par le modèle User)
        Session::put('entreprise_id', $entrepriseId);

        // Garder une référence pour le retour
        Session::put('superadmin_return_url', route('admin.superadmin.index'));

        $request->session()->flash('success', 'Connecté en contexte : ' . $entreprise->nom_entreprise);
        return redirect()->route('admin.entreprise.index');
    }

    public function returnToSuperAdmin(Request $request)
    {
        // Nettoyer toutes les sessions liées au contexte entreprise
        Session::forget(['entreprise_id', 'superadmin_original', 'superadmin_return_url', 'superadmin_temp_entreprise_id']);

        return redirect()->route('admin.superadmin.index')->with('info', 'Retour au Super Admin.');
    }

    /**
     * Vérifier si le superadmin est en contexte d'entreprise
     */
    public function isInContext()
    {
        return Session::has('superadmin_temp_entreprise_id');
    }

    /**
     * Obtenir l'entreprise temporaire (si en contexte)
     */
    public function getTempEntreprise()
    {
        $entrepriseId = Session::get('superadmin_temp_entreprise_id');

        if ($entrepriseId) {
            return Entreprise::find($entrepriseId);
        }

        return null;
    }
}
