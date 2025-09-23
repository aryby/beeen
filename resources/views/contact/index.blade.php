@extends('layouts.soft-ui')

@section('title', 'Contact - Support Client')

@section('content')
<!-- Hero Section -->
<section class="position-relative overflow-hidden py-5 text-white" style="background: var(--gradient-primary);">
    <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10">
        <svg width="100%" height="100%" viewBox="0 0 1000 1000">
            <defs>
                <pattern id="contact-pattern" x="0" y="0" width="80" height="80" patternUnits="userSpaceOnUse">
                    <circle cx="40" cy="40" r="2" fill="white"/>
                    <circle cx="20" cy="20" r="1" fill="white" opacity="0.5"/>
                    <circle cx="60" cy="60" r="1.5" fill="white" opacity="0.7"/>
                </pattern>
            </defs>
            <rect width="1000" height="1000" fill="url(#contact-pattern)"/>
        </svg>
    </div>
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">Contactez-nous</h1>
                <p class="lead mb-4 opacity-75">
                    Notre équipe est disponible 24h/24 pour répondre à toutes vos questions et vous accompagner.
                </p>
                <div class="row">
                    <div class="col-6 col-md-3 mb-3">
                        <div class="text-center">
                            <div class="feature-icon success mb-2 mx-auto">
                                <i class="bi bi-clock fs-3"></i>
                            </div>
                            <div class="fw-bold">Support 24/7</div>
                            <small class="opacity-75">Réponse sous 24h</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-3">
                        <div class="text-center">
                            <div class="feature-icon info mb-2 mx-auto">
                                <i class="bi bi-headset fs-3"></i>
                            </div>
                            <div class="fw-bold">Équipe dédiée</div>
                            <small class="opacity-75">Experts IPTV</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-3">
                        <div class="text-center">
                            <div class="feature-icon warning mb-2 mx-auto">
                                <i class="bi bi-chat-dots fs-3"></i>
                            </div>
                            <div class="fw-bold">Multi-canaux</div>
                            <small class="opacity-75">Email, chat</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-3">
                        <div class="text-center">
                            <div class="feature-icon mb-2 mx-auto" style="background: var(--gradient-danger);">
                                <i class="bi bi-lightning fs-3"></i>
                            </div>
                            <div class="fw-bold">Réponse rapide</div>
                            <small class="opacity-75">Support réactif</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-center">
                <!-- Support Illustration -->
                <svg width="300" height="200" viewBox="0 0 300 200" class="img-fluid">
                    <defs>
                        <linearGradient id="supportGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#82d616"/>
                            <stop offset="100%" style="stop-color:#98ec2d"/>
                        </linearGradient>
                    </defs>
                    
                    <!-- Support person -->
                    <circle cx="150" cy="80" r="40" fill="url(#supportGradient)" opacity="0.8"/>
                    <circle cx="150" cy="70" r="15" fill="rgba(255,255,255,0.9)"/>
                    <rect x="135" y="85" width="30" height="35" rx="15" fill="rgba(255,255,255,0.9)"/>
                    
                    <!-- Headset -->
                    <path d="M130 65 Q150 50 170 65" stroke="rgba(255,255,255,0.9)" stroke-width="3" fill="none"/>
                    <circle cx="125" cy="70" r="8" fill="rgba(255,255,255,0.9)"/>
                    <circle cx="175" cy="70" r="8" fill="rgba(255,255,255,0.9)"/>
                    
                    <!-- Chat bubbles -->
                    <g opacity="0.7">
                        <ellipse cx="80" cy="50" rx="25" ry="15" fill="rgba(255,255,255,0.9)"/>
                        <text x="80" y="55" text-anchor="middle" fill="#344767" font-size="8">Bonjour !</text>
                        
                        <ellipse cx="220" cy="90" rx="30" ry="18" fill="rgba(255,255,255,0.9)"/>
                        <text x="220" y="95" text-anchor="middle" fill="#344767" font-size="7">Comment puis-je</text>
                        <text x="220" y="105" text-anchor="middle" fill="#344767" font-size="7">vous aider ?</text>
                    </g>
                    
                    <!-- Floating help icons -->
                    <g opacity="0.6">
                        <circle cx="50" cy="120" r="12" fill="rgba(255,255,255,0.8)">
                            <animate attributeName="cy" values="120;110;120" dur="3s" repeatCount="indefinite"/>
                        </circle>
                        <text x="50" y="125" text-anchor="middle" fill="#344767" font-size="8">?</text>
                        
                        <circle cx="250" cy="140" r="10" fill="rgba(255,255,255,0.8)">
                            <animate attributeName="cy" values="140;130;140" dur="2s" repeatCount="indefinite"/>
                        </circle>
                        <text x="250" y="145" text-anchor="middle" fill="#344767" font-size="6">!</text>
                    </g>
                </svg>
            </div>
        </div>
    </div>
