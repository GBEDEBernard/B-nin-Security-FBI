<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            // Date de naissance pour les particuliers
            $table->date('date_naissance')->nullable()->after('prenoms');

            // Représentant légal pour les entreprises/institutions
            $table->string('representant_nom', 255)->nullable()->after('rc');
            $table->string('representant_prenom', 255)->nullable()->after('representant_nom');
            $table->string('representant_fonction', 255)->nullable()->after('representant_prenom');

            // Contact email supplémentaire
            $table->string('contact_email', 255)->nullable()->after('contact_principal_fonction');
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'date_naissance',
                'representant_nom',
                'representant_prenom',
                'representant_fonction',
                'contact_email',
            ]);
        });
    }
};
