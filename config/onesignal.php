<?php

return [
    /*
    |--------------------------------------------------------------------------
    | OneSignal Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for OneSignal push notification service.
    | Get your App ID and REST API Key from https://dashboard.onesignal.com
    |
    */

    'app_id' => env('ONESIGNAL_APP_ID', ''),

    'rest_api_key' => env('ONESIGNAL_REST_API_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Default notification settings
    |--------------------------------------------------------------------------
    */
    'default_url' => env('ONESIGNAL_DEFAULT_URL', ''),

    'default_icon' => env('ONESIGNAL_DEFAULT_ICON', ''),

    /*
    |--------------------------------------------------------------------------
    | Guzzle HTTP client configuration
    |--------------------------------------------------------------------------
    */
    'guzzle' => [
        'timeout' => 10,
        'connect_timeout' => 5,
    ],
];
