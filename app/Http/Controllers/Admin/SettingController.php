<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->keyBy('key');
        $defaults = Setting::getDefaults();

        // Organiser les paramètres par catégorie
        $categories = [
            'smtp' => [
                'title' => 'Configuration SMTP',
                'icon' => 'envelope',
                'settings' => ['smtp_host', 'smtp_port', 'smtp_username', 'smtp_password', 'smtp_encryption']
            ],
            'paypal' => [
                'title' => 'Configuration PayPal',
                'icon' => 'credit-card',
                'settings' => ['paypal_client_id', 'paypal_client_secret', 'paypal_sandbox']
            ],
            'site' => [
                'title' => 'Configuration du Site',
                'icon' => 'globe',
                'settings' => ['site_name', 'subscription_description', 'contact_email']
            ],
            'recaptcha' => [
                'title' => 'reCAPTCHA',
                'icon' => 'shield-check',
                'settings' => ['recaptcha_site_key', 'recaptcha_secret_key']
            ],
            'legal' => [
                'title' => 'Pages Légales',
                'icon' => 'file-text',
                'settings' => ['terms_of_service', 'privacy_policy', 'legal_mentions']
            ]
        ];

        return view('admin.settings.index', compact('settings', 'defaults', 'categories'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.*' => 'nullable|string|max:5000',
        ]);

        foreach ($validated['settings'] as $key => $value) {
            $defaults = Setting::getDefaults();
            $type = $defaults[$key]['type'] ?? 'text';
            
            Setting::set($key, $value, $type, $defaults[$key]['description'] ?? '');
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Paramètres mis à jour avec succès.');
    }
}