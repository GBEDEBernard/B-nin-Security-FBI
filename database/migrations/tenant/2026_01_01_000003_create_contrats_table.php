
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Table des contrats de prestation - spécifique à chaque tenant
     */
    public function up(): void
    {
        Schema::create('contrats', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->foreignId('client_id')->constrained()->onDelete('restrict');
            $table->string('type'); // gardiennage, surveillance, événements, etc.
            $table->text('description')->nullable();
            $table->date('date_debut');
            $table->date('date_fin')->nullable();
            $table->decimal('montant_mensuel', 12, 2);
            $table->decimal('montant_total', 12, 2)->nullable();
            $table->string('devise')->default('XOF');

            // Statut du contrat
            $table->enum('statut', [
                'en_attente',
                'actif',
                'suspendu',
                'renouvele',
                'resilie',
                'expire'
            ])->default('en_attente');

            // Conditions
            $table->text('conditions')->nullable();
            $table->text('clauses')->nullable();

            // Dates importantes
            $table->date('date_signature')->nullable();
            $table->date('date_resiliation')->nullable();
            $table->text('motif_resiliation')->nullable();

            // Gestion
            $table->foreignId('created_by')->nullable()->constrained('employes')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('employes')->onDelete('set null');

            $table->timestamps();

            // Index
            $table->index('numero');
            $table->index('client_id');
            $table->index('statut');
            $table->index('date_debut');
            $table->index('date_fin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contrats');
    }
};
