<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        try {
            $settings = Setting::getCached();

            foreach ($settings as $setting) {
                $configKey = $this->mapToConfig($setting->key);
                if ($configKey) {
                    config([$configKey => $setting->castedValue()]);
                }
            }
        } catch (\Exception $e) {
            // Table 'settings' n'existe pas encore (fresh install, migrations pas encore jouées)
        }
    }

    private function mapToConfig(string $key): ?string
    {
        return match ($key) {
            'app_name' => 'app.name',
            'app_url' => 'app.url',
            'timezone' => 'app.timezone',
            'locale' => 'app.locale',
            'maintenance_mode' => 'app.maintenance_mode',

            'session_lifetime' => 'session.lifetime',

            'mail_driver' => 'mail.default',
            'mail_host' => 'mail.mailers.smtp.host',
            'mail_port' => 'mail.mailers.smtp.port',
            'mail_encryption' => 'mail.mailers.smtp.encryption',
            'mail_username' => 'mail.mailers.smtp.username',
            'mail_password' => 'mail.mailers.smtp.password',
            'mail_from_address' => 'mail.from.address',
            'mail_from_name' => 'mail.from.name',

            default => null,
        };
    }
}
