@extends('layouts.admin')

@section('title', 'Créer un Tutoriel')
@section('page-title', 'Créer un Tutoriel')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="table-card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-plus-circle me-2"></i>Nouveau Tutoriel
                    </h5>
                    <a href="{{ route('admin.tutorials.index') }}" class="btn btn-soft btn-soft-outline">
                        <i class="bi bi-arrow-left me-1"></i>Retour
                    </a>
                </div>
            </div>
            
            <form method="POST" action="{{ route('admin.tutorials.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="p-4">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-4">
                                <label for="title" class="form-label fw-bold">Titre du tutoriel *</label>
                                <input type="text" 
                                       class="form-control form-control-soft @error('title') is-invalid @enderror" 
                                       id="title" 
                                       name="title" 
                                       value="{{ old('title') }}" 
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
                                        <option value="{{ $key }}" {{ old('device_type') === $key ? 'selected' : '' }}>
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
                                  required>{{ old('intro') }}</textarea>
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
                                
                                <!-- Aperçu de l'image -->
                                <div id="image-preview" class="mt-3" style="display: none;">
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
                                       value="{{ old('sort_order', Tutorial::max('sort_order') + 1) }}" 
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
                                   {{ old('is_published') ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="is_published">
                                Publier immédiatement
                            </label>
                            <div class="form-text">Si coché, le tutoriel sera visible par les utilisateurs</div>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer bg-light p-3">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.tutorials.index') }}" class="btn btn-soft btn-soft-outline">
                            <i class="bi bi-x me-1"></i>Annuler
                        </a>
                        <button type="submit" class="btn btn-soft btn-soft-primary">
                            <i class="bi bi-check me-1"></i>Créer le tutoriel
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Aide -->
        <div class="table-card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-lightbulb me-2"></i>Conseils
                </h6>
            </div>
            <div class="p-3">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <small>Choisissez un titre clair et descriptif</small>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <small>Utilisez une image de couverture attractive</small>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <small>Décrivez précisément le contenu du tutoriel</small>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <small>Organisez les tutoriels par ordre de priorité</small>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Types d'appareils -->
        <div class="table-card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-devices me-2"></i>Types d'appareils
                </h6>
            </div>
            <div class="p-3">
                <div class="row g-2">
                    @foreach($deviceTypes as $key => $label)
                        <div class="col-6">
                            <div class="p-2 bg-light rounded text-center">
                                <i class="bi bi-{{ $key === 'smart_tv' ? 'tv' : ($key === 'android' || $key === 'ios' ? 'phone' : 'laptop') }} d-block mb-1"></i>
                                <small class="fw-bold">{{ $label }}</small>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Aperçu de l'image
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