</section>

<div class="container py-5">
    <div class="row">
        <!-- Formulaire de contact -->
        <div class="col-lg-8 mb-5">
            <div class="card-soft">
                <div class="card-header text-white position-relative overflow-hidden" style="background: var(--gradient-success); border-radius: var(--border-radius-soft-lg) var(--border-radius-soft-lg) 0 0;">
                    <div class="position-relative">
                        <h4 class="mb-0 fw-bold">
                            <i class="bi bi-envelope me-2"></i>Envoyer un message
                        </h4>
                        <small class="opacity-75">Nous vous répondrons dans les 24 heures</small>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('contact.store') }}">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label fw-bold">Nom complet *</label>
                                <input type="text" 
                                       class="form-control form-control-soft @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', auth()->user()->name ?? '') }}" 
                                       placeholder="Votre nom complet"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label fw-bold">Adresse email *</label>
                                <div class="input-group input-group-soft">
                                    <span class="input-group-text">
                                        <i class="bi bi-envelope text-primary"></i>
                                    </span>
                                    <input type="email" 
                                           class="form-control form-control-soft @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email', auth()->user()->email ?? '') }}" 
                                           placeholder="votre@email.com"
                                           required>
                                </div>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="type" class="form-label fw-bold">Type de demande *</label>
                                <select class="form-select form-control-soft @error('type') is-invalid @enderror" 
                                        id="type" 
                                        name="type" 
                                        required>
                                    <option value="">Choisir le type</option>
                                    <option value="contact" {{ old('type') === 'contact' ? 'selected' : '' }}>
                                        💬 Question générale
                                    </option>
                                    <option value="support" {{ old('type') === 'support' ? 'selected' : '' }}>
                                        🛠️ Support technique
                                    </option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="subject" class="form-label fw-bold">Sujet *</label>
                                <input type="text" 
                                       class="form-control form-control-soft @error('subject') is-invalid @enderror" 
                                       id="subject" 
                                       name="subject" 
                                       value="{{ old('subject') }}" 
                                       placeholder="Résumé de votre demande"
                                       required>
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="message" class="form-label fw-bold">Message *</label>
                            <textarea class="form-control form-control-soft @error('message') is-invalid @enderror" 
                                      id="message" 
                                      name="message" 
                                      rows="6" 
                                      placeholder="Décrivez votre demande en détail..."
                                      required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text d-flex justify-content-between">
                                <span>Maximum 2000 caractères</span>
                                <span id="charCount" class="text-muted">0 / 2000</span>
                            </div>
                        </div>
                        
                        <!-- reCAPTCHA -->
                        @if($recaptchaSiteKey && !empty($recaptchaSiteKey))
                            <div class="mb-4 text-center">
                                <div class="g-recaptcha d-inline-block" data-sitekey="{{ $recaptchaSiteKey }}"></div>
                                @error('g-recaptcha-response')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                                @error('recaptcha')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-soft btn-soft-primary btn-lg py-3">
                                <i class="bi bi-send me-2"></i>Envoyer le message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Contact Info -->
            <div class="card-soft mb-4">
                <div class="card-header text-white" style="background: var(--gradient-info); border-radius: var(--border-radius-soft-lg) var(--border-radius-soft-lg) 0 0;">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-info-circle me-2"></i>Autres moyens de contact
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="feature-icon me-3">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <div>
                            <div class="fw-bold">Email</div>
                            <small class="text-muted">{{ App\Models\Setting::get('contact_email', 'contact@iptv2smartv.com') }}</small>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center mb-3">
                        <div class="feature-icon success me-3">
                            <i class="bi bi-clock"></i>
                        </div>
                        <div>
                            <div class="fw-bold">Horaires</div>
                            <small class="text-muted">24h/24 - 7j/7</small>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center">
                        <div class="feature-icon info me-3">
                            <i class="bi bi-lightning"></i>
                        </div>
                        <div>
                            <div class="fw-bold">Délai de réponse</div>
                            <small class="text-muted">Sous 24h maximum</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- FAQ -->
            <div class="card-soft mb-4">
                <div class="card-header text-white" style="background: var(--gradient-warning); border-radius: var(--border-radius-soft-lg) var(--border-radius-soft-lg) 0 0;">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-question-circle me-2"></i>Questions Fréquentes
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item border-0 mb-2" style="border-radius: var(--border-radius-soft);">
                            <h6 class="accordion-header">
                                <button class="accordion-button collapsed small fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" style="border-radius: var(--border-radius-soft);">
                                    Comment installer IPTV ?
                                </button>
                            </h6>
                            <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body small">
                                    Consultez nos tutoriels détaillés pour chaque appareil dans la section dédiée.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item border-0 mb-2" style="border-radius: var(--border-radius-soft);">
                            <h6 class="accordion-header">
                                <button class="accordion-button collapsed small fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq2" style="border-radius: var(--border-radius-soft);">
                                    Problème de connexion ?
                                </button>
                            </h6>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body small">
                                    Vérifiez votre connexion internet et les paramètres de votre application IPTV.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item border-0" style="border-radius: var(--border-radius-soft);">
                            <h6 class="accordion-header">
                                <button class="accordion-button collapsed small fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq3" style="border-radius: var(--border-radius-soft);">
                                    Remboursement possible ?
                                </button>
                            </h6>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body small">
                                    Oui, nous offrons une garantie satisfait ou remboursé de 7 jours.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div class="card-soft">
                <div class="card-header text-white" style="background: var(--gradient-secondary); border-radius: var(--border-radius-soft-lg) var(--border-radius-soft-lg) 0 0;">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-link-45deg me-2"></i>Liens Utiles
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="d-grid gap-2">
                        <a href="{{ route('tutorials.index') }}" class="btn btn-soft-outline">
                            <i class="bi bi-book me-2"></i>Tutoriels d'installation
                        </a>
                        <a href="{{ route('subscriptions.index') }}" class="btn btn-soft-outline" style="border-color: var(--soft-success); color: var(--soft-success);">
                            <i class="bi bi-tv me-2"></i>Nos abonnements
                        </a>
                        <a href="{{ route('resellers.index') }}" class="btn btn-soft-outline" style="border-color: var(--soft-warning); color: var(--soft-warning);">
                            <i class="bi bi-people me-2"></i>Devenir revendeur
                        </a>
                        <a href="{{ route('legal', 'terms') }}" class="btn btn-soft-outline" style="border-color: var(--soft-secondary); color: var(--soft-secondary);">
                            <i class="bi bi-file-text me-2"></i>Conditions générales
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if($recaptchaSiteKey && !empty($recaptchaSiteKey))
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const messageTextarea = document.getElementById('message');
        const charCount = document.getElementById('charCount');
        
        // Character counter with Soft UI styling
        const maxLength = 2000;
        
        function updateCounter() {
            const remaining = maxLength - messageTextarea.value.length;
            const used = messageTextarea.value.length;
            charCount.textContent = `${used} / ${maxLength}`;
            
            if (remaining < 100) {
                charCount.className = 'text-warning fw-bold';
            } else if (remaining < 50) {
                charCount.className = 'text-danger fw-bold';
            } else {
                charCount.className = 'text-muted';
            }
        }
        
        messageTextarea.addEventListener('input', updateCounter);
        updateCounter();
        
        // Enhanced form submission
        form.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="spinner-border spinner-border-sm me-2"></i>Envoi en cours...';
            submitBtn.style.background = 'var(--gradient-warning)';
        });
        
        // Auto-fill subject based on type with enhanced UX
        const typeSelect = document.getElementById('type');
        const subjectInput = document.getElementById('subject');
        
        typeSelect.addEventListener('change', function() {
            if (!subjectInput.value) {
                const placeholders = {
                    'support': 'Ex: Problème de connexion, Installation, Qualité vidéo...',
                    'contact': 'Ex: Question sur les abonnements, Devenir revendeur...'
                };
                subjectInput.placeholder = placeholders[this.value] || 'Résumé de votre demande';
                
                // Add visual feedback
                subjectInput.style.borderColor = this.value ? 'var(--soft-success)' : '';
            }
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