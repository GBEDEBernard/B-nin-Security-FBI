<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->index();
            $table->text('value')->nullable();
            $table->string('type')->default('string');
            $table->string('group')->default('general');
            $table->boolean('is_encrypted')->default(false);
            $table->timestamps();
        });

        DB::table('system_settings')->insert([
            ['key' => 'app_name', 'value' => 'Bénin Security FBI', 'type' => 'string', 'group' => 'general', 'is_encrypted' => false],
            ['key' => 'app_env', 'value' => 'production', 'type' => 'string', 'group' => 'general', 'is_encrypted' => false],
            ['key' => 'app_debug', 'value' => '0', 'type' => 'boolean', 'group' => 'general', 'is_encrypted' => false],
            ['key' => 'app_url', 'value' => 'http://localhost', 'type' => 'string', 'group' => 'general', 'is_encrypted' => false],
            ['key' => 'app_timezone', 'value' => 'Africa/Porto-Novo', 'type' => 'string', 'group' => 'general', 'is_encrypted' => false],
            ['key' => 'app_locale', 'value' => 'fr', 'type' => 'string', 'group' => 'general', 'is_encrypted' => false],
            ['key' => 'mail_driver', 'value' => 'smtp', 'type' => 'string', 'group' => 'email', 'is_encrypted' => false],
            ['key' => 'mail_host', 'value' => '', 'type' => 'string', 'group' => 'email', 'is_encrypted' => false],
            ['key' => 'mail_port', 'value' => '587', 'type' => 'integer', 'group' => 'email', 'is_encrypted' => false],
            ['key' => 'mail_encryption', 'value' => 'tls', 'type' => 'string', 'group' => 'email', 'is_encrypted' => false],
            ['key' => 'mail_username', 'value' => '', 'type' => 'string', 'group' => 'email', 'is_encrypted' => false],
            ['key' => 'mail_password', 'value' => '', 'type' => 'string', 'group' => 'email', 'is_encrypted' => true],
            ['key' => 'mail_from_address', 'value' => '', 'type' => 'string', 'group' => 'email', 'is_encrypted' => false],
            ['key' => 'mail_from_name', 'value' => 'Bénin Security', 'type' => 'string', 'group' => 'email', 'is_encrypted' => false],
            ['key' => 'password_min_length', 'value' => '8', 'type' => 'integer', 'group' => 'security', 'is_encrypted' => false],
            ['key' => 'session_lifetime', 'value' => '120', 'type' => 'integer', 'group' => 'security', 'is_encrypted' => false],
            ['key' => 'max_login_attempts', 'value' => '5', 'type' => 'integer', 'group' => 'security', 'is_encrypted' => false],
            ['key' => 'maintenance_mode', 'value' => '0', 'type' => 'boolean', 'group' => 'security', 'is_encrypted' => false],
            ['key' => 'api_token_expiration', 'value' => '24', 'type' => 'integer', 'group' => 'api', 'is_encrypted' => false],
            ['key' => 'api_rate_limit', 'value' => '60', 'type' => 'integer', 'group' => 'api', 'is_encrypted' => false],
            ['key' => 'app_version_minimum', 'value' => '1.0.0', 'type' => 'string', 'group' => 'mobile', 'is_encrypted' => false],
            ['key' => 'notification_enabled', 'value' => '1', 'type' => 'boolean', 'group' => 'mobile', 'is_encrypted' => false],
            ['key' => 'geolocation_enabled', 'value' => '1', 'type' => 'boolean', 'group' => 'mobile', 'is_encrypted' => false],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
