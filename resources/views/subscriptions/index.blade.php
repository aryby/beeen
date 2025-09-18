@extends('layouts.soft-ui')

@section('title', 'Nos Abonnements IPTV')

@section('content')
<!-- Hero Section -->
<section class="iptv-hero-bg text-white py-5 position-relative overflow-hidden">
    <div class="hero-content">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold mb-3">Choisissez votre abonnement IPTV</h1>
                    <p class="lead mb-4 opacity-75">{{ $subscriptionDescription }}</p>
                    <div class="row">
                        <div class="col-6 col-md-3 mb-3">
                            <div class="text-center">
                                <div class="feature-icon success mb-2 mx-auto">
                                    <i class="bi bi-tv fs-3"></i>
                                </div>
                                <div class="fw-bold">1000+ Chaînes</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <div class="text-center">
                                <div class="feature-icon info mb-2 mx-auto">
                                    <i class="bi bi-film fs-3"></i>
                                </div>
                                <div class="fw-bold">VOD Illimité</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <div class="text-center">
                                <div class="feature-icon warning mb-2 mx-auto">
                                    <i class="bi bi-hd-btn fs-3"></i>
                                </div>
                                <div class="fw-bold">Qualité HD/4K</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <div class="text-center">
                                <div class="feature-icon mb-2 mx-auto">
                                    <i class="bi bi-headset fs-3"></i>
                                </div>
                                <div class="fw-bold">Support 24/7</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 text-center">
                    <div class="position-relative">
                        <!-- IPTV Devices Illustration -->
                        <svg width="300" height="200" viewBox="0 0 300 200" class="img-fluid tv-glow">
                            <defs>
                                <linearGradient id="deviceGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" style="stop-color:#17c1e8"/>
                                    <stop offset="100%" style="stop-color:#21d4fd"/>
                                </linearGradient>
                            </defs>
                            <!-- TV -->
                            <rect x="50" y="50" width="80" height="60" rx="8" fill="url(#deviceGradient)" opacity="0.9"/>
                            <rect x="55" y="55" width="70" height="45" rx="4" fill="rgba(255,255,255,0.3)"/>
                            <text x="90" y="82" text-anchor="middle" fill="white" font-size="8" font-weight="bold">IPTV</text>
                            
                            <!-- Phone -->
                            <rect x="170" y="40" width="30" height="50" rx="6" fill="url(#deviceGradient)" opacity="0.8"/>
                            <rect x="175" y="45" width="20" height="35" rx="2" fill="rgba(255,255,255,0.3)"/>
                            
                            <!-- Tablet -->
                            <rect x="120" y="120" width="60" height="40" rx="6" fill="url(#deviceGradient)" opacity="0.7"/>
                            <rect x="125" y="125" width="50" height="30" rx="3" fill="rgba(255,255,255,0.3)"/>
                            
                            <!-- Connecting lines -->
                            <line x1="90" y1="110" x2="150" y2="140" stroke="rgba(255,255,255,0.5)" stroke-width="2" stroke-dasharray="5,5">
                                <animate attributeName="stroke-dashoffset" values="0;10" dur="2s" repeatCount="indefinite"/>
                            </line>
                            <line x1="130" y1="80" x2="170" y2="65" stroke="rgba(255,255,255,0.5)" stroke-width="2" stroke-dasharray="5,5">
                                <animate attributeName="stroke-dashoffset" values="0;10" dur="2s" repeatCount="indefinite"/>
                            </line>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Subscriptions -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3" style="color: var(--soft-dark);">Nos Formules</h2>
            <p class="lead text-muted">Profitez de tarifs dégressifs selon la durée de votre engagement</p>
        </div>

        @if($subscriptions->count() > 0)
            <div class="row justify-content-center">
                @foreach($subscriptions as $index => $subscription)
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="pricing-card {{ $index === 1 ? 'featured' : '' }} h-100">
                            <div class="card-body text-center p-4">
                                <h3 class="fw-bold mb-3" style="color: var(--soft-dark);">{{ $subscription->name }}</h3>
                                
                                <div class="mb-4">
                                    <span class="display-4 fw-bold" style="background: var(--gradient-primary); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                                        {{ number_format($subscription->price, 0) }}€
                                    </span>
                                    <div class="text-muted">{{ $subscription->duration_text }}</div>
                                </div>
                                
                                @if($subscription->duration_months > 1)
                                    <div class="alert alert-soft alert-success small mb-4">
                                        <i class="bi bi-calculator me-1"></i>
                                        Soit {{ number_format($subscription->price / $subscription->duration_months, 2) }}€/mois
                                    </div>
                                @endif
                                
                                <ul class="list-unstyled mb-4">
                                    <li class="mb-2 d-flex align-items-center">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        <span><strong>1000+</strong> Chaînes HD/4K</span>
                                    </li>
                                    <li class="mb-2 d-flex align-items-center">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        <span><strong>VOD</strong> Illimité</span>
                                    </li>
                                    <li class="mb-2 d-flex align-items-center">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        <span><strong>Sans</strong> Publicité</span>
                                    </li>
                                    <li class="mb-2 d-flex align-items-center">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        <span><strong>Support</strong> 24/7</span>
                                    </li>
                                    <li class="mb-2 d-flex align-items-center">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        <span><strong>Multi</strong>-appareils</span>
                                    </li>
                                    <li class="mb-2 d-flex align-items-center">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        <span><strong>Activation</strong> immédiate</span>
                                    </li>
                                </ul>
                                
                                @auth
                                    <a href="{{ route('subscriptions.checkout', $subscription) }}" 
                                       class="btn btn-soft btn-soft-primary w-100 mb-3">
                                        <i class="bi bi-cart-plus me-2"></i>Commander maintenant
                                    </a>
                                @else
                                    <button type="button" 
                                            class="btn btn-soft btn-soft-primary w-100 mb-3"
                                            onclick="openQuickOrderModal('subscription', {{ $subscription->id }}, '{{ $subscription->name }}', '{{ $subscription->formatted_price }}', 'Abonnement IPTV {{ $subscription->duration_text }}', '{{ $subscription->duration_text }}')">
                                        <i class="bi bi-lightning-charge me-2"></i>Commander Rapidement
                                    </button>
                                @endauth
                                
                                <small class="text-muted d-flex align-items-center justify-content-center">
                                    <i class="bi bi-shield-check me-1 text-success"></i>Paiement sécurisé
                                </small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <div class="feature-icon warning mx-auto mb-4" style="width: 5rem; height: 5rem;">
                    <i class="bi bi-exclamation-triangle fs-1"></i>
                </div>
                <h3 class="fw-bold mb-3">Aucun abonnement disponible</h3>
                <p class="text-muted">Les abonnements seront bientôt disponibles. Revenez plus tard.</p>
                <a href="{{ route('home') }}" class="btn btn-soft btn-soft-primary">Retour à l'accueil</a>
            </div>
        @endif
    </div>
