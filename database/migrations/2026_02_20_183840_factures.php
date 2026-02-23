<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('factures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entreprise_id')->constrained('entreprises')->onDelete('cascade');
            $table->foreignId('contrat_prestation_id')->constrained('contrats_prestation')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');

            $table->string('numero_facture', 100)->unique();
            $table->string('reference', 255)->nullable();

            $table->integer('mois');
            $table->integer('annee');

            // Montants
            $table->decimal('montant_ht', 15, 2);
            $table->decimal('tva', 5, 2);
            $table->decimal('montant_ttc', 15, 2);

            // Détail
            $table->json('detail_prestation')->nullable();
            $table->json('periodes_calc')->nullable();

            // Dates
            $table->date('date_emission');
            $table->date('date_echeance');
            $table->date('date_paiement')->nullable();

            // Paiement
            $table->enum('statut', ['emise', 'envoyee', 'payee', 'partiellement_payee', 'impayee', 'annulee'])->default('emise');
            $table->decimal('montant_paye', 15, 2)->default(0);
            $table->decimal('montant_restant', 15, 2)->default(0);

            // Fichier
            $table->string('fichier_pdf', 255)->nullable();
            $table->text('notes')->nullable();

            $table->foreignId('cree_par')->constrained('employes');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['entreprise_id', 'statut']);
            $table->index(['client_id', 'mois', 'annee']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('factures');
    }
};
