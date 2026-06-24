<?php

namespace App\Models\Traits;

use App\Models\DeviceToken;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasDeviceTokens
{
    public function deviceTokens(): MorphMany
    {
        return $this->morphMany(DeviceToken::class, 'tokenable');
    }

    public function routeNotificationForOneSignal($notification = null): array
    {
        return $this->deviceTokens()
            ->where('is_active', true)
            ->pluck('player_id')
            ->toArray();
    }

    public function registerDeviceToken(string $playerId, string $platform = null, string $deviceModel = null): DeviceToken
    {
        return $this->deviceTokens()->updateOrCreate(
            ['player_id' => $playerId],
            [
                'platform' => $platform,
                'device_model' => $deviceModel,
                'is_active' => true,
                'last_used_at' => now(),
            ]
        );
    }

    public function unregisterDeviceToken(string $playerId): bool
    {
        return (bool) $this->deviceTokens()
            ->where('player_id', $playerId)
            ->update(['is_active' => false]);
    }
}
