<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Abonnement;
use App\Models\Entreprise;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AbonnementController extends Controller
{
    /**
     * Constructeur
     */
    public function __construct()
    {
        $this->middleware(['auth', 'superadmin']);
    }

    /**
     * Liste des abonnements
     * Affiche tous les abonnements avec leurs entreprises liées
     */
    public function index()
    {
        $abonnements = Abonnement::withCount('entreprises')
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'total' => $abonnements->count(),
            'actifs' => $abonnements->where('est_active', true)->count(),
            'en_essai' => $abonnements->where('est_en_essai', true)->count(),
            'inactifs' => $abonnements->where('est_active', false)->count(),
            'expirés' => $abonnements->where('statut', 'expire')->count(),
            'revenu_mensuel' => $abonnements->where('est_active', true)->sum('montant_mensuel'),
            'entreprises_total' => $abonnements->sum('entreprises_count'),
        ];

        return view('admin.superadmin.abonnements.index', compact('abonnements', 'stats'));
    }

    /**
     * Formulaire de création d'un abonnement
     */
    public function create()
    {
        $entreprises = Entreprise::orderBy('nom_entreprise')->get();
        $formules = Abonnement::FORMULES;
        $cycles = Abonnement::CYCLES;

        return view('admin.superadmin.abonnements.create', compact('entreprises', 'formules', 'cycles'));
    }

    /**
     * Enregistrer un nouvel abonnement
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'formule' => 'required|string|' . Rule::in(array_keys(Abonnement::FORMULES)),
            'description' => 'nullable|string|max:500',

            // Limites
            'nombre_agents_max' => 'nullable|integer|min:0',
            'nombre_sites_max' => 'nullable|integer|min:0',
            'nombre_agents_inclus' => 'nullable|integer|min:0',

            // Dates
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after:date_debut',
            'date_fin_essai' => 'nullable|date|after:date_debut',

            // Facturation
            'montant_mensuel' => 'required|numeric|min:0',
            'montant_total' => 'nullable|numeric|min:0',
            'cycle_facturation' => 'nullable|string|' . Rule::in(array_keys(Abonnement::CYCLES)),
            'tarif_agents_supplementaires' => 'nullable|numeric|min:0',

            // Statut
            'est_active' => 'boolean',
            'est_en_essai' => 'boolean',
            'est_renouvele_auto' => 'boolean',
            'statut' => 'nullable|string|' . Rule::in(array_keys(Abonnement::STATUTS)),

            // Fonctionnalités
            'modules_accessibles' => 'nullable|array',
            'limite_utilisateurs' => 'nullable|integer|min:1',
            'limite_stockage_go' => 'nullable|integer|min:1',

            // Paiement
            'mode_paiement' => 'nullable|string|in:virement,mobile_money,cheque',
            'reference_paiement' => 'nullable|string|max:255',

            // Notes
            'notes' => 'nullable|string|max:1000',

            // Entreprise à lier (optionnel)
            'entreprise_id' => 'nullable|exists:entreprises,id',
        ]);

        // Créer l'abonnement
        $abonnement = Abonnement::create([
            'formule' => $validated['formule'],
            'description' => $validated['description'] ?? null,
            'nombre_agents_max' => $validated['nombre_agents_max'] ?? 0,
            'nombre_sites_max' => $validated['nombre_sites_max'] ?? 0,
            'nombre_agents_inclus' => $validated['nombre_agents_inclus'] ?? $validated['nombre_agents_max'],
            'date_debut' => $validated['date_debut'],
            'date_fin' => $validated['date_fin'] ?? null,
            'date_fin_essai' => $validated['date_fin_essai'] ?? null,
            'montant_mensuel' => $validated['montant_mensuel'],
            'montant_total' => $validated['montant_total'] ?? null,
            'cycle_facturation' => $validated['cycle_facturation'] ?? 'mensuel',
            'tarif_agents_supplementaires' => $validated['tarif_agents_supplementaires'] ?? null,
            'est_active' => $validated['est_active'] ?? true,
            'est_en_essai' => $validated['est_en_essai'] ?? false,
            'est_renouvele_auto' => $validated['est_renouvele_auto'] ?? false,
            'statut' => $validated['statut'] ?? 'actif',
            'modules_accessibles' => $validated['modules_accessibles'] ?? null,
            'limite_utilisateurs' => $validated['limite_utilisateurs'] ?? null,
            'limite_stockage_go' => $validated['limite_stockage_go'] ?? null,
            'mode_paiement' => $validated['mode_paiement'] ?? null,
            'reference_paiement' => $validated['reference_paiement'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        // Assigner à une entreprise si spécifié
        if (!empty($validated['entreprise_id'])) {
            $entreprise = Entreprise::find($validated['entreprise_id']);
            if ($entreprise) {
                $entreprise->update(['abonnement_id' => $abonnement->id]);
            }
        }

        return redirect()->route('admin.superadmin.abonnements.index')
            ->with('success', 'Abonnement créé avec succès.');
    }

    /**
     * Voir un abonnement
     */
    public function show($id)
    {
        $abonnement = Abonnement::with(['entreprises', 'factures'])->findOrFail($id);

        return view('admin.superadmin.abonnements.show', compact('abonnement'));
    }

    /**
     * Formulaire de modification
     */
    public function edit($id)
    {
        $abonnement = Abonnement::findOrFail($id);
        $entreprises = Entreprise::orderBy('nom_entreprise')->get();
        $formules = Abonnement::FORMULES;
        $cycles = Abonnement::CYCLES;

        return view('admin.superadmin.abonnements.edit', compact('abonnement', 'entreprises', 'formules', 'cycles'));
    }

    /**
     * Mettre à jour un abonnement
     */
    public function update(Request $request, $id)
    {
        $abonnement = Abonnement::findOrFail($id);

        $validated = $request->validate([
            'formule' => 'required|string|' . Rule::in(array_keys(Abonnement::FORMULES)),
            'description' => 'nullable|string|max:500',

            // Limites
            'nombre_agents_max' => 'nullable|integer|min:0',
            'nombre_sites_max' => 'nullable|integer|min:0',
            'nombre_agents_inclus' => 'nullable|integer|min:0',

            // Dates
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after:date_debut',
            'date_fin_essai' => 'nullable|date|after:date_debut',

            // Facturation
            'montant_mensuel' => 'required|numeric|min:0',
            'montant_total' => 'nullable|numeric|min:0',
            'cycle_facturation' => 'nullable|string|' . Rule::in(array_keys(Abonnement::CYCLES)),
            'tarif_agents_supplementaires' => 'nullable|numeric|min:0',

            // Statut
            'est_active' => 'boolean',
            'est_en_essai' => 'boolean',
            'est_renouvele_auto' => 'boolean',
            'statut' => 'nullable|string|' . Rule::in(array_keys(Abonnement::STATUTS)),

            // Fonctionnalités
            'modules_accessibles' => 'nullable|array',
            'limite_utilisateurs' => 'nullable|integer|min:1',
            'limite_stockage_go' => 'nullable|integer|min:1',

            // Paiement
            'mode_paiement' => 'nullable|string|in:virement,mobile_money,cheque',
            'reference_paiement' => 'nullable|string|max:255',

            // Notes
            'notes' => 'nullable|string|max:1000',
        ]);

        $abonnement->update($validated);

        return redirect()->route('admin.superadmin.abonnements.index')
            ->with('success', 'Abonnement mis à jour avec succès.');
    }

    /**
     * Supprimer un abonnement
     */
    public function destroy($id)
    {
        $abonnement = Abonnement::findOrFail($id);

        // Détacher toutes les entreprises liées
        Entreprise::where('abonnement_id', $abonnement->id)->update(['abonnement_id' => null]);

        $abonnement->delete();

        return redirect()->route('admin.superadmin.abonnements.index')
            ->with('success', 'Abonnement supprimé avec succès.');
    }

    /**
     * Assigner un abonnement à une entreprise
     */
    public function assigner(Request $request, $id)
    {
        $abonnement = Abonnement::findOrFail($id);

        $validated = $request->validate([
            'entreprise_id' => 'required|exists:entreprises,id',
        ]);

        $entreprise = Entreprise::findOrFail($validated['entreprise_id']);

        // Vérifier si l'entreprise a déjà un abonnement
        if ($entreprise->abonnement_id && $entreprise->abonnement_id != $abonnement->id) {
            return back()->with('error', 'Cette entreprise a déjà un abonnement actif.');
        }

        $entreprise->update(['abonnement_id' => $abonnement->id]);

        return back()->with('success', 'Abonnement assigné à ' . $entreprise->nom_entreprise . ' avec succès.');
    }

    /**
     * Retirer l'abonnement d'une entreprise
     */
    public function retirer($id, $entrepriseId)
    {
        $abonnement = Abonnement::findOrFail($id);
        $entreprise = Entreprise::findOrFail($entrepriseId);

        if ($entreprise->abonnement_id != $abonnement->id) {
            return back()->with('error', 'Cette entreprise n\'est pas associée à cet abonnement.');
        }

        $entreprise->update(['abonnement_id' => null]);

        return back()->with('success', 'Abonnement retiré de ' . $entreprise->nom_entreprise . ' avec succès.');
    }

    /**
     * Renouveler un abonnement
     */
    public function renew(Request $request, $id)
    {
        $abonnement = Abonnement::findOrFail($id);

        $validated = $request->validate([
            'date_fin' => 'required|date|after:today',
            'montant_mensuel' => 'required|numeric|min:0',
            'cycle_facturation' => 'nullable|string|' . Rule::in(array_keys(Abonnement::CYCLES)),
        ]);

        $abonnement->update([
            'date_fin' => $validated['date_fin'],
            'montant_mensuel' => $validated['montant_mensuel'],
            'cycle_facturation' => $validated['cycle_facturation'] ?? $abonnement->cycle_facturation,
            'est_active' => true,
            'est_en_essai' => false,
            'statut' => 'actif',
        ]);

        return redirect()->route('admin.superadmin.abonnements.index')
            ->with('success', 'Abonnement renouvelé avec succès.');
    }

    /**
     * Suspendre un abonnement
     */
    public function suspend($id)
    {
        $abonnement = Abonnement::findOrFail($id);
        $abonnement->suspendre();

        return redirect()->route('admin.superadmin.abonnements.index')
            ->with('success', 'Abonnement suspendu.');
    }

    /**
     * Activer un abonnement
     */
    public function activate($id)
    {
        $abonnement = Abonnement::findOrFail($id);
        $abonnement->activer();

        return redirect()->route('admin.superadmin.abonnements.index')
            ->with('success', 'Abonnement activé.');
    }

    /**
     * Mettre en période d'essai
     */
    public function mettreEnEssai(Request $request, $id)
    {
        $abonnement = Abonnement::findOrFail($id);

        $validated = $request->validate([
            'date_fin_essai' => 'required|date|after:today',
        ]);

        $abonnement->mettreEnEssai($validated['date_fin_essai']);

        return redirect()->route('admin.superadmin.abonnements.index')
            ->with('success', 'Abonnement mis en période d\'essai.');
    }

    /**
     * Résilier un abonnement
     */
    public function resilier($id)
    {
        $abonnement = Abonnement::findOrFail($id);
        $abonnement->resilier();

        return redirect()->route('admin.superadmin.abonnements.index')
            ->with('success', 'Abonnement résilié.');
    }
}
