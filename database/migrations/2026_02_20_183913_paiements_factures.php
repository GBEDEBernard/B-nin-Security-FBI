<?php
// database/migrations/2024_01_01_000015_create_paiements_factures_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiements_factures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facture_id')->constrained()->onDelete('cascade');
            
            $table->decimal('montant', 15, 2);
            $table->date('date_paiement');
            $table->enum('mode_paiement', ['especes', 'cheque', 'virement', 'carte', 'mobile_money']);
            $table->string('reference', 255)->nullable(); // Numéro de chèque, référence virement
            
            $table->string('piece_justificative', 255)->nullable();
            $table->text('notes')->nullable();
            
            $table->foreignId('enregistre_par')->constrained('employes');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiements_factures');
    }
};