<?php

namespace App\Http\Controllers\Entreprise;

use App\Http\Controllers\Controller;
use App\Models\ContratPrestation;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContratController extends Controller
{
    /**
     * Constructeur
     */
    public function __construct()
    {
        $this->middleware(['auth', 'entreprise']);
    }

    /**
     * Liste des contrats
     */
    public function index(Request $request)
    {
        $entrepriseId = Auth::user()->entreprise_id;

        $query = ContratPrestation::where('entreprise_id', $entrepriseId)->with('client');

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('type')) {
            $query->where('type_contrat', $request->type);
        }

        $contrats = $query->orderByDesc('date_debut')->paginate(15);

        $stats = [
            'total' => ContratPrestation::where('entreprise_id', $entrepriseId)->count(),
            'actifs' => ContratPrestation::where('entreprise_id', $entrepriseId)->where('statut', 'en_cours')->count(),
            'expires' => ContratPrestation::where('entreprise_id', $entrepriseId)->where('statut', 'expire')->count(),
            'resilies' => ContratPrestation::where('entreprise_id', $entrepriseId)->where('statut', 'resilie')->count(),
        ];

        return view('admin.entreprise.contrats.index', compact('contrats', 'stats'));
    }

    /**
     * Créer un contrat
     */
    public function create()
    {
        $clients = Client::where('entreprise_id', Auth::user()->entreprise_id)
            ->where('est_actif', true)
            ->orderBy('nom')
            ->get();

        return view('admin.entreprise.contrats.create', compact('clients'));
    }

    /**
     * Enregistrer un contrat
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'type_contrat' => 'required|in:ponctuel,annuel,multiannuel',
            'objet' => 'required|string',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after:date_debut',
            'montant_ht' => 'required|numeric|min:0',
            'tva' => 'nullable|numeric|min:0|max:100',
            'frequence_facturation' => 'required|in:mensuelle,trimestrielle,annuelle',
            'conditions_paiement' => 'nullable|string',
            'clause_resiliation' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $validated['entreprise_id'] = Auth::user()->entreprise_id;
        $validated['statut'] = 'en_cours';
        $validated['montant_ttc'] = $validated['montant_ht'] * (1 + ($validated['tva'] ?? 18) / 100);

        ContratPrestation::create($validated);

        return redirect()->route('admin.entreprise.contrats.index')
            ->with('success', 'Contrat créé avec succès.');
    }

    /**
     * Voir un contrat
     */
    public function show($id)
    {
        $contrat = ContratPrestation::with(['client', 'sites', 'factures', 'employesAffectes'])
            ->where('entreprise_id', Auth::user()->entreprise_id)
            ->findOrFail($id);

        return view('admin.entreprise.contrats.show', compact('contrat'));
    }

    /**
     * Modifier un contrat
     */
    public function edit($id)
    {
        $contrat = ContratPrestation::where('entreprise_id', Auth::user()->entreprise_id)
            ->findOrFail($id);

        $clients = Client::where('entreprise_id', Auth::user()->entreprise_id)
            ->where('est_actif', true)
            ->get();

        return view('admin.entreprise.contrats.edit', compact('contrat', 'clients'));
    }

    /**
     * Mettre à jour un contrat
     */
    public function update(Request $request, $id)
    {
        $contrat = ContratPrestation::where('entreprise_id', Auth::user()->entreprise_id)
            ->findOrFail($id);

        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'type_contrat' => 'required|in:ponctuel,annuel,multiannuel',
            'objet' => 'required|string',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date',
            'montant_ht' => 'required|numeric|min:0',
            'tva' => 'nullable|numeric|min:0|max:100',
            'frequence_facturation' => 'required|in:mensuelle,trimestrielle,annuelle',
            'conditions_paiement' => 'nullable|string',
            'clause_resiliation' => 'nullable|string',
            'notes' => 'nullable|string',
            'statut' => 'required|in:en_cours,expire,resilie,suspendu',
        ]);

        $validated['montant_ttc'] = $validated['montant_ht'] * (1 + ($validated['tva'] ?? 18) / 100);

        $contrat->update($validated);

        return redirect()->route('admin.entreprise.contrats.index')
            ->with('success', 'Contrat mis à jour.');
    }

    /**
     * Supprimer un contrat
     */
    public function destroy($id)
    {
        $contrat = ContratPrestation::where('entreprise_id', Auth::user()->entreprise_id)
            ->findOrFail($id);

        $contrat->delete();

        return redirect()->route('admin.entreprise.contrats.index')
            ->with('success', 'Contrat supprimé.');
    }

    /**
     * Résilier un contrat
     */
    public function resilier(Request $request, $id)
    {
        $contrat = ContratPrestation::where('entreprise_id', Auth::user()->entreprise_id)
            ->findOrFail($id);

        $validated = $request->validate([
            'motif_resiliation' => 'required|string',
        ]);

        $contrat->update([
            'statut' => 'resilie',
            'date_resiliation' => now(),
            'motif_resiliation' => $validated['motif_resiliation'],
        ]);

        return back()->with('success', 'Contrat résilié.');
    }

    /**
     * Renouveler un contrat
     */
    public function renouveler(Request $request, $id)
    {
        $contrat = ContratPrestation::where('entreprise_id', Auth::user()->entreprise_id)
            ->findOrFail($id);

        $validated = $request->validate([
            'nouvelle_date_fin' => 'required|date|after:today',
            'nouveau_montant' => 'required|numeric|min:0',
        ]);

        $contrat->update([
            'date_fin' => $validated['nouvelle_date_fin'],
            'montant_ht' => $validated['nouveau_montant'],
            'statut' => 'en_cours',
            'date_resiliation' => null,
            'motif_resiliation' => null,
        ]);

        return back()->with('success', 'Contrat renouvelé.');
    }

    /**
     * Suspendre un contrat
     */
    public function suspendre($id)
    {
        $contrat = ContratPrestation::where('entreprise_id', Auth::user()->entreprise_id)
            ->findOrFail($id);

        $contrat->update(['statut' => 'suspendu']);

        return back()->with('success', 'Contrat suspendu.');
    }

    /**
     * Reprendre un contrat
     */
    public function reprendre($id)
    {
        $contrat = ContratPrestation::where('entreprise_id', Auth::user()->entreprise_id)
            ->findOrFail($id);

        $contrat->update(['statut' => 'en_cours']);

        return back()->with('success', 'Contrat repris.');
    }
}
