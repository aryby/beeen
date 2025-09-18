@extends('layouts.soft-ui')

@section('title', 'Accueil - Service IPTV Légal Premium')

@section('content')
<!-- Hero Section IPTV -->
<section class="iptv-hero-bg text-white position-relative overflow-hidden">
    <div class="hero-content">
        <div class="container">
            <div class="row align-items-center min-vh-100 py-5">
                <div class="col-lg-6">
                    <div class="animate-fade-in-up">
                        <h1 class="display-3 fw-bold mb-4">
                            Service IPTV
                            <span class="text-gradient" style="background: linear-gradient(310deg, #fbcf33 0%, #82d616 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                                Premium
                            </span>
                            <br>
                            <span style="background: linear-gradient(310deg, #17c1e8 0%, #21d4fd 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                                100% Légal
                            </span>
                        </h1>
                        <p class="lead mb-4 opacity-75" style="font-size: 1.25rem;">
                            {{ $subscriptionDescription }}
                        </p>
                        
                        <!-- Key Features -->
                        <div class="row mb-5">
                            <div class="col-6 col-lg-3 mb-3">
                                <div class="text-center">
                                    <div class="feature-icon success mb-2 mx-auto tv-glow">
                                        <i class="bi bi-tv fs-3"></i>
                                    </div>
                                    <div class="fw-bold">1000+</div>
                                    <small class="opacity-75">Chaînes HD</small>
                                </div>
                            </div>
                            <div class="col-6 col-lg-3 mb-3">
                                <div class="text-center">
                                    <div class="feature-icon info mb-2 mx-auto">
                                        <i class="bi bi-film fs-3"></i>
                                    </div>
                                    <div class="fw-bold">VOD</div>
                                    <small class="opacity-75">Illimité</small>
                                </div>
                            </div>
                            <div class="col-6 col-lg-3 mb-3">
                                <div class="text-center">
                                    <div class="feature-icon warning mb-2 mx-auto">
                                        <i class="bi bi-x-circle fs-3"></i>
                                    </div>
                                    <div class="fw-bold">Sans</div>
                                    <small class="opacity-75">Publicité</small>
                                </div>
                            </div>
                            <div class="col-6 col-lg-3 mb-3">
                                <div class="text-center">
                                    <div class="feature-icon mb-2 mx-auto">
                                        <i class="bi bi-headset fs-3"></i>
                                    </div>
                                    <div class="fw-bold">Support</div>
                                    <small class="opacity-75">24/7</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- CTA Buttons -->
                        <div class="d-flex flex-wrap gap-3">
                            <a href="{{ route('subscriptions.index') }}" class="btn btn-soft btn-soft-primary btn-lg px-4">
                                <i class="bi bi-cart-plus me-2"></i>Commander maintenant
                            </a>
                            <a href="{{ route('tutorials.index') }}" class="btn btn-soft-outline btn-lg px-4">
                                <i class="bi bi-play-circle me-2"></i>Voir les tutoriels
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="text-center position-relative">
                        <!-- TV Mockup -->
                        <div class="position-relative d-inline-block">
                            <div class="tv-glow">
                                <svg width="500" height="350" viewBox="0 0 500 350" class="img-fluid">
                                    <!-- TV Frame -->
                                    <defs>
                                        <linearGradient id="tvGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                            <stop offset="0%" style="stop-color:#344767"/>
                                            <stop offset="100%" style="stop-color:#1a1f37"/>
                                        </linearGradient>
                                        <linearGradient id="screenGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                            <stop offset="0%" style="stop-color:#17c1e8"/>
                                            <stop offset="50%" style="stop-color:#7928ca"/>
                                            <stop offset="100%" style="stop-color:#ff0080"/>
                                        </linearGradient>
                                    </defs>
                                    
                                    <!-- TV Body -->
                                    <rect x="50" y="50" width="400" height="250" rx="20" fill="url(#tvGradient)" stroke="rgba(255,255,255,0.2)" stroke-width="2"/>
                                    
                                    <!-- Screen -->
                                    <rect x="70" y="70" width="360" height="200" rx="10" fill="url(#screenGradient)"/>
                                    
                                    <!-- Screen Content -->
                                    <rect x="90" y="90" width="320" height="20" rx="5" fill="rgba(255,255,255,0.3)"/>
                                    <rect x="90" y="120" width="100" height="60" rx="5" fill="rgba(255,255,255,0.2)"/>
                                    <rect x="200" y="120" width="100" height="60" rx="5" fill="rgba(255,255,255,0.2)"/>
                                    <rect x="310" y="120" width="100" height="60" rx="5" fill="rgba(255,255,255,0.2)"/>
                                    <rect x="90" y="190" width="320" height="60" rx="5" fill="rgba(255,255,255,0.1)"/>
                                    
                                    <!-- IPTV Text -->
                                    <text x="250" y="235" text-anchor="middle" fill="white" font-family="Open Sans" font-weight="700" font-size="24">IPTV PRO</text>
                                    
                                    <!-- TV Stand -->
                                    <rect x="200" y="300" width="100" height="10" rx="5" fill="url(#tvGradient)"/>
                                    <rect x="230" y="310" width="40" height="20" rx="5" fill="url(#tvGradient)"/>
                                    
                                    <!-- Decorative elements -->
                                    <circle cx="400" cy="100" r="3" fill="rgba(130, 214, 22, 0.6)">
                                        <animate attributeName="opacity" values="0.6;1;0.6" dur="2s" repeatCount="indefinite"/>
                                    </circle>
                                    <circle cx="120" cy="200" r="2" fill="rgba(251, 207, 51, 0.6)">
                                        <animate attributeName="opacity" values="0.6;1;0.6" dur="3s" repeatCount="indefinite"/>
                                    </circle>
                                </svg>
                            </div>
                            
                            <!-- Floating Elements -->
                            <div class="position-absolute top-0 start-0 w-100 h-100">
                                <div class="position-absolute" style="top: 20%; left: -10%; animation: float 6s ease-in-out infinite;">
                                    <div class="glass-effect rounded-circle p-3">
                                        <i class="bi bi-hd-btn fs-4 text-info"></i>
                                    </div>
                                </div>
                                <div class="position-absolute" style="top: 60%; right: -10%; animation: float 4s ease-in-out infinite reverse;">
                                    <div class="glass-effect rounded-circle p-3">
                                        <i class="bi bi-wifi fs-4 text-success"></i>
                                    </div>
                                </div>
                                <div class="position-absolute" style="bottom: 20%; left: 10%; animation: float 5s ease-in-out infinite;">
                                    <div class="glass-effect rounded-circle p-3">
                                        <i class="bi bi-shield-check fs-4 text-warning"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Subscriptions Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3" style="color: var(--soft-dark);">Nos Abonnements</h2>
            <p class="lead text-muted">Choisissez la formule qui vous convient le mieux</p>
        </div>
        
        <div class="row justify-content-center">
            @foreach($subscriptions as $index => $subscription)
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="pricing-card {{ $index === 1 ? 'featured' : '' }} h-100">
                        <div class="card-body text-center p-4">
                            <h4 class="fw-bold mb-3" style="color: var(--soft-dark);">{{ $subscription->name }}</h4>
                            
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
                            </ul>
                            
                            <a href="{{ route('subscriptions.checkout', $subscription) }}" 
                               class="btn btn-soft btn-soft-primary w-100 mb-3">
                                <i class="bi bi-cart-plus me-2"></i>Commander
                            </a>
                            
                            <small class="text-muted">
                                <i class="bi bi-shield-check me-1 text-success"></i>Paiement sécurisé
                            </small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5" style="background: linear-gradient(310deg, #f8f9fa 0%, #fff 100%);">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3" style="color: var(--soft-dark);">Pourquoi choisir notre service ?</h2>
            <p class="lead text-muted">Les avantages qui font la différence</p>
        </div>
        
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card-soft h-100 p-4 text-center">
                    <div class="feature-icon mx-auto mb-3">
                        <i class="bi bi-shield-check fs-2"></i>
                    </div>
                    <h5 class="fw-bold mb-3" style="color: var(--soft-dark);">100% Légal</h5>
                    <p class="text-muted">Service entièrement légal et conforme aux réglementations en vigueur.</p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card-soft h-100 p-4 text-center">
                    <div class="feature-icon success mx-auto mb-3">
                        <i class="bi bi-lightning-charge fs-2"></i>
                    </div>
                    <h5 class="fw-bold mb-3" style="color: var(--soft-dark);">Activation Immédiate</h5>
                    <p class="text-muted">Recevez vos identifiants par email dans les minutes qui suivent votre paiement.</p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card-soft h-100 p-4 text-center">
                    <div class="feature-icon info mx-auto mb-3">
                        <i class="bi bi-headset fs-2"></i>
                    </div>
                    <h5 class="fw-bold mb-3" style="color: var(--soft-dark);">Support 24/7</h5>
                    <p class="text-muted">Une équipe dédiée disponible 24h/24 pour répondre à toutes vos questions.</p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card-soft h-100 p-4 text-center">
                    <div class="feature-icon warning mx-auto mb-3">
                        <i class="bi bi-display fs-2"></i>
                    </div>
                    <h5 class="fw-bold mb-3" style="color: var(--soft-dark);">Multi-appareils</h5>
                    <p class="text-muted">Compatible avec tous vos appareils : TV, smartphone, tablette, PC.</p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card-soft h-100 p-4 text-center">
                    <div class="feature-icon mx-auto mb-3" style="background: var(--gradient-danger);">
                        <i class="bi bi-hd-btn fs-2"></i>
                    </div>
                    <h5 class="fw-bold mb-3" style="color: var(--soft-dark);">Qualité HD/4K</h5>
                    <p class="text-muted">Profitez d'une qualité d'image exceptionnelle en HD et 4K.</p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card-soft h-100 p-4 text-center">
                    <div class="feature-icon mx-auto mb-3" style="background: var(--gradient-secondary);">
                        <i class="bi bi-arrow-clockwise fs-2"></i>
                    </div>
                    <h5 class="fw-bold mb-3" style="color: var(--soft-dark);">Mise à jour continue</h5>
                    <p class="text-muted">Contenu régulièrement mis à jour avec les dernières nouveautés.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="py-5 position-relative">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card text-center">
                    <div class="feature-icon mx-auto mb-3">
                        <i class="bi bi-people fs-2"></i>
                    </div>
                    <h3 class="fw-bold mb-1" style="color: var(--soft-dark);">50,000+</h3>
                    <p class="text-muted mb-0">Clients satisfaits</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card success text-center">
                    <div class="feature-icon success mx-auto mb-3">
                        <i class="bi bi-tv fs-2"></i>
                    </div>
                    <h3 class="fw-bold mb-1" style="color: var(--soft-dark);">1000+</h3>
                    <p class="text-muted mb-0">Chaînes disponibles</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card info text-center">
                    <div class="feature-icon info mx-auto mb-3">
                        <i class="bi bi-globe fs-2"></i>
                    </div>
                    <h3 class="fw-bold mb-1" style="color: var(--soft-dark);">99.9%</h3>
                    <p class="text-muted mb-0">Temps de disponibilité</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card warning text-center">
                    <div class="feature-icon warning mx-auto mb-3">
                        <i class="bi bi-headset fs-2"></i>
                    </div>
                    <h3 class="fw-bold mb-1" style="color: var(--soft-dark);">24/7</h3>
                    <p class="text-muted mb-0">Support technique</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
@if($testimonials->count() > 0)
<section class="py-5" style="background: linear-gradient(310deg, #f8f9fa 0%, #e9ecef 100%);">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3" style="color: var(--soft-dark);">Ce que disent nos clients</h2>
            <p class="lead text-muted">Témoignages authentiques de nos utilisateurs satisfaits</p>
        </div>
        
        <div class="row">
            @foreach($testimonials->take(3) as $testimonial)
                <div class="col-lg-4 mb-4">
                    <div class="testimonial-soft h-100">
                        <div class="d-flex align-items-center mb-3">
                            <div class="feature-icon me-3" style="width: 3rem; height: 3rem;">
                                <i class="bi bi-person fs-4"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">{{ $testimonial->customer_name }}</h6>
                                @if($testimonial->customer_location)
                                    <small class="text-muted">{{ $testimonial->customer_location }}</small>
                                @endif
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $i <= $testimonial->rating ? '-fill' : '' }} text-warning"></i>
                            @endfor
                        </div>
                        
                        <blockquote class="mb-0">
                            <p class="text-muted fst-italic">"{{ $testimonial->testimonial }}"</p>
                        </blockquote>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- CTA Section -->
