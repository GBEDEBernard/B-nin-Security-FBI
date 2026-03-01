<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Cette table remplace les champs d'abonnement dans la table entreprises
     */
    public function up(): void
    {
        Schema::create('abonnements', function (Blueprint $table) {
            $table->id();

            // Formule d'abonnement (pour les plans standard)
            $table->string('formule'); // basic, premium, enterprise
            $table->text('description')->nullable();

            // Tarification par employé
            $table->integer('employes_min')->default(1);
            $table->integer('employes_max')->default(10);
            $table->decimal('tarif_par_employe', 10, 2)->default(0);
            $table->decimal('tarif_employe_supplementaire', 10, 2)->nullable();
            $table->integer('sites_max')->default(1);

            // Type et durée
            $table->string('type_formule')->nullable(); // basic, premium, enterprise
            $table->integer('duree_mois')->nullable(); // null = non limitée
            $table->integer('jours_essai')->default(7);

            // Dates du contrat
            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();
            $table->date('date_fin_essai')->nullable();

            // Facturation
            $table->decimal('montant_total', 12, 2)->default(0)->nullable();
            $table->decimal('montant_mensuel', 10, 2)->default(0);
            $table->string('cycle_facturation')->nullable(); // mensuel, trimestriel, annuel
            $table->integer('nombre_agents_inclus')->nullable();

            // Statut
            $table->boolean('est_active')->default(true);
            $table->boolean('est_en_essai')->default(false);
            $table->boolean('est_renouvele_auto')->default(false);
            $table->string('statut')->default('actif'); // actif, expire, resilie, suspendu

            // Accès et fonctionnalités
            $table->json('fonctionnalites')->nullable();
            $table->json('modules_accessibles')->nullable();
            $table->integer('limite_utilisateurs')->nullable();
            $table->integer('limite_stockage_go')->nullable();

            // Paiement
            $table->string('mode_paiement')->nullable(); // virement, mobile_money, cheque
            $table->string('reference_paiement')->nullable();
            $table->date('date_dernier_paiement')->nullable();
            $table->date('date_prochain_paiement')->nullable();

            // Notes
            $table->text('notes')->nullable();

            $table->timestamps();

            // Index
            $table->index('formule');
            $table->index('est_active');
            $table->index('est_en_essai');
            $table->index('statut');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abonnements');
    }
};
