<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('apk_versions', function (Blueprint $table) {
            $table->id();
            $table->string('version', 20);
            $table->integer('version_code')->unique();
            $table->enum('type', ['alpha', 'beta', 'stable'])->default('stable');
            $table->string('fichier_path')->nullable();
            $table->bigInteger('taille')->nullable()->comment('Taille en octets');
            $table->string('checksum', 64)->nullable()->comment('SHA256 du fichier');
            $table->text('notes')->nullable();
            $table->json('changelog')->nullable();
            $table->unsignedInteger('telechargements')->default(0);
            $table->boolean('est_active')->default(false);
            $table->boolean('est_obligatoire')->default(false);
            $table->timestamp('date_publication')->nullable();
            $table->foreignId('publie_par')->constrained('users');
            $table->softDeletes();
            $table->timestamps();

            $table->unique('version');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('apk_versions');
    }
};
