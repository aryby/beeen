@extends('layouts.app')

@section('title', 'Accueil - Service IPTV Légal Premium')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">
                    Service IPTV Premium
                    <span class="text-warning">100% Légal</span>
                </h1>
                <p class="lead mb-4">
                    {{ $subscriptionDescription }}
                </p>
                <div class="row mb-4">
                    <div class="col-sm-6 col-lg-3 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-tv fs-2 text-warning me-3"></i>
                            <div>
                                <div class="fw-bold">1000+</div>
                                <small>Chaînes HD</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-film fs-2 text-warning me-3"></i>
                            <div>
                                <div class="fw-bold">VOD</div>
                                <small>Illimité</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-x-circle fs-2 text-warning me-3"></i>
                            <div>
                                <div class="fw-bold">Sans</div>
                                <small>Publicité</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-headset fs-2 text-warning me-3"></i>
                            <div>
                                <div class="fw-bold">Support</div>
                                <small>24/7</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('subscriptions.index') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-cart-plus me-2"></i>Commander maintenant
                    </a>
                    <a href="{{ route('tutorials.index') }}" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-play-circle me-2"></i>Voir les tutoriels
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <img src="https://via.placeholder.com/500x400/0d6efd/ffffff?text=IPTV+Premium" 
                     alt="IPTV Service" 
                     class="img-fluid rounded shadow-lg"
                     style="max-width: 100%; height: auto;">
            </div>
        </div>
    </div>
</section>

<!-- Subscriptions Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3">Nos Abonnements</h2>
            <p class="lead text-muted">Choisissez la formule qui vous convient le mieux</p>
        </div>
        
        <div class="row justify-content-center">
            @foreach($subscriptions as $index => $subscription)
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card subscription-card h-100 {{ $index === 1 ? 'featured' : '' }}">
                        <div class="card-body text-center p-4">
                            <h4 class="card-title fw-bold mb-3">{{ $subscription->name }}</h4>
                            <div class="price-display mb-3">{{ $subscription->formatted_price }}</div>
                            <p class="text-muted mb-4">{{ $subscription->duration_text }}</p>
                            
                            <ul class="list-unstyled mb-4">
                                <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>1000+ Chaînes HD</li>
                                <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>VOD Illimité</li>
                                <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Sans Publicité</li>
                                <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Support 24/7</li>
                                <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Multi-appareils</li>
                            </ul>
                            
                            <a href="{{ route('subscriptions.checkout', $subscription) }}" 
                               class="btn btn-primary btn-lg w-100">
                                <i class="bi bi-cart-plus me-2"></i>Commander
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="text-center mt-4">
            <p class="text-muted">
                <i class="bi bi-shield-check text-success me-2"></i>
                Paiement sécurisé via PayPal • Activation immédiate • Garantie satisfait ou remboursé
            </p>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3">Pourquoi choisir notre service ?</h2>
            <p class="lead text-muted">Les avantages qui font la différence</p>
        </div>
        
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="text-center">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-shield-check fs-2"></i>
                    </div>
                    <h4 class="fw-bold mb-3">100% Légal</h4>
                    <p class="text-muted">Service entièrement légal et conforme aux réglementations en vigueur.</p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="text-center">
                    <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-lightning-charge fs-2"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Activation Immédiate</h4>
                    <p class="text-muted">Recevez vos identifiants par email dans les minutes qui suivent votre paiement.</p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="text-center">
                    <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-headset fs-2"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Support 24/7</h4>
                    <p class="text-muted">Une équipe dédiée disponible 24h/24 pour répondre à toutes vos questions.</p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="text-center">
                    <div class="bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-display fs-2"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Multi-appareils</h4>
                    <p class="text-muted">Compatible avec tous vos appareils : TV, smartphone, tablette, PC.</p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="text-center">
                    <div class="bg-danger text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-hd-btn fs-2"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Qualité HD/4K</h4>
                    <p class="text-muted">Profitez d'une qualité d'image exceptionnelle en HD et 4K.</p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="text-center">
                    <div class="bg-secondary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-arrow-clockwise fs-2"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Mise à jour continue</h4>
                    <p class="text-muted">Contenu régulièrement mis à jour avec les dernières nouveautés.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
@if($testimonials->count() > 0)
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3">Ce que disent nos clients</h2>
            <p class="lead text-muted">Témoignages authentiques de nos utilisateurs satisfaits</p>
        </div>
        
        <div class="row">
            @foreach($testimonials as $testimonial)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="testimonial-card h-100">
                        <div class="stars mb-3">{{ $testimonial->stars }}</div>
                        <blockquote class="mb-3">
                            "{{ $testimonial->testimonial }}"
                        </blockquote>
                        <footer class="d-flex align-items-center">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="bi bi-person fs-4"></i>
                            </div>
                            <div>
                                <div class="fw-bold">{{ $testimonial->customer_name }}</div>
                                @if($testimonial->customer_location)
                                    <small class="text-muted">{{ $testimonial->customer_location }}</small>
                                @endif
                            </div>
                        </footer>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- CTA Section -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h2 class="display-5 fw-bold mb-4">Prêt à commencer ?</h2>
                <p class="lead mb-4">
                    Rejoignez des milliers de clients satisfaits et découvrez le meilleur du divertissement en streaming.
                </p>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="{{ route('subscriptions.index') }}" class="btn btn-light btn-lg">
                        <i class="bi bi-cart-plus me-2"></i>Choisir mon abonnement
                    </a>
                    <a href="{{ route('contact.index') }}" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-question-circle me-2"></i>Poser une question
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Animation des cartes au scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observer toutes les cartes
    document.querySelectorAll('.card, .testimonial-card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });
</script>
@endpush
