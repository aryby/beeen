@extends('layouts.admin')

@section('title', 'Gestion des Abonnements')
@section('page-title', 'Gestion des Abonnements')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Abonnements</h1>
        <p class="text-muted">Gérez les formules d'abonnements disponibles sur le site</p>
    </div>
    <a href="{{ route('admin.subscriptions.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Nouvel abonnement
    </a>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-label">Total Abonnements</div>
            <div class="stats-value">{{ $subscriptions->count() }}</div>
            <div class="d-flex align-items-center mt-2">
                <i class="bi bi-tv text-primary me-2"></i>
                <small class="text-muted">{{ $subscriptions->where('is_active', true)->count() }} actifs</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card success">
            <div class="stats-label">Revenus Total</div>
            <div class="stats-value">{{ number_format($subscriptions->sum('total_revenue'), 0) }}€</div>
            <div class="d-flex align-items-center mt-2">
                <i class="bi bi-currency-euro text-success me-2"></i>
                <small class="text-muted">Toutes formules</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card info">
            <div class="stats-label">Commandes Total</div>
            <div class="stats-value">{{ number_format($subscriptions->sum('orders_count')) }}</div>
            <div class="d-flex align-items-center mt-2">
                <i class="bi bi-cart text-info me-2"></i>
                <small class="text-muted">Payées</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card warning">
            <div class="stats-label">Plus Populaire</div>
            <div class="stats-value">{{ $subscriptions->sortByDesc('orders_count')->first()?->name ?? 'N/A' }}</div>
            <div class="d-flex align-items-center mt-2">
                <i class="bi bi-star text-warning me-2"></i>
                <small class="text-muted">{{ $subscriptions->sortByDesc('orders_count')->first()?->orders_count ?? 0 }} commandes</small>
            </div>
        </div>
    </div>
</div>

<!-- Subscriptions Table -->
<div class="table-card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-list me-2"></i>Liste des abonnements</h5>
            <div class="d-flex gap-2">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="showInactive">
                    <label class="form-check-label" for="showInactive">
                        Afficher les inactifs
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        @if($subscriptions->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nom</th>
                            <th>Durée</th>
                            <th>Prix</th>
                            <th>Prix/Mois</th>
                            <th>Commandes</th>
                            <th>Revenus</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subscriptions as $subscription)
                            <tr class="subscription-row" data-active="{{ $subscription->is_active ? 'true' : 'false' }}">
                                <td>
                                    <div class="fw-bold">{{ $subscription->name }}</div>
                                    @if($subscription->description)
                                        <small class="text-muted">{{ Str::limit($subscription->description, 50) }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $subscription->duration_text }}</span>
                                </td>
                                <td>
                                    <span class="fw-bold text-primary">{{ $subscription->formatted_price }}</span>
                                </td>
                                <td>
                                    @if($subscription->duration_months > 1)
                                        <span class="text-success">{{ number_format($subscription->price / $subscription->duration_months, 2) }}€</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ number_format($subscription->orders_count) }}</span>
                                </td>
                                <td>
                                    <span class="fw-bold">{{ number_format($subscription->total_revenue, 2) }}€</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $subscription->is_active ? 'success' : 'secondary' }}">
                                        {{ $subscription->is_active ? 'Actif' : 'Inactif' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.subscriptions.show', $subscription) }}" 
                                           class="btn btn-sm btn-outline-info" 
                                           title="Voir">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.subscriptions.edit', $subscription) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if($subscription->orders_count == 0)
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    title="Supprimer"
                                                    onclick="confirmDelete({{ $subscription->id }}, '{{ $subscription->name }}')">
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
        @else
            <div class="text-center py-5">
                <i class="bi bi-tv fs-1 text-muted mb-3"></i>
                <h4>Aucun abonnement</h4>
                <p class="text-muted">Commencez par créer votre premier abonnement</p>
                <a href="{{ route('admin.subscriptions.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Créer un abonnement
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer l'abonnement <strong id="subscriptionName"></strong> ?</p>
                <p class="text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Cette action est irréversible.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Toggle inactive subscriptions
    document.getElementById('showInactive').addEventListener('change', function() {
        const rows = document.querySelectorAll('.subscription-row');
        const showInactive = this.checked;
        
        rows.forEach(row => {
            const isActive = row.getAttribute('data-active') === 'true';
            if (!isActive && !showInactive) {
                row.style.display = 'none';
            } else {
                row.style.display = '';
            }
        });
    });

    // Initialize - hide inactive by default
    document.addEventListener('DOMContentLoaded', function() {
        const showInactive = document.getElementById('showInactive').checked;
        if (!showInactive) {
            const rows = document.querySelectorAll('.subscription-row');
            rows.forEach(row => {
                const isActive = row.getAttribute('data-active') === 'true';
                if (!isActive) {
                    row.style.display = 'none';
                }
            });
        }
    });

    function confirmDelete(subscriptionId, subscriptionName) {
        document.getElementById('subscriptionName').textContent = subscriptionName;
        document.getElementById('deleteForm').action = `/admin/subscriptions/${subscriptionId}`;
        
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }

    // Add some visual feedback for actions
    document.querySelectorAll('.btn-outline-info, .btn-outline-primary').forEach(btn => {
        btn.addEventListener('click', function() {
            this.innerHTML = '<i class="spinner-border spinner-border-sm me-2"></i>Chargement...';
        });
    });
</script>
@endpush
