<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Employe;
use App\Models\Pointage;
use App\Models\Affectation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PointageController extends Controller
{
    /**
     * Constructeur
     */
    public function __construct()
    {
        $this->middleware(['auth', 'entreprise']);
    }

    /**
     * Liste des pointages
     */
    public function index(Request $request)
    {
        $employe = Employe::where('user_id', Auth::id())->first();

        if (!$employe) {
            return redirect()->route('home')->with('error', 'Profil employé non trouvé.');
        }

        $query = Pointage::where('employe_id', $employe->id);

        if ($request->filled('date_debut')) {
            $query->whereDate('date', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('date', '<=', $request->date_fin);
        }

        $pointages = $query->orderByDesc('date')->paginate(15);

        $stats = [
            'total' => Pointage::where('employe_id', $employe)->count(),
            'aujourd_hui' => Pointage::where('employe_id', $employe)->whereDate('date', today())->count(),
            'heures_sup' => Pointage::where('employe_id', $employe)->sum('heures_supplementaires'),
        ];

        return view('admin.agent.pointages.index', compact('pointages', 'stats', 'employe'));
    }

    /**
     * Pointer l'entrée
     */
    public function pointerEntree(Request $request)
    {
        $employe = Employe::where('user_id', Auth::id())->first();

        if (!$employe) {
            return back()->with('error', 'Profil employé non trouvé.');
        }

        // Vérifier si déjà pointé aujourd'hui
        $existant = Pointage::where('employe_id', $employe->id)
            ->whereDate('date', today())
            ->first();

        if ($existant && $existant->heure_entree) {
            return back()->with('error', 'Vous avez déjà pointé votre entrée aujourd\'hui.');
        }

        $validated = $request->validate([
            'heure_entree' => 'required',
            'localisation_entree' => 'nullable|string',
            'photo_entree' => 'nullable|image',
            'remarques' => 'nullable|string',
        ]);

        if ($existant) {
            $existant->update([
                'heure_entree' => $validated['heure_entree'],
                'localisation_entree' => $validated['localisation_entree'] ?? null,
                'remarques' => $validated['remarques'] ?? null,
            ]);
        } else {
            Pointage::create([
                'employe_id' => $employe->id,
                'entreprise_id' => $employe->entreprise_id,
                'date' => today(),
                'heure_entree' => $validated['heure_entree'],
                'localisation_entree' => $validated['localisation_entree'] ?? null,
                'remarques' => $validated['remarques'] ?? null,
                'statut' => 'en_cours',
            ]);
        }

        return back()->with('success', 'Entrée pointée avec succès.');
    }

    /**
     * Pointer la sortie
     */
    public function pointerSortie(Request $request, $id)
    {
        $pointage = Pointage::whereHas('employe', function ($q) {
            $q->where('user_id', Auth::id());
        })->findOrFail($id);

        $validated = $request->validate([
            'heure_sortie' => 'required',
            'localisation_sortie' => 'nullable|string',
            'photo_sortie' => 'nullable|image',
            'rapport_journee' => 'nullable|string',
        ]);

        $pointage->update([
            'heure_sortie' => $validated['heure_sortie'],
            'localisation_sortie' => $validated['localisation_sortie'] ?? null,
            'rapport_journee' => $validated['rapport_journee'] ?? null,
            'statut' => 'termine',
        ]);

        return back()->with('success', 'Sortie pointée avec succès.');
    }

    /**
     * Voir un pointage
     */
    public function show($id)
    {
        $pointage = Pointage::whereHas('employe', function ($q) {
            $q->where('user_id', Auth::id());
        })->findOrFail($id);

        return view('admin.agent.pointages.show', compact('pointage'));
    }

    /**
     * Today's pointage
     */
    public function today()
    {
        $employe = Employe::where('user_id', Auth::id())->first();

        if (!$employe) {
            return back()->with('error', 'Profil employé non trouvé.');
        }

        $pointage = Pointage::where('employe_id', $employe->id)
            ->whereDate('date', today())
            ->first();

        // Mission du jour
        $mission = Affectation::where('employe_id', $employe->id)
            ->whereDate('date_debut', '<=', today())
            ->whereDate('date_fin', '>=', today())
            ->where('statut', 'active')
            ->with(['site', 'contrat.client'])
            ->first();

        return view('admin.agent.pointages.today', compact('pointage', 'mission', 'employe'));
    }

    /**
     * Signaler un problème
     */
    public function signalerProbleme(Request $request, $id)
    {
        $pointage = Pointage::whereHas('employe', function ($q) {
            $q->where('user_id', Auth::id());
        })->findOrFail($id);

        $validated = $request->validate([
            'type_probleme' => 'required|in:retard,absence,incident,autre',
            'description' => 'required|string',
        ]);

        $pointage->update([
            'probleme_type' => $validated['type_probleme'],
            'probleme_description' => $validated['description'],
            'probleme_signale' => true,
        ]);

        return back()->with('success', 'Problème signalé.');
    }
}
