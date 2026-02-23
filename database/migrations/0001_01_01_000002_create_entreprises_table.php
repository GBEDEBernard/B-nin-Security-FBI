<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entreprises', function (Blueprint $table) {
            $table->id();

            // Identité de l'entreprise
            $table->string('nom_entreprise');
            $table->string('slug')->unique();
            $table->string('nom_commercial')->nullable();
            $table->string('forme_juridique')->nullable();

            // Informations légales et fiscales
            $table->string('numero_registre')->nullable();
            $table->string('numeroIdentificationFiscale')->nullable();
            $table->string('numeroContribuable')->nullable();

            // Contact principal
            $table->string('email')->nullable();
            $table->string('telephone')->nullable();
            $table->string('telephone_alternatif')->nullable();

            // Adresse
            $table->text('adresse')->nullable();
            $table->string('ville')->nullable();
            $table->string('pays')->nullable();
            $table->string('code_postal')->nullable();

            // Représentant légal
            $table->string('nom_representant_legal')->nullable();
            $table->string('email_representant_legal')->nullable();
            $table->string('telephone_representant_legal')->nullable();

            // Identité visuelle
            $table->string('logo')->nullable();
            $table->string('couleur_primaire')->nullable();
            $table->string('couleur_secondaire')->nullable();

            // Abonnement et facturation
            $table->string('formule')->nullable();
            $table->integer('nombre_agents_max')->default(0);
            $table->integer('nombre_sites_max')->default(0);
            $table->date('date_debut_contrat')->nullable();
            $table->date('date_fin_contrat')->nullable();
            $table->decimal('montant_mensuel', 10, 2)->default(0);
            $table->string('cycle_facturation')->nullable();

            // Statut
            $table->boolean('est_active')->default(true);
            $table->boolean('est_en_essai')->default(false);
            $table->date('date_fin_essai')->nullable();

            // Configuration
            $table->json('parametres')->nullable();

            // Notes
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Index
            $table->index('slug');
            $table->index('est_active');
            $table->index('est_en_essai');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entreprises');
    }
};
