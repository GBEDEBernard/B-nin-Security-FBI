<?php

namespace Database\Seeders;

use App\Models\Facture;
use App\Models\PaiementFacture;
use Illuminate\Database\Seeder;

class FactureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $factures = [
            // ============================================================
            // ENTREPRISE 1: Benin Security (ID 1)
            // ============================================================

            // SBT - Client ID 1 - Contrat 1
            [
                'entreprise_id' => 1,
                'contrat_prestation_id' => 1,
                'client_id' => 1,
                'numero_facture' => 'FAC-20260304-0001',
                'reference' => 'MARS-2026-SBT',
                'mois' => 3,
                'annee' => 2026,
                'montant_ht' => 169492,
                'tva' => 18,
                'montant_ttc' => 200000,
                'detail_prestation' => json_encode([
                    'superviseur' => ['agents' => 1, 'heures' => 240],
                    'chef_de_poste' => ['agents' => 2, 'heures' => 480],
                    'agent_poste_fixe' => ['agents' => 4, 'heures' => 960],
                    'agent_mobile' => ['agents' => 1, 'heures' => 240]
                ]),
                'periodes_calc' => json_encode([
                    'debut' => '2026-03-01',
                    'fin' => '2026-03-31'
                ]),
                'date_emission' => '2026-03-04',
                'date_echeance' => '2026-03-19',
                'date_paiement' => '2026-03-05',
                'statut' => 'payee',
                'montant_paye' => 200000,
                'montant_restant' => 0,
                'cree_par' => 1,
                'notes' => 'Facture payée le jour même',
            ],
            [
                'entreprise_id' => 1,
                'contrat_prestation_id' => 1,
                'client_id' => 1,
                'numero_facture' => 'BSS-FA-2026-002',
                'reference' => 'AVRIL-2026-SBT',
                'mois' => 4,
                'annee' => 2026,
                'montant_ht' => 169492,
                'tva' => 18,
                'montant_ttc' => 200000,
                'detail_prestation' => json_encode([
                    'superviseur' => ['agents' => 1, 'heures' => 240],
                    'chef_de_poste' => ['agents' => 2, 'heures' => 480],
                    'agent_poste_fixe' => ['agents' => 4, 'heures' => 960],
                    'agent_mobile' => ['agents' => 1, 'heures' => 240]
                ]),
                'periodes_calc' => json_encode([
                    'debut' => '2026-04-01',
                    'fin' => '2026-04-30'
                ]),
                'date_emission' => '2026-05-01',
                'date_echeance' => '2026-05-15',
                'date_paiement' => null,
                'statut' => 'emise',
                'montant_paye' => 0,
                'montant_restant' => 200000,
                'cree_par' => 1,
            ],

            // UAC - Client ID 2 - Contrat 2
            [
                'entreprise_id' => 1,
                'contrat_prestation_id' => 2,
                'client_id' => 2,
                'numero_facture' => 'FAC-20260304-0002',
                'reference' => 'MARS-2026-UAC',
                'mois' => 3,
                'annee' => 2026,
                'montant_ht' => 169492,
                'tva' => 18,
                'montant_ttc' => 200000,
                'detail_prestation' => json_encode([
                    'superviseur' => ['agents' => 2, 'heures' => 240],
                    'chef_de_poste' => ['agents' => 3, 'heures' => 360],
                    'agent_poste_fixe' => ['agents' => 6, 'heures' => 720],
                    'agent_mobile' => ['agents' => 1, 'heures' => 240]
                ]),
                'periodes_calc' => json_encode([
                    'debut' => '2026-03-01',
                    'fin' => '2026-03-31'
                ]),
                'date_emission' => '2026-03-04',
                'date_echeance' => '2026-03-19',
                'date_paiement' => '2026-03-10',
                'statut' => 'payee',
                'montant_paye' => 200000,
                'montant_restant' => 0,
                'cree_par' => 1,
            ],
            [
                'entreprise_id' => 1,
                'contrat_prestation_id' => 2,
                'client_id' => 2,
                'numero_facture' => 'BSS-FA-2026-004',
                'reference' => 'AVRIL-2026-UAC',
                'mois' => 4,
                'annee' => 2026,
                'montant_ht' => 169492,
                'tva' => 18,
                'montant_ttc' => 200000,
                'detail_prestation' => json_encode([
                    'superviseur' => ['agents' => 2, 'heures' => 240],
                    'chef_de_poste' => ['agents' => 3, 'heures' => 360],
                    'agent_poste_fixe' => ['agents' => 6, 'heures' => 720],
                    'agent_mobile' => ['agents' => 1, 'heures' => 240]
                ]),
                'periodes_calc' => json_encode([
                    'debut' => '2026-04-01',
                    'fin' => '2026-04-30'
                ]),
                'date_emission' => '2026-05-01',
                'date_echeance' => '2026-05-15',
                'date_paiement' => null,
                'statut' => 'emise',
                'montant_paye' => 0,
                'montant_restant' => 200000,
                'cree_par' => 1,
            ],

            // Banque Atlantique - Client ID 4 - Contrat 3
            [
                'entreprise_id' => 1,
                'contrat_prestation_id' => 3,
                'client_id' => 4,
                'numero_facture' => 'BSS-FA-2026-005',
                'reference' => 'MARS-2026-BAB',
                'mois' => 3,
                'annee' => 2026,
                'montant_ht' => 125000,
                'tva' => 18,
                'montant_ttc' => 147500,
                'detail_prestation' => json_encode([
                    'superviseur' => ['agents' => 1, 'heures' => 240],
                    'agent_poste_fixe' => ['agents' => 4, 'heures' => 960],
                    'agent_mobile' => ['agents' => 1, 'heures' => 240]
                ]),
                'periodes_calc' => json_encode([
                    'debut' => '2026-03-01',
                    'fin' => '2026-03-31'
                ]),
                'date_emission' => '2026-04-01',
                'date_echeance' => '2026-04-15',
                'date_paiement' => null,
                'statut' => 'envoyee',
                'montant_paye' => 0,
                'montant_restant' => 147500,
                'cree_par' => 2,
            ],

            // ============================================================
            // ENTREPRISE 2: Guard Pro CI (ID 2)
            // ============================================================

            // Orange CI - Client ID 5 - Contrat 4
            [
                'entreprise_id' => 2,
                'contrat_prestation_id' => 4,
                'client_id' => 5,
                'numero_facture' => 'GPC-FA-2026-001',
                'reference' => 'MARS-2026-ORANGE',
                'mois' => 3,
                'annee' => 2026,
                'montant_ht' => 200000,
                'tva' => 18,
                'montant_ttc' => 236000,
                'detail_prestation' => json_encode([
                    'superviseur' => ['agents' => 2, 'heures' => 240],
                    'chef_de_poste' => ['agents' => 3, 'heures' => 360],
                    'agent_poste_fixe' => ['agents' => 4, 'heures' => 720],
                    'agent_mobile' => ['agents' => 1, 'heures' => 240]
                ]),
                'periodes_calc' => json_encode([
                    'debut' => '2026-03-01',
                    'fin' => '2026-03-31'
                ]),
                'date_emission' => '2026-04-01',
                'date_echeance' => '2026-04-15',
                'date_paiement' => '2026-04-10',
                'statut' => 'payee',
                'montant_paye' => 236000,
                'montant_restant' => 0,
                'cree_par' => 7,
            ],
            [
                'entreprise_id' => 2,
                'contrat_prestation_id' => 4,
                'client_id' => 5,
                'numero_facture' => 'GPC-FA-2026-002',
                'reference' => 'AVRIL-2026-ORANGE',
                'mois' => 4,
                'annee' => 2026,
                'montant_ht' => 200000,
                'tva' => 18,
                'montant_ttc' => 236000,
                'detail_prestation' => json_encode([
                    'superviseur' => ['agents' => 2, 'heures' => 240],
                    'chef_de_poste' => ['agents' => 3, 'heures' => 360],
                    'agent_poste_fixe' => ['agents' => 4, 'heures' => 720],
                    'agent_mobile' => ['agents' => 1, 'heures' => 240]
                ]),
                'periodes_calc' => json_encode([
                    'debut' => '2026-04-01',
                    'fin' => '2026-04-30'
                ]),
                'date_emission' => '2026-05-01',
                'date_echeance' => '2026-05-15',
                'date_paiement' => null,
                'statut' => 'partiellement_payee',
                'montant_paye' => 100000,
                'montant_restant' => 136000,
                'cree_par' => 7,
            ],
        ];

        foreach ($factures as $factureData) {
            $facture = Facture::create($factureData);

            // Créer les enregistrements de paiement si la facture est payée ou partiellement payée
            if ($facture->montant_paye > 0) {
                $paiements = [];

                if ($facture->montant_paye == $facture->montant_ttc) {
                    // Paiement complet
                    $paiements[] = [
                        'facture_id' => $facture->id,
                        'montant' => $facture->montant_paye,
                        'date_paiement' => $facture->date_paiement,
                        'mode_paiement' => 'virement',
                        'reference' => 'PAI-' . $facture->numero_facture . '-001',
                        'enregistre_par' => $facture->cree_par,
                    ];
                } elseif ($facture->montant_paye > 0 && $facture->montant_paye < $facture->montant_ttc) {
                    // Paiement partiel
                    $paiements[] = [
                        'facture_id' => $facture->id,
                        'montant' => $facture->montant_paye,
                        'date_paiement' => $facture->date_paiement,
                        'mode_paiement' => 'virement',
                        'reference' => 'PAI-' . $facture->numero_facture . '-001',
                        'enregistre_par' => $facture->cree_par,
                    ];
                }

                foreach ($paiements as $paiement) {
                    PaiementFacture::create($paiement);
                }
            }
        }

        $totalFactures = Facture::count();
        $totalMontant = Facture::sum('montant_ttc');
        $totalPaye = Facture::sum('montant_paye');
        $totalRestant = Facture::sum('montant_restant');

        $this->command->info('================================================');
        $this->command->info('✅ ' . count($factures) . ' factures créées avec succès!');
        $this->command->info('================================================');
        $this->command->info('📊 STATISTIQUES:');
        $this->command->info('   Total Factures: ' . number_format($totalMontant, 0, ',', ' ') . ' CFA');
        $this->command->info('   Montant Payé:   ' . number_format($totalPaye, 0, ',', ' ') . ' CFA');
        $this->command->info('   Montant Restant:' . number_format($totalRestant, 0, ',', ' ') . ' CFA');
        $this->command->info('');
    }
}
