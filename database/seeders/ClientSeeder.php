<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = [
            // Entreprise 1: Benin Security (ID 1)
            [
                'entreprise_id' => 1,
                'type_client' => 'entreprise',
                'nom' => null,
                'prenoms' => null,
                'raison_sociale' => 'Société Béninoise de Télécommunications (SBT)',
                'nif' => 'NIF-2018-12345',
                'rc' => 'RC-ABT/2018/1234',
                'email' => 'direction@sbt.bj',
                'telephone' => '+229 21 30 00 10',
                'telephone_secondaire' => '+229 21 30 00 11',
                'contact_principal_nom' => 'Michel ALOKO',
                'contact_principal_fonction' => 'Directeur des Opérations',
                'adresse' => ' Boulevard de la Marina, Cotonou',
                'ville' => 'Cotonou',
                'pays' => 'Bénin',
                'est_actif' => true,
                'notes' => 'Client historique depuis 2020',
            ],
            [
                'entreprise_id' => 1,
                'type_client' => 'institution',
                'nom' => null,
                'prenoms' => null,
                'raison_sociale' => 'Université d\'Abomey-Calavi (UAC)',
                'nif' => 'NIF-2015-67890',
                'rc' => 'RC-UAC/2015/567',
                'email' => 'sg@uac.bj',
                'telephone' => '+229 21 30 00 20',
                'telephone_secondaire' => null,
                'contact_principal_nom' => 'Professeur Joseph KOKO',
                'contact_principal_fonction' => 'Secrétaire Général',
                'adresse' => 'Campus d\'Abomey-Calavi',
                'ville' => 'Abomey-Calavi',
                'pays' => 'Bénin',
                'est_actif' => true,
                'notes' => 'Campus principal - 3 sites',
            ],
            [
                'entreprise_id' => 1,
                'type_client' => 'particulier',
                'nom' => 'TOSSOU',
                'prenoms' => 'David Alexandre',
                'raison_sociale' => null,
                'nif' => null,
                'rc' => null,
                'email' => 'd.tossou@gmail.com',
                'telephone' => '+229 97 10 00 01',
                'telephone_secondaire' => null,
                'contact_principal_nom' => null,
                'contact_principal_fonction' => null,
                'adresse' => 'Fidjrossè, Cotonou',
                'ville' => 'Cotonou',
                'pays' => 'Bénin',
                'est_actif' => true,
                'notes' => 'Résidence privée',
            ],
            [
                'entreprise_id' => 1,
                'type_client' => 'entreprise',
                'nom' => null,
                'prenoms' => null,
                'raison_sociale' => 'Banque Atlantique Bénin',
                'nif' => 'NIF-2010-11111',
                'rc' => 'RC-BAB/2010/1111',
                'email' => 'securite@atlantique-benin.bj',
                'telephone' => '+229 21 30 00 30',
                'telephone_secondaire' => '+229 21 30 00 31',
                'contact_principal_nom' => 'Pierre HOUNGBO',
                'contact_principal_fonction' => 'Responsable Sécurité',
                'adresse' => 'Rue de la Bourse, Cotonou',
                'ville' => 'Cotonou',
                'pays' => 'Bénin',
                'est_actif' => true,
                'notes' => 'Agence principale + 5 agences',
            ],

            // Entreprise 2: Guard Pro CI (ID 2)
            [
                'entreprise_id' => 2,
                'type_client' => 'entreprise',
                'nom' => null,
                'prenoms' => null,
                'raison_sociale' => 'Orange Côte d\'Ivoire',
                'nif' => 'NIF-CI-2010-11111',
                'rc' => 'RC-OCI/2010/1111',
                'email' => 'securite@orange.ci',
                'telephone' => '+225 27 20 00 10',
                'telephone_secondaire' => '+225 27 20 00 11',
                'contact_principal_nom' => 'Jean-Baptiste KOUASSI',
                'contact_principal_fonction' => 'Chef Sécurité',
                'adresse' => 'Treichville, Abidjan',
                'ville' => 'Abidjan',
                'pays' => 'Côte d\'Ivoire',
                'est_actif' => true,
                'notes' => 'Siège social + 10 agences',
            ],
            [
                'entreprise_id' => 2,
                'type_client' => 'institution',
                'nom' => null,
                'prenoms' => null,
                'raison_sociale' => 'Ministère de la Défense',
                'nif' => 'NIF-CI-GOV-001',
                'rc' => 'RC-MD/2015/001',
                'email' => 'secretariat@defense.gouv.ci',
                'telephone' => '+225 27 20 00 20',
                'telephone_secondaire' => null,
                'contact_principal_nom' => 'Colonel Bakayoko',
                'contact_principal_fonction' => 'Secrétaire Général',
                'adresse' => 'Plateau, Abidjan',
                'ville' => 'Abidjan',
                'pays' => 'Côte d\'Ivoire',
                'est_actif' => true,
                'notes' => 'Bâtiments ministériels',
            ],

            // Entreprise 3: Niger Protection (ID 3)
            [
                'entreprise_id' => 3,
                'type_client' => 'entreprise',
                'nom' => null,
                'prenoms' => null,
                'raison_sociale' => 'Société Nigérienne d\'Electricité (NIGELEC)',
                'nif' => 'NIF-NE-1980-11111',
                'rc' => 'RC-NIGELEC/1980/111',
                'email' => 'direction@nigelec.ne',
                'telephone' => '+227 20 00 10',
                'telephone_secondaire' => null,
                'contact_principal_nom' => 'Maman Sani OUSMANE',
                'contact_principal_fonction' => 'Directeur Général',
                'adresse' => 'Avenue de l\'Indépendance, Niamey',
                'ville' => 'Niamey',
                'pays' => 'Niger',
                'est_actif' => true,
                'notes' => 'Siège + stations',
            ],
            [
                'entreprise_id' => 3,
                'type_client' => 'institution',
                'nom' => null,
                'prenoms' => null,
                'raison_sociale' => 'Ambassade de France au Niger',
                'nif' => null,
                'rc' => null,
                'email' => 'securite@ambafrance-ne.org',
                'telephone' => '+227 20 00 20',
                'telephone_secondaire' => null,
                'contact_principal_nom' => 'Monsieur Jean DUBOIS',
                'contact_principal_fonction' => 'Attaché de Sécurité',
                'adresse' => 'Rue de l\'Indépendance, Niamey',
                'ville' => 'Niamey',
                'pays' => 'Niger',
                'est_actif' => true,
                'notes' => 'Mission diplomatique',
            ],
        ];

        foreach ($clients as $clientData) {
            Client::create($clientData);
        }

        $this->command->info('✅ ' . count($clients) . ' clients créés avec succès!');
    }
}
