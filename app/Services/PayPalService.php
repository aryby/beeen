<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayPalService
{
    private $clientId;
    private $clientSecret;
    private $baseUrl;
    private $accessToken;

    public function __construct()
    {
        $this->clientId = Setting::get('paypal_client_id') ?: env('PAYPAL_CLIENT_ID');
        $this->clientSecret = Setting::get('paypal_client_secret') ?: env('PAYPAL_CLIENT_SECRET');
        $this->baseUrl = (Setting::get('paypal_sandbox', true) || env('PAYPAL_SANDBOX', true))
            ? 'https://api.sandbox.paypal.com' 
            : 'https://api.paypal.com';
    }

    /**
     * Obtenir un token d'accès PayPal
     */
    private function getAccessToken()
    {
        if ($this->accessToken) {
            return $this->accessToken;
        }

        try {
            // Debug: Log les paramètres
            Log::info('PayPal Token Request', [
                'base_url' => $this->baseUrl,
                'client_id' => substr($this->clientId, 0, 10) . '...',
                'has_secret' => !empty($this->clientSecret)
            ]);

            $response = Http::withBasicAuth($this->clientId, $this->clientSecret)
                ->withOptions([
                    'verify' => false, // Désactiver la vérification SSL pour sandbox
                    'timeout' => 30,
                    'curl' => [
                        CURLOPT_SSL_VERIFYPEER => false,
                        CURLOPT_SSL_VERIFYHOST => false,
                    ]
                ])
                ->asForm()
                ->post($this->baseUrl . '/v1/oauth2/token', [
                    'grant_type' => 'client_credentials'
                ]);

            Log::info('PayPal Token Response', [
                'status' => $response->status(),
                'successful' => $response->successful(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $this->accessToken = $data['access_token'];
                return $this->accessToken;
            }

            throw new \Exception('Erreur lors de l\'obtention du token PayPal: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('PayPal Token Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Créer un paiement PayPal
     */
    public function createPayment($amount, $currency, $description, $returnUrl, $cancelUrl)
    {
        try {
            $token = $this->getAccessToken();

            $response = Http::withToken($token)
                ->withOptions([
                    'verify' => false, // Désactiver SSL pour sandbox
                    'timeout' => 30,
                    'curl' => [
                        CURLOPT_SSL_VERIFYPEER => false,
                        CURLOPT_SSL_VERIFYHOST => false,
                    ]
                ])
                ->post($this->baseUrl . '/v2/checkout/orders', [
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

            if ($response->successful()) {
                $data = $response->json();
                
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
            }

            throw new \Exception('Erreur lors de la création du paiement: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('PayPal Create Payment Error: ' . $e->getMessage());
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

            $response = Http::withToken($token)
                ->withOptions([
                    'verify' => false,
                    'timeout' => 30,
                    'curl' => [
                        CURLOPT_SSL_VERIFYPEER => false,
                        CURLOPT_SSL_VERIFYHOST => false,
                    ]
                ])
                ->post($this->baseUrl . "/v2/checkout/orders/{$orderId}/capture");

            if ($response->successful()) {
                $data = $response->json();
                
                return [
                    'success' => true,
                    'status' => $data['status'],
                    'data' => $data
                ];
            }

            throw new \Exception('Erreur lors de la capture du paiement: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('PayPal Capture Payment Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Obtenir les détails d'un paiement
     */
    public function getPaymentDetails($orderId)
    {
        try {
            $token = $this->getAccessToken();

            $response = Http::withToken($token)
                ->get($this->baseUrl . "/v2/checkout/orders/{$orderId}");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            throw new \Exception('Erreur lors de la récupération des détails: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('PayPal Get Payment Details Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Rembourser un paiement
     */
    public function refundPayment($captureId, $amount = null, $currency = 'EUR')
    {
        try {
            $token = $this->getAccessToken();

            $data = [];
            if ($amount) {
                $data['amount'] = [
                    'currency_code' => $currency,
                    'value' => number_format($amount, 2, '.', '')
                ];
            }

            $response = Http::withToken($token)
                ->post($this->baseUrl . "/v2/payments/captures/{$captureId}/refund", $data);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            throw new \Exception('Erreur lors du remboursement: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('PayPal Refund Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Vérifier si PayPal est configuré
     */
    public function isConfigured()
    {
        return !empty($this->clientId) && !empty($this->clientSecret);
    }
}
