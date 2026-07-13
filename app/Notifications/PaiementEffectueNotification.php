<?php

namespace App\Notifications;

use App\Models\Facture;
use App\Models\PaiementFacture;
use Illuminate\Notifications\Notification;

class PaiementEffectueNotification extends Notification
{

    public function __construct(
        public Facture $facture,
        public PaiementFacture $paiement,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $entreprise = $this->facture->entreprise;

        return [
            'type' => 'paiement_effectue',
            'facture_id' => $this->facture->id,
            'paiement_id' => $this->paiement->id,
            'numero_facture' => $this->facture->numero_facture,
            'entreprise_id' => $this->facture->entreprise_id,
            'entreprise_nom' => $entreprise?->nom_entreprise ?? 'Inconnue',
            'montant' => $this->paiement->montant,
            'mode_paiement' => $this->paiement->mode_paiement,
            'titre' => 'Paiement reçu',
            'message' => ($entreprise?->nom_entreprise ?? 'Une entreprise') . " a effectué un paiement de " . number_format($this->paiement->montant, 0, ',', ' ') . " FCFA sur la facture {$this->facture->numero_facture}",
            'icon' => 'credit-card',
            'color' => 'success',
            'url' => route('admin.superadmin.facturation.show', $this->facture->id),
        ];
    }
}