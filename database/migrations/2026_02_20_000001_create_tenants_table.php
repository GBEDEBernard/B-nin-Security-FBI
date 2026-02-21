<?php
// database/migrations/2024_01_01_000001_create_entreprises_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entreprises', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 255);
            $table->string('slug', 255)->unique(); // Pour sous-domaine: securitex.monapp.com
            $table->string('email', 255)->unique();
            $table->string('telephone', 50);
            $table->text('adresse')->nullable();
            $table->string('logo', 255)->nullable();
            $table->string('nif', 100)->nullable(); // Numéro d'identification fiscale
            $table->string('rc', 100)->nullable(); // Registre de commerce
            $table->string('type_abonnement', 50)->default('standard');
            $table->date('date_expiration_abonnement');
            $table->boolean('est_actif')->default(true);
            $table->json('configuration')->nullable(); // Config spécifique à l'entreprise
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entreprises');
    }
};