<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ResellerPack;
use App\Models\Reseller;
use App\Models\ResellerTransaction;
use App\Models\Setting;
use App\Traits\PayPalDetailsExtractor;
use Illuminate\Support\Str;

class ResellerController extends Controller
{
    use PayPalDetailsExtractor;
    /**
     * Page publique des packs revendeur
     */
    public function index()
    {
        $packs = ResellerPack::active()
            ->orderByCredits()
            ->get();

        return view('resellers.index', compact('packs'));
    }

    /**
     * Page de checkout pour un pack revendeur
     */
    public function checkout(ResellerPack $pack)
    {
        if (!$pack->is_active) {
            return redirect()->route('resellers.index')
                ->with('error', 'Ce pack n\'est plus disponible.');
        }

        // Rediriger les visiteurs non connectés vers la page de connexion
        if (!auth()->check()) {
            session(['intended_checkout' => route('resellers.checkout', $pack)]);
            return redirect()->route('login')
                ->with('info', 'Veuillez vous connecter pour commander un pack revendeur.');
        }

        return view('resellers.checkout', compact('pack'));
    }

    /**
     * Traitement du checkout revendeur
     */
    public function processCheckout(Request $request, ResellerPack $pack)
    {
        try {
            \Log::info('Reseller checkout started', [
                'pack_id' => $pack->id,
                'pack_name' => $pack->name,
                'user_id' => auth()->id()
            ]);

            $validated = $request->validate([
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'required|email|max:255',
                'customer_address' => 'nullable|string|max:1000',
                'terms_accepted' => 'required|accepted',
                'g-recaptcha-response' => 'required',
            ]);

            \Log::info('Form validation passed');

            // Vérifier reCAPTCHA
            $recaptchaSecret = Setting::get('recaptcha_secret_key');
            if ($recaptchaSecret) {
                $recaptchaResponse = $request->input('g-recaptcha-response');
                $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptchaSecret}&response={$recaptchaResponse}");
                $responseKeys = json_decode($response, true);
                
                if (!$responseKeys["success"]) {
                    \Log::warning('reCAPTCHA validation failed');
                    return back()->withErrors(['recaptcha' => 'Veuillez vérifier le reCAPTCHA.'])->withInput();
                }
            }

            \Log::info('reCAPTCHA validation passed');

            // Créer ou récupérer le compte revendeur
            $user = auth()->user();
            if (!$user) {
                \Log::warning('User not authenticated');
                return redirect()->route('register')
                    ->with('info', 'Veuillez créer un compte pour devenir revendeur.');
            }

            \Log::info('User authenticated', ['user_id' => $user->id]);

            // Créer une commande pour le pack revendeur
            $order = \App\Models\Order::create([
                'user_id' => $user->id,
                'item_type' => 'reseller_pack',
                'item_id' => $pack->id,
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_address' => $validated['customer_address'],
                'amount' => $pack->price,
                'currency' => 'EUR',
                'payment_method' => 'paypal',
                'status' => 'pending',
                'is_guest_order' => false,
            ]);

            \Log::info('Order created', ['order_id' => $order->id, 'order_number' => $order->order_number]);

            // Intégration PayPal
            $paypalService = new \App\Services\PayPalService();
            
            \Log::info('PayPal service initialized', [
                'is_configured' => $paypalService->isConfigured(),
                'client_id' => substr(Setting::get('paypal_client_id') ?: env('PAYPAL_CLIENT_ID'), 0, 10) . '...'
            ]);
            
            if (!$paypalService->isConfigured()) {
                \Log::error('PayPal not configured');
                return back()->with('error', 'Service de paiement non configuré. Veuillez contacter le support.');
            }

            $result = $paypalService->createPayment(
                $pack->price,
                'EUR',
                "Pack Revendeur {$pack->name} - {$pack->credits} crédits",
                route('resellers.payment-success', ['order' => $order->id]),
                route('resellers.checkout', $pack)
            );

            \Log::info('PayPal payment creation result', $result);

            if ($result['success']) {
                // Sauvegarder l'ID PayPal
                $order->update([
                    'payment_id' => $result['order_id'],
                    'status' => 'pending'
                ]);

                \Log::info('Order updated with PayPal ID', ['payment_id' => $result['order_id']]);

                return redirect($result['approval_url']);
            } else {
                \Log::error('PayPal payment creation failed', ['error' => $result['error']]);
                return back()->with('error', 'Erreur lors de la création du paiement PayPal: ' . $result['error']);
            }
        } catch (\Exception $e) {
            \Log::error('Reseller checkout error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'pack_id' => $pack->id ?? null,
                'user_id' => auth()->id()
            ]);
            
