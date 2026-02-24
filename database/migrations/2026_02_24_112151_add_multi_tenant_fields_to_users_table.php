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
        Schema::table('users', function (Blueprint $table) {
            // Type d'utilisateur: interne (employé de l'entreprise de sécurité) ou client
            $table->enum('type_user', ['interne', 'client'])->default('interne')->after('email');

            // Pour les clients qui ont un compte
            $table->foreignId('client_id')->nullable()->constrained('clients')->onDelete('set null')->after('entreprise_id');

            // Statut du compte utilisateur
            $table->boolean('is_active')->default(true)->after('type_user');

            // Journalisation des connexions
            $table->timestamp('last_login_at')->nullable()->after('is_active');
            $table->string('last_login_ip', 45)->nullable()->after('last_login_at');

            // Index pour optimiser les requêtes
            $table->index('type_user');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
            $table->dropColumn([
                'type_user',
                'client_id',
                'is_active',
                'last_login_at',
                'last_login_ip',
            ]);
        });
    }
};
