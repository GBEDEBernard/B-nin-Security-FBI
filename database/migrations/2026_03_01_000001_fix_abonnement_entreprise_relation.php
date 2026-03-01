<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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

        // 2. Supprimer entreprise_id de la table abonnements (si la colonne existe)
        Schema::table('abonnements', function (Blueprint $table) {
            // Vérifier si la colonne existe avant de la supprimer
            if (Schema::hasColumn('abonnements', 'entreprise_id')) {
                // Supprimer la clé étrangère si elle existe
                try {
                    $table->dropForeign(['entreprise_id']);
                } catch (\Exception $e) {
                    // Ignorer si la clé étrangère n'existe pas
                }
                $table->dropColumn('entreprise_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remettre entreprise_id dans abonnements
        Schema::table('abonnements', function (Blueprint $table) {
            if (!Schema::hasColumn('abonnements', 'entreprise_id')) {
                $table->foreignId('entreprise_id')
                    ->nullable()
                    ->constrained('entreprises')
                    ->onDelete('cascade')
                    ->after('id');
            }
        });

        // Retirer abonnement_id de entreprises
        Schema::table('entreprises', function (Blueprint $table) {
            if (Schema::hasColumn('entreprises', 'abonnement_id')) {
                $table->dropForeign(['abonnement_id']);
                $table->dropColumn('abonnement_id');
            }
        });
    }
};
