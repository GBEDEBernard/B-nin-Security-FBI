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
     * Crée 2 SuperAdmins pour la plateforme:
     * 1. Administrateur Principal
     * 2. Administrateur Technique
     * 
     * Ces utilisateurs ont tous les rôles et peuvent gérer la plateforme.
     */
    public function run(): void
    {
        // ============================================================
        // 1. SUPER ADMIN PRINCIPAL
        // ============================================================
        $superAdmin1 = User::firstOrCreate(
            ['email' => 'admin@benin-security.bj'],
            [
                'name' => 'Administrateur Principal',
                'telephone' => '+229 12 34 56 78',
                'password' => Hash::make('admin@BenSecure2026'),
                'email_verified_at' => now(),
                'is_superadmin' => true,
                'is_active' => true,
            ]
        );

        if (method_exists($superAdmin1, 'assignRole')) {
            $superAdmin1->assignRole('super_admin');
        }

        // ============================================================
        // 2. SUPER ADMIN TECHNIQUE
        // ============================================================
        $superAdmin2 = User::firstOrCreate(
            ['email' => 'techadmin@benin-security.bj'],
            [
                'name' => 'Administrateur Technique',
                'telephone' => '+229 12 34 56 79',
                'password' => Hash::make('tech@BenSecure2026'),
                'email_verified_at' => now(),
                'is_superadmin' => true,
                'is_active' => true,
            ]
        );

        if (method_exists($superAdmin2, 'assignRole')) {
            $superAdmin2->assignRole('super_admin');
        }

        // ============================================================
        // INFORMATIONS DE CONNEXION
        // ============================================================

        $this->command->info('================================================');
        $this->command->info('✅ SuperAdmins seeded avec succès!');
        $this->command->info('================================================');
        $this->command->info('');
        $this->command->info('--- SUPER ADMINISTRATEURS ---');
        $this->command->info('');
        $this->command->info('1. Administrateur Principal:');
        $this->command->info('   Email: admin@benin-security.bj');
        $this->command->info('   Mot de passe: admin@BenSecure2026');
        $this->command->info('');
        $this->command->info('2. Administrateur Technique:');
        $this->command->info('   Email: techadmin@benin-security.bj');
        $this->command->info('   Mot de passe: tech@BenSecure2026');
        $this->command->info('');
        $this->command->info('URL: /admin/superadmin');
        $this->command->info('');
        $this->command->info('================================================');
        $this->command->info('');
        $this->command->info('⚠️  NOTE:');
        $this->command->info('- Les employés se connectent via /login avec leur email Employe');
        $this->command->info('- Les clients se connectent via /login avec leur email Client');
        $this->command->info('- Utilisez EmployeSeeder pour créer les employés');
        $this->command->info('');
    }
}
