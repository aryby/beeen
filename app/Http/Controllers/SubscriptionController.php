<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\Order;
use App\Models\Setting;
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

        // Rediriger vers PayPal (simulation pour l'instant)
        return $this->redirectToPayPal($order);
    }

    private function redirectToPayPal(Order $order)
    {
        // TODO: Intégrer vraiment PayPal API
        // Pour l'instant, simulation d'un paiement réussi
        
        // Simuler un délai de paiement
        sleep(1);
        
        // Marquer comme payé
        $order->update([
            'status' => 'paid',
            'payment_id' => 'PAYPAL-' . strtoupper(Str::random(15)),
        ]);
        
        // Générer le code IPTV
        $order->generateIptvCode();
        $order->setExpirationDate();
        
        return redirect()->route('payment.success', ['order' => $order->id]);
    }

    public function paymentSuccess(Request $request)
    {
        $orderId = $request->get('order');
        $order = Order::findOrFail($orderId);

        if (!$order->isPaid()) {
            return redirect()->route('subscriptions.index')
                ->with('error', 'Le paiement n\'a pas été confirmé.');
        }

        // TODO: Envoyer l'email de confirmation avec les identifiants IPTV

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