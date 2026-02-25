<?php

namespace App\Http\Controllers\Entreprise;

use App\Http\Controllers\Controller;
use App\Models\Employe;
use App\Models\Client;
use App\Models\ContratPrestation;
use App\Models\Facture;
use App\Models\Affectation;
use App\Models\Incident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Constructeur
     */
    public function __construct()
    {
        $this->middleware(['auth', 'entreprise']);
    }

    /**
     * Dashboard de l'entreprise
     */
    public function index()
    {
        $entrepriseId = Auth::user()->entreprise_id;

        // Statistiques rapides
        $stats = [
            // Employés
            'employes_total' => Employe::where('entreprise_id', $entrepriseId)->count(),
            'employes_actifs' => Employe::where('entreprise_id', $entrepriseId)
                ->where('est_actif', true)
                ->where('statut', 'en_poste')
                ->count(),
            'employes_agents' => Employe::where('entreprise_id', $entrepriseId)
                ->where('categorie', 'agent')
                ->where('est_actif', true)
                ->count(),
            'employes_disponibles' => Employe::where('entreprise_id', $entrepriseId)
                ->where('disponible', true)
                ->count(),

            // Clients
            'clients_total' => Client::where('entreprise_id', $entrepriseId)->count(),
            'clients_actifs' => Client::where('entreprise_id', $entrepriseId)
                ->where('est_actif', true)
                ->count(),

            // Contrats
            'contrats_total' => ContratPrestation::where('entreprise_id', $entrepriseId)->count(),
            'contrats_actifs' => ContratPrestation::where('entreprise_id', $entrepriseId)
                ->where('statut', 'en_cours')
                ->count(),
            'contrats_expires' => ContratPrestation::where('entreprise_id', $entrepriseId)
                ->where('statut', 'expire')
                ->count(),

            // Facturation
            'factures_en_attente' => Facture::where('entreprise_id', $entrepriseId)
                ->whereIn('statut', ['en_attente', 'impayee'])
                ->count(),
            'montant_impaye' => Facture::where('entreprise_id', $entrepriseId)
                ->sum('montant_restant'),

            // Affectations
            'affectations_actives' => Affectation::where('entreprise_id', $entrepriseId)
                ->where('statut', 'active')
                ->count(),

            // Incidents
            'incidents_en_cours' => Incident::where('entreprise_id', $entrepriseId)
                ->where('statut', 'en_cours')
                ->count(),
        ];

        // Données pour les graphiques
        $chartData = [
            // Évolution des employés par mois
            'employes_par_mois' => Employe::where('entreprise_id', $entrepriseId)
                ->selectRaw('MONTH(date_embauche) as mois, COUNT(*) as total')
                ->whereYear('date_embauche', now()->year)
                ->groupBy('mois')
                ->get(),

            // Contrats par mois
            'contrats_par_mois' => ContratPrestation::where('entreprise_id', $entrepriseId)
                ->selectRaw('MONTH(date_debut) as mois, COUNT(*) as total')
                ->whereYear('date_debut', now()->year)
                ->groupBy('mois')
                ->get(),
        ];

        // Incidents récents
        $incidents_recents = Incident::where('entreprise_id', $entrepriseId)
            ->orderByDesc('date_incident')
            ->limit(5)
            ->get();

        // Affectations du jour
        $affectations_aujourdhui = Affectation::where('entreprise_id', $entrepriseId)
            ->whereDate('date_debut', '<=', today())
            ->whereDate('date_fin', '>=', today())
            ->where('statut', 'active')
            ->with(['employe', 'site'])
            ->limit(10)
            ->get();

        // Factures impayées
        $factures_impayees = Facture::where('entreprise_id', $entrepriseId)
            ->whereIn('statut', ['en_attente', 'impayee'])
            ->orderBy('date_echeance')
            ->limit(5)
            ->get();

        return view('admin.entreprise', compact(
            'stats',
            'chartData',
            'incidents_recents',
            'affectations_aujourdhui',
            'factures_impayees'
        ));
    }

    /**
     * Statistiques détaillées
     */
    public function statistiques()
    {
        $entrepriseId = Auth::user()->entreprise_id;

        $stats = [
            'employes_par_categorie' => Employe::where('entreprise_id', $entrepriseId)
                ->select('categorie')
                ->selectRaw('COUNT(*) as total')
                ->groupBy('categorie')
                ->get(),

            'employes_par_statut' => Employe::where('entreprise_id', $entrepriseId)
                ->select('statut')
                ->selectRaw('COUNT(*) as total')
                ->groupBy('statut')
                ->get(),

            'clients_par_type' => Client::where('entreprise_id', $entrepriseId)
                ->select('type_client')
                ->selectRaw('COUNT(*) as total')
                ->groupBy('type_client')
                ->get(),

            'factures_par_mois' => Facture::where('entreprise_id', $entrepriseId)
                ->selectRaw('MONTH(date_emission) as mois, SUM(montant_ttc) as total')
                ->whereYear('date_emission', now()->year)
                ->groupBy('mois')
                ->get(),
        ];

        return view('admin.entreprise.statistiques', compact('stats'));
    }

    /**
     * Notifications
     */
    public function notifications()
    {
        // Logique pour les notifications
        return view('admin.entreprise.notifications');
    }

    /**
     * Profile de l'entreprise
     */
    public function profile()
    {
        $entreprise = Auth::user()->entreprise;
        return view('admin.entreprise.profile', compact('entreprise'));
    }

    /**
     * Mettre à jour le profile
     */
    public function updateProfile(Request $request)
    {
        $entreprise = Auth::user()->entreprise;

        $validated = $request->validate([
            'nom_entreprise' => 'required|string|max:255',
            'nom_commercial' => 'nullable|string|max:255',
            'email' => 'required|email',
            'telephone' => 'required|string|max:20',
            'adresse' => 'nullable|string',
            'ville' => 'nullable|string|max:100',
            'pays' => 'nullable|string|max:100',
        ]);

        $entreprise->update($validated);

        return back()->with('success', 'Profil mis à jour.');
    }
}
