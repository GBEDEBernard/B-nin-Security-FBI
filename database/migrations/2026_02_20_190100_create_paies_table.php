<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entreprise_id')->constrained('entreprises')->onDelete('cascade');
            $table->foreignId('employe_id')->constrained('employes')->onDelete('cascade');

            $table->integer('mois');
            $table->integer('annee');

            $table->decimal('salaire_base', 10, 2)->default(0);
            $table->integer('jours_travailles')->default(0);
            $table->decimal('heures_normales', 8, 2)->default(0);
            $table->decimal('heures_supplementaires', 8, 2)->default(0);

            $table->decimal('prime_anciennete', 10, 2)->default(0);
            $table->decimal('prime_panier', 10, 2)->default(0);
            $table->decimal('prime_transport', 10, 2)->default(0);
            $table->decimal('prime_risque', 10, 2)->default(0);
            $table->decimal('prime_performance', 10, 2)->default(0);
            $table->decimal('autres_primes', 10, 2)->default(0);
            $table->decimal('indemnites', 10, 2)->default(0);

            $table->decimal('brut_imposable', 10, 2)->nullable();

            $table->decimal('cnss_part_salariale', 10, 2)->default(0);
            $table->decimal('cnss_part_patronale', 10, 2)->default(0);
            $table->decimal('ipps', 10, 2)->default(0);
            $table->decimal('autres_cotisations', 10, 2)->default(0);

            $table->decimal('avance_salaire', 10, 2)->default(0);
            $table->decimal('absence_deduction', 10, 2)->default(0);
            $table->decimal('autres_retenues', 10, 2)->default(0);

            $table->decimal('net_a_payer', 10, 2)->default(0);

            $table->date('date_paiement')->nullable();
            $table->enum('statut', ['calcule', 'valide', 'paye', 'annule'])->default('calcule');
            $table->string('bulletin_pdf', 255)->nullable();

            $table->json('details_calcul')->nullable();

            $table->foreignId('calcule_par')->nullable()->constrained('users');
            $table->foreignId('valide_par')->nullable()->constrained('users');

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['employe_id', 'mois', 'annee']);
            $table->index(['entreprise_id', 'mois', 'annee', 'statut']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paies');
    }
};
