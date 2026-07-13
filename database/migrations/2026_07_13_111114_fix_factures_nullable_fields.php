<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('factures', function (Blueprint $table) {
            $table->dropForeign(['contrat_prestation_id']);
            $table->dropForeign(['client_id']);
            $table->dropForeign(['cree_par']);
            $table->dropIndex(['client_id', 'mois', 'annee']);
        });

        Schema::table('factures', function (Blueprint $table) {
            $table->foreignId('contrat_prestation_id')->nullable()->change();
            $table->foreignId('client_id')->nullable()->change();
            $table->dropColumn('cree_par');
        });

        Schema::table('factures', function (Blueprint $table) {
            $table->string('cree_par', 255)->nullable()->after('notes');
            $table->foreign('contrat_prestation_id')->references('id')->on('contrats_prestation')->onDelete('set null');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('set null');
            $table->index(['client_id', 'mois', 'annee']);
        });
    }

    public function down(): void
    {
        Schema::table('factures', function (Blueprint $table) {
            $table->dropForeign(['contrat_prestation_id']);
            $table->dropForeign(['client_id']);
            $table->dropIndex(['client_id', 'mois', 'annee']);
            $table->dropColumn('cree_par');
        });

        Schema::table('factures', function (Blueprint $table) {
            $table->foreignId('cree_par')->constrained('employes')->after('notes');
            $table->foreignId('contrat_prestation_id')->nullable(false)->change();
            $table->foreignId('client_id')->nullable(false)->change();
            $table->foreign('contrat_prestation_id')->references('id')->on('contrats_prestation')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->index(['client_id', 'mois', 'annee']);
        });
    }
};
