<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PayPalService;
use App\Models\Setting;

class TestPayPalController extends Controller
{
    public function testConnection()
    {
        try {
            $paypalService = new PayPalService();
            
            // Vérifier la configuration
            $config = [
                'client_id' => Setting::get('paypal_client_id'),
                'client_secret' => Setting::get('paypal_client_secret'),
                'sandbox' => Setting::get('paypal_sandbox'),
                'configured' => $paypalService->isConfigured(),
            ];
            
            if (!$paypalService->isConfigured()) {
                return response()->json([
                    'success' => false,
                    'message' => 'PayPal non configuré',
                    'config' => $config
                ]);
            }
            
            // Test de création d'un paiement simple
            $result = $paypalService->createPayment(
                10.00,
                'EUR',
                'Test de connexion PayPal',
                route('home'),
                route('home')
            );
            
            return response()->json([
                'success' => $result['success'],
                'message' => $result['success'] ? 'Connexion PayPal réussie !' : $result['error'],
                'config' => $config,
                'result' => $result
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage(),
                'config' => $config ?? []
            ]);
        }
    }
}