@extends('layouts.app')

@section('title', 'Commande - ' . $subscription->name)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('subscriptions.index') }}">Abonnements</a></li>
                    <li class="breadcrumb-item active">Commande</li>
                </ol>
            </nav>
            
            <div class="row">
                <!-- Formulaire de commande -->
                <div class="col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0"><i class="bi bi-cart-check me-2"></i>Finaliser votre commande</h4>
                        </div>
                        <div class="card-body p-4">
                            <form method="POST" action="{{ route('subscriptions.process', $subscription) }}">
                                @csrf
                                
                                <!-- Informations client -->
                                <div class="mb-4">
                                    <h5 class="fw-bold mb-3">Vos informations</h5>
                                    
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="customer_name" class="form-label">Nom complet *</label>
                                            <input type="text" 
                                                   class="form-control @error('customer_name') is-invalid @enderror" 
                                                   id="customer_name" 
                                                   name="customer_name" 
                                                   value="{{ old('customer_name', auth()->user()->name ?? '') }}" 
                                                   required>
                                            @error('customer_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="customer_email" class="form-label">Adresse email *</label>
                                            <input type="email" 
                                                   class="form-control @error('customer_email') is-invalid @enderror" 
                                                   id="customer_email" 
                                                   name="customer_email" 
                                                   value="{{ old('customer_email', auth()->user()->email ?? '') }}" 
                                                   required>
                                            @error('customer_email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">
                                                <i class="bi bi-info-circle me-1"></i>
                                                Vos identifiants IPTV seront envoyés à cette adresse
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="customer_address" class="form-label">Adresse (optionnel)</label>
                                            <textarea class="form-control @error('customer_address') is-invalid @enderror" 
                                                      id="customer_address" 
                                                      name="customer_address" 
                                                      rows="3">{{ old('customer_address') }}</textarea>
                                            @error('customer_address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Conditions -->
                                <div class="mb-4">
                                    <h5 class="fw-bold mb-3">Conditions</h5>
                                    
                                    <div class="form-check mb-3">
                                        <input class="form-check-input @error('terms_accepted') is-invalid @enderror" 
                                               type="checkbox" 
                                               id="terms_accepted" 
                                               name="terms_accepted" 
                                               value="1" 
                                               {{ old('terms_accepted') ? 'checked' : '' }} 
                                               required>
                                        <label class="form-check-label" for="terms_accepted">
                                            J'accepte les <a href="{{ route('legal', 'terms') }}" target="_blank">conditions générales de vente</a> *
                                        </label>
                                        @error('terms_accepted')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <!-- reCAPTCHA -->
                                @if(App\Models\Setting::get('recaptcha_site_key'))
                                    <div class="mb-4">
                                        <div class="g-recaptcha" data-sitekey="{{ App\Models\Setting::get('recaptcha_site_key') }}"></div>
                                        @error('g-recaptcha-response')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endif
                                
                                <!-- Bouton de paiement -->
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="bi bi-credit-card me-2"></i>
                                        Payer avec PayPal - {{ $subscription->formatted_price }}
                                    </button>
                                </div>
                                
                                <div class="text-center mt-3">
                                    <small class="text-muted">
                                        <i class="bi bi-shield-lock me-1"></i>
                                        Paiement 100% sécurisé via PayPal
                                    </small>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Récapitulatif de commande -->
                <div class="col-lg-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Récapitulatif</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="mb-1">{{ $subscription->name }}</h6>
                                    <small class="text-muted">{{ $subscription->duration_text }}</small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold">{{ $subscription->formatted_price }}</div>
                                    @if($subscription->duration_months > 1)
                                        <small class="text-muted">
                                            {{ number_format($subscription->price / $subscription->duration_months, 2) }}€/mois
                                        </small>
                                    @endif
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="mb-3">
                                <h6 class="fw-bold mb-2">Inclus dans votre abonnement :</h6>
                                <ul class="list-unstyled small">
                                    <li class="mb-1">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        1000+ chaînes HD/4K
                                    </li>
                                    <li class="mb-1">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        VOD illimité
                                    </li>
                                    <li class="mb-1">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        Sans publicité
                                    </li>
                                    <li class="mb-1">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        Support 24/7
                                    </li>
                                    <li class="mb-1">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        Multi-appareils (3 simultanés)
                                    </li>
                                    <li class="mb-1">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        Activation immédiate
                                    </li>
                                </ul>
                            </div>
                            
                            <hr>
                            
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="fw-bold">Total</span>
                                <span class="fw-bold fs-5 text-primary">{{ $subscription->formatted_price }}</span>
                            </div>
                            
                            <div class="alert alert-info small mb-0">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Activation immédiate :</strong> Vous recevrez vos identifiants IPTV par email dans les minutes qui suivent votre paiement.
                            </div>
                        </div>
                    </div>
                    
                    <!-- Garanties -->
                    <div class="card shadow-sm mt-3">
                        <div class="card-body text-center">
                            <h6 class="fw-bold mb-3">Nos garanties</h6>
                            
                            <div class="row text-center">
                                <div class="col-4">
                                    <i class="bi bi-shield-check text-success fs-3 mb-2 d-block"></i>
                                    <small>100% Légal</small>
                                </div>
                                <div class="col-4">
                                    <i class="bi bi-arrow-repeat text-primary fs-3 mb-2 d-block"></i>
                                    <small>Satisfait ou remboursé</small>
                                </div>
                                <div class="col-4">
                                    <i class="bi bi-headset text-info fs-3 mb-2 d-block"></i>
                                    <small>Support 24/7</small>
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
    // Validation du formulaire
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const submitBtn = form.querySelector('button[type="submit"]');
        
        form.addEventListener('submit', function(e) {
            // Désactiver le bouton pour éviter les doubles soumissions
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="spinner-border spinner-border-sm me-2"></i>Traitement en cours...';
        });
        
        // Validation en temps réel
        const requiredFields = form.querySelectorAll('input[required], textarea[required]');
        requiredFields.forEach(field => {
            field.addEventListener('blur', function() {
                if (this.value.trim() === '') {
                    this.classList.add('is-invalid');
                } else {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                }
            });
        });
        
        // Validation email
        const emailField = document.getElementById('customer_email');
        emailField.addEventListener('blur', function() {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(this.value)) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });
    });
</script>
@endpush
