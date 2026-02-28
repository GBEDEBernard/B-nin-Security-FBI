<?php

namespace Database\Seeders;

use App\Models\Entreprise;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EntrepriseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Crée 2 entreprises de sécurité avec les informations complètes.
     */
    public function run(): void
    {
        $entreprises = [
            // ============================================================
            // ENTREPRISE 1: Bénin Security Services (Siège)
            // ============================================================
            [
                'nom_entreprise' => 'Bénin Security Services',
                'slug' => 'benin-security',
                'nom_commercial' => 'Bénin Security',
                'forme_juridique' => 'SARL',
                'numero_registre' => 'RC/ABC/2023/001',
                'numeroIdentificationFiscale' => 'NIF-2023-001-ABC',
                'numeroContribuable' => 'NC-2023-001',
                'email' => 'contact@benin-security.bj',
                'telephone' => '+229 21 30 00 01',
                'telephone_alternatif' => '+229 21 30 00 02',
                'adresse' => 'Rue 412 Akpakpa, Cotonou',
                'ville' => 'Cotonou',
                'pays' => 'Bénin',
                'code_postal' => '01 BP 1234',
                'nom_representant_legal' => 'Jean-Baptiste DOSSOU',
                'email_representant_legal' => 'jb.dossou@benin-security.bj',
                'telephone_representant_legal' => '+229 97 00 00 01',
                'logo' => null,
                'couleur_primaire' => '#1a237e',
                'couleur_secondaire' => '#ff6f00',
                'formule' => 'enterprise',
                'nombre_agents_max' => 100,
                'nombre_sites_max' => 50,
                'date_debut_contrat' => '2024-01-01',
                'date_fin_contrat' => '2025-12-31',
                'montant_mensuel' => 500000,
                'cycle_facturation' => 'mensuel',
                'est_active' => true,
                'est_en_essai' => false,
                'parametres' => [
                    'rayon_gps_defaut' => 300,
                    'fuseau_horaire' => 'Africa/Porto-Novo',
                    'devise' => 'XOF',
                    'symbole_devise' => 'FCFA',
                ],
                'notes' => 'Entreprise de sécurité leaders au Bénin - Siège',
            ],

            // ============================================================
            // ENTREPRISE 2: Guard Pro Côte d'Ivoire
            // ============================================================
            [
                'nom_entreprise' => 'Guard Pro Côte d\'Ivoire',
                'slug' => 'guard-pro-ci',
                'nom_commercial' => 'Guard Pro',
                'forme_juridique' => 'SA',
                'numero_registre' => 'RC/ABJ/2022/456',
                'numeroIdentificationFiscale' => 'NIF-2022-456-CI',
                'numeroContribuable' => 'NC-2022-456',
                'email' => 'info@guardpro.ci',
                'telephone' => '+225 27 20 00 01',
                'telephone_alternatif' => '+225 27 20 00 02',
                'adresse' => 'Plateau, Rue des Banques, Abidjan',
                'ville' => 'Abidjan',
                'pays' => 'Côte d\'Ivoire',
                'code_postal' => '01 BP 5678',
                'nom_representant_legal' => 'Koffi ANOMA',
                'email_representant_legal' => 'k.anoma@guardpro.ci',
                'telephone_representant_legal' => '+225 07 00 00 01',
                'logo' => null,
                'couleur_primaire' => '#0d47a1',
                'couleur_secondaire' => '#ffd600',
                'formule' => 'premium',
                'nombre_agents_max' => 50,
                'nombre_sites_max' => 25,
                'date_debut_contrat' => '2024-03-01',
                'date_fin_contrat' => '2025-03-01',
                'montant_mensuel' => 350000,
                'cycle_facturation' => 'mensuel',
                'est_active' => true,
                'est_en_essai' => false,
                'parametres' => [
                    'rayon_gps_defaut' => 200,
                    'fuseau_horaire' => 'Africa/Abidjan',
                    'devise' => 'XOF',
                    'symbole_devise' => 'FCFA',
                ],
                'notes' => 'Filiale en Côte d\'Ivoire',
            ],
        ];

        foreach ($entreprises as $entrepriseData) {
            Entreprise::create($entrepriseData);
        }

        $this->command->info('✅ ' . count($entreprises) . ' entreprises créées avec succès!');
    }
}
