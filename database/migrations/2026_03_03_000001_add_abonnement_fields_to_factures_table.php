<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ajouter la colonne description à la table factures
        Schema::table('factures', function (Blueprint $table) {
            $table->text('description')->nullable()->after('statut');
        });

        // Rendre les colonnes nullable (elles sont requises dans la migration originale)
        Schema::table('factures', function (Blueprint $table) {
            $table->foreignId('contrat_prestation_id')->nullable()->change();
            $table->foreignId('client_id')->nullable()->change();
            $table->integer('mois')->nullable()->change();
            $table->integer('annee')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('factures', function (Blueprint $table) {
            $table->dropColumn('description');

            // Remettre comme avant (requis)
            $table->foreignId('contrat_prestation_id')->change();
            $table->foreignId('client_id')->change();
            $table->integer('mois')->change();
            $table->integer('annee')->change();
        });
    }
};
