<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Correction de la relation Entreprise ↔ Abonnement:
     * - Avant: Abonnement a entreprise_id (1:1)
     * - Après: Entreprise a abonnement_id (1:1)
     * 
     * Cela permet à un abonnement d'être lié à plusieurs entreprises
     * (pour la facturation groupée par exemple)
     */
    public function up(): void
    {
        // 1. Ajouter abonnement_id à la table entreprises
        Schema::table('entreprises', function (Blueprint $table) {
            $table->foreignId('abonnement_id')
                ->nullable()
                ->constrained('abonnements')
                ->onDelete('set null')
                ->after('id');
        });

        // 2. Supprimer entreprise_id de la table abonnements
        Schema::table('abonnements', function (Blueprint $table) {
            $table->dropForeign(['entreprise_id']);
            $table->dropColumn('entreprise_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remettre entreprise_id dans abonnements
        Schema::table('abonnements', function (Blueprint $table) {
            $table->foreignId('entreprise_id')
                ->nullable()
                ->constrained('entreprises')
                ->onDelete('cascade')
                ->after('id');
        });

        // Retirer abonnement_id de entreprises
        Schema::table('entreprises', function (Blueprint $table) {
            $table->dropForeign(['abonnement_id']);
            $table->dropColumn('abonnement_id');
        });
    }
};
