@extends('layouts.admin')

@section('title', 'Modifier le Tutoriel')
@section('page-title', 'Modifier le Tutoriel')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="table-card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-pencil me-2"></i>Modifier: {{ $tutorial->title }}
                    </h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.tutorials.show', $tutorial) }}" class="btn btn-soft btn-soft-outline">
                            <i class="bi bi-eye me-1"></i>Voir
                        </a>
                        <a href="{{ route('admin.tutorials.index') }}" class="btn btn-soft btn-soft-outline">
                            <i class="bi bi-arrow-left me-1"></i>Retour
                        </a>
                    </div>
                </div>
            </div>
            
            <form method="POST" action="{{ route('admin.tutorials.update', $tutorial) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="p-4">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-4">
                                <label for="title" class="form-label fw-bold">Titre du tutoriel *</label>
                                <input type="text" 
                                       class="form-control form-control-soft @error('title') is-invalid @enderror" 
                                       id="title" 
                                       name="title" 
                                       value="{{ old('title', $tutorial->title) }}" 
                                       placeholder="Ex: Installation sur Smart TV Samsung"
                                       required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-4">
                                <label for="device_type" class="form-label fw-bold">Type d'appareil *</label>
                                <select class="form-control form-control-soft @error('device_type') is-invalid @enderror" 
                                        id="device_type" 
                                        name="device_type" 
                                        required>
                                    <option value="">Sélectionner...</option>
                                    @foreach($deviceTypes as $key => $label)
                                        <option value="{{ $key }}" {{ old('device_type', $tutorial->device_type) === $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('device_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="intro" class="form-label fw-bold">Description *</label>
                        <textarea class="form-control form-control-soft @error('intro') is-invalid @enderror" 
                                  id="intro" 
                                  name="intro" 
                                  rows="4" 
                                  placeholder="Décrivez brièvement le tutoriel et ce que l'utilisateur va apprendre..."
                                  required>{{ old('intro', $tutorial->intro) }}</textarea>
                        <div class="form-text">Maximum 1000 caractères</div>
                        @error('intro')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="featured_image" class="form-label fw-bold">Image de couverture</label>
                                <input type="file" 
                                       class="form-control form-control-soft @error('featured_image') is-invalid @enderror" 
                                       id="featured_image" 
                                       name="featured_image" 
                                       accept="image/*">
                                <div class="form-text">JPG, PNG, GIF - Max 2MB</div>
                                @error('featured_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                
                                <!-- Image actuelle -->
                                @if($tutorial->featured_image)
                                    <div class="mt-3">
                                        <label class="form-label fw-bold">Image actuelle :</label>
                                        <div class="position-relative d-inline-block">
                                            <img src="{{ Storage::url($tutorial->featured_image) }}" 
                                                 alt="{{ $tutorial->title }}" 
                                                 class="img-fluid rounded" 
                                                 style="max-height: 200px;">
                                            <div class="position-absolute top-0 end-0">
                                                <button type="button" class="btn btn-soft btn-soft-danger btn-sm" 
                                                        onclick="removeCurrentImage()">
                                                    <i class="bi bi-x"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <input type="hidden" name="current_image" value="{{ $tutorial->featured_image }}">
                                    </div>
                                @endif
                                
                                <!-- Aperçu de la nouvelle image -->
                                <div id="image-preview" class="mt-3" style="display: none;">
                                    <label class="form-label fw-bold">Nouvelle image :</label>
                                    <img id="preview-img" src="" alt="Aperçu" class="img-fluid rounded" style="max-height: 200px;">
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="sort_order" class="form-label fw-bold">Ordre d'affichage</label>
                                <input type="number" 
                                       class="form-control form-control-soft @error('sort_order') is-invalid @enderror" 
                                       id="sort_order" 
                                       name="sort_order" 
                                       value="{{ old('sort_order', $tutorial->sort_order) }}" 
                                       min="0">
                                <div class="form-text">Plus le nombre est petit, plus le tutoriel apparaît en premier</div>
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="is_published" 
                                   name="is_published" 
                                   value="1" 
                                   {{ old('is_published', $tutorial->is_published) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="is_published">
                                Publier immédiatement
                            </label>
                            <div class="form-text">Si coché, le tutoriel sera visible par les utilisateurs</div>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer bg-light p-3">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.tutorials.show', $tutorial) }}" class="btn btn-soft btn-soft-outline">
                            <i class="bi bi-x me-1"></i>Annuler
                        </a>
                        <button type="submit" class="btn btn-soft btn-soft-primary">
                            <i class="bi bi-check me-1"></i>Mettre à jour
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Informations du tutoriel -->
        <div class="table-card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>Informations
                </h6>
            </div>
            <div class="p-3">
                <div class="mb-3">
                    <small class="text-muted">Créé le :</small>
                    <div class="fw-bold">{{ $tutorial->created_at->format('d/m/Y H:i') }}</div>
                </div>
                <div class="mb-3">
                    <small class="text-muted">Modifié le :</small>
                    <div class="fw-bold">{{ $tutorial->updated_at->format('d/m/Y H:i') }}</div>
                </div>
                <div class="mb-3">
                    <small class="text-muted">Nombre d'étapes :</small>
                    <div class="fw-bold">{{ $tutorial->steps_count }}</div>
                </div>
                <div class="mb-3">
                    <small class="text-muted">Statut :</small>
                    <div>
                        @if($tutorial->is_published)
                            <span class="badge badge-soft bg-success">
                                <i class="bi bi-check-circle me-1"></i>Publié
                            </span>
                        @else
                            <span class="badge badge-soft bg-warning">
                                <i class="bi bi-clock me-1"></i>Brouillon
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Actions rapides -->
        <div class="table-card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-lightning me-2"></i>Actions Rapides
                </h6>
            </div>
            <div class="p-3">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.tutorials.show', $tutorial) }}" class="btn btn-soft btn-soft-outline">
                        <i class="bi bi-eye me-1"></i>Voir le tutoriel
                    </a>
                    
                    <form method="POST" action="{{ route('admin.tutorials.toggle-status', $tutorial) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-soft {{ $tutorial->is_published ? 'btn-soft-warning' : 'btn-soft-success' }} w-100">
                            <i class="bi bi-{{ $tutorial->is_published ? 'eye-slash' : 'eye' }} me-1"></i>
                            {{ $tutorial->is_published ? 'Dépublier' : 'Publier' }}
                        </button>
                    </form>
                    
                    <form method="POST" action="{{ route('admin.tutorials.duplicate', $tutorial) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-soft btn-soft-info w-100">
                            <i class="bi bi-files me-1"></i>Dupliquer
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Gestion des étapes -->
        <div class="table-card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-list-ol me-2"></i>Étapes du tutoriel
                </h6>
            </div>
            <div class="p-3">
                @if($tutorial->steps_count > 0)
                    <p class="text-muted mb-3">{{ $tutorial->steps_count }} étape{{ $tutorial->steps_count > 1 ? 's' : '' }} définie{{ $tutorial->steps_count > 1 ? 's' : '' }}</p>
                    <a href="{{ route('admin.tutorials.show', $tutorial) }}" class="btn btn-soft btn-soft-primary w-100">
                        <i class="bi bi-gear me-1"></i>Gérer les étapes
                    </a>
                @else
                    <p class="text-muted mb-3">Aucune étape définie</p>
                    <a href="{{ route('admin.tutorials.show', $tutorial) }}" class="btn btn-soft btn-soft-primary w-100">
                        <i class="bi bi-plus me-1"></i>Ajouter des étapes
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Aperçu de la nouvelle image
    document.getElementById('featured_image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-img').src = e.target.result;
                document.getElementById('image-preview').style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            document.getElementById('image-preview').style.display = 'none';
        }
    });
    
    // Supprimer l'image actuelle
    function removeCurrentImage() {
        if (confirm('Supprimer l\'image actuelle ?')) {
            // Cacher l'image actuelle
            const currentImageContainer = document.querySelector('.position-relative.d-inline-block');
            if (currentImageContainer) {
                currentImageContainer.style.display = 'none';
            }
            
            // Ajouter un champ caché pour indiquer la suppression
            const form = document.querySelector('form');
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'remove_image';
            hiddenInput.value = '1';
            form.appendChild(hiddenInput);
        }
    }
    
    // Compteur de caractères pour la description
    document.getElementById('intro').addEventListener('input', function() {
        const maxLength = 1000;
        const currentLength = this.value.length;
        const remaining = maxLength - currentLength;
        
        let counter = document.getElementById('char-counter');
        if (!counter) {
            counter = document.createElement('div');
            counter.id = 'char-counter';
            counter.className = 'form-text text-end';
            this.parentNode.appendChild(counter);
        }
        
        counter.textContent = `${currentLength}/${maxLength} caractères`;
        
        if (remaining < 50) {
            counter.className = 'form-text text-end text-warning';
        } else if (remaining < 0) {
            counter.className = 'form-text text-end text-danger';
        } else {
            counter.className = 'form-text text-end';
        }
    });
</script>
@endpush
