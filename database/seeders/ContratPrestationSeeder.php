<?php

namespace Database\Seeders;

use App\Models\ContratPrestation;
use Illuminate\Database\Seeder;

class ContratPrestationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contrats = [
            // Entreprise 1: Benin Security (ID 1)
            [
                'entreprise_id' => 1,
                'client_id' => 1, // SBT
                'numero_contrat' => 'BSS-CT-2024-001',
                'intitule' => 'Prestation de Sécurité - Siège SBT',
                'date_debut' => '2024-01-01',
                'date_fin' => '2024-12-31',
                'est_renouvelable' => true,
                'duree_preavis' => 30,
                'montant_annuel_ht' => 18000000,
                'montant_mensuel_ht' => 1500000,
                'tva' => 18,
                'montant_mensuel_ttc' => 1770000,
                'periodicite_facturation' => 'mensuel',
                'nombre_agents_requis' => 8,
                'postes_requis' => json_encode([
                    'superviseur' => 1,
                    'chef_de_poste' => 2,
                    'agent_poste_fixe' => 4,
                    'agent_mobile' => 1
                ]),
                'description_prestation' => ' Surveillance et gardiennage du siège social de la SBT à Cotonou. Service 24h/24, 7j/7 avec ronde de nuit et contrôle d\'accès.',
                'horaires_globaux' => json_encode([
                    'debut_journee' => '06:00',
                    'fin_journee' => '18:00',
                    'nuit' => [
                        'debut' => '18:00',
                        'fin' => '06:00'
                    ]
                ]),
                'conditions_particulieres' => 'Port d\'uniforme obligatoire. Formation SST requise pour tous les agents.',
                'statut' => 'en_cours',
                'signataire_client_nom' => 'Michel ALOKO',
                'signataire_client_fonction' => 'Directeur des Opérations',
                'signataire_securite_id' => 1, // K. Togo
                'date_signature' => '2023-12-15',
                'cree_par' => 1,
                'valide_par' => 1,
                'date_validation' => '2023-12-20',
            ],
            [
                'entreprise_id' => 1,
                'client_id' => 2, // UAC
                'numero_contrat' => 'BSS-CT-2024-002',
                'intitule' => 'Prestation de Sécurité - Campus UAC',
                'date_debut' => '2024-01-15',
                'date_fin' => '2025-01-14',
                'est_renouvelable' => true,
                'duree_preavis' => 60,
                'montant_annuel_ht' => 24000000,
                'montant_mensuel_ht' => 2000000,
                'tva' => 18,
                'montant_mensuel_ttc' => 2360000,
                'periodicite_facturation' => 'mensuel',
                'nombre_agents_requis' => 12,
                'postes_requis' => json_encode([
                    'superviseur' => 2,
                    'chef_de_poste' => 3,
                    'agent_poste_fixe' => 6,
                    'agent_mobile' => 1
                ]),
                'description_prestation' => 'Sécurisation du campus universitaire d\'Abomey-Calavi. Contrôle aux entrées, patrouilles pédagogiques, surveillance nocturne.',
                'horaires_globaux' => json_encode([
                    'journee' => [
                        'debut' => '06:00',
                        'fin' => '22:00'
                    ],
                    'nuit' => [
                        'debut' => '22:00',
                        'fin' => '06:00'
                    ]
                ]),
                'conditions_particulieres' => 'Agents formés aux premiers secours. Coordination avec la police universitaire.',
                'statut' => 'en_cours',
                'signataire_client_nom' => 'Professeur Joseph KOKO',
                'signataire_client_fonction' => 'Secrétaire Général',
                'signataire_securite_id' => 1,
                'date_signature' => '2024-01-10',
                'cree_par' => 1,
                'valide_par' => 1,
                'date_validation' => '2024-01-12',
            ],
            [
                'entreprise_id' => 1,
                'client_id' => 4, // Banque Atlantique
                'numero_contrat' => 'BSS-CT-2024-003',
                'intitule' => 'Prestation de Sécurité - Agence Principale BAB',
                'date_debut' => '2024-03-01',
                'date_fin' => '2025-02-28',
                'est_renouvelable' => true,
                'duree_preavis' => 30,
                'montant_annuel_ht' => 15000000,
                'montant_mensuel_ht' => 1250000,
                'tva' => 18,
                'montant_mensuel_ttc' => 1475000,
                'periodicite_facturation' => 'mensuel',
                'nombre_agents_requis' => 6,
                'postes_requis' => json_encode([
                    'superviseur' => 1,
                    'agent_poste_fixe' => 4,
                    'agent_mobile' => 1
                ]),
                'description_prestation' => 'Sécurisation de l\'agence principale et gestion des coffre-forts. Service 24h/24.',
                'horaires_globaux' => json_encode([
                    'continu' => true,
                    'horaires_bancaires' => '08:00-16:00'
                ]),
                'conditions_particulieres' => 'Habilitation confidentiel défense requise. Formation spécifique bancaire.',
                'statut' => 'en_cours',
                'signataire_client_nom' => 'Pierre HOUNGBO',
                'signataire_client_fonction' => 'Responsable Sécurité',
                'signataire_securite_id' => 2, // K. Akakpo
                'date_signature' => '2024-02-20',
                'cree_par' => 2,
                'valide_par' => 1,
                'date_validation' => '2024-02-25',
            ],

            // Entreprise 2: Guard Pro CI (ID 2)
            [
                'entreprise_id' => 2,
                'client_id' => 5, // Orange CI
                'numero_contrat' => 'GPC-CT-2024-001',
                'intitule' => 'Prestation de Sécurité - Siège Orange CI',
                'date_debut' => '2024-01-01',
                'date_fin' => '2024-12-31',
                'est_renouvelable' => true,
                'duree_preavis' => 30,
                'montant_annuel_ht' => 24000000,
                'montant_mensuel_ht' => 2000000,
                'tva' => 18,
                'montant_mensuel_ttc' => 2360000,
                'periodicite_facturation' => 'mensuel',
                'nombre_agents_requis' => 10,
                'postes_requis' => json_encode([
                    'superviseur' => 2,
                    'chef_de_poste' => 3,
                    'agent_poste_fixe' => 4,
                    'agent_mobile' => 1
                ]),
                'description_prestation' => 'Sécurisation du siège social Orange Côte d\'Ivoire à Treichville.',
                'horaires_globaux' => json_encode([
                    'journee' => ['debut' => '06:00', 'fin' => '22:00'],
                    'nuit' => ['debut' => '22:00', 'fin' => '06:00']
                ]),
                'conditions_particulieres' => 'Formation SAP et secourisme obligatoire.',
                'statut' => 'en_cours',
                'signataire_client_nom' => 'Jean-Baptiste KOUASSI',
                'signataire_client_fonction' => 'Chef Sécurité',
                'signataire_securite_id' => 7, // Y. Konan
                'date_signature' => '2023-12-20',
                'cree_par' => 7,
                'valide_par' => 7,
                'date_validation' => '2023-12-22',
            ],

            // Entreprise 3: Niger Protection (ID 3)
            [
                'entreprise_id' => 3,
                'client_id' => 7, // NIGELEC
                'numero_contrat' => 'NPR-CT-2024-001',
                'intitule' => 'Prestation de Sécurité - Siège NIGELEC',
                'date_debut' => '2024-06-01',
                'date_fin' => '2025-05-31',
                'est_renouvelable' => true,
                'duree_preavis' => 30,
                'montant_annuel_ht' => 12000000,
                'montant_mensuel_ht' => 1000000,
                'tva' => 19,
                'montant_mensuel_ttc' => 1190000,
                'periodicite_facturation' => 'mensuel',
                'nombre_agents_requis' => 5,
                'postes_requis' => json_encode([
                    'superviseur' => 1,
                    'chef_de_poste' => 1,
                    'agent_poste_fixe' => 3
                ]),
                'description_prestation' => 'Sécurisation du siège de la NIGELEC à Niamey.',
                'horaires_globaux' => json_encode([
                    'journee' => ['debut' => '07:00', 'fin' => '19:00'],
                    'nuit' => ['debut' => '19:00', 'fin' => '07:00']
                ]),
                'conditions_particulieres' => 'Expérience en sécurité électrique requise.',
                'statut' => 'en_cours',
                'signataire_client_nom' => 'Maman Sani OUSMANE',
                'signataire_client_fonction' => 'Directeur Général',
                'signataire_securite_id' => 10, // M. Ibrahim
                'date_signature' => '2024-05-25',
                'cree_par' => 10,
                'valide_par' => 10,
                'date_validation' => '2024-05-28',
            ],
        ];

        foreach ($contrats as $contratData) {
            ContratPrestation::create($contratData);
        }

        $this->command->info('✅ ' . count($contrats) . ' contrats de prestation créés avec succès!');
    }
}
