<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Channels\DatabaseChannel as BaseDatabaseChannel;
use Illuminate\Notifications\Notification;

class DatabaseChannel extends BaseDatabaseChannel
{
    protected function buildPayload($notifiable, Notification $notification): array
    {
        return [
            'type' => get_class($notification),
            'donnees' => $this->getData($notifiable, $notification),
            'lu_le' => null,
        ];
    }
}