</section>

<!-- Features Section -->
<section class="py-5" style="background: linear-gradient(310deg, #f8f9fa 0%, #fff 100%);">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-6 fw-bold mb-3" style="color: var(--soft-dark);">Pourquoi nous choisir ?</h2>
        </div>
        
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card-soft p-4 h-100">
                    <div class="d-flex align-items-start">
                        <div class="feature-icon me-3">
                            <i class="bi bi-lightning-charge"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-2">Activation Immédiate</h5>
                            <p class="text-muted mb-0">Recevez vos identifiants par email dans les minutes qui suivent votre paiement.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card-soft p-4 h-100">
                    <div class="d-flex align-items-start">
                        <div class="feature-icon success me-3">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-2">100% Légal</h5>
                            <p class="text-muted mb-0">Service entièrement conforme aux réglementations en vigueur.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card-soft p-4 h-100">
                    <div class="d-flex align-items-start">
                        <div class="feature-icon info me-3">
                            <i class="bi bi-arrow-repeat"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-2">Garantie Satisfait</h5>
                            <p class="text-muted mb-0">Satisfait ou remboursé sous 7 jours sans condition.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-6 fw-bold mb-3" style="color: var(--soft-dark);">Questions Fréquentes</h2>
            <p class="lead text-muted">Tout ce que vous devez savoir avant de commander</p>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item border-0 mb-3" style="border-radius: var(--border-radius-soft-lg); overflow: hidden; box-shadow: var(--shadow-soft);">
                        <h3 class="accordion-header">
                            <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" style="border-radius: var(--border-radius-soft-lg);">
                                <i class="bi bi-lightning-charge text-primary me-2"></i>
                                Comment fonctionne l'activation ?
                            </button>
                        </h3>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Après votre paiement, vous recevez immédiatement un email avec vos identifiants IPTV et un guide d'installation détaillé pour votre appareil.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item border-0 mb-3" style="border-radius: var(--border-radius-soft-lg); overflow: hidden; box-shadow: var(--shadow-soft);">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq2" style="border-radius: var(--border-radius-soft-lg);">
                                <i class="bi bi-display text-success me-2"></i>
                                Sur combien d'appareils puis-je utiliser mon abonnement ?
                            </button>
                        </h3>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Votre abonnement peut être utilisé sur jusqu'à 3 appareils simultanément. Vous pouvez l'installer sur autant d'appareils que vous le souhaitez.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item border-0 mb-3" style="border-radius: var(--border-radius-soft-lg); overflow: hidden; box-shadow: var(--shadow-soft);">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq3" style="border-radius: var(--border-radius-soft-lg);">
                                <i class="bi bi-arrow-repeat text-info me-2"></i>
                                Que se passe-t-il si je ne suis pas satisfait ?
                            </button>
                        </h3>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Nous offrons une garantie satisfait ou remboursé de 7 jours. Si vous n'êtes pas entièrement satisfait, contactez-nous pour un remboursement complet.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item border-0 mb-3" style="border-radius: var(--border-radius-soft-lg); overflow: hidden; box-shadow: var(--shadow-soft);">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq4" style="border-radius: var(--border-radius-soft-lg);">
                                <i class="bi bi-shield-check text-warning me-2"></i>
                                Le service est-il vraiment légal ?
                            </button>
                        </h3>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Oui, notre service est 100% légal. Nous respectons toutes les réglementations en vigueur et disposons des licences nécessaires pour diffuser le contenu proposé.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 position-relative overflow-hidden">
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: var(--gradient-primary);"></div>
    <div class="position-relative">
        <div class="container text-center text-white">
            <h2 class="display-6 fw-bold mb-4">Prêt à profiter de votre service IPTV ?</h2>
            <p class="lead mb-4 opacity-75">
                Rejoignez notre réseau de clients satisfaits et développez votre activité dès aujourd'hui.
            </p>
            <a href="{{ route('contact.index') }}" class="btn btn-soft" style="background: white; color: var(--soft-primary);">
                <i class="bi bi-question-circle me-2"></i>Une question ? Contactez-nous
            </a>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .accordion-button:not(.collapsed) {
        background: var(--gradient-primary);
        color: white;
    }
    
    .accordion-button:focus {
        box-shadow: 0 0 0 0.25rem rgba(203, 12, 159, 0.25);
    }
</style>
@endpush