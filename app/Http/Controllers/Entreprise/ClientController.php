<?php

namespace App\Http\Controllers\Entreprise;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    /**
     * Obtenir l'entreprise_id selon le type d'utilisateur connecté.
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
     * Récupérer un client en bypassant le GlobalScope.
     */
    private function findClient(int $id, int $entrepriseId): Client
    {
        return Client::withoutGlobalScope('entreprise')
            ->where('id', $id)
            ->where('entreprise_id', $entrepriseId)
            ->firstOrFail();
    }

    /**
     * Liste des clients
     */
    public function index(Request $request)
    {
        $entrepriseId = $this->getEntrepriseId();

        if (!$entrepriseId) {
            return redirect()->route('superadmin.dashboard')
                ->with('error', 'Aucune entreprise sélectionnée.');
        }

        $query = Client::withoutGlobalScope('entreprise')
            ->where('entreprise_id', $entrepriseId);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                    ->orWhere('prenoms', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('telephone', 'like', "%{$search}%")
                    ->orWhere('raison_sociale', 'like', "%{$search}%")
                    ->orWhere('nif', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type_client')) {
            $query->where('type_client', $request->type_client);
        }

        if ($request->filled('est_actif')) {
            $query->where('est_actif', $request->est_actif == '1');
        }

        $clients = $query->orderBy('created_at', 'desc')->paginate(15);

        $stats = [
            'total'        => Client::withoutGlobalScope('entreprise')->where('entreprise_id', $entrepriseId)->count(),
            'actifs'       => Client::withoutGlobalScope('entreprise')->where('entreprise_id', $entrepriseId)->where('est_actif', true)->count(),
            'particuliers' => Client::withoutGlobalScope('entreprise')->where('entreprise_id', $entrepriseId)->where('type_client', 'particulier')->count(),
            'entreprises'  => Client::withoutGlobalScope('entreprise')->where('entreprise_id', $entrepriseId)->whereIn('type_client', ['entreprise', 'institution'])->count(),
        ];

        return view('admin.entreprise.clients.index', compact('clients', 'stats'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        return view('admin.entreprise.clients.create');
    }

    /**
     * Enregistrer un nouveau client
     */
    public function store(Request $request)
    {
        $entrepriseId = $this->getEntrepriseId();

        if (!$entrepriseId) {
            return back()->with('error', 'Aucune entreprise associée.');
        }

        // ✅ input() == '1' et non has() — car hidden value="0" + checkbox value="1"
        $creerCompte = $request->input('creer_compte') == '1';

        $rules = [
            'type_client'                => 'required|in:particulier,entreprise,institution',
            'nom'                        => 'nullable|string|max:100',
            'prenoms'                    => 'nullable|string|max:150',
            'date_naissance'             => 'nullable|date',
            'raison_sociale'             => 'nullable|string|max:255',
            'nif'                        => 'nullable|string|max:100',
            'rc'                         => 'nullable|string|max:100',
            'representant_nom'           => 'nullable|string|max:255',
            'representant_prenom'        => 'nullable|string|max:255',
            'representant_fonction'      => 'nullable|string|max:255',
            'email'                      => 'nullable|email|max:255',
            'telephone'                  => 'required|string|max:50',
            'telephone_secondaire'       => 'nullable|string|max:50',
            'contact_principal_nom'      => 'nullable|string|max:255',
            'contact_principal_fonction' => 'nullable|string|max:255',
            'contact_email'              => 'nullable|email|max:255',
            'adresse'                    => 'required|string',
            'ville'                      => 'nullable|string|max:100',
            'pays'                       => 'nullable|string|max:100',
            'notes'                      => 'nullable|string',
        ];

        if ($creerCompte) {
            $rules['email']    = 'required|email|max:255';
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $validated = $request->validate($rules);

        $validated['est_actif']     = $request->input('est_actif', '0') == '1';
        $validated['entreprise_id'] = $entrepriseId;

        if ($creerCompte && $request->filled('password')) {
            $validated['password'] = bcrypt($request->password);
        } else {
            unset($validated['password']);
        }

        try {
            Client::withoutGlobalScope('entreprise')->create($validated);

            return redirect()->route('admin.entreprise.clients.index')
                ->with('success', 'Client créé avec succès.');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }

    /**
     * Afficher un client
     */
    public function show($id)
    {
        $entrepriseId = $this->getEntrepriseId();

        if (!$entrepriseId) {
            return redirect()->route('superadmin.dashboard')
                ->with('error', 'Aucune entreprise sélectionnée.');
        }

        $client = Client::withoutGlobalScope('entreprise')
            ->with(['sites', 'contrats', 'factures'])
            ->where('entreprise_id', $entrepriseId)
            ->findOrFail($id);

        return view('admin.entreprise.clients.show', compact('client'));
    }

    /**
     * Formulaire de modification
     */
    public function edit($id)
    {
        $entrepriseId = $this->getEntrepriseId();

        if (!$entrepriseId) {
            return redirect()->route('superadmin.dashboard')
                ->with('error', 'Aucune entreprise sélectionnée.');
        }

        $client = $this->findClient($id, $entrepriseId);

        return view('admin.entreprise.clients.edit', compact('client'));
    }

    /**
     * ✅ Mettre à jour un client
     *
     * RÈGLE MOT DE PASSE :
     * - Champ vide  → on ne touche PAS au password en base (unset)
     * - Champ rempli → on valide (min:8, confirmed) puis on hache
     *
     * Ainsi l'utilisateur peut modifier n'importe quel autre champ
     * sans être obligé de resaisir le mot de passe.
     */
    public function update(Request $request, $id)
    {
        $entrepriseId = $this->getEntrepriseId();

        if (!$entrepriseId) {
            return redirect()->route('superadmin.dashboard')
                ->with('error', 'Aucune entreprise sélectionnée.');
        }

        $client = $this->findClient($id, $entrepriseId);

        // ── Règles de base (sans password) ────────────────────────────────────
        $rules = [
            'type_client'                => 'required|in:particulier,entreprise,institution',
            'nom'                        => 'nullable|string|max:100',
            'prenoms'                    => 'nullable|string|max:150',
            'date_naissance'             => 'nullable|date',
            'raison_sociale'             => 'nullable|string|max:255',
            'nif'                        => 'nullable|string|max:100',
            'rc'                         => 'nullable|string|max:100',
            'representant_nom'           => 'nullable|string|max:255',
            'representant_prenom'        => 'nullable|string|max:255',
            'representant_fonction'      => 'nullable|string|max:255',
            'email'                      => 'nullable|email|max:255',
            'telephone'                  => 'required|string|max:50',
            'telephone_secondaire'       => 'nullable|string|max:50',
            'contact_principal_nom'      => 'nullable|string|max:255',
            'contact_principal_fonction' => 'nullable|string|max:255',
            'contact_email'              => 'nullable|email|max:255',
            'adresse'                    => 'required|string',
            'ville'                      => 'nullable|string|max:100',
            'pays'                       => 'nullable|string|max:100',
            'notes'                      => 'nullable|string',
        ];

        // ✅ PASSWORD : on ajoute la règle SEULEMENT si le champ est rempli
        // Si vide → on ignore complètement, le mot de passe existant est conservé
        $passwordRempli = $request->filled('password');
        if ($passwordRempli) {
            $rules['password'] = 'string|min:8|confirmed';
        }

        $validated = $request->validate($rules, [
            'telephone.required' => 'Le téléphone principal est obligatoire.',
            'adresse.required'   => "L'adresse est obligatoire.",
            'type_client.required' => 'Le type de client est obligatoire.',
            'password.min'       => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        // ✅ est_actif : input() car hidden="0" + checkbox="1"
        $validated['est_actif'] = $request->input('est_actif', '0') == '1';

        // ✅ PASSWORD :
        // - Rempli  → hasher et inclure dans la mise à jour
        // - Vide    → retirer du tableau pour NE PAS écraser l'ancien mot de passe
        if ($passwordRempli) {
            $validated['password'] = bcrypt($request->password);
        } else {
            unset($validated['password']);
        }

        try {
            $client->update($validated);

            return redirect()->route('admin.entreprise.clients.index')
                ->with('success', 'Client mis à jour avec succès.');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    /**
     * Supprimer un client
     */
    public function destroy($id)
    {
        $entrepriseId = $this->getEntrepriseId();

        if (!$entrepriseId) {
            return redirect()->route('superadmin.dashboard')
                ->with('error', 'Aucune entreprise sélectionnée.');
        }

        $client = $this->findClient($id, $entrepriseId);
        $client->delete();

        return redirect()->route('admin.entreprise.clients.index')
            ->with('success', 'Client supprimé.');
    }

    /**
     * Sites du client
     */
    public function sites($id)
    {
        $entrepriseId = $this->getEntrepriseId();
        $client = $this->findClient($id, $entrepriseId);
        $client->load('sites');
        return view('admin.entreprise.clients.sites', compact('client'));
    }

    /**
     * Contrats du client
     */
    public function contrats($id)
    {
        $entrepriseId = $this->getEntrepriseId();
        $client = $this->findClient($id, $entrepriseId);
        $client->load('contrats');
        return view('admin.entreprise.clients.contrats', compact('client'));
    }

    /**
     * Factures du client
     */
    public function factures($id)
    {
        $entrepriseId = $this->getEntrepriseId();
        $client = $this->findClient($id, $entrepriseId);
        $client->load('factures');
        return view('admin.entreprise.clients.factures', compact('client'));
    }
}