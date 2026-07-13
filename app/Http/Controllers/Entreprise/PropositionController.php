<?php

namespace App\Http\Controllers\Entreprise;

use App\Http\Controllers\Controller;
use App\Models\PropositionContrat;
use App\Notifications\PropositionNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PropositionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'entreprise']);
    }

    public function index()
    {
        $entrepriseId = Auth::user()->entreprise_id;

        $propositions = PropositionContrat::where('entreprise_id', $entrepriseId)
            ->orderByDesc('created_at')
            ->paginate(15);

        $stats = [
            'total' => PropositionContrat::where('entreprise_id', $entrepriseId)->count(),
            'en_attente' => PropositionContrat::where('entreprise_id', $entrepriseId)
                ->whereIn('statut', ['soumis', 'en_cours', 'contrat_envoye'])
                ->count(),
            'en_attente_signature' => PropositionContrat::where('entreprise_id', $entrepriseId)
                ->where('statut', 'en_attente_signature')
                ->count(),
            'signes' => PropositionContrat::where('entreprise_id', $entrepriseId)
                ->where('statut', 'signe')
                ->count(),
            'rejetes' => PropositionContrat::where('entreprise_id', $entrepriseId)
                ->where('statut', 'rejete')
                ->count(),
        ];

        return view('admin.entreprise.propositions.index', compact('propositions', 'stats'));
    }

    public function show($id)
    {
        $entrepriseId = Auth::user()->entreprise_id;
        $proposition = PropositionContrat::where('entreprise_id', $entrepriseId)
            ->findOrFail($id);

        return view('admin.entreprise.propositions.show', compact('proposition'));
    }

    public function accepter(Request $request, $id)
    {
        $entrepriseId = Auth::user()->entreprise_id;
        $proposition = PropositionContrat::where('entreprise_id', $entrepriseId)
            ->findOrFail($id);

        if (!in_array($proposition->statut, ['soumis', 'en_cours', 'contrat_envoye', 'en_attente_signature'])) {
            return back()->with('error', 'Cette proposition ne peut pas être acceptée.');
        }

        $proposition->update([
            'statut' => 'signe',
            'date_signature' => now(),
        ]);

        $proposition->entreprise->notify(new PropositionNotification($proposition, 'acceptee'));

        return redirect()->route('admin.entreprise.propositions.show', $proposition->id)
            ->with('success', 'Proposition acceptée avec succès.');
    }

    public function refuser(Request $request, $id)
    {
        $entrepriseId = Auth::user()->entreprise_id;
        $proposition = PropositionContrat::where('entreprise_id', $entrepriseId)
            ->findOrFail($id);

        $request->validate([
            'motif_rejet' => 'required|string|min:5',
        ]);

        $proposition->update([
            'statut' => 'rejete',
            'motif_rejet' => $request->motif_rejet,
            'date_rejet' => now(),
        ]);

        $proposition->entreprise->notify(new PropositionNotification($proposition, 'refusee'));

        return redirect()->route('admin.entreprise.propositions.index')
            ->with('success', 'Proposition refusée.');
    }
}
