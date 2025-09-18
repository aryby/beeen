@extends('layouts.soft-ui')

@section('title', 'Tableau de Bord Revendeur')

@section('content')
<div class="container py-5">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Tableau de Bord Revendeur</h1>
            <p class="text-muted">Bonjour {{ auth()->user()->name }}, gérez vos crédits et générez des codes IPTV</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('reseller.transactions') }}" class="btn btn-outline-primary">
                <i class="bi bi-list-ul me-2"></i>Historique
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2" style="border-left: 4px solid #4e73df;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Crédits Disponibles</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['total_credits']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-wallet2 fa-2x text-gray-300" style="font-size: 2em; color: #dddfeb;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2" style="border-left: 4px solid #1cc88a;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Crédits Achetés</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['total_purchased']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-cart-plus fa-2x text-gray-300" style="font-size: 2em; color: #dddfeb;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2" style="border-left: 4px solid #36b9cc;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Codes Générés</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['codes_generated']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-qr-code fa-2x text-gray-300" style="font-size: 2em; color: #dddfeb;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2" style="border-left: 4px solid #f6c23e;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Crédits Utilisés</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['total_used']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-graph-up fa-2x text-gray-300" style="font-size: 2em; color: #dddfeb;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Code Generator -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Générer un Code IPTV</h5>
                </div>
                <div class="card-body">
                    @if($reseller->credits > 0)
                        <form id="generateCodeForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="subscription_months" class="form-label">Durée de l'abonnement *</label>
                                    <select class="form-select" id="subscription_months" name="subscription_months" required>
                                        <option value="">Choisir la durée</option>
                                        <option value="1">1 mois (1 crédit)</option>
                                        <option value="3">3 mois (3 crédits)</option>
                                        <option value="6">6 mois (6 crédits)</option>
                                        <option value="12">12 mois (12 crédits)</option>
                                    </select>
                                    <div class="form-text">Chaque mois consomme 1 crédit</div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="customer_info" class="form-label">Info client (optionnel)</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="customer_info" 
                                           name="customer_info" 
                                           placeholder="Nom du client, référence...">
                                    <div class="form-text">Pour votre suivi personnel</div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">
                                        <i class="bi bi-wallet2 me-1"></i>
                                        Crédits disponibles: <strong>{{ $reseller->credits }}</strong>
                                    </small>
                                </div>
                                <button type="submit" class="btn btn-success" id="generateBtn">
                                    <i class="bi bi-lightning me-2"></i>Générer le code
                                </button>
                            </div>
                        </form>
                        
                        <!-- Result -->
                        <div id="generateResult" class="mt-4" style="display: none;">
                            <div class="alert alert-success">
                                <h6><i class="bi bi-check-circle me-2"></i>Code généré avec succès !</h6>
                                <div class="input-group mt-3">
                                    <input type="text" class="form-control" id="generatedCode" readonly>
                                    <button class="btn btn-outline-success" type="button" onclick="copyCode()">
                                        <i class="bi bi-clipboard"></i>
                                    </button>
                                </div>
                                <small class="text-muted mt-2 d-block">
                                    Crédits restants: <span id="creditsRemaining"></span>
                                </small>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Crédits insuffisants</strong><br>
                            Vous n'avez plus de crédits disponibles. Achetez un nouveau pack pour continuer.
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Recharge Credits -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="bi bi-plus-square me-2"></i>Recharger des Crédits</h6>
                </div>
                <div class="card-body">
                    @if($availablePacks->count() > 0)
                        @foreach($availablePacks->take(3) as $pack)
                            <div class="d-flex justify-content-between align-items-center mb-3 p-3 border rounded">
                                <div>
                                    <div class="fw-bold">{{ $pack->name }}</div>
                                    <small class="text-muted">{{ $pack->formatted_price_per_credit }}</small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-primary">{{ $pack->formatted_price }}</div>
                                    <form method="POST" action="{{ route('reseller.renew-pack', $pack) }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-primary">
                                            Acheter
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                        
                        <div class="text-center">
                            <a href="{{ route('resellers.index') }}" class="btn btn-outline-secondary btn-sm">
                                Voir tous les packs
                            </a>
                        </div>
                    @else
                        <p class="text-muted">Aucun pack disponible actuellement.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="card shadow">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-clock-history me-2"></i>Transactions Récentes</h6>
                <a href="{{ route('reseller.transactions') }}" class="btn btn-sm btn-outline-primary">
                    Voir tout
                </a>
            </div>
        </div>
        <div class="card-body">
            @if($recentTransactions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Description</th>
                                <th>Crédits</th>
                                <th>Montant</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentTransactions as $transaction)
                                <tr>
                                    <td>
                                        <span class="badge bg-{{ $transaction->type === 'purchase_pack' ? 'success' : 'warning' }}">
                                            {{ $transaction->type_name }}
                                        </span>
                                    </td>
                                    <td>{{ $transaction->description }}</td>
                                    <td>
                                        <span class="text-{{ $transaction->credits_amount > 0 ? 'success' : 'danger' }}">
                                            {{ $transaction->formatted_credits_amount }}
                                        </span>
                                    </td>
                                    <td>{{ $transaction->formatted_money_amount }}</td>
                                    <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="bi bi-inbox fs-1 text-muted mb-3"></i>
                    <p class="text-muted">Aucune transaction pour le moment</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('generateCodeForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const btn = document.getElementById('generateBtn');
        const originalText = btn.innerHTML;
        
        // Disable button and show loading
        btn.disabled = true;
        btn.innerHTML = '<i class="spinner-border spinner-border-sm me-2"></i>Génération...';
        
        try {
            const formData = new FormData(this);
            const response = await fetch('{{ route("reseller.generate-code") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                // Show success result
                document.getElementById('generatedCode').value = result.iptv_code;
                document.getElementById('creditsRemaining').textContent = result.credits_remaining;
                document.getElementById('generateResult').style.display = 'block';
                
                // Update credits display
                document.querySelector('.h5.mb-0.font-weight-bold.text-gray-800').textContent = result.credits_remaining;
                
                // Reset form
                this.reset();
                
                // Show success toast
                showToast(result.message, 'success');
            } else {
                showToast(result.message, 'error');
            }
        } catch (error) {
            showToast('Erreur lors de la génération du code', 'error');
        } finally {
            // Re-enable button
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    });
    
    function copyCode() {
        const codeInput = document.getElementById('generatedCode');
        codeInput.select();
        document.execCommand('copy');
        
        const btn = event.target.closest('button');
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check"></i>';
        btn.classList.remove('btn-outline-success');
        btn.classList.add('btn-success');
        
        setTimeout(() => {
            btn.innerHTML = originalHTML;
            btn.classList.remove('btn-success');
            btn.classList.add('btn-outline-success');
        }, 2000);
        
        showToast('Code copié dans le presse-papiers !', 'success');
    }
    
    function showToast(message, type = 'success') {
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0`;
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        
        // Add to DOM
        let toastContainer = document.querySelector('.toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
            document.body.appendChild(toastContainer);
        }
        
        toastContainer.appendChild(toast);
        
        // Show toast
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        // Remove after hide
        toast.addEventListener('hidden.bs.toast', () => {
            toast.remove();
        });
    }
</script>
@endpush
