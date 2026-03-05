<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('parametres', function (Blueprint $table) {
            $table->id();
            $table->string('cle')->unique()->comment('Clé unique du paramètre');
            $table->text('valeur')->nullable()->comment('Valeur du paramètre (JSON pour les objets)');
            $table->string('type')->default('string')->comment('Type de donnée: string, integer, boolean, json, array');
            $table->string('categorie')->comment('Catégorie du paramètre');
            $table->string('description')->nullable()->description('Description du paramètre');
            $table->boolean('est_visible')->default(true)->comment('Visible dans l\'interface');
            $table->boolean('est_modifiable')->default(true)->comment('Modifiable par l\'utilisateur');
            $table->timestamps();

            $table->index('categorie');
            $table->index('cle');
        });

        // Insérer les paramètres par défaut
        DB::table('parametres')->insert([
            // Paramètres généraux
            ['cle' => 'app.nom', 'valeur' => 'Bénin Security', 'type' => 'string', 'categorie' => 'general', 'description' => 'Nom de l\'application', 'est_visible' => true, 'est_modifiable' => true],
            ['cle' => 'app.description', 'valeur' => 'Plateforme de gestion de sécurité', 'type' => 'string', 'categorie' => 'general', 'description' => 'Description de l\'application', 'est_visible' => true, 'est_modifiable' => true],
            ['cle' => 'app.timezone', 'valeur' => 'Africa/Porto-Novo', 'type' => 'string', 'categorie' => 'general', 'description' => 'Fuseau horaire', 'est_visible' => true, 'est_modifiable' => true],
            ['cle' => 'app.locale', 'valeur' => 'fr', 'type' => 'string', 'categorie' => 'general', 'description' => 'Langue par défaut', 'est_visible' => true, 'est_modifiable' => true],
            ['cle' => 'app.devise', 'valeur' => 'XOF', 'type' => 'string', 'categorie' => 'general', 'description' => 'Devise', 'est_visible' => true, 'est_modifiable' => true],
            ['cle' => 'app.devise_symbole', 'valeur' => 'F', 'type' => 'string', 'categorie' => 'general', 'description' => 'Symbole de la devise', 'est_visible' => true, 'est_modifiable' => true],
            ['cle' => 'app.logo', 'valeur' => null, 'type' => 'string', 'categorie' => 'general', 'description' => 'Logo de l\'application', 'est_visible' => true, 'est_modifiable' => true],
            ['cle' => 'app.favicon', 'valeur' => null, 'type' => 'string', 'categorie' => 'general', 'description' => 'Favicon de l\'application', 'est_visible' => true, 'est_modifiable' => true],

            // Paramètres email
            ['cle' => 'mail.driver', 'valeur' => 'smtp', 'type' => 'string', 'categorie' => 'email', 'description' => 'Driver d\'envoi d\'email', 'est_visible' => true, 'est_modifiable' => true],
            ['cle' => 'mail.host', 'valeur' => 'smtp.mailgun.org', 'type' => 'string', 'categorie' => 'email', 'description' => 'Hôte SMTP', 'est_visible' => true, 'est_modifiable' => true],
            ['cle' => 'mail.port', 'valeur' => '587', 'type' => 'integer', 'categorie' => 'email', 'description' => 'Port SMTP', 'est_visible' => true, 'est_modifiable' => true],
            ['cle' => 'mail.username', 'valeur' => null, 'type' => 'string', 'categorie' => 'email', 'description' => 'Nom d\'utilisateur SMTP', 'est_visible' => true, 'est_modifiable' => true],
            ['cle' => 'mail.password', 'valeur' => null, 'type' => 'string', 'categorie' => 'email', 'description' => 'Mot de passe SMTP', 'est_visible' => false, 'est_modifiable' => true],
            ['cle' => 'mail.encryption', 'valeur' => 'tls', 'type' => 'string', 'categorie' => 'email', 'description' => 'Encryption SMTP', 'est_visible' => true, 'est_modifiable' => true],
            ['cle' => 'mail.from.address', 'valeur' => 'noreply@benin-security.com', 'type' => 'string', 'categorie' => 'email', 'description' => 'Adresse email expéditeur', 'est_visible' => true, 'est_modifiable' => true],
            ['cle' => 'mail.from.name', 'valeur' => 'Bénin Security', 'type' => 'string', 'categorie' => 'email', 'description' => 'Nom de l\'expéditeur', 'est_visible' => true, 'est_modifiable' => true],

            // Paramètres sécurité
            ['cle' => 'security.password_min_length', 'valeur' => '8', 'type' => 'integer', 'categorie' => 'security', 'description' => 'Longueur minimale du mot de passe', 'est_visible' => true, 'est_modifiable' => true],
            ['cle' => 'security.password_require_uppercase', 'valeur' => '1', 'type' => 'boolean', 'categorie' => 'security', 'description' => 'Exiger une majuscule', 'est_visible' => true, 'est_modifiable' => true],
            ['cle' => 'security.password_require_lowercase', 'valeur' => '1', 'type' => 'boolean', 'categorie' => 'security', 'description' => 'Exiger une minuscule', 'est_visible' => true, 'est_modifiable' => true],
            ['cle' => 'security.password_require_numbers', 'valeur' => '1', 'type' => 'boolean', 'categorie' => 'security', 'description' => 'Exiger un chiffre', 'est_visible' => true, 'est_modifiable' => true],
            ['cle' => 'security.password_require_special', 'valeur' => '1', 'type' => 'boolean', 'categorie' => 'security', 'description' => 'Exiger un caractère spécial', 'est_visible' => true, 'est_modifiable' => true],
            ['cle' => 'security.session_lifetime', 'valeur' => '120', 'type' => 'integer', 'categorie' => 'security', 'description' => 'Durée de vie de la session (minutes)', 'est_visible' => true, 'est_modifiable' => true],
            ['cle' => 'security.max_login_attempts', 'valeur' => '5', 'type' => 'integer', 'categorie' => 'security', 'description' => 'Nombre maximal de tentatives de connexion', 'est_visible' => true, 'est_modifiable' => true],
            ['cle' => 'security.lockout_duration', 'valeur' => '15', 'type' => 'integer', 'categorie' => 'security', 'description' => 'Durée de verrouillage après échecs (minutes)', 'est_visible' => true, 'est_modifiable' => true],
            ['cle' => 'security.enable_2fa', 'valeur' => '0', 'type' => 'boolean', 'categorie' => 'security', 'description' => 'Activer l\'authentification à deux facteurs', 'est_visible' => true, 'est_modifiable' => true],
            ['cle' => 'security.maintenance_mode', 'valeur' => '0', 'type' => 'boolean', 'categorie' => 'security', 'description' => 'Mode maintenance', 'est_visible' => true, 'est_modifiable' => true],
            ['cle' => 'security.maintenance_message', 'valeur' => 'Le site est en maintenance. Veuillez revenir plus tard.', 'type' => 'string', 'categorie' => 'security', 'description' => 'Message de maintenance', 'est_visible' => true, 'est_modifiable' => true],

            // Paramètres API
            ['cle' => 'api.enabled', 'valeur' => '1', 'type' => 'boolean', 'categorie' => 'api', 'description' => 'Activer l\'API', 'est_visible' => true, 'est_modifiable' => true],
            ['cle' => 'api.token_expiration', 'valeur' => '365', 'type' => 'integer', 'categorie' => 'api', 'description' => 'Expiration du token API (jours)', 'est_visible' => true, 'est_modifiable' => true],
            ['cle' => 'api.rate_limit', 'valeur' => '60', 'type' => 'integer', 'categorie' => 'api', 'description' => 'Limite de requêtes par minute', 'est_visible' => true, 'est_modifiable' => true],
            ['cle' => 'api.whitelist_ips', 'valeur' => null, 'type' => 'json', 'categorie' => 'api', 'description' => 'Liste des IPs autorisées', 'est_visible' => true, 'est_modifiable' => true],

            // Paramètres Mobile
            ['cle' => 'mobile.app_version', 'valeur' => '1.0.0', 'type' => 'string', 'categorie' => 'mobile', 'description' => 'Version actuelle de l\'application mobile', 'est_visible' => true, 'est_modifiable' => true],
            ['cle' => 'mobile.minimum_version', 'valeur' => '1.0.0', 'type' => 'string', 'categorie' => 'mobile', 'description' => 'Version minimale requise', 'est_visible' => true, 'est_modifiable' => true],
            ['cle' => 'mobile.force_update', 'valeur' => '0', 'type' => 'boolean', 'categorie' => 'mobile', 'description' => 'Forcer la mise à jour', 'est_visible' => true, 'est_modifiable' => true],
            ['cle' => 'mobile.notifications_enabled', 'valeur' => '1', 'type' => 'boolean', 'categorie' => 'mobile', 'description' => 'Activer les notifications push', 'est_visible' => true, 'est_modifiable' => true],
            ['cle' => 'mobile.geolocation_enabled', 'valeur' => '1', 'type' => 'boolean', 'categorie' => 'mobile', 'description' => 'Activer la géolocalisation', 'est_visible' => true, 'est_modifiable' => true],
            ['cle' => 'mobile.offline_mode', 'valeur' => '1', 'type' => 'boolean', 'categorie' => 'mobile', 'description' => 'Activer le mode hors ligne', 'est_visible' => true, 'est_modifiable' => true],

            // Paramètres Paiement
            ['cle' => 'paiement.fedapay.enabled', 'valeur' => '1', 'type' => 'boolean', 'categorie' => 'paiement', 'description' => 'Activer Fedapay', 'est_visible' => true, 'est_modifiable' => true],
            ['cle' => 'paiement.fedapay.mode', 'valeur' => 'sandbox', 'type' => 'string', 'categorie' => 'paiement', 'description' => 'Mode Fedapay (sandbox/live)', 'est_visible' => true, 'est_modifiable' => true],
            ['cle' => 'paiement.fedapay.api_key', 'valeur' => null, 'type' => 'string', 'categorie' => 'paiement', 'description' => 'Clé API Fedapay', 'est_visible' => false, 'est_modifiable' => true],
            ['cle' => 'paiement.fedapay.secret_key', 'valeur' => null, 'type' => 'string', 'categorie' => 'paiement', 'description' => 'Clé secrète Fedapay', 'est_visible' => false, 'est_modifiable' => true],
            ['cle' => 'paiement.stripe.enabled', 'valeur' => '0', 'type' => 'boolean', 'categorie' => 'paiement', 'description' => 'Activer Stripe', 'est_visible' => true, 'est_modifiable' => true],
            ['cle' => 'paiement.stripe.key', 'valeur' => null, 'type' => 'string', 'categorie' => 'paiement', 'description' => 'Clé Stripe', 'est_visible' => false, 'est_modifiable' => true],
            ['cle' => 'paiement.stripe.secret', 'valeur' => null, 'type' => 'string', 'categorie' => 'paiement', 'description' => 'Secret Stripe', 'est_visible' => false, 'est_modifiable' => true],

            // Paramètres Backup
            ['cle' => 'backup.enabled', 'valeur' => '1', 'type' => 'boolean', 'categorie' => 'backup', 'description' => 'Activer les sauvegardes automatiques', 'est_visible' => true, 'est_modifiable' => true],
            ['cle' => 'backup.frequency', 'valeur' => 'daily', 'type' => 'string', 'categorie' => 'backup', 'description' => 'Fréquence de sauvegarde', 'est_visible' => true, 'est_modifiable' => true],
            ['cle' => 'backup.retention_days', 'valeur' => '30', 'type' => 'integer', 'categorie' => 'backup', 'description' => 'Durée de conservation (jours)', 'est_visible' => true, 'est_modifiable' => true],
            ['cle' => 'backup.destination', 'valeur' => 'local', 'type' => 'string', 'categorie' => 'backup', 'description' => 'Destination de sauvegarde', 'est_visible' => true, 'est_modifiable' => true],
            ['cle' => 'backup.compress', 'valeur' => '1', 'type' => 'boolean', 'categorie' => 'backup', 'description' => 'Compresser les sauvegardes', 'est_visible' => true, 'est_modifiable' => true],

            // Paramètres Notifications
            ['cle' => 'notifications.email_enabled', 'valeur' => '1', 'type' => 'boolean', 'categorie' => 'notifications', 'description' => 'Activer les notifications par email', 'est_visible' => true, 'est_modifiable' => true],
            ['cle' => 'notifications.sms_enabled', 'valeur' => '0', 'type' => 'boolean', 'categorie' => 'notifications', 'description' => 'Activer les notifications SMS', 'est_visible' => true, 'est_modifiable' => true],
            ['cle' => 'notifications.push_enabled', 'valeur' => '1', 'type' => 'boolean', 'categorie' => 'notifications', 'description' => 'Activer les notifications push', 'est_visible' => true, 'est_modifiable' => true],

            // Paramètres Abonnement
            ['cle' => 'abonnement.trial_days', 'valeur' => '14', 'type' => 'integer', 'categorie' => 'abonnement', 'description' => 'Jours d\'essai gratuit', 'est_visible' => true, 'est_modifiable' => true],
            ['cle' => 'abonnement.default_plan', 'valeur' => 'basic', 'type' => 'string', 'categorie' => 'abonnement', 'description' => 'Plan par défaut', 'est_visible' => true, 'est_modifiable' => true],
            ['cle' => 'abonnement.auto_renew', 'valeur' => '1', 'type' => 'boolean', 'categorie' => 'abonnement', 'description' => 'Renouvellement automatique', 'est_visible' => true, 'est_modifiable' => true],
            ['cle' => 'abonnement.grace_period', 'valeur' => '7', 'type' => 'integer', 'categorie' => 'abonnement', 'description' => 'Période de grâce après expiration (jours)', 'est_visible' => true, 'est_modifiable' => true],

            // Paramètres Facturation
            ['cle' => 'facturation.tva', 'valeur' => '18', 'type' => 'integer', 'categorie' => 'facturation', 'description' => 'Taux de TVA (%)', 'est_visible' => true, 'est_modifiable' => true],
            ['cle' => 'facturation.devise', 'valeur' => 'XOF', 'type' => 'string', 'categorie' => 'facturation', 'description' => 'Devise de facturation', 'est_visible' => true, 'est_modifiable' => true],
            ['cle' => 'facturation.delai_paiement', 'valeur' => '30', 'type' => 'integer', 'categorie' => 'facturation', 'description' => 'Délai de paiement (jours)', 'est_visible' => true, 'est_modifiable' => true],
            ['cle' => 'facturation.penalite_retard', 'valeur' => '5', 'type' => 'integer', 'categorie' => 'facturation', 'description' => 'Pénalité de retard (%)', 'est_visible' => true, 'est_modifiable' => true],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parametres');
    }
};
