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
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->unique();
            $table->string('slug')->unique()->index();
            $table->string('domaine')->nullable()->unique();
            $table->string('chemin_logo')->nullable();
            $table->text('description')->nullable();
            $table->json('metadonnees')->nullable();
            $table->enum('statut', ['active', 'inactive', 'suspended'])->default('active');
            $table->string('telephone')->nullable();
            $table->string('chemin_avatar')->nullable();
            $table->timestamp('active_le')->nullable();
            $table->timestamp('desactive_le')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
