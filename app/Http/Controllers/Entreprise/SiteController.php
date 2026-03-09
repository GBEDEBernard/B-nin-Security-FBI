<?php

namespace App\Http\Controllers\Entreprise;

use App\Http\Controllers\Controller;
use App\Models\SiteClient;
use App\Models\Client;
use App\Models\ContratPrestation;
use App\Models\Affectation;
use App\Models\Employe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiteController extends Controller
{
    /**
     * Liste des sites
     */
    public function index(Request $request)
    {
        $entrepriseId = $this->getEntrepriseId();

        $query = SiteClient::where('entreprise_id', $entrepriseId)
            ->with('client');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nom_site', 'like', "%{$search}%")
                    ->orWhere('code_site', 'like', "%{$search}%")
                    ->orWhere('adresse', 'like', "%{$search}%");
            });
        }

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->filled('est_actif')) {
            $query->where('est_actif', $request->est_actif == '1');
        }

        if ($request->filled('niveau_risque')) {
            $query->where('niveau_risque', $request->niveau_risque);
        }

        $sites = $query->orderBy('nom_site')->paginate(15);

        // ✅ FILTRER: Clients avec contrat actif uniquement pour le filtre
        $clients = Client::withoutGlobalScope('entreprise')
            ->where('entreprise_id', $entrepriseId)
            ->where('est_actif', true)
            ->whereHas('contrats', function ($query) {
                $query->where('statut', 'en_cours');
            })
            ->orderBy('nom')
            ->get();

        $stats = [
            'total'      => SiteClient::where('entreprise_id', $entrepriseId)->count(),
            'actifs'     => SiteClient::where('entreprise_id', $entrepriseId)->where('est_actif', true)->count(),
            'inactifs'   => SiteClient::where('entreprise_id', $entrepriseId)->where('est_actif', false)->count(),
            'haut_risque' => SiteClient::where('entreprise_id', $entrepriseId)->whereIn('niveau_risque', ['haut', 'critique'])->count(),
        ];

        return view('admin.entreprise.sites.index', compact('sites', 'clients', 'stats'));
    }

    /**
     * Formulaire de création
     */
    public function create(Request $request)
    {
        $entrepriseId = $this->getEntrepriseId();

        // ✅ FILTRER: Clients avec contrat actif uniquement (statut = 'en_cours')
        $clients = Client::withoutGlobalScope('entreprise')
            ->where('entreprise_id', $entrepriseId)
            ->where('est_actif', true)
            ->whereHas('contrats', function ($query) {
                $query->where('statut', 'en_cours');
            })
            ->with(['contrats' => function ($query) {
                $query->where('statut', 'en_cours')->select('id', 'client_id', 'intitule', 'numero_contrat');
            }])
            ->orderBy('nom')
            ->get();

        $clientId = $request->get('client_id');

        return view('admin.entreprise.sites.create', compact('clients', 'clientId'));
    }

    /**
     * Enregistrer un nouveau site
     */
    public function store(Request $request)
    {
        $entrepriseId = $this->getEntrepriseId();

        $validated = $request->validate([
            'client_id'              => 'required|exists:clients,id',
            'nom_site'               => 'required|string|max:255',
            'code_site'              => 'nullable|string|max:50|unique:sites_clients,code_site',
            'adresse'                => 'required|string',
            'ville'                  => 'nullable|string|max:100',
            'commune'                => 'nullable|string|max:100',
            'quartier'               => 'nullable|string|max:100',
            'latitude'               => 'nullable|numeric|between:-90,90',
            'longitude'              => 'nullable|numeric|between:-180,180',
            'rayon_pointage'         => 'nullable|integer|min:10|max:1000',
            'contact_nom'            => 'nullable|string|max:255',
            'contact_telephone'      => 'nullable|string|max:20',
            'contact_email'          => 'nullable|email|max:255',
            'niveau_risque'          => 'nullable|in:faible,moyen,haut,critique',
            'equipements'            => 'nullable|array',
            'consignes_specifiques'  => 'nullable|string',
            'photos'                 => 'nullable|array',
            'notes'                  => 'nullable|string',
        ]);

        $validated['entreprise_id'] = $entrepriseId;

        // ✅ est_actif : la checkbox n'envoie rien quand décochée → on lit request directement
        $validated['est_actif'] = $request->has('est_actif') ? true : false;

        // Générer code site si absent
        if (empty($validated['code_site'])) {
            $validated['code_site'] = $this->genererCodeSite($entrepriseId);
        }

        $site = SiteClient::create($validated);

        return redirect()->route('admin.entreprise.sites.show', $site->id)
            ->with('success', 'Site créé avec succès.');
    }

    /**
     * Afficher les détails d'un site
     */
    public function show($id)
    {
        $entrepriseId = $this->getEntrepriseId();

        $site = SiteClient::with(['client'])
            ->where('entreprise_id', $entrepriseId)
            ->findOrFail($id);

        $employesDisponibles = Employe::where('entreprise_id', $entrepriseId)
            ->where('est_actif', true)
            ->where('disponible', true)
            ->orderBy('nom')
            ->get();

        $statsSite = [
            'contrats_count' => $site->contrats()->count(),
            'agents_count'   => $site->affectations()->where('statut', 'en_cours')->count(),
        ];

        return view('admin.entreprise.sites.show', compact('site', 'employesDisponibles', 'statsSite'));
    }

    /**
     * Formulaire de modification
     */
    public function edit($id)
    {
        $entrepriseId = $this->getEntrepriseId();

        $site = SiteClient::where('entreprise_id', $entrepriseId)->findOrFail($id);

        // ✅ FILTRER: Clients avec contrat actif uniquement + garder le client actuel du site
        $clients = Client::withoutGlobalScope('entreprise')
            ->where('entreprise_id', $entrepriseId)
            ->where('est_actif', true)
            ->where(function ($query) use ($site) {
                // Inclure le client actuel du site même s'il n'a plus de contrat actif
                $query->whereHas('contrats', function ($q) {
                    $q->where('statut', 'en_cours');
                })
                    ->orWhere('id', $site->client_id);
            })
            ->orderBy('nom')
            ->get();

        return view('admin.entreprise.sites.edit', compact('site', 'clients'));
    }

    /**
     * Mettre à jour un site
     */
    public function update(Request $request, $id)
    {
        $entrepriseId = $this->getEntrepriseId();

        $site = SiteClient::where('entreprise_id', $entrepriseId)->findOrFail($id);

        $validated = $request->validate([
            'client_id'              => 'required|exists:clients,id',
            'nom_site'               => 'required|string|max:255',
            'code_site'              => 'nullable|string|max:50|unique:sites_clients,code_site,' . $id,
            'adresse'                => 'required|string',
            'ville'                  => 'nullable|string|max:100',
            'commune'                => 'nullable|string|max:100',
            'quartier'               => 'nullable|string|max:100',
            'latitude'               => 'nullable|numeric|between:-90,90',
            'longitude'              => 'nullable|numeric|between:-180,180',
            'rayon_pointage'         => 'nullable|integer|min:10|max:1000',
            'contact_nom'            => 'nullable|string|max:255',
            'contact_telephone'      => 'nullable|string|max:20',
            'contact_email'          => 'nullable|email|max:255',
            'niveau_risque'          => 'nullable|in:faible,moyen,haut,critique',
            'equipements'            => 'nullable|array',
            'consignes_specifiques'  => 'nullable|string',
            'photos'                 => 'nullable|array',
            'notes'                  => 'nullable|string',
        ]);

        // ✅ est_actif : checkbox non cochée = pas envoyée = false
        $validated['est_actif'] = $request->has('est_actif') ? true : false;

        $site->update($validated);

        return redirect()->route('admin.entreprise.sites.show', $site->id)
            ->with('success', 'Site mis à jour avec succès.');
    }

    /**
     * Supprimer un site
     */
    public function destroy($id)
    {
        $entrepriseId = $this->getEntrepriseId();

        $site = SiteClient::where('entreprise_id', $entrepriseId)->findOrFail($id);

        if ($site->contrats()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer un site associé à des contrats.');
        }

        if ($site->affectations()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer un site avec des agents affectés.');
        }

        $site->delete();

        return redirect()->route('admin.entreprise.sites.index')
            ->with('success', 'Site supprimé.');
    }

    /**
     * Activer/désactiver un site
     */
    public function toggleStatut($id)
    {
        $entrepriseId = $this->getEntrepriseId();

        $site = SiteClient::where('entreprise_id', $entrepriseId)->findOrFail($id);
        $site->update(['est_actif' => !$site->est_actif]);

        $status = $site->est_actif ? 'activé' : 'désactivé';

        return back()->with('success', "Site {$status}.");
    }

    /**
     * Obtenir l'entreprise_id selon le type d'utilisateur connecté
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
     * Générer un code site unique
     */
    private function genererCodeSite(int $entrepriseId): string
    {
        $prefix = 'SITE-';
        $dernier = SiteClient::where('entreprise_id', $entrepriseId)
            ->where('code_site', 'like', "{$prefix}%")
            ->orderBy('code_site', 'desc')
            ->first();

        $numero = $dernier ? ((int) substr($dernier->code_site, -4) + 1) : 1;

        return $prefix . str_pad($numero, 4, '0', STR_PAD_LEFT);
    }
}
