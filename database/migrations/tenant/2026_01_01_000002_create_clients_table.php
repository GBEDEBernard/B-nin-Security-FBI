<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Table des clients - spécifique à chaque tenant
     */
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->unique();
            $table->string('telephone')->nullable();
            $table->string('adresse')->nullable();

            // Personne morale ou physique
            $table->enum('type', ['personne_physique', 'personne_morale'])->default('personne_physique');
            $table->string('entreprise_nom')->nullable(); // Pour personne morale
            $table->string('ifu')->nullable(); // Identifiant Fiscal Unique
            $table->string('rccm')->nullable(); // Registre du Commerce

            // Représentant légal (pour personne morale)
            $table->string('representant_nom')->nullable();
            $table->string('representant_prenom')->nullable();
            $table->string('representant_fonction')->nullable();

            // Contact principale
            $table->string('contact_nom')->nullable();
            $table->string('contact_telephone')->nullable();
            $table->string('contact_email')->nullable();

            // Statut
            $table->boolean('est_actif')->default(true);

            // Authentification
            $table->string('password')->nullable();
            $table->rememberToken()->nullable();

            // Connexion
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();
            $table->timestamp('derniere_connexion')->nullable();

            $table->timestamps();

            // Index
            $table->index('email');
            $table->index('type');
            $table->index('est_actif');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
