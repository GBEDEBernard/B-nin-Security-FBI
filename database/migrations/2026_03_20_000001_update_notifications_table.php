<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Ajouter les nouveaux champs manquants
            if (!Schema::hasColumn('notifications', 'titre')) {
                $table->string('titre')->nullable()->after('type');
            }
            if (!Schema::hasColumn('notifications', 'message')) {
                $table->text('message')->nullable()->after('titre');
            }
            if (!Schema::hasColumn('notifications', 'statut')) {
                $table->enum('statut', ['brouillon', 'envoyee', 'echouee'])->default('envoyee')->after('message');
            }
            if (!Schema::hasColumn('notifications', 'cible_type')) {
                $table->string('cible_type')->nullable()->after('statut');
            }
            if (!Schema::hasColumn('notifications', 'cible_id')) {
                $table->unsignedBigInteger('cible_id')->nullable()->after('cible_type');
            }
            if (!Schema::hasColumn('notifications', 'envoyeur_id')) {
                $table->unsignedBigInteger('envoyeur_id')->nullable()->after('cible_id');
            }
            if (!Schema::hasColumn('notifications', 'entreprise_id')) {
                $table->unsignedBigInteger('entreprise_id')->nullable()->after('envoyeur_id');
            }
            if (!Schema::hasColumn('notifications', 'url')) {
                $table->string('url')->nullable()->after('entreprise_id');
            }
            if (!Schema::hasColumn('notifications', 'donnees')) {
                $table->json('donnees')->nullable()->after('url');
            }

            // Ajouter les index pour optimiser les requêtes
            $table->index(['cible_type', 'cible_id'], 'notifications_cible_index');
            $table->index(['statut'], 'notifications_statut_index');
            $table->index(['created_at'], 'notifications_created_index');
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex('notifications_cible_index');
            $table->dropIndex('notifications_statut_index');
            $table->dropIndex('notifications_created_index');

            $table->dropColumn([
                'titre',
                'message',
                'statut',
                'cible_type',
                'cible_id',
                'envoyeur_id',
                'entreprise_id',
                'url',
                'donnees',
            ]);
        });
    }
};
