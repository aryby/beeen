<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DynamicConfigService;
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
        
        $smtpConfigured = DynamicConfigService::isSmtpConfigured();
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
        
        return view('test.index', compact('smtpConfigured', 'paypalConfigured', 'settings'));
    }
    
    /**
     * Test d'envoi d'email
     */
    public function testEmail(Request $request)
    {
        try {
            $email = $request->input('email', 'test@example.com');
            
            // Configurer SMTP dynamiquement
            if (!DynamicConfigService::configureSmtp()) {
                return response()->json([
                    'success' => false,
                    'message' => 'SMTP non configuré. Veuillez configurer les paramètres SMTP dans l\'administration.'
                ]);
            }
            
            // Envoyer un email de test
            \Mail::raw('Ceci est un email de test depuis IPTV Pro.', function ($message) use ($email) {
                $message->to($email)
                        ->subject('Test Email - IPTV Pro');
            });
            
            return response()->json([
                'success' => true,
                'message' => 'Email de test envoyé avec succès à ' . $email
            ]);
            
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
            
            return response()->json([
                'success' => true,
                'message' => 'PayPal est correctement configuré'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Test PayPal error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du test PayPal: ' . $e->getMessage()
            ]);
        }
    }
}