<section class="py-5 position-relative overflow-hidden">
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: var(--gradient-primary); opacity: 0.9;"></div>
    <div class="position-relative">
        <div class="container text-center text-white">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h2 class="display-5 fw-bold mb-4">Prêt à commencer ?</h2>
                    <p class="lead mb-4 opacity-75">
                        Rejoignez des milliers de clients satisfaits et découvrez le meilleur du divertissement en streaming.
                    </p>
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="{{ route('subscriptions.index') }}" class="btn btn-soft" style="background: white; color: var(--soft-primary);">
                            <i class="bi bi-cart-plus me-2"></i>Choisir mon abonnement
                        </a>
                        <a href="{{ route('contact.index') }}" class="btn btn-soft-outline" style="border-color: white; color: white;">
                            <i class="bi bi-question-circle me-2"></i>Poser une question
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }

    /* Custom animations for IPTV theme */
    .tv-glow {
        animation: tvGlow 3s ease-in-out infinite alternate;
    }

    @keyframes tvGlow {
        from {
            filter: drop-shadow(0 0 20px rgba(23, 193, 232, 0.5));
        }
        to {
            filter: drop-shadow(0 0 30px rgba(255, 0, 128, 0.7));
        }
    }

    /* Parallax effect for hero */
    .iptv-hero-bg {
        background-attachment: fixed;
        background-size: cover;
        background-position: center;
    }

    /* Smooth entrance animations */
    .animate-on-scroll {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.6s ease;
    }

    .animate-on-scroll.visible {
        opacity: 1;
        transform: translateY(0);
    }
