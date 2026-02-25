<?php

namespace App\Http\Controllers\Entreprise;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Employe;
use App\Models\ContratPrestation;
use App\Models\SiteClient;
use App\Models\Affectation;
use App\Models\Facture;
use App\Models\Incident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    /**
     * Constructeur
     */
    public function __construct()
    {
        $this->middleware(['auth', 'entreprise']);
    }

    /**
     * Liste des clients
     */
    public function index(Request $request)
    {
        $entrepriseId = Auth::user()->entreprise_id;

        $query = Client::where('entreprise_id', $entrepriseId);

        if ($request->filled('type')) {
            $query->where('type_client', $request->type);
        }

        if ($request->filled('statut')) {
            $query->where('est_actif', $request->statut === 'active');
        }

        $clients = $query->orderBy('nom')->paginate(15);

        $stats = [
            'total' => Client::where('entreprise_id', $entrepriseId)->count(),
            'actifs' => Client::where('entreprise_id', $entrepriseId)->where('est_actif', true)->count(),
            'particuliers' => Client::where('entreprise_id', $entrepriseId)->where('type_client', 'particulier')->count(),
            'entreprises' => Client::where('entreprise_id', $entrepriseId)->where('type_client', 'entreprise')->count(),
        ];

        return view('admin.entreprise.clients.index', compact('clients', 'stats'));
    }

    /**
     * Créer un client
     */
    public function create()
    {
        return view('admin.entreprise.clients.create');
    }

    /**
     * Enregistrer un client
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type_client' => 'required|in:particulier,entreprise,institution',
            'nom' => 'required|string|max:255',
            'prenoms' => 'nullable|string|max:255',
            'raison_sociale' => 'nullable|string|max:255',
            'nif' => 'nullable|string|max:50',
            'rc' => 'nullable|string|max:50',
            'email' => 'required|email',
            'telephone' => 'required|string|max:20',
            'telephone_secondaire' => 'nullable|string|max:20',
            'contact_principal_nom' => 'nullable|string|max:255',
            'contact_principal_fonction' => 'nullable|string|max:255',
            'adresse' => 'nullable|string',
            'ville' => 'nullable|string|max:100',
            'pays' => 'nullable|string|max:100',
            'est_actif' => 'boolean',
        ]);

        $validated['entreprise_id'] = Auth::user()->entreprise_id;

        Client::create($validated);

        return redirect()->route('admin.entreprise.clients.index')
            ->with('success', 'Client créé avec succès.');
    }

    /**
     * Voir un client
     */
    public function show($id)
    {
        $client = Client::with(['sites', 'contrats', 'factures'])
            ->where('entreprise_id', Auth::user()->entreprise_id)
            ->findOrFail($id);

        return view('admin.entreprise.clients.show', compact('client'));
    }

    /**
     * Modifier un client
     */
    public function edit($id)
    {
        $client = Client::where('entreprise_id', Auth::user()->entreprise_id)
            ->findOrFail($id);

        return view('admin.entreprise.clients.edit', compact('client'));
    }

    /**
     * Mettre à jour un client
     */
    public function update(Request $request, $id)
    {
        $client = Client::where('entreprise_id', Auth::user()->entreprise_id)
            ->findOrFail($id);

        $validated = $request->validate([
            'type_client' => 'required|in:particulier,entreprise,institution',
            'nom' => 'required|string|max:255',
            'prenoms' => 'nullable|string|max:255',
            'raison_sociale' => 'nullable|string|max:255',
            'nif' => 'nullable|string|max:50',
            'rc' => 'nullable|string|max:50',
            'email' => 'required|email',
            'telephone' => 'required|string|max:20',
            'telephone_secondaire' => 'nullable|string|max:20',
            'contact_principal_nom' => 'nullable|string|max:255',
            'contact_principal_fonction' => 'nullable|string|max:255',
            'adresse' => 'nullable|string',
            'ville' => 'nullable|string|max:100',
            'pays' => 'nullable|string|max:100',
            'est_actif' => 'boolean',
        ]);

        $client->update($validated);

        return redirect()->route('admin.entreprise.clients.index')
            ->with('success', 'Client mis à jour.');
    }

    /**
     * Supprimer un client
     */
    public function destroy($id)
    {
        $client = Client::where('entreprise_id', Auth::user()->entreprise_id)
            ->findOrFail($id);

        $client->delete();

        return redirect()->route('admin.entreprise.clients.index')
            ->with('success', 'Client supprimé.');
    }

    /**
     * Sites du client
     */
    public function sites($id)
    {
        $client = Client::with('sites')
            ->where('entreprise_id', Auth::user()->entreprise_id)
            ->findOrFail($id);

        return view('admin.entreprise.clients.sites', compact('client'));
    }

    /**
     * Contrats du client
     */
    public function contrats($id)
    {
        $client = Client::with('contrats')
            ->where('entreprise_id', Auth::user()->entreprise_id)
            ->findOrFail($id);

        return view('admin.entreprise.clients.contrats', compact('client'));
    }

    /**
     * Factures du client
     */
    public function factures($id)
    {
        $client = Client::with('factures')
            ->where('entreprise_id', Auth::user()->entreprise_id)
            ->findOrFail($id);

        return view('admin.entreprise.clients.factures', compact('client'));
    }
}
