@extends('layouts.admin')

@section('title', 'Abonnement - '.$subscription->name)

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">{{ $subscription->name }} ({{ $subscription->duration_months }} mois)</h4>
        <a href="{{ route('admin.subscriptions.edit', $subscription) }}" class="btn btn-soft btn-soft-primary">Éditer</a>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card stats-card info mb-4">
                <div class="stats-label">Revenu total</div>
                <div class="stats-value">{{ number_format($subscription->total_revenue, 2) }} €</div>
            </div>
            <div class="card stats-card success">
                <div class="stats-label">Commandes payées</div>
                <div class="stats-value">{{ $subscription->orders_count }}</div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card table-card mb-4">
                <div class="card-header">Statistiques 6 derniers mois</div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Mois</th>
                                <th>Commandes</th>
                                <th>Revenu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($monthlyStats as $row)
                                <tr>
                                    <td>{{ $row['month'] }}</td>
                                    <td>{{ $row['orders'] }}</td>
                                    <td>{{ number_format($row['revenue'], 2) }} €</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card table-card">
                <div class="card-header">Dernières commandes</div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Client</th>
                                <th>Montant</th>
                                <th>Statut</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subscription->orders->take(10) as $order)
                                <tr>
                                    <td>{{ $order->order_number }}</td>
                                    <td>{{ optional($order->user)->email ?? $order->customer_email }}</td>
                                    <td>{{ number_format($order->amount, 2) }} €</td>
                                    <td><span class="badge {{ $order->status === 'paid' ? 'bg-success' : 'bg-secondary' }}">{{ $order->status }}</span></td>
                                    <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center py-4">Aucune commande</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


