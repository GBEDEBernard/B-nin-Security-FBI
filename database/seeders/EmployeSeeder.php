<?php

namespace Database\Seeders;

use App\Models\Employe;
use App\Models\Entreprise;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Crée des employés pour chaque entreprise:
     * - 2 Directeurs Généraux (DG)
     * - 2 Agents de Sécurité
     * 
     * Chaque employé peut se connecter avec son email et mot de passe.
     */
    public function run(): void
    {
        $entreprises = Entreprise::all();

        $password = Hash::make('password123');

        foreach ($entreprises as $entreprise) {
            $this->command->info("Création des employés pour: {$entreprise->nom_entreprise}");

            // ============================================================
            // 1. DIRECTEUR GÉNÉRAL 1
            // ============================================================
            $dg1 = Employe::firstOrCreate(
                ['email' => 'dg1@' . $entreprise->slug . '.bj'],
                [
                    'entreprise_id' => $entreprise->id,
                    'matricule' => 'DG1-' . strtoupper($entreprise->slug) . '-001',
                    'civilite' => 'M',
                    'nom' => 'AKAKPO',
                    'prenoms' => 'Jean-Baptiste',
                    'email' => 'dg1@' . $entreprise->slug . '.bj',
                    'password' => $password,
                    'cni' => 'CI-001-' . strtoupper($entreprise->slug),
                    'date_naissance' => '1980-05-15',
                    'lieu_naissance' => $entreprise->ville,
                    'telephone' => '+229 97 00 00 01',
                    'adresse' => $entreprise->adresse,
                    'categorie' => 'direction',
                    'poste' => 'directeur_general',
                    'niveau_hierarchique' => 1,
                    'type_contrat' => 'cdi',
                    'date_embauche' => '2024-01-01',
                    'salaire_base' => 500000,
                    'est_actif' => true,
                    'disponible' => true,
                    'statut' => 'en_poste',
                    'est_connecte' => false,
                ]
            );

            // ============================================================
            // 2. DIRECTEUR GÉNÉRAL 2
            // ============================================================
            $dg2 = Employe::firstOrCreate(
                ['email' => 'dg2@' . $entreprise->slug . '.bj'],
                [
                    'entreprise_id' => $entreprise->id,
                    'matricule' => 'DG2-' . strtoupper($entreprise->slug) . '-001',
                    'civilite' => 'M',
                    'nom' => 'HOUNKPATIN',
                    'prenoms' => 'Michel',
                    'email' => 'dg2@' . $entreprise->slug . '.bj',
                    'password' => $password,
                    'cni' => 'CI-002-' . strtoupper($entreprise->slug),
                    'date_naissance' => '1985-08-20',
                    'lieu_naissance' => $entreprise->ville,
                    'telephone' => '+229 97 00 00 02',
                    'adresse' => $entreprise->adresse,
                    'categorie' => 'direction',
                    'poste' => 'directeur_general',
                    'niveau_hierarchique' => 1,
                    'type_contrat' => 'cdi',
                    'date_embauche' => '2024-02-01',
                    'salaire_base' => 450000,
                    'est_actif' => true,
                    'disponible' => true,
                    'statut' => 'en_poste',
                    'est_connecte' => false,
                ]
            );

            // ============================================================
            // 3. AGENT DE SÉCURITÉ 1
            // ============================================================
            $agent1 = Employe::firstOrCreate(
                ['email' => 'agent1@' . $entreprise->slug . '.bj'],
                [
                    'entreprise_id' => $entreprise->id,
                    'matricule' => 'AGT1-' . strtoupper($entreprise->slug) . '-001',
                    'civilite' => 'M',
                    'nom' => 'TOSSOU',
                    'prenoms' => 'Alain',
                    'email' => 'agent1@' . $entreprise->slug . '.bj',
                    'password' => $password,
                    'cni' => 'CI-003-' . strtoupper($entreprise->slug),
                    'date_naissance' => '1995-12-01',
                    'lieu_naissance' => $entreprise->ville,
                    'telephone' => '+229 97 00 00 03',
                    'adresse' => $entreprise->adresse,
                    'categorie' => 'agent',
                    'poste' => 'agent_terrain',
                    'niveau_hierarchique' => 5,
                    'type_contrat' => 'cdi',
                    'date_embauche' => '2024-04-01',
                    'salaire_base' => 150000,
                    'est_actif' => true,
                    'disponible' => true,
                    'statut' => 'en_poste',
                    'est_connecte' => false,
                ]
            );

            // ============================================================
            // 4. AGENT DE SÉCURITÉ 2
            // ============================================================
            $agent2 = Employe::firstOrCreate(
                ['email' => 'agent2@' . $entreprise->slug . '.bj'],
                [
                    'entreprise_id' => $entreprise->id,
                    'matricule' => 'AGT2-' . strtoupper($entreprise->slug) . '-001',
                    'civilite' => 'M',
                    'nom' => 'ADEBO',
                    'prenoms' => 'Patrick',
                    'email' => 'agent2@' . $entreprise->slug . '.bj',
                    'password' => $password,
                    'cni' => 'CI-004-' . strtoupper($entreprise->slug),
                    'date_naissance' => '1992-06-15',
                    'lieu_naissance' => $entreprise->ville,
                    'telephone' => '+229 97 00 00 04',
                    'adresse' => $entreprise->adresse,
                    'categorie' => 'agent',
                    'poste' => 'agent_terrain',
                    'niveau_hierarchique' => 5,
                    'type_contrat' => 'cdi',
                    'date_embauche' => '2024-05-01',
                    'salaire_base' => 150000,
                    'est_actif' => true,
                    'disponible' => true,
                    'statut' => 'en_poste',
                    'est_connecte' => false,
                ]
            );

            $this->command->info("  ✅ DG1: {$dg1->email}");
            $this->command->info("  ✅ DG2: {$dg2->email}");
            $this->command->info("  ✅ Agent1: {$agent1->email}");
            $this->command->info("  ✅ Agent2: {$agent2->email}");
        }

        $total = Employe::count();
        $this->command->info('');
        $this->command->info('================================================');
        $this->command->info("✅ {$total} employés créés avec succès!");
        $this->command->info('================================================');
        $this->command->info('');
        $this->command->info('--- INFORMATIONS DE CONNEXION EMPLOYÉS ---');
        $this->command->info('');
        $this->command->info('Pour chaque entreprise:');
        $this->command->info('  DG1:       dg1@[slug].bj       | Mot de passe: password123');
        $this->command->info('  DG2:       dg2@[slug].bj       | Mot de passe: password123');
        $this->command->info('  Agent1:    agent1@[slug].bj    | Mot de passe: password123');
        $this->command->info('  Agent2:    agent2@[slug].bj    | Mot de passe: password123');
        $this->command->info('');
        $this->command->info('Exemple (Bénin Security):');
        $this->command->info('  DG1:       dg1@benin-security.bj       | Mot de passe: password123');
        $this->command->info('  DG2:       dg2@benin-security.bj       | Mot de passe: password123');
        $this->command->info('  Agent1:    agent1@benin-security.bj    | Mot de passe: password123');
        $this->command->info('  Agent2:    agent2@benin-security.bj    | Mot de passe: password123');
        $this->command->info('');
    }
}
