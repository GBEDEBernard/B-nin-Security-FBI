<?php

namespace App\Console\Commands;

use App\Models\Abonnement;
use App\Models\Facture;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenererFacturesAbonnements extends Command
{
    protected $signature = 'abonnements:facturer';
    protected $description = 'Génère les factures pour les abonnements actifs selon leur cycle de facturation';

    public function handle(): void
    {
        $now = now();
        $generateur = 0;

        $abonnements = Abonnement::with('entreprises')
            ->where('est_active', true)
            ->where('statut', 'actif')
            ->where('est_en_essai', false)
            ->get();

        foreach ($abonnements as $abonnement) {
            if (!$this->doitFacturerCeMois($abonnement, $now)) {
                continue;
            }

            foreach ($abonnement->entreprises as $entreprise) {
                $dejaFacture = Facture::where('abonnement_id', $abonnement->id)
                    ->where('entreprise_id', $entreprise->id)
                    ->whereMonth('date_emission', $now->month)
                    ->whereYear('date_emission', $now->year)
                    ->exists();

                if ($dejaFacture) {
                    continue;
                }

                $numero = 'ABO-' . $now->format('Ym') . '-' . str_pad($abonnement->id, 4, '0', STR_PAD_LEFT);

                Facture::create([
                    'abonnement_id' => $abonnement->id,
                    'entreprise_id' => $entreprise->id,
                    'numero_facture' => $numero,
                    'montant_ht' => $abonnement->montant_mensuel,
                    'tva' => 0,
                    'montant_ttc' => $abonnement->montant_mensuel,
                    'montant_paye' => 0,
                    'montant_restant' => $abonnement->montant_mensuel,
                    'date_emission' => $now,
                    'date_echeance' => $now->copy()->addDays(30),
                    'statut' => 'en_attente',
                    'notes' => "Facture automatique - {$abonnement->formule_label} - " . ucfirst($abonnement->cycle_facturation ?? 'mensuel'),
                    'cree_par' => 'system',
                ]);

                $generateur++;
            }

            $abonnement->mettreAJourDatesPaiement();
        }

        $this->info("{$generateur} facture(s) d'abonnement générée(s).");
    }

    private function doitFacturerCeMois(Abonnement $abonnement, Carbon $now): bool
    {
        $dateDebut = $abonnement->date_debut;
        if (!$dateDebut) return false;

        $cycle = $abonnement->cycle_facturation ?? 'mensuel';
        $moisDepuisDebut = $dateDebut->diffInMonths($now);

        return match ($cycle) {
            'mensuel' => true,
            'trimestriel' => $moisDepuisDebut % 3 === 0,
            'semestriel' => $moisDepuisDebut % 6 === 0,
            'annuel' => $moisDepuisDebut % 12 === 0,
            default => true,
        };
    }
}
