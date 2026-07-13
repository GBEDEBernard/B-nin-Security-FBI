<?php

namespace App\Notifications;

use App\Models\Facture;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NouvelleFactureNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Facture $facture,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'facture_id' => $this->facture->id,
            'numero_facture' => $this->facture->numero_facture,
            'montant_ttc' => $this->facture->montant_ttc,
            'entreprise_id' => $this->facture->entreprise_id,
            'message' => "Nouvelle facture {$this->facture->numero_facture} de {$this->facture->montant_ttc} FCFA",
            'titre' => 'Nouvelle facture générée',
            'icon' => 'file-earmark-text',
            'color' => 'success',
            'url' => route('admin.entreprise.factures.show', $this->facture->id),
        ];
    }
}
