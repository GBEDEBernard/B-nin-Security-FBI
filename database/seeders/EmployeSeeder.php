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
     * - 1 Directeur Général (DG)
     * - 1 Superviseur
     * - 1 Contrôleur
     * - 1 Agent
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
            // 1. DIRECTEUR GÉNÉRAL
            // ============================================================
            $dg = Employe::firstOrCreate(
                ['email' => 'dg@' . $entreprise->slug . '.bj'],
                [
                    'entreprise_id' => $entreprise->id,
                    'matricule' => 'DG-' . strtoupper($entreprise->slug) . '-001',
                    'civilite' => 'M',
                    'nom' => 'AKAKPO',
                    'prenoms' => 'Jean-Baptiste',
                    'email' => 'dg@' . $entreprise->slug . '.bj',
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
            // 2. SUPERVISEUR
            // ============================================================
            $superviseur = Employe::firstOrCreate(
                ['email' => 'superviseur@' . $entreprise->slug . '.bj'],
                [
                    'entreprise_id' => $entreprise->id,
                    'matricule' => 'SUP-' . strtoupper($entreprise->slug) . '-001',
                    'civilite' => 'M',
                    'nom' => 'HOUNKPATIN',
                    'prenoms' => 'Michel',
                    'email' => 'superviseur@' . $entreprise->slug . '.bj',
                    'password' => $password,
                    'cni' => 'CI-002-' . strtoupper($entreprise->slug),
                    'date_naissance' => '1985-08-20',
                    'lieu_naissance' => $entreprise->ville,
                    'telephone' => '+229 97 00 00 02',
                    'adresse' => $entreprise->adresse,
                    'categorie' => 'supervision',
                    'poste' => 'superviseur_general',
                    'niveau_hierarchique' => 2,
                    'type_contrat' => 'cdi',
                    'date_embauche' => '2024-02-01',
                    'salaire_base' => 350000,
                    'est_actif' => true,
                    'disponible' => true,
                    'statut' => 'en_poste',
                    'est_connecte' => false,
                ]
            );

            // ============================================================
            // 3. CONTRÔLEUR
            // ============================================================
            $controleur = Employe::firstOrCreate(
                ['email' => 'controleur@' . $entreprise->slug . '.bj'],
                [
                    'entreprise_id' => $entreprise->id,
                    'matricule' => 'CTL-' . strtoupper($entreprise->slug) . '-001',
                    'civilite' => 'M',
                    'nom' => 'ADEBO',
                    'prenoms' => 'Patrick',
                    'email' => 'controleur@' . $entreprise->slug . '.bj',
                    'password' => $password,
                    'cni' => 'CI-003-' . strtoupper($entreprise->slug),
                    'date_naissance' => '1988-03-10',
                    'lieu_naissance' => $entreprise->ville,
                    'telephone' => '+229 97 00 00 03',
                    'adresse' => $entreprise->adresse,
                    'categorie' => 'controle',
                    'poste' => 'controleur',
                    'niveau_hierarchique' => 3,
                    'type_contrat' => 'cdi',
                    'date_embauche' => '2024-03-01',
                    'salaire_base' => 250000,
                    'est_actif' => true,
                    'disponible' => true,
                    'statut' => 'en_poste',
                    'est_connecte' => false,
                ]
            );

            // ============================================================
            // 4. AGENT DE TERRAIN
            // ============================================================
            $agent = Employe::firstOrCreate(
                ['email' => 'agent@' . $entreprise->slug . '.bj'],
                [
                    'entreprise_id' => $entreprise->id,
                    'matricule' => 'AGT-' . strtoupper($entreprise->slug) . '-001',
                    'civilite' => 'M',
                    'nom' => 'TOSSOU',
                    'prenoms' => 'Alain',
                    'email' => 'agent@' . $entreprise->slug . '.bj',
                    'password' => $password,
                    'cni' => 'CI-004-' . strtoupper($entreprise->slug),
                    'date_naissance' => '1995-12-01',
                    'lieu_naissance' => $entreprise->ville,
                    'telephone' => '+229 97 00 00 04',
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

            $this->command->info("  ✅ DG: {$dg->email}");
            $this->command->info("  ✅ Superviseur: {$superviseur->email}");
            $this->command->info("  ✅ Contrôleur: {$controleur->email}");
            $this->command->info("  ✅ Agent: {$agent->email}");
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
        $this->command->info('  DG:        dg@[slug].bj       | Mot de passe: password123');
        $this->command->info('  Superviseur: superviseur@[slug].bj | Mot de passe: password123');
        $this->command->info('  Contrôleur: controleur@[slug].bj  | Mot de passe: password123');
        $this->command->info('  Agent:     agent@[slug].bj      | Mot de passe: password123');
        $this->command->info('');
        $this->command->info('Exemple (Bénin Security):');
        $this->command->info('  DG:        dg@benin-security.bj       | Mot de passe: password123');
        $this->command->info('');
    }
}
