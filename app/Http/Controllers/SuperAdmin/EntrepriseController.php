<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Entreprise;
use App\Models\Facture;
use App\Models\PaiementFacture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EntrepriseController extends Controller
{
    /**
     * Constructeur
     */
    public function __construct()
    {
        $this->middleware(['auth', 'superadmin']);
    }

    /**
     * Enregistrer une entreprise
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom_entreprise' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:entreprises,slug',
            'nom_commercial' => 'nullable|string|max:255',
            'forme_juridique' => 'nullable|string|max:100',
            'numero_registre' => 'nullable|string|max:50',
            'numeroIdentificationFiscale' => 'nullable|string|max:50',
            'numeroContribuable' => 'nullable|string|max:50',
            'email' => 'required|email',
            'telephone' => 'required|string|max:20',
            'telephone_alternatif' => 'nullable|string|max:20',
            'adresse' => 'nullable|string',
            'ville' => 'nullable|string|max:100',
            'pays' => 'nullable|string|max:100',
            'code_postal' => 'nullable|string|max:20',
            'nom_representant_legal' => 'nullable|string|max:255',
            'email_representant_legal' => 'nullable|email',
            'telephone_representant_legal' => 'nullable|string|max:20',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'couleur_primaire' => 'nullable|string|regex:/^#[a-fA-F0-9]{6}$/',
            'couleur_secondaire' => 'nullable|string|regex:/^#[a-fA-F0-9]{6}$/',
            'formule' => 'nullable|in:essai,basic,standard,premium',
            'nombre_agents_max' => 'nullable|integer|min:1',
            'nombre_sites_max' => 'nullable|integer|min:1',
            'date_debut_contrat' => 'nullable|date',
            'date_fin_contrat' => 'nullable|date',
            'montant_mensuel' => 'nullable|numeric|min:0',
            'cycle_facturation' => 'nullable|in:mensuel,trimestriel,annuel',
            'est_active' => 'boolean',
            'est_en_essai' => 'boolean',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = \Str::slug($validated['nom_entreprise']);
        }

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $validated['logo'] = $path;
        }

        $validated['est_active']        = $validated['est_active'] ?? true;
        $validated['est_en_essai']      = $validated['est_en_essai'] ?? false;
        $validated['formule']           = $validated['formule'] ?? 'essai';
        $validated['nombre_agents_max'] = $validated['nombre_agents_max'] ?? 5;
        $validated['nombre_sites_max']  = $validated['nombre_sites_max'] ?? 2;
        $validated['montant_mensuel']   = $validated['montant_mensuel'] ?? 0;
        $validated['cycle_facturation'] = $validated['cycle_facturation'] ?? 'mensuel';

        if ($validated['formule'] === 'essai') {
            $validated['est_en_essai']  = true;
            $validated['date_fin_essai'] = now()->addDays(30);
        }

        $entreprise = Entreprise::create($validated);

        return redirect()->route('admin.superadmin.entreprises.abonnement', $entreprise->id)
            ->with('success', 'Entreprise créée avec succès. Veuillez choisir un abonnement.');
    }

    /**
     * Mettre à jour une entreprise
     */
    public function update(Request $request, $id)
    {
        $entreprise = Entreprise::findOrFail($id);

        $validated = $request->validate([
            'nom_entreprise' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:entreprises,slug,' . $id,
            'nom_commercial' => 'nullable|string|max:255',
            'forme_juridique' => 'nullable|string|max:100',
            'numero_registre' => 'nullable|string|max:50',
            'numeroIdentificationFiscale' => 'nullable|string|max:50',
            'numeroContribuable' => 'nullable|string|max:50',
            'email' => 'required|email',
            'telephone' => 'required|string|max:20',
            'telephone_alternatif' => 'nullable|string|max:20',
            'adresse' => 'nullable|string',
            'ville' => 'nullable|string|max:100',
            'pays' => 'nullable|string|max:100',
            'code_postal' => 'nullable|string|max:20',
            'nom_representant_legal' => 'nullable|string|max:255',
            'email_representant_legal' => 'nullable|email',
            'telephone_representant_legal' => 'nullable|string|max:20',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'couleur_primaire' => 'nullable|string|regex:/^#[a-fA-F0-9]{6}$/',
            'couleur_secondaire' => 'nullable|string|regex:/^#[a-fA-F0-9]{6}$/',
            'formule' => 'required|in:essai,basic,standard,premium',
            'nombre_agents_max' => 'required|integer|min:1',
            'nombre_sites_max' => 'required|integer|min:1',
            'date_debut_contrat' => 'nullable|date',
            'date_fin_contrat' => 'nullable|date',
            'montant_mensuel' => 'nullable|numeric|min:0',
            'cycle_facturation' => 'nullable|in:mensuel,trimestriel,annuel',
            'est_active' => 'boolean',
            'est_en_essai' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        if ($request->hasFile('logo')) {
            if ($entreprise->logo) {
                Storage::disk('public')->delete($entreprise->logo);
            }
            $path = $request->file('logo')->store('logos', 'public');
            $validated['logo'] = $path;
        } else {
            unset($validated['logo']);
        }

        $entreprise->update($validated);

        return redirect()->route('admin.superadmin.entreprises.index')
            ->with('success', 'Entreprise mise à jour.');
    }

    /**
     * Supprimer une entreprise
     */
    public function destroy($id)
    {
        $entreprise = Entreprise::findOrFail($id);

        if (
            $entreprise->employes()->count() > 0 ||
            $entreprise->clients()->count() > 0 ||
            $entreprise->contratsPrestation()->count() > 0
        ) {
            return back()->with('error', 'Impossible de supprimer une entreprise avec des données associées.');
        }

        if ($entreprise->logo) {
            Storage::disk('public')->delete($entreprise->logo);
        }

        $entreprise->delete();

        return redirect()->route('admin.superadmin.entreprises.index')
            ->with('success', 'Entreprise supprimée.');
    }

    /**
     * Activer une entreprise
     */
    public function activate($id)
    {
        $entreprise = Entreprise::findOrFail($id);
        $entreprise->update(['est_active' => true]);

        return back()->with('success', 'Entreprise activée.');
    }

    /**
     * Désactiver une entreprise
     */
    public function deactivate($id)
    {
        $entreprise = Entreprise::findOrFail($id);
        $entreprise->update(['est_active' => false]);

        return back()->with('success', 'Entreprise désactivée.');
    }

    /**
     * Mettre en période d'essai
     */
    public function mettreEnEssai(Request $request, $id)
    {
        $entreprise = Entreprise::findOrFail($id);

        $validated = $request->validate([
            'date_fin_essai' => 'required|date|after:today',
        ]);

        $entreprise->update([
            'est_en_essai'  => true,
            'date_fin_essai' => $validated['date_fin_essai'],
        ]);

        return back()->with('success', "Entreprise mise en période d'essai.");
    }

    /**
     * Souscrire à une formule
     */
    public function subscribe(Request $request, $id)
    {
        $entreprise = Entreprise::findOrFail($id);

        $validated = $request->validate([
            'formule'           => 'required|in:basic,standard,premium',
            'date_fin_contrat'  => 'required|date|after:today',
            'montant_mensuel'   => 'required|numeric|min:0',
            'cycle_facturation' => 'required|in:mensuel,trimestriel,annuel',
        ]);

        $entreprise->update([
            'formule'           => $validated['formule'],
            'date_debut_contrat' => now(),
            'date_fin_contrat'  => $validated['date_fin_contrat'],
            'montant_mensuel'   => $validated['montant_mensuel'],
            'cycle_facturation' => $validated['cycle_facturation'],
            'est_en_essai'      => false,
            'est_active'        => true,
        ]);

        return back()->with('success', 'Abonnement souscrit.');
    }

    /**
     * Statistiques d'une entreprise
     */
    public function statistiques($id)
    {
        $entreprise = Entreprise::withCount(['employes', 'clients', 'contratsPrestation', 'factures'])
            ->findOrFail($id);

        return view('admin.superadmin.entreprises.statistiques', compact('entreprise'));
    }

    /**
     * Exporter les données d'une entreprise
     */
    public function exporter($id)
    {
        return back()->with('info', 'Export en cours de développement.');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // FLUX D'ABONNEMENT ET PAIEMENT FEDAPAY
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Afficher la page de choix d'abonnement
     */
    public function abonnement($id)
    {
        $entreprise = Entreprise::findOrFail($id);

        $formules = [
            'essai' => [
                'nom'        => 'Essai Gratuit',
                'description' => '30 jours pour tester toutes les fonctionnalités',
                'prix'       => 0,
                'duree'      => '30 jours',
                'agents_max' => 5,
                'sites_max'  => 2,
                'couleur'    => 'success',
                'icone'      => 'bi-gift',
            ],
            'basic' => [
                'nom'        => 'Basic',
                'description' => 'Pour les petites structures',
                'prix'       => 100000,
                'duree'      => 'mois',
                'agents_max' => 40,
                'sites_max'  => 5,
                'couleur'    => 'primary',
                'icone'      => 'bi-star',
            ],
            'standard' => [
                'nom'        => 'Standard',
                'description' => 'Pour les moyennes entreprises',
                'prix'       => 150000,
                'duree'      => 'mois',
                'agents_max' => 100,
                'sites_max'  => 10,
                'couleur'    => 'info',
                'icone'      => 'bi-award',
            ],
            'premium' => [
                'nom'        => 'Premium',
                'description' => 'Pour les grandes entreprises',
                'prix'       => 200000,
                'duree'      => 'mois',
                'agents_max' => 300,
                'sites_max'  => 999,
                'couleur'    => 'warning',
                'icone'      => 'bi-gem',
            ],
        ];

        return view('admin.superadmin.entreprises.abonnement', compact('entreprise', 'formules'));
    }

    /**
     * Traiter le choix d'abonnement
     */
    public function choisirFormule(Request $request, $id)
    {
        $entreprise = Entreprise::findOrFail($id);

        $validated = $request->validate([
            'formule'       => 'required|in:essai,basic,standard,premium',
            'nombre_agents' => 'required|integer|min:1',
            'nombre_sites'  => 'required|integer|min:1',
        ]);

        $prix = [
            'essai'    => 0,
            'basic'    => 100000,
            'standard' => 150000,
            'premium'  => 200000,
        ];

        $formule = $validated['formule'];

        $entreprise->update([
            'formule'           => $formule,
            'nombre_agents_max' => $validated['nombre_agents'],
            'nombre_sites_max'  => $validated['nombre_sites'],
            'montant_mensuel'   => $prix[$formule],
            'cycle_facturation' => 'mensuel',
            'est_active'        => $formule === 'essai',
            'est_en_essai'      => $formule === 'essai',
        ]);

        if ($formule === 'essai') {
            $entreprise->update([
                'date_debut_contrat' => now(),
                'date_fin_essai'     => now()->addDays(30),
            ]);

            return redirect()->route('admin.superadmin.entreprises.index')
                ->with('success', "Entreprise créée avec succès! Période d'essai de 30 jours activée.");
        }

        return redirect()->route('admin.superadmin.entreprises.payer', $entreprise->id)
            ->with('info', 'Veuillez procéder au paiement pour activer votre abonnement.');
    }

    /**
     * Afficher la page de paiement FEDAPAY
     */
    public function payer($id)
    {
        $entreprise = Entreprise::findOrFail($id);

        $prix = [
            'basic'    => 100000,
            'standard' => 150000,
            'premium'  => 200000,
        ];

        $montant = $prix[$entreprise->formule] ?? 100000;

        return view('admin.superadmin.entreprises.payer', compact('entreprise', 'montant'));
    }

    /**
     * Initier le paiement FEDAPAY
     */
    public function initierPaiement(Request $request, $id)
    {
        $entreprise = Entreprise::findOrFail($id);

        $validated = $request->validate([
            'montant'     => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255',
        ]);

        $apiKey      = config('services.fedapay.api_key');
        $secretKey   = config('services.fedapay.secret_key');
        $environment = config('services.fedapay.environment', 'sandbox');

        $transactionData = [
            'amount'   => (int) $validated['montant'],
            'currency' => ['iso' => 'XOF'],
            'description' => $validated['description'] ?? 'Paiement abonnement ' . $entreprise->formule . ' - ' . $entreprise->nom_entreprise,
            'customer' => [
                'email' => $entreprise->email,
                'name'  => $entreprise->nom_entreprise,
            ],
            'callback_url' => route('admin.superadmin.entreprises.callback', $entreprise->id),
            'return_url'   => route('admin.superadmin.entreprises.paiement-succes', $entreprise->id),
        ];

        try {
            $client   = new \GuzzleHttp\Client();
            $response = $client->post(
                ($environment === 'production'
                    ? 'https://api.fedapay.com'
                    : 'https://api-sandbox.fedapay.com') . '/v1/transactions',
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $secretKey,
                        'Content-Type'  => 'application/json',
                    ],
                    'json' => $transactionData,
                ]
            );

            $result = json_decode($response->getBody()->getContents(), true);

            if (isset($result['transaction']['url'])) {
                session(['transaction_id' => $result['transaction']['id']]);
                session(['entreprise_id'  => $entreprise->id]);

                return redirect($result['transaction']['url']);
            }

            return back()->with('error', 'Erreur lors de la création du paiement.');
        } catch (\Exception $e) {
            return $this->simulerPaiement($request, $id);
        }
    }

    /**
     * Simuler le paiement (pour les tests)
     */
    public function simulerPaiement(Request $request, $id)
    {
        $entreprise = Entreprise::findOrFail($id);

        $validated = $request->validate([
            'montant' => 'required|numeric|min:1',
        ]);

        $entreprise->update([
            'est_active'         => true,
            'est_en_essai'       => false,
            'date_debut_contrat' => now(),
            'date_fin_contrat'   => now()->addMonth(),
        ]);

        $facture = $this->genererFacture($entreprise, (float) $validated['montant'], 'SIMULATION');

        return redirect()->route('admin.superadmin.entreprises.facture', [
            'id'      => $entreprise->id,
            'facture' => $facture->id,
        ])->with('success', 'Paiement simulé avec succès! Votre abonnement est maintenant actif.');
    }

    /**
     * Callback de FEDAPAY
     */
    public function callback(Request $request, $id)
    {
        $entreprise    = Entreprise::findOrFail($id);
        $transactionId = $request->input('transaction_id') ?? session('transaction_id');

        if (!$transactionId) {
            return redirect()->route('admin.superadmin.entreprises.payer', $id)
                ->with('error', 'Transaction non trouvée.');
        }

        $secretKey   = config('services.fedapay.secret_key');
        $environment = config('services.fedapay.environment', 'sandbox');

        try {
            $client   = new \GuzzleHttp\Client();
            $response = $client->get(
                ($environment === 'production'
                    ? 'https://api.fedapay.com'
                    : 'https://api-sandbox.fedapay.com') . '/v1/transactions/' . $transactionId,
                [
                    'headers' => ['Authorization' => 'Bearer ' . $secretKey],
                ]
            );

            $result      = json_decode($response->getBody()->getContents(), true);
            $transaction = $result['transaction'] ?? null;

            if ($transaction && $transaction['status'] === 'approved') {
                $entreprise->update([
                    'est_active'         => true,
                    'est_en_essai'       => false,
                    'date_debut_contrat' => now(),
                    'date_fin_contrat'   => now()->addMonth(),
                ]);

                $facture = $this->genererFacture($entreprise, (float) $transaction['amount'], $transactionId);

                return redirect()->route('admin.superadmin.entreprises.facture', [
                    'id'      => $entreprise->id,
                    'facture' => $facture->id,
                ])->with('success', 'Paiement réussi! Votre abonnement est maintenant actif.');
            }

            return redirect()->route('admin.superadmin.entreprises.payer', $id)
                ->with('error', 'Paiement échoué ou annulé.');
        } catch (\Exception $e) {
            return redirect()->route('admin.superadmin.entreprises.payer', $id)
                ->with('error', 'Erreur lors de la vérification du paiement.');
        }
    }

    /**
     * Page de succès du paiement
     */
    public function paiementSucces($id)
    {
        $entreprise = Entreprise::findOrFail($id);

        return view('admin.superadmin.entreprises.paiement-succes', compact('entreprise'));
    }

    /**
     * Afficher la facture
     */
    public function afficherFacture($id, $factureId)
    {
        $entreprise = Entreprise::findOrFail($id);

        $facture = \App\Models\Facture::findOrFail($factureId);

        return view('admin.superadmin.entreprises.facture', compact('entreprise', 'facture'));
    }

    /**
     * Générer une facture et enregistrer le paiement associé
     */
    protected function genererFacture(Entreprise $entreprise, float $montant, string $referencePaiement): Facture
    {
        // ── 1. Créer la facture ───────────────────────────────────────────────
        $facture = new Facture();
        $facture->entreprise_id  = $entreprise->id;
        $facture->numero_facture = 'FAC-' . date('Ymd') . '-' . str_pad(Facture::count() + 1, 4, '0', STR_PAD_LEFT);
        $facture->date_emission  = now();
        $facture->date_echeance  = now()->addDays(30);
        $facture->montant_ht     = $montant;
        $facture->tva            = 0;
        $facture->montant_ttc    = $montant;
        $facture->statut         = 'payee';
        $facture->description    = 'Abonnement ' . ucfirst($entreprise->formule) . ' - ' . $entreprise->nom_entreprise;
        $facture->cree_par       = null; // ✅ nullable après migration
        $facture->save();

        // ── 2. Enregistrer le paiement dans paiements_factures ───────────────
        $paiement = new PaiementFacture();
        $paiement->facture_id     = $facture->id;
        $paiement->montant        = $montant;
        $paiement->date_paiement  = now();
        $paiement->mode_paiement  = 'carte';  // enum: especes|cheque|virement|carte|mobile_money
        $paiement->reference      = $referencePaiement;
        $paiement->notes          = 'Paiement abonnement ' . ucfirst($entreprise->formule) . ' - ' . $entreprise->nom_entreprise;
        $paiement->enregistre_par = $entreprise->employes()->first()?->id ?? 1; // premier employé ou fallback
        $paiement->save();

        return $facture;
    }
}