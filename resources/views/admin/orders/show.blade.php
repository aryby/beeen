@extends('layouts.admin')

@section('title', 'Commande ' . $order->order_number)
@section('page-title', 'Détails de la Commande')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Tableau de bord</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Commandes</a></li>
                <li class="breadcrumb-item active">{{ $order->order_number }}</li>
            </ol>
        </nav>

        <!-- Order Header -->
        <div class="table-card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="bi bi-receipt me-2"></i>Commande {{ $order->order_number }}
                    </h4>
                    <span class="badge bg-{{ $order->status === 'paid' ? 'success' : ($order->status === 'pending' ? 'warning' : ($order->status === 'refunded' ? 'info' : 'danger')) }} fs-6">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-3">Informations de commande</h6>
                        <p><strong>Numéro :</strong> {{ $order->order_number }}</p>
                        <p><strong>Date :</strong> {{ $order->created_at->format('d/m/Y à H:i') }}</p>
                        <p><strong>Statut :</strong> 
                            <span class="badge bg-{{ $order->status === 'paid' ? 'success' : ($order->status === 'pending' ? 'warning' : ($order->status === 'refunded' ? 'info' : 'danger')) }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </p>
                        <p><strong>Abonnement :</strong> {{ $order->subscription->name }}</p>
                        <p><strong>Montant :</strong> {{ $order->formatted_amount }}</p>
                        @if($order->payment_id)
                            <p><strong>ID PayPal :</strong> <code>{{ $order->payment_id }}</code></p>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-3">Informations client</h6>
                        <p><strong>Nom :</strong> {{ $order->customer_name }}</p>
                        <p><strong>Email :</strong> {{ $order->customer_email }}</p>
                        @if($order->customer_address)
                            <p><strong>Adresse :</strong><br>{{ $order->customer_address }}</p>
                        @endif
                        @if($order->user)
                            <p><strong>Compte utilisateur :</strong> 
                                <a href="#" class="text-decoration-none">{{ $order->user->name }}</a>
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- IPTV Details -->
        @if($order->iptv_code)
            <div class="table-card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-key me-2"></i>Détails IPTV</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Code IPTV :</strong> <code>{{ $order->iptv_code }}</code></p>
                            @if($order->expires_at)
                                <p><strong>Date d'expiration :</strong> {{ $order->expires_at->format('d/m/Y à H:i') }}</p>
                                <p><strong>Temps restant :</strong> 
                                    @if($order->expires_at->isFuture())
                                        <span class="text-success">{{ $order->expires_at->diffForHumans() }}</span>
                                    @else
                                        <span class="text-danger">Expiré</span>
                                    @endif
                                </p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <p><strong>Durée :</strong> {{ $order->subscription->duration_text }}</p>
                            <p><strong>Activé le :</strong> {{ $order->updated_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Refund Details -->
        @if($order->isRefunded())
            <div class="table-card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="bi bi-arrow-return-left me-2"></i>Détails du remboursement</h5>
                </div>
                <div class="card-body">
                    <p><strong>Montant remboursé :</strong> {{ number_format($order->refund_amount, 2) }}€</p>
                    <p><strong>Date :</strong> {{ $order->refunded_at?->format('d/m/Y à H:i') }}</p>
                    @if($order->refund_reason)
                        <p><strong>Raison :</strong> {{ $order->refund_reason }}</p>
                    @endif
                </div>
            </div>
        @endif
    </div>
    
    <!-- Actions Sidebar -->
    <div class="col-lg-4">
        <div class="table-card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-tools me-2"></i>Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($order->isPending())
                        <button type="button" 
                                class="btn btn-success" 
                                onclick="confirmAction('{{ route('admin.orders.validate', $order) }}', 'Valider cette commande et envoyer les identifiants IPTV ?', 'Valider')">
                            <i class="bi bi-check-circle me-2"></i>Valider la commande
                        </button>
                        <button type="button" 
                                class="btn btn-danger" 
                                onclick="confirmAction('{{ route('admin.orders.cancel', $order) }}', 'Annuler cette commande ?', 'Annuler')">
                            <i class="bi bi-x-circle me-2"></i>Annuler la commande
                        </button>
                    @endif
                    
                    @if($order->isPaid())
                        <a href="{{ route('admin.orders.invoice', $order) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-file-pdf me-2"></i>Télécharger la facture
                        </a>
                        <button type="button" 
                                class="btn btn-warning" 
                                onclick="showRefundModal({{ $order->id }}, '{{ $order->order_number }}', {{ $order->amount }})">
                            <i class="bi bi-arrow-return-left me-2"></i>Rembourser
                        </button>
                    @endif
                    
                    <!-- Send Message to Customer -->
                    <button type="button" 
                            class="btn btn-outline-primary" 
                            onclick="showMessageModal('{{ $order->customer_name }}', '{{ $order->customer_email }}', {{ $order->id }})">
                        <i class="bi bi-envelope me-2"></i>Envoyer un message
                    </button>
                    
                    @if($order->isPending() || $order->isCancelled())
                        <button type="button" 
                                class="btn btn-outline-danger" 
                                onclick="confirmDelete({{ $order->id }}, '{{ $order->order_number }}')">
                            <i class="bi bi-trash me-2"></i>Supprimer
                        </button>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Customer Actions -->
        <div class="table-card mt-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-person me-2"></i>Actions client</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="mailto:{{ $order->customer_email }}?subject=Votre commande {{ $order->order_number }}" 
                       class="btn btn-outline-primary">
                        <i class="bi bi-envelope me-2"></i>Contacter le client
                    </a>
                    @if($order->isPaid() && $order->iptv_code)
                        <button type="button" class="btn btn-outline-info" onclick="copyIptvCode()">
                            <i class="bi bi-clipboard me-2"></i>Copier le code IPTV
                        </button>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Timeline -->
        <div class="table-card mt-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-clock-history me-2"></i>Historique</h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Commande créée</h6>
                            <small class="text-muted">{{ $order->created_at->format('d/m/Y à H:i') }}</small>
                        </div>
                    </div>
                    
                    @if($order->isPaid())
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Paiement confirmé</h6>
                                <small class="text-muted">{{ $order->updated_at->format('d/m/Y à H:i') }}</small>
                            </div>
                        </div>
                    @endif
                    
                    @if($order->iptv_code)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Code IPTV généré</h6>
                                <small class="text-muted">{{ $order->updated_at->format('d/m/Y à H:i') }}</small>
                            </div>
                        </div>
                    @endif
                    
                    @if($order->isRefunded())
                        <div class="timeline-item">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Remboursé</h6>
                                <small class="text-muted">{{ $order->refunded_at?->format('d/m/Y à H:i') }}</small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Send Message Modal -->
<div class="modal fade" id="messageModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Envoyer un message au client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="messageForm" method="POST" action="{{ route('admin.orders.send-message', $order) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="message_recipient" class="form-label">Destinataire</label>
                        <input type="text" class="form-control" id="message_recipient" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label for="message_subject" class="form-label">Sujet</label>
                        <input type="text" class="form-control" id="message_subject" name="subject" required placeholder="Sujet du message...">
                    </div>
                    
                    <div class="mb-3">
                        <label for="message_type" class="form-label">Type de message</label>
                        <select class="form-select" id="message_type" name="type" required>
                            <option value="order_update">Mise à jour commande</option>
                            <option value="support">Support client</option>
                            <option value="notification">Notification</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="message_content" class="form-label">Message</label>
                        <textarea class="form-control" id="message_content" name="message" rows="6" required placeholder="Votre message au client..."></textarea>
                        <div class="form-text">Le message sera envoyé par email au client</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send me-2"></i>Envoyer le message
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function copyIptvCode() {
        const code = '{{ $order->iptv_code }}';
        navigator.clipboard.writeText(code).then(function() {
            // Show success feedback
            const btn = event.target.closest('button');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-check me-2"></i>Copié !';
            btn.classList.remove('btn-outline-info');
            btn.classList.add('btn-success');
            
            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.classList.remove('btn-success');
                btn.classList.add('btn-outline-info');
            }, 2000);
        });
    }

    function showMessageModal(customerName, customerEmail, orderId) {
        document.getElementById('message_recipient').value = customerName + ' (' + customerEmail + ')';
        document.getElementById('message_subject').value = 'Concernant votre commande {{ $order->order_number }}';
        
        const modal = new bootstrap.Modal(document.getElementById('messageModal'));
        modal.show();
    }

    // Form submission for message
    document.getElementById('messageForm').addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="spinner-border spinner-border-sm me-2"></i>Envoi...';
    });
</script>
@endpush

@push('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 2rem;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 0.75rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #dee2e6;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .timeline-marker {
        position: absolute;
        left: -2.25rem;
        top: 0.25rem;
        width: 1rem;
        height: 1rem;
        border-radius: 50%;
        border: 2px solid white;
    }

    .timeline-content h6 {
        margin-bottom: 0.25rem;
        font-weight: 600;
    }
</style>
@endpush
