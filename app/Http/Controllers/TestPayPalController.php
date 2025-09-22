<?php

namespace App\Http\Controllers;

use App\Services\PayPalService;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TestPayPalController extends Controller
{
    public function testConnection()
    {
        try {
            $paypalService = new PayPalService();
            
            // Test de configuration
            $config = [
                'client_id' => Setting::get('paypal_client_id') ?: env('PAYPAL_CLIENT_ID'),
                'client_secret' => Setting::get('paypal_client_secret') ?: env('PAYPAL_CLIENT_SECRET'),
                'sandbox' => Setting::get('paypal_sandbox', true) || env('PAYPAL_SANDBOX', true),
                'base_url' => (Setting::get('paypal_sandbox', true) || env('PAYPAL_SANDBOX', true))
                    ? 'https://api.sandbox.paypal.com' 
                    : 'https://api.paypal.com'
            ];
            
            Log::info('PayPal Configuration Test', $config);
            
            // Test de création de paiement
            $result = $paypalService->createPayment(
                10.00,
                'EUR',
                'Test Payment',
                'http://localhost:8000/test-success',
                'http://localhost:8000/test-cancel'
            );
            
            return response()->json([
                'success' => true,
                'config' => $config,
                'result' => $result
            ]);
            
        } catch (\Exception $e) {
            Log::error('PayPal Test Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'config' => [
                    'client_id' => Setting::get('paypal_client_id') ?: env('PAYPAL_CLIENT_ID'),
                    'client_secret' => Setting::get('paypal_client_secret') ?: env('PAYPAL_CLIENT_SECRET'),
                    'sandbox' => Setting::get('paypal_sandbox', true) || env('PAYPAL_SANDBOX', true),
                ]
            ]);
        }
    }

    public function testPage()
    {
        return view('test-paypal');
    }
}