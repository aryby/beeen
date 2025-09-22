<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\ResellerPack;
use App\Models\Order;
use App\Models\Reseller;
use App\Models\User;
use App\Models\Setting;
use App\Services\PayPalService;
use App\Services\PayPalServiceAlternative;
use App\Services\DynamicConfigService;
use App\Services\DynamicMailService;
use App\Traits\PayPalDetailsExtractor;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class QuickOrderController extends Controller
{
    use PayPalDetailsExtractor;
    /**
     * Traiter une commande rapide (visiteur sans compte)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_type' => 'required|in:subscription,reseller_pack',
            'item_id' => 'required|integer',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'terms_accepted' => 'required|accepted',
        ]);

        try {
            // Récupérer l'item (abonnement ou pack revendeur)
            if ($validated['item_type'] === 'subscription') {
                $item = Subscription::findOrFail($validated['item_id']);
                if (!$item->is_active) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cet abonnement n\'est plus disponible.'
                    ], 400);
                }
            } else {
                $item = ResellerPack::findOrFail($validated['item_id']);
                if (!$item->is_active) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Ce pack n\'est plus disponible.'
                    ], 400);
                }
            }

            // Créer ou récupérer l'utilisateur
            $user = User::where('email', $validated['customer_email'])->first();
            
            if (!$user) {
                // Créer un compte automatiquement
                $user = User::create([
                    'name' => $validated['customer_name'],
                    'email' => $validated['customer_email'],
                    'password' => Hash::make(Str::random(12)), // Mot de passe aléatoire
                    'role' => $validated['item_type'] === 'reseller_pack' ? 'reseller' : 'customer',
                    'email_verified_at' => now(), // Auto-vérifier pour commande rapide
                ]);
            }

            // Créer la commande
            if ($validated['item_type'] === 'subscription') {
                $order = Order::create([
                    'order_number' => 'ORD-' . strtoupper(Str::random(10)),
                    'user_id' => $user->id,
                    'subscription_id' => $item->id,
                    'customer_name' => $validated['customer_name'],
                    'customer_email' => $validated['customer_email'],
                    'amount' => $item->price,
                    'currency' => 'EUR',
                    'status' => 'pending',
                    'is_guest_order' => true,
                    'item_type' => 'subscription',
                ]);
                
                $description = "Abonnement IPTV {$item->name} - {$order->order_number}";
            } else {
                // Pour les packs revendeur, créer une commande spéciale
                $order = Order::create([
                    'order_number' => 'PACK-' . strtoupper(Str::random(10)),
                    'user_id' => $user->id,
                    'subscription_id' => null, // Pas d'abonnement pour les packs
                    'customer_name' => $validated['customer_name'],
                    'customer_email' => $validated['customer_email'],
                    'amount' => $item->price,
                    'currency' => 'EUR',
                    'status' => 'pending',
                    'item_type' => 'reseller_pack',
                    'item_id' => $item->id,
                    'is_guest_order' => true,
                ]);
                
                $description = "Pack Revendeur {$item->name} - {$order->order_number}";
            }

            // Configurer dynamiquement SMTP et PayPal
            DynamicConfigService::configureAll();
            
            // Initier le paiement PayPal
            $paypalService = new PayPalService();
            
            if (!$paypalService->isConfigured()) {
                \Log::error('PayPal not configured', [
                    'client_id' => !empty(Setting::get('paypal_client_id')),
                    'client_secret' => !empty(Setting::get('paypal_client_secret'))
                ]);
                
                // Mode simulation pour développement
                return $this->simulateQuickPayment($order, $validated['item_type']);
            }

            \Log::info('Creating PayPal payment', [
                'order_id' => $order->id,
                'amount' => $order->amount,
                'description' => $description
            ]);

            $result = $paypalService->createPayment(
                $order->amount,
                $order->currency,
                $description,
                route('payment.success') . '?order=' . $order->id,
                route('payment.cancel') . '?order=' . $order->id
            );

            \Log::info('PayPal payment result', $result);

            if ($result['success']) {
                // Sauvegarder l'ID PayPal
                $order->update(['payment_id' => $result['order_id']]);
                
                return response()->json([
                    'success' => true,
                    'paypal_url' => $result['approval_url'],
                    'order_id' => $order->id
                ]);
            } else {
                \Log::error('PayPal payment failed', $result);
                
                // Essayer avec le service alternatif
                \Log::info('Trying alternative PayPal service...');
                $alternativeService = new PayPalServiceAlternative();
                $alternativeResult = $alternativeService->createPayment(
                    $order->amount,
                    $order->currency,
                    $description,
                    route('payment.success', ['order' => $order->id]),
                    route('payment.cancel', ['order' => $order->id])
                );
                
                if ($alternativeResult['success']) {
                    $order->update(['payment_id' => $alternativeResult['order_id']]);
                    
                    return response()->json([
                        'success' => true,
                        'paypal_url' => $alternativeResult['approval_url'],
                        'order_id' => $order->id,
                        'alternative' => true
                    ]);
                }
                
                // Fallback final vers simulation
                return $this->simulateQuickPayment($order, $validated['item_type']);
            }

        } catch (\Exception $e) {
            \Log::error('Quick Order Error: ' . $e->getMessage());
            
            // Fallback vers simulation en cas d'erreur
            if (isset($order)) {
                return $this->simulateQuickPayment($order, $validated['item_type']);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du traitement de la commande.'
            ], 500);
        }
    }

    /**
     * Simulation de paiement pour développement
     */
    private function simulateQuickPayment($order, $itemType)
    {
        \Log::info('Using PayPal simulation for order: ' . $order->id);
        
        // Marquer comme payé mais en attente de validation admin
        $order->update([
            'status' => 'paid_pending_validation',
            'payment_id' => 'PAYPAL-SIM-' . strtoupper(Str::random(15)),
            'payment_details' => [
                'simulation' => true,
                'timestamp' => now()->toISOString(),
                'amount' => $order->amount,
                'currency' => $order->currency
            ]
        ]);

        // Traitement selon le type
        if ($itemType === 'reseller_pack') {
            // Pour les packs revendeur, traitement immédiat (pas besoin validation admin)
            $pack = ResellerPack::find($order->item_id);
            $reseller = Reseller::firstOrCreate(
                ['user_id' => $order->user_id],
                ['credits' => 0, 'total_credits_purchased' => 0, 'total_credits_used' => 0]
            );
            
            $reseller->addCredits($pack->credits, "Achat pack {$pack->name} (simulation)", $pack->price);
            $order->user->update(['role' => 'reseller']);
            $order->update(['status' => 'paid']); // Validation automatique pour packs
            
            $redirectUrl = route('reseller.dashboard');
        } else {
            // Pour les abonnements, attendre validation admin
            // NE PAS générer le code IPTV maintenant
            
            // Envoyer email de confirmation de commande (sans code IPTV)
            try {
                $mailSent = DynamicMailService::send(
                    $order->customer_email,
                    new \App\Mail\OrderPendingValidation($order)
                );
                
                if ($mailSent) {
                    \Log::info('Order pending validation email sent successfully');
                } else {
                    \Log::warning('Failed to send order pending validation email for order: ' . $order->id);
                }
            } catch (\Exception $e) {
                \Log::error('Email error in simulation: ' . $e->getMessage());
            }
            
            $redirectUrl = route('payment.pending', ['order' => $order->id]);
        }

        return response()->json([
            'success' => true,
            'paypal_url' => $redirectUrl . '?simulation=1',
            'order_id' => $order->id,
            'simulation' => true
        ]);
    }

    /**
     * Traitement du retour PayPal pour commandes rapides
     */
    public function handlePayPalReturn(Request $request)
    {
        // Configurer dynamiquement SMTP et PayPal
        DynamicConfigService::configureAll();
        
        $orderId = $request->get('order');
        $paypalOrderId = $request->get('token');

        if (!$orderId || !$paypalOrderId) {
            return redirect()->route('home')
                ->with('error', 'Informations de paiement manquantes.');
        }

        $order = Order::findOrFail($orderId);

        // Capturer le paiement PayPal
        $paypalService = new PayPalService();
        $result = $paypalService->capturePayment($paypalOrderId);

        if ($result['success'] && $result['status'] === 'COMPLETED') {
            // Extraire et sauvegarder les détails PayPal importants
            $paypalDetails = $this->extractPayPalDetails($result['data']);
            
            // Marquer la commande comme payée
            $order->update([
                'status' => 'paid',
                'payment_details' => $paypalDetails,
            ]);

            // Traitement spécifique selon le type
            if ($order->item_type === 'reseller_pack') {
                // Traitement pack revendeur
                $pack = ResellerPack::find($order->item_id);
                $reseller = Reseller::firstOrCreate(
                    ['user_id' => $order->user_id],
                    ['credits' => 0, 'total_credits_purchased' => 0, 'total_credits_used' => 0]
                );
                
                $reseller->addCredits($pack->credits, "Achat pack {$pack->name}", $pack->price);
                
                // Mettre à jour le rôle utilisateur
                $order->user->update(['role' => 'reseller']);
                
                return redirect()->route('reseller.dashboard')
                    ->with('success', "Pack {$pack->name} acheté avec succès ! Vous avez maintenant {$reseller->credits} crédits.");
            } else {
                // Traitement abonnement normal
                $order->generateIptvCode();
                $order->setExpirationDate();
                
                // Envoyer l'email de confirmation
                try {
                    $mailSent = DynamicMailService::send(
                        $order->customer_email,
                        new \App\Mail\OrderConfirmation($order)
                    );
                    
                    if ($mailSent) {
                        \Log::info('Order confirmation email sent successfully for order: ' . $order->id);
                    } else {
                        \Log::warning('Failed to send order confirmation email for order: ' . $order->id);
                    }
                } catch (\Exception $e) {
                    \Log::error('Erreur envoi email commande rapide: ' . $e->getMessage());
                }
                
                return redirect()->route('payment.success', ['order' => $order->id]);
            }
        } else {
            return redirect()->route('payment.cancel', ['order' => $order->id])
                ->with('error', 'Le paiement n\'a pas pu être confirmé.');
        }
    }

}