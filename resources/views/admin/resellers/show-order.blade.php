@extends('layouts.admin')

@section('title', 'Détails Commande Revendeur')
@section('page-title', 'Détails Commande Revendeur')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="table-card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-cart me-2"></i>Commande {{ $order->order_number }}
                    </h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.resellers.index') }}" class="btn btn-soft btn-soft-outline">
                            <i class="bi bi-arrow-left me-1"></i>Retour
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="p-4">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-3">Informations de la commande</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold">Numéro de commande:</td>
                                <td>{{ $order->order_number }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Date de commande:</td>
                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Statut:</td>
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
                            </tr>
                            <tr>
                                <td class="fw-bold">Montant:</td>
                                <td class="fw-bold text-primary">{{ number_format($order->amount, 2) }}€</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Méthode de paiement:</td>
                                <td>{{ ucfirst($order->payment_method) }}</td>
                            </tr>
                            @if($order->payment_id)
                                <tr>
                                    <td class="fw-bold">ID de paiement:</td>
                                    <td><code>{{ $order->payment_id }}</code></td>
                                </tr>
                            @endif
                        </table>
                    </div>
                    
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-3">Informations du revendeur</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold">Nom:</td>
                                <td>{{ $order->customer_name }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Email:</td>
                                <td>{{ $order->customer_email }}</td>
                            </tr>
                            @if($order->user)
                                <tr>
                                    <td class="fw-bold">Compte utilisateur:</td>
                                    <td>
                                        @if($order->user->reseller)
                                            <a href="{{ route('admin.resellers.show-reseller', $order->user->reseller) }}" 
                                               class="text-decoration-none">
                                                {{ $order->user->name }}
                                            </a>
                                        @else
                                            {{ $order->user->name }}
                                            <small class="text-muted">(Pas de compte revendeur)</small>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
                
                <hr>
                
                <div class="row">
                    <div class="col-12">
                        <h6 class="fw-bold mb-3">Détails du pack de crédits</h6>
                        @if($order->resellerPack)
                            <div class="card-soft p-3" style="background: rgba(203, 12, 159, 0.1);">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="fw-bold">{{ $order->resellerPack->name }}</div>
                                        <div class="text-muted">{{ $order->resellerPack->description }}</div>
                                    </div>
                                    <div class="col-md-6 text-md-end">
                                        <div class="fw-bold fs-4 text-primary">{{ $order->resellerPack->credits_amount }} crédits</div>
                                        <div class="text-muted">{{ number_format($order->amount, 2) }}€</div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-soft alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                Pack de crédits non trouvé ou supprimé
                            </div>
                        @endif
                    </div>
                </div>
                
                @if($order->refunded_at)
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <h6 class="fw-bold mb-3">Informations de remboursement</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold">Date de remboursement:</td>
                                    <td>{{ $order->refunded_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Montant remboursé:</td>
                                    <td class="text-danger">{{ number_format($order->refund_amount, 2) }}€</td>
                                </tr>
                                @if($order->refund_reason)
                                    <tr>
                                        <td class="fw-bold">Raison:</td>
                                        <td>{{ $order->refund_reason }}</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Actions -->
        <div class="table-card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-gear me-2"></i>Actions
                </h6>
            </div>
            <div class="p-3">
                @if($order->status === 'pending')
                    <form method="POST" action="{{ route('admin.resellers.validate-order', $order) }}" 
                          class="mb-3" onsubmit="return confirm('Valider cette commande ? Les crédits seront ajoutés au compte revendeur.')">
                        @csrf
                        <button type="submit" class="btn btn-soft btn-soft-success w-100">
                            <i class="bi bi-check me-2"></i>Valider la commande
                        </button>
                    </form>
                    
                    <form method="POST" action="{{ route('admin.resellers.cancel-order', $order) }}" 
                          class="mb-3" onsubmit="return confirm('Annuler cette commande ?')">
                        @csrf
                        <button type="submit" class="btn btn-soft btn-soft-danger w-100">
                            <i class="bi bi-x me-2"></i>Annuler la commande
                        </button>
                    </form>
                @endif
                
                @if($order->status === 'paid')
                    <button type="button" class="btn btn-soft btn-soft-warning w-100 mb-3" 
                            data-bs-toggle="modal" data-bs-target="#refundModal">
                        <i class="bi bi-arrow-counterclockwise me-2"></i>Rembourser
                    </button>
                @endif
                
                <a href="{{ route('admin.resellers.index') }}" class="btn btn-soft btn-soft-outline w-100">
                    <i class="bi bi-arrow-left me-2"></i>Retour à la liste
                </a>
            </div>
        </div>
        
        <!-- Détails PayPal -->
        @if($paypalDetails && !empty($paypalDetails))
            <div class="table-card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-paypal me-2"></i>Détails PayPal
                    </h6>
                </div>
                <div class="p-3">
                    <div class="mb-2">
                        <small class="text-muted">État du paiement:</small>
                        <div class="fw-bold">{{ $paypalDetails['state'] ?? 'N/A' }}</div>
                    </div>
                    
                    @if(isset($paypalDetails['purchase_units'][0]['payments']['captures'][0]))
                        @php $capture = $paypalDetails['purchase_units'][0]['payments']['captures'][0]; @endphp
                        <div class="mb-2">
                            <small class="text-muted">ID de capture:</small>
                            <div class="fw-bold"><code>{{ $capture['id'] ?? 'N/A' }}</code></div>
                        </div>
                        
                        <div class="mb-2">
                            <small class="text-muted">Montant capturé:</small>
                            <div class="fw-bold">{{ $capture['amount']['value'] ?? 'N/A' }} {{ $capture['amount']['currency_code'] ?? '' }}</div>
                        </div>
                        
                        <div class="mb-2">
                            <small class="text-muted">Date de capture:</small>
                            <div class="fw-bold">{{ isset($capture['create_time']) ? \Carbon\Carbon::parse($capture['create_time'])->format('d/m/Y H:i') : 'N/A' }}</div>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modal de remboursement -->
@if($order->status === 'paid')
    <div class="modal fade" id="refundModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Rembourser la commande</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('admin.resellers.refund-order', $order) }}">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-soft alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Attention:</strong> Le remboursement retirera automatiquement les crédits du compte revendeur.
                        </div>
                        
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
@endsection
