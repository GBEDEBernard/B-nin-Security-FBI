<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Corriger les valeurs existantes: 'eleve' -> 'haut'
        DB::table('sites_clients')
            ->where('niveau_risque', 'eleve')
            ->update(['niveau_risque' => 'haut']);

        // Modifier la colonne pour utiliser 'haut' au lieu de 'eleve'
        Schema::table('sites_clients', function (Blueprint $table) {
            $table->enum('niveau_risque', ['faible', 'moyen', 'haut', 'critique'])->default('moyen')->change();
        });
    }

    public function down(): void
    {
        // Remettre 'eleve' si nécessaire
        DB::table('sites_clients')
            ->where('niveau_risque', 'haut')
            ->update(['niveau_risque' => 'eleve']);

        Schema::table('sites_clients', function (Blueprint $table) {
            $table->enum('niveau_risque', ['faible', 'moyen', 'eleve', 'critique'])->default('moyen')->change();
        });
    }
};
