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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_locataire')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('id_agence')->constrained('agencies')->onDelete('cascade');
            $table->enum('type', ['individual', 'company'])->default('company');
            $table->string('nom');
            $table->string('personne_contact')->nullable();
            $table->string('email')->nullable();
            $table->string('telephone');
            $table->string('autre_telephone')->nullable();
            $table->string('adresse');
            $table->string('ville');
            $table->string('code_postal')->nullable();
            $table->string('pays')->default('Benin');
            $table->string('id_fiscal')->nullable()->unique();
            $table->text('notes')->nullable();
            $table->enum('statut', ['active', 'inactive', 'suspended'])->default('active');
            $table->json('metadonnees')->nullable();
            $table->timestamps();
            $table->unique(['id_locataire', 'email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
