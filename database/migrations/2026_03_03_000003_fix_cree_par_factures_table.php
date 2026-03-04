<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('factures', function (Blueprint $table) {
            // 1. Supprimer la contrainte FK existante
            $table->dropForeign(['cree_par']);

            // 2. Rendre le champ nullable
            $table->unsignedBigInteger('cree_par')->nullable()->change();

            // 3. Rétablir la FK avec nullOnDelete
            $table->foreign('cree_par')
                  ->references('id')
                  ->on('employes')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('factures', function (Blueprint $table) {
            $table->dropForeign(['cree_par']);
            $table->unsignedBigInteger('cree_par')->nullable(false)->change();
            $table->foreign('cree_par')
                  ->references('id')
                  ->on('employes');
        });
    }
};