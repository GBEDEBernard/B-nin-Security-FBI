<?php
// database/migrations/2024_01_01_000003_create_employes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entreprise_id')->constrained()->onDelete('cascade');
            
            // Identité
            $table->string('matricule', 50)->unique();
            $table->enum('civilite', ['M', 'Mme', 'Mlle'])->default('M');
            $table->string('nom', 100);
            $table->string('prenoms', 150);
            $table->string('email', 255)->nullable();
            $table->string('password')->nullable();
            $table->string('cni', 50)->unique()->nullable();
            $table->date('date_naissance')->nullable();
            $table->string('lieu_naissance', 255)->nullable();
            $table->string('telephone', 50);
            $table->string('telephone_urgence', 50)->nullable();
            $table->string('photo', 255)->nullable();
            $table->text('adresse')->nullable();
            
            // Catégorie hiérarchique (POSTE OCCUPE DANS L'ENTREPRISE DE SECURITE)
            $table->enum('categorie', [
                'direction',
                'supervision',
                'controle',
                'agent'
            ])->default('agent');
            
            $table->enum('poste', [
                // Direction
                'directeur_general',
                'directeur_adjoint',
                // Supervision
                'superviseur_general',
                'superviseur_adjoint',
                // Contrôle
                'controleur_principal',
                'controleur',
                // Agents
                'agent_terrain',
                'agent_mobile',
                'agent_poste_fixe'
            ])->default('agent_terrain');
            
            $table->integer('niveau_hierarchique')->default(5); // 1 = plus haut (DG), 5 = agent
            
            // Contrat avec l'entreprise de sécurité
            $table->enum('type_contrat', ['cdi', 'cdd', 'stage', 'prestation'])->default('cdi');
            $table->date('date_embauche');
            $table->date('date_fin_contrat')->nullable();
            $table->decimal('salaire_base', 10, 2)->default(0);
            $table->string('numero_cnss', 50)->nullable();
            
            // Statut
            $table->boolean('est_actif')->default(true);
            $table->enum('statut', ['en_poste', 'conge', 'suspendu', 'licencie'])->default('en_poste');
            $table->date('date_depart')->nullable();
            $table->text('motif_depart')->nullable();
            
            $table->rememberToken();
            $table->timestamps();
            
            // Index
            $table->index(['entreprise_id', 'categorie', 'poste']);
            $table->index('matricule');
            $table->index('statut');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employes');
    }
};