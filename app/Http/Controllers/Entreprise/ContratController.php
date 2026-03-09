<?php

namespace App\Http\Controllers\Entreprise;

use App\Http\Controllers\Controller;
use App\Models\ContratPrestation;
use App\Models\Client;
use App\Models\Affectation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContratController extends Controller
{
    // ═══════════════════════════════════════════════════════════════════
    //  LISTE
    // ═══════════════════════════════════════════════════════════════════
    public function index(Request $request)
    {
        $entrepriseId = $this->getEntrepriseId();

        $query = ContratPrestation::where('entreprise_id', $entrepriseId)
            ->with(['client', 'sites']);

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

        $stats = [
            'total'    => ContratPrestation::where('entreprise_id', $entrepriseId)->count(),
            'actifs'   => ContratPrestation::where('entreprise_id', $entrepriseId)->where('statut', 'en_cours')->count(),
            'brouillon' => ContratPrestation::where('entreprise_id', $entrepriseId)->where('statut', 'brouillon')->count(),
            'resilies' => ContratPrestation::where('entreprise_id', $entrepriseId)->where('statut', 'resilie')->count(),
            'expires'  => ContratPrestation::where('entreprise_id', $entrepriseId)
                ->where('date_fin', '<', now())
                ->where('statut', '!=', 'resilie')
                ->count(),
        ];

        return view('admin.entreprise.contrats.index', compact('contrats', 'stats'));
    }

    // ═══════════════════════════════════════════════════════════════════
    //  CRÉATION
    // ═══════════════════════════════════════════════════════════════════
    public function create(Request $request)
    {
        $entrepriseId = $this->getEntrepriseId();

        // Clients actifs sans contrat actif ou brouillon
        $clients = Client::withoutGlobalScope('entreprise')
            ->where('entreprise_id', $entrepriseId)
            ->where('est_actif', true)
            ->whereDoesntHave('contrats', function ($query) {
                $query->whereIn('statut', ['brouillon', 'en_cours']);
            })
            ->orderBy('nom')
            ->get();

        $clientId = $request->get('client_id');

        return view('admin.entreprise.contrats.create', compact('clients', 'clientId'));
    }

    public function store(Request $request)
    {
        $entrepriseId = $this->getEntrepriseId();

        $validated = $request->validate([
            'client_id'                => 'required|exists:clients,id',
            'intitule'                 => 'required|string|max:255',
            'numero_contrat'           => 'nullable|string|unique:contrats_prestation,numero_contrat',
            'date_debut'               => 'required|date',
            'date_fin'                 => 'required|date|after:date_debut',
            'duree_preavis'            => 'nullable|integer|min:0',
            'montant_mensuel_ht'       => 'nullable|numeric|min:0',
            'prix_par_agent'           => 'nullable|numeric|min:0',
            'tva'                      => 'nullable|numeric|min:0|max:100',
            'periodicite_facturation'  => 'required|in:mensuel,trimestriel,semestriel,annuel',
            'nombre_agents_requis'     => 'required|integer|min:1',
            'nombre_sites'             => 'nullable|integer|min:1',
            'description_prestation'   => 'nullable|string',
            'conditions_particulieres' => 'nullable|string',
            'statut'                   => 'required|in:brouillon,en_cours',
            'signataire_client_nom'    => 'nullable|string|max:255',
            'signataire_client_fonction' => 'nullable|string|max:255',
            'date_signature'           => 'nullable|date',
        ], $this->messages());

        // ✅ est_renouvelable : hidden="0" + checkbox="1"
        $validated['est_renouvelable'] = $request->input('est_renouvelable', '0') == '1';

        // Vérifier unicité contrat actif/brouillon pour ce client
        $client = Client::withoutGlobalScope('entreprise')->find($validated['client_id']);
        $existingContrat = $client->contrats()
            ->whereIn('statut', ['brouillon', 'en_cours'])
            ->first();

        if ($existingContrat) {
            return back()->withInput()
                ->with('error', 'Ce client a déjà un contrat actif ou en brouillon (N° ' . $existingContrat->numero_contrat . ').');
        }

        // ============================================================
        // CALCULS FINANCIERS AUTOMATIQUES
        // ============================================================
        $prixParAgent = $validated['prix_par_agent'] ?? 0;
        $nombreAgents = $validated['nombre_agents_requis'] ?? 1;
        $tva = $validated['tva'] ?? 18;

        // Si prix_par_agent est fourni, on calcule le montant mensuel
        if ($prixParAgent > 0) {
            $validated['montant_mensuel_ht'] = $nombreAgents * $prixParAgent;
        } elseif (empty($validated['montant_mensuel_ht'])) {
            // Valeur par défaut si aucun montant n'est fourni
            $validated['montant_mensuel_ht'] = 0;
        }

        // Calculer montant total HT et TTC
        $validated['montant_mensuel_ttc'] = $validated['montant_mensuel_ht'] * (1 + $tva / 100);
        $validated['montant_annuel_ht']   = $validated['montant_mensuel_ht'] * 12;
        $validated['montant_total_ht']    = $validated['montant_mensuel_ht'];
        $validated['entreprise_id']        = $entrepriseId;

        // Numéro auto
        if (empty($validated['numero_contrat'])) {
            $validated['numero_contrat'] = $this->genererNumeroContrat($entrepriseId);
        }

        // Créateur
        $validated['cree_par'] = $this->getCurrentEmployeId();

        try {
            $contrat = ContratPrestation::create($validated);

            return redirect()->route('admin.entreprise.contrats.show', $contrat->id)
                ->with('success', 'Contrat créé avec succès.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }

    // ═══════════════════════════════════════════════════════════════════
    //  AFFICHAGE
    // ═══════════════════════════════════════════════════════════════════
    public function show($id)
    {
        $entrepriseId = $this->getEntrepriseId();

        $contrat = ContratPrestation::with([
            'client',
            'sites.site',
            'factures' => fn($q) => $q->orderByDesc('created_at')->limit(5),
            'affectations.employe',
        ])
            ->where('entreprise_id', $entrepriseId)
            ->findOrFail($id);

        $statsContrat = [
            'sites_count'   => $contrat->sites()->count(),
            'agents_count'  => $contrat->affectations()->where('statut', 'en_cours')->count(),
            'factures_count' => $contrat->factures()->count(),
            'montant_total' => $contrat->factures()->where('statut', 'payee')->sum('montant_paye'),
        ];

        return view('admin.entreprise.contrats.show', compact('contrat', 'statsContrat'));
    }

    // ═══════════════════════════════════════════════════════════════════
    //  ÉDITION
    // ═══════════════════════════════════════════════════════════════════
    public function edit($id)
    {
        $entrepriseId = $this->getEntrepriseId();

        $contrat = ContratPrestation::where('entreprise_id', $entrepriseId)->findOrFail($id);

        // Tous les clients actifs + le client actuel du contrat (même s'il a déjà un contrat)
        $clients = Client::withoutGlobalScope('entreprise')
            ->where('entreprise_id', $entrepriseId)
            ->where('est_actif', true)
            ->where(function ($q) use ($contrat) {
                $q->whereDoesntHave('contrats', function ($sub) use ($contrat) {
                    $sub->whereIn('statut', ['brouillon', 'en_cours'])
                        ->where('id', '!=', $contrat->id);
                })
                    ->orWhere('id', $contrat->client_id);
            })
            ->orderBy('nom')
            ->get();

        return view('admin.entreprise.contrats.edit', compact('contrat', 'clients'));
    }

    /**
     * ✅ UPDATE — Règles clés :
     * - est_renouvelable : input() == '1' (hidden + checkbox)
     * - Résiliation : champs requis uniquement si statut = resilie
     * - Si statut != resilie : on efface date_resiliation et motif_resiliation
     * - Calculs financiers automatiques
     */
    public function update(Request $request, $id)
    {
        $entrepriseId = $this->getEntrepriseId();
        $contrat = ContratPrestation::where('entreprise_id', $entrepriseId)->findOrFail($id);

        $validated = $request->validate([
            'client_id'                => 'required|exists:clients,id',
            'intitule'                 => 'required|string|max:255',
            'numero_contrat'           => 'required|string|unique:contrats_prestation,numero_contrat,' . $id,
            'date_debut'               => 'required|date',
            'date_fin'                 => 'required|date|after:date_debut',
            'duree_preavis'            => 'nullable|integer|min:0',
            'montant_mensuel_ht'       => 'nullable|numeric|min:0',
            'prix_par_agent'           => 'nullable|numeric|min:0',
            'tva'                      => 'nullable|numeric|min:0|max:100',
            'periodicite_facturation'  => 'required|in:mensuel,trimestriel,semestriel,annuel',
            'nombre_agents_requis'     => 'required|integer|min:1',
            'nombre_sites'             => 'nullable|integer|min:1',
            'description_prestation'   => 'nullable|string',
            'conditions_particulieres' => 'nullable|string',
            'statut'                   => 'required|in:brouillon,en_cours,suspendu,termine,resilie',
            'signataire_client_nom'    => 'nullable|string|max:255',
            'signataire_client_fonction' => 'nullable|string|max:255',
            'date_signature'           => 'nullable|date',
            // Résiliation : requis seulement si statut = resilie
            'date_resiliation'         => 'nullable|date|required_if:statut,resilie',
            'motif_resiliation'        => 'nullable|string|required_if:statut,resilie',
        ], $this->messages());

        // ✅ est_renouvelable
        $validated['est_renouvelable'] = $request->input('est_renouvelable', '0') == '1';

        // ============================================================
        // CALCULS FINANCIERS AUTOMATIQUES
        // ============================================================
        $prixParAgent = $validated['prix_par_agent'] ?? 0;
        $nombreAgents = $validated['nombre_agents_requis'] ?? 1;
        $tva = $validated['tva'] ?? 18;

        // Si prix_par_agent est fourni, on calcule le montant mensuel
        if ($prixParAgent > 0) {
            $validated['montant_mensuel_ht'] = $nombreAgents * $prixParAgent;
        } elseif (empty($validated['montant_mensuel_ht'])) {
            // Valeur par défaut si aucun montant n'est fourni
            $validated['montant_mensuel_ht'] = 0;
        }

        // Calculer montant total HT et TTC
        $validated['montant_mensuel_ttc'] = $validated['montant_mensuel_ht'] * (1 + $tva / 100);
        $validated['montant_annuel_ht']   = $validated['montant_mensuel_ht'] * 12;
        $validated['montant_total_ht']    = $validated['montant_mensuel_ht'];

        // ✅ Résiliation : si statut != resilie → on efface les champs résiliation
        if ($validated['statut'] !== 'resilie') {
            $validated['date_resiliation']  = null;
            $validated['motif_resiliation'] = null;
        } elseif (!empty($validated['date_resiliation'])) {
            // Si résilié, la date de fin = date de résiliation
            $validated['date_fin'] = $validated['date_resiliation'];
        }

        try {
            $contrat->update($validated);

            return redirect()->route('admin.entreprise.contrats.show', $contrat->id)
                ->with('success', 'Contrat mis à jour avec succès.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    // ═══════════════════════════════════════════════════════════════════
    //  SUPPRESSION
    // ═══════════════════════════════════════════════════════════════════
    public function destroy($id)
    {
        $entrepriseId = $this->getEntrepriseId();
        $contrat = ContratPrestation::where('entreprise_id', $entrepriseId)->findOrFail($id);

        if ($contrat->factures()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer : ce contrat possède des factures.');
        }
        if ($contrat->affectations()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer : des agents sont affectés à ce contrat.');
        }

        $contrat->delete();

        return redirect()->route('admin.entreprise.contrats.index')
            ->with('success', 'Contrat supprimé avec succès.');
    }

    // ═══════════════════════════════════════════════════════════════════
    //  CHANGER STATUT (action rapide)
    // ═══════════════════════════════════════════════════════════════════
    public function changerStatut(Request $request, $id)
    {
        $entrepriseId = $this->getEntrepriseId();

        $request->validate([
            'statut'           => 'required|in:brouillon,en_cours,suspendu,termine,resilie',
            'motif_resiliation' => 'nullable|string|required_if:statut,resilie',
            'date_resiliation' => 'nullable|date|required_if:statut,resilie',
        ]);

        $contrat = ContratPrestation::where('entreprise_id', $entrepriseId)->findOrFail($id);

        $data = ['statut' => $request->statut];

        if ($request->statut === 'resilie') {
            $data['motif_resiliation'] = $request->motif_resiliation;
            $data['date_resiliation']  = $request->date_resiliation;
            if ($request->date_resiliation) {
                $data['date_fin'] = $request->date_resiliation;
            }
        }

        $contrat->update($data);

        return back()->with('success', 'Statut du contrat mis à jour.');
    }

    // ═══════════════════════════════════════════════════════════════════
    //  DUPLIQUER
    // ═══════════════════════════════════════════════════════════════════
    public function dupliquer($id)
    {
        $entrepriseId = $this->getEntrepriseId();
        $contrat = ContratPrestation::where('entreprise_id', $entrepriseId)->findOrFail($id);

        $nouveau = $contrat->replicate();
        $nouveau->numero_contrat  = $this->genererNumeroContrat($entrepriseId);
        $nouveau->statut          = 'brouillon';
        $nouveau->date_signature  = null;
        $nouveau->date_debut      = null;
        $nouveau->date_fin        = null;
        $nouveau->date_resiliation = null;
        $nouveau->motif_resiliation = null;
        $nouveau->save();

        return redirect()->route('admin.entreprise.contrats.edit', $nouveau->id)
            ->with('success', 'Contrat dupliqué en brouillon. Vous pouvez le modifier.');
    }

    // ═══════════════════════════════════════════════════════════════════
    //  GESTION DES SITES
    // ═══════════════════════════════════════════════════════════════════
    public function ajouterSite(Request $request, $id)
    {
        $entrepriseId = $this->getEntrepriseId();

        $request->validate([
            'site_client_id'     => 'required|exists:sites_clients,id',
            'nombre_agents_site' => 'required|integer|min:1',
            'consignes_site'     => 'nullable|string',
        ]);

        $contrat = ContratPrestation::where('entreprise_id', $entrepriseId)->findOrFail($id);

        if ($contrat->sites()->where('site_client_id', $request->site_client_id)->exists()) {
            return back()->with('error', 'Ce site est déjà associé au contrat.');
        }

        $contrat->sites()->create([
            'site_client_id'     => $request->site_client_id,
            'nombre_agents_site' => $request->nombre_agents_site,
            'consignes_site'     => $request->consignes_site,
        ]);

        return back()->with('success', 'Site ajouté au contrat.');
    }

    // ═══════════════════════════════════════════════════════════════════
    //  HELPERS PRIVÉS
    // ═══════════════════════════════════════════════════════════════════

    /**
     * Obtenir l'entreprise_id selon le guard actif.
     */
    private function getEntrepriseId(): ?int
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->estSuperAdmin() && session()->has('entreprise_id')) {
                return (int) session('entreprise_id');
            }
        }

        if (Auth::guard('employe')->check()) {
            $employe = Auth::guard('employe')->user();
            return $employe->entreprise_id ? (int) $employe->entreprise_id : null;
        }

        return null;
    }

    /**
     * Générer un numéro unique de contrat.
     */
    private function genererNumeroContrat(int $entrepriseId): string
    {
        $prefix  = 'CTR-' . date('Y') . '-';
        $dernier = ContratPrestation::where('entreprise_id', $entrepriseId)
            ->where('numero_contrat', 'like', "{$prefix}%")
            ->orderBy('numero_contrat', 'desc')
            ->first();

        $numero = $dernier
            ? (int) substr($dernier->numero_contrat, -4) + 1
            : 1;

        return $prefix . str_pad($numero, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Obtenir l'ID de l'employé connecté.
     */
    private function getCurrentEmployeId(): ?int
    {
        if (Auth::guard('employe')->check()) {
            return Auth::guard('employe')->user()->id;
        }
        if (Auth::guard('web')->check() && Auth::user()->employe_id) {
            return Auth::user()->employe_id;
        }
        return null;
    }

    /**
     * Messages de validation en français.
     */
    private function messages(): array
    {
        return [
            'client_id.required'               => 'Veuillez sélectionner un client.',
            'client_id.exists'                 => 'Le client sélectionné est invalide.',
            'intitule.required'                => "L'intitulé du contrat est obligatoire.",
            'numero_contrat.unique'            => 'Ce numéro de contrat est déjà utilisé.',
            'date_debut.required'              => 'La date de début est obligatoire.',
            'date_fin.required'                => 'La date de fin est obligatoire.',
            'date_fin.after'                   => 'La date de fin doit être postérieure à la date de début.',
            'montant_mensuel_ht.required'      => 'Le montant mensuel HT est obligatoire.',
            'montant_mensuel_ht.min'           => 'Le montant ne peut pas être négatif.',
            'periodicite_facturation.required' => 'La périodicité de facturation est obligatoire.',
            'nombre_agents_requis.required'    => "Le nombre d'agents requis est obligatoire.",
            'nombre_agents_requis.min'         => "Le nombre d'agents doit être d'au moins 1.",
            'statut.required'                  => 'Le statut est obligatoire.',
            'date_resiliation.required_if'     => 'La date de résiliation est obligatoire pour un contrat résilié.',
            'motif_resiliation.required_if'    => 'Le motif de résiliation est obligatoire pour un contrat résilié.',
        ];
    }
}
