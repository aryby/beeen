@extends('layouts.soft-ui')

@section('title', 'Test Modal Popup')

@section('content')
<div class="container py-5">
    <div class="text-center">
        <h1 class="display-4 fw-bold mb-4">Test Modal Popup</h1>
        <p class="lead mb-4">Testez le modal de commande rapide</p>
        
        <div class="row justify-content-center">
            <div class="col-md-4 mb-3">
                <div class="card-soft p-4">
                    <h5 class="fw-bold mb-3">Test Abonnement</h5>
                    <button type="button" 
                            class="btn btn-soft btn-soft-primary w-100"
                            onclick="openQuickOrderModal('subscription', 2, 'Abonnement 3 Mois', '39.99€', 'Abonnement IPTV 3 mois', '3 mois')">
                        <i class="bi bi-lightning-charge me-2"></i>Test Modal Abonnement
                    </button>
                </div>
            </div>
            
            <div class="col-md-4 mb-3">
                <div class="card-soft p-4">
                    <h5 class="fw-bold mb-3">Test Pack Revendeur</h5>
                    <button type="button" 
                            class="btn btn-soft btn-soft-success w-100"
                            onclick="openQuickOrderModal('reseller_pack', 1, '50 Crédits', '199.99€', 'Pack revendeur avec 50 crédits', '50 crédits IPTV')">
                        <i class="bi bi-lightning-charge me-2"></i>Test Modal Revendeur
                    </button>
                </div>
            </div>
        </div>
        
        <div class="mt-4">
            <a href="{{ route('home') }}" class="btn btn-soft-outline">
                <i class="bi bi-arrow-left me-2"></i>Retour à l'accueil
            </a>
        </div>
    </div>
</div>
@endsection
