<?php

namespace Database\Seeders;

use App\Models\Facture;
use Illuminate\Database\Seeder;

class FactureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $factures = [
            // SBT - Contrat 1 - Benin Security
            [
                'entreprise_id' => 1,
                'contrat_prestation_id' => 1,
                'client_id' => 1,
                'numero_facture' => 'BSS-FA-2024-001',
                'reference' => 'JAN-2024-SBT',
                'mois' => 1,
                'annee' => 2024,
                'montant_ht' => 1500000,
                'tva' => 18,
                'montant_ttc' => 1770000,
                'detail_prestation' => json_encode([
                    'superviseur' => ['agents' => 1, 'heures' => 240],
                    'chef_de_poste' => ['agents' => 2, 'heures' => 480],
                    'agent_poste_fixe' => ['agents' => 4, 'heures' => 960],
                    'agent_mobile' => ['agents' => 1, 'heures' => 240]
                ]),
                'periodes_calc' => json_encode([
                    'debut' => '2024-01-01',
                    'fin' => '2024-01-31'
                ]),
                'date_emission' => '2024-02-01',
                'date_echeance' => '2024-02-15',
                'date_paiement' => '2024-02-10',
                'statut' => 'payee',
                'montant_paye' => 1770000,
                'montant_restant' => 0,
                'cree_par' => 1,
            ],
            [
                'entreprise_id' => 1,
                'contrat_prestation_id' => 1,
                'client_id' => 1,
                'numero_facture' => 'BSS-FA-2024-002',
                'reference' => 'FEV-2024-SBT',
                'mois' => 2,
                'annee' => 2024,
                'montant_ht' => 1500000,
                'tva' => 18,
                'montant_ttc' => 1770000,
                'detail_prestation' => json_encode([
                    'superviseur' => ['agents' => 1, 'heures' => 216],
                    'chef_de_poste' => ['agents' => 2, 'heures' => 432],
                    'agent_poste_fixe' => ['agents' => 4, 'heures' => 864],
                    'agent_mobile' => ['agents' => 1, 'heures' => 216]
                ]),
                'periodes_calc' => json_encode([
                    'debut' => '2024-02-01',
                    'fin' => '2024-02-29'
                ]),
                'date_emission' => '2024-03-01',
                'date_echeance' => '2024-03-15',
                'date_paiement' => '2024-03-12',
                'statut' => 'payee',
                'montant_paye' => 1770000,
                'montant_restant' => 0,
                'cree_par' => 1,
            ],
            [
                'entreprise_id' => 1,
                'contrat_prestation_id' => 1,
                'client_id' => 1,
                'numero_facture' => 'BSS-FA-2024-003',
                'reference' => 'MAR-2024-SBT',
                'mois' => 3,
                'annee' => 2024,
                'montant_ht' => 1500000,
                'tva' => 18,
                'montant_ttc' => 1770000,
                'detail_prestation' => json_encode([
                    'superviseur' => ['agents' => 1, 'heures' => 240],
                    'chef_de_poste' => ['agents' => 2, 'heures' => 480],
                    'agent_poste_fixe' => ['agents' => 4, 'heures' => 960],
                    'agent_mobile' => ['agents' => 1, 'heures' => 240]
                ]),
                'periodes_calc' => json_encode([
                    'debut' => '2024-03-01',
                    'fin' => '2024-03-31'
                ]),
                'date_emission' => '2024-04-01',
                'date_echeance' => '2024-04-15',
                'date_paiement' => null,
                'statut' => 'emise',
                'montant_paye' => 0,
                'montant_restant' => 1770000,
                'cree_par' => 1,
            ],

            // UAC - Contrat 2 - Benin Security
            [
                'entreprise_id' => 1,
                'contrat_prestation_id' => 2,
                'client_id' => 2,
                'numero_facture' => 'BSS-FA-2024-004',
                'reference' => 'JAN-2024-UAC',
                'mois' => 1,
                'annee' => 2024,
                'montant_ht' => 2000000,
                'tva' => 18,
                'montant_ttc' => 2360000,
                'detail_prestation' => json_encode([
                    'superviseur' => ['agents' => 2, 'heures' => 240],
                    'chef_de_poste' => ['agents' => 3, 'heures' => 360],
                    'agent_poste_fixe' => ['agents' => 6, 'heures' => 720],
                    'agent_mobile' => ['agents' => 1, 'heures' => 240]
                ]),
                'periodes_calc' => json_encode([
                    'debut' => '2024-01-15',
                    'fin' => '2024-01-31'
                ]),
                'date_emission' => '2024-02-01',
                'date_echeance' => '2024-02-15',
                'date_paiement' => '2024-02-14',
                'statut' => 'payee',
                'montant_paye' => 2360000,
                'montant_restant' => 0,
                'cree_par' => 1,
            ],
            [
                'entreprise_id' => 1,
                'contrat_prestation_id' => 2,
                'client_id' => 2,
                'numero_facture' => 'BSS-FA-2024-005',
                'reference' => 'FEV-2024-UAC',
                'mois' => 2,
                'annee' => 2024,
                'montant_ht' => 2000000,
                'tva' => 18,
                'montant_ttc' => 2360000,
                'detail_prestation' => json_encode([
                    'superviseur' => ['agents' => 2, 'heures' => 232],
                    'chef_de_poste' => ['agents' => 3, 'heures' => 348],
                    'agent_poste_fixe' => ['agents' => 6, 'heures' => 696],
                    'agent_mobile' => ['agents' => 1, 'heures' => 232]
                ]),
                'periodes_calc' => json_encode([
                    'debut' => '2024-02-01',
                    'fin' => '2024-02-29'
                ]),
                'date_emission' => '2024-03-01',
                'date_echeance' => '2024-03-15',
                'date_paiement' => null,
                'statut' => 'emise',
                'montant_paye' => 1000000,
                'montant_restant' => 1360000,
                'cree_par' => 1,
            ],

            // Banque Atlantique - Contrat 3
            [
                'entreprise_id' => 1,
                'contrat_prestation_id' => 3,
                'client_id' => 4,
                'numero_facture' => 'BSS-FA-2024-006',
                'reference' => 'MAR-2024-BAB',
                'mois' => 3,
                'annee' => 2024,
                'montant_ht' => 1250000,
                'tva' => 18,
                'montant_ttc' => 1475000,
                'detail_prestation' => json_encode([
                    'superviseur' => ['agents' => 1, 'heures' => 240],
                    'agent_poste_fixe' => ['agents' => 4, 'heures' => 960],
                    'agent_mobile' => ['agents' => 1, 'heures' => 240]
                ]),
                'periodes_calc' => json_encode([
                    'debut' => '2024-03-01',
                    'fin' => '2024-03-31'
                ]),
                'date_emission' => '2024-04-01',
                'date_echeance' => '2024-04-15',
                'date_paiement' => null,
                'statut' => 'emise',
                'montant_paye' => 0,
                'montant_restant' => 1475000,
                'cree_par' => 2,
            ],
        ];

        foreach ($factures as $factureData) {
            Facture::create($factureData);
        }

        $this->command->info('✅ ' . count($factures) . ' factures créées avec succès!');
    }
}
