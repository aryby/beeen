<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\Order;
use App\Models\Setting;
use App\Services\PayPalService;
use Illuminate\Support\Str;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::active()
            ->orderByDuration()
            ->get();

        $subscriptionDescription = Setting::get('subscription_description', 
            'Accédez à plus de 1000 chaînes HD, VOD illimité, sans publicité avec support 24/7.'
        );

        return view('subscriptions.index', compact('subscriptions', 'subscriptionDescription'));
    }

    public function checkout(Subscription $subscription)
    {
        if (!$subscription->is_active) {
            return redirect()->route('subscriptions.index')
                ->with('error', 'Cet abonnement n\'est plus disponible.');
        }

        // Rediriger les visiteurs non connectés vers la page de connexion
        if (!auth()->check()) {
            session(['intended_checkout' => route('subscriptions.checkout', $subscription)]);
            return redirect()->route('login')
                ->with('info', 'Veuillez vous connecter pour commander un abonnement.');
        }

        return view('subscriptions.checkout', compact('subscription'));
    }

    public function processCheckout(Request $request, Subscription $subscription)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_address' => 'nullable|string|max:1000',
            'terms_accepted' => 'required|accepted',
            'g-recaptcha-response' => 'required', // reCAPTCHA validation
        ]);

        // Vérifier reCAPTCHA
        $recaptchaSecret = Setting::get('recaptcha_secret_key');
        if ($recaptchaSecret) {
            $recaptchaResponse = $request->input('g-recaptcha-response');
            $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptchaSecret}&response={$recaptchaResponse}");
            $responseKeys = json_decode($response, true);
            
            if (!$responseKeys["success"]) {
                return back()->withErrors(['recaptcha' => 'Veuillez vérifier le reCAPTCHA.'])->withInput();
            }
        }

        // Créer la commande
        $order = Order::create([
            'order_number' => 'ORD-' . strtoupper(Str::random(10)),
            'user_id' => auth()->id(),
            'subscription_id' => $subscription->id,
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_address' => $validated['customer_address'],
            'amount' => $subscription->price,
            'currency' => 'EUR',
            'status' => 'pending',
        ]);

        // Rediriger vers PayPal
        return $this->redirectToPayPal($order);
    }

    private function redirectToPayPal(Order $order)
    {
        $paypalService = new PayPalService();
        
        if (!$paypalService->isConfigured()) {
            // Fallback simulation si PayPal pas configuré
            return $this->simulatePayment($order);
        }

        try {
            $result = $paypalService->createPayment(
                $order->amount,
                $order->currency,
                "Abonnement IPTV {$order->subscription->name} - {$order->order_number}",
                route('payment.success', ['order' => $order->id, 'token' => '__TOKEN__']),
                route('payment.cancel', ['order' => $order->id])
            );

            if ($result['success']) {
                // Sauvegarder l'ID PayPal pour le suivi
                $order->update(['payment_id' => $result['order_id']]);
                
                // Rediriger vers PayPal
                return redirect($result['approval_url']);
            } else {
                return redirect()->route('subscriptions.index')
                    ->with('error', 'Erreur lors de l\'initialisation du paiement: ' . $result['error']);
            }
        } catch (\Exception $e) {
            Log::error('PayPal Error: ' . $e->getMessage());
            return $this->simulatePayment($order);
        }
    }

    private function simulatePayment(Order $order)
    {
        // Simulation pour les tests
        $order->update([
            'status' => 'paid',
            'payment_id' => 'PAYPAL-SIM-' . strtoupper(Str::random(15)),
        ]);
        
        $order->generateIptvCode();
        $order->setExpirationDate();
        
        return redirect()->route('payment.success', ['order' => $order->id]);
    }

    public function paymentSuccess(Request $request)
    {
        // Log des paramètres de retour PayPal
        \Log::info('PayPal Return Parameters', $request->all());
        
        $orderId = $request->get('order');
        $paypalToken = $request->get('token');
        $payerId = $request->get('PayerID');

        if (!$orderId) {
            \Log::error('Missing order ID in PayPal return', $request->all());
            return redirect()->route('subscriptions.index')
                ->with('error', 'Informations de commande manquantes.');
        }

        $order = Order::findOrFail($orderId);
        \Log::info('Processing PayPal return for order', ['order_id' => $order->id, 'current_status' => $order->status]);

        // Si la commande n'est pas encore payée, essayer de capturer le paiement PayPal
        if (!$order->isPaid() && $paypalToken) {
            $paypalService = new PayPalService();
            $result = $paypalService->capturePayment($paypalToken);

            if ($result['success'] && $result['status'] === 'COMPLETED') {
                $order->update([
                    'status' => 'paid',
                    'payment_details' => $result['data'],
                ]);
                
                // Générer le code IPTV si c'est un abonnement
                if ($order->subscription_id) {
                    $order->generateIptvCode();
                    $order->setExpirationDate();
                }
                
                // Traitement spécial pour les packs revendeur
                if ($order->item_type === 'reseller_pack') {
                    $pack = ResellerPack::find($order->item_id);
                    $reseller = Reseller::firstOrCreate(
                        ['user_id' => $order->user_id],
                        ['credits' => 0, 'total_credits_purchased' => 0, 'total_credits_used' => 0]
                    );
                    
                    $reseller->addCredits($pack->credits, "Achat pack {$pack->name}", $pack->price);
                    $order->user->update(['role' => 'reseller']);
                    
                    return redirect()->route('reseller.dashboard')
                        ->with('success', "Pack {$pack->name} acheté avec succès ! Vous avez maintenant {$reseller->credits} crédits.");
                }
            }
        }

        if (!$order->isPaid()) {
            return redirect()->route('subscriptions.index')
                ->with('error', 'Le paiement n\'a pas été confirmé.');
        }

        // Envoyer l'email de confirmation avec les identifiants IPTV
        try {
            \Illuminate\Support\Facades\Mail::to($order->customer_email)
                ->send(new \App\Mail\OrderConfirmation($order));
        } catch (\Exception $e) {
            // Log l'erreur mais ne pas faire échouer la confirmation
            logger()->error('Erreur envoi email confirmation commande: ' . $e->getMessage());
        }

        return view('subscriptions.success', compact('order'));
    }

    public function paymentCancel()
    {
        return redirect()->route('subscriptions.index')
            ->with('warning', 'Paiement annulé. Vous pouvez réessayer quand vous le souhaitez.');
    }

    public function paymentWebhook(Request $request)
    {
        // TODO: Implémenter le webhook PayPal pour la confirmation automatique des paiements
        
        return response()->json(['status' => 'ok']);
    }
}