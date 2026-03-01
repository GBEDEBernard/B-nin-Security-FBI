<?php

namespace Database\Seeders;

use App\Models\Abonnement;
use Illuminate\Database\Seeder;

class AbonnementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Crée les 3 plans d'abonnement avec tarification fixe:
     * - Basic: 20-40 employés → 100,000 F/mois (3 mois)
     * - Premium: 41-100 employés → 150,000 F/mois
     * - Enterprise: 101-300 employés → 200,000 F/mois
     */
    public function run(): void
    {
        $this->command->info('📦 Création des plans d\'abonnement...');

        $plans = [
            // Basic - 20 à 40 employés - 100,000 F/mois (durée limitée 3 mois)
            [
                'formule' => 'basic',
                'description' => 'Pour les petites entreprises de sécurité (20-40 employés). Durée limitée à 3 mois.',
                'employes_min' => 20,
                'employes_max' => 40,
                'tarif_par_employe' => 0, // Non utilisé - prix fixe
                'tarif_employe_supplementaire' => 0,
                'sites_max' => 5,
                'type_formule' => 'basic',
                'duree_mois' => 3,
                'jours_essai' => 7,
                'date_debut' => now()->toDateString(),
                'date_fin' => now()->addMonths(3)->toDateString(),
                'date_fin_essai' => now()->addDays(7)->toDateString(),
                'montant_total' => 300000, // 100,000 x 3 mois
                'montant_mensuel' => 100000,
                'cycle_facturation' => 'trimestriel',
                'est_active' => true,
                'est_en_essai' => false,
                'est_renouvele_auto' => false,
                'statut' => 'actif',
                'notes' => 'Basic - Prix fixe: 100,000 F/mois pour 20-40 employés. Durée: 3 mois.',
            ],

            // Premium - 41 à 100 employés - 150,000 F/mois
            [
                'formule' => 'premium',
                'description' => 'Pour les moyennes entreprises de sécurité (41-100 employés). Sans limite de durée.',
                'employes_min' => 41,
                'employes_max' => 100,
                'tarif_par_employe' => 0, // Non utilisé - prix fixe
                'tarif_employe_supplementaire' => 0,
                'sites_max' => 15,
                'type_formule' => 'premium',
                'duree_mois' => null, // Non limitée
                'jours_essai' => 7,
                'date_debut' => now()->toDateString(),
                'date_fin' => null,
                'date_fin_essai' => now()->addDays(7)->toDateString(),
                'montant_total' => 150000,
                'montant_mensuel' => 150000,
                'cycle_facturation' => 'mensuel',
                'est_active' => true,
                'est_en_essai' => false,
                'est_renouvele_auto' => true,
                'statut' => 'actif',
                'notes' => 'Premium - Prix fixe: 150,000 F/mois pour 41-100 employés. Sans limite de durée.',
            ],

            // Enterprise - 101 à 300 employés - 200,000 F/mois
            [
                'formule' => 'enterprise',
                'description' => 'Pour les grandes entreprises de sécurité (101-300 employés). Sans limite de durée.',
                'employes_min' => 101,
                'employes_max' => 300,
                'tarif_par_employe' => 0, // Non utilisé - prix fixe
                'tarif_employe_supplementaire' => 0,
                'sites_max' => 50,
                'type_formule' => 'enterprise',
                'duree_mois' => null, // Non limitée
                'jours_essai' => 7,
                'date_debut' => now()->toDateString(),
                'date_fin' => null,
                'date_fin_essai' => now()->addDays(7)->toDateString(),
                'montant_total' => 200000,
                'montant_mensuel' => 200000,
                'cycle_facturation' => 'mensuel',
                'est_active' => true,
                'est_en_essai' => false,
                'est_renouvele_auto' => true,
                'statut' => 'actif',
                'notes' => 'Enterprise - Prix fixe: 200,000 F/mois pour 101-300 employés. Sans limite de durée.',
            ],
        ];

        foreach ($plans as $planData) {
            $plan = Abonnement::create($planData);
            
            $duree = $plan->duree_mois ? $plan->duree_mois . ' mois' : 'Non limitée';
            $this->command->info("  ✓ Plan '{$plan->formule}' créé - {$plan->employes_min}-{$plan->employes_max} employés à " . number_format($plan->montant_mensuel, 0, ',', ' ') . " F/mois (Durée: {$duree})");
        }

        $this->command->info('');
        $this->command->info('📊 Récapitulatif des tarifs (Prix Fixe) :');
        $this->command->info('   • Basic    : 20-40 employés  = 100,000 F/mois (3 mois)');
        $this->command->info('   • Premium  : 41-100 employés = 150,000 F/mois');
        $this->command->info('   • Enterprise: 101-300 employés = 200,000 F/mois');
    }
}

