<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Employe;
use App\Models\Conge;
use App\Models\SoldeConge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CongeController extends Controller
{
    /**
     * Constructeur
     */
    public function __construct()
    {
        $this->middleware(['auth', 'entreprise']);
    }

    /**
     * Liste des congés
     */
    public function index(Request $request)
    {
        $employe = Employe::where('user_id', Auth::id())->first();

        if (!$employe) {
            return redirect()->route('home')->with('error', 'Profil employé non trouvé.');
        }

        $query = Conge::where('employe_id', $employe->id);

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $conges = $query->orderByDesc('date_debut')->paginate(15);

        // Soldes de congés
        $soldes = SoldeConge::where('employe_id', $employe->id)->get();

        $stats = [
            'total' => Conge::where('employe_id', $employe->id)->count(),
            'en_attente' => Conge::where('employe_id', $employe->id)->where('statut', 'en_attente')->count(),
            'approuves' => Conge::where('employe_id', $employe->id)->where('statut', 'approuve')->count(),
            'rejetes' => Conge::where('employe_id', $employe->id)->where('statut', 'rejete')->count(),
        ];

        return view('admin.agent.conges.index', compact('conges', 'stats', 'employe', 'soldes'));
    }

    /**
     * Demander un congés
     */
    public function store(Request $request)
    {
        $employe = Employe::where('user_id', Auth::id())->first();

        if (!$employe) {
            return back()->with('error', 'Profil employé non trouvé.');
        }

        $validated = $request->validate([
            'type_conge' => 'required|in:annuel,maladie,maternite,paternite,sans_solde,exceptionnel',
            'date_debut' => 'required|date|after:today',
            'date_fin' => 'required|date|after:date_debut',
            'nombre_jours' => 'required|integer|min:1',
            'motif' => 'required|string',
            'remplacement' => 'nullable|string',
        ]);

        $validated['employe_id'] = $employe->id;
        $validated['entreprise_id'] = $employe->entreprise_id;
        $validated['statut'] = 'en_attente';

        Conge::create($validated);

        return redirect()->route('admin.agent.conges.index')
            ->with('success', 'Demande de congés envoyée.');
    }

    /**
     * Voir un congés
     */
    public function show($id)
    {
        $employe = Employe::where('user_id', Auth::id())->first();

        $conge = Conge::where('employe_id', $employe->id)
            ->findOrFail($id);

        return view('admin.agent.conges.show', compact('conge'));
    }

    /**
     * Annuler une demande de congés
     */
    public function annuler($id)
    {
        $employe = Employe::where('user_id', Auth::id())->first();

        $conge = Conge::where('employe_id', $employe->id)
            ->where('statut', 'en_attente')
            ->findOrFail($id);

        $conge->update(['statut' => 'annule']);

        return back()->with('success', 'Demande annulée.');
    }

    /**
     * Mes soldes de congés
     */
    public function soldes()
    {
        $employe = Employe::where('user_id', Auth::id())->first();

        if (!$employe) {
            return back()->with('error', 'Profil employé non trouvé.');
        }

        $soldes = SoldeConge::where('employe_id', $employe->id)->get();

        return view('admin.agent.conges.soldes', compact('soldes', 'employe'));
    }

    /**
     * Calendrier des congés
     */
    public function calendrier(Request $request)
    {
        $employe = Employe::where('user_id', Auth::id())->first();

        if (!$employe) {
            return back()->with('error', 'Profil employé non trouvé.');
        }

        $annee = $request->get('annee', now()->year);

        $conges = Conge::where('employe_id', $employe->id)
            ->whereYear('date_debut', $annee)
            ->orWhereYear('date_fin', $annee)
            ->get();

        return view('admin.agent.conges.calendrier', compact('conges', 'annee', 'employe'));
    }
}
