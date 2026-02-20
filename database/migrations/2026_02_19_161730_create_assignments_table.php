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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_locataire')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('id_agent')->constrained('agents')->onDelete('cascade');
            $table->foreignId('id_quart')->constrained('shifts')->onDelete('cascade');
            $table->enum('statut', ['assigned', 'completed', 'cancelled', 'no_show'])->default('assigned');
            $table->dateTime('heure_arrivee')->nullable();
            $table->dateTime('heure_depart')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('tarif_reel', 10, 2)->nullable();
            $table->json('metadonnees')->nullable();
            $table->timestamps();
            $table->unique(['id_agent', 'id_quart']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
