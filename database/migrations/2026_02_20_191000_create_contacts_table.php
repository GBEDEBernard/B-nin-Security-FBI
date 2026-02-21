<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entreprise_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->nullable()->constrained('clients')->onDelete('cascade');
            $table->foreignId('site_client_id')->nullable()->constrained('sites_clients')->onDelete('cascade');
            $table->foreignId('employe_id')->nullable()->constrained('employes')->onDelete('set null');

            $table->string('nom', 100);
            $table->string('prenoms', 150)->nullable();
            $table->string('fonction', 150)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('telephone', 50)->nullable();
            $table->string('telephone_secondaire', 50)->nullable();
            $table->text('adresse')->nullable();
            $table->boolean('est_principal')->default(false);
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['entreprise_id', 'client_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
