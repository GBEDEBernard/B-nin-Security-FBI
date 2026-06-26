<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('entreprises', function (Blueprint $table) {
            $table->dropColumn([
                'formule',
                'nombre_agents_max',
                'nombre_sites_max',
                'date_debut_contrat',
                'date_fin_contrat',
                'montant_mensuel',
                'cycle_facturation',
                'est_en_essai',
                'date_fin_essai',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('entreprises', function (Blueprint $table) {
            $table->string('formule')->nullable();
            $table->integer('nombre_agents_max')->default(0);
            $table->integer('nombre_sites_max')->default(0);
            $table->date('date_debut_contrat')->nullable();
            $table->date('date_fin_contrat')->nullable();
            $table->decimal('montant_mensuel', 10, 2)->default(0);
            $table->string('cycle_facturation')->nullable();
            $table->boolean('est_en_essai')->default(false);
            $table->date('date_fin_essai')->nullable();
        });
    }
};
