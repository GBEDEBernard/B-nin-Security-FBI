<?php

namespace Database\Seeders;

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
        // Create a default enterprise for the security company
        $entreprise = Entreprise::firstOrCreate(
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

        // Create super admin user linked to the enterprise
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@benin-security.local'],
            [
                'name' => 'Super Admin',
                'telephone' => '+229 12 34 56 78',
                'password' => Hash::make('admin@BenSecure2026'),
                'email_verified_at' => now(),
                'entreprise_id' => $entreprise->id,
                'is_superadmin' => true,
            ]
        );

        // Assign super_admin role
        $superAdmin->assignRole('super_admin');

        // Create test users with different roles
        $testUsers = [
            [
                'name' => 'Directeur Général',
                'email' => 'general-director@benin-security.local',
                'telephone' => '+229 90 00 00 01',
                'role' => 'general_director',
            ],
            [
                'name' => 'Directeur Adjoint',
                'email' => 'deputy-director@benin-security.local',
                'telephone' => '+229 90 00 00 02',
                'role' => 'deputy_director',
            ],
            [
                'name' => 'Directeur des Opérations',
                'email' => 'operations-director@benin-security.local',
                'telephone' => '+229 90 00 00 03',
                'role' => 'operations_director',
            ],
            [
                'name' => 'Superviseur',
                'email' => 'supervisor@benin-security.local',
                'telephone' => '+229 90 00 00 04',
                'role' => 'supervisor',
            ],
            [
                'name' => 'Contrôleur',
                'email' => 'controller@benin-security.local',
                'telephone' => '+229 90 00 00 05',
                'role' => 'controller',
            ],
            [
                'name' => 'Agent de Sécurité',
                'email' => 'agent@benin-security.local',
                'telephone' => '+229 90 00 00 06',
                'role' => 'agent',
            ],
            [
                'name' => 'Client Particulier',
                'email' => 'client-individual@benin-security.local',
                'telephone' => '+229 90 00 00 07',
                'role' => 'client_individual',
            ],
            [
                'name' => 'Client Entreprise',
                'email' => 'client-company@benin-security.local',
                'telephone' => '+229 90 00 00 08',
                'role' => 'client_company',
            ],
        ];

        $defaultPassword = Hash::make('test@BenSecure2026');

        foreach ($testUsers as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'telephone' => $userData['telephone'],
                    'password' => $defaultPassword,
                    'email_verified_at' => now(),
                ]
            );

            $user->assignRole($userData['role']);
        }

        $this->command->info('Super Admin and test users created successfully!');
        $this->command->info('Credentials:');
        $this->command->info('  Super Admin: admin@benin-security.local / admin@BenSecure2026');
        $this->command->info('  Other users password: test@BenSecure2026');
    }
}
