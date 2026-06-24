<?php

namespace App\Notifications\Channels;

use App\Services\OneSignalService;
use Illuminate\Notifications\Notification;

class OneSignalChannel
{
    protected OneSignalService $oneSignal;

    public function __construct(OneSignalService $oneSignal)
    {
        $this->oneSignal = $oneSignal;
    }

    public function send(object $notifiable, Notification $notification): void
    {
        if (!$this->oneSignal->isConfigured()) {
            return;
        }

        $message = $notification->toOneSignal($notifiable);

        if (empty($message['player_ids']) && empty($message['external_user_ids'])) {
            $playerIds = $notifiable->routeNotificationForOneSignal($notification);
            if (empty($playerIds)) {
                return;
            }
            $message['include_player_ids'] = $playerIds;
        }

        $fields = [
            'headings' => ['en' => $message['title'] ?? 'Notification'],
            'contents' => ['en' => $message['message'] ?? ''],
            'data' => $message['data'] ?? [],
            'url' => $message['url'] ?? config('onesignal.default_url'),
            'chrome_web_icon' => $message['icon'] ?? config('onesignal.default_icon'),
            'android_channel_id' => $message['channel_id'] ?? null,
        ];

        if (!empty($message['player_ids'])) {
            $fields['include_player_ids'] = $message['player_ids'];
        } elseif (!empty($message['external_user_ids'])) {
            $fields['include_external_user_ids'] = $message['external_user_ids'];
        }

        $this->oneSignal->sendNotification($fields);
    }
}
