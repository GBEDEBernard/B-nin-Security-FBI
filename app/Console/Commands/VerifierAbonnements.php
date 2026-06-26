<?php

namespace App\Console\Commands;

use App\Models\Abonnement;
use App\Models\ActivityLog;
use App\Notifications\AbonnementEtatChange;
use App\Notifications\AbonnementExpirationImminente;
use Carbon\Carbon;
use Illuminate\Console\Command;

class VerifierAbonnements extends Command
{
    protected $signature = 'abonnements:verifier';
    protected $description = 'Vérifie les abonnements : expire les dépassés, clôture les essais, envoie les rappels';

    public function handle(): void
    {
        $this->expirerAbonnementsDepasses();
        $this->cloturerEssaisArrives();
        $this->envoyerRappelsExpiration();
        $this->info('Vérification des abonnements terminée.');
    }

    private function expirerAbonnementsDepasses(): void
    {
        $expires = Abonnement::where('est_active', true)
            ->where('statut', 'actif')
            ->whereNotNull('date_fin')
            ->where('date_fin', '<', now())
            ->get();

        foreach ($expires as $abonnement) {
            $abonnement->update([
                'est_active' => false,
                'statut' => 'expire',
            ]);

            $this->logAction($abonnement, 'Abonnement expiré automatiquement (date de fin dépassée)');

            $abonnement->entreprises->each(function ($entreprise) use ($abonnement) {
                $entreprise->notify(new AbonnementEtatChange($abonnement, 'expire'));
            });
        }

        $this->info("{$expires->count()} abonnement(s) expiré(s).");
    }

    private function cloturerEssaisArrives(): void
    {
        $essais = Abonnement::where('est_en_essai', true)
            ->where('est_active', true)
            ->whereNotNull('date_fin_essai')
            ->where('date_fin_essai', '<', now())
            ->get();

        foreach ($essais as $abonnement) {
            $abonnement->update([
                'est_en_essai' => false,
                'est_active' => false,
                'statut' => 'expire',
            ]);

            $this->logAction($abonnement, 'Période d\'essai terminée - abonnement expiré');

            $abonnement->entreprises->each(function ($entreprise) use ($abonnement) {
                $entreprise->notify(new AbonnementEtatChange($abonnement, 'essai_termine'));
            });
        }

        $this->info("{$essais->count()} période(s) d'essai clôturée(s).");
    }

    private function envoyerRappelsExpiration(): void
    {
        $dans7Jours = now()->addDays(7)->format('Y-m-d');
        $demain = now()->addDay()->format('Y-m-d');

        $aRappeler = Abonnement::where('est_active', true)
            ->where('statut', 'actif')
            ->whereNotNull('date_fin')
            ->whereIn('date_fin', [$dans7Jours, $demain])
            ->get();

        foreach ($aRappeler as $abonnement) {
            $joursRestants = now()->diffInDays($abonnement->date_fin, false);

            $abonnement->entreprises->each(function ($entreprise) use ($abonnement, $joursRestants) {
                $entreprise->notify(new AbonnementExpirationImminente($abonnement, $joursRestants));
            });
        }

        $this->info("{$aRappeler->count()} rappel(s) d'expiration envoyé(s).");
    }

    private function logAction(Abonnement $abonnement, string $description): void
    {
        ActivityLog::create([
            'description' => $description,
            'subject_type' => Abonnement::class,
            'subject_id' => $abonnement->id,
            'causer_id' => null,
            'causer_type' => 'system',
            'properties' => [
                'formule' => $abonnement->formule,
                'statut' => $abonnement->statut,
                'date_fin' => $abonnement->date_fin?->format('Y-m-d'),
            ],
        ]);
    }
}
