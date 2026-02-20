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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_locataire')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('id_agence')->constrained('agencies')->onDelete('cascade');
            $table->string('numero_enregistrement')->unique();
            $table->string('marque');
            $table->string('modele');
            $table->integer('annee');
            $table->enum('type', ['car', 'van', 'truck', 'motorcycle'])->default('car');
            $table->string('couleur');
            $table->string('vin')->unique()->nullable();
            $table->string('plaque_immatriculation')->unique();
            $table->date('expiration_enregistrement');
            $table->date('expiration_assurance');
            $table->decimal('tarif_quotidien', 10, 2)->nullable();
            $table->enum('statut', ['available', 'in_use', 'maintenance', 'retired'])->default('available');
            $table->text('notes')->nullable();
            $table->json('metadonnees')->nullable();
            $table->timestamps();
            $table->unique(['id_locataire', 'numero_enregistrement']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
