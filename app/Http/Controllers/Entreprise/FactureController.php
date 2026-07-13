<?php

namespace App\Http\Controllers\Entreprise;

use App\Http\Controllers\Controller;
use App\Models\Facture;
use App\Models\Entreprise;
use App\Models\PaiementFacture;
use App\Models\User;
use App\Notifications\PaiementEffectueNotification;
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

    public function payer($id)
    {
        $entrepriseId = $this->getEntrepriseId();
        $facture = Facture::with(['entreprise', 'abonnement'])
            ->where('entreprise_id', $entrepriseId)->findOrFail($id);

        if ($facture->montant_restant <= 0) {
            return back()->with('error', 'Cette facture est déjà entièrement payée.');
        }

        return view('admin.entreprise.factures.payer', compact('facture'));
    }

    public function traiterPaiement(Request $request, $id)
    {
        $entrepriseId = $this->getEntrepriseId();
        $facture = Facture::where('entreprise_id', $entrepriseId)->findOrFail($id);

        if ($facture->montant_restant <= 0) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Cette facture est déjà entièrement payée.'], 422);
            }
            return back()->with('error', 'Cette facture est déjà entièrement payée.');
        }

        $validated = $request->validate([
            'montant' => 'required|numeric|min:100|max:' . $facture->montant_restant,
            'mode_paiement' => 'required|in:mobile_money,carte,virement,especes,cheque',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        $paiement = PaiementFacture::create([
            'facture_id' => $facture->id,
            'montant' => $validated['montant'],
            'date_paiement' => now(),
            'mode_paiement' => $validated['mode_paiement'],
            'reference' => $validated['reference'] ?? 'PAIEMENT-' . strtoupper(uniqid()),
            'notes' => $validated['notes'] ?? null,
            'enregistre_par' => Auth::guard('employe')->id(),
        ]);

        $facture->montant_paye = ($facture->montant_paye ?? 0) + $validated['montant'];
        $facture->montant_restant = $facture->montant_ttc - $facture->montant_paye;
        $facture->statut = $facture->montant_restant <= 0 ? 'payee' : 'partiellement_payee';
        if ($facture->statut === 'payee') {
            $facture->date_paiement = now();
        }
        $facture->save();

        User::where('is_superadmin', true)->chunk(100, function ($superadmins) use ($facture, $paiement) {
            foreach ($superadmins as $admin) {
                $admin->notify(new PaiementEffectueNotification($facture, $paiement));
            }
        });

        if ($request->wantsJson() || $request->ajax()) {
            $modeLabels = [
                'mobile_money' => 'Mobile Money',
                'carte' => 'Carte Bancaire',
                'virement' => 'Virement',
                'especes' => 'Espèces',
                'cheque' => 'Chèque',
            ];
            return response()->json([
                'success' => true,
                'message' => 'Votre paiement a été enregistré avec succès !',
                'facture_url' => route('admin.entreprise.factures.show', $facture->id),
                'paiement' => [
                    'montant' => number_format($validated['montant'], 0, ',', ' ') . ' CFA',
                    'mode' => $modeLabels[$validated['mode_paiement']] ?? $validated['mode_paiement'],
                    'reference' => $paiement->reference,
                    'date' => $paiement->date_paiement->format('d/m/Y'),
                    'statut' => $facture->statut_label,
                ],
            ]);
        }

        return redirect()->route('admin.entreprise.factures.show', $facture->id)
            ->with('success', 'Paiement de ' . number_format($validated['montant'], 0, ',', ' ') . ' CFA enregistré avec succès.');
    }
}
