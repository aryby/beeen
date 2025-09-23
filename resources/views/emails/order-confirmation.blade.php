<x-mail::message>
# 🎉 Commande confirmée !

@if($custom_message)
{!! nl2br(e($custom_message)) !!}
@else
Bonjour {{ $customer_name }},

Merci pour votre commande ! Votre abonnement IPTV est maintenant actif.

## 📋 Détails de votre commande

**Numéro de commande :** {{ $order->order_number }}  
**Abonnement :** {{ $subscription->name }}  
**Montant :** {{ $order->formatted_amount }}  
**Date :** {{ $order->created_at->format('d/m/Y à H:i') }}

@if($iptv_code)
## 🔑 Vos identifiants IPTV

**Code IPTV :** `{{ $iptv_code }}`

@if($m3u_username && $m3u_password)
**Nom d'utilisateur M3U :** `{{ $m3u_username }}`  
**Mot de passe M3U :** `{{ $m3u_password }}`  
@if($m3u_url)
**URL M3U :** [Télécharger la playlist]({{ $m3u_url }})
@endif
@endif

@if($expires_at)
**Expire le :** {{ $expires_at->format('d/m/Y') }}
@endif

> ⚠️ **Important :** Conservez précieusement ces identifiants. Ils vous permettent d'accéder à votre service IPTV.
@endif
@endif

## 📱 Installation

Pour installer votre service IPTV sur vos appareils :

<x-mail::button :url="route('tutorials.index')">
Voir les tutoriels d'installation
</x-mail::button>

## 📞 Support

Notre équipe est disponible 24h/24 pour vous accompagner :

<x-mail::button :url="route('contact.index')" color="success">
Contacter le support
</x-mail::button>

---

## ✨ Ce qui est inclus dans votre abonnement

- ✅ **12000+** chaînes HD/4K
- ✅ **VOD** illimité
- ✅ **Sans** publicité
- ✅ **Multi-appareils** (3 simultanés)
- ✅ **Support** 24/7

---

Merci de votre confiance !

L'équipe {{ config('app.name') }}

<x-mail::subcopy>
Si vous avez des questions, n'hésitez pas à nous contacter à {{ App\Models\Setting::get('contact_email', 'contact@iptv2smartv.com') }}
</x-mail::subcopy>
</x-mail::message>