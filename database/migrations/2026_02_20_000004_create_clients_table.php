<?php
// database/migrations/2024_01_01_000004_create_clients_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entreprise_id')->constrained()->onDelete('cascade');
            
            // Type de client
            $table->enum('type_client', ['particulier', 'entreprise', 'institution'])->default('entreprise');
            
            // Si particulier
            $table->string('nom', 100)->nullable();
            $table->string('prenoms', 150)->nullable();
            
            // Si entreprise/institution
            $table->string('raison_sociale', 255)->nullable();
            $table->string('nif', 100)->nullable();
            $table->string('rc', 100)->nullable();
            
            // Contact principal
            $table->string('email', 255)->nullable();
            $table->string('telephone', 50);
            $table->string('telephone_secondaire', 50)->nullable();
            $table->string('contact_principal_nom', 255)->nullable();
            $table->string('contact_principal_fonction', 255)->nullable();
            
            // Adresse principale
            $table->text('adresse');
            $table->string('ville', 100)->nullable();
            $table->string('pays', 100)->default('Côte d\'Ivoire');
            
            $table->boolean('est_actif')->default(true);
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            $table->index(['entreprise_id', 'type_client']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};