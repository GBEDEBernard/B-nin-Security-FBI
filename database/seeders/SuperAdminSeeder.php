<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Crée le SuperAdmin User qui a tous les rôles et est actif.
     * C'est le développeur/admin qui peut donner des rôles aux autres utilisateurs.
     */
    public function run(): void
    {
        // ============================================================
        // 1. SUPER ADMIN (Platform Administrator)
        // Le seul utilisateur User qui peut gérer la plateforme
        // ============================================================

        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@benin-security.bj'],
            [
                'name' => 'Super Administrateur',
                'telephone' => '+229 12 34 56 78',
                'password' => Hash::make('admin@BenSecure2026'),
                'email_verified_at' => now(),
                'is_superadmin' => true,
                'is_active' => true,
            ]
        );

        // Assigner le rôle super_admin
        if (method_exists($superAdmin, 'assignRole')) {
            $superAdmin->assignRole('super_admin');
        }

        // ============================================================
        // INFORMATIONS DE CONNEXION
        // ============================================================

        $this->command->info('================================================');
        $this->command->info('✅ SuperAdmin seeded avec succès!');
        $this->command->info('================================================');
        $this->command->info('');
        $this->command->info('--- SUPER ADMINISTRATOR ---');
        $this->command->info('Email: admin@benin-security.bj');
        $this->command->info('Mot de passe: admin@BenSecure2026');
        $this->command->info('');
        $this->command->info('URL: /admin/superadmin');
        $this->command->info('');
        $this->command->info('================================================');
        $this->command->info('');
        $this->command->info('⚠️  NOTE:');
        $this->command->info('- Les employés se connectent via la page /login avec leur email Employe');
        $this->command->info('- Les clients se connectent via la page /login avec leur email Client');
        $this->command->info('- Utilisez EmployeSeeder pour créer les employés');
        $this->command->info('');
    }
}
