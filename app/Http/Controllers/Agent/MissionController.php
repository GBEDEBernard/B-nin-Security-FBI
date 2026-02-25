<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Employe;
use App\Models\Affectation;
use App\Models\Pointage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MissionController extends Controller
{
    /**
     * Constructeur
     */
    public function __construct()
    {
        $this->middleware(['auth', 'entreprise']);
    }

    /**
     * Liste des missions (affectations)
     */
    public function index(Request $request)
    {
        $employe = Employe::where('user_id', Auth::id())->first();

        if (!$employe) {
            return redirect()->route('home')->with('error', 'Profil employé non trouvé.');
        }

        $query = Affectation::where('employe_id', $employe->id)
            ->with(['site', 'contrat.client']);

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $missions = $query->orderBy('date_debut', 'desc')->paginate(15);

        $stats = [
            'total' => Affectation::where('employe_id', $employe->id)->count(),
            'actives' => Affectation::where('employe_id', $employe->id)->where('statut', 'active')->count(),
            'terminees' => Affectation::where('employe_id', $employe->id)->where('statut', 'terminee')->count(),
        ];

        return view('admin.agent.missions.index', compact('missions', 'stats', 'employe'));
    }

    /**
     * Voir une mission
     */
    public function show($id)
    {
        $employe = Employe::where('user_id', Auth::id())->first();

        $mission = Affectation::where('employe_id', $employe->id)
            ->with(['site', 'contrat.client', 'pointages'])
            ->findOrFail($id);

        return view('admin.agent.missions.show', compact('mission'));
    }

    /**
     * Accepter une mission
     */
    public function accepter($id)
    {
        $employe = Employe::where('user_id', Auth::id())->first();

        $mission = Affectation::where('employe_id', $employe->id)
            ->findOrFail($id);

        $mission->update(['statut' => 'active']);

        return back()->with('success', 'Mission acceptée.');
    }

    /**
     * Refuser une mission
     */
    public function refuser(Request $request, $id)
    {
        $employe = Employe::where('user_id', Auth::id())->first();

        $mission = Affectation::where('employe_id', $employe->id)
            ->findOrFail($id);

        $validated = $request->validate([
            'motif_refus' => 'required|string',
        ]);

        $mission->update([
            'statut' => 'annulee',
            'notes' => $validated['motif_refus'],
        ]);

        return back()->with('success', 'Mission refusée.');
    }

    /**
     * Terminer une mission
     */
    public function terminer(Request $request, $id)
    {
        $employe = Employe::where('user_id', Auth::id())->first();

        $mission = Affectation::where('employe_id', $employe->id)
            ->findOrFail($id);

        $validated = $request->validate([
            'rapport' => 'required|string',
        ]);

        $mission->update([
            'statut' => 'terminee',
            'notes' => $validated['rapport'],
        ]);

        return back()->with('success', 'Mission terminée.');
    }
}
