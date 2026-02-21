<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entreprise_id')->constrained()->onDelete('cascade');
            $table->foreignId('employe_id')->constrained()->onDelete('cascade');

            $table->enum('type_conge', [
                'paye',
                'maladie',
                'maternite',
                'paternite',
                'sans_solde',
                'exceptionnel',
                'formation'
            ])->default('paye');

            $table->date('date_debut');
            $table->date('date_fin');
            $table->integer('nombre_jours');
            $table->boolean('est_deduit_solde')->default(true);

            $table->text('motif');
            $table->string('piece_justificative', 255)->nullable();

            $table->foreignId('remplacant_id')->nullable()->constrained('employes');
            $table->text('consignes_remplacement')->nullable();

            $table->foreignId('demande_par')->constrained('employes');
            $table->foreignId('valide_par')->nullable()->constrained('employes');
            $table->enum('statut', [
                'en_attente',
                'valide_superviseur',
                'valide_direction',
                'refuse',
                'annule'
            ])->default('en_attente');

            $table->text('commentaire_validation')->nullable();
            $table->timestamp('date_validation')->nullable();

            $table->timestamps();

            $table->index(['entreprise_id', 'employe_id', 'statut']);
            $table->index(['date_debut', 'date_fin']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conges');
    }
};
