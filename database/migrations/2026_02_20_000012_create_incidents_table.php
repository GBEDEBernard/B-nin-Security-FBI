<?php
// database/migrations/2024_01_01_000012_create_incidents_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entreprise_id')->constrained()->onDelete('cascade');
            $table->foreignId('employe_id')->constrained()->onDelete('cascade');
            $table->foreignId('affectation_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('site_client_id')->constrained('sites_clients')->onDelete('cascade');

            $table->string('reference', 100)->unique();
            $table->dateTime('date_incident');

            $table->enum('type', [
                'securite',
                'discipline',
                'materiel',
                'client',
                'environnement',
                'autre'
            ]);

            $table->enum('gravite', ['faible', 'moyenne', 'elevee', 'critique']);
            $table->text('description');

            // Personnes impliquées
            $table->json('impliques')->nullable(); // Autres employés, clients, etc.
            $table->json('temoins')->nullable();

            // Éléments
            $table->json('photos')->nullable();
            $table->json('documents')->nullable();

            // Actions
            $table->text('actions_immediates')->nullable();
            $table->text('actions_correctives')->nullable();
            $table->date('date_resolution')->nullable();

            // Gestion
            $table->foreignId('declare_par')->constrained('employes');
            $table->foreignId('traite_par')->nullable()->constrained('employes');
            $table->enum('statut', ['ouvert', 'en_cours', 'resolu', 'clos'])->default('ouvert');
            $table->text('conclusion')->nullable();

            // Rapport
            $table->string('rapport_pdf', 255)->nullable();

            $table->timestamps();

            $table->index(['entreprise_id', 'statut', 'gravite']);
            $table->index(['site_client_id', 'date_incident']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};
