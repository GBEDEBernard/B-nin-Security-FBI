<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Table des pointages - spécifique à chaque tenant
     */
    public function up(): void
    {
        Schema::create('pointages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employe_id')->constrained()->onDelete('cascade');
            $table->foreignId('affectation_id')->nullable()->constrained()->onDelete('set null');
            $table->date('date');
            $table->time('heure_entree')->nullable();
            $table->time('heure_sortie')->nullable();
            $table->decimal('heures_travail', 5, 2)->nullable();

            // Statut
            $table->enum('statut', ['en_cours', 'termine', 'valide', 'signale'])->default('en_cours');

            // Problème signalé
            $table->text('probleme')->nullable();
            $table->boolean('probleme_resolu')->default(false);

            // Validation
            $table->foreignId('valide_par')->nullable()->constrained('employes')->onDelete('set null');
            $table->timestamp('date_validation')->nullable();

            $table->timestamps();

            $table->index('employe_id');
            $table->index('date');
            $table->index('statut');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pointages');
    }
};
