@component('mail::message')
# Remboursement de votre commande

Bonjour {{ $order->customer_name }},

Nous vous informons que votre commande **#{{ $order->order_number }}** a été remboursée.

## Détails du remboursement

- **Numéro de commande** : #{{ $order->order_number }}
- **Montant remboursé** : {{ number_format($order->refund_amount, 2) }}€
- **Date du remboursement** : {{ $order->refunded_at->format('d/m/Y à H:i') }}
- **Raison** : {{ $order->refund_reason ?? 'Non spécifiée' }}

## Informations importantes

- Le remboursement sera traité dans les **3-5 jours ouvrables** selon votre banque
- Si vous avez des questions, n'hésitez pas à nous contacter
- Votre abonnement IPTV a été désactivé suite à ce remboursement

@component('mail::button', ['url' => route('contact.index')])
Nous contacter
@endcomponent

Merci pour votre confiance.

Cordialement,<br>
L'équipe {{ config('app.name') }}
@endcomponent