</style>
@endpush

@push('scripts')
<script>
    // Enhanced scroll animations
    const animateElements = document.querySelectorAll('.card-soft, .pricing-card, .stat-card, .testimonial-soft');
    
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, index * 100);
            }
        });
    }, observerOptions);

    animateElements.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });

    // Parallax effect for floating elements
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        const parallaxElements = document.querySelectorAll('[style*="animation: float"]');
        
        parallaxElements.forEach((el, index) => {
            const speed = 0.5 + (index * 0.1);
            el.style.transform = `translateY(${scrolled * speed}px)`;
        });
    });

    // Counter animation
    function animateCounter(element, target, duration = 2000) {
        let start = 0;
        const increment = target / (duration / 16);
        
        const timer = setInterval(() => {
            start += increment;
            element.textContent = Math.floor(start).toLocaleString() + '+';
            
            if (start >= target) {
                element.textContent = target.toLocaleString() + '+';
                clearInterval(timer);
            }
        }, 16);
    }

    // Trigger counters when stats section is visible
    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counters = entry.target.querySelectorAll('.fw-bold');
                counters.forEach(counter => {
                    const text = counter.textContent;
                    const number = parseInt(text.replace(/[^0-9]/g, ''));
                    if (number > 0) {
                        animateCounter(counter, number);
                    }
                });
                statsObserver.unobserve(entry.target);
            }
        });
    });

    document.querySelectorAll('.stat-card').forEach(card => {
        statsObserver.observe(card);
    });
</script>
@endpush
