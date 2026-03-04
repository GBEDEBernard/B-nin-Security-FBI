<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('paies', function (Blueprint $table) {
            // Colonnes pour les paiements d'abonnement (utilisées par le contrôleur)
            $table->foreignId('facture_id')->nullable()->constrained('factures')->onDelete('set null');
            $table->string('mode_paiement', 50)->nullable()->after('employe_id');
            $table->string('reference_paiement', 255)->nullable()->after('mode_paiement');
            $table->text('description')->nullable()->after('reference_paiement');

            // Mettre à jour le statut pour inclure 'completed'
            $table->enum('statut', ['calcule', 'valide', 'paye', 'annule', 'completed'])->default('calcule')->change();
        });
    }

    public function down(): void
    {
        Schema::table('paies', function (Blueprint $table) {
            $table->dropForeign(['facture_id']);
            $table->dropColumn(['facture_id', 'mode_paiement', 'reference_paiement', 'description']);

            // Remettre le statut original
            $table->enum('statut', ['calcule', 'valide', 'paye', 'annule'])->default('calcule')->change();
        });
    }
};
