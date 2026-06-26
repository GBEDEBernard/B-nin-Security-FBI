<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('factures', function (Blueprint $table) {
            $table->foreignId('abonnement_id')
                ->nullable()
                ->constrained('abonnements')
                ->onDelete('set null')
                ->after('entreprise_id');
        });
    }

    public function down(): void
    {
        Schema::table('factures', function (Blueprint $table) {
            $table->dropForeign(['abonnement_id']);
            $table->dropColumn('abonnement_id');
        });
    }
};
