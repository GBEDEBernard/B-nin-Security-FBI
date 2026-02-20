<?php

namespace Database\Seeders;

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
        // Create super admin user
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@benin-security.local'],
            [
                'name' => 'Super Admin',
                'phone' => '+229 12 34 56 78',
                'password' => Hash::make('admin@BenSecure2026'),
                'email_verified_at' => now(),
            ]
        );

        // Assign super_admin role
        $superAdmin->assignRole('super_admin');

        // Create test users with different roles
        $testUsers = [
            [
                'name' => 'Directeur Général',
                'email' => 'general-director@benin-security.local',
                'role' => 'general_director',
            ],
            [
                'name' => 'Directeur Adjoint',
                'email' => 'deputy-director@benin-security.local',
                'role' => 'deputy_director',
            ],
            [
                'name' => 'Directeur des Opérations',
                'email' => 'operations-director@benin-security.local',
                'role' => 'operations_director',
            ],
            [
                'name' => 'Superviseur',
                'email' => 'supervisor@benin-security.local',
                'role' => 'supervisor',
            ],
            [
                'name' => 'Contrôleur',
                'email' => 'controller@benin-security.local',
                'role' => 'controller',
            ],
            [
                'name' => 'Agent de Sécurité',
                'email' => 'agent@benin-security.local',
                'role' => 'agent',
            ],
            [
                'name' => 'Client Particulier',
                'email' => 'client-individual@benin-security.local',
                'role' => 'client_individual',
            ],
            [
                'name' => 'Client Entreprise',
                'email' => 'client-company@benin-security.local',
                'role' => 'client_company',
            ],
        ];

        $defaultPassword = Hash::make('test@BenSecure2026');

        foreach ($testUsers as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'phone' => '+229 90 00 00 00',
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
