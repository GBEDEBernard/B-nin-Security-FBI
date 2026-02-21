<?php
// database/migrations/2024_01_01_000008_create_affectations_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('affectations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entreprise_id')->constrained()->onDelete('cascade');
            $table->foreignId('employe_id')->constrained()->onDelete('cascade');
            $table->foreignId('contrat_prestation_id')->constrained('contrats_prestation')->onDelete('cascade');
            $table->foreignId('site_client_id')->constrained('sites_clients')->onDelete('cascade');
            
            // Rôle sur ce site
            $table->enum('role_site', [
                'superviseur_site',
                'chef_de_poste',
                'agent_execution',
                'agent_mobile',
                'agent_releve'
            ])->default('agent_execution');
            
            // Période d'affectation
            $table->date('date_debut');
            $table->date('date_fin')->nullable();
            
            // Horaires spécifiques pour cette affectation
            $table->time('horaire_debut')->nullable();
            $table->time('horaire_fin')->nullable();
            $table->json('jours_travail')->nullable(); // ["lundi", "mardi", ...]
            
            // Responsabilités spécifiques
            $table->json('responsabilites')->nullable();
            
            // Supervision
            $table->foreignId('superviseur_direct_id')->nullable()->constrained('employes');
            $table->foreignId('controleur_id')->nullable()->constrained('employes');
            
            // Statut
            $table->enum('statut', [
                'planifiee',
                'en_cours',
                'suspendue',
                'terminee',
                'remplacee'
            ])->default('planifiee');
            
            $table->text('motif_fin')->nullable();
            
            $table->foreignId('affecte_par')->constrained('employes');
            $table->timestamps();
            
            // Index
            $table->index(['entreprise_id', 'employe_id', 'statut']);
            $table->index(['site_client_id', 'date_debut', 'date_fin']);
            
            // Empêcher double affectation active sur la même période
            $table->unique(['employe_id', 'site_client_id', 'date_debut'], 'unique_affectation_periode');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affectations');
    }
};
