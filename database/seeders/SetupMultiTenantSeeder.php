<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Stancl\Tenancy\Facades\Tenancy;
use Stancl\Tenancy\Database\Models\Tenant;
use Stancl\Tenancy\Database\Models\Domain;
use App\Models\User;
use App\Models\Entreprise;
use App\Models\Employe;
use App\Models\Client;
use App\Models\Abonnement;
use App\Models\ContratPrestation;
use App\Models\Facture;
use Carbon\Carbon;

class SetupMultiTenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Crée la base centrale + 4 tenants avec données de test
     */
    public function run(): void
    {
        $this->command->info('========================================');
        $this->command->info('DÉBUT: Setup Multi-Tenant');
        $this->command->info('========================================');

        // 1. Créer le SuperAdmin
        $this->createSuperAdmin();

        // 2. Créer les 4 entreprises (tenants)
        $this->createTenants();

        $this->command->info('========================================');
        $this->command->info('FIN: Setup Multi-Tenant terminé!');
        $this->command->info('========================================');
        $this->command->info('');
        $this->command->info('Comptes de test:');
        $this->command->info('----------------');
        $this->command->info('SuperAdmin: admin@benin-security.com / password');
        $this->command->info('');
        $this->command->info('Entreprise 1 (Sécurité Alpha):');
        $this->command->info('  Directeur: directeur@alpha.benin-security.com / password');
        $this->command->info('  Agent: agent@alpha.benin-security.com / password');
        $this->command->info('  Client: client@alpha.benin-security.com / password');
        $this->command->info('');
        $this->command->info('Entreprise 2 (Bêta Protection):');
        $this->command->info('  Directeur: directeur@beta.benin-security.com / password');
        $this->command->info('  Agent: agent@beta.benin-security.com / password');
        $this->command->info('  Client: client@beta.benin-security.com / password');
    }

    /**
     * Créer le SuperAdmin
     */
    private function createSuperAdmin(): void
    {
        $this->command->info('Création du SuperAdmin...');

        $user = User::updateOrCreate(
            ['email' => 'admin@benin-security.com'],
            [
                'name' => 'Administrateur Système',
                'password' => bcrypt('password'),
                'is_superadmin' => true,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info("SuperAdmin créé: {$user->email}");
    }

    /**
     * Créer les 4 tenants avec données
     */
    private function createTenants(): void
    {
        $entreprises = [
            [
                'nom' => 'Sécurité Alpha',
                'sigle' => 'SA',
                'sous_domaine' => 'alpha',
                'email' => 'contact@alpha.benin-security.com',
                'telephone' => '+229 61 00 01 01',
                'adresse' => 'Cotonou, Rue de la Gare',
                'ifu' => '0123456789012',
                'rccm' => 'RC/CO/2020/001',
            ],
            [
                'nom' => 'Bêta Protection',
                'sigle' => 'BP',
                'sous_domaine' => 'beta',
                'email' => 'contact@beta.benin-security.com',
                'telephone' => '+229 61 00 02 02',
                'adresse' => 'Porto-Novo, Avenue de la République',
                'ifu' => '0123456789013',
                'rccm' => 'RC/CO/2020/002',
            ],
            [
                'nom' => 'Gamma Gardiennage',
                'sigle' => 'GG',
                'sous_domaine' => 'gamma',
                'email' => 'contact@gamma.benin-security.com',
                'telephone' => '+229 61 00 03 03',
                'adresse' => 'Abomey-Calavi, Zone Industrielle',
                'ifu' => '0123456789014',
                'rccm' => 'RC/CO/2020/003',
            ],
            [
                'nom' => 'Delta Sécurité',
                'sigle' => 'DS',
                'sous_domaine' => 'delta',
                'email' => 'contact@delta.benin-security.com',
                'telephone' => '+229 61 00 04 04',
                'adresse' => 'Cotonou, Boulevard de la Marina',
                'ifu' => '0123456789015',
                'rccm' => 'RC/CO/2020/004',
            ],
        ];

        foreach ($entreprises as $entrepriseData) {
            $this->createTenant($entrepriseData);
        }
    }

    /**
     * Créer un tenant avec toutes ses données
     */
    private function createTenant(array $data): void
    {
        $this->command->info("Création du tenant: {$data['nom']}");

        // Créer l'entreprise dans la base centrale
        $entreprise = Entreprise::updateOrCreate(
            ['email' => $data['email']],
            [
                'nom' => $data['nom'],
                'sigle' => $data['sigle'],
                'telephone' => $data['telephone'],
                'adresse' => $data['adresse'],
                'ifu' => $data['ifu'],
                'rccm' => $data['rccm'],
                'est_active' => true,
                'sous_domaine' => $data['sous_domaine'],
            ]
        );

        // Créer le tenant
        $tenantId = strtolower(Str::slug($data['nom']) . '-' . Str::random(6));

        $tenant = Tenant::updateOrCreate(
            ['id' => $tenantId],
            [
                'data' => [
                    'entreprise_id' => $entreprise->id,
                    'nom' => $data['nom'],
                ]
            ]
        );

        // Créer le domaine
        $domain = $data['sous_domaine'] . '.benin-security.com';
        Domain::updateOrCreate(
            ['domain' => $domain],
            [
                'tenant_id' => $tenant->id,
                'is_primary' => true,
            ]
        );

        // Mettre à jour l'entreprise avec le tenant_id
        $entreprise->update(['tenant_id' => $tenant->id]);

        $this->command->info("  Tenant ID: {$tenantId}");
        $this->command->info("  Domaine: {$domain}");

        // Initialiser le tenant et créer les données
        try {
            tenancy()->initialize($tenant);
            $this->createTenantData($entreprise, $data['sous_domaine']);
            $this->command->info("  Données créées avec succès!");
        } catch (\Exception $e) {
            $this->command->warn("  Erreur: " . $e->getMessage());
        }
    }

    /**
     * Créer les données pour un tenant (employés, clients, etc.)
     */
    private function createTenantData(Entreprise $entreprise, string $sousDomaine): void
    {
        // Créer le directeur
        $directeur = Employe::updateOrCreate(
            ['email' => "directeur@{$sousDomaine}.benin-security.com"],
            [
                'nom' => 'Directeur',
                'prenom' => 'Entreprise',
                'email' => "directeur@{$sousDomaine}.benin-security.com",
                'telephone' => '+229 61 10 00 01',
                'adresse' => $entreprise->adresse,
                'poste' => 'Directeur Général',
                'matricule' => 'DIR/' . strtoupper($sousDomaine) . '/001',
                'date_embauche' => Carbon::now()->subYears(2),
                'type_contrat' => 'cdi',
                'statut' => 'en_poste',
                'est_actif' => true,
                'password' => bcrypt('password'),
            ]
        );

        // Créer des agents
        $agents = [
            ['nom' => 'Koffi', 'prenom' => 'Amani', 'poste' => 'Agent de Sécurité'],
            ['nom' => 'Sossa', 'prenom' => 'Judith', 'poste' => 'Agent de Sécurité'],
            ['nom' => 'Akakpo', 'prenom' => 'Jean', 'poste' => 'Chef d\'équipe'],
        ];

        foreach ($agents as $index => $agent) {
            Employe::updateOrCreate(
                ['email' => "agent" . ($index + 1) . "@{$sousDomaine}.benin-security.com"],
                [
                    'nom' => $agent['nom'],
                    'prenom' => $agent['prenom'],
                    'email' => "agent" . ($index + 1) . "@{$sousDomaine}.benin-security.com",
                    'telephone' => '+229 61 20 00 ' . str_pad($index + 1, 2, '0', STR_PAD_LEFT),
                    'adresse' => 'Cotonou, Benin',
                    'poste' => $agent['poste'],
                    'matricule' => 'AGT/' . strtoupper($sousDomaine) . '/' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                    'date_embauche' => Carbon::now()->subMonths(rand(1, 24)),
                    'type_contrat' => 'cdi',
                    'statut' => 'en_poste',
                    'est_actif' => true,
                    'password' => bcrypt('password'),
                ]
            );
        }

        // Créer des clients
        $clients = [
            ['nom' => 'Sébastien', 'prenom' => 'Durand', 'entreprise' => 'Société Nouvelle'],
            ['nom' => 'Moukailou', 'prenom' => 'Issa', 'entreprise' => 'Moukailou Corp'],
            ['nom' => 'Tognissè', 'prenom' => 'Paul', 'entreprise' => 'PT Services'],
        ];

        foreach ($clients as $index => $clientData) {
            Client::updateOrCreate(
                ['email' => "client" . ($index + 1) . "@{$sousDomaine}.benin-security.com"],
                [
                    'nom' => $clientData['nom'],
                    'prenom' => $clientData['prenom'],
                    'email' => "client" . ($index + 1) . "@{$sousDomaine}.benin-security.com",
                    'telephone' => '+229 61 30 00 ' . str_pad($index + 1, 2, '0', STR_PAD_LEFT),
                    'adresse' => 'Cotonou, Benin',
                    'type' => 'personne_morale',
                    'entreprise_nom' => $clientData['entreprise'],
                    'representant_nom' => $clientData['nom'],
                    'representant_prenom' => $clientData['prenom'],
                    'representant_fonction' => 'Gérant',
                    'est_actif' => true,
                    'password' => bcrypt('password'),
                ]
            );
        }

        $this->command->info("  - {$directeur->poste} créé");
        $this->command->info("  - " . count($agents) . " agents créés");
        $this->command->info("  - " . count($clients) . " clients créés");
    }
}
