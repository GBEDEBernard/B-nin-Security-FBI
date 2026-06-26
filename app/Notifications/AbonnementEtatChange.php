<?php

namespace App\Notifications;

use App\Models\Abonnement;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AbonnementEtatChange extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Abonnement $abonnement,
        public string $nouvelEtat,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return match ($this->nouvelEtat) {
            'suspendu' => $this->mailSuspendu($notifiable),
            'actif' => $this->mailActif($notifiable),
            'resilie' => $this->mailResilie($notifiable),
            'expire' => $this->mailExpire($notifiable),
            'essai_termine' => $this->mailEssaiTermine($notifiable),
            default => $this->mailDefaut($notifiable),
        };
    }

    private function mailSuspendu(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Votre abonnement {$this->abonnement->formule_label} a été suspendu")
            ->greeting("Bonjour {$notifiable->nom_entreprise},")
            ->line("Votre abonnement **{$this->abonnement->formule_label}** a été suspendu.")
            ->line('Vous ne pouvez plus accéder à certaines fonctionnalités.')
            ->action('Contacter le support', url('/contact'))
            ->line('Merci de régulariser votre situation pour réactiver votre abonnement.');
    }

    private function mailActif(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Votre abonnement {$this->abonnement->formule_label} est actif")
            ->greeting("Bonjour {$notifiable->nom_entreprise},")
            ->line("Votre abonnement **{$this->abonnement->formule_label}** est maintenant actif.")
            ->line('Vous avez accès à toutes les fonctionnalités de votre formule.')
            ->action('Accéder à mon espace', url('/admin/entreprise'));
    }

    private function mailResilie(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Votre abonnement {$this->abonnement->formule_label} a été résilié")
            ->greeting("Bonjour {$notifiable->nom_entreprise},")
            ->line("Votre abonnement **{$this->abonnement->formule_label}** a été résilié.")
            ->line('Merci pour votre confiance. Nous espérons vous revoir bientôt.')
            ->action('Souscrire à nouveau', url('/contact'));
    }

    private function mailExpire(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Votre abonnement {$this->abonnement->formule_label} a expiré")
            ->greeting("Bonjour {$notifiable->nom_entreprise},")
            ->line("Votre abonnement **{$this->abonnement->formule_label}** a expiré.")
            ->line('Pour continuer à utiliser nos services, merci de renouveler votre abonnement.')
            ->action('Renouveler', url('/admin/entreprise/abonnement'));
    }

    private function mailEssaiTermine(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Votre période d'essai {$this->abonnement->formule_label} est terminée")
            ->greeting("Bonjour {$notifiable->nom_entreprise},")
            ->line("Votre période d'essai est terminée.")
            ->line('Souscrivez à une formule pour continuer à utiliser nos services.')
            ->action('Voir les formules', url('/admin/entreprise/abonnement'));
    }

    private function mailDefaut(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Changement d'état de votre abonnement")
            ->greeting("Bonjour {$notifiable->nom_entreprise},")
            ->line("L'état de votre abonnement **{$this->abonnement->formule_label}** a changé.")
            ->line("Nouvel état : {$this->nouvelEtat}");
    }

    public function toArray(object $notifiable): array
    {
        return [
            'abonnement_id' => $this->abonnement->id,
            'formule' => $this->abonnement->formule_label,
            'nouvel_etat' => $this->nouvelEtat,
            'type' => 'etat_change',
        ];
    }
}
