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
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_locataire')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('id_agence')->constrained('agencies')->onDelete('cascade');
            $table->foreignId('id_client')->nullable()->constrained('clients')->onDelete('set null');
            $table->string('nom');
            $table->string('lieu');
            $table->text('description')->nullable();
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->integer('agents_requis')->default(1);
            $table->decimal('tarif_par_agent', 10, 2);
            $table->date('date_quart');
            $table->enum('type', ['regular', 'special_event'])->default('regular');
            $table->enum('statut', ['pending', 'active', 'completed', 'cancelled'])->default('pending');
            $table->json('metadonnees')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
