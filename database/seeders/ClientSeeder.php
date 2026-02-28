<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Entreprise;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Crée des clients pour chaque entreprise avec mot de passe pour connexion.
     */
    public function run(): void
    {
        $entreprises = Entreprise::all();
        $password = Hash::make('password123');

        // Pour chaque entreprise, créer des clients
        $clientsData = [];

        foreach ($entreprises as $entreprise) {
            if ($entreprise->id == 1) {
                // Benin Security - 4 clients
                $clientsData[] = [
                    'entreprise_id' => $entreprise->id,
                    'type_client' => 'entreprise',
                    'nom' => null,
                    'prenoms' => null,
                    'raison_sociale' => 'Société Béninoise de Télécommunications (SBT)',
                    'nif' => 'NIF-2018-12345',
                    'rc' => 'RC-ABT/2018/1234',
                    'email' => 'direction@sbt.bj',
                    'password' => $password,
                    'telephone' => '+229 21 30 00 10',
                    'telephone_secondaire' => '+229 21 30 00 11',
                    'contact_principal_nom' => 'Michel ALOKO',
                    'contact_principal_fonction' => 'Directeur des Opérations',
                    'adresse' => 'Boulevard de la Marina, Cotonou',
                    'ville' => 'Cotonou',
                    'pays' => 'Bénin',
                    'est_actif' => true,
                    'notes' => 'Client historique depuis 2020',
                ];
                $clientsData[] = [
                    'entreprise_id' => $entreprise->id,
                    'type_client' => 'institution',
                    'nom' => null,
                    'prenoms' => null,
                    'raison_sociale' => 'Université d\'Abomey-Calavi (UAC)',
                    'nif' => 'NIF-2015-67890',
                    'rc' => 'RC-UAC/2015/567',
                    'email' => 'sg@uac.bj',
                    'password' => $password,
                    'telephone' => '+229 21 30 00 20',
                    'telephone_secondaire' => null,
                    'contact_principal_nom' => 'Professeur Joseph KOKO',
                    'contact_principal_fonction' => 'Secrétaire Général',
                    'adresse' => 'Campus d\'Abomey-Calavi',
                    'ville' => 'Abomey-Calavi',
                    'pays' => 'Bénin',
                    'est_actif' => true,
                    'notes' => 'Campus principal - 3 sites',
                ];
                $clientsData[] = [
                    'entreprise_id' => $entreprise->id,
                    'type_client' => 'particulier',
                    'nom' => 'TOSSOU',
                    'prenoms' => 'David Alexandre',
                    'raison_sociale' => null,
                    'nif' => null,
                    'rc' => null,
                    'email' => 'd.tossou@gmail.com',
                    'password' => $password,
                    'telephone' => '+229 97 10 00 01',
                    'telephone_secondaire' => null,
                    'contact_principal_nom' => null,
                    'contact_principal_fonction' => null,
                    'adresse' => 'Fidjrossè, Cotonou',
                    'ville' => 'Cotonou',
                    'pays' => 'Bénin',
                    'est_actif' => true,
                    'notes' => 'Résidence privée',
                ];
                $clientsData[] = [
                    'entreprise_id' => $entreprise->id,
                    'type_client' => 'entreprise',
                    'nom' => null,
                    'prenoms' => null,
                    'raison_sociale' => 'Banque Atlantique Bénin',
                    'nif' => 'NIF-2010-11111',
                    'rc' => 'RC-BAB/2010/1111',
                    'email' => 'securite@atlantique-benin.bj',
                    'password' => $password,
                    'telephone' => '+229 21 30 00 30',
                    'telephone_secondaire' => '+229 21 30 00 31',
                    'contact_principal_nom' => 'Pierre HOUNGBO',
                    'contact_principal_fonction' => 'Responsable Sécurité',
                    'adresse' => 'Rue de la Bourse, Cotonou',
                    'ville' => 'Cotonou',
                    'pays' => 'Bénin',
                    'est_actif' => true,
                    'notes' => 'Agence principale + 5 agences',
                ];
            } elseif ($entreprise->id == 2) {
                // Guard Pro CI - 2 clients
                $clientsData[] = [
                    'entreprise_id' => $entreprise->id,
                    'type_client' => 'entreprise',
                    'nom' => null,
                    'prenoms' => null,
                    'raison_sociale' => 'Orange Côte d\'Ivoire',
                    'nif' => 'NIF-CI-2010-11111',
                    'rc' => 'RC-OCI/2010/1111',
                    'email' => 'securite@orange.ci',
                    'password' => $password,
                    'telephone' => '+225 27 20 00 10',
                    'telephone_secondaire' => '+225 27 20 00 11',
                    'contact_principal_nom' => 'Jean-Baptiste KOUASSI',
                    'contact_principal_fonction' => 'Chef Sécurité',
                    'adresse' => 'Treichville, Abidjan',
                    'ville' => 'Abidjan',
                    'pays' => 'Côte d\'Ivoire',
                    'est_actif' => true,
                    'notes' => 'Siège social + 10 agences',
                ];
                $clientsData[] = [
                    'entreprise_id' => $entreprise->id,
                    'type_client' => 'institution',
                    'nom' => null,
                    'prenoms' => null,
                    'raison_sociale' => 'Ministère de la Défense',
                    'nif' => 'NIF-CI-GOV-001',
                    'rc' => 'RC-MD/2015/001',
                    'email' => 'secretariat@defense.gouv.ci',
                    'password' => $password,
                    'telephone' => '+225 27 20 00 20',
                    'telephone_secondaire' => null,
                    'contact_principal_nom' => 'Colonel Bakayoko',
                    'contact_principal_fonction' => 'Secrétaire Général',
                    'adresse' => 'Plateau, Abidjan',
                    'ville' => 'Abidjan',
                    'pays' => 'Côte d\'Ivoire',
                    'est_actif' => true,
                    'notes' => 'Bâtiments ministériels',
                ];
            } elseif ($entreprise->id == 3) {
                // Niger Protection - 2 clients
                $clientsData[] = [
                    'entreprise_id' => $entreprise->id,
                    'type_client' => 'entreprise',
                    'nom' => null,
                    'prenoms' => null,
                    'raison_sociale' => 'Société Nigérienne d\'Electricité (NIGELEC)',
                    'nif' => 'NIF-NE-1980-11111',
                    'rc' => 'RC-NIGELEC/1980/111',
                    'email' => 'direction@nigelec.ne',
                    'password' => $password,
                    'telephone' => '+227 20 00 10',
                    'telephone_secondaire' => null,
                    'contact_principal_nom' => 'Maman Sani OUSMANE',
                    'contact_principal_fonction' => 'Directeur Général',
                    'adresse' => 'Avenue de l\'Indépendance, Niamey',
                    'ville' => 'Niamey',
                    'pays' => 'Niger',
                    'est_actif' => true,
                    'notes' => 'Siège + stations',
                ];
                $clientsData[] = [
                    'entreprise_id' => $entreprise->id,
                    'type_client' => 'institution',
                    'nom' => null,
                    'prenoms' => null,
                    'raison_sociale' => 'Ambassade de France au Niger',
                    'nif' => null,
                    'rc' => null,
                    'email' => 'securite@ambafrance-ne.org',
                    'password' => $password,
                    'telephone' => '+227 20 00 20',
                    'telephone_secondaire' => null,
                    'contact_principal_nom' => 'Monsieur Jean DUBOIS',
                    'contact_principal_fonction' => 'Attaché de Sécurité',
                    'adresse' => 'Rue de l\'Indépendance, Niamey',
                    'ville' => 'Niamey',
                    'pays' => 'Niger',
                    'est_actif' => true,
                    'notes' => 'Mission diplomatique',
                ];
            } elseif ($entreprise->id == 4) {
                // Togo Sécurité - 2 clients
                $clientsData[] = [
                    'entreprise_id' => $entreprise->id,
                    'type_client' => 'entreprise',
                    'nom' => null,
                    'prenoms' => null,
                    'raison_sociale' => 'Banque Togolaise du Commerce',
                    'nif' => 'NIF-TG-2010-11111',
                    'rc' => 'RC-BTC/2010/1111',
                    'email' => 'securite@btc.tg',
                    'password' => $password,
                    'telephone' => '+228 22 00 10',
                    'telephone_secondaire' => null,
                    'contact_principal_nom' => 'Koffi AWO',
                    'contact_principal_fonction' => 'Directeur Sécurité',
                    'adresse' => 'Rue de la Chance, Lomé',
                    'ville' => 'Lomé',
                    'pays' => 'Togo',
                    'est_actif' => true,
                    'notes' => 'Siège + agences',
                ];
                $clientsData[] = [
                    'entreprise_id' => $entreprise->id,
                    'type_client' => 'entreprise',
                    'nom' => null,
                    'prenoms' => null,
                    'raison_sociale' => 'Société Togolaise d\'Electricité (SAEB)',
                    'nif' => 'NIF-TG-1975-11111',
                    'rc' => 'RC-SAEB/1975/111',
                    'email' => 'direction@saeb.tg',
                    'password' => $password,
                    'telephone' => '+228 22 00 20',
                    'telephone_secondaire' => null,
                    'contact_principal_nom' => 'Komlan AGBE',
                    'contact_principal_fonction' => 'Directeur Général',
                    'adresse' => 'Avenue de la Liberation, Lomé',
                    'ville' => 'Lomé',
                    'pays' => 'Togo',
                    'est_actif' => true,
                    'notes' => 'Siège + agglomérations',
                ];
            }
        }

        foreach ($clientsData as $clientData) {
            Client::create($clientData);
        }

        $total = Client::count();
        $this->command->info('================================================');
        $this->command->info("✅ {$total} clients créés avec succès!");
        $this->command->info('================================================');
        $this->command->info('');
        $this->command->info('--- INFORMATIONS DE CONNEXION CLIENTS ---');
        $this->command->info('');
        $this->command->info('Mot de passe pour tous les clients: password123');
        $this->command->info('');
        $this->command->info('Exemples:');
        $this->command->info('  direction@sbt.bj          (SBT - Benin Security)');
        $this->command->info('  securite@orange.ci       (Orange CI - Guard Pro)');
        $this->command->info('  direction@nigelec.ne      (NIGELEC - Niger Protection)');
        $this->command->info('  securite@btc.tg          (BTC - Togo Sécurité)');
        $this->command->info('');
    }
}
