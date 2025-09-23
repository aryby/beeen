<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class DynamicConfigService
{
    /**
     * Configure SMTP dynamiquement à partir des settings de la base de données
     */
    public static function configureSmtp()
    {
        $smtpHost = Setting::get('smtp_host');
        $smtpPort = Setting::get('smtp_port');
        $smtpUsername = Setting::get('smtp_username');
        $smtpPassword = Setting::get('smtp_password');
        $smtpEncryption = Setting::get('smtp_encryption');
        $contactEmail = Setting::get('mail_from_address', Setting::get('contact_email', 'support@iptv2smartv.com'));
        $siteName = Setting::get('mail_from_name', Setting::get('site_name', 'IPTV Pro'));

        // Vérifier si SMTP est configuré
        if (empty($smtpHost) || empty($smtpUsername) || empty($smtpPassword)) {
            \Log::warning('SMTP not properly configured in settings');
            return false;
        }

        // Configurer SMTP dynamiquement
        Config::set('mail.default', 'smtp');
        Config::set('mail.mailers.smtp.host', $smtpHost);
        Config::set('mail.mailers.smtp.port', $smtpPort);
        Config::set('mail.mailers.smtp.username', $smtpUsername);
        Config::set('mail.mailers.smtp.password', $smtpPassword);
        Config::set('mail.mailers.smtp.encryption', $smtpEncryption);
        Config::set('mail.from.address', $contactEmail);
        Config::set('mail.from.name', $siteName);

        // Pour Laravel 12, on force la reconfiguration du manager mail
        try {
            app('mail.manager')->forgetMailers();
            \Log::info('SMTP configuration updated dynamically');
        } catch (\Exception $e) {
            \Log::warning('Failed to update mail configuration: ' . $e->getMessage());
        }

        return true;
    }

    /**
     * Vérifier si SMTP est configuré
     */
    public static function isSmtpConfigured()
    {
        $smtpHost = Setting::get('smtp_host');
        $smtpUsername = Setting::get('smtp_username');
        $smtpPassword = Setting::get('smtp_password');

        return !empty($smtpHost) && !empty($smtpUsername) && !empty($smtpPassword);
    }

    /**
     * Configurer PayPal dynamiquement
     */
    public static function configurePayPal()
    {
        $clientId = Setting::get('paypal_client_id');
        $clientSecret = Setting::get('paypal_client_secret');
        $sandbox = Setting::get('paypal_sandbox', true);

        if (empty($clientId) || empty($clientSecret)) {
            \Log::warning('PayPal not properly configured in settings');
            return false;
        }

        // Les services PayPal utilisent déjà les settings directement
        // Cette méthode est pour la cohérence et les futurs besoins
        return true;
    }

    /**
     * Vérifier si PayPal est configuré
     */
    public static function isPayPalConfigured()
    {
        $clientId = Setting::get('paypal_client_id');
        $clientSecret = Setting::get('paypal_client_secret');

        return !empty($clientId) && !empty($clientSecret);
    }

    /**
     * Configurer toutes les configurations dynamiques
     */
    public static function configureAll()
    {
        self::configureSmtp();
        self::configurePayPal();
    }
}
