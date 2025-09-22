<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Log;

class PayPalServiceAlternative
{
    private $clientId;
    private $clientSecret;
    private $baseUrl;
    private $accessToken;

    public function __construct()
    {
        $this->clientId = Setting::get('paypal_client_id');
        $this->clientSecret = Setting::get('paypal_client_secret');
        $this->baseUrl = Setting::get('paypal_sandbox', true) 
            ? 'https://api.sandbox.paypal.com' 
            : 'https://api.paypal.com';
    }

    /**
     * Obtenir un token avec cURL natif
     */
    private function getAccessToken()
    {
        if ($this->accessToken) {
            return $this->accessToken;
        }

        $ch = curl_init();
        
        curl_setopt_array($ch, [
            CURLOPT_URL => $this->baseUrl . '/v1/oauth2/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => 'grant_type=client_credentials',
            CURLOPT_USERPWD => $this->clientId . ':' . $this->clientSecret,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Accept-Language: en_US',
                'Content-Type: application/x-www-form-urlencoded'
            ],
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_TIMEOUT => 30,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new \Exception('cURL Error: ' . $error);
        }

        if ($httpCode !== 200) {
            throw new \Exception('HTTP Error: ' . $httpCode . ' - ' . $response);
        }

        $data = json_decode($response, true);
        if (!$data || !isset($data['access_token'])) {
            throw new \Exception('Invalid token response: ' . $response);
        }

        $this->accessToken = $data['access_token'];
        return $this->accessToken;
    }

    /**
     * Créer un paiement avec cURL natif
     */
    public function createPayment($amount, $currency, $description, $returnUrl, $cancelUrl)
    {
        try {
            $token = $this->getAccessToken();

            $payload = json_encode([
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'amount' => [
                            'currency_code' => $currency,
                            'value' => number_format($amount, 2, '.', '')
                        ],
                        'description' => $description
                    ]
                ],
                'application_context' => [
                    'return_url' => $returnUrl,
                    'cancel_url' => $cancelUrl,
                    'brand_name' => config('app.name', 'IPTV Pro'),
                    'landing_page' => 'BILLING',
                    'user_action' => 'PAY_NOW'
                ]
            ]);

            $ch = curl_init();
            
            curl_setopt_array($ch, [
                CURLOPT_URL => $this->baseUrl . '/v2/checkout/orders',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $payload,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $token,
                    'Accept: application/json',
                    'Prefer: return=representation'
                ],
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_TIMEOUT => 30,
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            if ($error) {
                throw new \Exception('cURL Error: ' . $error);
            }

            if ($httpCode !== 201) {
                throw new \Exception('HTTP Error: ' . $httpCode . ' - ' . $response);
            }

            $data = json_decode($response, true);
            
            // Trouver le lien d'approbation
            $approvalUrl = null;
            foreach ($data['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    $approvalUrl = $link['href'];
                    break;
                }
            }

            return [
                'success' => true,
                'order_id' => $data['id'],
                'approval_url' => $approvalUrl,
                'data' => $data
            ];

        } catch (\Exception $e) {
            Log::error('PayPal Alternative Create Payment Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Capturer un paiement PayPal
     */
    public function capturePayment($orderId)
    {
        try {
            $token = $this->getAccessToken();

            // PayPal v2 API - Utiliser directement la méthode avec body JSON vide
            $ch = curl_init();
            
            curl_setopt_array($ch, [
                CURLOPT_URL => $this->baseUrl . "/v2/checkout/orders/{$orderId}/capture",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => '{}', // Body JSON vide obligatoire
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'Prefer: return=representation',
                    'Authorization: Bearer ' . $token,
                    'PayPal-Request-Id: ' . uniqid()
                ],
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_TIMEOUT => 30,
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            if ($error) {
                throw new \Exception('cURL Error: ' . $error);
            }

            if ($httpCode !== 200 && $httpCode !== 201) {
                throw new \Exception('HTTP Error: ' . $httpCode . ' - ' . $response);
            }

            $data = json_decode($response, true);
            
            if (!$data) {
                throw new \Exception('Invalid JSON response from PayPal: ' . $response);
            }
            
            return [
                'success' => true,
                'status' => $data['status'],
                'data' => $data
            ];

        } catch (\Exception $e) {
            Log::error('PayPal Alternative Capture Payment Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public function isConfigured()
    {
        return !empty($this->clientId) && !empty($this->clientSecret);
    }
}
