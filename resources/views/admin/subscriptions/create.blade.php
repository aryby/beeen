@extends('layouts.admin')

@section('title', 'Créer un Abonnement')
@section('page-title', 'Créer un Abonnement')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Tableau de bord</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.subscriptions.index') }}">Abonnements</a></li>
                <li class="breadcrumb-item active">Créer</li>
            </ol>
        </nav>

        <div class="table-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Nouvel abonnement</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.subscriptions.store') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Nom de l'abonnement *</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   placeholder="ex: 1 Mois, 3 Mois, etc."
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="duration_months" class="form-label">Durée (en mois) *</label>
                            <input type="number" 
                                   class="form-control @error('duration_months') is-invalid @enderror" 
                                   id="duration_months" 
                                   name="duration_months" 
                                   value="{{ old('duration_months') }}" 
                                   min="1" 
                                   max="60"
                                   placeholder="ex: 1, 3, 6, 12"
                                   required>
                            @error('duration_months')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Durée de l'abonnement en mois (1-60)</div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Prix (€) *</label>
                            <div class="input-group">
                                <input type="number" 
                                       class="form-control @error('price') is-invalid @enderror" 
                                       id="price" 
                                       name="price" 
                                       value="{{ old('price') }}" 
                                       step="0.01" 
                                       min="0" 
                                       max="9999.99"
                                       placeholder="ex: 15.99"
                                       required>
                                <span class="input-group-text">€</span>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">Prix total de l'abonnement</div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Prix par mois (calculé)</label>
                            <div class="input-group">
                                <input type="text" 
                                       class="form-control" 
                                       id="price_per_month" 
                                       readonly 
                                       placeholder="0.00">
                                <span class="input-group-text">€/mois</span>
                            </div>
                            <div class="form-text">Prix mensuel équivalent</div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="4" 
                                  placeholder="Description de l'abonnement (optionnel)">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Description affichée sur le site (optionnel)</div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="is_active" 
                                   name="is_active" 
                                   value="1" 
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Abonnement actif
                            </label>
                        </div>
                        <div class="form-text">Si désactivé, l'abonnement ne sera pas visible sur le site</div>
                    </div>
                    
                    <!-- Preview Card -->
                    <div class="mb-4">
                        <h6>Aperçu :</h6>
                        <div class="card" id="preview-card" style="max-width: 300px;">
                            <div class="card-body text-center">
                                <h5 class="card-title" id="preview-name">Nom de l'abonnement</h5>
                                <div class="display-6 text-primary mb-2" id="preview-price">0.00€</div>
                                <p class="text-muted" id="preview-duration">Durée</p>
                                <div class="small text-success" id="preview-monthly" style="display: none;">
                                    Soit <span id="preview-monthly-price">0.00</span>€/mois
                                </div>
                                <div class="mt-3">
                                    <button type="button" class="btn btn-primary" disabled>Commander</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Retour
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Créer l'abonnement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.getElementById('name');
        const durationInput = document.getElementById('duration_months');
        const priceInput = document.getElementById('price');
        const pricePerMonthInput = document.getElementById('price_per_month');
        
        // Preview elements
        const previewName = document.getElementById('preview-name');
        const previewPrice = document.getElementById('preview-price');
        const previewDuration = document.getElementById('preview-duration');
        const previewMonthly = document.getElementById('preview-monthly');
        const previewMonthlyPrice = document.getElementById('preview-monthly-price');
        
        function updatePreview() {
            const name = nameInput.value || 'Nom de l\'abonnement';
            const duration = parseInt(durationInput.value) || 0;
            const price = parseFloat(priceInput.value) || 0;
            
            // Update preview
            previewName.textContent = name;
            previewPrice.textContent = price.toFixed(2) + '€';
            
            if (duration > 0) {
                const durationText = duration === 1 ? '1 mois' : duration + ' mois';
                previewDuration.textContent = durationText;
                
                if (duration > 1) {
                    const monthlyPrice = price / duration;
                    pricePerMonthInput.value = monthlyPrice.toFixed(2);
                    previewMonthlyPrice.textContent = monthlyPrice.toFixed(2);
                    previewMonthly.style.display = 'block';
                } else {
                    pricePerMonthInput.value = price.toFixed(2);
                    previewMonthly.style.display = 'none';
                }
            } else {
                previewDuration.textContent = 'Durée';
                pricePerMonthInput.value = '';
                previewMonthly.style.display = 'none';
            }
        }
        
        // Event listeners
        nameInput.addEventListener('input', updatePreview);
        durationInput.addEventListener('input', updatePreview);
        priceInput.addEventListener('input', updatePreview);
        
        // Initial update
        updatePreview();
        
        // Form validation
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const duration = parseInt(durationInput.value);
            const price = parseFloat(priceInput.value);
            
            if (duration <= 0) {
                e.preventDefault();
                alert('La durée doit être supérieure à 0');
                durationInput.focus();
                return;
            }
            
            if (price <= 0) {
                e.preventDefault();
                alert('Le prix doit être supérieur à 0');
                priceInput.focus();
                return;
            }
            
            // Disable submit button to prevent double submission
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="spinner-border spinner-border-sm me-2"></i>Création...';
        });
    });
</script>
@endpush
