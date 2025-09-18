@extends('layouts.app')

@section('title', 'Nos Abonnements IPTV')

@section('content')
<!-- Hero Section -->
<section class="bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">Choisissez votre abonnement IPTV</h1>
                <p class="lead mb-4">{{ $subscriptionDescription }}</p>
                <div class="d-flex flex-wrap gap-4 text-center">
                    <div>
                        <i class="bi bi-tv fs-1 d-block mb-2"></i>
                        <small>1000+ Chaînes</small>
                    </div>
                    <div>
                        <i class="bi bi-film fs-1 d-block mb-2"></i>
                        <small>VOD Illimité</small>
                    </div>
                    <div>
                        <i class="bi bi-hd-btn fs-1 d-block mb-2"></i>
                        <small>Qualité HD/4K</small>
                    </div>
                    <div>
                        <i class="bi bi-headset fs-1 d-block mb-2"></i>
                        <small>Support 24/7</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-center">
                <img src="https://via.placeholder.com/300x200/ffffff/0d6efd?text=IPTV" 
                     alt="IPTV Service" 
                     class="img-fluid rounded shadow">
            </div>
        </div>
    </div>
</section>

<!-- Subscriptions -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3">Nos Formules</h2>
            <p class="lead text-muted">Profitez de tarifs dégressifs selon la durée de votre engagement</p>
        </div>

        @if($subscriptions->count() > 0)
            <div class="row justify-content-center">
                @foreach($subscriptions as $index => $subscription)
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card subscription-card h-100 {{ $index === 1 ? 'featured' : '' }}">
                            @if($index === 1)
                                <div class="position-absolute top-0 start-50 translate-middle">
                                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">
                                        <i class="bi bi-star-fill me-1"></i>Populaire
                                    </span>
                                </div>
                            @endif
                            
                            <div class="card-body text-center p-4">
                                <h3 class="card-title fw-bold mb-3 text-primary">{{ $subscription->name }}</h3>
                                
                                <div class="price-display mb-3">
                                    <span class="fs-1 fw-bold">{{ number_format($subscription->price, 0) }}€</span>
                                    <small class="text-muted d-block">{{ $subscription->duration_text }}</small>
                                </div>
                                
                                @if($subscription->duration_months > 1)
                                    <div class="alert alert-success small mb-3">
                                        <i class="bi bi-calculator me-1"></i>
                                        Soit {{ number_format($subscription->price / $subscription->duration_months, 2) }}€/mois
                                    </div>
                                @endif
                                
                                <ul class="list-unstyled mb-4">
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        <strong>1000+</strong> Chaînes HD/4K
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        <strong>VOD</strong> Illimité
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        <strong>Sans</strong> Publicité
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        <strong>Support</strong> 24/7
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        <strong>Multi</strong>-appareils
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        <strong>Activation</strong> immédiate
                                    </li>
                                </ul>
                                
                                <a href="{{ route('subscriptions.checkout', $subscription) }}" 
                                   class="btn btn-primary btn-lg w-100 mb-2">
                                    <i class="bi bi-cart-plus me-2"></i>Commander maintenant
                                </a>
                                
                                <small class="text-muted">
                                    <i class="bi bi-shield-check me-1"></i>Paiement sécurisé
                                </small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-exclamation-triangle fs-1 text-warning mb-3"></i>
                <h3>Aucun abonnement disponible</h3>
                <p class="text-muted">Les abonnements seront bientôt disponibles. Revenez plus tard.</p>
                <a href="{{ route('home') }}" class="btn btn-primary">Retour à l'accueil</a>
            </div>
        @endif
    </div>
</section>

<!-- Features Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-6 fw-bold mb-3">Pourquoi nous choisir ?</h2>
        </div>
        
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-lightning-charge fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="fw-bold">Activation Immédiate</h5>
                        <p class="text-muted mb-0">Recevez vos identifiants par email dans les minutes qui suivent votre paiement.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-shield-check fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="fw-bold">100% Légal</h5>
                        <p class="text-muted mb-0">Service entièrement conforme aux réglementations en vigueur.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-arrow-repeat fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="fw-bold">Garantie Satisfait</h5>
                        <p class="text-muted mb-0">Satisfait ou remboursé sous 7 jours sans condition.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-headset fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="fw-bold">Support Dédié</h5>
                        <p class="text-muted mb-0">Une équipe technique disponible 24h/24 pour vous accompagner.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-display fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="fw-bold">Multi-Plateforme</h5>
                        <p class="text-muted mb-0">Compatible avec tous vos appareils : TV, PC, smartphone, tablette.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-lock fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="fw-bold">Paiement Sécurisé</h5>
                        <p class="text-muted mb-0">Transactions protégées par PayPal et cryptage SSL 256 bits.</p>
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
            <h2 class="display-6 fw-bold mb-3">Questions Fréquentes</h2>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                Comment fonctionne l'activation ?
                            </button>
                        </h3>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Après votre paiement, vous recevez immédiatement un email avec vos identifiants IPTV et un guide d'installation détaillé pour votre appareil.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                Sur combien d'appareils puis-je utiliser mon abonnement ?
                            </button>
                        </h3>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Votre abonnement peut être utilisé sur jusqu'à 3 appareils simultanément. Vous pouvez l'installer sur autant d'appareils que vous le souhaitez.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                Que se passe-t-il si je ne suis pas satisfait ?
                            </button>
                        </h3>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Nous offrons une garantie satisfait ou remboursé de 7 jours. Si vous n'êtes pas entièrement satisfait, contactez-nous pour un remboursement complet.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
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
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="display-6 fw-bold mb-4">Prêt à profiter de votre service IPTV ?</h2>
        <p class="lead mb-4">Rejoignez des milliers de clients satisfaits dès maintenant</p>
        <a href="{{ route('contact.index') }}" class="btn btn-outline-light btn-lg">
            <i class="bi bi-question-circle me-2"></i>Une question ? Contactez-nous
        </a>
    </div>
</section>
@endsection

@push('styles')
<style>
    .subscription-card.featured {
        border-color: var(--primary-color);
        border-width: 2px;
        position: relative;
    }
    
    .subscription-card.featured .card-body {
        padding-top: 3rem !important;
    }
</style>
@endpush
