<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Table des factures - spécifique à chaque tenant
     */
    public function up(): void
    {
        Schema::create('factures', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->foreignId('contrat_id')->constrained()->onDelete('restrict');
            $table->foreignId('client_id')->constrained()->onDelete('restrict');
            $table->string('type'); // mensuelle, trimestrielle, annuelle
            $table->decimal('montant_ht', 12, 2);
            $table->decimal('tva', 5, 2)->default(18);
            $table->decimal('montant_ttc', 12, 2);
            $table->string('devise')->default('XOF');

            // Période
            $table->date('periode_debut');
            $table->date('periode_fin');
            $table->integer('mois')->nullable();
            $table->integer('annee')->nullable();

            // Statut
            $table->enum('statut', ['en_attente', 'payee', 'impayee', 'annulee'])->default('en_attente');
            $table->date('date_echeance')->nullable();
            $table->date('date_paiement')->nullable();

            // Informations de paiement
            $table->string('mode_paiement')->nullable();
            $table->string('reference_paiement')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();

            // Index
            $table->index('numero');
            $table->index('contrat_id');
            $table->index('client_id');
            $table->index('statut');
            $table->index('date_echeance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factures');
    }
};
