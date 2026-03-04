<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Facture;
use App\Models\Entreprise;
use App\Models\Client;
use App\Models\PaiementFacture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

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
        $query = Facture::with(['entreprise', 'contrat'])
            ->select('factures.*')
            ->leftJoin('clients', 'factures.client_id', '=', 'clients.id');

        // Filtres
        if ($request->filled('entreprise_id')) {
            $query->where('factures.entreprise_id', $request->entreprise_id);
        }

        if ($request->filled('statut')) {
            $query->where('factures.statut', $request->statut);
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('factures.date_emission', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('factures.date_emission', '<=', $request->date_fin);
        }

        $factures = $query->orderByDesc('factures.date_emission')->paginate(20);

        // Charger les clients sans global scope
        $factureIds = $factures->pluck('id');
        $clientsMap = [];
        if ($factureIds->isNotEmpty()) {
            $clients = Client::withoutGlobalScopes()
                ->whereIn('id', Facture::whereIn('id', $factureIds)->pluck('client_id')->unique())
                ->get()
                ->keyBy('id');

            foreach ($factures as $facture) {
                $facture->setRelation('client', $clients->get($facture->client_id));
            }
        }

        $entreprises = Entreprise::orderBy('nom_entreprise')->get();

        // Stats calculées correctement
        $allFacturesQuery = Facture::query();
        if ($request->filled('entreprise_id')) {
            $allFacturesQuery->where('entreprise_id', $request->entreprise_id);
        }
        if ($request->filled('statut')) {
            $allFacturesQuery->where('statut', $request->statut);
        }
        if ($request->filled('date_debut')) {
            $allFacturesQuery->whereDate('date_emission', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $allFacturesQuery->whereDate('date_emission', '<=', $request->date_fin);
        }

        $totalMontantPaye = $allFacturesQuery->clone()->sum('montant_paye');
        $totalMontantRestant = $allFacturesQuery->clone()->sum('montant_restant');

        $stats = [
            'total_factures' => $allFacturesQuery->clone()->count(),
            'montant_total' => $allFacturesQuery->clone()->sum('montant_ttc'),
            'montant_paye' => $totalMontantPaye,
            'montant_restant' => $totalMontantRestant,
            'factures_impayees' => $allFacturesQuery->clone()->where('statut', '!=', 'payee')->count(),
        ];

        return view(
            'admin.superadmin.facturation.index',
            compact('factures', 'entreprises', 'stats', 'request')
        );
    }

    /**
     * Voir une facture
     */
    public function show($id)
    {
        $facture = Facture::with(['entreprise', 'contrat', 'paiements'])
            ->findOrFail($id);

        // Charger le client sans global scope
        $client = Client::withoutGlobalScopes()->find($facture->client_id);
        $facture->setRelation('client', $client);

        return view('admin.superadmin.facturation.show', compact('facture'));
    }

    /**
     * Générer le PDF d'une facture
     */
    public function generatePdf($id)
    {
        $facture = Facture::with(['entreprise', 'contrat', 'paiements'])
            ->findOrFail($id);

        // Charger le client sans global scope
        $client = Client::withoutGlobalScopes()->find($facture->client_id);
        $facture->setRelation('client', $client);

        // Charger l'employé qui a créé la facture
        $facture->load('createur');

        $pdf = Pdf::loadView('admin.superadmin.facturation.pdf.facture', [
            'facture' => $facture,
            'client' => $client,
            'entreprise' => $facture->entreprise,
            'contrat' => $facture->contrat,
            'paiements' => $facture->paiements,
        ]);

        $filename = 'Facture-' . $facture->numero_facture . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Imprimer une facture (vue navigateur)
     */
    public function printPdf($id)
    {
        $facture = Facture::with(['entreprise', 'contrat', 'paiements'])
            ->findOrFail($id);

        // Charger le client sans global scope
        $client = Client::withoutGlobalScopes()->find($facture->client_id);
        $facture->setRelation('client', $client);

        // Charger l'employé qui a créé la facture
        $facture->load('createur');

        return view('admin.superadmin.facturation.pdf.facture', [
            'facture' => $facture,
            'client' => $client,
            'entreprise' => $facture->entreprise,
            'contrat' => $facture->contrat,
            'paiements' => $facture->paiements,
            'print' => true,
        ]);
    }

    /**
     * Liste des paiements
     */
    public function paiements(Request $request)
    {
        $query = PaiementFacture::with(['facture', 'facture.entreprise']);

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
        $query = Facture::with(['entreprise'])
            ->where('montant_restant', '>', 0)
            ->orderByDesc('date_echeance');

        if ($request->filled('entreprise_id')) {
            $query->where('entreprise_id', $request->entreprise_id);
        }

        $creances = $query->paginate(20);

        // Charger les clients sans global scope
        $creanceIds = $creances->pluck('id');
        if ($creanceIds->isNotEmpty()) {
            $clients = Client::withoutGlobalScopes()
                ->whereIn('id', Facture::whereIn('id', $creanceIds)->pluck('client_id')->unique())
                ->get()
                ->keyBy('id');

            foreach ($creances as $creance) {
                $creance->setRelation('client', $clients->get($creance->client_id));
            }
        }

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
