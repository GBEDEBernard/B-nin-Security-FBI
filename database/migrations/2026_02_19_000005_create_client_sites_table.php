<?php
// database/migrations/2024_01_01_000005_create_sites_clients_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sites_clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entreprise_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            
            $table->string('nom_site', 255); // ex: "Siège social", "Agence de Yopougon", "Entrepôt"
            $table->string('code_site', 50)->nullable(); // Code interne du client
            
            // Localisation
            $table->text('adresse');
            $table->string('ville', 100);
            $table->string('commune', 100)->nullable();
            $table->string('quartier', 255)->nullable();
            
            // Géolocalisation pour pointage
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->integer('rayon_pointage')->default(100); // Rayon en mètres pour autoriser le pointage
            
            // Contact sur site
            $table->string('contact_nom', 255)->nullable();
            $table->string('contact_telephone', 50)->nullable();
            $table->string('contact_email', 255)->nullable();
            
            // Caractéristiques du site
            $table->enum('niveau_risque', ['faible', 'moyen', 'eleve', 'critique'])->default('moyen');
            $table->json('equipements')->nullable(); // Liste des équipements de sécurité
            $table->text('consignes_specifiques')->nullable();
            $table->json('photos')->nullable();
            
            $table->boolean('est_actif')->default(true);
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            // Un client ne peut pas avoir deux sites avec le même nom
            $table->unique(['client_id', 'nom_site']);
            $table->index(['entreprise_id', 'ville', 'commune']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sites_clients');
    }
};