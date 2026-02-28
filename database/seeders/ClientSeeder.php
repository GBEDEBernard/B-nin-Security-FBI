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
     * Crée 2 clients pour chaque entreprise avec mot de passe pour connexion.
     */
    public function run(): void
    {
        $entreprises = Entreprise::all();
        $password = Hash::make('password123');

        // Pour chaque entreprise, créer 2 clients
        $clientsData = [];

        foreach ($entreprises as $entreprise) {
            if ($entreprise->id == 1) {
                // Benin Security - 2 clients
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
        $this->command->info('');
    }
}
