<?php

namespace App\Http\Controllers\Entreprise;

use App\Http\Controllers\Controller;
use App\Models\Employe;
use App\Models\Client;
use App\Models\ContratPrestation;
use App\Models\Facture;
use App\Models\Incident;
use App\Models\Affectation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RapportController extends Controller
{
    /**
     * Constructeur
     */
    public function __construct()
    {
        $this->middleware(['auth', 'entreprise']);
    }

    /**
     * Liste des rapports
     */
    public function index()
    {
        return view('admin.entreprise.rapports.index');
    }

    /**
     * Rapport sur les employés
     */
    public function employes(Request $request)
    {
        $entrepriseId = Auth::user()->entreprise_id;

        $query = Employe::where('entreprise_id', $entrepriseId);

        if ($request->filled('categorie')) {
            $query->where('categorie', $request->categorie);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $employes = $query->get();

        $stats = [
            'total' => $employes->count(),
            'actifs' => $employes->where('est_actif', true)->count(),
            'par_categorie' => $employes->groupBy('categorie')->map->count(),
            'par_statut' => $employes->groupBy('statut')->map->count(),
        ];

        return view('admin.entreprise.rapports.employes', compact('employes', 'stats'));
    }

    /**
     * Rapport sur les clients
     */
    public function clients(Request $request)
    {
        $entrepriseId = Auth::user()->entreprise_id;

        $query = Client::where('entreprise_id', $entrepriseId);

        if ($request->filled('type')) {
            $query->where('type_client', $request->type);
        }

        $clients = $query->get();

        $stats = [
            'total' => $clients->count(),
            'actifs' => $clients->where('est_actif', true)->count(),
            'par_type' => $clients->groupBy('type_client')->map->count(),
        ];

        return view('admin.entreprise.rapports.clients', compact('clients', 'stats'));
    }

    /**
     * Rapport financier
     */
    public function financier(Request $request)
    {
        $entrepriseId = Auth::user()->entreprise_id;

        $query = Facture::where('entreprise_id', $entrepriseId);

        if ($request->filled('date_debut')) {
            $query->whereDate('date_emission', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('date_emission', '<=', $request->date_fin);
        }

        $factures = $query->get();

        $stats = [
            'total_factures' => $factures->count(),
            'montant_total' => $factures->sum('montant_ttc'),
            'montant_paye' => $factures->sum('montant_paye'),
            'montant_restant' => $factures->sum('montant_restant'),
            'par_statut' => $factures->groupBy('statut')->map(function ($items) {
                return [
                    'nombre' => $items->count(),
                    'montant' => $items->sum('montant_ttc'),
                ];
            }),
        ];

        return view('admin.entreprise.rapports.financier', compact('factures', 'stats'));
    }

    /**
     * Rapport sur les incidents
     */
    public function incidents(Request $request)
    {
        $entrepriseId = Auth::user()->entreprise_id;

        $query = Incident::where('entreprise_id', $entrepriseId);

        if ($request->filled('date_debut')) {
            $query->whereDate('date_incident', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('date_incident', '<=', $request->date_fin);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $incidents = $query->get();

        $stats = [
            'total' => $incidents->count(),
            'par_statut' => $incidents->groupBy('statut')->map->count(),
            'par_type' => $incidents->groupBy('type')->map->count(),
        ];

        return view('admin.entreprise.rapports.incidents', compact('incidents', 'stats'));
    }

    /**
     * Rapport sur les affectations
     */
    public function affectations(Request $request)
    {
        $entrepriseId = Auth::user()->entreprise_id;

        $query = Affectation::where('entreprise_id', $entrepriseId);

        if ($request->filled('date_debut')) {
            $query->whereDate('date_debut', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('date_fin', '<=', $request->date_fin);
        }

        $affectations = $query->get();

        $stats = [
            'total' => $affectations->count(),
            'actives' => $affectations->where('statut', 'active')->count(),
            'terminees' => $affectations->where('statut', 'terminee')->count(),
        ];

        return view('admin.entreprise.rapports.affectations', compact('affectations', 'stats'));
    }

    /**
     * Exporter en PDF
     */
    public function exportPdf(Request $request)
    {
        return back()->with('info', 'Export PDF en cours de développement.');
    }

    /**
     * Exporter en Excel
     */
    public function exportExcel(Request $request)
    {
        return back()->with('info', 'Export Excel en cours de développement.');
    }
}
