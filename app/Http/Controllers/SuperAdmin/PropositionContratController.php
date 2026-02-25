<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\PropositionContrat;
use App\Models\Entreprise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PDF;

class PropositionContratController extends Controller
{
    /**
     * Constructeur
     */
    public function __construct()
    {
        $this->middleware(['auth', 'superadmin']);
    }

    /**
     * Liste des propositions de contrat
     */
    public function index(Request $request)
    {
        $query = PropositionContrat::query();

        // Recherche
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nom_entreprise', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('telephone', 'like', "%{$search}%");
            });
        }

        // Filtre par statut
        if ($request->has('statut') && $request->statut) {
            $query->where('statut', $request->statut);
        }

        // Tri
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder === 'desc' ? 'desc' : 'asc');

        $propositions = $query->paginate(15)->appends($request->query());

        return view('admin.superadmin.propositions.index', compact('propositions'));
    }

    /**
     * Formulaire de création d'une proposition
     */
    public function create()
    {
        return view('admin.superadmin.propositions.create');
    }

    /**
     * Soumettre une proposition (formulaire public)
     */
    public function soumettre(Request $request)
    {
        $validated = $request->validate([
            'nom_entreprise' => 'required|string|max:255',
            'email' => 'required|email',
            'telephone' => 'required|string|max:20',
            'type_service' => 'required|in:garde_renforcee,garde_simple,surveillance_electronique,garde_evenementiel,conseil,autre',
            'nombre_agents' => 'required|integer|min:1',
            'description_besoins' => 'nullable|string',
        ]);

        $validated['statut'] = 'soumis';
        $validated['date_soumission'] = now();

        $proposition = PropositionContrat::create($validated);

        return redirect()->route('home')->with('success', 'Votre demande a été soumise avec succès. Nous vous contacterons rapidement.');
    }

    /**
     * Détails d'une proposition
     */
    public function show($id)
    {
        $proposition = PropositionContrat::findOrFail($id);
        return view('admin.superadmin.propositions.show', compact('proposition'));
    }

    /**
     * Formulaire d'édition d'une proposition
     */
    public function edit($id)
    {
        $proposition = PropositionContrat::findOrFail($id);
        return view('admin.superadmin.propositions.edit', compact('proposition'));
    }

    /**
     * Mettre à jour une proposition
     */
    public function update(Request $request, $id)
    {
        $proposition = PropositionContrat::findOrFail($id);

        $validated = $request->validate([
            'nom_entreprise' => 'required|string|max:255',
            'nom_commercial' => 'nullable|string|max:255',
            'forme_juridique' => 'nullable|string|max:100',
            'email' => 'required|email',
            'telephone' => 'required|string|max:20',
            'adresse' => 'nullable|string',
            'ville' => 'nullable|string|max:100',
            'pays' => 'nullable|string|max:100',
            'numero_registre' => 'nullable|string|max:50',
            'numeroIdentificationFiscale' => 'nullable|string|max:50',
            'numeroContribuable' => 'nullable|string|max:50',
            'representant_nom' => 'nullable|string|max:255',
            'representant_fonction' => 'nullable|string|max:255',
            'representant_email' => 'nullable|email',
            'representant_telephone' => 'nullable|string|max:20',
            'type_service' => 'required|in:garde_renforcee,garde_simple,surveillance_electronique,garde_evenementiel,conseil,autre',
            'nombre_agents' => 'required|integer|min:1',
            'description_besoins' => 'nullable|string',
            'budget_approx' => 'nullable|numeric|min:0',
            'statut' => 'required|in:soumis,en_cours,contrat_envoye,en_attente_signature,signe,rejete,expire',
            'notes' => 'nullable|string',
        ]);

        $validated['traite_par'] = auth()->id();
        $validated['date_traitement'] = now();

        $proposition->update($validated);

        return redirect()->route('admin.superadmin.propositions.show', $proposition->id)
            ->with('success', 'Proposition mise à jour.');
    }

    /**
     * Supprimer une proposition
     */
    public function destroy($id)
    {
        $proposition = PropositionContrat::findOrFail($id);

        // Supprimer les fichiers si existants
        if ($proposition->contrat_pdf_path) {
            Storage::disk('public')->delete($proposition->contrat_pdf_path);
        }
        if ($proposition->fichier_contrat_signe) {
            Storage::disk('public')->delete($proposition->fichier_contrat_signe);
        }

        $proposition->delete();

        return redirect()->route('admin.superadmin.propositions.index')
            ->with('success', 'Proposition supprimée.');
    }

    /**
     * Générer et télécharger le PDF du contrat
     */
    public function telechargerContrat($id)
    {
        $proposition = PropositionContrat::findOrFail($id);

        // Mettre à jour le statut
        $proposition->update([
            'statut' => 'en_attente_signature',
        ]);

        // Générer le PDF
        $pdf = PDF::loadView('admin.superadmin.propositions.pdf.contrat', compact('proposition'));

        $filename = 'contrat_' . Str::slug($proposition->nom_entreprise) . '_' . date('Ymd') . '.pdf';

        // Sauvegarder le PDF
        $path = 'contrats/' . $filename;
        Storage::disk('public')->put($path, $pdf->output());

        $proposition->update([
            'contrat_pdf_path' => $path,
            'statut' => 'en_attente_signature',
        ]);

        return $pdf->download($filename);
    }

    /**
     * Envoyer le contrat par email
     */
    public function envoyerContrat(Request $request, $id)
    {
        $request->validate([
            'message' => 'nullable|string',
        ]);

        $proposition = PropositionContrat::findOrFail($id);

        // Ici, vous ajouteriez l'envoi d'email avec le PDF en pièce jointe
        // Mail::to($proposition->email)->send(new ContratEnvoye($proposition, $request->message));

        $proposition->update([
            'statut' => 'en_attente_signature',
        ]);

        return back()->with('success', 'Contrat envoyé par email à ' . $proposition->email);
    }

    /**
     * Soumettre le contrat signé
     */
    public function soumettreSigne(Request $request, $id)
    {
        $request->validate([
            'fichier_contrat_signe' => 'required|file|mimes:pdf|max:5120',
        ]);

        $proposition = PropositionContrat::findOrFail($id);

        // Sauvegarder le fichier signé
        $path = $request->file('fichier_contrat_signe')->store('contrats_signes', 'public');

        $proposition->update([
            'fichier_contrat_signe' => $path,
            'date_signature' => now(),
            'statut' => 'signe',
        ]);

        return back()->with('success', 'Contrat signé soumis avec succès.');
    }

    /**
     * Créer l'entreprise à partir d'une proposition
     */
    public function creerEntreprise(Request $request, $id)
    {
        $proposition = PropositionContrat::findOrFail($id);

        if ($proposition->statut !== 'signe') {
            return back()->with('error', 'La proposition doit être-signed avant de créer l\'entreprise.');
        }

        $validated = $request->validate([
            'formule' => 'required|in:essai,basic,standard,premium',
            'nombre_agents_max' => 'required|integer|min:1',
            'nombre_sites_max' => 'required|integer|min:1',
            'montant_mensuel' => 'required|numeric|min:0',
            'cycle_facturation' => 'required|in:mensuel,trimestriel,annuel',
            'date_debut_contrat' => 'required|date',
            'date_fin_contrat' => 'required|date|after:date_debut_contrat',
        ]);

        // Créer l'entreprise
        $entreprise = Entreprise::create([
            'nom_entreprise' => $proposition->nom_entreprise,
            'nom_commercial' => $proposition->nom_commercial,
            'forme_juridique' => $proposition->forme_juridique,
            'email' => $proposition->email,
            'telephone' => $proposition->telephone,
            'adresse' => $proposition->adresse,
            'ville' => $proposition->ville,
            'pays' => $proposition->pays,
            'numero_registre' => $proposition->numero_registre,
            'numeroIdentificationFiscale' => $proposition->numeroIdentificationFiscale,
            'numeroContribuable' => $proposition->numeroContribuable,
            'nom_representant_legal' => $proposition->representant_nom,
            'email_representant_legal' => $proposition->representant_email,
            'telephone_representant_legal' => $proposition->representant_telephone,
            'formule' => $validated['formule'],
            'nombre_agents_max' => $validated['nombre_agents_max'],
            'nombre_sites_max' => $validated['nombre_sites_max'],
            'montant_mensuel' => $validated['montant_mensuel'],
            'cycle_facturation' => $validated['cycle_facturation'],
            'date_debut_contrat' => $validated['date_debut_contrat'],
            'date_fin_contrat' => $validated['date_fin_contrat'],
            'est_active' => true,
            'slug' => Str::slug($proposition->nom_entreprise),
        ]);

        // Lier la proposition à l'entreprise
        $proposition->update([
            'entreprise_id' => $entreprise->id,
        ]);

        return redirect()->route('admin.superadmin.entreprises.show', $entreprise->id)
            ->with('success', 'Entreprise créée avec succès à partir de la proposition.');
    }

    /**
     * Rejeter une proposition
     */
    public function rejeter(Request $request, $id)
    {
        $request->validate([
            'motif_rejet' => 'required|string',
        ]);

        $proposition = PropositionContrat::findOrFail($id);

        $proposition->update([
            'statut' => 'rejete',
            'motif_rejet' => $request->motif_rejet,
            'date_rejet' => now(),
            'traite_par' => auth()->id(),
            'date_traitement' => now(),
        ]);

        return back()->with('success', 'Proposition rejetée.');
    }
}
