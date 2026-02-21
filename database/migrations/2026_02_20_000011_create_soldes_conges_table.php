<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('soldes_conges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entreprise_id')->constrained()->onDelete('cascade');
            $table->foreignId('employe_id')->constrained()->onDelete('cascade');

            $table->integer('annee');

            $table->integer('jours_acquis')->default(0);
            $table->integer('jours_pris')->default(0);
            $table->integer('jours_restants')->virtualAs('jours_acquis - jours_pris');

            $table->integer('jours_maladie_utilises')->default(0);
            $table->integer('jours_exceptionnels_utilises')->default(0);

            $table->timestamps();

            $table->unique(['employe_id', 'annee']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soldes_conges');
    }
};
