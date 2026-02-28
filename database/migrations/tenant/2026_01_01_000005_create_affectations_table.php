<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Table des affectations d'employés aux sites clients
     */
    public function up(): void
    {
        Schema::create('affectations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employe_id')->constrained()->onDelete('cascade');
            $table->foreignId('contrat_id')->constrained()->onDelete('cascade');
            $table->foreignId('site_client_id')->nullable()->constrained()->onDelete('set null');
            $table->date('date_debut');
            $table->date('date_fin')->nullable();
            $table->string('horaire'); // Jour, Nuit, Alternance
            $table->text('description')->nullable();

            // Statut
            $table->enum('statut', ['active', 'terminee', 'annulee'])->default('active');

            // Heures
            $table->decimal('heures_par_semaine', 5, 2)->default(40);

            $table->timestamps();

            $table->index('employe_id');
            $table->index('contrat_id');
            $table->index('statut');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affectations');
    }
};
