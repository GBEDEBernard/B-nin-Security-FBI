<?php

namespace App\Http\Controllers\Entreprise;

use App\Http\Controllers\Controller;
use App\Models\Affectation;
use App\Models\Employe;
use App\Models\SiteClient;
use App\Models\ContratPrestation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AffectationController extends Controller
{
    /**
     * Constructeur
     */
    public function __construct()
    {
        $this->middleware(['auth', 'entreprise']);
    }

    /**
     * Liste des affectations (Planning)
     */
    public function index(Request $request)
    {
        $entrepriseId = Auth::user()->entreprise_id;

        $query = Affectation::whereHas('employe', function ($q) use ($entrepriseId) {
            $q->where('entreprise_id', $entrepriseId);
        })->with(['employe', 'site', 'contrat']);

        if ($request->filled('date')) {
            $query->whereDate('date_debut', '<=', $request->date)
                ->whereDate('date_fin', '>=', $request->date);
        }

        if ($request->filled('employe_id')) {
            $query->where('employe_id', $request->employe_id);
        }

        $affectations = $query->orderBy('date_debut', 'desc')->paginate(20);

        $stats = [
            'total' => Affectation::whereHas('employe', function ($q) use ($entrepriseId) {
                $q->where('entreprise_id', $entrepriseId);
            })->count(),
            'actives' => Affectation::whereHas('employe', function ($q) use ($entrepriseId) {
                $q->where('entreprise_id', $entrepriseId);
            })->where('statut', 'active')->count(),
        ];

        return view('admin.entreprise.affectations.index', compact('affectations', 'stats'));
    }

    /**
     * Créer une affectation
     */
    public function create(Request $request)
    {
        $employes = Employe::where('entreprise_id', Auth::user()->entreprise_id)
            ->where('disponible', true)
            ->where('est_actif', true)
            ->orderBy('nom')
            ->get();

        $contrats = ContratPrestation::where('entreprise_id', Auth::user()->entreprise_id)
            ->where('statut', 'en_cours')
            ->with('client')
            ->get();

        $siteId = $request->get('site_id');

        return view('admin.entreprise.affectations.create', compact('employes', 'contrats', 'siteId'));
    }

    /**
     * Enregistrer une affectation
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employe_id' => 'required|exists:employes,id',
            'site_id' => 'required|exists:client_sites,id',
            'contrat_id' => 'required|exists:contrat_prestations,id',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'heure_debut' => 'required',
            'heure_fin' => 'required',
            'poste' => 'required|string',
            'instructions' => 'nullable|string',
            'statut' => 'required|in:active,terminee,annulee',
        ]);

        $validated['entreprise_id'] = Auth::user()->entreprise_id;

        Affectation::create($validated);

        return redirect()->route('admin.entreprise.affectations.index')
            ->with('success', 'Affectation créée avec succès.');
    }

    /**
     * Voir une affectation
     */
    public function show($id)
    {
        $affectation = Affectation::with(['employe', 'site', 'contrat'])
            ->whereHas('employe', function ($q) {
                $q->where('entreprise_id', Auth::user()->entreprise_id);
            })
            ->findOrFail($id);

        return view('admin.entreprise.affectations.show', compact('affectation'));
    }

    /**
     * Modifier une affectation
     */
    public function edit($id)
    {
        $affectation = Affectation::whereHas('employe', function ($q) {
            $q->where('entreprise_id', Auth::user()->entreprise_id);
        })->findOrFail($id);

        $employes = Employe::where('entreprise_id', Auth::user()->entreprise_id)
            ->orderBy('nom')
            ->get();

        $contrats = ContratPrestation::where('entreprise_id', Auth::user()->entreprise_id)
            ->where('statut', 'en_cours')
            ->get();

        return view('admin.entreprise.affectations.edit', compact('affectation', 'employes', 'contrats'));
    }

    /**
     * Mettre à jour une affectation
     */
    public function update(Request $request, $id)
    {
        $affectation = Affectation::whereHas('employe', function ($q) {
            $q->where('entreprise_id', Auth::user()->entreprise_id);
        })->findOrFail($id);

        $validated = $request->validate([
            'employe_id' => 'required|exists:employes,id',
            'site_id' => 'required|exists:client_sites,id',
            'contrat_id' => 'required|exists:contrat_prestations,id',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date',
            'heure_debut' => 'required',
            'heure_fin' => 'required',
            'poste' => 'required|string',
            'instructions' => 'nullable|string',
            'statut' => 'required|in:active,terminee,annulee',
        ]);

        $affectation->update($validated);

        return redirect()->route('admin.entreprise.affectations.index')
            ->with('success', 'Affectation mise à jour.');
    }

    /**
     * Supprimer une affectation
     */
    public function destroy($id)
    {
        $affectation = Affectation::whereHas('employe', function ($q) {
            $q->where('entreprise_id', Auth::user()->entreprise_id);
        })->findOrFail($id);

        $affectation->delete();

        return redirect()->route('admin.entreprise.affectations.index')
            ->with('success', 'Affectation supprimée.');
    }

    /**
     * Terminer une affectation
     */
    public function terminer($id)
    {
        $affectation = Affectation::whereHas('employe', function ($q) {
            $q->where('entreprise_id', Auth::user()->entreprise_id);
        })->findOrFail($id);

        $affectation->update(['statut' => 'terminee']);

        return back()->with('success', 'Affectation terminée.');
    }

    /**
     * Annuler une affectation
     */
    public function annuler($id)
    {
        $affectation = Affectation::whereHas('employe', function ($q) {
            $q->where('entreprise_id', Auth::user()->entreprise_id);
        })->findOrFail($id);

        $affectation->update(['statut' => 'annulee']);

        return back()->with('success', 'Affectation annulée.');
    }

    /**
     * Vue planning (calendrier)
     */
    public function planning(Request $request)
    {
        $date = $request->get('date', now());

        $affectations = Affectation::whereHas('employe', function ($q) {
            $q->where('entreprise_id', Auth::user()->entreprise_id);
        })->with(['employe', 'site'])
            ->whereDate('date_debut', '<=', $date)
            ->whereDate('date_fin', '>=', $date)
            ->get();

        return view('admin.entreprise.affectations.planning', compact('affectations', 'date'));
    }
}
