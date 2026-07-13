<?php

namespace App\Console\Commands;

use App\Models\Abonnement;
use App\Models\Facture;
use App\Models\Employe;
use App\Notifications\NouvelleFactureNotification;
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

            $montant = $abonnement->montant_periode;

            foreach ($abonnement->entreprises as $entreprise) {
                $dejaFacture = Facture::where('abonnement_id', $abonnement->id)
                    ->where('entreprise_id', $entreprise->id)
                    ->whereMonth('date_emission', $now->month)
                    ->whereYear('date_emission', $now->year)
                    ->exists();

                if ($dejaFacture) {
                    continue;
                }

                $count = Facture::whereYear('date_emission', $now->year)->count();
                $numero = 'FACT-' . $now->format('Y') . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);

                $facture = Facture::create([
                    'abonnement_id' => $abonnement->id,
                    'entreprise_id' => $entreprise->id,
                    'numero_facture' => $numero,
                    'mois' => $now->month,
                    'annee' => $now->year,
                    'montant_ht' => $montant,
                    'tva' => 0,
                    'montant_ttc' => $montant,
                    'montant_paye' => 0,
                    'montant_restant' => $montant,
                    'date_emission' => $now,
                    'date_echeance' => $now->copy()->addDays(30),
                    'statut' => 'emise',
                    'notes' => "Facture {$abonnement->cycle_label} - {$abonnement->formule_label}",
                    'cree_par' => 'Système',
                ]);

                $this->notifierEntreprise($facture, $entreprise);

                $generateur++;
            }

            $abonnement->mettreAJourDatesPaiement();
        }

        $this->info("{$generateur} facture(s) d'abonnement générée(s).");
    }

    private function notifierEntreprise(Facture $facture, $entreprise): void
    {
        $employes = Employe::where('entreprise_id', $entreprise->id)
            ->where('est_actif', true)
            ->whereHas('roles', function ($q) {
                $q->whereIn('name', ['general_director', 'developpeur']);
            })
            ->get();

        foreach ($employes as $employe) {
            $employe->notify(new NouvelleFactureNotification($facture));
        }
    }

    private function doitFacturerCeMois(Abonnement $abonnement, Carbon $now): bool
    {
        $cycle = $abonnement->cycle_facturation ?? 'mensuel';

        if ($cycle === 'mensuel') {
            return true;
        }

        $dateDebut = $abonnement->date_debut ?? $abonnement->created_at;
        if (!$dateDebut) return false;

        $moisDepuisDebut = $dateDebut->diffInMonths($now);

        return match ($cycle) {
            'trimestriel' => $moisDepuisDebut % 3 === 0,
            'semestriel' => $moisDepuisDebut % 6 === 0,
            'annuel' => $moisDepuisDebut % 12 === 0,
            default => true,
        };
    }
}
