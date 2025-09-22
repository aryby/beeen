@extends('layouts.admin')

@section('title', 'Modifier l\'étape')
@section('page-title', 'Modifier l\'étape')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="table-card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-pencil me-2"></i>Modifier l'étape {{ $step->step_order }}: {{ $step->title }}
                    </h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.tutorials.show', $tutorial) }}" class="btn btn-soft btn-soft-outline">
                            <i class="bi bi-arrow-left me-1"></i>Retour au tutoriel
                        </a>
                    </div>
                </div>
            </div>
            
            <form method="POST" action="{{ route('admin.tutorials.steps.update', [$tutorial, $step]) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="p-4">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-4">
                                <label for="title" class="form-label fw-bold">Titre de l'étape *</label>
                                <input type="text" 
                                       class="form-control form-control-soft @error('title') is-invalid @enderror" 
                                       id="title" 
                                       name="title" 
                                       value="{{ old('title', $step->title) }}" 
                                       placeholder="Ex: Télécharger l'application"
                                       required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-4">
                                <label for="step_order" class="form-label fw-bold">Ordre de l'étape *</label>
                                <input type="number" 
                                       class="form-control form-control-soft @error('step_order') is-invalid @enderror" 
                                       id="step_order" 
                                       name="step_order" 
                                       value="{{ old('step_order', $step->step_order) }}" 
                                       min="1" 
                                       required>
                                @error('step_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="content" class="form-label fw-bold">Contenu de l'étape *</label>
                        <textarea class="form-control form-control-soft @error('content') is-invalid @enderror" 
                                  id="content" 
                                  name="content" 
                                  rows="6" 
                                  placeholder="Décrivez en détail cette étape du tutoriel..."
                                  required>{{ old('content', $step->content) }}</textarea>
                        <div class="form-text">Vous pouvez utiliser du HTML basique pour la mise en forme</div>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="image" class="form-label fw-bold">Image</label>
                                <input type="file" 
                                       class="form-control form-control-soft @error('image') is-invalid @enderror" 
                                       id="image" 
                                       name="image" 
                                       accept="image/*">
                                <div class="form-text">JPG, PNG, GIF - Max 2MB</div>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                
                                <!-- Image actuelle -->
                                @if($step->image)
                                    <div class="mt-3">
                                        <label class="form-label fw-bold">Image actuelle :</label>
                                        <div class="position-relative d-inline-block">
                                            <img src="{{ Storage::url($step->image) }}" 
                                                 alt="{{ $step->title }}" 
                                                 class="img-fluid rounded" 
                                                 style="max-height: 200px;">
                                            <div class="position-absolute top-0 end-0">
                                                <button type="button" class="btn btn-soft btn-soft-danger btn-sm" 
                                                        onclick="removeCurrentImage()">
                                                    <i class="bi bi-x"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <input type="hidden" name="current_image" value="{{ $step->image }}">
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
                                <label for="video_url" class="form-label fw-bold">URL Vidéo</label>
                                <input type="url" 
                                       class="form-control form-control-soft @error('video_url') is-invalid @enderror" 
                                       id="video_url" 
                                       name="video_url" 
                                       value="{{ old('video_url', $step->video_url) }}" 
                                       placeholder="https://www.youtube.com/watch?v=...">
                                <div class="form-text">YouTube, Vimeo, etc.</div>
                                @error('video_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                
                                <!-- Aperçu de la vidéo -->
                                @if($step->video_url)
                                    <div class="mt-3">
                                        <label class="form-label fw-bold">Vidéo actuelle :</label>
                                        <div class="bg-light rounded p-2">
                                            <a href="{{ $step->video_url }}" target="_blank" class="text-decoration-none">
                                                <i class="bi bi-play-circle me-1"></i>
                                                {{ Str::limit($step->video_url, 50) }}
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer bg-light p-3">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.tutorials.show', $tutorial) }}" class="btn btn-soft btn-soft-outline">
                            <i class="bi bi-x me-1"></i>Annuler
                        </a>
                        <div class="d-flex gap-2">
                            <form method="POST" action="{{ route('admin.tutorials.steps.destroy', [$tutorial, $step]) }}" 
                                  class="d-inline" 
                                  onsubmit="return confirm('Supprimer cette étape ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-soft btn-soft-danger">
                                    <i class="bi bi-trash me-1"></i>Supprimer
                                </button>
                            </form>
                            <button type="submit" class="btn btn-soft btn-soft-primary">
                                <i class="bi bi-check me-1"></i>Mettre à jour
                            </button>
                        </div>
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
                    <i class="bi bi-book me-2"></i>Tutoriel parent
                </h6>
            </div>
            <div class="p-3">
                <h6 class="fw-bold">{{ $tutorial->title }}</h6>
                <p class="text-muted mb-2">{{ Str::limit($tutorial->intro, 100) }}</p>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge badge-soft bg-info">
                        <i class="bi bi-{{ $tutorial->device_type === 'smart_tv' ? 'tv' : 'phone' }} me-1"></i>
                        {{ $tutorial->device_type_name }}
                    </span>
                    @if($tutorial->is_published)
                        <span class="badge badge-soft bg-success">Publié</span>
                    @else
                        <span class="badge badge-soft bg-warning">Brouillon</span>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Informations de l'étape -->
        <div class="table-card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>Informations de l'étape
                </h6>
            </div>
            <div class="p-3">
                <div class="mb-3">
                    <small class="text-muted">Créée le :</small>
                    <div class="fw-bold">{{ $step->created_at->format('d/m/Y H:i') }}</div>
                </div>
                <div class="mb-3">
                    <small class="text-muted">Modifiée le :</small>
                    <div class="fw-bold">{{ $step->updated_at->format('d/m/Y H:i') }}</div>
                </div>
                <div class="mb-3">
                    <small class="text-muted">Ordre actuel :</small>
                    <div class="fw-bold">{{ $step->step_order }}</div>
                </div>
                <div class="mb-3">
                    <small class="text-muted">Contenu :</small>
                    <div class="fw-bold">{{ Str::length(strip_tags($step->content)) }} caractères</div>
                </div>
            </div>
        </div>
        
        <!-- Aide -->
        <div class="table-card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-lightbulb me-2"></i>Conseils
                </h6>
            </div>
            <div class="p-3">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <small>Utilisez un titre clair et descriptif</small>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <small>Ajoutez des images pour illustrer</small>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <small>Utilisez des vidéos pour les actions complexes</small>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <small>Organisez les étapes dans l'ordre logique</small>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Aperçu de la nouvelle image
    document.getElementById('image').addEventListener('change', function(e) {
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
</script>
@endpush
