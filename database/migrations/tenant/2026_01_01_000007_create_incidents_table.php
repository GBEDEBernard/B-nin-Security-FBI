<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Table des incidents signalés par les clients
     */
    public function up(): void
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('contrat_id')->nullable()->constrained()->onDelete('set null');
            $table->string('titre');
            $table->text('description');
            $table->string('type'); // vol, effraction, incendie, accident, autre
            $table->string('gravite'); // faible, moyenne, grave, critique

            // Statut
            $table->enum('statut', ['nouveau', 'en_cours', 'resolu', 'ferme'])->default('nouveau');

            // Dates
            $table->dateTime('date_incident');
            $table->dateTime('date_resolution')->nullable();

            // Suite donnée
            $table->text('resolution')->nullable();
            $table->foreignId('traite_par')->nullable()->constrained('employes')->onDelete('set null');

            $table->timestamps();

            $table->index('client_id');
            $table->index('contrat_id');
            $table->index('statut');
            $table->index('type');
            $table->index('gravite');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};
