<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ModeleController extends Controller
{
    /**
     * Constructeur
     */
    public function __construct()
    {
        $this->middleware(['auth', 'superadmin']);
    }

    /**
     * Liste des modèles
     */
    public function index(Request $request)
    {
        $type = $request->get('type', 'all');

        // Modèles système (hardcodés pour le moment)
        $modeles = collect([
            (object)[
                'id' => 1,
                'nom' => 'Contrat de Prestation',
                'type' => 'contrat',
                'description' => 'Modèle de contrat de prestation de services de sécurité',
                'est_systeme' => true,
                'date_creation' => now()->subMonths(2),
                'date_modification' => now()->subDays(15),
            ],
            (object)[
                'id' => 2,
                'nom' => 'Facture Standard',
                'type' => 'facture',
                'description' => 'Modèle de facture avec logo et coordonnées',
                'est_systeme' => true,
                'date_creation' => now()->subMonths(2),
                'date_modification' => now()->subDays(10),
            ],
            (object)[
                'id' => 3,
                'nom' => 'Bulletin de Paie',
                'type' => 'bulletin_paie',
                'description' => 'Modèle de bulletin de paie mensuel',
                'est_systeme' => true,
                'date_creation' => now()->subMonths(2),
                'date_modification' => now()->subDays(1),
            ],
            (object)[
                'id' => 4,
                'nom' => 'Convention de Stage',
                'type' => 'contrat',
                'description' => 'Modèle de convention de stage professionnel',
                'est_systeme' => true,
                'date_creation' => now()->subMonths(1),
                'date_modification' => now()->subDays(20),
            ],
            (object)[
                'id' => 5,
                'nom' => 'Bon de Commande',
                'type' => 'autre',
                'description' => 'Modèle de bon de commande fournitures',
                'est_systeme' => true,
                'date_creation' => now()->subMonths(1),
                'date_modification' => now()->subDays(15),
            ],
            (object)[
                'id' => 6,
                'nom' => 'Attestation de Travail',
                'type' => 'autre',
                'description' => 'Modèle d\'attestation de travail',
                'est_systeme' => true,
                'date_creation' => now()->subMonths(1),
                'date_modification' => now()->subDays(10),
            ],
        ]);

        if ($type !== 'all') {
            $modeles = $modeles->where('type', $type);
        }

        $types = [
            'all' => 'Tous les modèles',
            'contrat' => 'Contrats',
            'facture' => 'Factures',
            'bulletin_paie' => 'Bulletins de paie',
            'autre' => 'Autres',
        ];

        return view('admin.superadmin.modeles.index', compact('modeles', 'types', 'type'));
    }

    /**
     * Voir un modèle
     */
    public function show($id)
    {
        $modele = (object)[
            'id' => $id,
            'nom' => 'Contrat de Prestation',
            'type' => 'contrat',
            'description' => 'Modèle de contrat de prestation de services de sécurité',
            'est_systeme' => true,
            'contenu' => 'Contenu du modèle à afficher...',
            'date_creation' => now()->subMonths(2),
            'date_modification' => now()->subDays(15),
        ];

        return view('admin.superadmin.modeles.show', compact('modele'));
    }

    /**
     * Prévisualiser un modèle
     */
    public function preview($id)
    {
        $modele = (object)[
            'id' => $id,
            'nom' => 'Contrat de Prestation',
            'type' => 'contrat',
            'contenu' => 'Contenu du modèle à prévisualiser...',
        ];

        return view('admin.superadmin.modeles.preview', compact('modele'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $types = [
            'contrat' => 'Contrat',
            'facture' => 'Facture',
            'bulletin_paie' => 'Bulletin de paie',
            'autre' => 'Autre',
        ];

        return view('admin.superadmin.modeles.create', compact('types'));
    }

    /**
     * Enregistrer un modèle
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'type' => 'required|in:contrat,facture,bulletin_paie,autre',
            'description' => 'nullable|string',
            'fichier' => 'nullable|file|mimes:doc,docx,pdf',
        ]);

        // Sauvegarder le fichier
        if ($request->hasFile('fichier')) {
            // Storage::put('modeles/', $validated['fichier']);
        }

        return redirect()->route('admin.superadmin.modeles.index')
            ->with('success', 'Modèle créé avec succès.');
    }

    /**
     * Formulaire de modification
     */
    public function edit($id)
    {
        $modele = (object)[
            'id' => $id,
            'nom' => 'Contrat de Prestation',
            'type' => 'contrat',
            'description' => 'Modèle de contrat de prestation',
        ];

        $types = [
            'contrat' => 'Contrat',
            'facture' => 'Facture',
            'bulletin_paie' => 'Bulletin de paie',
            'autre' => 'Autre',
        ];

        return view('admin.superadmin.modeles.edit', compact('modele', 'types'));
    }

    /**
     * Mettre à jour un modèle
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'type' => 'required|in:contrat,facture,bulletin_paie,autre',
            'description' => 'nullable|string',
            'fichier' => 'nullable|file|mimes:doc,docx,pdf',
        ]);

        return redirect()->route('admin.superadmin.modeles.index')
            ->with('success', 'Modèle mis à jour avec succès.');
    }

    /**
     * Supprimer un modèle
     */
    public function destroy($id)
    {
        return redirect()->route('admin.superadmin.modeles.index')
            ->with('success', 'Modèle supprimé avec succès.');
    }

    /**
     * Télécharger un modèle
     */
    public function download($id)
    {
        // Logique de téléchargement
        return back()->with('info', 'Téléchargement en cours...');
    }

    /**
     * Dupliquer un modèle
     */
    public function duplicate($id)
    {
        return redirect()->route('admin.superadmin.modeles.index')
            ->with('success', 'Modèle dupliqué.');
    }
}
