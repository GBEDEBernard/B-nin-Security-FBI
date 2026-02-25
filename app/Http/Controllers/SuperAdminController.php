<?php

namespace App\Http\Controllers;

use App\Models\Entreprise;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SuperAdminController extends Controller
{
    /**
     * Constructeur - vérifie que l'utilisateur est un superadmin
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check() || !Auth::user()->estSuperAdmin()) {
                return redirect('/login')->with('error', 'Accès refusé. Vous devez être Super Admin.');
            }
            return $next($request);
        });
    }

    /**
     * Dashboard Super Admin
     */
    public function index()
    {
        $entreprises = Entreprise::withCount(['employes', 'clients', 'contratsPrestation'])
            ->orderBy('nom_entreprise')
            ->get();

        $stats = [
            'total_entreprises' => Entreprise::count(),
            'entreprises_actives' => Entreprise::where('est_active', true)->count(),
            'total_utilisateurs' => User::where('is_superadmin', false)->count(),
        ];

        return view('admin.superadmin', compact('entreprises', 'stats'));
    }

    /**
     * Liste des entreprises (vue dédiée)
     */
    public function entreprisesIndex(Request $request)
    {
        $query = Entreprise::withCount(['employes', 'clients', 'contratsPrestation']);

        // Recherche par nom, email ou téléphone
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nom_entreprise', 'like', "%{$search}%")
                    ->orWhere('nom_commercial', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('telephone', 'like', "%{$search}%")
                    ->orWhere('ville', 'like', "%{$search}%");
            });
        }

        // Filtre par statut (actif, inactif, essai)
        if ($request->has('statut') && $request->statut) {
            switch ($request->statut) {
                case 'actif':
                    $query->where('est_active', true);
                    break;
                case 'inactif':
                    $query->where('est_active', false);
                    break;
                case 'essai':
                    $query->where('est_en_essai', true);
                    break;
            }
        }

        // Filtre par formule
        if ($request->has('formule') && $request->formule) {
            $query->where('formule', $request->formule);
        }

        // Tri
        $sortBy = $request->get('sort', 'nom_entreprise');
        $sortOrder = $request->get('order', 'asc');

        // Colonnes autorisées pour le tri
        $allowedSorts = ['nom_entreprise', 'created_at', 'updated_at', 'formule', 'est_active'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder === 'desc' ? 'desc' : 'asc');
        } else {
            $query->orderBy('nom_entreprise');
        }

        $entreprises = $query->paginate(10)->appends($request->query());

        return view('admin.superadmin.entreprises.index', compact('entreprises'));
    }

    /**
     * Créer une entreprise
     */
    public function entreprisesCreate()
    {
        return view('admin.superadmin.entreprises.create');
    }

    /**
     * Voir une entreprise
     */
    public function entreprisesShow($id)
    {
        $entreprise = Entreprise::with(['employes', 'clients', 'contratsPrestation'])->findOrFail($id);
        return view('admin.superadmin.entreprises.show', compact('entreprise'));
    }

    /**
     * Modifier une entreprise
     */
    public function entreprisesEdit($id)
    {
        $entreprise = Entreprise::findOrFail($id);
        return view('admin.superadmin.entreprises.edit', compact('entreprise'));
    }

    /**
     * Liste des utilisateurs globaux
     */
    public function utilisateursIndex()
    {
        $utilisateurs = User::with('entreprise')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.superadmin.utilisateurs.index', compact('utilisateurs'));
    }

    /**
     * Créer un utilisateur
     */
    public function utilisateursCreate()
    {
        $entreprises = Entreprise::orderBy('nom_entreprise')->get();
        return view('admin.superadmin.utilisateurs.create', compact('entreprises'));
    }

    /**
     * Paramètres système
     */
    public function parametresIndex()
    {
        return view('admin.superadmin.parametres.index');
    }

    // ═══════════════════════════════════════════════════════════════════════
    // GESTION DU CHANGEMENT DE CONTEXTE (CONNEXION AUX ENTREPRISES)
    // ═══════════════════════════════════════════════════════════════════════

    /**
     * Liste des entreprises accessibles pour la connexion
     */
    public function listeEntreprises()
    {
        $entreprises = Entreprise::withCount(['employes', 'clients'])
            ->where('est_active', true)
            ->orderBy('nom_entreprise')
            ->get();

        return view('admin.superadmin.entreprises.index', compact('entreprises'));
    }

    /**
     * Se connecter au tableau de bord d'une entreprise
     * 
     * Cette méthode permet au superadmin de se connecter temporairement
     * au tableau de bord d'une entreprise spécifique
     */
    public function switchToEntreprise(Request $request, $entrepriseId)
    {
        // Vérifier que l'utilisateur est un superadmin
        if (!Auth::user()->estSuperAdmin()) {
            return redirect()->back()->with('error', 'Accès refusé.');
        }

        // Récupérer l'entreprise
        $entreprise = Entreprise::findOrFail($entrepriseId);

        // Vérifier que l'entreprise est active
        if (!$entreprise->est_active) {
            return redirect()->back()->with('error', 'Cette entreprise est inactive.');
        }

        // Sauvegarder l'état actuel du superadmin dans la session
        Session::put('superadmin_original', [
            'user_id' => Auth::id(),
            'entreprise_id' => Auth::user()->entreprise_id,
            'is_superadmin' => true,
        ]);

        // Stocker l'ID de l'entreprise temporaire dans la session
        Session::put('superadmin_temp_entreprise_id', $entrepriseId);

        // Stocker l'URL de retour
        Session::put('superadmin_return_url', $request->headers->get('referer'));

        // Message de confirmation
        $request->session()->flash(
            'success',
            'Vous êtes maintenant connecté en tant que Super Admin dans le contexte de l\'entreprise: ' . $entreprise->nom_entreprise
        );

        // Rediriger vers le dashboard de l'entreprise
        // On utilise un flag pour indiquer qu'on est en mode "superadmin dans contexte entreprise"
        return redirect()->route('admin.entreprise.index', ['superadmin_context' => true]);
    }

    /**
     * Retourner au tableau de bord Super Admin
     */
    public function returnToSuperAdmin(Request $request)
    {
        // Vérifier qu'on était bien en contexte superadmin
        if (!Session::has('superadmin_original')) {
            return redirect()->route('admin.superadmin.index')->with('error', 'Aucune session précédente trouvée.');
        }

        // Restaurer les données originales
        Session::forget('superadmin_temp_entreprise_id');

        // Message de confirmation
        $request->session()->flash('info', 'Vous êtes retourné au tableau de bord Super Admin.');

        // Retourner au dashboard superadmin
        return redirect()->route('admin.superadmin.index');
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
