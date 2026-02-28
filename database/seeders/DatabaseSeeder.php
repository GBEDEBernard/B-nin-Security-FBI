<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * 
     * Ordre d'exécution:
     * 1. RolesAndPermissionsSeeder - Crée les rôles et permissions
     * 2. SuperAdminSeeder - Crée le SuperAdmin
     * 3. EntrepriseSeeder - Crée les 4 entreprises
     * 4. EmployeSeeder - Crée les employés pour chaque entreprise
     * 5. ClientSeeder - Crée les clients pour chaque entreprise
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            SuperAdminSeeder::class,
            EntrepriseSeeder::class,
            EmployeSeeder::class,
            ClientSeeder::class,
        ]);
    }
}
