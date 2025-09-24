<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\Testimonial;
use App\Models\Setting;

class HomeController extends Controller
{
    public function index()
    {
        // Récupérer les abonnements actifs
        $subscriptions = Subscription::active()
            ->orderByDuration()
            ->get();

        // Récupérer les témoignages publiés
        $testimonials = Testimonial::published()
            ->ordered()
            ->limit(6)
            ->get();

        // Récupérer la description commune des abonnements
        $subscriptionDescription = Setting::get('subscription_description', 
            'Accédez à plus de 12000 chaînes HD, VOD illimité, sans publicité avec support 24/7.'
        );

        return view('home', compact('subscriptions', 'testimonials', 'subscriptionDescription'));
    }

    public function legal($page)
    {
        $validPages = ['terms', 'privacy', 'mentions'];
        
        if (!in_array($page, $validPages)) {
            abort(404);
        }

        $settingKey = match($page) {
            'terms' => 'terms_of_service',
            'privacy' => 'privacy_policy',
            'mentions' => 'legal_mentions',
        };

        $title = match($page) {
            'terms' => 'Conditions Générales de Vente',
            'privacy' => 'Politique de Confidentialité',
            'mentions' => 'Mentions Légales',
        };

        $content = Setting::get($settingKey, 'Contenu à configurer dans l\'administration.');

        return view('legal', compact('title', 'content'));
    }
}