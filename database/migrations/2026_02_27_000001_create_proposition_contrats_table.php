<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('proposition_contrats', function (Blueprint $table) {
            $table->id();

            // Informations de l'entreprise interessée
            $table->string('nom_entreprise');
            $table->string('nom_commercial')->nullable();
            $table->string('forme_juridique')->nullable();
            $table->string('email');
            $table->string('telephone');
            $table->text('adresse')->nullable();
            $table->string('ville')->nullable();
            $table->string('pays')->nullable();

            // Informations légales
            $table->string('numero_registre')->nullable();
            $table->string('numeroIdentificationFiscale')->nullable();
            $table->string('numeroContribuable')->nullable();

            // Représentant légal
            $table->string('representant_nom')->nullable();
            $table->string('representant_fonction')->nullable();
            $table->string('representant_email')->nullable();
            $table->string('representant_telephone')->nullable();

            // Besoins
            $table->string('type_service');
            $table->integer('nombre_agents');
            $table->text('description_besoins')->nullable();
            $table->decimal('budget_approx', 15, 2)->nullable();

            // Statut de la proposition
            $table->string('statut')->default('soumis');
            $table->timestamp('date_soumission')->nullable();
            $table->timestamp('date_signature')->nullable();
            $table->timestamp('date_rejet')->nullable();
            $table->text('motif_rejet')->nullable();

            // Contrat PDF
            $table->string('fichier_contrat_signe')->nullable();
            $table->string('contrat_pdf_path')->nullable();

            // Notes internes
            $table->text('notes')->nullable();

            // Admin qui traite
            $table->foreignId('traite_par')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('date_traitement')->nullable();

            // Entreprise créée à partir de cette proposition
            $table->foreignId('entreprise_id')->nullable()->constrained('entreprises')->onDelete('set null');

            $table->timestamps();
            $table->softDeletes();

            // Index
            $table->index('statut');
            $table->index('email');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposition_contrats');
    }
};
