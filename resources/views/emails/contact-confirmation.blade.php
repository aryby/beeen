<x-mail::message>
# ✅ Message bien reçu !

Bonjour {{ $customerName }},

Nous avons bien reçu votre message et vous en remercions.

## 📋 Récapitulatif de votre message

**Sujet :** {{ $contactMessage->subject }}  
**Type :** {{ ucfirst($contactMessage->type) }}  
**Date :** {{ $contactMessage->created_at->format('d/m/Y à H:i') }}

## ⏱️ Délai de réponse

Notre équipe vous répondra dans les **24 heures** maximum (généralement sous quelques heures).

Pour les demandes urgentes, vous pouvez également nous contacter directement.

<x-mail::button :url="route('contact.index')" color="success">
Envoyer un autre message
</x-mail::button>

## 🔍 Suivi de votre demande

Vous pouvez suivre l'état de votre demande en vous connectant à votre compte.

@auth
<x-mail::button :url="route('dashboard')">
Mon tableau de bord
</x-mail::button>
@else
<x-mail::button :url="route('login')">
Se connecter
</x-mail::button>
@endauth

---

Merci de votre confiance !

L'équipe {{ config('app.name') }}

<x-mail::subcopy>
Ceci est un message automatique de confirmation. Vous recevrez une réponse personnalisée de notre équipe prochainement.
</x-mail::subcopy>
</x-mail::message>