            return back()->with('error', 'Erreur lors du traitement de la commande: ' . $e->getMessage());
        }
    }

    /**
     * Succès du paiement revendeur
     */
    public function paymentSuccess(Request $request, \App\Models\Order $order)
    {
        $paypalToken = $request->get('token');
        $payerId = $request->get('PayerID');

        if (!$paypalToken) {
            return redirect()->route('resellers.index')
                ->with('error', 'Informations de paiement manquantes.');
        }

        // Capturer le paiement PayPal
        $paypalService = new \App\Services\PayPalService();
        $result = $paypalService->capturePayment($paypalToken);

        if ($result['success'] && $result['status'] === 'COMPLETED') {
            // Extraire et sauvegarder les détails PayPal importants
            $paypalDetails = $this->extractPayPalDetails($result['data']);
            
            // Marquer la commande comme payée
            $order->update([
                'status' => 'paid',
                'payment_details' => $paypalDetails,
            ]);

            // Traiter le pack revendeur
            $pack = ResellerPack::find($order->item_id);
            $user = $order->user;
            
            $reseller = Reseller::firstOrCreate(
                ['user_id' => $user->id],
                ['credits' => 0, 'total_credits_purchased' => 0, 'total_credits_used' => 0]
            );

            // Ajouter les crédits
            $reseller->addCredits(
                $pack->credits,
                "Achat du pack {$pack->name}",
                $pack->price
            );

            // Mettre à jour le rôle utilisateur
            if ($user->role !== 'reseller') {
                $user->update(['role' => 'reseller']);
            }

            return redirect()->route('reseller.dashboard')
                ->with('success', "Pack {$pack->name} acheté avec succès ! Vous avez maintenant {$reseller->credits} crédits.");
        } else {
            return redirect()->route('resellers.index')
                ->with('error', 'Le paiement n\'a pas été confirmé.');
        }
    }

    /**
     * Dashboard revendeur
     */
    public function dashboard()
    {
        $user = auth()->user();
        $reseller = $user->reseller;

        if (!$reseller) {
            return redirect()->route('resellers.index')
                ->with('error', 'Vous n\'êtes pas encore revendeur.');
        }

        // Statistiques
        $stats = [
            'total_credits' => $reseller->credits,
            'total_purchased' => $reseller->total_credits_purchased,
            'total_used' => $reseller->total_credits_used,
            'codes_generated' => $reseller->transactions()->where('type', 'generate_code')->count(),
        ];

        // Transactions récentes
        $recentTransactions = $reseller->transactions()
            ->latest()
            ->limit(10)
            ->get();

        // Packs disponibles pour renouvellement
        $availablePacks = ResellerPack::active()->get();

        return view('resellers.dashboard', compact('reseller', 'stats', 'recentTransactions', 'availablePacks'));
    }

    /**
     * Générer un code IPTV
     */
    public function generateCode(Request $request)
    {
        $validated = $request->validate([
            'subscription_months' => 'required|integer|min:1|max:12',
            'customer_info' => 'nullable|string|max:255',
        ]);

        $user = auth()->user();
        $reseller = $user->reseller;

        if (!$reseller || !$reseller->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Compte revendeur non actif.'
            ], 403);
        }

        try {
            $iptvCode = $reseller->generateIptvCode($validated['subscription_months']);
            
            return response()->json([
                'success' => true,
                'iptv_code' => $iptvCode,
                'credits_remaining' => $reseller->credits,
                'message' => 'Code IPTV généré avec succès !'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Historique des transactions
     */
    public function transactions()
    {
        $user = auth()->user();
        $reseller = $user->reseller;

        if (!$reseller) {
            return redirect()->route('resellers.index');
        }

        $transactions = $reseller->transactions()
            ->latest()
            ->paginate(20);

        return view('resellers.transactions', compact('reseller', 'transactions'));
    }

    /**
     * Renouveler un pack
     */
    public function renewPack(Request $request, ResellerPack $pack)
    {
        $user = auth()->user();
        $reseller = $user->reseller;

        if (!$reseller) {
            return redirect()->route('resellers.index')
                ->with('error', 'Compte revendeur introuvable.');
        }

        // TODO: Intégration PayPal pour renouvellement
        // Pour l'instant, simulation

        $reseller->addCredits(
            $pack->credits,
            "Renouvellement du pack {$pack->name}",
            $pack->price
        );

        return redirect()->route('reseller.dashboard')
            ->with('success', "Pack renouvelé ! +{$pack->credits} crédits ajoutés.");
    }

}