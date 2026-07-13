<?php

namespace App\Http\Controllers\Entreprise;

use App\Http\Controllers\Controller;
use App\Models\Facture;
use App\Models\Entreprise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

class FactureController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'entreprise']);
    }

    private function getEntrepriseId(): ?int
    {
        if (Auth::guard('web')->check() && Auth::guard('web')->user()->estSuperAdmin() && Auth::guard('web')->user()->estEnContexteEntreprise()) {
            return session('entreprise_id');
        }
        return Auth::guard('employe')->user()->entreprise_id;
    }

    public function index(Request $request)
    {
        $entrepriseId = $this->getEntrepriseId();
        $entreprise = Entreprise::find($entrepriseId);

        $query = Facture::with('abonnement')
            ->where('entreprise_id', $entrepriseId);

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        if ($request->filled('date_debut')) {
            $query->whereDate('date_emission', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('date_emission', '<=', $request->date_fin);
        }

        $factures = $query->orderByDesc('date_emission')->paginate(15);

        $stats = [
            'total' => Facture::where('entreprise_id', $entrepriseId)->count(),
            'montant_total' => Facture::where('entreprise_id', $entrepriseId)->sum('montant_ttc'),
            'montant_paye' => Facture::where('entreprise_id', $entrepriseId)->sum('montant_paye'),
            'montant_restant' => Facture::where('entreprise_id', $entrepriseId)->sum('montant_restant'),
            'impayees' => Facture::where('entreprise_id', $entrepriseId)
                ->whereIn('statut', ['emise', 'envoyee', 'impayee'])
                ->count(),
        ];

        return view('admin.entreprise.factures.index', compact('factures', 'entreprise', 'stats'));
    }

    public function show($id)
    {
        $entrepriseId = $this->getEntrepriseId();
        $facture = Facture::with(['abonnement', 'paiements'])
            ->where('entreprise_id', $entrepriseId)
            ->findOrFail($id);

        return view('admin.entreprise.factures.show', compact('facture'));
    }

    public function downloadPdf($id)
    {
        $entrepriseId = $this->getEntrepriseId();
        $facture = Facture::with(['entreprise', 'abonnement'])
            ->where('entreprise_id', $entrepriseId)
            ->findOrFail($id);

        $pdf = PDF::loadView('admin.superadmin.facturation.pdf.facture', compact('facture'));

        return $pdf->download("facture_{$facture->numero_facture}.pdf");
    }
}
