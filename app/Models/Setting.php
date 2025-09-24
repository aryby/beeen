<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'description',
    ];

    /**
     * Helper methods
     */
    public static function get($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        return static::castValue($setting->value, $setting->type);
    }

    public static function set($key, $value, $type = 'text', $description = null)
    {
        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'description' => $description,
            ]
        );
    }

    public static function castValue($value, $type)
    {
        switch ($type) {
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'number':
                return is_numeric($value) ? (float) $value : 0;
            case 'json':
                return json_decode($value, true) ?? [];
            case 'text':
            default:
                return $value;
        }
    }

    public function getCastedValueAttribute()
    {
        return static::castValue($this->value, $this->type);
    }

    /**
     * Configuration par défaut
     */
    public static function getDefaults()
    {
        return [
            // SMTP Configuration
            'smtp_host' => ['value' => 'localhost', 'type' => 'text', 'description' => 'Serveur SMTP'],
            'smtp_port' => ['value' => '587', 'type' => 'number', 'description' => 'Port SMTP'],
            'smtp_username' => ['value' => '', 'type' => 'text', 'description' => 'Nom d\'utilisateur SMTP'],
            'smtp_password' => ['value' => '', 'type' => 'text', 'description' => 'Mot de passe SMTP'],
            'smtp_encryption' => ['value' => 'tls', 'type' => 'text', 'description' => 'Chiffrement SMTP'],
            'mail_from_address' => ['value' => 'support@iptv2smartv.com', 'type' => 'text', 'description' => 'Adresse email expéditeur'],
            'mail_from_name' => ['value' => 'IPTV Pro', 'type' => 'text', 'description' => 'Nom expéditeur email'],
            
            // PayPal Configuration
            'paypal_client_id' => ['value' => '', 'type' => 'text', 'description' => 'PayPal Client ID'],
            'paypal_client_secret' => ['value' => '', 'type' => 'text', 'description' => 'PayPal Client Secret'],
            'paypal_sandbox' => ['value' => true, 'type' => 'boolean', 'description' => 'Mode sandbox PayPal'],
            
            // Site Configuration
            'site_name' => ['value' => 'IPTV Pro', 'type' => 'text', 'description' => 'Nom du site'],
            'subscription_description' => ['value' => 'Accédez à plus de 12000 chaînes HD, VOD illimité, sans publicité avec support 24/7.', 'type' => 'text', 'description' => 'Description commune des abonnements'],
            'contact_email' => ['value' => 'contact@iptv2smartv.com', 'type' => 'text', 'description' => 'Email de contact'],
            
            // reCAPTCHA
            'recaptcha_site_key' => ['value' => '', 'type' => 'text', 'description' => 'reCAPTCHA Site Key'],
            'recaptcha_secret_key' => ['value' => '', 'type' => 'text', 'description' => 'reCAPTCHA Secret Key'],
            
            // Legal Pages
            'terms_of_service' => ['value' => '', 'type' => 'text', 'description' => 'Conditions générales de vente'],
            'privacy_policy' => ['value' => '', 'type' => 'text', 'description' => 'Politique de confidentialité'],
            'legal_mentions' => ['value' => '', 'type' => 'text', 'description' => 'Mentions légales'],
        ];
    }

    public static function initializeDefaults()
    {
        $defaults = static::getDefaults();
        
        foreach ($defaults as $key => $config) {
            if (!static::where('key', $key)->exists()) {
                static::create([
                    'key' => $key,
                    'value' => $config['value'],
                    'type' => $config['type'],
                    'description' => $config['description'],
                ]);
            }
        }
    }
}