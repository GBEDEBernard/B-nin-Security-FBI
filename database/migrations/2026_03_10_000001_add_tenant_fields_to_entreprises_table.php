<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Ajoute les champs nécessaires pour le multi-tenant à la table entreprises
     */
    public function up(): void
    {
        Schema::table('entreprises', function (Blueprint $table) {
            // Lien vers le tenant (stancl/tenancy)
            $table->string('tenant_id')->nullable()->after('id');
            $table->string('sous_domaine')->nullable()->unique()->after('tenant_id');

            // Index pour optimiser les recherches
            $table->index('tenant_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('entreprises', function (Blueprint $table) {
            $table->dropIndex(['tenant_id']);
            $table->dropUnique(['sous_domaine']);
            $table->dropColumn(['tenant_id', 'sous_domaine']);
        });
    }
};
