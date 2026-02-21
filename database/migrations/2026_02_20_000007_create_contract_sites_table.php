<?php
// database/migrations/2024_01_01_000007_create_sites_contrats_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sites_contrats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contrat_prestation_id')->constrained('contrats_prestation')->onDelete('cascade');
            $table->foreignId('site_client_id')->constrained('sites_clients')->onDelete('cascade');
            
            // Spécificités par site
            $table->integer('nombre_agents_site')->default(0);
            $table->json('horaires_site')->nullable(); // Horaires spécifiques à ce site
            $table->text('consignes_site')->nullable();
            
            $table->timestamps();
            
            // Un site ne peut être associé qu'une fois à un contrat
            $table->unique(['contrat_prestation_id', 'site_client_id'], 'unique_site_contrat');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sites_contrats');
    }
};