<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Notification as NotificationModel;

class NotificationApplication extends Notification implements ShouldQueue
{
    use Queueable;

    public string $titre;
    public string $message;
    public string $type;
    public ?string $url;
    public ?array $donnees;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        string $titre,
        string $message,
        string $type = 'info',
        ?string $url = null,
        ?array $donnees = null
    ) {
        $this->titre = $titre;
        $this->message = $message;
        $this->type = $type;
        $this->url = $url;
        $this->donnees = $donnees;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage())
            ->subject($this->titre)
            ->line($this->message);

        if ($this->url) {
            $mail->action('Voir plus', url($this->url));
        }

        return $mail;
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'titre' => $this->titre,
            'message' => $this->message,
            'type' => $this->type,
            'url' => $this->url,
            'donnees' => $this->donnees,
        ];
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'notifiable_type' => get_class($notifiable),
            'notifiable_id' => $notifiable->id,
            'type' => $this->type,
            'titre' => $this->titre,
            'message' => $this->message,
            'url' => $this->url,
            'donnees' => $this->donnees,
            'statut' => 'envoyee',
        ];
    }

    /**
     * Créer une notification en base de données
     */
    public static function creerEnBase(object $notifiable, array $data): NotificationModel
    {
        return NotificationModel::create([
            'notifiable_type' => get_class($notifiable),
            'notifiable_id' => $notifiable->id,
            'type' => $data['type'] ?? 'info',
            'titre' => $data['titre'],
            'message' => $data['message'],
            'statut' => 'envoyee',
            'url' => $data['url'] ?? null,
            'donnees' => $data['donnees'] ?? null,
            'entreprise_id' => $data['entreprise_id'] ?? null,
            'envoyeur_id' => $data['envoyeur_id'] ?? null,
        ]);
    }
}
