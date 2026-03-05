<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Ajouter la colonne envoyeur_type manquante pour la relation MorphTo
            if (!Schema::hasColumn('notifications', 'envoyeur_type')) {
                $table->string('envoyeur_type')->nullable()->after('envoyeur_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn('envoyeur_type');
        });
    }
};

