<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('abonnements', function (Blueprint $table) {
            // Vérifier et ajouter les colonnes manquantes seulement si elles n'existent pas
            if (!Schema::hasColumn('abonnements', 'employes_min')) {
                $table->integer('employes_min')->default(20)->after('formule');
            }
            if (!Schema::hasColumn('abonnements', 'employes_max')) {
                $table->integer('employes_max')->default(40)->after('employes_min');
            }
            if (!Schema::hasColumn('abonnements', 'tarif_par_employe')) {
                $table->decimal('tarif_par_employe', 10, 2)->default(0)->after('employes_max');
            }
            if (!Schema::hasColumn('abonnements', 'tarif_employe_supplementaire')) {
                $table->decimal('tarif_employe_supplementaire', 10, 2)->nullable()->after('tarif_par_employe');
            }
            if (!Schema::hasColumn('abonnements', 'sites_max')) {
                $table->integer('sites_max')->default(5)->after('tarif_employe_supplementaire');
            }
            if (!Schema::hasColumn('abonnements', 'type_formule')) {
                $table->string('type_formule')->default('basic')->after('sites_max');
            }
            if (!Schema::hasColumn('abonnements', 'duree_mois')) {
                // ✅ NULLABLE - Premium et Enterprise n'ont pas de limite de durée
                $table->integer('duree_mois')->nullable()->after('type_formule');
            }
            if (!Schema::hasColumn('abonnements', 'jours_essai')) {
                $table->integer('jours_essai')->default(7)->after('duree_mois');
            }
            if (!Schema::hasColumn('abonnements', 'montant_mensuel')) {
                $table->decimal('montant_mensuel', 10, 2)->default(0)->after('jours_essai');
            }
        });

        // Modifier duree_mois pour être nullable si elle existe déjà
        Schema::table('abonnements', function (Blueprint $table) {
            $table->integer('duree_mois')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('abonnements', function (Blueprint $table) {
            $columns = ['employes_min', 'employes_max', 'tarif_par_employe',
                'tarif_employe_supplementaire', 'sites_max', 'type_formule',
                'duree_mois', 'jours_essai', 'montant_mensuel'];

            foreach ($columns as $col) {
                if (Schema::hasColumn('abonnements', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
