<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Entreprise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EntrepriseController extends Controller
{
    /**
     * Constructeur
     */
    public function __construct()
    {
        $this->middleware(['auth', 'superadmin']);
    }

    /**
     * Enregistrer une entreprise
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom_entreprise' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:entreprises,slug',
            'nom_commercial' => 'nullable|string|max:255',
            'forme_juridique' => 'nullable|string|max:100',
            'numero_registre' => 'nullable|string|max:50',
            'numeroIdentificationFiscale' => 'nullable|string|max:50',
            'numeroContribuable' => 'nullable|string|max:50',
            'email' => 'required|email',
            'telephone' => 'required|string|max:20',
            'telephone_alternatif' => 'nullable|string|max:20',
            'adresse' => 'nullable|string',
            'ville' => 'nullable|string|max:100',
            'pays' => 'nullable|string|max:100',
            'code_postal' => 'nullable|string|max:20',
            'nom_representant_legal' => 'nullable|string|max:255',
            'email_representant_legal' => 'nullable|email',
            'telephone_representant_legal' => 'nullable|string|max:20',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'formule' => 'required|in:essai,basic,standard,premium',
            'nombre_agents_max' => 'required|integer|min:1',
            'nombre_sites_max' => 'required|integer|min:1',
            'date_debut_contrat' => 'nullable|date',
            'date_fin_contrat' => 'nullable|date',
            'montant_mensuel' => 'nullable|numeric|min:0',
            'cycle_facturation' => 'nullable|in:mensuel,trimestriel,annuel',
            'est_active' => 'boolean',
            'est_en_essai' => 'boolean',
        ]);

        // Générer le slug si non fourni
        if (empty($validated['slug'])) {
            $validated['slug'] = \Str::slug($validated['nom_entreprise']);
        }

        // Gérer le logo
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $validated['logo'] = $path;
        }

        $validated['est_active'] = $validated['est_active'] ?? true;
        $validated['est_en_essai'] = $validated['est_en_essai'] ?? false;

        $entreprise = Entreprise::create($validated);

        return redirect()->route('admin.superadmin.entreprises.index')
            ->with('success', 'Entreprise créée avec succès.');
    }

    /**
     * Mettre à jour une entreprise
     */
    public function update(Request $request, $id)
    {
        $entreprise = Entreprise::findOrFail($id);

        $validated = $request->validate([
            'nom_entreprise' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:entreprises,slug,' . $id,
            'nom_commercial' => 'nullable|string|max:255',
            'forme_juridique' => 'nullable|string|max:100',
            'numero_registre' => 'nullable|string|max:50',
            'numeroIdentificationFiscale' => 'nullable|string|max:50',
            'numeroContribuable' => 'nullable|string|max:50',
            'email' => 'required|email',
            'telephone' => 'required|string|max:20',
            'telephone_alternatif' => 'nullable|string|max:20',
            'adresse' => 'nullable|string',
            'ville' => 'nullable|string|max:100',
            'pays' => 'nullable|string|max:100',
            'code_postal' => 'nullable|string|max:20',
            'nom_representant_legal' => 'nullable|string|max:255',
            'email_representant_legal' => 'nullable|email',
            'telephone_representant_legal' => 'nullable|string|max:20',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'formule' => 'required|in:essai,basic,standard,premium',
            'nombre_agents_max' => 'required|integer|min:1',
            'nombre_sites_max' => 'required|integer|min:1',
            'date_debut_contrat' => 'nullable|date',
            'date_fin_contrat' => 'nullable|date',
            'montant_mensuel' => 'nullable|numeric|min:0',
            'cycle_facturation' => 'nullable|in:mensuel,trimestriel,annuel',
            'est_active' => 'boolean',
            'est_en_essai' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        // Gérer le logo
        if ($request->hasFile('logo')) {
            // Supprimer l'ancien logo
            if ($entreprise->logo) {
                Storage::disk('public')->delete($entreprise->logo);
            }
            $path = $request->file('logo')->store('logos', 'public');
            $validated['logo'] = $path;
        }

        $entreprise->update($validated);

        return redirect()->route('admin.superadmin.entreprises.index')
            ->with('success', 'Entreprise mise à jour.');
    }

    /**
     * Supprimer une entreprise
     */
    public function destroy($id)
    {
        $entreprise = Entreprise::findOrFail($id);

        // Vérifier si l'entreprise a des données关联
        if (
            $entreprise->employes()->count() > 0 ||
            $entreprise->clients()->count() > 0 ||
            $entreprise->contratsPrestation()->count() > 0
        ) {
            return back()->with('error', 'Impossible de supprimer une entreprise avec des données关联.');
        }

        // Supprimer le logo
        if ($entreprise->logo) {
            Storage::disk('public')->delete($entreprise->logo);
        }

        $entreprise->delete();

        return redirect()->route('admin.superadmin.entreprises.index')
            ->with('success', 'Entreprise supprimée.');
    }

    /**
     * Activer une entreprise
     */
    public function activate($id)
    {
        $entreprise = Entreprise::findOrFail($id);
        $entreprise->update(['est_active' => true]);

        return back()->with('success', 'Entreprise activée.');
    }

    /**
     * Désactiver une entreprise
     */
    public function deactivate($id)
    {
        $entreprise = Entreprise::findOrFail($id);
        $entreprise->update(['est_active' => false]);

        return back()->with('success', 'Entreprise désactivée.');
    }

    /**
     * Mettre en période d'essai
     */
    public function mettreEnEssai(Request $request, $id)
    {
        $entreprise = Entreprise::findOrFail($id);

        $validated = $request->validate([
            'date_fin_essai' => 'required|date|after:today',
        ]);

        $entreprise->update([
            'est_en_essai' => true,
            'date_fin_essai' => $validated['date_fin_essai'],
        ]);

        return back()->with('success', 'Entreprise mise en période d\'essai.');
    }

    /**
     * Souscrire à une formule
     */
    public function subscribe(Request $request, $id)
    {
        $entreprise = Entreprise::findOrFail($id);

        $validated = $request->validate([
            'formule' => 'required|in:basic,standard,premium',
            'date_fin_contrat' => 'required|date|after:today',
            'montant_mensuel' => 'required|numeric|min:0',
            'cycle_facturation' => 'required|in:mensuel,trimestriel,annuel',
        ]);

        $entreprise->update([
            'formule' => $validated['formule'],
            'date_debut_contrat' => now(),
            'date_fin_contrat' => $validated['date_fin_contrat'],
            'montant_mensuel' => $validated['montant_mensuel'],
            'cycle_facturation' => $validated['cycle_facturation'],
            'est_en_essai' => false,
            'est_active' => true,
        ]);

        return back()->with('success', 'Abonnement souscrit.');
    }

    /**
     * Statistiques d'une entreprise
     */
    public function statistiques($id)
    {
        $entreprise = Entreprise::withCount(['employes', 'clients', 'contratsPrestation', 'factures'])
            ->findOrFail($id);

        return view('admin.superadmin.entreprises.statistiques', compact('entreprise'));
    }

    /**
     * Exporter les données d'une entreprise
     */
    public function exporter($id)
    {
        return back()->with('info', 'Export en cours de développement.');
    }
}
