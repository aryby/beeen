@extends('layouts.soft-ui')

@section('title', 'Commande - ' . $subscription->name)

@section('content')
<!-- Hero Section -->
<section class="position-relative overflow-hidden py-5" style="background: var(--gradient-primary);">
    <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10">
        <svg width="100%" height="100%" viewBox="0 0 1000 1000">
            <defs>
                <pattern id="checkout-pattern" x="0" y="0" width="50" height="50" patternUnits="userSpaceOnUse">
                    <circle cx="25" cy="25" r="2" fill="rgba(255,255,255,0.3)"/>
                </pattern>
            </defs>
            <rect width="1000" height="1000" fill="url(#checkout-pattern)"/>
        </svg>
    </div>
    <div class="container position-relative">
        <div class="text-center text-white">
            <h1 class="display-5 fw-bold mb-3">Finaliser votre commande</h1>
            <p class="lead opacity-75">Quelques informations et votre abonnement sera activé immédiatement</p>
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
                    <li class="breadcrumb-item"><a href="{{ route('subscriptions.index') }}" class="text-decoration-none">Abonnements</a></li>
                    <li class="breadcrumb-item active">Commande</li>
                </ol>
            </nav>
            
            <div class="row">
                <!-- Formulaire de commande -->
                <div class="col-lg-8">
                    <div class="card-soft">
                        <div class="card-header text-white position-relative overflow-hidden" style="background: var(--gradient-primary); border-radius: var(--border-radius-soft-lg) var(--border-radius-soft-lg) 0 0;">
                            <div class="position-absolute top-0 start-0 w-100 h-100 opacity-20">
                                <svg width="100%" height="100%" viewBox="0 0 100 100">
                                    <circle cx="20" cy="20" r="2" fill="white" opacity="0.3"/>
                                    <circle cx="80" cy="40" r="1.5" fill="white" opacity="0.4"/>
                                    <circle cx="60" cy="80" r="1" fill="white" opacity="0.5"/>
                                </svg>
                            </div>
                            <div class="position-relative">
                                <h4 class="mb-0 fw-bold">
                                    <i class="bi bi-cart-check me-2"></i>Informations de commande
                                </h4>
                                <small class="opacity-75">Sécurisé par cryptage SSL 256 bits</small>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <form method="POST" action="{{ route('subscriptions.process', $subscription) }}">
                                @csrf
                                
                                <!-- Informations client -->
                                <div class="mb-4">
                                    <h5 class="fw-bold mb-3 d-flex align-items-center">
                                        <div class="feature-icon me-3" style="width: 2.5rem; height: 2.5rem;">
                                            <i class="bi bi-person"></i>
                                        </div>
                                        Vos informations
                                    </h5>
                                    
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="customer_name" class="form-label fw-bold">Nom complet *</label>
                                            <input type="text" 
                                                   class="form-control form-control-soft @error('customer_name') is-invalid @enderror" 
                                                   id="customer_name" 
                                                   name="customer_name" 
                                                   value="{{ old('customer_name', auth()->user()->name ?? '') }}" 
                                                   placeholder="Votre nom complet"
                                                   required>
                                            @error('customer_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="customer_email" class="form-label fw-bold">Adresse email *</label>
                                            <div class="input-group input-group-soft">
                                                <span class="input-group-text">
                                                    <i class="bi bi-envelope text-primary"></i>
                                                </span>
                                                <input type="email" 
                                                       class="form-control form-control-soft @error('customer_email') is-invalid @enderror" 
                                                       id="customer_email" 
                                                       name="customer_email" 
                                                       value="{{ old('customer_email', auth()->user()->email ?? '') }}" 
                                                       placeholder="votre@email.com"
                                                       required>
                                            </div>
                                            @error('customer_email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text d-flex align-items-center mt-2">
                                                <i class="bi bi-info-circle me-2 text-info"></i>
                                                Vos identifiants IPTV seront envoyés à cette adresse
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="customer_address" class="form-label fw-bold">Adresse (optionnel)</label>
                                            <textarea class="form-control form-control-soft @error('customer_address') is-invalid @enderror" 
                                                      id="customer_address" 
                                                      name="customer_address" 
                                                      rows="3"
                                                      placeholder="Votre adresse complète (optionnel)">{{ old('customer_address') }}</textarea>
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
                                        Conditions et sécurité
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
                                                J'accepte les <a href="{{ route('legal', 'terms') }}" target="_blank" class="text-decoration-none">conditions générales de vente</a> *
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
                                    <button type="submit" class="btn btn-soft btn-soft-primary btn-lg py-3">
                                        <i class="bi bi-credit-card me-2"></i>
                                        Payer avec PayPal - {{ $subscription->formatted_price }}
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
                
                <!-- Récapitulatif de commande -->
                <div class="col-lg-4">
                    <div class="card-soft sticky-top" style="top: 100px;">
                        <div class="card-header text-white" style="background: var(--gradient-success); border-radius: var(--border-radius-soft-lg) var(--border-radius-soft-lg) 0 0;">
                            <h5 class="mb-0 fw-bold">
                                <i class="bi bi-receipt me-2"></i>Récapitulatif
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <!-- Subscription Details -->
                            <div class="d-flex justify-content-between align-items-center mb-4 p-3 rounded" style="background: rgba(203, 12, 159, 0.1);">
                                <div>
                                    <h6 class="mb-1 fw-bold">{{ $subscription->name }}</h6>
                                    <small class="text-muted">{{ $subscription->duration_text }}</small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold fs-5" style="color: var(--soft-primary);">{{ $subscription->formatted_price }}</div>
                                    @if($subscription->duration_months > 1)
                                        <small class="text-success">
                                            {{ number_format($subscription->price / $subscription->duration_months, 2) }}€/mois
                                        </small>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Features Included -->
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3 d-flex align-items-center">
                                    <i class="bi bi-star text-warning me-2"></i>
                                    Inclus dans votre abonnement
                                </h6>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-check-circle text-success me-2"></i>
                                            <small>12000+ chaînes HD/4K</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-check-circle text-success me-2"></i>
                                            <small>VOD illimité</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-check-circle text-success me-2"></i>
                                            <small>Sans publicité</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-check-circle text-success me-2"></i>
                                            <small>Support 24/7</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-check-circle text-success me-2"></i>
                                            <small>Multi-appareils</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-check-circle text-success me-2"></i>
                                            <small>Activation immédiate</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <hr class="my-4">
                            
                            <!-- Total -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <span class="fw-bold fs-5">Total</span>
                                <span class="fw-bold fs-4" style="background: var(--gradient-primary); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                                    {{ $subscription->formatted_price }}
                                </span>
                            </div>
                            
                            <!-- Guarantees -->
                            <div class="alert alert-soft alert-info">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-lightning-charge text-info me-2 fs-4"></i>
                                    <div>
                                        <strong>Activation immédiate</strong><br>
                                        <small>Identifiants IPTV envoyés par email dans les minutes suivant votre paiement</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Security Badges -->
                    <div class="card-soft mt-4">
                        <div class="card-body text-center p-4">
                            <h6 class="fw-bold mb-3">Nos garanties</h6>
                            
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="feature-icon success mx-auto mb-2" style="width: 3rem; height: 3rem;">
                                        <i class="bi bi-shield-check"></i>
                                    </div>
                                    <small class="fw-bold">100% Légal</small>
                                </div>
                                <div class="col-4">
                                    <div class="feature-icon mx-auto mb-2" style="width: 3rem; height: 3rem;">
                                        <i class="bi bi-arrow-repeat"></i>
                                    </div>
                                    <small class="fw-bold">Satisfait ou remboursé</small>
                                </div>
                                <div class="col-4">
                                    <div class="feature-icon info mx-auto mb-2" style="width: 3rem; height: 3rem;">
                                        <i class="bi bi-headset"></i>
                                    </div>
                                    <small class="fw-bold">Support 24/7</small>
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
        
        // Enhanced form validation with Soft UI feedback
        form.addEventListener('submit', function(e) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="spinner-border spinner-border-sm me-2"></i>Traitement sécurisé en cours...';
            submitBtn.style.background = 'var(--gradient-warning)';
        });
        
        // Real-time validation with Soft UI styling
        const requiredFields = form.querySelectorAll('input[required], textarea[required]');
        requiredFields.forEach(field => {
            field.addEventListener('blur', function() {
                if (this.value.trim() === '') {
                    this.classList.add('is-invalid');
                    this.style.borderColor = 'var(--soft-danger)';
                } else {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                    this.style.borderColor = 'var(--soft-success)';
                }
            });
        });
        
        // Email validation with enhanced feedback
        const emailField = document.getElementById('customer_email');
        emailField.addEventListener('blur', function() {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(this.value)) {
                this.classList.add('is-invalid');
                this.style.borderColor = 'var(--soft-danger)';
                this.style.boxShadow = '0 0 0 2px rgba(234, 6, 6, 0.25)';
            } else {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
                this.style.borderColor = 'var(--soft-success)';
                this.style.boxShadow = '0 0 0 2px rgba(130, 214, 22, 0.25)';
            }
        });

        // Animate elements on load
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