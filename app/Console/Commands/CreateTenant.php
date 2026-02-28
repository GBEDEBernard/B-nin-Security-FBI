<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Stancl\Tenancy\Facades\Tenancy;
use Stancl\Tenancy\Database\Models\Tenant;
use Stancl\Tenancy\Database\Models\Domain;
use App\Models\Entreprise;
use App\Models\Abonnement;
use Illuminate\Support\Str;

class CreateTenant extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:create
                            {entreprise_id : ID de l\'entreprise dans la base centrale}
                            {--domain= : Sous-domaine (ex: securite-alpha)}
                            {--skip-migration : Ne pas exécuter les migrations}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Créer un nouveau tenant (entreprise) avec son domaine';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $entrepriseId = $this->argument('entreprise_id');

        // Récupérer l'entreprise depuis la base centrale
        $entreprise = Entreprise::find($entrepriseId);

        if (!$entreprise) {
            $this->error("Entreprise avec ID {$entrepriseId} introuvable.");
            return Command::FAILURE;
        }

        // Vérifier si l'entreprise a déjà un tenant
        if ($entreprise->tenant_id) {
            $this->error("L'entreprise a déjà un tenant associé.");
            return Command::FAILURE;
        }

        // Générer l'ID du tenant (UUID)
        $tenantId = strtolower(Str::slug($entreprise->nom) . '-' . Str::random(8));

        // Demander le sous-domaine si non fourni
        $domain = $this->option('domain');
        if (!$domain) {
            $domain = strtolower(Str::slug($entreprise->nom));
        }

        // Vérifier que le domaine n'existe pas
        if (Domain::where('domain', $domain . '.benin-security.com')->exists()) {
            $this->error("Le domaine {$domain}.benin-security.com existe déjà.");
            return Command::FAILURE;
        }

        $this->info("Création du tenant pour l'entreprise: {$entreprise->nom}");
        $this->info("ID du tenant: {$tenantId}");
        $this->info("Domaine: {$domain}.benin-security.com");

        // Créer le tenant
        $tenant = Tenant::create([
            'id' => $tenantId,
            'data' => [
                'entreprise_id' => $entreprise->id,
                'nom' => $entreprise->nom,
            ],
        ]);

        // Créer le domaine
        $domainFull = $domain . '.benin-security.com';
        Domain::create([
            'tenant_id' => $tenant->id,
            'domain' => $domainFull,
            'is_primary' => true,
        ]);

        // Associer le tenant à l'entreprise
        $entreprise->update([
            'tenant_id' => $tenant->id,
            'sous_domaine' => $domain,
        ]);

        $this->info("Tenant créé avec succès!");
        $this->info("Base de données: benin_security_{$tenantId}");

        // Exécuter les migrations si demandé
        if (!$this->option('skip-migration')) {
            $this->info("Exécution des migrations...");

            try {
                tenancy()->initialize($tenant);

                // Exécuter les migrations pour ce tenant
                $this->call('tenants:migrate', [
                    '--tenants' => [$tenant->id],
                    '--force' => true,
                ]);

                // Exécuter les seeders si nécessaire
                $this->call('tenants:seed', [
                    '--tenants' => [$tenant->id],
                    '--class' => 'DatabaseSeeder',
                    '--force' => true,
                ]);

                $this->info("Migrations et seeders exécutés avec succès!");
            } catch (\Exception $e) {
                $this->warn("Erreur lors de l'exécution des migrations: " . $e->getMessage());
                $this->info("Vous pouvez exécuter les migrations manuellement avec:");
                $this->info("php artisan tenants:migrate --tenants={$tenant->id}");
            }
        }

        $this->info("");
        $this->info("=== Résumé ===");
        $this->info("Entreprise: {$entreprise->nom}");
        $this->info("Tenant ID: {$tenantId}");
        $this->info("URL: https://{$domainFull}");
        $this->info("");
        $this->info("Pour tester localement, ajoutez cette ligne à votre fichier hosts:");
        $this->info("127.0.0.1 {$domainFull}");

        return Command::SUCCESS;
    }
}
