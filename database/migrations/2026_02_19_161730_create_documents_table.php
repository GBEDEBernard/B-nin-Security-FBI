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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_locataire')->constrained('tenants')->onDelete('cascade');
            $table->morphs('documentable');
            $table->string('type');
            $table->string('chemin_fichier');
            $table->string('nom_fichier_original');
            $table->string('type_mime');
            $table->unsignedBigInteger('taille_fichier');
            $table->date('date_expiration')->nullable();
            $table->text('notes')->nullable();
            $table->enum('statut', ['valid', 'expired', 'pending_renewal'])->default('valid');
            $table->json('metadonnees')->nullable();
            $table->timestamps();
            $table->index(['id_locataire', 'documentable_type', 'documentable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
