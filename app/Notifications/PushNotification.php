<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class PushNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $title,
        public string $message,
        public string $type = 'info',
        public ?string $url = null,
        public array $data = [],
    ) {}

    public function via(object $notifiable): array
    {
        $channels = [];

        if (config('onesignal.app_id')) {
            $channels[] = \App\Notifications\Channels\OneSignalChannel::class;
        }

        return $channels;
    }

    public function toOneSignal(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'url' => $this->url,
            'data' => array_merge($this->data, [
                'type' => $this->type,
            ]),
        ];
    }
}
