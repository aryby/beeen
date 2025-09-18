<x-mail::message>
# ✅ Paiement reçu avec succès !

Bonjour {{ $customer_name }},

Merci pour votre commande ! Votre paiement a été reçu et traité avec succès.

## 📋 Détails de votre commande

**Numéro de commande :** {{ $order->order_number }}  
**Abonnement :** {{ $subscription->name }}  
**Montant :** {{ $order->formatted_amount }}  
**Date :** {{ $order->created_at->format('d/m/Y à H:i') }}  
**Statut :** Paiement confirmé ✅

## ⏳ Prochaine étape

Votre commande est actuellement **en cours de validation** par notre équipe. 

**Vous recevrez vos identifiants IPTV par email dans les prochaines heures** (généralement sous 2-4 heures, maximum 24h).

## 🔄 Processus de validation

1. ✅ **Paiement reçu** - Terminé
2. 🔄 **Validation technique** - En cours
3. ⏳ **Génération des codes** - En attente
4. 📧 **Envoi des identifiants** - En attente

## 📞 Besoin d'aide ?

Si vous avez des questions sur votre commande :

<x-mail::button :url="route('contact.index')" color="primary">
Contacter le support
</x-mail::button>

---

## ℹ️ Pourquoi cette validation ?

Cette étape nous permet de :
- ✅ Vérifier la qualité de service
- ✅ Préparer vos identifiants personnalisés
- ✅ Garantir une activation parfaite
- ✅ Vous offrir le meilleur support

---

Merci de votre patience et de votre confiance !

L'équipe {{ config('app.name') }}

<x-mail::subcopy>
Vous recevrez un second email avec vos identifiants IPTV dès que la validation sera terminée.
</x-mail::subcopy>
</x-mail::message>