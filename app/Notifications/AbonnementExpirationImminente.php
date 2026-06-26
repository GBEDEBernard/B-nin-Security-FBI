<?php

namespace App\Notifications;

use App\Models\Abonnement;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AbonnementExpirationImminente extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Abonnement $abonnement,
        public int $joursRestants,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject("Votre abonnement {$this->abonnement->formule_label} expire dans {$this->joursRestants} jours")
            ->greeting("Bonjour {$notifiable->nom_entreprise},")
            ->line("Votre abonnement **{$this->abonnement->formule_label}** arrive à expiration.")
            ->line("Formule : {$this->abonnement->formule_label}")
            ->line("Date de fin : {$this->abonnement->date_fin?->format('d/m/Y')}")
            ->line("Jours restants : {$this->joursRestants}")
            ->action('Voir mon abonnement', url('/admin/entreprise/abonnement'))
            ->line('Merci de renouveler votre abonnement pour continuer à bénéficier de nos services.');

        return $message;
    }

    public function toArray(object $notifiable): array
    {
        return [
            'abonnement_id' => $this->abonnement->id,
            'formule' => $this->abonnement->formule_label,
            'jours_restants' => $this->joursRestants,
            'date_fin' => $this->abonnement->date_fin?->format('Y-m-d'),
            'type' => 'expiration_imminente',
        ];
    }
}
