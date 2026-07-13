<?php

namespace App\Notifications;

use App\Models\PropositionContrat;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PropositionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public PropositionContrat $proposition,
        public string $action,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return match ($this->action) {
            'cree' => $this->mailCree($notifiable),
            'acceptee' => $this->mailAcceptee($notifiable),
            'refusee' => $this->mailRefusee($notifiable),
            default => $this->mailDefaut($notifiable),
        };
    }

    private function mailCree(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nouvelle proposition de contrat')
            ->greeting("Bonjour {$notifiable->nom_entreprise},")
            ->line("Une nouvelle proposition de contrat vous a été envoyée.")
            ->line("Service : {$this->proposition->type_service_label}")
            ->line("Nombre d'agents : {$this->proposition->nombre_agents}")
            ->action('Voir la proposition', url('/admin/entreprise/propositions/' . $this->proposition->id))
            ->line('Vous pouvez l\'accepter ou la refuser depuis votre espace.');
    }

    private function mailAcceptee(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Proposition de contrat acceptée')
            ->greeting("Bonjour,")
            ->line("La proposition de {$this->proposition->nom_entreprise} a été acceptée.")
            ->action('Voir la proposition', url('/admin/superadmin/propositions/' . $this->proposition->id));
    }

    private function mailRefusee(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Proposition de contrat refusée')
            ->greeting("Bonjour,")
            ->line("La proposition de {$this->proposition->nom_entreprise} a été refusée.")
            ->line("Motif : {$this->proposition->motif_rejet}")
            ->action('Voir la proposition', url('/admin/superadmin/propositions/' . $this->proposition->id));
    }

    private function mailDefaut(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Mise à jour de votre proposition de contrat')
            ->greeting("Bonjour {$notifiable->nom_entreprise},")
            ->line('Votre proposition de contrat a été mise à jour.')
            ->action('Voir la proposition', url('/admin/entreprise/propositions/' . $this->proposition->id));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'proposition_id' => $this->proposition->id,
            'nom_entreprise' => $this->proposition->nom_entreprise,
            'action' => $this->action,
            'type' => 'proposition',
        ];
    }
}
