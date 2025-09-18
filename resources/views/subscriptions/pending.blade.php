@extends('layouts.soft-ui')

@section('title', 'Paiement en cours de validation')

@section('content')
<!-- Hero Section -->
<section class="position-relative overflow-hidden py-5" style="background: var(--gradient-warning);">
    <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10">
        <svg width="100%" height="100%" viewBox="0 0 1000 1000">
            <defs>
                <pattern id="pending-pattern" x="0" y="0" width="60" height="60" patternUnits="userSpaceOnUse">
                    <circle cx="30" cy="30" r="2" fill="white"/>
                    <circle cx="15" cy="45" r="1" fill="white" opacity="0.6"/>
                    <circle cx="45" cy="15" r="1.5" fill="white" opacity="0.8"/>
                </pattern>
            </defs>
            <rect width="1000" height="1000" fill="url(#pending-pattern)"/>
        </svg>
    </div>
    <div class="container position-relative">
        <div class="text-center text-white">
            <div class="feature-icon mx-auto mb-4" style="width: 5rem; height: 5rem; background: rgba(255,255,255,0.2);">
                <i class="bi bi-clock-history" style="font-size: 2.5rem;"></i>
            </div>
            <h1 class="display-5 fw-bold mb-3">Paiement Reçu !</h1>
            <p class="lead opacity-75">Votre commande est en cours de validation par notre équipe</p>
        </div>
    </div>
</section>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Order Status -->
            <div class="card-soft mb-4">
                <div class="card-header text-white" style="background: var(--gradient-success); border-radius: var(--border-radius-soft-lg) var(--border-radius-soft-lg) 0 0;">
                    <h4 class="mb-0 fw-bold">
                        <i class="bi bi-check-circle me-2"></i>Paiement Confirmé
                    </h4>
                    <small class="opacity-75">Votre commande est sécurisée et en cours de traitement</small>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Détails de votre commande</h6>
                            <p class="mb-1"><strong>Numéro :</strong> {{ $order->order_number }}</p>
                            <p class="mb-1"><strong>Date :</strong> {{ $order->created_at->format('d/m/Y à H:i') }}</p>
                            <p class="mb-1"><strong>Abonnement :</strong> {{ $order->subscription->name }}</p>
                            <p class="mb-1"><strong>Montant :</strong> {{ $order->formatted_amount }}</p>
                            <p class="mb-0"><strong>Statut :</strong> 
                                <span class="badge" style="background: var(--gradient-warning); color: white;">
                                    Validation en cours
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Informations client</h6>
                            <p class="mb-1"><strong>Nom :</strong> {{ $order->customer_name }}</p>
                            <p class="mb-1"><strong>Email :</strong> {{ $order->customer_email }}</p>
                            <p class="mb-0"><strong>Durée :</strong> {{ $order->subscription->duration_text }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Process Timeline -->
            <div class="card-soft mb-4">
                <div class="card-header text-white" style="background: var(--gradient-info); border-radius: var(--border-radius-soft-lg) var(--border-radius-soft-lg) 0 0;">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-list-check me-2"></i>Processus de Validation
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-3 text-center mb-3">
                            <div class="feature-icon success mx-auto mb-3">
                                <i class="bi bi-check-lg"></i>
                            </div>
                            <h6 class="fw-bold text-success">1. Paiement</h6>
                            <small class="text-muted">Confirmé ✅</small>
                        </div>
                        
                        <div class="col-md-3 text-center mb-3">
                            <div class="feature-icon warning mx-auto mb-3">
                                <i class="bi bi-gear"></i>
                            </div>
                            <h6 class="fw-bold text-warning">2. Validation</h6>
                            <small class="text-muted">En cours 🔄</small>
                        </div>
                        
                        <div class="col-md-3 text-center mb-3">
                            <div class="feature-icon mx-auto mb-3" style="background: var(--gradient-secondary);">
                                <i class="bi bi-key"></i>
                            </div>
                            <h6 class="fw-bold text-muted">3. Génération</h6>
                            <small class="text-muted">En attente ⏳</small>
                        </div>
                        
                        <div class="col-md-3 text-center mb-3">
                            <div class="feature-icon mx-auto mb-3" style="background: var(--gradient-secondary);">
                                <i class="bi bi-envelope"></i>
                            </div>
                            <h6 class="fw-bold text-muted">4. Envoi</h6>
                            <small class="text-muted">En attente ⏳</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Important Information -->
            <div class="card-soft mb-4">
                <div class="card-body p-4">
                    <div class="alert alert-soft alert-info">
                        <h6 class="fw-bold mb-2">
                            <i class="bi bi-info-circle me-2"></i>Information importante
                        </h6>
                        <ul class="mb-0">
                            <li><strong>Délai de validation :</strong> 2-4 heures en moyenne (maximum 24h)</li>
                            <li><strong>Notification :</strong> Vous recevrez un email dès que vos identifiants seront prêts</li>
                            <li><strong>Support :</strong> Notre équipe est disponible 24/7 pour toute question</li>
                            <li><strong>Garantie :</strong> Satisfait ou remboursé sous 7 jours</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Next Steps -->
            <div class="card-soft">
                <div class="card-header text-white" style="background: var(--gradient-primary); border-radius: var(--border-radius-soft-lg) var(--border-radius-soft-lg) 0 0;">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-arrow-right-circle me-2"></i>En Attendant
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="fw-bold mb-3">Préparez votre installation</h6>
                            <p class="text-muted mb-3">Consultez nos tutoriels pour préparer vos appareils.</p>
                            <a href="{{ route('tutorials.index') }}" class="btn btn-soft btn-soft-info">
                                <i class="bi bi-play-circle me-2"></i>Voir les tutoriels
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="fw-bold mb-3">Questions ?</h6>
                            <p class="text-muted mb-3">Notre équipe est là pour vous accompagner.</p>
                            <a href="{{ route('contact.index') }}" class="btn btn-soft btn-soft-primary">
                                <i class="bi bi-headset me-2"></i>Contacter le support
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="text-center mt-4">
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="{{ route('home') }}" class="btn btn-soft-outline">
                        <i class="bi bi-house me-2"></i>Retour à l'accueil
                    </a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-soft-outline">
                            <i class="bi bi-speedometer2 me-2"></i>Mon tableau de bord
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Animation d'entrée
    document.addEventListener('DOMContentLoaded', function() {
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
