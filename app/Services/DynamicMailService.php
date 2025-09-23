<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class DynamicMailService
{
    /**
     * Envoyer un email avec configuration dynamique
     */
    public static function send($to, $mailable)
    {
        try {
            // Configurer SMTP dynamiquement avant l'envoi
            if (!self::configureSmtp()) {
                Log::warning('SMTP not configured, falling back to log driver');
                // Fallback vers log si SMTP non configuré
                Config::set('mail.default', 'log');
            }

            // Envoyer l'email
            Mail::to($to)->send($mailable);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Dynamic mail send error: ' . $e->getMessage());
            
            // Fallback vers log en cas d'erreur
            try {
                Config::set('mail.default', 'log');
                Mail::to($to)->send($mailable);
                Log::info('Email sent via log driver as fallback');
                return true;
            } catch (\Exception $fallbackError) {
                Log::error('Fallback mail error: ' . $fallbackError->getMessage());
                return false;
            }
        }
    }

    /**
     * Configurer SMTP à partir des settings
     */
    private static function configureSmtp()
    {
        $smtpHost = Setting::get('smtp_host');
        $smtpPort = Setting::get('smtp_port');
        $smtpUsername = Setting::get('smtp_username');
        $smtpPassword = Setting::get('smtp_password');
        $smtpEncryption = Setting::get('smtp_encryption');
        $contactEmail = Setting::get('mail_from_address', Setting::get('contact_email', 'support@iptv2smartv.com'));
        $siteName = Setting::get('mail_from_name', Setting::get('site_name', 'IPTV Pro'));

        // Align from domain with SMTP username to avoid 550 rejections
        try {
            $smtpUserDomain = str_contains($smtpUsername, '@') ? substr(strrchr($smtpUsername, '@'), 1) : null;
            $fromDomain = str_contains($contactEmail, '@') ? substr(strrchr($contactEmail, '@'), 1) : null;
            if ($smtpUserDomain && $fromDomain && strtolower($smtpUserDomain) !== strtolower($fromDomain)) {
                $contactEmail = $smtpUsername;
            }
        } catch (\Throwable $e) {
            // ignore
        }

        // Vérifier si SMTP est configuré
        if (empty($smtpHost) || empty($smtpUsername) || empty($smtpPassword)) {
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
}
