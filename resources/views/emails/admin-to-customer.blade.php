<x-mail::message>
# 📧 Message de notre équipe

Bonjour {{ $recipientName }},

Vous recevez ce message de la part de notre équipe {{ config('app.name') }}.

@if($order)
## 📋 Concernant votre commande

**Numéro :** {{ $order->order_number }}  
**Abonnement :** {{ $order->subscription ? $order->subscription->name : 'Pack Revendeur' }}  
**Date :** {{ $order->created_at->format('d/m/Y') }}
@endif

## 💬 Message

{{ $messageContent }}

---

@if($order)
<x-mail::button :url="route('customer.dashboard')" color="primary">
Voir ma commande
</x-mail::button>
@endif

## 📞 Besoin d'aide ?

Si vous avez des questions, n'hésitez pas à nous contacter :

<x-mail::button :url="route('contact.index')" color="success">
Nous contacter
</x-mail::button>

---

Cordialement,  
**{{ $adminName }}**  
Équipe {{ config('app.name') }}

<x-mail::subcopy>
Ce message vous a été envoyé par notre équipe support. Si vous ne souhaitez plus recevoir ces messages, contactez-nous.
</x-mail::subcopy>
</x-mail::message>