@extends('layouts.soft-ui')

@section('title', 'Mon Tableau de Bord')

@section('content')
<!-- Hero Section -->
<section class="position-relative overflow-hidden py-4" style="background: var(--gradient-primary);">
    <div class="container position-relative">
        <div class="text-center text-white">
            <h1 class="display-6 fw-bold mb-2">Bonjour, {{ $user->name }} !</h1>
            <p class="opacity-75">Gérez vos abonnements et commandes IPTV</p>
        </div>
    </div>
</section>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <!-- Quick Stats -->
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <div class="stat-card text-center">
                        <div class="feature-icon mx-auto mb-3">
                            <i class="bi bi-cart fs-2"></i>
                        </div>
                        <h4 class="fw-bold mb-1">{{ $orders->total() }}</h4>
                        <p class="text-muted mb-0">Commandes</p>
                    </div>
                </div>
                
                <div class="col-md-4 mb-3">
                    <div class="stat-card success text-center">
                        <div class="feature-icon success mx-auto mb-3">
                            <i class="bi bi-check-circle fs-2"></i>
                        </div>
                        <h4 class="fw-bold mb-1">{{ $orders->where('status', 'paid')->count() }}</h4>
                        <p class="text-muted mb-0">Actives</p>
                    </div>
                </div>
                
                <div class="col-md-4 mb-3">
                    <div class="stat-card info text-center">
                        <div class="feature-icon info mx-auto mb-3">
                            <i class="bi bi-tv fs-2"></i>
                        </div>
                        <h4 class="fw-bold mb-1">{{ $orders->where('status', 'paid')->whereNotNull('iptv_code')->count() }}</h4>
                        <p class="text-muted mb-0">Codes IPTV</p>
                    </div>
                </div>
            </div>

            <!-- Orders -->
            <div class="card-soft">
                <div class="card-header text-white" style="background: var(--gradient-primary); border-radius: var(--border-radius-soft-lg) var(--border-radius-soft-lg) 0 0;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-cart me-2"></i>Mes Commandes
                        </h5>
                        <a href="{{ route('subscriptions.index') }}" class="btn btn-sm" style="background: rgba(255,255,255,0.2); color: white;">
                            <i class="bi bi-plus-circle me-1"></i>Nouvelle commande
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
                    @if($orders->count() > 0)
                        @foreach($orders as $order)
                            <div class="card-soft mb-3 p-3" style="background: rgba(203, 12, 159, 0.05);">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 fw-bold">{{ $order->subscription->name }}</h6>
                                        <small class="text-muted">Commande {{ $order->order_number }}</small>
                                        <br>
                                        <small class="text-muted">{{ $order->created_at->format('d/m/Y à H:i') }}</small>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold mb-1">{{ $order->formatted_amount }}</div>
                                        <span class="badge bg-{{ $order->status === 'paid' ? 'success' : ($order->status === 'paid_pending_validation' ? 'warning' : ($order->status === 'pending' ? 'secondary' : 'danger')) }}">
                                            @if($order->status === 'paid_pending_validation')
                                                Validation en cours
                                            @else
                                                {{ ucfirst($order->status) }}
                                            @endif
                                        </span>
                                        @if($order->iptv_code && $order->isPaid())
                                            <br>
                                            <small class="text-success fw-bold">
                                                <i class="bi bi-check-circle me-1"></i>Code IPTV prêt
                                            </small>
                                        @elseif($order->status === 'paid_pending_validation')
                                            <br>
                                            <small class="text-warning">
                                                <i class="bi bi-clock me-1"></i>En cours de validation
                                            </small>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($order->iptv_code && $order->isPaid())
                                    <hr class="my-3">
                                    <div class="alert alert-soft alert-success">
                                        <h6 class="fw-bold mb-2">
                                            <i class="bi bi-key me-2"></i>Votre Code IPTV
                                        </h6>
                                        <div class="input-group">
                                            <input type="text" class="form-control" value="{{ $order->iptv_code }}" readonly id="code_{{ $order->id }}">
                                            <button class="btn btn-outline-success" type="button" onclick="copyCode({{ $order->id }})">
                                                <i class="bi bi-clipboard"></i>
                                            </button>
                                        </div>
                                        @if($order->expires_at)
                                            <small class="text-muted mt-2 d-block">
                                                <i class="bi bi-calendar me-1"></i>
                                                Expire le : {{ $order->expires_at->format('d/m/Y') }}
                                            </small>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                        
                        <div class="mt-4">
                            {{ $orders->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="feature-icon mx-auto mb-4" style="width: 5rem; height: 5rem;">
                                <i class="bi bi-cart-x fs-1"></i>
                            </div>
                            <h5 class="fw-bold mb-3">Aucune commande</h5>
                            <p class="text-muted">Vous n'avez pas encore passé de commande</p>
                            <a href="{{ route('subscriptions.index') }}" class="btn btn-soft btn-soft-primary">
                                <i class="bi bi-tv me-2"></i>Découvrir nos abonnements
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- User Info -->
            <div class="card-soft mb-4">
                <div class="card-header text-white" style="background: var(--gradient-info); border-radius: var(--border-radius-soft-lg) var(--border-radius-soft-lg) 0 0;">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-person me-2"></i>Mon Profil
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="text-center mb-3">
                        <div class="feature-icon mx-auto mb-3">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    </div>
                    <p class="mb-2"><strong>Nom :</strong> {{ $user->name }}</p>
                    <p class="mb-2"><strong>Email :</strong> {{ $user->email }}</p>
                    <p class="mb-3"><strong>Membre depuis :</strong> {{ $user->created_at->format('d/m/Y') }}</p>
                    <div class="d-grid">
                        <a href="{{ route('profile.edit') }}" class="btn btn-soft-outline">
                            <i class="bi bi-pencil me-2"></i>Modifier mon profil
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card-soft">
                <div class="card-header text-white" style="background: var(--gradient-success); border-radius: var(--border-radius-soft-lg) var(--border-radius-soft-lg) 0 0;">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-lightning me-2"></i>Actions Rapides
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="d-grid gap-2">
                        <a href="{{ route('subscriptions.index') }}" class="btn btn-soft btn-soft-primary">
                            <i class="bi bi-tv me-2"></i>Voir les abonnements
                        </a>
                        <a href="{{ route('tutorials.index') }}" class="btn btn-soft-outline" style="border-color: var(--soft-info); color: var(--soft-info);">
                            <i class="bi bi-book me-2"></i>Tutoriels d'installation
                        </a>
                        <a href="{{ route('contact.index') }}" class="btn btn-soft-outline" style="border-color: var(--soft-warning); color: var(--soft-warning);">
                            <i class="bi bi-headset me-2"></i>Contacter le support
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function copyCode(orderId) {
        const input = document.getElementById('code_' + orderId);
        input.select();
        document.execCommand('copy');
        
        // Feedback visuel
        const button = input.nextElementSibling;
        const originalHTML = button.innerHTML;
        button.innerHTML = '<i class="bi bi-check"></i>';
        button.classList.remove('btn-outline-success');
        button.classList.add('btn-success');
        
        setTimeout(() => {
            button.innerHTML = originalHTML;
            button.classList.remove('btn-success');
            button.classList.add('btn-outline-success');
        }, 2000);
        
        // Toast notification
        showToast('Code IPTV copié !', 'success');
    }
    
    function showToast(message, type = 'success') {
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
        
        let toastContainer = document.querySelector('.toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
            document.body.appendChild(toastContainer);
        }
        
        toastContainer.appendChild(toast);
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        toast.addEventListener('hidden.bs.toast', () => {
            toast.remove();
        });
    }

    // Animation des cartes
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.card-soft, .stat-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });
</script>
@endpush