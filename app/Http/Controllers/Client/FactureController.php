<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Facture;
use App\Models\PaiementFacture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FactureController extends Controller
{
    /**
     * Constructeur
     */
    public function __construct()
    {
        $this->middleware(['auth', 'client']);
    }

    /**
     * Liste des factures
     */
    public function index(Request $request)
    {
        $query = Facture::where('client_id', Auth::user()->client_id);

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $factures = $query->orderByDesc('date_emission')->paginate(15);

        $stats = [
            'total' => Facture::where('client_id', Auth::user()->client_id)->count(),
            'payees' => Facture::where('client_id', Auth::user()->client_id)->where('statut', 'payee')->count(),
            'impayee' => Facture::where('client_id', Auth::user()->client_id)->where('statut', 'impayee')->count(),
            'montant_total' => Facture::where('client_id', Auth::user()->client_id)->sum('montant_ttc'),
            'montant_paye' => Facture::where('client_id', Auth::user()->client_id)->sum('montant_paye'),
        ];

        return view('admin.client.factures.index', compact('factures', 'stats'));
    }

    /**
     * Voir une facture
     */
    public function show($id)
    {
        $facture = Facture::where('client_id', Auth::user()->client_id)
            ->with(['entreprise', 'paiements'])
            ->findOrFail($id);

        return view('admin.client.factures.show', compact('facture'));
    }

    /**
     * Télécharger le PDF
     */
    public function downloadPdf($id)
    {
        $facture = Facture::where('client_id', Auth::user()->client_id)
            ->findOrFail($id);

        // Logique de téléchargement PDF
        return back()->with('info', 'Téléchargement du PDF en cours.');
    }

    /**
     * Payer une facture
     */
    public function payer(Request $request, $id)
    {
        $facture = Facture::where('client_id', Auth::user()->client_id)
            ->findOrFail($id);

        $validated = $request->validate([
            'montant' => 'required|numeric|min:0',
            'mode_paiement' => 'required|in:especes,virement,cheque,carte_bancaire',
            'reference' => 'nullable|string',
            'date_paiement' => 'required|date',
        ]);

        // Créer le paiement
        PaiementFacture::create([
            'facture_id' => $facture->id,
            'montant' => $validated['montant'],
            'mode_paiement' => $validated['mode_paiement'],
            'reference' => $validated['reference'] ?? null,
            'date_paiement' => $validated['date_paiement'],
            'statut' => 'valide',
        ]);

        // Mettre à jour la facture
        $nouveauMontantPaye = $facture->montant_paye + $validated['montant'];
        $facture->update([
            'montant_paye' => $nouveauMontantPaye,
            'montant_restant' => $facture->montant_ttc - $nouveauMontantPaye,
            'statut' => $nouveauMontantPaye >= $facture->montant_ttc ? 'payee' : 'partiellement_payee',
            'date_paiement' => $validated['date_paiement'],
        ]);

        return back()->with('success', 'Paiement enregistré.');
    }

    /**
     * Contester une facture
     */
    public function contester(Request $request, $id)
    {
        $facture = Facture::where('client_id', Auth::user()->client_id)
            ->findOrFail($id);

        $validated = $request->validate([
            'motif' => 'required|string',
        ]);

        // Logique de contestation
        return back()->with('success', 'Contestation envoyée.');
    }
}
