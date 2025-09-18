<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MessageTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'device_type',
        'subject',
        'message',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByDevice($query, $deviceType)
    {
        return $query->where('device_type', $deviceType);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Helper methods
     */
    public static function getDeviceTypes()
    {
        return [
            'mag' => 'MAG Box',
            'kodi' => 'Kodi',
            'android' => 'Android',
            'ios' => 'iOS',
            'smart_tv' => 'Smart TV',
            'pc' => 'PC/Windows',
            'mac' => 'Mac',
            'firestick' => 'Fire TV Stick',
            'general' => 'Général',
        ];
    }

    public function getDeviceTypeNameAttribute()
    {
        $types = static::getDeviceTypes();
        return $types[$this->device_type] ?? $this->device_type;
    }

    /**
     * Remplacer les variables dans le template
     */
    public function renderMessage($variables = [])
    {
        $message = $this->message;
        
        foreach ($variables as $key => $value) {
            $message = str_replace('{{' . $key . '}}', $value, $message);
        }
        
        return $message;
    }

    public function renderSubject($variables = [])
    {
        $subject = $this->subject;
        
        foreach ($variables as $key => $value) {
            $subject = str_replace('{{' . $key . '}}', $value, $subject);
        }
        
        return $subject;
    }

    /**
     * Templates par défaut
     */
    public static function getDefaultTemplates()
    {
        return [
            [
                'name' => 'Configuration MAG Box',
                'device_type' => 'mag',
                'subject' => 'Configuration de votre MAG Box - {{order_number}}',
                'message' => "Bonjour {{customer_name}},\n\nVoici vos informations de configuration pour votre MAG Box :\n\n🔗 **Portal URL :** {{portal_url}}\n🔑 **Code IPTV :** {{iptv_code}}\n\n**Instructions :**\n1. Allumez votre MAG Box\n2. Allez dans Paramètres > Serveurs\n3. Entrez l'URL du portal ci-dessus\n4. Redémarrez votre MAG Box\n5. Profitez de vos chaînes !\n\n📞 Support 24/7 disponible si besoin.\n\nCordialement,\nL'équipe {{app_name}}",
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Lien M3U pour Kodi',
                'device_type' => 'kodi',
                'subject' => 'Votre lien M3U pour Kodi - {{order_number}}',
                'message' => "Bonjour {{customer_name}},\n\nVoici votre lien M3U pour Kodi :\n\n🔗 **Lien M3U :** {{m3u_url}}\n🔑 **Code IPTV :** {{iptv_code}}\n\n**Installation sur Kodi :**\n1. Ouvrez Kodi\n2. Allez dans Add-ons > PVR IPTV Simple Client\n3. Configurez avec le lien M3U ci-dessus\n4. Redémarrez Kodi\n5. Vos chaînes apparaîtront dans TV\n\n📱 Besoin d'aide ? Consultez nos tutoriels !\n\nCordialement,\nL'équipe {{app_name}}",
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Identifiants généraux',
                'device_type' => 'general',
                'subject' => 'Vos identifiants IPTV - {{order_number}}',
                'message' => "Bonjour {{customer_name}},\n\nVos identifiants IPTV sont prêts :\n\n🔑 **Code IPTV :** {{iptv_code}}\n📅 **Expire le :** {{expires_at}}\n💰 **Abonnement :** {{subscription_name}}\n\n**Informations de connexion :**\n• Serveur : {{server_url}}\n• Nom d'utilisateur : {{username}}\n• Mot de passe : {{password}}\n\n📚 **Tutoriels d'installation :**\n{{tutorials_link}}\n\n📞 **Support 24/7 :**\n{{support_link}}\n\nMerci de votre confiance !\nL'équipe {{app_name}}",
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Support technique',
                'device_type' => 'general',
                'subject' => 'Support technique - {{order_number}}',
                'message' => "Bonjour {{customer_name}},\n\nSuite à votre demande, voici les informations techniques :\n\n🔧 **Votre commande :** {{order_number}}\n📺 **Abonnement :** {{subscription_name}}\n🔑 **Code IPTV :** {{iptv_code}}\n\n**Liens utiles :**\n• Tutoriels : {{tutorials_link}}\n• Support : {{support_link}}\n• Test de connexion : {{test_link}}\n\nNotre équipe reste à votre disposition pour toute assistance.\n\nCordialement,\nL'équipe Support {{app_name}}",
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];
    }
}