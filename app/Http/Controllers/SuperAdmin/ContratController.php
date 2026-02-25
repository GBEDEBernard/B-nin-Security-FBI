<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ContratPrestation;
use App\Models\Entreprise;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ContratController extends Controller
{
    /**
     * Constructeur
     */
    public function __construct()
    {
        $this->middleware(['auth', 'superadmin']);
    }

    /**
     * Liste de tous les contrats (toutes entreprises confondues)
     */
    public function index(Request $request)
    {
        $query = ContratPrestation::with(['entreprise', 'client']);

        // Recherche
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('numero_contrat', 'like', "%{$search}%")
                    ->orWhere('intitule', 'like', "%{$search}%")
                    ->orWhereHas('entreprise', function ($eq) use ($search) {
                        $eq->where('nom_entreprise', 'like', "%{$search}%");
                    })
                    ->orWhereHas('client', function ($cq) use ($search) {
                        $cq->where('nom', 'like', "%{$search}%");
                    });
            });
        }

        // Filtre par entreprise
        if ($request->has('entreprise_id') && $request->entreprise_id) {
            $query->where('entreprise_id', $request->entreprise_id);
        }

        // Filtre par statut
        if ($request->has('statut') && $request->statut) {
            $query->where('statut', $request->statut);
        }

        // Tri
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder === 'desc' ? 'desc' : 'asc');

        $contrats = $query->paginate(15)->appends($request->query());
        $entreprises = Entreprise::orderBy('nom_entreprise')->get();

        return view('admin.superadmin.contrats.index', compact('contrats', 'entreprises'));
    }

    /**
     * Formulaire de création d'un contrat
     */
    public function create(Request $request)
    {
        $entreprises = Entreprise::where('est_active', true)->orderBy('nom_entreprise')->get();

        $clients = [];
        if ($request->has('entreprise_id') && $request->entreprise_id) {
            $clients = Client::where('entreprise_id', $request->entreprise_id)
                ->where('est_actif', true)
                ->orderBy('nom')
                ->get();
        }

        return view('admin.superadmin.contrats.create', compact('entreprises', 'clients'));
    }

    /**
     * Récupérer les clients d'une entreprise (pour AJAX)
     */
    public function getClients(Request $request)
    {
        $request->validate([
            'entreprise_id' => 'required|exists:entreprises,id'
        ]);

        $clients = Client::where('entreprise_id', $request->entreprise_id)
            ->where('est_actif', true)
            ->orderBy('nom')
            ->get(['id', 'nom', 'email']);

        return response()->json($clients);
    }

    /**
     * Enregistrer un nouveau contrat
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'entreprise_id' => 'required|exists:entreprises,id',
            'client_id' => 'required|exists:clients,id',
            'numero_contrat' => 'required|string|unique:contrats_prestation,numero_contrat',
            'intitule' => 'required|string|max:255',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'est_renouvelable' => 'boolean',
            'duree_preavis' => 'nullable|integer|min:0',
            'montant_mensuel_ht' => 'required|numeric|min:0',
            'tva' => 'nullable|numeric|min:0|max:100',
            'periodicite_facturation' => 'required|in:mensuel,trimestriel,semestriel,annuel',
            'nombre_agents_requis' => 'required|integer|min:1',
            'description_prestation' => 'nullable|string',
            'horaires_globaux' => 'nullable|array',
            'conditions_particulieres' => 'nullable|string',
            'statut' => 'required|in:brouillon,en_cours,suspendu,termine,resilie',
            'signataire_client_nom' => 'nullable|string|max:255',
            'signataire_client_fonction' => 'nullable|string|max:255',
            'signataire_securite_id' => 'nullable|exists:employes,id',
            'date_signature' => 'nullable|date',
        ]);

        // Calculer le montant TTC
        $tva = $validated['tva'] ?? 18;
        $validated['montant_mensuel_ttc'] = $validated['montant_mensuel_ht'] * (1 + $tva / 100);
        $validated['montant_annuel_ht'] = $validated['montant_mensuel_ht'] * 12;

        // Convertir horaires en JSON si c'est un tableau
        if (isset($validated['horaires_globaux']) && is_array($validated['horaires_globaux'])) {
            $validated['horaires_globaux'] = json_encode($validated['horaires_globaux']);
        }

        $validated['est_renouvelable'] = $validated['est_renouvelable'] ?? false;

        // Générer le numéro de contrat si non fourni
        if (empty($validated['numero_contrat'])) {
            $validated['numero_contrat'] = $this->genererNumeroContrat();
        }

        $contrat = ContratPrestation::create($validated);

        return redirect()->route('admin.superadmin.contrats.show', $contrat->id)
            ->with('success', 'Contrat créé avec succès.');
    }

    /**
     * Afficher les détails d'un contrat
     */
    public function show($id)
    {
        $contrat = ContratPrestation::with(['entreprise', 'client', 'signataireSecurite', 'factures', 'affectations'])
            ->findOrFail($id);

        return view('admin.superadmin.contrats.show', compact('contrat'));
    }

    /**
     * Formulaire de modification d'un contrat
     */
    public function edit($id)
    {
        $contrat = ContratPrestation::findOrFail($id);
        $entreprises = Entreprise::where('est_active', true)->orderBy('nom_entreprise')->get();

        $clients = Client::where('entreprise_id', $contrat->entreprise_id)
            ->where('est_actif', true)
            ->orderBy('nom')
            ->get();

        return view('admin.superadmin.contrats.edit', compact('contrat', 'entreprises', 'clients'));
    }

    /**
     * Mettre à jour un contrat
     */
    public function update(Request $request, $id)
    {
        $contrat = ContratPrestation::findOrFail($id);

        $validated = $request->validate([
            'entreprise_id' => 'required|exists:entreprises,id',
            'client_id' => 'required|exists:clients,id',
            'numero_contrat' => 'required|string|unique:contrats_prestation,numero_contrat,' . $id,
            'intitule' => 'required|string|max:255',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'est_renouvelable' => 'boolean',
            'duree_preavis' => 'nullable|integer|min:0',
            'montant_mensuel_ht' => 'required|numeric|min:0',
            'tva' => 'nullable|numeric|min:0|max:100',
            'periodicite_facturation' => 'required|in:mensuel,trimestriel,semestriel,annuel',
            'nombre_agents_requis' => 'required|integer|min:1',
            'description_prestation' => 'nullable|string',
            'horaires_globaux' => 'nullable|array',
            'conditions_particulieres' => 'nullable|string',
            'statut' => 'required|in:brouillon,en_cours,suspendu,termine,resilie',
            'signataire_client_nom' => 'nullable|string|max:255',
            'signataire_client_fonction' => 'nullable|string|max:255',
            'signataire_securite_id' => 'nullable|exists:employes,id',
            'date_signature' => 'nullable|date',
            'motif_resiliation' => 'nullable|string|required_if:statut,resilie',
            'date_resiliation' => 'nullable|date|required_if:statut,resilie',
        ]);

        // Calculer le montant TTC
        $tva = $validated['tva'] ?? 18;
        $validated['montant_mensuel_ttc'] = $validated['montant_mensuel_ht'] * (1 + $tva / 100);
        $validated['montant_annuel_ht'] = $validated['montant_mensuel_ht'] * 12;

        // Convertir horaires en JSON si c'est un tableau
        if (isset($validated['horaires_globaux']) && is_array($validated['horaires_globaux'])) {
            $validated['horaires_globaux'] = json_encode($validated['horaires_globaux']);
        }

        $validated['est_renouvelable'] = $validated['est_renouvelable'] ?? false;

        // Si résiliation, calculer la date de fin
        if ($validated['statut'] === 'resilie' && !empty($validated['date_resiliation'])) {
            $validated['date_fin'] = $validated['date_resiliation'];
        }

        $contrat->update($validated);

        return redirect()->route('admin.superadmin.contrats.show', $contrat->id)
            ->with('success', 'Contrat mis à jour.');
    }

    /**
     * Supprimer un contrat
     */
    public function destroy($id)
    {
        $contrat = ContratPrestation::findOrFail($id);

        // Vérifier si le contrat a des factures
        if ($contrat->factures()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer un contrat avec des factures associées.');
        }

        $contrat->delete();

        return redirect()->route('admin.superadmin.contrats.index')
            ->with('success', 'Contrat supprimé.');
    }

    /**
     * Changer le statut d'un contrat
     */
    public function changerStatut(Request $request, $id)
    {
        $request->validate([
            'statut' => 'required|in:brouillon,en_cours,suspendu,termine,resilie',
            'motif_resiliation' => 'nullable|string|required_if:statut,resilie',
            'date_resiliation' => 'nullable|date|required_if:statut,resilie',
        ]);

        $contrat = ContratPrestation::findOrFail($id);

        $data = ['statut' => $request->statut];

        if ($request->statut === 'resilie') {
            $data['motif_resiliation'] = $request->motif_resiliation;
            $data['date_resiliation'] = $request->date_resiliation;
            $data['date_fin'] = $request->date_resiliation;
        }

        $contrat->update($data);

        return back()->with('success', 'Statut du contrat mis à jour.');
    }

    /**
     * Dupliquer un contrat
     */
    public function dupliquer($id)
    {
        $contrat = ContratPrestation::findOrFail($id);

        $nouveauContrat = $contrat->replicate();
        $nouveauContrat->numero_contrat = $this->genererNumeroContrat();
        $nouveauContrat->statut = 'brouillon';
        $nouveauContrat->date_signature = null;
        $nouveauContrat->date_debut = null;
        $nouveauContrat->date_fin = null;
        $nouveauContrat->save();

        return redirect()->route('admin.superadmin.contrats.edit', $nouveauContrat->id)
            ->with('success', 'Contrat dupliqué. Vous pouvez maintenant le modifier.');
    }

    /**
     * Générer un numéro de contrat unique
     */
    private function genererNumeroContrat(): string
    {
        $prefix = 'CTR-' . date('Y');
        $dernierContrat = ContratPrestation::where('numero_contrat', 'like', "{$prefix}%")
            ->orderBy('numero_contrat', 'desc')
            ->first();

        if ($dernierContrat) {
            $numero = (int) substr($dernierContrat->numero_contrat, -5) + 1;
        } else {
            $numero = 1;
        }

        return $prefix . str_pad($numero, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Statistiques des contrats
     */
    public function statistiques()
    {
        $stats = [
            'total' => ContratPrestation::count(),
            'actifs' => ContratPrestation::where('statut', 'en_cours')->count(),
            'brouillon' => ContratPrestation::where('statut', 'brouillon')->count(),
            'suspendus' => ContratPrestation::where('statut', 'suspendu')->count(),
            'resilies' => ContratPrestation::where('statut', 'resilie')->count(),
            'expires' => ContratPrestation::where('date_fin', '<', now())->count(),
            'montant_total_mensuel' => ContratPrestation::where('statut', 'en_cours')->sum('montant_mensuel_ht'),
            'montant_total_annuel' => ContratPrestation::where('statut', 'en_cours')->sum('montant_annuel_ht'),
        ];

        $contratsParEntreprise = Entreprise::withCount(['contratsPrestation as contrats_count' => function ($query) {
            $query->where('statut', 'en_cours');
        }])->having('contrats_count', '>', 0)->get();

        $contratsParMois = ContratPrestation::selectRaw('MONTH(date_debut) as mois, COUNT(*) as total')
            ->whereYear('date_debut', date('Y'))
            ->groupBy('mois')
            ->get();

        return view('admin.superadmin.contrats.statistiques', compact('stats', 'contratsParEntreprise', 'contratsParMois'));
    }
}
