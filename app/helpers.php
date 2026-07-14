<?php

use App\Models\SystemSetting;

if (!function_exists('system_setting')) {
    function system_setting(string $key, mixed $default = null): mixed
    {
        return SystemSetting::get($key, $default);
    }
}
