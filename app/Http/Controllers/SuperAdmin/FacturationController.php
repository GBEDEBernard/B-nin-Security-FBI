<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Facture;
use App\Models\Entreprise;
use App\Models\Client;
use Illuminate\Http\Request;

class FacturationController extends Controller
{
    /**
     * Constructeur
     */
    public function __construct()
    {
        $this->middleware(['auth', 'superadmin']);
    }

    /**
     * Liste des factures globales
     */
    public function index(Request $request)
    {
        $query = Facture::with(['entreprise', 'client', 'contrat']);

        // Filtres
        if ($request->filled('entreprise_id')) {
            $query->where('entreprise_id', $request->entreprise_id);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('date_emission', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('date_emission', '<=', $request->date_fin);
        }

        $factures = $query->orderByDesc('date_emission')->paginate(20);

        $entreprises = Entreprise::orderBy('nom_entreprise')->get();

        $stats = [
            'total_factures' => Facture::count(),
            'montant_total' => Facture::sum('montant_ttc'),
            'montant_paye' => Facture::sum('montant_paye'),
            'montant_restant' => Facture::sum('montant_restant'),
            'factures_impayees' => Facture::where('statut', '!=', 'payee')->count(),
        ];

        return view('admin.superadmin.facturation.index', compact('factures', 'entreprises', 'stats'));
    }

    /**
     * Voir une facture
     */
    public function show($id)
    {
        $facture = Facture::with(['entreprise', 'client', 'contrat', 'paiements'])
            ->findOrFail($id);

        return view('admin.superadmin.facturation.show', compact('facture'));
    }

    /**
     * Liste des paiements
     */
    public function paiements(Request $request)
    {
        $query = \App\Models\PaiementFacture::with(['facture', 'facture.entreprise']);

        if ($request->filled('entreprise_id')) {
            $query->whereHas('facture', function ($q) use ($request) {
                $q->where('entreprise_id', $request->entreprise_id);
            });
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('date_paiement', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('date_paiement', '<=', $request->date_fin);
        }

        $paiements = $query->orderByDesc('date_paiement')->paginate(20);

        $stats = [
            'total_paiements' => $query->count(),
            'montant_total' => $query->sum('montant'),
        ];

        return view('admin.superadmin.facturation.paiements', compact('paiements', 'stats'));
    }

    /**
     * Liste des créances
     */
    public function creances(Request $request)
    {
        $query = Facture::with(['entreprise', 'client'])
            ->where('montant_restant', '>', 0)
            ->orderByDesc('date_echeance');

        if ($request->filled('entreprise_id')) {
            $query->where('entreprise_id', $request->entreprise_id);
        }

        $creances = $query->paginate(20);

        $stats = [
            'total_creances' => $creances->sum('montant_restant'),
            'nombre_factures' => $creances->count(),
            'en_retard' => $creances->where('date_echeance', '<', now())->count(),
        ];

        return view('admin.superadmin.facturation.creances', compact('creances', 'stats'));
    }

    /**
     * Exporter les factures
     */
    public function export(Request $request)
    {
        // Logique d'export à implémenter
        return back()->with('info', 'Fonctionnalité d\'export en cours de développement.');
    }

    /**
     * Statistiques de facturation
     */
    public function statistiques()
    {
        $moisActuel = now()->month;
        $anneeActuelle = now()->year;

        // Factures du mois
        $facturesMois = Facture::whereMonth('date_emission', $moisActuel)
            ->whereYear('date_emission', $anneeActuelle)
            ->get();

        $stats = [
            'nombre_mois' => $facturesMois->count(),
            'montant_mois' => $facturesMois->sum('montant_ttc'),
            'paye_mois' => $facturesMois->sum('montant_paye'),
            'total_entreprises' => Facture::distinct('entreprise_id')->count('entreprise_id'),
            'total_clients' => Facture::distinct('client_id')->count('client_id'),
        ];

        // Évolution mensuelle (12 derniers mois)
        $evolution = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $evolution[] = [
                'mois' => $date->format('m/Y'),
                'nombre' => Facture::whereMonth('date_emission', $date->month)
                    ->whereYear('date_emission', $date->year)
                    ->count(),
                'montant' => Facture::whereMonth('date_emission', $date->month)
                    ->whereYear('date_emission', $date->year)
                    ->sum('montant_ttc'),
            ];
        }

        return view('admin.superadmin.facturation.statistiques', compact('stats', 'evolution'));
    }
}
