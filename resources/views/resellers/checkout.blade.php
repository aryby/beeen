@extends('layouts.soft-ui')

@section('title', 'Commande Pack Revendeur - ' . $pack->name)

@section('content')
<!-- Hero Section -->
<section class="position-relative overflow-hidden py-5" style="background: var(--gradient-success);">
    <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10">
        <svg width="100%" height="100%" viewBox="0 0 1000 1000">
            <defs>
                <pattern id="reseller-checkout-pattern" x="0" y="0" width="80" height="80" patternUnits="userSpaceOnUse">
                    <circle cx="40" cy="40" r="2" fill="white"/>
                    <circle cx="20" cy="60" r="1" fill="white" opacity="0.6"/>
                    <circle cx="60" cy="20" r="1.5" fill="white" opacity="0.8"/>
                </pattern>
            </defs>
            <rect width="1000" height="1000" fill="url(#reseller-checkout-pattern)"/>
        </svg>
    </div>
    <div class="container position-relative">
        <div class="text-center text-white">
            <h1 class="display-5 fw-bold mb-3">Commande Pack Revendeur</h1>
            <p class="lead opacity-75">Rejoignez notre réseau de revendeurs et commencez à générer des revenus</p>
        </div>
    </div>
</section>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('resellers.index') }}" class="text-decoration-none">Revendeurs</a></li>
                    <li class="breadcrumb-item active">Commande Pack</li>
                </ol>
            </nav>
            
            <div class="row">
                <!-- Formulaire -->
                <div class="col-lg-8">
                    <div class="card-soft">
                        <div class="card-header text-white position-relative overflow-hidden" style="background: var(--gradient-success); border-radius: var(--border-radius-soft-lg) var(--border-radius-soft-lg) 0 0;">
                            <div class="position-relative">
                                <h4 class="mb-0 fw-bold">
                                    <i class="bi bi-cart-check me-2"></i>Finaliser votre commande revendeur
                                </h4>
                                <small class="opacity-75">Devenez revendeur IPTV en quelques clics</small>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <form method="POST" action="{{ route('resellers.process', $pack) }}">
                                @csrf
                                
                                <!-- Informations revendeur -->
                                <div class="mb-4">
                                    <h5 class="fw-bold mb-3 d-flex align-items-center">
                                        <div class="feature-icon me-3" style="width: 2.5rem; height: 2.5rem;">
                                            <i class="bi bi-person-badge"></i>
                                        </div>
                                        Informations revendeur
                                    </h5>
                                    
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="customer_name" class="form-label fw-bold">Nom / Raison sociale *</label>
                                            <input type="text" 
                                                   class="form-control form-control-soft @error('customer_name') is-invalid @enderror" 
                                                   id="customer_name" 
                                                   name="customer_name" 
                                                   value="{{ old('customer_name', auth()->user()->name ?? '') }}" 
                                                   placeholder="Votre nom ou raison sociale"
                                                   required>
                                            @error('customer_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="customer_email" class="form-label fw-bold">Adresse email professionnelle *</label>
                                            <div class="input-group input-group-soft">
                                                <span class="input-group-text">
                                                    <i class="bi bi-envelope text-success"></i>
                                                </span>
                                                <input type="email" 
                                                       class="form-control form-control-soft @error('customer_email') is-invalid @enderror" 
                                                       id="customer_email" 
                                                       name="customer_email" 
                                                       value="{{ old('customer_email', auth()->user()->email ?? '') }}" 
                                                       placeholder="votre@entreprise.com"
                                                       required>
                                            </div>
                                            @error('customer_email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text d-flex align-items-center mt-2">
                                                <i class="bi bi-info-circle me-2 text-info"></i>
                                                Vos accès revendeur seront envoyés à cette adresse
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="customer_address" class="form-label fw-bold">Adresse professionnelle</label>
                                            <textarea class="form-control form-control-soft @error('customer_address') is-invalid @enderror" 
                                                      id="customer_address" 
                                                      name="customer_address" 
                                                      rows="3"
                                                      placeholder="Adresse de votre entreprise (optionnel)">{{ old('customer_address') }}</textarea>
                                            @error('customer_address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Conditions -->
                                <div class="mb-4">
                                    <h5 class="fw-bold mb-3 d-flex align-items-center">
                                        <div class="feature-icon success me-3" style="width: 2.5rem; height: 2.5rem;">
                                            <i class="bi bi-shield-check"></i>
                                        </div>
                                        Conditions revendeur
                                    </h5>
                                    
                                    <div class="card-soft p-3 mb-3" style="background: rgba(130, 214, 22, 0.1);">
                                        <div class="form-check">
                                            <input class="form-check-input @error('terms_accepted') is-invalid @enderror" 
                                                   type="checkbox" 
                                                   id="terms_accepted" 
                                                   name="terms_accepted" 
                                                   value="1" 
                                                   {{ old('terms_accepted') ? 'checked' : '' }} 
                                                   required>
                                            <label class="form-check-label fw-bold" for="terms_accepted">
                                                J'accepte les <a href="{{ route('legal', 'terms') }}" target="_blank" class="text-decoration-none">conditions générales revendeur</a> *
                                            </label>
                                            @error('terms_accepted')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- reCAPTCHA -->
                                @if(App\Models\Setting::get('recaptcha_site_key'))
                                    <div class="mb-4 text-center">
                                        <div class="g-recaptcha d-inline-block" data-sitekey="{{ App\Models\Setting::get('recaptcha_site_key') }}"></div>
                                        @error('g-recaptcha-response')
                                            <div class="text-danger small mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endif
                                
                                <!-- Bouton de paiement -->
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-soft btn-soft-success btn-lg py-3">
                                        <i class="bi bi-credit-card me-2"></i>
                                        Payer avec PayPal - {{ $pack->formatted_price }}
                                    </button>
                                </div>
                                
                                <div class="text-center mt-3">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="bi bi-shield-lock me-2 text-success"></i>
                                        <small class="text-muted">Paiement 100% sécurisé via PayPal</small>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Récapitulatif Pack -->
                <div class="col-lg-4">
                    <div class="card-soft sticky-top" style="top: 100px;">
                        <div class="card-header text-white" style="background: var(--gradient-success); border-radius: var(--border-radius-soft-lg) var(--border-radius-soft-lg) 0 0;">
                            <h5 class="mb-0 fw-bold">
                                <i class="bi bi-package me-2"></i>Votre Pack Revendeur
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <!-- Pack Details -->
                            <div class="text-center mb-4 p-3 rounded" style="background: rgba(130, 214, 22, 0.1);">
                                <h4 class="fw-bold mb-2" style="color: var(--soft-dark);">{{ $pack->name }}</h4>
                                <div class="display-5 fw-bold mb-2" style="background: var(--gradient-success); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                                    {{ $pack->credits }}
                                </div>
                                <small class="text-muted">crédits IPTV</small>
                            </div>
                            
                            <!-- Pricing -->
                            <div class="d-flex justify-content-between align-items-center mb-3 p-3 rounded" style="background: rgba(23, 193, 232, 0.1);">
                                <span class="fw-bold">Prix du pack</span>
                                <span class="fw-bold fs-5" style="color: var(--soft-success);">{{ $pack->formatted_price }}</span>
                            </div>
                            
                            <div class="alert alert-soft alert-info small mb-4">
                                <i class="bi bi-calculator me-1"></i>
                                <strong>Prix par crédit :</strong> {{ $pack->formatted_price_per_credit }}
                            </div>
                            
                            <!-- What's included -->
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3">Inclus dans votre pack :</h6>
                                <ul class="list-unstyled">
                                    <li class="mb-2 d-flex align-items-center">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        <span>{{ $pack->credits }} codes IPTV à générer</span>
                                    </li>
                                    <li class="mb-2 d-flex align-items-center">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        <span>Tableau de bord revendeur</span>
                                    </li>
                                    <li class="mb-2 d-flex align-items-center">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        <span>Génération instantanée</span>
                                    </li>
                                    <li class="mb-2 d-flex align-items-center">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        <span>Historique complet</span>
                                    </li>
                                    <li class="mb-2 d-flex align-items-center">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        <span>Support revendeur 24/7</span>
                                    </li>
                                    <li class="mb-2 d-flex align-items-center">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        <span>Formation gratuite</span>
                                    </li>
                                </ul>
                            </div>
                            
                            <!-- Business Calculator -->
                            <div class="card-soft p-3" style="background: rgba(251, 207, 51, 0.1);">
                                <h6 class="fw-bold mb-2">
                                    <i class="bi bi-calculator text-warning me-2"></i>Calculateur de revenus
                                </h6>
                                <div class="small">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Prix d'achat :</span>
                                        <strong>{{ $pack->formatted_price }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Prix de revente suggéré :</span>
                                        <strong class="text-success">{{ number_format($pack->price * 1.5, 2) }}€</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Bénéfice potentiel :</span>
                                        <strong class="text-warning">+{{ number_format($pack->price * 0.5, 2) }}€</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Support Info -->
                    <div class="card-soft mt-4">
                        <div class="card-body text-center p-4">
                            <h6 class="fw-bold mb-3">Support Revendeur</h6>
                            
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="feature-icon success mx-auto mb-2" style="width: 3rem; height: 3rem;">
                                        <i class="bi bi-headset"></i>
                                    </div>
                                    <small class="fw-bold">Support 24/7</small>
                                </div>
                                <div class="col-4">
                                    <div class="feature-icon mx-auto mb-2" style="width: 3rem; height: 3rem;">
                                        <i class="bi bi-book"></i>
                                    </div>
                                    <small class="fw-bold">Formation incluse</small>
                                </div>
                                <div class="col-4">
                                    <div class="feature-icon info mx-auto mb-2" style="width: 3rem; height: 3rem;">
                                        <i class="bi bi-graph-up"></i>
                                    </div>
                                    <small class="fw-bold">Suivi des ventes</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if(App\Models\Setting::get('recaptcha_site_key'))
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const submitBtn = form.querySelector('button[type="submit"]');
        
        // Enhanced form validation
        form.addEventListener('submit', function(e) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="spinner-border spinner-border-sm me-2"></i>Traitement de votre commande...';
            submitBtn.style.background = 'var(--gradient-warning)';
        });
        
        // Real-time validation
        const requiredFields = form.querySelectorAll('input[required], textarea[required]');
        requiredFields.forEach(field => {
            field.addEventListener('blur', function() {
                if (this.value.trim() === '') {
                    this.style.borderColor = 'var(--soft-danger)';
                    this.style.boxShadow = '0 0 0 2px rgba(234, 6, 6, 0.25)';
                } else {
                    this.style.borderColor = 'var(--soft-success)';
                    this.style.boxShadow = '0 0 0 2px rgba(130, 214, 22, 0.25)';
                }
            });
        });

        // Animate cards on load
        const cards = document.querySelectorAll('.card-soft');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 200);
        });
    });
</script>
@endpush
