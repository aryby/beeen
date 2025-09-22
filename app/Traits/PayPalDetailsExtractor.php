<?php

namespace App\Traits;

trait PayPalDetailsExtractor
{
    /**
     * Extraire les détails PayPal importants pour la sauvegarde
     */
    protected function extractPayPalDetails($paypalData)
    {
        $details = [
            'status' => $paypalData['status'] ?? null,
            'id' => $paypalData['id'] ?? null,
            'create_time' => $paypalData['create_time'] ?? null,
            'update_time' => $paypalData['update_time'] ?? null,
            'intent' => $paypalData['intent'] ?? null,
        ];

        // Détails du payeur
        if (isset($paypalData['payer'])) {
            $details['payer'] = [
                'payer_id' => $paypalData['payer']['payer_id'] ?? null,
                'email_address' => $paypalData['payer']['email_address'] ?? null,
                'name' => [
                    'given_name' => $paypalData['payer']['name']['given_name'] ?? null,
                    'surname' => $paypalData['payer']['name']['surname'] ?? null,
                ],
                'address' => $paypalData['payer']['address'] ?? null,
            ];
        }

        // Détails des unités d'achat
        if (isset($paypalData['purchase_units'])) {
            $details['purchase_units'] = [];
            foreach ($paypalData['purchase_units'] as $unit) {
                $unitDetails = [
                    'reference_id' => $unit['reference_id'] ?? null,
                    'amount' => $unit['amount'] ?? null,
                    'payee' => $unit['payee'] ?? null,
                ];

                // Détails des paiements (captures)
                if (isset($unit['payments']['captures'])) {
                    $unitDetails['captures'] = [];
                    foreach ($unit['payments']['captures'] as $capture) {
                        $unitDetails['captures'][] = [
                            'id' => $capture['id'] ?? null,
                            'status' => $capture['status'] ?? null,
                            'amount' => $capture['amount'] ?? null,
                            'create_time' => $capture['create_time'] ?? null,
                            'update_time' => $capture['update_time'] ?? null,
                        ];
                    }
                }

                $details['purchase_units'][] = $unitDetails;
            }
        }

        // Liens PayPal
        if (isset($paypalData['links'])) {
            $details['links'] = [];
            foreach ($paypalData['links'] as $link) {
                $details['links'][] = [
                    'href' => $link['href'] ?? null,
                    'rel' => $link['rel'] ?? null,
                    'method' => $link['method'] ?? null,
                ];
            }
        }

        return $details;
    }
}
