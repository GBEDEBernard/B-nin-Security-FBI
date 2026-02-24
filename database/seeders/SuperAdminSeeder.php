<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Entreprise;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultPassword = Hash::make('test@BenSecure2026');

        // ============================================================
        // 1. SUPER ADMIN (Platform Administrator)
        // ============================================================

        // Create or get the default enterprise for the main platform
        $mainEntreprise = Entreprise::firstOrCreate(
            ['email' => 'benin-security@benin-security.local'],
            [
                'nom_entreprise' => 'Bénin Security',
                'slug' => 'benin-security',
                'nom_commercial' => 'Bénin Security',
                'forme_juridique' => 'SARL',
                'numero_registre' => 'RC/2024/001',
                'numeroIdentificationFiscale' => 'NIF-2024-001',
                'numeroContribuable' => 'NC-2024-001',
                'email' => 'benin-security@benin-security.local',
                'telephone' => '+229 11 22 33 44',
                'adresse' => 'Cotonou, République du Bénin',
                'ville' => 'Cotonou',
                'pays' => 'Bénin',
                'nom_representant_legal' => 'Administrateur',
                'email_representant_legal' => 'admin@benin-security.local',
                'telephone_representant_legal' => '+229 12 34 56 78',
                'formule' => 'entreprise',
                'nombre_agents_max' => 100,
                'nombre_sites_max' => 50,
                'est_active' => true,
                'est_en_essai' => false,
            ]
        );

        // Create super admin user
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@benin-security.local'],
            [
                'name' => 'Super Administrateur',
                'telephone' => '+229 12 34 56 78',
                'password' => Hash::make('admin@BenSecure2026'),
                'email_verified_at' => now(),
                'entreprise_id' => $mainEntreprise->id,
                'is_superadmin' => true,
                'type_user' => 'interne',
                'is_active' => true,
            ]
        );
        $superAdmin->assignRole('super_admin');

        // ============================================================
        // 2. ENTREPRISE 1: BÉNIN GARDIEN
        // ============================================================

        $entreprise1 = Entreprise::firstOrCreate(
            ['email' => 'contact@benin-gardien.bj'],
            [
                'nom_entreprise' => 'Bénin Gardien',
                'slug' => 'benin-gardien',
                'nom_commercial' => 'Bénin Gardien',
                'forme_juridique' => 'SARL',
                'numero_registre' => 'RC/2024/002',
                'numeroIdentificationFiscale' => 'NIF-2024-002',
                'numeroContribuable' => 'NC-2024-002',
                'email' => 'contact@benin-gardien.bj',
                'telephone' => '+229 21 30 40 50',
                'adresse' => 'Rue de la Présidence, Cotonou',
                'ville' => 'Cotonou',
                'pays' => 'Bénin',
                'nom_representant_legal' => 'Jean-Pierre DOSSOU',
                'email_representant_legal' => 'dg@benin-gardien.bj',
                'telephone_representant_legal' => '+229 90 11 22 33',
                'formule' => 'standard',
                'nombre_agents_max' => 50,
                'nombre_sites_max' => 25,
                'est_active' => true,
                'est_en_essai' => false,
            ]
        );

        // --- Personnel Entreprise 1 ---

        // Directeur Général
        $dg1 = User::firstOrCreate(
            ['email' => 'dg@benin-gardien.bj'],
            [
                'name' => 'Jean-Pierre DOSSOU',
                'telephone' => '+229 90 11 22 33',
                'password' => $defaultPassword,
                'email_verified_at' => now(),
                'entreprise_id' => $entreprise1->id,
                'is_superadmin' => false,
                'type_user' => 'interne',
                'is_active' => true,
            ]
        );
        $dg1->assignRole('general_director');

        // Directeur Adjoint
        $da1 = User::firstOrCreate(
            ['email' => 'da@benin-gardien.bj'],
            [
                'name' => 'Marie-Chantal AHOUANSOU',
                'telephone' => '+229 90 22 33 44',
                'password' => $defaultPassword,
                'email_verified_at' => now(),
                'entreprise_id' => $entreprise1->id,
                'is_superadmin' => false,
                'type_user' => 'interne',
                'is_active' => true,
            ]
        );
        $da1->assignRole('deputy_director');

        // Superviseur
        $superviseur1 = User::firstOrCreate(
            ['email' => 'superviseur@benin-gardien.bj'],
            [
                'name' => 'Pascal HOUNKANRIN',
                'telephone' => '+229 90 33 44 55',
                'password' => $defaultPassword,
                'email_verified_at' => now(),
                'entreprise_id' => $entreprise1->id,
                'is_superadmin' => false,
                'type_user' => 'interne',
                'is_active' => true,
            ]
        );
        $superviseur1->assignRole('supervisor');

        // Agent de terrain
        $agent1 = User::firstOrCreate(
            ['email' => 'agent@benin-gardien.bj'],
            [
                'name' => 'Antoine TCHIBOZO',
                'telephone' => '+229 90 44 55 66',
                'password' => $defaultPassword,
                'email_verified_at' => now(),
                'entreprise_id' => $entreprise1->id,
                'is_superadmin' => false,
                'type_user' => 'interne',
                'is_active' => true,
            ]
        );
        $agent1->assignRole('agent');

        // --- Clients Entreprise 1 ---

        // Client 1: Particulier
        $client1Particulier = Client::firstOrCreate(
            ['email' => 'michel.akinocho@email.bj'],
            [
                'entreprise_id' => $entreprise1->id,
                'type_client' => 'particulier',
                'nom' => 'AKINOCHO',
                'prenoms' => 'Michel',
                'email' => 'michel.akinocho@email.bj',
                'telephone' => '+229 95 11 22 33',
                'adresse' => 'Haie Vive, Cotonou',
                'ville' => 'Cotonou',
                'pays' => 'Bénin',
                'est_actif' => true,
            ]
        );

        // Créer utilisateur client pour le particulier
        $userClient1 = User::firstOrCreate(
            ['email' => 'michel.akinocho@email.bj'],
            [
                'name' => 'Michel AKINOCHO',
                'telephone' => '+229 95 11 22 33',
                'password' => $defaultPassword,
                'email_verified_at' => now(),
                'entreprise_id' => $entreprise1->id,
                'client_id' => $client1Particulier->id,
                'is_superadmin' => false,
                'type_user' => 'client',
                'is_active' => true,
            ]
        );
        $userClient1->assignRole('client_individual');

        // Client 2: Entreprise
        $client1Entreprise = Client::firstOrCreate(
            ['email' => 'contact@hotel-plaza.bj'],
            [
                'entreprise_id' => $entreprise1->id,
                'type_client' => 'entreprise',
                'raison_sociale' => 'Hôtel Plaza Cotonou',
                'nif' => 'NIF-2023-1050',
                'rc' => 'RC/2018/0456',
                'email' => 'contact@hotel-plaza.bj',
                'telephone' => '+229 21 31 00 00',
                'contact_principal_nom' => 'Robert GNIMADI',
                'contact_principal_fonction' => 'Directeur Général',
                'adresse' => 'Boulevard de la Marina, Cotonou',
                'ville' => 'Cotonou',
                'pays' => 'Bénin',
                'est_actif' => true,
            ]
        );

        // Créer utilisateur client pour l'entreprise
        $userClient1Ent = User::firstOrCreate(
            ['email' => 'contact@hotel-plaza.bj'],
            [
                'name' => 'Robert GNIMADI',
                'telephone' => '+229 21 31 00 00',
                'password' => $defaultPassword,
                'email_verified_at' => now(),
                'entreprise_id' => $entreprise1->id,
                'client_id' => $client1Entreprise->id,
                'is_superadmin' => false,
                'type_user' => 'client',
                'is_active' => true,
            ]
        );
        $userClient1Ent->assignRole('client_company');

        // ============================================================
        // 3. ENTREPRISE 2: SÉCURITÉ PLUS
        // ============================================================

        $entreprise2 = Entreprise::firstOrCreate(
            ['email' => 'contact@securite-plus.bj'],
            [
                'nom_entreprise' => 'Sécurité Plus',
                'slug' => 'securite-plus',
                'nom_commercial' => 'Sécurité Plus',
                'forme_juridique' => 'SASU',
                'numero_registre' => 'RC/2024/003',
                'numeroIdentificationFiscale' => 'NIF-2024-003',
                'numeroContribuable' => 'NC-2024-003',
                'email' => 'contact@securite-plus.bj',
                'telephone' => '+229 21 40 50 60',
                'adresse' => 'Akpakpa, Avenue de l\'Indépendance',
                'ville' => 'Cotonou',
                'pays' => 'Bénin',
                'nom_representant_legal' => 'François ALOYS',
                'email_representant_legal' => 'dg@securite-plus.bj',
                'telephone_representant_legal' => '+229 97 00 11 22',
                'formule' => 'standard',
                'nombre_agents_max' => 75,
                'nombre_sites_max' => 30,
                'est_active' => true,
                'est_en_essai' => false,
            ]
        );

        // --- Personnel Entreprise 2 ---

        // Directeur Général
        $dg2 = User::firstOrCreate(
            ['email' => 'dg@securite-plus.bj'],
            [
                'name' => 'François ALOYS',
                'telephone' => '+229 97 00 11 22',
                'password' => $defaultPassword,
                'email_verified_at' => now(),
                'entreprise_id' => $entreprise2->id,
                'is_superadmin' => false,
                'type_user' => 'interne',
                'is_active' => true,
            ]
        );
        $dg2->assignRole('general_director');

        // Directeur Adjoint
        $da2 = User::firstOrCreate(
            ['email' => 'da@securite-plus.bj'],
            [
                'name' => 'Sébastien HOUNGBÉDJAN',
                'telephone' => '+229 97 11 22 33',
                'password' => $defaultPassword,
                'email_verified_at' => now(),
                'entreprise_id' => $entreprise2->id,
                'is_superadmin' => false,
                'type_user' => 'interne',
                'is_active' => true,
            ]
        );
        $da2->assignRole('deputy_director');

        // Superviseur
        $superviseur2 = User::firstOrCreate(
            ['email' => 'superviseur@securite-plus.bj'],
            [
                'name' => 'Brice AMOUSSOU',
                'telephone' => '+229 97 22 33 44',
                'password' => $defaultPassword,
                'email_verified_at' => now(),
                'entreprise_id' => $entreprise2->id,
                'is_superadmin' => false,
                'type_user' => 'interne',
                'is_active' => true,
            ]
        );
        $superviseur2->assignRole('supervisor');

        // Agent de terrain
        $agent2 = User::firstOrCreate(
            ['email' => 'agent@securite-plus.bj'],
            [
                'name' => 'Jules SEHOUÉTO',
                'telephone' => '+229 97 33 44 55',
                'password' => $defaultPassword,
                'email_verified_at' => now(),
                'entreprise_id' => $entreprise2->id,
                'is_superadmin' => false,
                'type_user' => 'interne',
                'is_active' => true,
            ]
        );
        $agent2->assignRole('agent');

        // --- Clients Entreprise 2 ---

        // Client 1: Particulier
        $client2Particulier = Client::firstOrCreate(
            ['email' => 'jean.baptiste.houegban@email.bj'],
            [
                'entreprise_id' => $entreprise2->id,
                'type_client' => 'particulier',
                'nom' => 'HOUEGBAN',
                'prenoms' => 'Jean-Baptiste',
                'email' => 'jean.baptiste.houegban@email.bj',
                'telephone' => '+229 96 00 11 22',
                'adresse' => 'Fidrossè, Cotonou',
                'ville' => 'Cotonou',
                'pays' => 'Bénin',
                'est_actif' => true,
            ]
        );

        // Créer utilisateur client pour le particulier
        $userClient2 = User::firstOrCreate(
            ['email' => 'jean.baptiste.houegban@email.bj'],
            [
                'name' => 'Jean-Baptiste HOUEGBAN',
                'telephone' => '+229 96 00 11 22',
                'password' => $defaultPassword,
                'email_verified_at' => now(),
                'entreprise_id' => $entreprise2->id,
                'client_id' => $client2Particulier->id,
                'is_superadmin' => false,
                'type_user' => 'client',
                'is_active' => true,
            ]
        );
        $userClient2->assignRole('client_individual');

        // Client 2: Entreprise
        $client2Entreprise = Client::firstOrCreate(
            ['email' => 'direction@centre-commercial.json'],
            [
                'entreprise_id' => $entreprise2->id,
                'type_client' => 'entreprise',
                'raison_sociale' => 'Centre Commercial JSN',
                'nif' => 'NIF-2022-0875',
                'rc' => 'RC/2015/0234',
                'email' => 'direction@centre-commercial.json',
                'telephone' => '+229 21 35 10 00',
                'contact_principal_nom' => 'Aimé DJIMASRE',
                'contact_principal_fonction' => 'Directeur du Centre',
                'adresse' => 'Zone Industrielle, Godomey',
                'ville' => 'Abomey-Calavi',
                'pays' => 'Bénin',
                'est_actif' => true,
            ]
        );

        // Créer utilisateur client pour l'entreprise
        $userClient2Ent = User::firstOrCreate(
            ['email' => 'direction@centre-commercial.json'],
            [
                'name' => 'Aimé DJIMASRE',
                'telephone' => '+229 21 35 10 00',
                'password' => $defaultPassword,
                'email_verified_at' => now(),
                'entreprise_id' => $entreprise2->id,
                'client_id' => $client2Entreprise->id,
                'is_superadmin' => false,
                'type_user' => 'client',
                'is_active' => true,
            ]
        );
        $userClient2Ent->assignRole('client_company');

        // ============================================================
        // INFORMATIONS DE CONNEXION
        // ============================================================

        $this->command->info('================================================');
        $this->command->info('Données de connexion seeded avec succès!');
        $this->command->info('================================================');

        $this->command->info('');
        $this->command->info('--- SUPER ADMINISTRATOR ---');
        $this->command->info('Email: admin@benin-security.local');
        $this->command->info('Mot de passe: admin@BenSecure2026');

        $this->command->info('');
        $this->command->info('--- ENTREPRISE 1: BÉNIN GARDIEN ---');
        $this->command->info('DG: dg@benin-gardien.bj / test@BenSecure2026');
        $this->command->info('DA: da@benin-gardien.bj / test@BenSecure2026');
        $this->command->info('Superviseur: superviseur@benin-gardien.bj / test@BenSecure2026');
        $this->command->info('Agent: agent@benin-gardien.bj / test@BenSecure2026');
        $this->command->info('Client Particulier: michel.akinocho@email.bj / test@BenSecure2026');
        $this->command->info('Client Entreprise: contact@hotel-plaza.bj / test@BenSecure2026');

        $this->command->info('');
        $this->command->info('--- ENTREPRISE 2: SÉCURITÉ PLUS ---');
        $this->command->info('DG: dg@securite-plus.bj / test@BenSecure2026');
        $this->command->info('DA: da@securite-plus.bj / test@BenSecure2026');
        $this->command->info('Superviseur: superviseur@securite-plus.bj / test@BenSecure2026');
        $this->command->info('Agent: agent@securite-plus.bj / test@BenSecure2026');
        $this->command->info('Client Particulier: jean.baptiste.houegban@email.bj / test@BenSecure2026');
        $this->command->info('Client Entreprise: direction@centre-commercial.json / test@BenSecure2026');

        $this->command->info('');
        $this->command->info('================================================');
    }
}
