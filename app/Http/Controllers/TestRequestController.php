<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TestRequest;
use App\Models\Subscription;
use App\Models\Order;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestRequestConfirmation;

class TestRequestController extends Controller
{
    /**
     * Store a new test request and redirect to PayPal checkout
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'device_type' => 'required|in:smart_tv,android,apple,kodi,mag,pc,other',
            'mac_address' => 'nullable|string|max:17',
            'notes' => 'nullable|string|max:1000',
        ], [
            'name.required' => 'Le nom est obligatoire.',
            'name.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'Veuillez saisir une adresse email valide.',
            'device_type.required' => 'Le type d\'appareil est obligatoire.',
            'device_type.in' => 'Le type d\'appareil sélectionné n\'est pas valide.',
            'mac_address.max' => 'L\'adresse MAC ne peut pas dépasser 17 caractères.',
            'notes.max' => 'Les notes ne peuvent pas dépasser 1000 caractères.',
        ]);

        // Vérification spéciale pour MAC address si MAG est sélectionné
        if ($request->device_type === 'mag') {
            $validator->after(function ($validator) use ($request) {
                if (empty($request->mac_address)) {
                    $validator->errors()->add('mac_address', 'L\'adresse MAC est obligatoire pour les appareils MAG.');
                }
            });
        }

        // Vérifier si une commande de test récente existe déjà pour cet email
        $recentOrder = Order::where('customer_email', $request->email)
            ->whereHas('subscription', function($query) {
                $query->where('duration_months', 0); // Test 48h
            })
            ->where('created_at', '>', now()->subDays(7))
            ->whereIn('status', ['pending', 'validated'])
            ->first();

        if ($recentOrder) {
            return response()->json([
                'success' => false,
                'message' => 'Une commande de test est déjà en cours pour cette adresse email. Veuillez patienter ou contacter le support.',
                'errors' => ['email' => ['Une commande de test récente existe déjà pour cette adresse email.']]
            ], 422);
        }

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Veuillez corriger les erreurs dans le formulaire.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Récupérer l'abonnement de test 48h
            $testSubscription = Subscription::where('duration_months', 0)->first();
            
            if (!$testSubscription) {
                return response()->json([
                    'success' => false,
                    'message' => 'Service de test temporairement indisponible.',
                ], 500);
            }

            // Stocker les données du formulaire dans la session pour le checkout
            session([
                'test_form_data' => [
                    'name' => $request->name,
                    'email' => $request->email,
                    'device_type' => $request->device_type,
                    'mac_address' => $request->mac_address,
                    'notes' => $request->notes,
                ]
            ]);

            // Rediriger vers le checkout
            return response()->json([
                'success' => true,
                'redirect' => route('subscriptions.checkout', $testSubscription),
                'message' => 'Redirection vers le paiement...',
                'data' => [
                    'subscription_name' => $testSubscription->name
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur création commande test: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la création de votre commande. Veuillez réessayer ou contacter le support.',
            ], 500);
        }
    }

    /**
     * Get device types for frontend
     */
    public function getDeviceTypes()
    {
        return response()->json([
            'device_types' => [
                ['value' => 'smart_tv', 'label' => 'Smart TV', 'description' => 'Samsung, LG, Sony, etc.'],
                ['value' => 'android', 'label' => 'Android', 'description' => 'Smartphone, tablette, box Android TV'],
                ['value' => 'apple', 'label' => 'Apple TV / iOS', 'description' => 'Apple TV, iPhone, iPad'],
                ['value' => 'kodi', 'label' => 'Kodi', 'description' => 'Application Kodi sur tous appareils'],
                ['value' => 'mag', 'label' => 'MAG Box', 'description' => 'Appareil MAG (adresse MAC requise)'],
                ['value' => 'pc', 'label' => 'PC / Windows', 'description' => 'Ordinateur Windows, Mac, Linux'],
                ['value' => 'other', 'label' => 'Autre', 'description' => 'Autre type d\'appareil'],
            ]
        ]);
    }

    /**
     * Check if MAC address is required for device type
     */
    public function checkMacRequirement(Request $request)
    {
        $deviceType = $request->input('device_type');
        
        return response()->json([
            'requires_mac' => TestRequest::requiresMacAddress($deviceType),
            'device_label' => $this->getDeviceLabel($deviceType)
        ]);
    }

    private function getDeviceLabel($deviceType)
    {
        $labels = [
            'smart_tv' => 'Smart TV',
            'android' => 'Android',
            'apple' => 'Apple TV / iOS',
            'kodi' => 'Kodi',
            'mag' => 'MAG Box',
            'pc' => 'PC / Windows',
            'other' => 'Autre',
        ];

        return $labels[$deviceType] ?? $deviceType;
    }
}