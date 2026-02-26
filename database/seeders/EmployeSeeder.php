<?php

namespace Database\Seeders;

use App\Models\Employe;
use Illuminate\Database\Seeder;

class EmployeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Crée les employés avec leurs rôles selon leur poste.
     * Le premier employé (directeur_general) de chaque entreprise sera l'admin de cette entreprise.
     */
    public function run(): void
    {
        $defaultPassword = 'password123';

        // ============================================================
        // ENTREPRISE 1: BÉNIN SECURITY (ID 1)
        // ============================================================

        // Directeur Général - Admin de l'entreprise
        $dg = Employe::firstOrCreate(
            ['email' => 'dg@benin-security.bj'],
            [
                'entreprise_id' => 1,
                'matricule' => 'BSS-001',
                'civilite' => 'M',
                'nom' => 'DOSSOU',
                'prenoms' => 'Jean-Baptiste',
                'email' => 'dg@benin-security.bj',
                'password' => bcrypt($defaultPassword),
                'cni' => 'CI-BJ-1234567',
                'date_naissance' => '1980-05-15',
                'lieu_naissance' => 'Cotonou',
                'telephone' => '+229 97 00 01 01',
                'telephone_urgence' => '+229 96 00 01 01',
                'adresse' => 'Akpakpa, Cotonou',
                'categorie' => 'direction',
                'poste' => 'directeur_general',
                'niveau_hierarchique' => 1,
                'type_contrat' => 'cdi',
                'date_embauche' => '2020-01-01',
                'salaire_base' => 500000,
                'numero_cnss' => 'CNSS-BJ-2020-001',
                'est_actif' => true,
                'disponible' => true,
                'statut' => 'en_poste',
            ]
        );
        $dg->assignRole('general_director');

        // Directeur Adjoint
        $da = Employe::firstOrCreate(
            ['email' => 'da@benin-security.bj'],
            [
                'entreprise_id' => 1,
                'matricule' => 'BSS-002',
                'civilite' => 'M',
                'nom' => 'AKAKPO',
                'prenoms' => 'Koffi Jean-Marie',
                'email' => 'da@benin-security.bj',
                'password' => bcrypt($defaultPassword),
                'cni' => 'CI-BJ-1234568',
                'date_naissance' => '1985-07-22',
                'lieu_naissance' => 'Porto-Novo',
                'telephone' => '+229 97 00 01 02',
                'telephone_urgence' => '+229 96 00 01 02',
                'adresse' => 'Fidrossè, Cotonou',
                'categorie' => 'direction',
                'poste' => 'directeur_adjoint',
                'niveau_hierarchique' => 2,
                'type_contrat' => 'cdi',
                'date_embauche' => '2021-03-15',
                'salaire_base' => 400000,
                'numero_cnss' => 'CNSS-BJ-2021-002',
                'est_actif' => true,
                'disponible' => true,
                'statut' => 'en_poste',
            ]
        );
        $da->assignRole('deputy_director');

        // Superviseur Général
        $sup = Employe::firstOrCreate(
            ['email' => 'superviseur@benin-security.bj'],
            [
                'entreprise_id' => 1,
                'matricule' => 'BSS-003',
                'civilite' => 'M',
                'nom' => 'ADEKUNLE',
                'prenoms' => 'Mariam Chloé',
                'email' => 'superviseur@benin-security.bj',
                'password' => bcrypt($defaultPassword),
                'cni' => 'CI-BJ-1234569',
                'date_naissance' => '1988-11-08',
                'lieu_naissance' => 'Cotonou',
                'telephone' => '+229 97 00 01 03',
                'telephone_urgence' => '+229 96 00 01 03',
                'adresse' => 'Cadjehoun, Cotonou',
                'categorie' => 'supervision',
                'poste' => 'superviseur_general',
                'niveau_hierarchique' => 2,
                'type_contrat' => 'cdi',
                'date_embauche' => '2022-01-10',
                'salaire_base' => 350000,
                'numero_cnss' => 'CNSS-BJ-2022-003',
                'est_actif' => true,
                'disponible' => true,
                'statut' => 'en_poste',
            ]
        );
        $sup->assignRole('supervisor');

        // Contrôleur Principal
        $ctrl = Employe::firstOrCreate(
            ['email' => 'controleur@benin-security.bj'],
            [
                'entreprise_id' => 1,
                'matricule' => 'BSS-004',
                'civilite' => 'M',
                'nom' => 'HOUESSOU',
                'prenoms' => 'Antoine Didier',
                'email' => 'controleur@benin-security.bj',
                'password' => bcrypt($defaultPassword),
                'cni' => 'CI-BJ-1234570',
                'date_naissance' => '1990-05-20',
                'lieu_naissance' => 'Abomey-Calavi',
                'telephone' => '+229 97 00 01 04',
                'telephone_urgence' => '+229 96 00 01 04',
                'adresse' => 'Kpodji, Abomey-Calavi',
                'categorie' => 'controle',
                'poste' => 'controleur_principal',
                'niveau_hierarchique' => 3,
                'type_contrat' => 'cdi',
                'date_embauche' => '2022-06-01',
                'salaire_base' => 280000,
                'numero_cnss' => 'CNSS-BJ-2022-004',
                'est_actif' => true,
                'disponible' => true,
                'statut' => 'en_poste',
            ]
        );
        $ctrl->assignRole('controller');

        // Agent Terrain 1
        $agent1 = Employe::firstOrCreate(
            ['email' => 'agent1@benin-security.bj'],
            [
                'entreprise_id' => 1,
                'matricule' => 'BSS-005',
                'civilite' => 'M',
                'nom' => 'AMOUSSOU',
                'prenoms' => 'Rodrigue Kévin',
                'email' => 'agent1@benin-security.bj',
                'password' => bcrypt($defaultPassword),
                'cni' => 'CI-BJ-1234571',
                'date_naissance' => '1995-02-14',
                'lieu_naissance' => 'Cotonou',
                'telephone' => '+229 97 00 01 05',
                'telephone_urgence' => '+229 96 00 01 05',
                'adresse' => 'Mènontin, Cotonou',
                'categorie' => 'agent',
                'poste' => 'agent_terrain',
                'niveau_hierarchique' => 5,
                'type_contrat' => 'cdi',
                'date_embauche' => '2023-09-15',
                'salaire_base' => 150000,
                'numero_cnss' => 'CNSS-BJ-2023-005',
                'est_actif' => true,
                'disponible' => true,
                'statut' => 'en_poste',
            ]
        );
        $agent1->assignRole('agent');

        // Agent Terrain 2
        $agent2 = Employe::firstOrCreate(
            ['email' => 'agent2@benin-security.bj'],
            [
                'entreprise_id' => 1,
                'matricule' => 'BSS-006',
                'civilite' => 'Mlle',
                'nom' => 'YAI',
                'prenoms' => 'Lucette Dénise',
                'email' => 'agent2@benin-security.bj',
                'password' => bcrypt($defaultPassword),
                'cni' => 'CI-BJ-1234572',
                'date_naissance' => '1998-08-30',
                'lieu_naissance' => 'Ouidah',
                'telephone' => '+229 97 00 01 06',
                'telephone_urgence' => '+229 96 00 01 06',
                'adresse' => 'Ouidah',
                'categorie' => 'agent',
                'poste' => 'agent_mobile',
                'niveau_hierarchique' => 5,
                'type_contrat' => 'cdd',
                'date_embauche' => '2024-01-01',
                'date_fin_contrat' => '2024-12-31',
                'salaire_base' => 135000,
                'numero_cnss' => 'CNSS-BJ-2024-001',
                'est_actif' => true,
                'disponible' => true,
                'statut' => 'en_poste',
            ]
        );
        $agent2->assignRole('agent');

        // ============================================================
        // ENTREPRISE 2: GUARD PRO CI (ID 2)
        // ============================================================

        // Directeur Général
        $dg2 = Employe::firstOrCreate(
            ['email' => 'dg@guardpro.ci'],
            [
                'entreprise_id' => 2,
                'matricule' => 'GPC-001',
                'civilite' => 'M',
                'nom' => 'KONAN',
                'prenoms' => 'Yao Bernard',
                'email' => 'dg@guardpro.ci',
                'password' => bcrypt($defaultPassword),
                'cni' => 'CI-CI-4567890',
                'date_naissance' => '1983-04-12',
                'lieu_naissance' => 'Abidjan',
                'telephone' => '+225 07 00 02 01',
                'telephone_urgence' => '+225 06 00 02 01',
                'adresse' => 'Cocody, Abidjan',
                'categorie' => 'direction',
                'poste' => 'directeur_general',
                'niveau_hierarchique' => 1,
                'type_contrat' => 'cdi',
                'date_embauche' => '2019-06-01',
                'salaire_base' => 600000,
                'numero_cnss' => 'CNSS-CI-2019-001',
                'est_actif' => true,
                'disponible' => true,
                'statut' => 'en_poste',
            ]
        );
        $dg2->assignRole('general_director');

        // Superviseur Général
        $sup2 = Employe::firstOrCreate(
            ['email' => 'superviseur@guardpro.ci'],
            [
                'entreprise_id' => 2,
                'matricule' => 'GPC-002',
                'civilite' => 'M',
                'nom' => 'SILUE',
                'prenoms' => 'Mamadou',
                'email' => 'superviseur@guardpro.ci',
                'password' => bcrypt($defaultPassword),
                'cni' => 'CI-CI-4567891',
                'date_naissance' => '1990-09-25',
                'lieu_naissance' => 'Bouaké',
                'telephone' => '+225 07 00 02 02',
                'telephone_urgence' => '+225 06 00 02 02',
                'adresse' => 'Plateau, Abidjan',
                'categorie' => 'supervision',
                'poste' => 'superviseur_general',
                'niveau_hierarchique' => 2,
                'type_contrat' => 'cdi',
                'date_embauche' => '2020-03-01',
                'salaire_base' => 380000,
                'numero_cnss' => 'CNSS-CI-2020-002',
                'est_actif' => true,
                'disponible' => true,
                'statut' => 'en_poste',
            ]
        );
        $sup2->assignRole('supervisor');

        // Agent
        $agent3 = Employe::firstOrCreate(
            ['email' => 'agent@guardpro.ci'],
            [
                'entreprise_id' => 2,
                'matricule' => 'GPC-003',
                'civilite' => 'M',
                'nom' => 'COULIBALY',
                'prenoms' => 'Adama',
                'email' => 'agent@guardpro.ci',
                'password' => bcrypt($defaultPassword),
                'cni' => 'CI-CI-4567892',
                'date_naissance' => '1994-12-05',
                'lieu_naissance' => 'Daloa',
                'telephone' => '+225 07 00 02 03',
                'telephone_urgence' => '+225 06 00 02 03',
                'adresse' => 'Marcory, Abidjan',
                'categorie' => 'agent',
                'poste' => 'agent_terrain',
                'niveau_hierarchique' => 5,
                'type_contrat' => 'cdi',
                'date_embauche' => '2022-07-15',
                'salaire_base' => 155000,
                'numero_cnss' => 'CNSS-CI-2022-003',
                'est_actif' => true,
                'disponible' => true,
                'statut' => 'en_poste',
            ]
        );
        $agent3->assignRole('agent');

        // ============================================================
        // ENTREPRISE 3: NIGER PROTECTION (ID 3)
        // ============================================================

        // Directeur Général
        $dg3 = Employe::firstOrCreate(
            ['email' => 'dg@nigerprotection.ne'],
            [
                'entreprise_id' => 3,
                'matricule' => 'NPR-001',
                'civilite' => 'M',
                'nom' => 'IBRAHIM',
                'prenoms' => 'Mahamane',
                'email' => 'dg@nigerprotection.ne',
                'password' => bcrypt($defaultPassword),
                'cni' => 'NI-NE-7890123',
                'date_naissance' => '1980-01-01',
                'lieu_naissance' => 'Niamey',
                'telephone' => '+227 90 00 03 01',
                'telephone_urgence' => '+227 80 00 03 01',
                'adresse' => 'Avenue du Sahel, Niamey',
                'categorie' => 'direction',
                'poste' => 'directeur_general',
                'niveau_hierarchique' => 1,
                'type_contrat' => 'cdi',
                'date_embauche' => '2021-01-01',
                'salaire_base' => 450000,
                'numero_cnss' => 'CNSS-NE-2021-001',
                'est_actif' => true,
                'disponible' => true,
                'statut' => 'en_poste',
            ]
        );
        $dg3->assignRole('general_director');

        // Agent
        $agent4 = Employe::firstOrCreate(
            ['email' => 'agent@nigerprotection.ne'],
            [
                'entreprise_id' => 3,
                'matricule' => 'NPR-002',
                'civilite' => 'M',
                'nom' => 'MOUSSA',
                'prenoms' => 'Salifou',
                'email' => 'agent@nigerprotection.ne',
                'password' => bcrypt($defaultPassword),
                'cni' => 'NI-NE-7890124',
                'date_naissance' => '1992-06-15',
                'lieu_naissance' => 'Maradi',
                'telephone' => '+227 90 00 03 02',
                'telephone_urgence' => '+227 80 00 03 02',
                'adresse' => '、洗牌, Niamey',
                'categorie' => 'agent',
                'poste' => 'agent_terrain',
                'niveau_hierarchique' => 5,
                'type_contrat' => 'cdi',
                'date_embauche' => '2023-02-01',
                'salaire_base' => 130000,
                'numero_cnss' => 'CNSS-NE-2023-002',
                'est_actif' => true,
                'disponible' => true,
                'statut' => 'en_poste',
            ]
        );
        $agent4->assignRole('agent');

        // ============================================================
        // INFORMATIONS DE CONNEXION
        // ============================================================

        $this->command->info('================================================');
        $this->command->info('Employés seeded avec succès!');
        $this->command->info('================================================');
        $this->command->info('');
        $this->command->info('--- ENTREPRISE 1: BÉNIN SECURITY ---');
        $this->command->info('DG: dg@benin-security.bj / ' . $defaultPassword);
        $this->command->info('DA: da@benin-security.bj / ' . $defaultPassword);
        $this->command->info('Superviseur: superviseur@benin-security.bj / ' . $defaultPassword);
        $this->command->info('Contrôleur: controleur@benin-security.bj / ' . $defaultPassword);
        $this->command->info('Agent 1: agent1@benin-security.bj / ' . $defaultPassword);
        $this->command->info('Agent 2: agent2@benin-security.bj / ' . $defaultPassword);
        $this->command->info('');
        $this->command->info('--- ENTREPRISE 2: GUARD PRO CI ---');
        $this->command->info('DG: dg@guardpro.ci / ' . $defaultPassword);
        $this->command->info('Superviseur: superviseur@guardpro.ci / ' . $defaultPassword);
        $this->command->info('Agent: agent@guardpro.ci / ' . $defaultPassword);
        $this->command->info('');
        $this->command->info('--- ENTREPRISE 3: NIGER PROTECTION ---');
        $this->command->info('DG: dg@nigerprotection.ne / ' . $defaultPassword);
        $this->command->info('Agent: agent@nigerprotection.ne / ' . $defaultPassword);
        $this->command->info('');
        $this->command->info('================================================');
    }
}
