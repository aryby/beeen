@extends('layouts.app')

@section('title', 'Mon Tableau de Bord')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-1">Bonjour, {{ $user->name }}</h1>
                    <p class="text-muted">Gérez vos abonnements et commandes</p>
                </div>
                <a href="{{ route('subscriptions.index') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Nouvel abonnement
                </a>
            </div>

            <!-- Orders -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-cart me-2"></i>Mes Commandes</h5>
                </div>
                <div class="card-body">
                    @if($orders->count() > 0)
                        @foreach($orders as $order)
                            <div class="d-flex justify-content-between align-items-center p-3 border rounded mb-3">
                                <div>
                                    <h6 class="mb-1">{{ $order->subscription->name }}</h6>
                                    <small class="text-muted">Commande {{ $order->order_number }}</small>
                                    <br>
                                    <small class="text-muted">{{ $order->created_at->format('d/m/Y à H:i') }}</small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold">{{ $order->formatted_amount }}</div>
                                    <span class="badge bg-{{ $order->status === 'paid' ? 'success' : ($order->status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                    @if($order->iptv_code && $order->isPaid())
                                        <br>
                                        <small class="text-success">
                                            <i class="bi bi-check-circle me-1"></i>Actif
                                        </small>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        
                        {{ $orders->links() }}
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-cart-x fs-1 text-muted mb-3"></i>
                            <h5>Aucune commande</h5>
                            <p class="text-muted">Vous n'avez pas encore passé de commande</p>
                            <a href="{{ route('subscriptions.index') }}" class="btn btn-primary">
                                Découvrir nos abonnements
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- User Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Mon Profil</h6>
                </div>
                <div class="card-body">
                    <p><strong>Nom:</strong> {{ $user->name }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Membre depuis:</strong> {{ $user->created_at->format('d/m/Y') }}</p>
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-pencil me-1"></i>Modifier
                    </a>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Actions Rapides</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('subscriptions.index') }}" class="btn btn-primary">
                            <i class="bi bi-tv me-2"></i>Voir les abonnements
                        </a>
                        <a href="{{ route('tutorials.index') }}" class="btn btn-outline-info">
                            <i class="bi bi-book me-2"></i>Tutoriels d'installation
                        </a>
                        <a href="{{ route('contact.index') }}" class="btn btn-outline-warning">
                            <i class="bi bi-headset me-2"></i>Contacter le support
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
