<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ContratPrestation;
use App\Models\Facture;
use App\Models\Incident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContratController extends Controller
{
    /**
     * Constructeur
     */
    public function __construct()
    {
        $this->middleware(['auth', 'client']);
    }

    /**
     * Liste des contrats
     */
    public function index()
    {
        $contrats = ContratPrestation::where('client_id', Auth::user()->client_id)
            ->with(['entreprise', 'sites'])
            ->orderByDesc('date_debut')
            ->get();

        return view('admin.client.contrats.index', compact('contrats'));
    }

    /**
     * Voir un contrat
     */
    public function show($id)
    {
        $contrat = ContratPrestation::where('client_id', Auth::user()->client_id)
            ->with(['entreprise', 'sites', 'factures'])
            ->findOrFail($id);

        return view('admin.client.contrats.show', compact('contrat'));
    }

    /**
     * Demande de résiliation
     */
    public function resilier(Request $request, $id)
    {
        $contrat = ContratPrestation::where('client_id', Auth::user()->client_id)
            ->findOrFail($id);

        $validated = $request->validate([
            'motif' => 'required|string',
        ]);

        // Logique pour notifier l'entreprise
        return back()->with('success', 'Demande de résiliation envoyée.');
    }

    /**
     * Signer un avenant
     */
    public function signerAvenant(Request $request, $id)
    {
        $contrat = ContratPrestation::where('client_id', Auth::user()->client_id)
            ->findOrFail($id);

        return back()->with('success', 'Avenant signé.');
    }
}
