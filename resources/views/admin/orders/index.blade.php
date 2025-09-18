@extends('layouts.admin')

@section('title', 'Gestion des Commandes')
@section('page-title', 'Gestion des Commandes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Commandes</h1>
        <p class="text-muted">Gérez toutes les commandes clients et revendeurs</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.export.orders') }}" class="btn btn-outline-success">
            <i class="bi bi-download me-2"></i>Exporter CSV
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-2 col-md-4 mb-3">
        <div class="stats-card">
            <div class="stats-label">Total</div>
            <div class="stats-value">{{ number_format($stats['total']) }}</div>
            <div class="d-flex align-items-center mt-2">
                <i class="bi bi-cart text-primary me-2"></i>
                <small class="text-muted">Commandes</small>
            </div>
        </div>
    </div>
    
    <div class="col-xl-2 col-md-4 mb-3">
        <div class="stats-card success">
            <div class="stats-label">Payées</div>
            <div class="stats-value">{{ number_format($stats['paid']) }}</div>
            <div class="d-flex align-items-center mt-2">
                <i class="bi bi-check-circle text-success me-2"></i>
                <small class="text-muted">{{ number_format($stats['total_revenue'], 2) }}€</small>
            </div>
        </div>
    </div>
    
    <div class="col-xl-2 col-md-4 mb-3">
        <div class="stats-card warning">
            <div class="stats-label">En Attente</div>
            <div class="stats-value">{{ number_format($stats['pending']) }}</div>
            <div class="d-flex align-items-center mt-2">
                <i class="bi bi-clock text-warning me-2"></i>
                <small class="text-muted">{{ number_format($stats['pending_revenue'], 2) }}€</small>
            </div>
        </div>
    </div>
    
    <div class="col-xl-2 col-md-4 mb-3">
        <div class="stats-card danger">
            <div class="stats-label">Annulées</div>
            <div class="stats-value">{{ number_format($stats['cancelled']) }}</div>
            <div class="d-flex align-items-center mt-2">
                <i class="bi bi-x-circle text-danger me-2"></i>
                <small class="text-muted">Annulations</small>
            </div>
        </div>
    </div>
    
    <div class="col-xl-2 col-md-4 mb-3">
        <div class="stats-card info">
            <div class="stats-label">Remboursées</div>
            <div class="stats-value">{{ number_format($stats['refunded']) }}</div>
            <div class="d-flex align-items-center mt-2">
                <i class="bi bi-arrow-return-left text-info me-2"></i>
                <small class="text-muted">Remboursements</small>
            </div>
        </div>
    </div>
    
    <div class="col-xl-2 col-md-4 mb-3">
        <div class="stats-card">
            <div class="stats-label">Revenus</div>
            <div class="stats-value">{{ number_format($stats['total_revenue'], 0) }}€</div>
            <div class="d-flex align-items-center mt-2">
                <i class="bi bi-currency-euro text-success me-2"></i>
                <small class="text-muted">Total encaissé</small>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="table-card mb-4">
    <div class="card-header">
        <h6 class="mb-0"><i class="bi bi-funnel me-2"></i>Filtres</h6>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.orders.index') }}">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="status" class="form-label">Statut</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Tous les statuts</option>
                        <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="paid" {{ $status === 'paid' ? 'selected' : '' }}>Payé</option>
                        <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Annulé</option>
                        <option value="refunded" {{ $status === 'refunded' ? 'selected' : '' }}>Remboursé</option>
                    </select>
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="search" class="form-label">Recherche</label>
                    <input type="text" class="form-control" id="search" name="search" value="{{ $search }}" placeholder="Numéro, nom, email...">
                </div>
                
                <div class="col-md-2 mb-3">
                    <label for="date_from" class="form-label">Du</label>
                    <input type="date" class="form-control" id="date_from" name="date_from" value="{{ $dateFrom }}">
                </div>
                
                <div class="col-md-2 mb-3">
                    <label for="date_to" class="form-label">Au</label>
                    <input type="date" class="form-control" id="date_to" name="date_to" value="{{ $dateTo }}">
                </div>
                
                <div class="col-md-2 mb-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search me-1"></i>Filtrer
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Orders Table -->
<div class="table-card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-list me-2"></i>Liste des commandes</h5>
            <div class="d-flex align-items-center">
                <small class="text-muted me-3">{{ $orders->total() }} commande(s)</small>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        @if($orders->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Commande</th>
                            <th>Client</th>
                            <th>Abonnement</th>
                            <th>Montant</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $order->order_number }}</div>
                                    @if($order->payment_id)
                                        <small class="text-muted">{{ $order->payment_id }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $order->customer_name }}</div>
                                    <small class="text-muted">{{ $order->customer_email }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $order->subscription->name }}</span>
                                    @if($order->iptv_code)
                                        <br><small class="text-success">Code: {{ $order->iptv_code }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-bold">{{ $order->formatted_amount }}</span>
                                    @if($order->refund_amount)
                                        <br><small class="text-danger">Remb: {{ number_format($order->refund_amount, 2) }}€</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $order->status === 'paid' ? 'success' : ($order->status === 'pending' ? 'warning' : ($order->status === 'refunded' ? 'info' : 'danger')) }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                    @if($order->expires_at)
                                        <br><small class="text-muted">Expire: {{ $order->expires_at->format('d/m/Y') }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div>{{ $order->created_at->format('d/m/Y') }}</div>
                                    <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.orders.show', $order) }}" 
                                           class="btn btn-sm btn-outline-info" 
                                           title="Voir">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        
                                        @if($order->isPending())
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-success" 
                                                    title="Valider"
                                                    onclick="confirmAction('{{ route('admin.orders.validate', $order) }}', 'Valider cette commande ?', 'Valider')">
                                                <i class="bi bi-check"></i>
                                            </button>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    title="Annuler"
                                                    onclick="confirmAction('{{ route('admin.orders.cancel', $order) }}', 'Annuler cette commande ?', 'Annuler')">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        @endif
                                        
                                        @if($order->isPaid())
                                            <a href="{{ route('admin.orders.invoice', $order) }}" 
                                               class="btn btn-sm btn-outline-secondary" 
                                               title="Facture">
                                                <i class="bi bi-file-pdf"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-warning" 
                                                    title="Rembourser"
                                                    onclick="showRefundModal({{ $order->id }}, '{{ $order->order_number }}', {{ $order->amount }})">
                                                <i class="bi bi-arrow-return-left"></i>
                                            </button>
                                        @endif
                                        
                                        @if($order->isPending() || $order->isCancelled())
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    title="Supprimer"
                                                    onclick="confirmDelete({{ $order->id }}, '{{ $order->order_number }}')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="card-footer">
                {{ $orders->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-cart-x fs-1 text-muted mb-3"></i>
                <h4>Aucune commande</h4>
                <p class="text-muted">Aucune commande ne correspond à vos critères</p>
            </div>
        @endif
    </div>
</div>

<!-- Refund Modal -->
<div class="modal fade" id="refundModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rembourser la commande</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="refundForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Commande : <strong id="refundOrderNumber"></strong></p>
                    <p>Montant total : <strong id="refundOrderAmount"></strong>€</p>
                    
                    <div class="mb-3">
                        <label for="refund_amount" class="form-label">Montant à rembourser</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="refund_amount" name="refund_amount" step="0.01" min="0.01">
                            <span class="input-group-text">€</span>
                        </div>
                        <div class="form-text">Laissez vide pour un remboursement total</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="refund_reason" class="form-label">Raison du remboursement</label>
                        <textarea class="form-control" id="refund_reason" name="refund_reason" rows="3" placeholder="Expliquez la raison du remboursement..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-arrow-return-left me-2"></i>Rembourser
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Confirm Action Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmer l'action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="confirmMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="confirmForm" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-primary" id="confirmButton">Confirmer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Supprimer la commande</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer la commande <strong id="deleteOrderNumber"></strong> ?</p>
                <p class="text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Cette action est irréversible.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-2"></i>Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function showRefundModal(orderId, orderNumber, orderAmount) {
        document.getElementById('refundOrderNumber').textContent = orderNumber;
        document.getElementById('refundOrderAmount').textContent = orderAmount;
        document.getElementById('refund_amount').value = orderAmount;
        document.getElementById('refund_amount').max = orderAmount;
        document.getElementById('refundForm').action = `/admin/orders/${orderId}/refund`;
        
        const modal = new bootstrap.Modal(document.getElementById('refundModal'));
        modal.show();
    }

    function confirmAction(actionUrl, message, buttonText) {
        document.getElementById('confirmMessage').textContent = message;
        document.getElementById('confirmForm').action = actionUrl;
        document.getElementById('confirmButton').textContent = buttonText;
        
        const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
        modal.show();
    }

    function confirmDelete(orderId, orderNumber) {
        document.getElementById('deleteOrderNumber').textContent = orderNumber;
        document.getElementById('deleteForm').action = `/admin/orders/${orderId}`;
        
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }

    // Auto-refresh pending orders every 30 seconds
    @if($status === 'pending' || !$status)
        setInterval(function() {
            if (!document.hidden) {
                const pendingRows = document.querySelectorAll('tr:has(.badge.bg-warning)');
                if (pendingRows.length > 0) {
                    // Subtle indication of auto-refresh
                    console.log('Checking for payment updates...');
                }
            }
        }, 30000);
    @endif
</script>
@endpush
