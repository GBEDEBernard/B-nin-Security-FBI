<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Cette migration:
     * 1. Supprime les colonnes non utilisées de la table users
     * 2. Ajoute les colonnes de connexion à la table employes
     */
    public function up(): void
    {
        // ── Table users: Supprimer les colonnes inutiles ──
        Schema::table('users', function (Blueprint $table) {
            // Supprimer les colonnes qui ne sont plus nécessaires
            if (Schema::hasColumn('users', 'entreprise_id')) {
                $table->dropForeign(['entreprise_id']);
                $table->dropColumn('entreprise_id');
            }
            if (Schema::hasColumn('users', 'employe_id')) {
                $table->dropForeign(['employe_id']);
                $table->dropColumn('employe_id');
            }
            if (Schema::hasColumn('users', 'client_id')) {
                $table->dropForeign(['client_id']);
                $table->dropColumn('client_id');
            }
            if (Schema::hasColumn('users', 'type_user')) {
                $table->dropColumn('type_user');
            }
        });

        // ── Table employes: Ajouter les colonnes de connexion ──
        Schema::table('employes', function (Blueprint $table) {
            // Colonnes de connexion si elles n'existent pas déjà
            if (!Schema::hasColumn('employes', 'est_connecte')) {
                $table->boolean('est_connecte')->default(false)->after('statut');
            }
            if (!Schema::hasColumn('employes', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('est_connecte');
            }
            if (!Schema::hasColumn('employes', 'last_login_ip')) {
                $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
            }
            if (!Schema::hasColumn('employes', 'password')) {
                $table->string('password')->nullable()->after('email');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // ── Table users: Remettre les colonnes ──
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('entreprise_id')->nullable();
            $table->unsignedBigInteger('employe_id')->nullable();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->string('type_user')->nullable();

            $table->foreign('entreprise_id')->references('id')->on('entreprises')->onDelete('set null');
            $table->foreign('employe_id')->references('id')->on('employes')->onDelete('set null');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('set null');
        });

        // ── Table employes: Supprimer les colonnes de connexion ──
        Schema::table('employes', function (Blueprint $table) {
            $table->dropColumn(['est_connecte', 'last_login_at', 'last_login_ip', 'password']);
        });
    }
};
