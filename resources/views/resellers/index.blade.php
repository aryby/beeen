@extends('layouts.soft-ui')

@section('title', 'Devenir Revendeur IPTV')

@section('content')
<!-- Hero Section -->
<section class="position-relative overflow-hidden py-5 text-white" style="background: var(--gradient-success);">
    <div class="position-absolute top-0 start-0 w-100 h-100 opacity-15">
        <svg width="100%" height="100%" viewBox="0 0 1000 1000">
            <defs>
                <pattern id="reseller-pattern" x="0" y="0" width="100" height="100" patternUnits="userSpaceOnUse">
                    <circle cx="50" cy="50" r="3" fill="white"/>
                    <circle cx="25" cy="25" r="1.5" fill="white" opacity="0.6"/>
                    <circle cx="75" cy="75" r="2" fill="white" opacity="0.8"/>
                </pattern>
            </defs>
            <rect width="1000" height="1000" fill="url(#reseller-pattern)"/>
        </svg>
    </div>
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">Devenez Revendeur IPTV</h1>
                <p class="lead mb-4 opacity-75">
                    Générez des revenus en revendant nos services IPTV avec notre système de crédits simple et efficace.
                </p>
                <div class="row">
                    <div class="col-6 col-md-3 mb-3">
                        <div class="text-center">
                            <div class="feature-icon mb-2 mx-auto" style="background: rgba(255,255,255,0.2);">
                                <i class="bi bi-currency-euro fs-3"></i>
                            </div>
                            <div class="fw-bold">Revenus</div>
                            <small class="opacity-75">Marges attractives</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-3">
                        <div class="text-center">
                            <div class="feature-icon mb-2 mx-auto" style="background: rgba(255,255,255,0.2);">
                                <i class="bi bi-lightning fs-3"></i>
                            </div>
                            <div class="fw-bold">Instantané</div>
                            <small class="opacity-75">Codes immédiats</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-3">
                        <div class="text-center">
                            <div class="feature-icon mb-2 mx-auto" style="background: rgba(255,255,255,0.2);">
                                <i class="bi bi-headset fs-3"></i>
                            </div>
                            <div class="fw-bold">Support</div>
                            <small class="opacity-75">Accompagnement 24/7</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-3">
                        <div class="text-center">
                            <div class="feature-icon mb-2 mx-auto" style="background: rgba(255,255,255,0.2);">
                                <i class="bi bi-graph-up fs-3"></i>
                            </div>
                            <div class="fw-bold">Évolutif</div>
                            <small class="opacity-75">Croissance rapide</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-center">
                <!-- Business Growth SVG -->
                <svg width="300" height="200" viewBox="0 0 300 200" class="img-fluid">
                    <defs>
                        <linearGradient id="businessGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#fbcf33"/>
                            <stop offset="100%" style="stop-color:#82d616"/>
                        </linearGradient>
                    </defs>
                    
                    <!-- Growth chart -->
                    <path d="M50 150 L100 130 L150 100 L200 80 L250 50" 
                          stroke="url(#businessGradient)" 
                          stroke-width="4" 
                          fill="none"/>
                    
                    <!-- Data points -->
                    <circle cx="50" cy="150" r="6" fill="white" opacity="0.9"/>
                    <circle cx="100" cy="130" r="6" fill="white" opacity="0.9"/>
                    <circle cx="150" cy="100" r="6" fill="white" opacity="0.9"/>
                    <circle cx="200" cy="80" r="6" fill="white" opacity="0.9"/>
                    <circle cx="250" cy="50" r="6" fill="white" opacity="0.9"/>
                    
                    <!-- Money symbols -->
                    <text x="60" y="170" fill="white" font-size="12" opacity="0.8">€</text>
                    <text x="210" y="100" fill="white" font-size="16" opacity="0.9">€€</text>
                    <text x="250" y="40" fill="white" font-size="20" opacity="1">€€€</text>
                    
                    <!-- Arrow -->
                    <path d="M240 60 L250 50 L240 40" stroke="white" stroke-width="3" fill="none"/>
                </svg>
            </div>
        </div>
    </div>
</section>

