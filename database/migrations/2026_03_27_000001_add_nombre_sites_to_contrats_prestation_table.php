<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contrats_prestation', function (Blueprint $table) {
            $table->integer('nombre_sites')->nullable()->after('nombre_agents_requis');
        });
    }

    public function down(): void
    {
        Schema::table('contrats_prestation', function (Blueprint $table) {
            $table->dropColumn('nombre_sites');
        });
    }
};
