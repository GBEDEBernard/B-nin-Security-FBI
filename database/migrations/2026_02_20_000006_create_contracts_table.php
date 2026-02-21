<?php
// database/migrations/2024_01_01_000006_create_contrats_prestation_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contrats_prestation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entreprise_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            
            $table->string('numero_contrat', 100)->unique();
            $table->string('intitule', 255);
            
            // Période
            $table->date('date_debut');
            $table->date('date_fin')->nullable();
            $table->boolean('est_renouvelable')->default(false);
            $table->integer('duree_preavis')->default(30); // Jours de préavis
            
            // Aspects financiers
            $table->decimal('montant_annuel_ht', 15, 2)->nullable();
            $table->decimal('montant_mensuel_ht', 15, 2);
            $table->decimal('tva', 5, 2)->default(18);
            $table->decimal('montant_mensuel_ttc', 15, 2);
            $table->enum('periodicite_facturation', ['mensuel', 'trimestriel', 'semestriel', 'annuel'])->default('mensuel');
            
            // Ressources
            $table->integer('nombre_agents_requis');
            $table->json('postes_requis')->nullable(); // Ex: {"superviseur": 1, "agent": 5, "chef_de_poste": 1}
            
            // Détails prestation
            $table->text('description_prestation');
            $table->json('horaires_globaux')->nullable(); // Horaires généraux
            $table->text('conditions_particulieres')->nullable();
            $table->json('documents_contractuels')->nullable();
            
            // Statut
            $table->enum('statut', [
                'brouillon',
                'en_cours',
                'suspendu',
                'termine',
                'resilie'
            ])->default('brouillon');
            
            $table->text('motif_resiliation')->nullable();
            $table->date('date_resiliation')->nullable();
            
            // Signataires
            $table->string('signataire_client_nom', 255)->nullable();
            $table->string('signataire_client_fonction', 255)->nullable();
            $table->foreignId('signataire_securite_id')->nullable()->constrained('employes');
            
            $table->date('date_signature')->nullable();
            
            $table->foreignId('cree_par')->constrained('employes');
            $table->foreignId('valide_par')->nullable()->constrained('employes');
            $table->timestamp('date_validation')->nullable();
            
            $table->timestamps();
            
            $table->index(['entreprise_id', 'client_id', 'statut']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contrats_prestation');
    }
};