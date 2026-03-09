<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('contrats_prestation', function (Blueprint $table) {
            $table->decimal('prix_par_agent', 12, 2)->nullable()->after('nombre_sites');
            $table->decimal('montant_total_ht', 14, 2)->nullable()->after('prix_par_agent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contrats_prestation', function (Blueprint $table) {
            $table->dropColumn(['prix_par_agent', 'montant_total_ht']);
        });
    }
};
