<?php

namespace App\Http\Controllers\Entreprise;

use App\Http\Controllers\Controller;
use App\Models\Employe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EmployeController extends Controller
{
    /**
     * Constructeur
     */
    public function __construct()
    {
        $this->middleware(['auth', 'entreprise']);
    }

    /**
     * Liste des employés
     */
    public function index(Request $request)
    {
        $entrepriseId = Auth::user()->entreprise_id;

        $query = Employe::where('entreprise_id', $entrepriseId);

        if ($request->filled('categorie')) {
            $query->where('categorie', $request->categorie);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $employes = $query->orderBy('nom')->paginate(15);

        $stats = [
            'total' => Employe::where('entreprise_id', $entrepriseId)->count(),
            'actifs' => Employe::where('entreprise_id', $entrepriseId)->where('est_actif', true)->where('statut', 'en_poste')->count(),
            'en_conge' => Employe::where('entreprise_id', $entrepriseId)->where('statut', 'conge')->count(),
            'disponibles' => Employe::where('entreprise_id', $entrepriseId)->where('disponible', true)->count(),
        ];

        return view('admin.entreprise.employes.index', compact('employes', 'stats'));
    }

    /**
     * Créer un employé
     */
    public function create()
    {
        return view('admin.entreprise.employes.create');
    }

    /**
     * Enregistrer un employé
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'matricule' => 'nullable|string|max:50|unique:employes,matricule',
            'civilite' => 'required|in:M,Mme,Mlle',
            'nom' => 'required|string|max:255',
            'prenoms' => 'required|string|max:255',
            'email' => 'required|email|unique:employes,email',
            'telephone' => 'required|string|max:20',
            'telephone_urgence' => 'nullable|string|max:20',
            'cni' => 'nullable|string|max:50',
            'date_naissance' => 'nullable|date',
            'lieu_naissance' => 'nullable|string|max:255',
            'adresse' => 'nullable|string',
            'categorie' => 'required|in:direction,supervision,controle,agent',
            'poste' => 'required|string',
            'type_contrat' => 'required|in:cdi,cdd,stage,prestation',
            'date_embauche' => 'required|date',
            'date_fin_contrat' => 'nullable|date',
            'salaire_base' => 'nullable|numeric|min:0',
            'numero_cnss' => 'nullable|string|max:50',
        ]);

        $validated['entreprise_id'] = Auth::user()->entreprise_id;
        $validated['est_actif'] = true;
        $validated['statut'] = 'en_poste';
        $validated['disponible'] = true;

        Employe::create($validated);

        return redirect()->route('admin.entreprise.employes.index')
            ->with('success', 'Employé créé avec succès.');
    }

    /**
     * Voir un employé
     */
    public function show($id)
    {
        $employe = Employe::with(['entreprise', 'affectations.site', 'pointages', 'conges', 'paies'])
            ->where('entreprise_id', Auth::user()->entreprise_id)
            ->findOrFail($id);

        return view('admin.entreprise.employes.show', compact('employe'));
    }

    /**
     * Modifier un employé
     */
    public function edit($id)
    {
        $employe = Employe::where('entreprise_id', Auth::user()->entreprise_id)
            ->findOrFail($id);

        return view('admin.entreprise.employes.edit', compact('employe'));
    }

    /**
     * Mettre à jour un employé
     */
    public function update(Request $request, $id)
    {
        $employe = Employe::where('entreprise_id', Auth::user()->entreprise_id)
            ->findOrFail($id);

        $validated = $request->validate([
            'civilite' => 'required|in:M,Mme,Mlle',
            'nom' => 'required|string|max:255',
            'prenoms' => 'required|string|max:255',
            'email' => 'required|email|unique:employes,email,' . $id,
            'telephone' => 'required|string|max:20',
            'telephone_urgence' => 'nullable|string|max:20',
            'cni' => 'nullable|string|max:50',
            'date_naissance' => 'nullable|date',
            'lieu_naissance' => 'nullable|string|max:255',
            'adresse' => 'nullable|string',
            'categorie' => 'required|in:direction,supervision,controle,agent',
            'poste' => 'required|string',
            'type_contrat' => 'required|in:cdi,cdd,stage,prestation',
            'date_embauche' => 'required|date',
            'date_fin_contrat' => 'nullable|date',
            'salaire_base' => 'nullable|numeric|min:0',
            'numero_cnss' => 'nullable|string|max:50',
            'est_actif' => 'boolean',
            'disponible' => 'boolean',
            'statut' => 'required|in:en_poste,conge,suspendu,licencie',
        ]);

        $employe->update($validated);

        return redirect()->route('admin.entreprise.employes.index')
            ->with('success', 'Employé mis à jour.');
    }

    /**
     * Supprimer un employé
     */
    public function destroy($id)
    {
        $employe = Employe::where('entreprise_id', Auth::user()->entreprise_id)
            ->findOrFail($id);

        $employe->delete();

        return redirect()->route('admin.entreprise.employes.index')
            ->with('success', 'Employé supprimé.');
    }

    /**
     * Mettre en congés
     */
    public function mettreEnConge(Request $request, $id)
    {
        $employe = Employe::where('entreprise_id', Auth::user()->entreprise_id)
            ->findOrFail($id);

        $employe->update(['statut' => 'conge', 'disponible' => false]);

        return back()->with('success', 'Employé mis en congés.');
    }

    /**
     * Reprendre le travail
     */
    public function reprendre($id)
    {
        $employe = Employe::where('entreprise_id', Auth::user()->entreprise_id)
            ->findOrFail($id);

        $employe->update(['statut' => 'en_poste', 'disponible' => true]);

        return back()->with('success', 'Employé a repris le travail.');
    }

    /**
     * Liste des agents disponibles
     */
    public function disponibles()
    {
        $employes = Employe::where('entreprise_id', Auth::user()->entreprise_id)
            ->where('disponible', true)
            ->where('est_actif', true)
            ->where('categorie', 'agent')
            ->get();

        return response()->json($employes);
    }
}
