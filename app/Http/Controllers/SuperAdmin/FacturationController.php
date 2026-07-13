<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Facture;
use App\Models\Entreprise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use PDF;

class FacturationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'superadmin']);

        $this->middleware('permission:view_payments')->only([
            'index', 'show', 'paiements', 'creances',
        ]);

        $this->middleware('permission:view_analytics')->only([
            'statistiques',
        ]);

        $this->middleware('permission:create_invoices')->only([
            'genererFactures', 'export',
        ]);
    }

    public function index(Request $request)
    {
        $query = Facture::with(['entreprise', 'abonnement']);

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

        $statsQuery = Facture::query();
        if ($request->filled('entreprise_id')) {
            $statsQuery->where('entreprise_id', $request->entreprise_id);
        }
        if ($request->filled('statut')) {
            $statsQuery->where('statut', $request->statut);
        }
        if ($request->filled('date_debut')) {
            $statsQuery->whereDate('date_emission', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $statsQuery->whereDate('date_emission', '<=', $request->date_fin);
        }

        $stats = [
            'total_factures' => $statsQuery->count(),
            'montant_total' => $statsQuery->sum('montant_ttc'),
            'montant_paye' => $statsQuery->sum('montant_paye'),
            'montant_restant' => $statsQuery->sum('montant_restant'),
        ];

        return view('admin.superadmin.facturation.index', compact('factures', 'entreprises', 'stats'));
    }

    public function show($id)
    {
        $facture = Facture::with(['entreprise.abonnement', 'abonnement', 'paiements'])
            ->findOrFail($id);

        return view('admin.superadmin.facturation.show', compact('facture'));
    }

    public function downloadPdf($id)
    {
        $facture = Facture::with(['entreprise', 'abonnement'])->findOrFail($id);

        $pdf = PDF::loadView('admin.superadmin.facturation.pdf.facture', compact('facture'));

        return $pdf->download("facture_{$facture->numero_facture}.pdf");
    }

    public function genererFactures()
    {
        $exitCode = Artisan::call('abonnements:facturer');
        $output = Artisan::output();

        return back()->with('success', trim($output));
    }

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

    public function creances(Request $request)
    {
        $query = Facture::with(['entreprise'])
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

    public function export(Request $request)
    {
        return back()->with('info', 'Fonctionnalité d\'export en cours de développement.');
    }

    public function statistiques()
    {
        $moisActuel = now()->month;
        $anneeActuelle = now()->year;

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