<!-- How it works -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3" style="color: var(--soft-dark);">Comment ça marche ?</h2>
            <p class="lead text-muted">Un système simple en 3 étapes</p>
        </div>
        
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card-soft text-center p-4 h-100">
                    <div class="position-relative mb-4">
                        <div class="feature-icon mx-auto" style="width: 5rem; height: 5rem;">
                            <span class="fs-1 fw-bold">1</span>
                        </div>
                        <div class="position-absolute top-0 start-50 translate-middle" style="margin-top: -10px;">
                            <span class="badge" style="background: var(--gradient-primary); color: white; border-radius: var(--border-radius-soft);">ÉTAPE</span>
                        </div>
                    </div>
                    <h4 class="fw-bold mb-3" style="color: var(--soft-dark);">Achetez des crédits</h4>
                    <p class="text-muted">Choisissez un pack de crédits selon vos besoins. Plus vous achetez, plus le prix unitaire diminue.</p>
                </div>
            </div>
            
            <div class="col-lg-4 mb-4">
                <div class="card-soft text-center p-4 h-100">
                    <div class="position-relative mb-4">
                        <div class="feature-icon success mx-auto" style="width: 5rem; height: 5rem;">
                            <span class="fs-1 fw-bold">2</span>
                        </div>
                        <div class="position-absolute top-0 start-50 translate-middle" style="margin-top: -10px;">
                            <span class="badge" style="background: var(--gradient-success); color: white; border-radius: var(--border-radius-soft);">ÉTAPE</span>
                        </div>
                    </div>
                    <h4 class="fw-bold mb-3" style="color: var(--soft-dark);">Générez des codes</h4>
                    <p class="text-muted">Utilisez vos crédits pour générer des codes IPTV instantanément depuis votre tableau de bord.</p>
                </div>
            </div>
            
            <div class="col-lg-4 mb-4">
                <div class="card-soft text-center p-4 h-100">
                    <div class="position-relative mb-4">
                        <div class="feature-icon warning mx-auto" style="width: 5rem; height: 5rem;">
                            <span class="fs-1 fw-bold">3</span>
                        </div>
                        <div class="position-absolute top-0 start-50 translate-middle" style="margin-top: -10px;">
                            <span class="badge" style="background: var(--gradient-warning); color: white; border-radius: var(--border-radius-soft);">ÉTAPE</span>
                        </div>
                    </div>
                    <h4 class="fw-bold mb-3" style="color: var(--soft-dark);">Vendez à vos clients</h4>
                    <p class="text-muted">Revendez les codes à vos clients avec votre marge. Vous gérez vos prix librement.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Packs Revendeur -->
<section class="py-5" style="background: linear-gradient(310deg, #f8f9fa 0%, #fff 100%);">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3" style="color: var(--soft-dark);">Nos Packs Revendeur</h2>
            <p class="lead text-muted">Choisissez le pack qui correspond à votre activité</p>
        </div>

        @if($packs->count() > 0)
            <div class="row justify-content-center">
                @foreach($packs as $index => $pack)
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="pricing-card {{ $index === 1 ? 'featured' : '' }} h-100">
                            <div class="card-body text-center p-4">
                                <h3 class="fw-bold mb-3" style="color: var(--soft-dark);">{{ $pack->name }}</h3>
                                
                                <div class="mb-3">
                                    <span class="display-4 fw-bold" style="background: var(--gradient-success); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                                        {{ $pack->credits }}
                                    </span>
                                    <div class="text-muted">crédits</div>
                                </div>
                                
                                <div class="mb-3">
                                    <span class="fs-2 fw-bold" style="color: var(--soft-dark);">{{ $pack->formatted_price }}</span>
                                </div>
                                
                                <div class="alert alert-soft alert-success small mb-4">
                                    <i class="bi bi-calculator me-1"></i>
                                    {{ $pack->formatted_price_per_credit }}
                                </div>
                                
                                <ul class="list-unstyled mb-4">
                                    <li class="mb-2 d-flex align-items-center">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        <span><strong>{{ $pack->credits }}</strong> codes IPTV</span>
                                    </li>
                                    <li class="mb-2 d-flex align-items-center">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        <span><strong>Génération</strong> instantanée</span>
                                    </li>
                                    <li class="mb-2 d-flex align-items-center">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        <span><strong>Support</strong> dédié</span>
                                    </li>
                                    <li class="mb-2 d-flex align-items-center">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        <span><strong>Tableau de bord</strong> complet</span>
                                    </li>
                                    <li class="mb-2 d-flex align-items-center">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        <span><strong>Historique</strong> des ventes</span>
                                    </li>
                                </ul>
                                
                                @auth
                                    <a href="{{ route('resellers.checkout', $pack) }}" 
                                       class="btn btn-soft btn-soft-success w-100 mb-3">
                                        <i class="bi bi-cart-plus me-2"></i>Acheter ce pack
                                    </a>
                                @else
                                    <button type="button" 
                                            class="btn btn-soft btn-soft-success w-100 mb-3"
                                            onclick="openQuickOrderModal('reseller_pack', {{ $pack->id }}, '{{ $pack->name }}', '{{ $pack->formatted_price }}', 'Pack revendeur avec {{ $pack->credits }} crédits', '{{ $pack->credits }} crédits IPTV')">
                                        <i class="bi bi-lightning-charge me-2"></i>Acheter Rapidement
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
                <h3 class="fw-bold mb-3">Aucun pack disponible</h3>
                <p class="text-muted">Les packs revendeur seront bientôt disponibles.</p>
                <a href="{{ route('contact.index') }}" class="btn btn-soft btn-soft-primary">Nous contacter</a>
            </div>
        @endif
    </div>
