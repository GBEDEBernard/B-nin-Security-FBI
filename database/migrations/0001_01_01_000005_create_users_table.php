<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('telephone')->nullable()->unique();
            $table->string('password');

            // Relations
            $table->foreignId('entreprise_id')->nullable()->constrained('entreprises')->onDelete('set null');
            $table->foreignId('employe_id')->nullable()->constrained('employes')->onDelete('set null');

            // Rôle
            $table->boolean('is_superadmin')->default(false);

            $table->timestamp('email_verified_at')->nullable();
            $table->string('remember_token', 100)->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Index
            $table->index('entreprise_id');
            $table->index('is_superadmin');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
