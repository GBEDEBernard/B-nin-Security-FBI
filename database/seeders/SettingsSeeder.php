<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            ['key' => 'app_name', 'value' => 'Bénin Security', 'type' => 'string', 'group' => 'general', 'label' => 'Nom de l\'application', 'description' => 'Nom affiché de l\'application'],
            ['key' => 'app_description', 'value' => 'Plateforme de gestion de sécurité professionnelle', 'type' => 'string', 'group' => 'general', 'label' => 'Description', 'description' => 'Description courte de l\'application'],
            ['key' => 'app_url', 'value' => config('app.url', 'http://localhost'), 'type' => 'string', 'group' => 'general', 'label' => 'URL', 'description' => 'URL publique de l\'application'],
            ['key' => 'app_logo', 'value' => '', 'type' => 'string', 'group' => 'general', 'label' => 'Logo', 'description' => 'Chemin du logo'],
            ['key' => 'timezone', 'value' => 'Africa/Porto-Novo', 'type' => 'string', 'group' => 'general', 'label' => 'Fuseau horaire', 'description' => 'Fuseau horaire par défaut'],
            ['key' => 'locale', 'value' => 'fr', 'type' => 'string', 'group' => 'general', 'label' => 'Langue', 'description' => 'Langue par défaut'],
            ['key' => 'maintenance_mode', 'value' => '0', 'type' => 'boolean', 'group' => 'general', 'label' => 'Mode maintenance', 'description' => 'Activer le mode maintenance'],

            ['key' => 'password_min_length', 'value' => '8', 'type' => 'number', 'group' => 'security', 'label' => 'Longueur min. mot de passe', 'description' => 'Nombre de caractères minimum'],
            ['key' => 'password_require_special', 'value' => '1', 'type' => 'boolean', 'group' => 'security', 'label' => 'Caractères spéciaux requis', 'description' => 'Exiger des caractères spéciaux'],
            ['key' => 'password_require_numbers', 'value' => '1', 'type' => 'boolean', 'group' => 'security', 'label' => 'Chiffres requis', 'description' => 'Exiger des chiffres'],
            ['key' => 'session_lifetime', 'value' => '120', 'type' => 'number', 'group' => 'security', 'label' => 'Durée de session (min)', 'description' => 'Durée de validité d\'une session'],
            ['key' => 'max_login_attempts', 'value' => '5', 'type' => 'number', 'group' => 'security', 'label' => 'Tentatives max.', 'description' => 'Nombre de tentatives avant blocage'],
            ['key' => 'lockout_duration', 'value' => '15', 'type' => 'number', 'group' => 'security', 'label' => 'Durée de blocage (min)', 'description' => 'Durée de blocage après échecs'],
            ['key' => 'two_factor_enabled', 'value' => '0', 'type' => 'boolean', 'group' => 'security', 'label' => '2FA', 'description' => 'Authentification à deux facteurs'],

            ['key' => 'mail_driver', 'value' => 'smtp', 'type' => 'string', 'group' => 'email', 'label' => 'Driver', 'description' => 'Type de transport email'],
            ['key' => 'mail_host', 'value' => '', 'type' => 'string', 'group' => 'email', 'label' => 'Hôte SMTP', 'description' => 'Adresse du serveur SMTP'],
            ['key' => 'mail_port', 'value' => '587', 'type' => 'number', 'group' => 'email', 'label' => 'Port', 'description' => 'Port du serveur SMTP'],
            ['key' => 'mail_encryption', 'value' => 'tls', 'type' => 'string', 'group' => 'email', 'label' => 'Encryption', 'description' => 'Type d\'encryption (tls/ssl)'],
            ['key' => 'mail_username', 'value' => '', 'type' => 'string', 'group' => 'email', 'label' => 'Utilisateur', 'description' => 'Nom d\'utilisateur SMTP'],
            ['key' => 'mail_password', 'value' => '', 'type' => 'password', 'group' => 'email', 'label' => 'Mot de passe', 'description' => 'Mot de passe SMTP'],
            ['key' => 'mail_from_address', 'value' => '', 'type' => 'string', 'group' => 'email', 'label' => 'Email expéditeur', 'description' => 'Adresse d\'envoi'],
            ['key' => 'mail_from_name', 'value' => 'Bénin Security', 'type' => 'string', 'group' => 'email', 'label' => 'Nom expéditeur', 'description' => 'Nom affiché dans l\'email'],

            ['key' => 'api_rate_limit', 'value' => '60', 'type' => 'number', 'group' => 'api', 'label' => 'Limite de requêtes/min', 'description' => 'Nombre max de requêtes par minute'],
            ['key' => 'api_token_expiration', 'value' => '1440', 'type' => 'number', 'group' => 'api', 'label' => 'Expiration token (min)', 'description' => 'Durée de validité du token API'],
            ['key' => 'api_debug', 'value' => '0', 'type' => 'boolean', 'group' => 'api', 'label' => 'Mode debug API', 'description' => 'Activer les réponses détaillées'],

            ['key' => 'app_version_minimum', 'value' => '1.0.0', 'type' => 'string', 'group' => 'mobile', 'label' => 'Version minimum', 'description' => 'Version minimale requise'],
            ['key' => 'app_version_current', 'value' => '1.0.0', 'type' => 'string', 'group' => 'mobile', 'label' => 'Version actuelle', 'description' => 'Dernière version disponible'],
            ['key' => 'notification_enabled', 'value' => '1', 'type' => 'boolean', 'group' => 'mobile', 'label' => 'Notifications push', 'description' => 'Activer les notifications'],
            ['key' => 'geolocation_enabled', 'value' => '1', 'type' => 'boolean', 'group' => 'mobile', 'label' => 'Géolocalisation', 'description' => 'Activer le tracking GPS'],
            ['key' => 'gps_radius_default', 'value' => '100', 'type' => 'number', 'group' => 'mobile', 'label' => 'Rayon GPS (m)', 'description' => 'Rayon de pointage par défaut'],
            ['key' => 'gps_frequency', 'value' => '30', 'type' => 'number', 'group' => 'mobile', 'label' => 'Fréquence GPS (min)', 'description' => 'Intervalle entre les relevés'],

            ['key' => 'onesignal_app_id', 'value' => '', 'type' => 'string', 'group' => 'notification', 'label' => 'App ID OneSignal', 'description' => 'Identifiant OneSignal'],
            ['key' => 'onesignal_rest_api_key', 'value' => '', 'type' => 'password', 'group' => 'notification', 'label' => 'REST API Key', 'description' => 'Clé API REST OneSignal'],
            ['key' => 'push_notifications_enabled', 'value' => '1', 'type' => 'boolean', 'group' => 'notification', 'label' => 'Push activé', 'description' => 'Activer les notifications push'],
            ['key' => 'push_sound', 'value' => 'default', 'type' => 'string', 'group' => 'notification', 'label' => 'Son de notification', 'description' => 'Son par défaut'],

            ['key' => 'taux_tva', 'value' => '18', 'type' => 'number', 'group' => 'facturation', 'label' => 'TVA (%)', 'description' => 'Taux de TVA par défaut'],
            ['key' => 'devise', 'value' => 'FCFA', 'type' => 'string', 'group' => 'facturation', 'label' => 'Devise', 'description' => 'Monnaie par défaut'],
            ['key' => 'delai_paiement_jours', 'value' => '30', 'type' => 'number', 'group' => 'facturation', 'label' => 'Délai de paiement (j)', 'description' => 'Nombre de jours avant échéance'],
            ['key' => 'penalite_retard', 'value' => '2', 'type' => 'number', 'group' => 'facturation', 'label' => 'Pénalité retard (%)', 'description' => 'Pénalité par mois de retard'],
            ['key' => 'mode_paiements_acceptes', 'value' => '["carte","virement","especes","mobile_money"]', 'type' => 'json', 'group' => 'facturation', 'label' => 'Moyens de paiement', 'description' => 'Modes de paiement acceptés'],
            ['key' => 'numero_compte_bancaire', 'value' => '', 'type' => 'string', 'group' => 'facturation', 'label' => 'Compte bancaire', 'description' => 'Compte pour les virements'],
            ['key' => 'numero_compte_mobile', 'value' => '', 'type' => 'string', 'group' => 'facturation', 'label' => 'Compte mobile', 'description' => 'Numéro mobile money'],
        ];

        foreach ($defaults as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
