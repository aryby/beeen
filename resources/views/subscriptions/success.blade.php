@extends('layouts.app')

@section('title', 'Paiement réussi - Merci pour votre commande')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Success Message -->
            <div class="text-center mb-5">
                <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 100px; height: 100px;">
                    <i class="bi bi-check-lg" style="font-size: 3rem;"></i>
                </div>
                <h1 class="display-5 fw-bold text-success mb-3">Paiement réussi !</h1>
                <p class="lead text-muted">Merci pour votre confiance. Votre abonnement IPTV est maintenant actif.</p>
            </div>
            
            <!-- Order Details -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-receipt me-2"></i>Détails de votre commande</h4>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-2">Informations de commande</h6>
                            <p class="mb-1"><strong>Numéro :</strong> {{ $order->order_number }}</p>
                            <p class="mb-1"><strong>Date :</strong> {{ $order->created_at->format('d/m/Y à H:i') }}</p>
                            <p class="mb-1"><strong>Statut :</strong> 
                                <span class="badge bg-success">{{ ucfirst($order->status) }}</span>
                            </p>
                            <p class="mb-1"><strong>Abonnement :</strong> {{ $order->subscription->name }}</p>
                            <p class="mb-0"><strong>Montant :</strong> {{ $order->formatted_amount }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-2">Informations client</h6>
                            <p class="mb-1"><strong>Nom :</strong> {{ $order->customer_name }}</p>
                            <p class="mb-1"><strong>Email :</strong> {{ $order->customer_email }}</p>
                            @if($order->expires_at)
                                <p class="mb-0"><strong>Expire le :</strong> 
                                    <span class="text-primary fw-bold">{{ $order->expires_at->format('d/m/Y') }}</span>
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- IPTV Credentials -->
            @if($order->iptv_code)
                <div class="card shadow-sm mb-4 border-success">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0"><i class="bi bi-key me-2"></i>Vos identifiants IPTV</h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Important :</strong> Conservez précieusement ces informations. Elles vous permettent d'accéder à votre service IPTV.
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Code IPTV</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="iptvCode" value="{{ $order->iptv_code }}" readonly>
                                    <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('iptvCode')">
                                        <i class="bi bi-clipboard"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Ces informations ont également été envoyées par email à :</strong> {{ $order->customer_email }}
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Next Steps -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0"><i class="bi bi-list-check me-2"></i>Prochaines étapes</h4>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">1. Installation</h6>
                            <p class="mb-3">Consultez nos tutoriels détaillés pour installer le service sur votre appareil.</p>
                            <a href="{{ route('tutorials.index') }}" class="btn btn-info">
                                <i class="bi bi-play-circle me-2"></i>Voir les tutoriels
                            </a>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">2. Support</h6>
                            <p class="mb-3">Besoin d'aide ? Notre équipe est disponible 24h/24 pour vous accompagner.</p>
                            <a href="{{ route('contact.index') }}" class="btn btn-outline-info">
                                <i class="bi bi-headset me-2"></i>Contacter le support
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="text-center">
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">
                            <i class="bi bi-speedometer2 me-2"></i>Mon tableau de bord
                        </a>
                    @endauth
                    <a href="{{ route('subscriptions.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-repeat me-2"></i>Renouveler mon abonnement
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-house me-2"></i>Retour à l'accueil
                    </a>
                </div>
            </div>
            
            <!-- Additional Info -->
            <div class="row mt-5">
                <div class="col-md-4 text-center mb-3">
                    <i class="bi bi-shield-check text-success fs-1 mb-2 d-block"></i>
                    <h6 class="fw-bold">Service Légal</h6>
                    <small class="text-muted">100% conforme aux réglementations</small>
                </div>
                <div class="col-md-4 text-center mb-3">
                    <i class="bi bi-headset text-primary fs-1 mb-2 d-block"></i>
                    <h6 class="fw-bold">Support 24/7</h6>
                    <small class="text-muted">Une équipe dédiée à votre service</small>
                </div>
                <div class="col-md-4 text-center mb-3">
                    <i class="bi bi-arrow-repeat text-info fs-1 mb-2 d-block"></i>
                    <h6 class="fw-bold">Garantie</h6>
                    <small class="text-muted">Satisfait ou remboursé 7 jours</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function copyToClipboard(elementId) {
        const element = document.getElementById(elementId);
        element.select();
        element.setSelectionRange(0, 99999); // Pour mobile
        document.execCommand('copy');
        
        // Feedback visuel
        const button = element.nextElementSibling;
        const originalHTML = button.innerHTML;
        button.innerHTML = '<i class="bi bi-check"></i>';
        button.classList.remove('btn-outline-secondary');
        button.classList.add('btn-success');
        
        setTimeout(() => {
            button.innerHTML = originalHTML;
            button.classList.remove('btn-success');
            button.classList.add('btn-outline-secondary');
        }, 2000);
        
        // Toast notification
        showToast('Code copié dans le presse-papiers !', 'success');
    }
    
    function showToast(message, type = 'success') {
        // Créer le toast
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type} border-0`;
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi bi-check-circle me-2"></i>${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        
        // Ajouter au DOM
        let toastContainer = document.querySelector('.toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
            document.body.appendChild(toastContainer);
        }
        
        toastContainer.appendChild(toast);
        
        // Initialiser et afficher le toast
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        // Nettoyer après fermeture
        toast.addEventListener('hidden.bs.toast', () => {
            toast.remove();
        });
    }
    
    // Animation d'entrée
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 200);
        });
    });
</script>
@endpush
