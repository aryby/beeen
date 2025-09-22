@extends('layouts.admin')

@section('title', 'Détails Revendeur')
@section('page-title', 'Détails Revendeur')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Informations du revendeur -->
        <div class="table-card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-person me-2"></i>{{ $reseller->user->name }}
                    </h5>
                    <div class="d-flex gap-2">
                        <form method="POST" action="{{ route('admin.resellers.toggle-status', $reseller) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-soft {{ $reseller->is_active ? 'btn-soft-warning' : 'btn-soft-success' }}">
                                <i class="bi bi-{{ $reseller->is_active ? 'pause' : 'play' }} me-1"></i>
                                {{ $reseller->is_active ? 'Désactiver' : 'Activer' }}
                            </button>
                        </form>
                        <a href="{{ route('admin.resellers.index') }}" class="btn btn-soft btn-soft-outline">
                            <i class="bi bi-arrow-left me-1"></i>Retour
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="p-4">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-3">Informations personnelles</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold">Nom:</td>
                                <td>{{ $reseller->user->name }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Email:</td>
                                <td>{{ $reseller->user->email }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Date d'inscription:</td>
                                <td>{{ $reseller->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Statut:</td>
                                <td>
                                    <span class="badge badge-soft {{ $reseller->is_active ? 'bg-success' : 'bg-danger' }}">
                                        {{ $reseller->is_active ? 'Actif' : 'Inactif' }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-3">Statistiques des crédits</h6>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <div class="stats-card text-center">
                                    <div class="stats-value text-primary">{{ $reseller->credits }}</div>
                                    <div class="stats-label">Crédits Actuels</div>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="stats-card text-center success">
                                    <div class="stats-value">{{ $reseller->total_credits_purchased }}</div>
                                    <div class="stats-label">Total Acheté</div>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="stats-card text-center warning">
                                    <div class="stats-value">{{ $reseller->total_credits_used }}</div>
                                    <div class="stats-label">Total Utilisé</div>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="stats-card text-center info">
                                    <div class="stats-value">{{ $reseller->transactions_count }}</div>
                                    <div class="stats-label">Transactions</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Historique des transactions -->
        <div class="table-card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-clock-history me-2"></i>Historique des Transactions
                </h5>
            </div>
            
            <div class="p-0">
                @forelse($reseller->transactions as $transaction)
                    <div class="p-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="fw-bold">{{ $transaction->description }}</div>
                                <small class="text-muted">{{ $transaction->created_at->format('d/m/Y H:i') }}</small>
                                @if($transaction->reference)
                                    <div class="mt-1">
                                        <small class="text-muted">Référence: <code>{{ $transaction->reference }}</code></small>
                                    </div>
                                @endif
                            </div>
                            <div class="text-end">
                                <div class="fw-bold {{ $transaction->credits_amount > 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $transaction->credits_amount > 0 ? '+' : '' }}{{ $transaction->credits_amount }} crédits
                                </div>
                                @if($transaction->money_amount)
                                    <small class="text-muted">{{ number_format($transaction->money_amount, 2) }}€</small>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center">
                        <i class="bi bi-clock-history fs-1 text-muted"></i>
                        <p class="text-muted mt-2">Aucune transaction trouvée</p>
                    </div>
                @endforelse
            </div>
        </div>
        
        <!-- Commandes récentes -->
        <div class="table-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-cart me-2"></i>Commandes Récentes
                </h5>
            </div>
            
            <div class="p-0">
                @forelse($recentOrders as $order)
                    <div class="p-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="fw-bold">{{ $order->order_number }}</div>
                                <small class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</small>
                                @if($order->resellerPack)
                                    <div class="mt-1">
                                        <small class="text-muted">{{ $order->resellerPack->name }} - {{ $order->resellerPack->credits_amount }} crédits</small>
                                    </div>
                                @endif
                            </div>
                            <div class="text-end">
                                <div class="fw-bold">{{ number_format($order->amount, 2) }}€</div>
                                <div>
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
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center">
                        <i class="bi bi-cart-x fs-1 text-muted"></i>
                        <p class="text-muted mt-2">Aucune commande trouvée</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Actions rapides -->
        <div class="table-card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-gear me-2"></i>Actions Rapides
                </h6>
            </div>
            <div class="p-3">
                <a href="{{ route('admin.resellers.index') }}" class="btn btn-soft btn-soft-outline w-100 mb-2">
                    <i class="bi bi-arrow-left me-2"></i>Retour à la liste
                </a>
                
                <button type="button" class="btn btn-soft btn-soft-info w-100 mb-2" 
                        data-bs-toggle="modal" data-bs-target="#addCreditsModal">
                    <i class="bi bi-plus-circle me-2"></i>Ajouter des crédits
                </button>
                
                <button type="button" class="btn btn-soft btn-soft-warning w-100" 
                        data-bs-toggle="modal" data-bs-target="#removeCreditsModal">
                    <i class="bi bi-dash-circle me-2"></i>Retirer des crédits
                </button>
            </div>
        </div>
        
        <!-- Graphique des crédits -->
        <div class="table-card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-pie-chart me-2"></i>Répartition des Crédits
                </h6>
            </div>
            <div class="p-3">
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Crédits utilisés</span>
                        <span class="fw-bold">{{ $reseller->total_credits_used }}</span>
                    </div>
                    <div class="progress mt-1" style="height: 8px;">
                        <div class="progress-bar bg-warning" 
                             style="width: {{ $reseller->total_credits_purchased > 0 ? ($reseller->total_credits_used / $reseller->total_credits_purchased) * 100 : 0 }}%"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Crédits restants</span>
                        <span class="fw-bold text-primary">{{ $reseller->credits }}</span>
                    </div>
                    <div class="progress mt-1" style="height: 8px;">
                        <div class="progress-bar bg-primary" 
                             style="width: {{ $reseller->total_credits_purchased > 0 ? ($reseller->credits / $reseller->total_credits_purchased) * 100 : 0 }}%"></div>
                    </div>
                </div>
                
                <div class="text-center">
                    <small class="text-muted">
                        Taux d'utilisation: 
                        {{ $reseller->total_credits_purchased > 0 ? number_format(($reseller->total_credits_used / $reseller->total_credits_purchased) * 100, 1) : 0 }}%
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal ajouter des crédits -->
<div class="modal fade" id="addCreditsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter des crédits</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.resellers.add-credits', $reseller) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre de crédits à ajouter</label>
                        <input type="number" name="credits" class="form-control form-control-soft" 
                               min="1" max="1000" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description (optionnel)</label>
                        <input type="text" name="description" class="form-control form-control-soft" 
                               placeholder="Ex: Ajout manuel par l'admin">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-soft-outline" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-soft btn-soft-info">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal retirer des crédits -->
<div class="modal fade" id="removeCreditsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Retirer des crédits</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.resellers.remove-credits', $reseller) }}">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-soft alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Attention:</strong> Cette action retirera définitivement des crédits du compte.
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nombre de crédits à retirer</label>
                        <input type="number" name="credits" class="form-control form-control-soft" 
                               min="1" max="{{ $reseller->credits }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description (optionnel)</label>
                        <input type="text" name="description" class="form-control form-control-soft" 
                               placeholder="Ex: Retrait manuel par l'admin">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-soft-outline" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-soft btn-soft-warning">Retirer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
