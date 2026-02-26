<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            // Authentification
            $table->string('password')->nullable()->after('email');
            $table->rememberToken()->nullable()->after('password');

            // Statut de connexion
            $table->boolean('est_connecte')->default(false)->after('remember_token');
            $table->timestamp('last_login_at')->nullable()->after('est_connecte');
            $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'password',
                'remember_token',
                'est_connecte',
                'last_login_at',
                'last_login_ip',
            ]);
        });
    }
};
