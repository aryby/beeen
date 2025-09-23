<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DynamicConfigService;
use App\Services\DynamicMailService;
use App\Services\TrackedMailService;
use App\Models\Setting;

class TestController extends Controller
{
    /**
     * Page de test des configurations
     */
    public function index()
    {
        // Configurer dynamiquement
        DynamicConfigService::configureAll();
        
        $smtpConfigured = DynamicMailService::isSmtpConfigured();
        $paypalConfigured = DynamicConfigService::isPayPalConfigured();
        
        $settings = [
            'smtp_host' => Setting::get('smtp_host'),
            'smtp_port' => Setting::get('smtp_port'),
            'smtp_username' => Setting::get('smtp_username'),
            'smtp_password' => Setting::get('smtp_password') ? '***configured***' : null,
            'smtp_encryption' => Setting::get('smtp_encryption'),
            'paypal_client_id' => Setting::get('paypal_client_id') ? '***configured***' : null,
            'paypal_client_secret' => Setting::get('paypal_client_secret') ? '***configured***' : null,
            'paypal_sandbox' => Setting::get('paypal_sandbox'),
        ];
        $defaultTestEmail = auth()->check() ? auth()->user()->email : (Setting::get('smtp_username') ?: '');
        
        return view('test.index', compact('smtpConfigured', 'paypalConfigured', 'settings', 'defaultTestEmail'));
    }
    
    /**
     * Test d'envoi d'email
     */
    public function testEmail(Request $request)
    {
        try {
            $email = $request->input('email');
            if (empty($email)) {
                $email = auth()->check() ? auth()->user()->email : (Setting::get('smtp_username') ?: null);
            }
            if (empty($email)) {
                return response()->json([
                    'success' => false,
                    'message' => "Veuillez renseigner une adresse email de test"
                ], 422);
            }
            
            // Corps HTML de test (pour activer le tracking)
            $html = '<!DOCTYPE html><html><body><h1>Test Email</h1><p>Ceci est un test SMTP + tracking.</p><p><a href="https://iptv2smartv.com">Visiter le site</a></p></body></html>';
            
            // Envoyer via le service suivi
            $mailSent = TrackedMailService::sendTracked($email, 'Test Email - IPTV Pro', $html, auth()->id());
            
            if ($mailSent) {
                return response()->json([
                    'success' => true,
                    'message' => 'Email de test envoyé avec succès à ' . $email
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Échec de l\'envoi de l\'email. Vérifiez la configuration SMTP.'
                ]);
            }
            
        } catch (\Exception $e) {
            \Log::error('Test email error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi de l\'email: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Test PayPal
     */
    public function testPayPal()
    {
        try {
            $paypalService = new \App\Services\PayPalService();
            
            if (!$paypalService->isConfigured()) {
                return response()->json([
                    'success' => false,
                    'message' => 'PayPal non configuré. Veuillez configurer les paramètres PayPal dans l\'administration.'
                ]);
            }
            
            // Test de connexion avec l'API PayPal (via isConfigured qui teste la connexion)
            if (!$paypalService->isConfigured()) {
                return response()->json([
                    'success' => false,
                    'message' => 'PayPal non configuré ou erreur de connexion'
                ]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'PayPal est correctement configuré et connecté'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Test PayPal error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du test PayPal: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Test de capture PayPal avec un order ID de test
     */
    public function testPayPalCapture(Request $request)
    {
        try {
            $orderId = $request->input('order_id', 'TEST_ORDER_ID');
            
            $paypalService = new \App\Services\PayPalService();
            
            if (!$paypalService->isConfigured()) {
                return response()->json([
                    'success' => false,
                    'message' => 'PayPal non configuré'
                ]);
            }
            
            // Test de capture (cela va probablement échouer avec un faux ID, mais on peut voir l'erreur)
            $result = $paypalService->capturePayment($orderId);
            
            return response()->json([
                'success' => $result['success'],
                'message' => $result['success'] ? 'Capture réussie' : 'Capture échouée: ' . ($result['error'] ?? 'Erreur inconnue'),
                'details' => $result
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Test PayPal capture error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du test de capture: ' . $e->getMessage()
            ]);
        }
    }
}