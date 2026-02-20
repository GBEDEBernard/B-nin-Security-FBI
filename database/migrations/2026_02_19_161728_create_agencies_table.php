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
        Schema::create('agencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_locataire')->constrained('tenants')->onDelete('cascade');
            $table->string('nom');
            $table->string('numero_enregistrement')->unique();
            $table->string('numero_permis')->unique();
            $table->string('email')->unique();
            $table->string('telephone');
            $table->string('adresse');
            $table->string('ville');
            $table->string('code_postal');
            $table->string('pays')->default('Benin');
            $table->string('nom_proprietaire');
            $table->decimal('tarif_mensuel', 12, 2)->nullable();
            $table->enum('statut', ['active', 'inactive', 'suspended'])->default('active');
            $table->timestamp('active_le')->nullable();
            $table->timestamp('desactive_le')->nullable();
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
        Schema::dropIfExists('agencies');
    }
};