</section>

<!-- Avantages -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-6 fw-bold mb-3" style="color: var(--soft-dark);">Pourquoi devenir revendeur ?</h2>
            <p class="lead text-muted">Les avantages de rejoindre notre réseau</p>
        </div>
        
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card-soft p-4 h-100">
                    <div class="d-flex align-items-start">
                        <div class="feature-icon success me-3">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-2">Marges Attractives</h5>
                            <p class="text-muted mb-0">Définissez librement vos prix de revente et maximisez vos profits.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card-soft p-4 h-100">
                    <div class="d-flex align-items-start">
                        <div class="feature-icon me-3">
                            <i class="bi bi-lightning-charge"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-2">Génération Instantanée</h5>
                            <p class="text-muted mb-0">Créez des codes IPTV en temps réel depuis votre tableau de bord.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card-soft p-4 h-100">
                    <div class="d-flex align-items-start">
                        <div class="feature-icon info me-3">
                            <i class="bi bi-bar-chart"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-2">Suivi Complet</h5>
                            <p class="text-muted mb-0">Suivez vos ventes, crédits et statistiques en temps réel.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card-soft p-4 h-100">
                    <div class="d-flex align-items-start">
                        <div class="feature-icon warning me-3">
                            <i class="bi bi-headset"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-2">Support Dédié</h5>
                            <p class="text-muted mb-0">Une équipe dédiée aux revendeurs pour vous accompagner.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card-soft p-4 h-100">
                    <div class="d-flex align-items-start">
                        <div class="feature-icon me-3" style="background: var(--gradient-danger);">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-2">Service Légal</h5>
                            <p class="text-muted mb-0">Revendez un service 100% légal et conforme.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card-soft p-4 h-100">
                    <div class="d-flex align-items-start">
                        <div class="feature-icon me-3" style="background: var(--gradient-secondary);">
                            <i class="bi bi-clock"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-2">Disponibilité 24/7</h5>
                            <p class="text-muted mb-0">Générez des codes à tout moment, même la nuit ou le weekend.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 position-relative overflow-hidden">
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: var(--gradient-success);"></div>
    <div class="position-relative">
        <div class="container text-center text-white">
            <h2 class="display-6 fw-bold mb-4">Prêt à commencer ?</h2>
            <p class="lead mb-4 opacity-75">
                Rejoignez notre réseau de revendeurs et développez votre activité dès aujourd'hui.
            </p>
            <div class="d-flex flex-wrap justify-content-center gap-3">
                @if($packs->count() > 0)
                    <a href="{{ route('resellers.checkout', $packs->first()) }}" class="btn btn-soft" style="background: white; color: var(--soft-success);">
                        <i class="bi bi-cart-plus me-2"></i>Acheter mon premier pack
                    </a>
                @endif
                <a href="{{ route('contact.index') }}" class="btn btn-soft-outline" style="border-color: white; color: white;">
                    <i class="bi bi-question-circle me-2"></i>Poser une question
                </a>
            </div>
        </div>
    </div>
</section>
@endsection