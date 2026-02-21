<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pointages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entreprise_id')->constrained()->onDelete('cascade');
            $table->foreignId('employe_id')->constrained()->onDelete('cascade');
            $table->foreignId('affectation_id')->constrained()->onDelete('cascade');
            $table->foreignId('site_client_id')->constrained('sites_clients')->onDelete('cascade');

            $table->date('date_pointage');

            $table->dateTime('heure_arrivee');
            $table->decimal('latitude_arrivee', 10, 8)->nullable();
            $table->decimal('longitude_arrivee', 11, 8)->nullable();
            $table->string('photo_arrivee', 255)->nullable();

            $table->dateTime('heure_depart')->nullable();
            $table->decimal('latitude_depart', 10, 8)->nullable();
            $table->decimal('longitude_depart', 11, 8)->nullable();
            $table->string('photo_depart', 255)->nullable();

            $table->json('pauses')->nullable();

            $table->integer('heures_travaillees')->nullable();
            $table->integer('heures_supplementaires')->nullable();

            $table->enum('mode_pointage', ['mobile', 'web', 'manuel', 'qr_code'])->default('mobile');
            $table->enum('statut', ['en_cours', 'valide', 'anomalie', 'rejete'])->default('en_cours');

            $table->text('commentaire')->nullable();
            $table->text('anomalie')->nullable();

            $table->foreignId('valide_par')->nullable()->constrained('employes');
            $table->timestamp('date_validation')->nullable();
            $table->text('commentaire_validation')->nullable();

            $table->timestamps();

            $table->unique(['employe_id', 'affectation_id', 'date_pointage'], 'unique_pointage_jour');
            $table->index(['entreprise_id', 'date_pointage', 'statut']);
            $table->index(['employe_id', 'date_pointage']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pointages');
    }
};
