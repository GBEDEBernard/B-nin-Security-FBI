<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Entreprise;
use Illuminate\Http\Request;

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
     */
    public function index()
    {
        $entreprises = Entreprise::withCount(['employes', 'clients', 'contratsPrestation'])
            ->orderBy('nom_entreprise')
            ->get();

        $stats = [
            'total' => $entreprises->count(),
            'actives' => $entreprises->where('est_active', true)->count(),
            'en_essai' => $entreprises->where('est_en_essai', true)->count(),
            'inactives' => $entreprises->where('est_active', false)->count(),
            'revenu_mensuel' => $entreprises->sum('montant_mensuel'),
        ];

        return view('admin.superadmin.abonnements.index', compact('entreprises', 'stats'));
    }

    /**
     * Créer un abonnement
     */
    public function create()
    {
        return view('admin.superadmin.abonnements.create');
    }

    /**
     * Enregistrer un abonnement
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom_entreprise' => 'required|string|max:255',
            'formule' => 'required|string',
            'date_debut_contrat' => 'required|date',
            'date_fin_contrat' => 'nullable|date',
            'montant_mensuel' => 'required|numeric|min:0',
            'nombre_agents_max' => 'nullable|integer',
            'nombre_sites_max' => 'nullable|integer',
        ]);

        $entreprise = Entreprise::create([
            'nom_entreprise' => $validated['nom_entreprise'],
            'formule' => $validated['formule'],
            'date_debut_contrat' => $validated['date_debut_contrat'],
            'date_fin_contrat' => $validated['date_fin_contrat'] ?? null,
            'montant_mensuel' => $validated['montant_mensuel'],
            'nombre_agents_max' => $validated['nombre_agents_max'] ?? null,
            'nombre_sites_max' => $validated['nombre_sites_max'] ?? null,
            'est_active' => true,
            'est_en_essai' => false,
        ]);

        return redirect()->route('admin.superadmin.abonnements.index')
            ->with('success', 'Abonnement créé avec succès.');
    }

    /**
     * Voir un abonnement
     */
    public function show($id)
    {
        $entreprise = Entreprise::with(['employes', 'clients', 'contratsPrestation', 'factures'])
            ->findOrFail($id);

        return view('admin.superadmin.abonnements.show', compact('entreprise'));
    }

    /**
     * Modifier un abonnement
     */
    public function edit($id)
    {
        $entreprise = Entreprise::findOrFail($id);
        return view('admin.superadmin.abonnements.edit', compact('entreprise'));
    }

    /**
     * Mettre à jour un abonnement
     */
    public function update(Request $request, $id)
    {
        $entreprise = Entreprise::findOrFail($id);

        $validated = $request->validate([
            'formule' => 'required|string',
            'date_debut_contrat' => 'required|date',
            'date_fin_contrat' => 'nullable|date',
            'montant_mensuel' => 'required|numeric|min:0',
            'nombre_agents_max' => 'nullable|integer',
            'nombre_sites_max' => 'nullable|integer',
            'est_active' => 'boolean',
            'est_en_essai' => 'boolean',
        ]);

        $entreprise->update($validated);

        return redirect()->route('admin.superadmin.abonnements.index')
            ->with('success', 'Abonnement mis à jour avec succès.');
    }

    /**
     * Supprimer un abonnement
     */
    public function destroy($id)
    {
        $entreprise = Entreprise::findOrFail($id);
        $entreprise->delete();

        return redirect()->route('admin.superadmin.abonnements.index')
            ->with('success', 'Abonnement supprimé avec succès.');
    }

    /**
     * Renouveler un abonnement
     */
    public function renew(Request $request, $id)
    {
        $entreprise = Entreprise::findOrFail($id);

        $validated = $request->validate([
            'nouvelle_date_fin' => 'required|date|after:today',
            'montant_mensuel' => 'required|numeric|min:0',
        ]);

        $entreprise->update([
            'date_fin_contrat' => $validated['nouvelle_date_fin'],
            'montant_mensuel' => $validated['montant_mensuel'],
            'est_active' => true,
            'est_en_essai' => false,
        ]);

        return redirect()->route('admin.superadmin.abonnements.index')
            ->with('success', 'Abonnement renouvelé avec succès.');
    }

    /**
     * Suspendre un abonnement
     */
    public function suspend($id)
    {
        $entreprise = Entreprise::findOrFail($id);
        $entreprise->update(['est_active' => false]);

        return redirect()->route('admin.superadmin.abonnements.index')
            ->with('success', 'Abonnement suspendu.');
    }

    /**
     * Activer un abonnement
     */
    public function activate($id)
    {
        $entreprise = Entreprise::findOrFail($id);
        $entreprise->update(['est_active' => true]);

        return redirect()->route('admin.superadmin.abonnements.index')
            ->with('success', 'Abonnement activé.');
    }
}
