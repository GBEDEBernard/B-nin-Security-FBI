<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Table for Directors (CEO, Deputy Director, Operations Director)
     */
    public function up(): void
    {
        Schema::create('managers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_locataire')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('id_utilisateur')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_agence')->nullable()->constrained('agencies')->onDelete('set null');
            $table->enum('type_gestionnaire', ['general_director', 'deputy_director', 'operations_director'])->default('operations_director');
            $table->string('nom_complet');
            $table->string('email')->unique();
            $table->string('telephone');
            $table->string('numero_id')->unique();
            $table->enum('type_id', ['passport', 'national_id', 'drivers_license'])->default('national_id');
            $table->date('date_naissance')->nullable();
            $table->enum('genre', ['male', 'female', 'other'])->nullable();
            $table->string('adresse');
            $table->string('ville');
            $table->string('code_postal');
            $table->decimal('salaire_par_mois', 10, 2)->nullable();
            $table->date('date_embauche');
            $table->enum('statut', ['active', 'inactive', 'suspended'])->default('active');
            $table->date('date_fin')->nullable();
            $table->json('metadonnees')->nullable();
            $table->timestamps();
            $table->unique(['id_locataire', 'email']);
            $table->unique(['id_locataire', 'numero_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('managers');
    }
};
