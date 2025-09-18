@extends('layouts.admin')

@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')

@section('content')
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="stats-label">Total Commandes</div>
            <div class="stats-value">{{ number_format($stats['total_orders']) }}</div>
            <div class="d-flex align-items-center mt-2">
                <i class="bi bi-cart text-primary me-2"></i>
                <small class="text-muted">{{ $stats['pending_orders'] }} en attente</small>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card success">
            <div class="stats-label">Revenus Total</div>
            <div class="stats-value">{{ number_format($stats['total_revenue'], 2) }}€</div>
            <div class="d-flex align-items-center mt-2">
                <i class="bi bi-currency-euro text-success me-2"></i>
                <small class="text-muted">{{ number_format($stats['monthly_revenue'], 2) }}€ ce mois</small>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card info">
            <div class="stats-label">Clients</div>
            <div class="stats-value">{{ number_format($stats['total_customers']) }}</div>
            <div class="d-flex align-items-center mt-2">
                <i class="bi bi-people text-info me-2"></i>
                <small class="text-muted">{{ $stats['total_resellers'] }} revendeurs</small>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card warning">
            <div class="stats-label">Messages</div>
            <div class="stats-value">{{ number_format($stats['unread_messages']) }}</div>
            <div class="d-flex align-items-center mt-2">
                <i class="bi bi-envelope text-warning me-2"></i>
                <small class="text-muted">Non lus</small>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
    <!-- Revenue Chart -->
    <div class="col-xl-8 mb-4">
        <div class="table-card">
            <div class="card-header">
                <i class="bi bi-bar-chart me-2"></i>Évolution des revenus (12 derniers mois)
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Subscription Stats -->
    <div class="col-xl-4 mb-4">
        <div class="table-card">
            <div class="card-header">
                <i class="bi bi-pie-chart me-2"></i>Abonnements populaires
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="subscriptionChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity Row -->
<div class="row">
    <!-- Recent Orders -->
    <div class="col-xl-6 mb-4">
        <div class="table-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clock-history me-2"></i>Commandes récentes</span>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
            </div>
            <div class="card-body p-0">
                @if($recentOrders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Commande</th>
                                    <th>Client</th>
                                    <th>Abonnement</th>
                                    <th>Montant</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $order) }}" class="text-decoration-none">
                                                {{ $order->order_number }}
                                            </a>
                                            <br>
                                            <small class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</small>
                                        </td>
                                        <td>{{ $order->customer_name }}</td>
                                        <td>{{ $order->subscription->name }}</td>
                                        <td>{{ $order->formatted_amount }}</td>
                                        <td>
                                            <span class="badge bg-{{ $order->status === 'paid' ? 'success' : ($order->status === 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-cart-x fs-1 text-muted"></i>
                        <p class="text-muted">Aucune commande récente</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Recent Messages -->
    <div class="col-xl-6 mb-4">
        <div class="table-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-envelope me-2"></i>Messages récents</span>
                <a href="{{ route('admin.messages.index') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
            </div>
            <div class="card-body">
                @if($unreadMessages->count() > 0)
                    @foreach($unreadMessages as $message)
                        <div class="d-flex align-items-start mb-3 {{ !$loop->last ? 'border-bottom pb-3' : '' }}">
                            <div class="flex-shrink-0">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="bi bi-envelope"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">
                                    <a href="{{ route('admin.messages.show', $message) }}" class="text-decoration-none">
                                        {{ $message->name }}
                                    </a>
                                </h6>
                                <p class="mb-1 small">{{ Str::limit($message->subject, 50) }}</p>
                                <small class="text-muted">{{ $message->created_at->diffForHumans() }}</small>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="badge bg-{{ $message->type === 'support' ? 'danger' : 'info' }}">
                                    {{ ucfirst($message->type) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-envelope-check fs-1 text-muted"></i>
                        <p class="text-muted">Aucun message non lu</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Reseller Activity -->
@if($resellerActivity->count() > 0)
<div class="row">
    <div class="col-12 mb-4">
        <div class="table-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-activity me-2"></i>Activité des revendeurs</span>
                <a href="{{ route('admin.resellers.index') }}" class="btn btn-sm btn-outline-primary">Gérer les revendeurs</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Revendeur</th>
                                <th>Type</th>
                                <th>Crédits</th>
                                <th>Montant</th>
                                <th>Description</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($resellerActivity as $transaction)
                                <tr>
                                    <td>{{ $transaction->reseller->user->name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $transaction->type === 'purchase_pack' ? 'success' : 'warning' }}">
                                            {{ $transaction->type_name }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-{{ $transaction->credits_amount > 0 ? 'success' : 'danger' }}">
                                            {{ $transaction->formatted_credits_amount }}
                                        </span>
                                    </td>
                                    <td>{{ $transaction->formatted_money_amount }}</td>
                                    <td>{{ Str::limit($transaction->description, 30) }}</td>
                                    <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode(array_column($monthlyRevenue, 'month')) !!},
                datasets: [{
                    label: 'Revenus (€)',
                    data: {!! json_encode(array_column($monthlyRevenue, 'revenue')) !!},
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value + '€';
                            }
                        }
                    }
                }
            }
        });

        // Subscription Chart
        const subscriptionCtx = document.getElementById('subscriptionChart').getContext('2d');
        const subscriptionChart = new Chart(subscriptionCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($subscriptionStats->pluck('name')) !!},
                datasets: [{
                    data: {!! json_encode($subscriptionStats->pluck('orders_count')) !!},
                    backgroundColor: [
                        '#0d6efd',
                        '#198754',
                        '#ffc107',
                        '#dc3545',
                        '#0dcaf0',
                        '#6c757d'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    });
</script>
@endpush
