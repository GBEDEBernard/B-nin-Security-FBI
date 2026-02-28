<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Table des employés - spécifique à chaque tenant
     */
    public function up(): void
    {
        Schema::create('employes', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->unique();
            $table->string('telephone')->nullable();
            $table->string('adresse')->nullable();
            $table->date('date_naissance')->nullable();
            $table->string('lieu_naissance')->nullable();
            $table->string('sexe')->nullable(); // M, F
            $table->string('statut_matrimonial')->nullable();

            // Informations professionnelles
            $table->string('poste');
            $table->string('matricule')->unique();
            $table->date('date_embauche');
            $table->string('type_contrat')->default('cdi'); // cdi, cdd, interim
            $table->string('departement')->nullable();
            $table->string('service')->nullable();
            $table->decimal('salaire_base', 10, 2)->nullable();

            // Statut
            $table->string('statut')->default('en_poste'); // en_poste, conge, suspendu, licencie
            $table->boolean('est_actif')->default(true);
            $table->boolean('est_disponible')->default(true);
            $table->boolean('est_connecte')->default(false);

            // Contact d'urgence
            $table->string('contact_urgence_nom')->nullable();
            $table->string('contact_urgence_tel')->nullable();
            $table->string('contact_urgence_lien')->nullable(); // conjoint, parent, frere, etc.

            // Photo
            $table->string('photo')->nullable();

            // Authentification
            $table->string('password')->nullable();
            $table->rememberToken()->nullable();

            // Connexion
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();

            $table->timestamps();

            // Index
            $table->index('email');
            $table->index('matricule');
            $table->index('statut');
            $table->index('poste');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employes');
    }
};
