<x-mail::message>
# 🎉 Commande confirmée !

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

@if($expires_at)
**Expire le :** {{ $expires_at->format('d/m/Y') }}
@endif

> ⚠️ **Important :** Conservez précieusement ce code. Il vous permet d'accéder à votre service IPTV.
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

- ✅ **1000+** chaînes HD/4K
- ✅ **VOD** illimité
- ✅ **Sans** publicité
- ✅ **Multi-appareils** (3 simultanés)
- ✅ **Support** 24/7

---

Merci de votre confiance !

L'équipe {{ config('app.name') }}

<x-mail::subcopy>
Si vous avez des questions, n'hésitez pas à nous contacter à {{ App\Models\Setting::get('contact_email', 'contact@example.com') }}
</x-mail::subcopy>
</x-mail::message>