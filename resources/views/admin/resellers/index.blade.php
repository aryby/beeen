@extends('layouts.admin')

@section('title', 'Gestion des Revendeurs')
@section('page-title', 'Gestion des Revendeurs')

@section('content')
<div class="row">
    <!-- Statistiques -->
    <div class="col-12 mb-4">
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="stats-value">{{ $stats['total_orders'] }}</div>
                    <div class="stats-label">Commandes Total</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card success">
                    <div class="stats-value">{{ $stats['paid_orders'] }}</div>
                    <div class="stats-label">Commandes Payées</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card warning">
                    <div class="stats-value">{{ $stats['pending_orders'] }}</div>
                    <div class="stats-label">En Attente</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card info">
                    <div class="stats-value">{{ number_format($stats['total_revenue'], 2) }}€</div>
                    <div class="stats-label">Chiffre d'Affaires</div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="stats-value">{{ $stats['total_resellers'] }}</div>
                    <div class="stats-label">Total Revendeurs</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card success">
                    <div class="stats-value">{{ $stats['active_resellers'] }}</div>
                    <div class="stats-label">Revendeurs Actifs</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card info">
                    <div class="stats-value">{{ $stats['total_credits_sold'] }}</div>
                    <div class="stats-label">Crédits Vendus</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card warning">
                    <div class="stats-value">{{ $stats['total_credits_used'] }}</div>
                    <div class="stats-label">Crédits Utilisés</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Commandes de Revendeurs -->
    <div class="col-lg-8 mb-4">
        <div class="table-card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-cart me-2"></i>Commandes de Revendeurs
                    </h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.resellers.export-orders', request()->query()) }}" class="btn btn-soft btn-soft-outline btn-sm">
                            <i class="bi bi-download me-1"></i>Exporter
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Filtres -->
            <div class="p-3 border-bottom">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <select name="status" class="form-control form-control-soft">
                            <option value="">Tous les statuts</option>
                            <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="paid" {{ $status === 'paid' ? 'selected' : '' }}>Payé</option>
                            <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Annulé</option>
                            <option value="refunded" {{ $status === 'refunded' ? 'selected' : '' }}>Remboursé</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control form-control-soft" 
                               placeholder="Rechercher..." value="{{ $search }}">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="date_from" class="form-control form-control-soft" 
                               value="{{ $dateFrom }}">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="date_to" class="form-control form-control-soft" 
                               value="{{ $dateTo }}">
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-soft btn-soft-primary w-100">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Commande</th>
                            <th>Revendeur</th>
                            <th>Pack</th>
                            <th>Montant</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($resellerOrders as $order)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $order->order_number }}</div>
                                    <small class="text-muted">{{ $order->customer_email }}</small>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $order->customer_name }}</div>
                                    @if($order->user)
                                        <small class="text-muted">{{ $order->user->email }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $order->resellerPack->name ?? 'N/A' }}</div>
                                    <small class="text-muted">{{ $order->resellerPack->credits_amount ?? 0 }} crédits</small>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ number_format($order->amount, 2) }}€</div>
                                </td>
                                <td>
                                    @switch($order->status)
                                        @case('pending')
                                            <span class="badge badge-soft bg-warning">En attente</span>
                                            @break
                                        @case('paid')
                                            <span class="badge badge-soft bg-success">Payé</span>
                                            @break
                                        @case('cancelled')
                                            <span class="badge badge-soft bg-danger">Annulé</span>
                                            @break
                                        @case('refunded')
                                            <span class="badge badge-soft bg-info">Remboursé</span>
                                            @break
                                        @default
                                            <span class="badge badge-soft bg-secondary">{{ $order->status }}</span>
                                    @endswitch
                                </td>
                                <td>
                                    <div>{{ $order->created_at->format('d/m/Y') }}</div>
                                    <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.resellers.show-order', $order) }}" 
                                           class="btn btn-soft btn-soft-outline btn-sm">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        
                                        @if($order->status === 'pending')
                                            <form method="POST" action="{{ route('admin.resellers.validate-order', $order) }}" 
                                                  class="d-inline" onsubmit="return confirm('Valider cette commande ?')">
                                                @csrf
                                                <button type="submit" class="btn btn-soft btn-soft-success btn-sm">
                                                    <i class="bi bi-check"></i>
                                                </button>
                                            </form>
                                            
                                            <form method="POST" action="{{ route('admin.resellers.cancel-order', $order) }}" 
                                                  class="d-inline" onsubmit="return confirm('Annuler cette commande ?')">
                                                @csrf
                                                <button type="submit" class="btn btn-soft btn-soft-danger btn-sm">
                                                    <i class="bi bi-x"></i>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        @if($order->status === 'paid')
                                            <button type="button" class="btn btn-soft btn-soft-warning btn-sm" 
                                                    data-bs-toggle="modal" data-bs-target="#refundModal{{ $order->id }}">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        @endif
                                    </div>
                                    
                                    <!-- Modal de remboursement -->
                                    @if($order->status === 'paid')
                                        <div class="modal fade" id="refundModal{{ $order->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Rembourser la commande</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form method="POST" action="{{ route('admin.resellers.refund-order', $order) }}">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label">Montant du remboursement</label>
                                                                <input type="number" name="refund_amount" 
                                                                       class="form-control form-control-soft" 
                                                                       value="{{ $order->amount }}" 
                                                                       step="0.01" min="0.01" max="{{ $order->amount }}">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Raison du remboursement</label>
                                                                <textarea name="refund_reason" 
                                                                          class="form-control form-control-soft" 
                                                                          rows="3" 
                                                                          placeholder="Optionnel"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-soft-outline" data-bs-dismiss="modal">Annuler</button>
                                                            <button type="submit" class="btn btn-soft btn-soft-warning">Rembourser</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="bi bi-cart-x fs-1 text-muted"></i>
                                    <p class="text-muted mt-2">Aucune commande de revendeur trouvée</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($resellerOrders->hasPages())
                <div class="p-3">
                    {{ $resellerOrders->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
    
    <!-- Liste des Revendeurs -->
    <div class="col-lg-4 mb-4">
        <div class="table-card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-people me-2"></i>Clients Revendeurs
                    </h5>
                    <a href="{{ route('admin.resellers.export-resellers') }}" class="btn btn-soft btn-soft-outline btn-sm">
                        <i class="bi bi-download me-1"></i>Exporter
                    </a>
                </div>
            </div>
            
            <div class="p-0">
                @forelse($resellers as $reseller)
                    <div class="p-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="fw-bold">{{ $reseller->user->name }}</div>
                                <small class="text-muted">{{ $reseller->user->email }}</small>
                                <div class="mt-2">
                                    <span class="badge badge-soft {{ $reseller->is_active ? 'bg-success' : 'bg-danger' }}">
                                        {{ $reseller->is_active ? 'Actif' : 'Inactif' }}
                                    </span>
                                    <span class="badge badge-soft bg-info ms-1">
                                        {{ $reseller->credits }} crédits
                                    </span>
                                </div>
                            </div>
                            <div class="btn-group-vertical" role="group">
                                <a href="{{ route('admin.resellers.show-reseller', $reseller) }}" 
                                   class="btn btn-soft btn-soft-outline btn-sm">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.resellers.toggle-status', $reseller) }}" 
                                      class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-soft {{ $reseller->is_active ? 'btn-soft-warning' : 'btn-soft-success' }} btn-sm">
                                        <i class="bi bi-{{ $reseller->is_active ? 'pause' : 'play' }}"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <div class="mt-2">
                            <small class="text-muted">
                                <i class="bi bi-credit-card me-1"></i>
                                Acheté: {{ $reseller->total_credits_purchased }} | 
                                <i class="bi bi-arrow-right me-1"></i>
                                Utilisé: {{ $reseller->total_credits_used }}
                            </small>
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center">
                        <i class="bi bi-people fs-1 text-muted"></i>
                        <p class="text-muted mt-2">Aucun revendeur trouvé</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
