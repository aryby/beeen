@extends('layouts.admin')

@section('title', $tutorial->title)
@section('page-title', $tutorial->title)

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Informations du tutoriel -->
        <div class="table-card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-book me-2"></i>Informations du tutoriel
                    </h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.tutorials.edit', $tutorial) }}" class="btn btn-soft btn-soft-primary">
                            <i class="bi bi-pencil me-1"></i>Modifier
                        </a>
                        <a href="{{ route('admin.tutorials.index') }}" class="btn btn-soft btn-soft-outline">
                            <i class="bi bi-arrow-left me-1"></i>Retour
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="p-4">
                <div class="row">
                    <div class="col-md-4">
                        @if($tutorial->featured_image)
                            <img src="{{ Storage::url($tutorial->featured_image) }}" 
                                 alt="{{ $tutorial->title }}" 
                                 class="img-fluid rounded mb-3" 
                                 style="max-height: 200px; object-fit: cover;">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center mb-3" 
                                 style="height: 200px;">
                                <i class="bi bi-image text-muted fs-1"></i>
                            </div>
                        @endif
                    </div>
                    
                    <div class="col-md-8">
                        <h4 class="fw-bold mb-3">{{ $tutorial->title }}</h4>
                        <p class="text-muted mb-3">{{ $tutorial->intro }}</p>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <small class="text-muted">Type d'appareil :</small>
                                    <div class="fw-bold">
                                        <i class="bi bi-{{ $tutorial->device_type === 'smart_tv' ? 'tv' : 'phone' }} me-1"></i>
                                        {{ $tutorial->device_type_name }}
                                    </div>
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
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <small class="text-muted">Ordre d'affichage :</small>
                                    <div class="fw-bold">{{ $tutorial->sort_order }}</div>
                                </div>
                                
                                <div class="mb-3">
                                    <small class="text-muted">Nombre d'étapes :</small>
                                    <div class="fw-bold">{{ $tutorial->steps_count }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <form method="POST" action="{{ route('admin.tutorials.toggle-status', $tutorial) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-soft {{ $tutorial->is_published ? 'btn-soft-warning' : 'btn-soft-success' }}">
                                    <i class="bi bi-{{ $tutorial->is_published ? 'eye-slash' : 'eye' }} me-1"></i>
                                    {{ $tutorial->is_published ? 'Dépublier' : 'Publier' }}
                                </button>
                            </form>
                            
                            <form method="POST" action="{{ route('admin.tutorials.duplicate', $tutorial) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-soft btn-soft-info">
                                    <i class="bi bi-files me-1"></i>Dupliquer
                                </button>
                            </form>
                            
                            <form method="POST" action="{{ route('admin.tutorials.destroy', $tutorial) }}" 
                                  class="d-inline" 
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce tutoriel ? Cette action est irréversible.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-soft btn-soft-danger">
                                    <i class="bi bi-trash me-1"></i>Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Gestion des étapes -->
        <div class="table-card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-list-ol me-2"></i>Étapes du tutoriel
                    </h5>
                    <button type="button" class="btn btn-soft btn-soft-primary" data-bs-toggle="modal" data-bs-target="#addStepModal">
                        <i class="bi bi-plus me-1"></i>Ajouter une étape
                    </button>
                </div>
            </div>
            
            <div class="p-0">
                @forelse($tutorial->steps as $step)
                    <div class="p-3 border-bottom step-item" data-step-id="{{ $step->id }}">
                        <div class="row align-items-center">
                            <div class="col-md-1 text-center">
                                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" 
                                     style="width: 30px; height: 30px;">
                                    {{ $step->step_order }}
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-1">{{ $step->title }}</h6>
                                <p class="text-muted mb-2">{{ Str::limit(strip_tags($step->content), 100) }}</p>
                                <div class="d-flex align-items-center gap-2">
                                    @if($step->hasImage())
                                        <span class="badge badge-soft bg-info">
                                            <i class="bi bi-image me-1"></i>Image
                                        </span>
                                    @endif
                                    @if($step->hasVideo())
                                        <span class="badge badge-soft bg-warning">
                                            <i class="bi bi-play-circle me-1"></i>Vidéo
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                @if($step->image)
                                    <img src="{{ Storage::url($step->image) }}" 
                                         alt="{{ $step->title }}" 
                                         class="img-fluid rounded" 
                                         style="max-height: 60px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                         style="height: 60px;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="col-md-2">
                                <div class="btn-group-vertical w-100" role="group">
                                    <button type="button" class="btn btn-soft btn-soft-outline btn-sm mb-1" 
                                            onclick="editStep({{ $step->id }})">
                                        <i class="bi bi-pencil me-1"></i>Modifier
                                    </button>
                                    <button type="button" class="btn btn-soft btn-soft-danger btn-sm" 
                                            onclick="deleteStep({{ $step->id }})">
                                        <i class="bi bi-trash me-1"></i>Supprimer
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-5 text-center">
                        <i class="bi bi-list-ol fs-1 text-muted"></i>
                        <h5 class="text-muted mt-3">Aucune étape définie</h5>
                        <p class="text-muted">Commencez par ajouter la première étape de votre tutoriel.</p>
                        <button type="button" class="btn btn-soft btn-soft-primary" data-bs-toggle="modal" data-bs-target="#addStepModal">
                            <i class="bi bi-plus me-1"></i>Ajouter une étape
                        </button>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Statistiques -->
        <div class="table-card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-graph-up me-2"></i>Statistiques
                </h6>
            </div>
            <div class="p-3">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="stats-value text-primary">{{ $tutorial->steps_count }}</div>
                        <div class="stats-label">Étapes</div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="stats-value text-success">{{ $tutorial->is_published ? 'Oui' : 'Non' }}</div>
                        <div class="stats-label">Publié</div>
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
                    <a href="{{ route('admin.tutorials.edit', $tutorial) }}" class="btn btn-soft btn-soft-primary">
                        <i class="bi bi-pencil me-1"></i>Modifier le tutoriel
                    </a>
                    
                    <a href="{{ route('tutorials.show', $tutorial) }}" class="btn btn-soft btn-soft-outline" target="_blank">
                        <i class="bi bi-eye me-1"></i>Voir sur le site
                    </a>
                    
                    <button type="button" class="btn btn-soft btn-soft-info" data-bs-toggle="modal" data-bs-target="#addStepModal">
                        <i class="bi bi-plus me-1"></i>Ajouter une étape
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Informations de création -->
        <div class="table-card">
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
                    <small class="text-muted">Type :</small>
                    <div class="fw-bold">{{ $tutorial->device_type_name }}</div>
                </div>
                <div class="mb-3">
                    <small class="text-muted">Ordre :</small>
                    <div class="fw-bold">{{ $tutorial->sort_order }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ajouter/Modifier étape -->
<div class="modal fade" id="addStepModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="stepModalTitle">Ajouter une étape</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="stepForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="step_title" class="form-label fw-bold">Titre de l'étape *</label>
                        <input type="text" class="form-control form-control-soft" id="step_title" name="title" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="step_content" class="form-label fw-bold">Contenu *</label>
                        <textarea class="form-control form-control-soft" id="step_content" name="content" rows="4" required></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="step_image" class="form-label fw-bold">Image</label>
                                <input type="file" class="form-control form-control-soft" id="step_image" name="image" accept="image/*">
                                <div class="form-text">JPG, PNG, GIF - Max 2MB</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="step_video_url" class="form-label fw-bold">URL Vidéo</label>
                                <input type="url" class="form-control form-control-soft" id="step_video_url" name="video_url" placeholder="https://...">
                                <div class="form-text">YouTube, Vimeo, etc.</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="step_order" class="form-label fw-bold">Ordre de l'étape</label>
                        <input type="number" class="form-control form-control-soft" id="step_order" name="step_order" min="1" value="{{ $tutorial->steps_count + 1 }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-soft-outline" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-soft btn-soft-primary">Sauvegarder</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Ajouter une étape
    document.getElementById('addStepModal').addEventListener('show.bs.modal', function() {
        document.getElementById('stepModalTitle').textContent = 'Ajouter une étape';
        document.getElementById('stepForm').action = '{{ route("admin.tutorials.steps.store", $tutorial) }}';
        document.getElementById('stepForm').reset();
        document.getElementById('step_order').value = {{ $tutorial->steps_count + 1 }};
    });
    
    // Modifier une étape
    function editStep(stepId) {
        // Ici, vous devriez charger les données de l'étape via AJAX
        // Pour l'instant, on redirige vers une page d'édition
        window.location.href = `/admin/tutorials/{{ $tutorial->id }}/steps/${stepId}/edit`;
    }
    
    // Supprimer une étape
    function deleteStep(stepId) {
        if (confirm('Supprimer cette étape ?')) {
            fetch(`/admin/tutorials/{{ $tutorial->id }}/steps/${stepId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors de la suppression');
            });
        }
    }
</script>
@endpush
