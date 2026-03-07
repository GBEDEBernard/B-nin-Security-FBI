<?php

namespace App\Http\Controllers\Entreprise;

use App\Http\Controllers\Controller;
use App\Models\ContratPrestation;
use App\Models\Client;
use App\Models\SiteClient;
use App\Models\Affectation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ContratController extends Controller
{
    /**
     * Constructeur
     */
    public function __construct()
    {
        // Le middleware 'auth' et 'entreprise' est appliqué au niveau des routes
    }

    /**
     * Liste des contrats
     */
    public function index(Request $request)
    {
        $entrepriseId = $this->getEntrepriseId();

        $query = ContratPrestation::where('entreprise_id', $entrepriseId)
            ->with(['client', 'sites']);

        // Filtres
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('numero_contrat', 'like', "%{$search}%")
                    ->orWhere('intitule', 'like', "%{$search}%");
            });
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $contrats = $query->orderByDesc('created_at')->paginate(15);

        // Statistiques
        $stats = [
            'total' => ContratPrestation::where('entreprise_id', $entrepriseId)->count(),
            'actifs' => ContratPrestation::where('entreprise_id', $entrepriseId)->where('statut', 'en_cours')->count(),
            'brouillon' => ContratPrestation::where('entreprise_id', $entrepriseId)->where('statut', 'brouillon')->count(),
            'resilies' => ContratPrestation::where('entreprise_id', $entrepriseId)->where('statut', 'resilie')->count(),
            'expires' => ContratPrestation::where('entreprise_id', $entrepriseId)
                ->where('date_fin', '<', now())
                ->where('statut', '!=', 'resilie')
                ->count(),
        ];

        return view('admin.entreprise.contrats.index', compact('contrats', 'stats'));
    }

    /**
     * Formulaire de création
     */
    public function create(Request $request)
    {
        $entrepriseId = $this->getEntrepriseId();

        // Clients actifs (un seul contrat par client)
        $clients = Client::where('entreprise_id', $entrepriseId)
            ->where('est_actif', true)
            ->whereDoesntHave('contrats', function ($query) {
                $query->whereIn('statut', ['brouillon', 'en_cours']);
            })
            ->orderBy('nom')
            ->get();

        // Pré-sélectionner un client si fourni
        $clientId = $request->get('client_id');

        return view('admin.entreprise.contrats.create', compact('clients', 'clientId'));
    }

    /**
     * Enregistrer un nouveau contrat
     */
    public function store(Request $request)
    {
        $entrepriseId = $this->getEntrepriseId();

        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'intitule' => 'required|string|max:255',
            'numero_contrat' => 'nullable|string|unique:contrats_prestation,numero_contrat',
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
            'date_signature' => 'nullable|date',
        ]);

        // Vérifier que le client n'a pas déjà un contrat actif
        $client = Client::find($validated['client_id']);
        $existingContrat = $client->contrats()
            ->whereIn('statut', ['brouillon', 'en_cours'])
            ->first();

        if ($existingContrat) {
            return back()->with('error', 'Ce client a déjà un contrat actif ou en brouillon.');
        }

        // Calculs financiers
        $tva = $validated['tva'] ?? 18;
        $validated['montant_mensuel_ttc'] = $validated['montant_mensuel_ht'] * (1 + $tva / 100);
        $validated['montant_annuel_ht'] = $validated['montant_mensuel_ht'] * 12;

        // Conversion JSON
        if (isset($validated['horaires_globaux']) && is_array($validated['horaires_globaux'])) {
            $validated['horaires_globaux'] = json_encode($validated['horaires_globaux']);
        }

        $validated['est_renouvelable'] = $validated['est_renouvelable'] ?? false;
        $validated['entreprise_id'] = $entrepriseId;

        // Définir l'employé créateur du contrat
        $validated['cree_par'] = $this->getCurrentEmployeId();

        // Générer numéro si absent
        if (empty($validated['numero_contrat'])) {
            $validated['numero_contrat'] = $this->genererNumeroContrat($entrepriseId);
        }

        $contrat = ContratPrestation::create($validated);

        return redirect()->route('admin.entreprise.contrats.show', $contrat->id)
            ->with('success', 'Contrat créé avec succès.');
    }

    /**
     * Afficher les détails d'un contrat
     */
    public function show($id)
    {
        $entrepriseId = $this->getEntrepriseId();

        $contrat = ContratPrestation::with([
            'client',
            'sites.site',
            'factures' => function ($query) {
                $query->orderByDesc('created_at')->limit(5);
            },
            'affectations.employe' => function ($query) {
                $query->where('est_actif', true);
            }
        ])
            ->where('entreprise_id', $entrepriseId)
            ->findOrFail($id);

        // Statistiques du contrat
        $statsContrat = [
            'sites_count' => $contrat->sites()->count(),
            'agents_count' => $contrat->affectations()->where('statut', 'en_cours')->count(),
            'factures_count' => $contrat->factures()->count(),
            'montant_total' => $contrat->factures()->where('statut', 'payee')->sum('montant_paye'),
        ];

        return view('admin.entreprise.contrats.show', compact('contrat', 'statsContrat'));
    }

    /**
     * Formulaire de modification
     */
    public function edit($id)
    {
        $entrepriseId = $this->getEntrepriseId();

        $contrat = ContratPrestation::where('entreprise_id', $entrepriseId)->findOrFail($id);

        $clients = Client::where('entreprise_id', $entrepriseId)
            ->where('est_actif', true)
            ->orderBy('nom')
            ->get();

        return view('admin.entreprise.contrats.edit', compact('contrat', 'clients'));
    }

    /**
     * Mettre à jour un contrat
     */
    public function update(Request $request, $id)
    {
        $entrepriseId = $this->getEntrepriseId();

        $contrat = ContratPrestation::where('entreprise_id', $entrepriseId)->findOrFail($id);

        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'intitule' => 'required|string|max:255',
            'numero_contrat' => 'required|string|unique:contrats_prestation,numero_contrat,' . $id,
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
            'date_signature' => 'nullable|date',
            'motif_resiliation' => 'nullable|string|required_if:statut,resilie',
            'date_resiliation' => 'nullable|date|required_if:statut,resilie',
        ]);

        // Calculs financiers
        $tva = $validated['tva'] ?? 18;
        $validated['montant_mensuel_ttc'] = $validated['montant_mensuel_ht'] * (1 + $tva / 100);
        $validated['montant_annuel_ht'] = $validated['montant_mensuel_ht'] * 12;

        // Conversion JSON
        if (isset($validated['horaires_globaux']) && is_array($validated['horaires_globaux'])) {
            $validated['horaires_globaux'] = json_encode($validated['horaires_globaux']);
        }

        $validated['est_renouvelable'] = $validated['est_renouvelable'] ?? false;

        // Gestion résiliation
        if ($validated['statut'] === 'resilie' && !empty($validated['date_resiliation'])) {
            $validated['date_fin'] = $validated['date_resiliation'];
        }

        $contrat->update($validated);

        return redirect()->route('admin.entreprise.contrats.show', $contrat->id)
            ->with('success', 'Contrat mis à jour.');
    }

    /**
     * Supprimer un contrat
     */
    public function destroy($id)
    {
        $entrepriseId = $this->getEntrepriseId();

        $contrat = ContratPrestation::where('entreprise_id', $entrepriseId)->findOrFail($id);

        // Vérifier les contraintes
        if ($contrat->factures()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer un contrat avec des factures.');
        }

        if ($contrat->affectations()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer un contrat avec des agents affectés.');
        }

        $contrat->delete();

        return redirect()->route('admin.entreprise.contrats.index')
            ->with('success', 'Contrat supprimé.');
    }

    /**
     * Changer le statut
     */
    public function changerStatut(Request $request, $id)
    {
        $entrepriseId = $this->getEntrepriseId();

        $request->validate([
            'statut' => 'required|in:brouillon,en_cours,suspendu,termine,resilie',
            'motif_resiliation' => 'nullable|string|required_if:statut,resilie',
            'date_resiliation' => 'nullable|date|required_if:statut,resilie',
        ]);

        $contrat = ContratPrestation::where('entreprise_id', $entrepriseId)->findOrFail($id);

        $data = ['statut' => $request->statut];

        if ($request->statut === 'resilie') {
            $data['motif_resiliation'] = $request->motif_resiliation;
            $data['date_resiliation'] = $request->date_resiliation;
            $data['date_fin'] = $request->date_resiliation;
        }

        $contrat->update($data);

        return back()->with('success', 'Statut mis à jour.');
    }

    /**
     * Dupliquer un contrat
     */
    public function dupliquer($id)
    {
        $entrepriseId = $this->getEntrepriseId();

        $contrat = ContratPrestation::where('entreprise_id', $entrepriseId)->findOrFail($id);

        $nouveauContrat = $contrat->replicate();
        $nouveauContrat->numero_contrat = $this->genererNumeroContrat($entrepriseId);
        $nouveauContrat->statut = 'brouillon';
        $nouveauContrat->date_signature = null;
        $nouveauContrat->date_debut = null;
        $nouveauContrat->date_fin = null;
        $nouveauContrat->save();

        return redirect()->route('admin.entreprise.contrats.edit', $nouveauContrat->id)
            ->with('success', 'Contrat dupliqué. Vous pouvez le modifier.');
    }

    /**
     * Ajouter un site au contrat
     */
    public function ajouterSite(Request $request, $id)
    {
        $entrepriseId = $this->getEntrepriseId();

        $request->validate([
            'site_client_id' => 'required|exists:sites_clients,id',
            'nombre_agents_site' => 'required|integer|min:1',
            'horaires_site' => 'nullable|array',
            'consignes_site' => 'nullable|string',
        ]);

        $contrat = ContratPrestation::where('entreprise_id', $entrepriseId)->findOrFail($id);

        // Vérifier si le site est déjà associé
        $exists = $contrat->sites()->where('site_client_id', $request->site_client_id)->exists();
        if ($exists) {
            return back()->with('error', 'Ce site est déjà associé au contrat.');
        }

        $contrat->sites()->create([
            'site_client_id' => $request->site_client_id,
            'nombre_agents_site' => $request->nombre_agents_site,
            'horaires_site' => $request->horaires_site ? json_encode($request->horaires_site) : null,
            'consignes_site' => $request->consignes_site,
        ]);

        return back()->with('success', 'Site ajouté au contrat.');
    }

    /**
     * Retirer un site du contrat
     */
    public function retirerSite($id, $siteId)
    {
        $entrepriseId = $this->getEntrepriseId();

        $contrat = ContratPrestation::where('entreprise_id', $entrepriseId)->findOrFail($id);

        $siteContrat = $contrat->sites()->where('id', $siteId)->first();

        if (!$siteContrat) {
            return back()->with('error', 'Site non trouvé sur ce contrat.');
        }

        // Vérifier s'il y a des affectations
        $affectations = Affectation::where('site_contrat_id', $siteId)->count();
        if ($affectations > 0) {
            return back()->with('error', 'Impossible de retirer ce site : des agents y sont affectés.');
        }

        $siteContrat->delete();

        return back()->with('success', 'Site retiré du contrat.');
    }

    /**
     * Obtenir l'entreprise_id selon le type d'utilisateur connecté.
     *
     * Priorité :
     *   1. SuperAdmin (guard web) avec une entreprise sélectionnée en session
     *   2. Employé (guard employe) → son entreprise_id direct
     */
    private function getEntrepriseId(): ?int
    {
        // SuperAdmin en contexte entreprise
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->estSuperAdmin() && session()->has('entreprise_id')) {
                return (int) session('entreprise_id');
            }
        }

        // Employé connecté via guard 'employe'
        if (Auth::guard('employe')->check()) {
            $employe = Auth::guard('employe')->user();
            return $employe->entreprise_id ? (int) $employe->entreprise_id : null;
        }

        return null;
    }

    /**
     * Générer un numéro de contrat unique
     */
    private function genererNumeroContrat(int $entrepriseId): string
    {
        $prefix = 'CTR-' . date('Y') . '-';
        $dernier = ContratPrestation::where('entreprise_id', $entrepriseId)
            ->where('numero_contrat', 'like', "{$prefix}%")
            ->orderBy('numero_contrat', 'desc')
            ->first();

        if ($dernier) {
            $numero = (int) substr($dernier->numero_contrat, -4) + 1;
        } else {
            $numero = 1;
        }

        return $prefix . str_pad($numero, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Obtenir l'ID de l'employé connecté
     */
    private function getCurrentEmployeId(): ?int
    {
        // Guard 'employe' pour les employés de l'entreprise
        if (Auth::guard('employe')->check()) {
            return Auth::guard('employe')->user()->id;
        }

        // Fallback: utilisateur web avec lien vers employé
        if (Auth::guard('web')->check() && Auth::user()->employe_id) {
            return Auth::user()->employe_id;
        }

        return null;
    }
}
