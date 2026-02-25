<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Incident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncidentController extends Controller
{
    /**
     * Constructeur
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Liste des incidents
     */
    public function index(Request $request)
    {
        $query = Incident::where('client_id', Auth::user()->client_id);

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $incidents = $query->orderByDesc('date_incident')->paginate(15);

        $stats = [
            'total' => Incident::where('client_id', Auth::user()->client_id)->count(),
            'en_cours' => Incident::where('client_id', Auth::user()->client_id)->where('statut', 'en_cours')->count(),
            'resolus' => Incident::where('client_id', Auth::user()->client_id)->where('statut', 'resolu')->count(),
        ];

        return view('admin.client.incidents.index', compact('incidents', 'stats'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        return view('admin.client.incidents.create');
    }

    /**
     * Signaler un incident
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:vol,tentative_vol,incendie,intrusion,degat,autre',
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'date_incident' => 'required|date',
            'lieu' => 'required|string',
            'personnes_concernees' => 'nullable|string',
            'degats_materiels' => 'nullable|boolean',
            'description_degats' => 'nullable|string',
            'actions_entreprises' => 'nullable|string',
            'deposant_nom' => 'required|string',
            'deposant_fonction' => 'nullable|string',
        ]);

        $validated['client_id'] = Auth::user()->client_id;
        $validated['statut'] = 'en_cours';
        $validated['entreprise_id'] = Auth::user()->entreprise_id;

        Incident::create($validated);

        return redirect()->route('admin.client.incidents.index')
            ->with('success', 'Incident signalé avec succès.');
    }

    /**
     * Voir un incident
     */
    public function show($id)
    {
        $incident = Incident::where('client_id', Auth::user()->client_id)
            ->findOrFail($id);

        return view('admin.client.incidents.show', compact('incident'));
    }

    /**
     * Compléter un incident
     */
    public function completer(Request $request, $id)
    {
        $incident = Incident::where('client_id', Auth::user()->client_id)
            ->findOrFail($id);

        $validated = $request->validate([
            'description' => 'required|string',
        ]);

        $incident->update($validated);

        return back()->with('success', 'Incident complété.');
    }

    /**
     * Clore un incident
     */
    public function clore($id)
    {
        $incident = Incident::where('client_id', Auth::user()->client_id)
            ->findOrFail($id);

        $incident->update(['statut' => 'resolu']);

        return back()->with('success', 'Incident clos.');
    }
}
