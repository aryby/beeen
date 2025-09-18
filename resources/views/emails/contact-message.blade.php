<x-mail::message>
# 📨 Nouveau message de contact

Un nouveau message a été reçu sur le site.

## 👤 Informations du contact

**Nom :** {{ $customerName }}  
**Email :** {{ $customerEmail }}  
**Type :** {{ ucfirst($messageType) }}  
**Date :** {{ $contactMessage->created_at->format('d/m/Y à H:i') }}

## 📝 Sujet
{{ $messageSubject }}

## 💬 Message
{{ $messageContent }}

---

<x-mail::button :url="route('admin.messages.show', $contactMessage)">
Répondre au message
</x-mail::button>

---

**Répondre directement :** {{ $customerEmail }}

L'équipe {{ config('app.name') }}
</x-mail::message>