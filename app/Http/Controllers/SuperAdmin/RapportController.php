<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Entreprise;
use App\Models\Client;
use App\Models\Employe;
use App\Models\ContratPrestation;
use App\Models\Facture;
use Illuminate\Http\Request;

class RapportController extends Controller
{
    /**
     * Constructeur
     */
    public function __construct()
    {
        $this->middleware(['auth', 'superadmin']);
    }

    /**
     * Tableau de bord des rapports globaux
     */
    public function index()
    {
        $stats = [
            'total_entreprises' => Entreprise::count(),
            'entreprises_actives' => Entreprise::where('est_active', true)->count(),
            'total_clients' => Client::count(),
            'total_employes' => Employe::count(),
            'total_contrats' => ContratPrestation::count(),
            'total_factures' => Facture::count(),
            'chiffre_affaires' => Facture::sum('montant_ttc'),
            'chiffre_affaires_paye' => Facture::sum('montant_paye'),
        ];

        return view('admin.superadmin.rapports.index', compact('stats'));
    }

    /**
     * Rapport par entreprise
     */
    public function parEntreprise(Request $request)
    {
        $entrepriseId = $request->get('entreprise_id');

        $entreprises = Entreprise::withCount(['employes', 'clients', 'contratsPrestation', 'factures'])
            ->orderBy('nom_entreprise')
            ->get();

        if ($entrepriseId) {
            $entreprise = Entreprise::with([
                'employes',
                'clients',
                'contratsPrestation',
                'factures'
            ])->findOrFail($entrepriseId);

            return view('admin.superadmin.rapports.entreprise', compact('entreprise', 'entreprises'));
        }

        return view('admin.superadmin.rapports.par-entreprise', compact('entreprises'));
    }

    /**
     * Rapport financier
     */
    public function financier(Request $request)
    {
        $dateDebut = $request->get('date_debut', now()->startOfMonth());
        $dateFin = $request->get('date_fin', now()->endOfMonth());

        $factures = Facture::whereBetween('date_emission', [$dateDebut, $dateFin])
            ->with('entreprise')
            ->get();

        $stats = [
            'nombre' => $factures->count(),
            'montant_total' => $factures->sum('montant_ttc'),
            'montant_paye' => $factures->sum('montant_paye'),
            'montant_restant' => $factures->sum('montant_restant'),
            'par_entreprise' => $factures->groupBy('entreprise_id')->map(function ($items) {
                return [
                    'nombre' => $items->count(),
                    'montant' => $items->sum('montant_ttc'),
                ];
            }),
        ];

        return view('admin.superadmin.rapports.financier', compact('factures', 'stats', 'dateDebut', 'dateFin'));
    }

    /**
     * Rapport des employés
     */
    public function employes(Request $request)
    {
        $entrepriseId = $request->get('entreprise_id');

        $query = Employe::with('entreprise');

        if ($entrepriseId) {
            $query->where('entreprise_id', $entrepriseId);
        }

        $employes = $query->get();

        $stats = [
            'total' => $employes->count(),
            'actifs' => $employes->where('est_actif', true)->count(),
            'en_poste' => $employes->where('statut', 'en_poste')->count(),
            'en_conge' => $employes->where('statut', 'conge')->count(),
            'par_categorie' => $employes->groupBy('categorie')->map->count(),
        ];

        $entreprises = Entreprise::orderBy('nom_entreprise')->get();

        return view('admin.superadmin.rapports.employes', compact('employes', 'stats', 'entreprises'));
    }

    /**
     * Rapport des clients
     */
    public function clients(Request $request)
    {
        $entrepriseId = $request->get('entreprise_id');

        $query = Client::with('entreprise');

        if ($entrepriseId) {
            $query->where('entreprise_id', $entrepriseId);
        }

        $clients = $query->get();

        $stats = [
            'total' => $clients->count(),
            'actifs' => $clients->where('est_actif', true)->count(),
            'par_type' => $clients->groupBy('type_client')->map->count(),
        ];

        $entreprises = Entreprise::orderBy('nom_entreprise')->get();

        return view('admin.superadmin.rapports.clients', compact('clients', 'stats', 'entreprises'));
    }

    /**
     * Rapport des contrats
     */
    public function contrats(Request $request)
    {
        $entrepriseId = $request->get('entreprise_id');

        $query = ContratPrestation::with(['entreprise', 'client']);

        if ($entrepriseId) {
            $query->where('entreprise_id', $entrepriseId);
        }

        $contrats = $query->get();

        $stats = [
            'total' => $contrats->count(),
            'actifs' => $contrats->where('statut', 'en_cours')->count(),
            'expires' => $contrats->where('statut', 'expire')->count(),
            'par_type' => $contrats->groupBy('type_contrat')->map->count(),
        ];

        $entreprises = Entreprise::orderBy('nom_entreprise')->get();

        return view('admin.superadmin.rapports.contrats', compact('contrats', 'stats', 'entreprises'));
    }

    /**
     * Exporter en PDF
     */
    public function exportPdf(Request $request)
    {
        // Logique d'export PDF
        return back()->with('info', 'Export PDF en cours de développement.');
    }

    /**
     * Exporter en Excel
     */
    public function exportExcel(Request $request)
    {
        // Logique d'export Excel
        return back()->with('info', 'Export Excel en cours de développement.');
    }
}
