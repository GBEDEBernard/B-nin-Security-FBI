<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OneSignalService
{
    protected string $appId;
    protected string $restApiKey;
    protected string $baseUrl = 'https://onesignal.com/api/v1';

    public function __construct()
    {
        $this->appId = config('onesignal.app_id');
        $this->restApiKey = config('onesignal.rest_api_key');
    }

    public function isConfigured(): bool
    {
        return !empty($this->appId) && !empty($this->restApiKey);
    }

    public function sendNotification(array $fields): array
    {
        if (!$this->isConfigured()) {
            Log::warning('OneSignal non configuré. Notification non envoyée.', $fields);
            return ['success' => false, 'message' => 'OneSignal non configuré'];
        }

        $fields['app_id'] = $this->appId;

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . $this->restApiKey,
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/notifications", $fields);

            $body = $response->json();

            if ($response->successful()) {
                Log::info('Notification OneSignal envoyée avec succès', [
                    'id' => $body['id'] ?? null,
                    'recipients' => $body['recipients'] ?? 0,
                ]);

                return ['success' => true, 'data' => $body];
            }

            Log::error('Erreur OneSignal', [
                'status' => $response->status(),
                'body' => $body,
            ]);

            return ['success' => false, 'message' => $body['errors'][0] ?? 'Erreur inconnue'];
        } catch (\Exception $e) {
            Log::error('Exception OneSignal: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function sendToAll(string $title, string $message, array $data = []): array
    {
        return $this->sendNotification([
            'included_segments' => ['Total Subscribers'],
            'headings' => ['en' => $title],
            'contents' => ['en' => $message],
            'data' => $data,
            'url' => $data['url'] ?? config('onesignal.default_url'),
            'chrome_web_icon' => config('onesignal.default_icon'),
            'android_channel_id' => $data['channel_id'] ?? null,
        ]);
    }

    public function sendToUser(string $playerId, string $title, string $message, array $data = []): array
    {
        return $this->sendNotification([
            'include_player_ids' => [$playerId],
            'headings' => ['en' => $title],
            'contents' => ['en' => $message],
            'data' => $data,
            'url' => $data['url'] ?? config('onesignal.default_url'),
            'chrome_web_icon' => config('onesignal.default_icon'),
        ]);
    }

    public function sendToUsers(array $playerIds, string $title, string $message, array $data = []): array
    {
        return $this->sendNotification([
            'include_player_ids' => $playerIds,
            'headings' => ['en' => $title],
            'contents' => ['en' => $message],
            'data' => $data,
            'url' => $data['url'] ?? config('onesignal.default_url'),
            'chrome_web_icon' => config('onesignal.default_icon'),
        ]);
    }

    public function sendToSegment(string $segment, string $title, string $message, array $data = []): array
    {
        return $this->sendNotification([
            'included_segments' => [$segment],
            'headings' => ['en' => $title],
            'contents' => ['en' => $message],
            'data' => $data,
            'url' => $data['url'] ?? config('onesignal.default_url'),
            'chrome_web_icon' => config('onesignal.default_icon'),
        ]);
    }

    public function sendByExternalUserId(string $externalUserId, string $title, string $message, array $data = []): array
    {
        return $this->sendNotification([
            'include_external_user_ids' => [$externalUserId],
            'headings' => ['en' => $title],
            'contents' => ['en' => $message],
            'data' => $data,
            'url' => $data['url'] ?? config('onesignal.default_url'),
            'chrome_web_icon' => config('onesignal.default_icon'),
        ]);
    }

    public function sendByExternalUserIds(array $externalUserIds, string $title, string $message, array $data = []): array
    {
        return $this->sendNotification([
            'include_external_user_ids' => $externalUserIds,
            'headings' => ['en' => $title],
            'contents' => ['en' => $message],
            'data' => $data,
            'url' => $data['url'] ?? config('onesignal.default_url'),
            'chrome_web_icon' => config('onesignal.default_icon'),
        ]);
    }
}
