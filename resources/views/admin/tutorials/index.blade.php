@extends('layouts.admin')

@section('title', 'Gestion des Tutoriels')
@section('page-title', 'Gestion des Tutoriels')

@section('content')
<div class="row">
    <!-- Statistiques -->
    <div class="col-12 mb-4">
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="stats-value">{{ $stats['total'] }}</div>
                    <div class="stats-label">Total Tutoriels</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card success">
                    <div class="stats-value">{{ $stats['published'] }}</div>
                    <div class="stats-label">Publiés</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card warning">
                    <div class="stats-value">{{ $stats['draft'] }}</div>
                    <div class="stats-label">Brouillons</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card info">
                    <div class="stats-value">{{ $stats['total_steps'] }}</div>
                    <div class="stats-label">Total Étapes</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="table-card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-book me-2"></i>Liste des Tutoriels
                    </h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.tutorials.create') }}" class="btn btn-soft btn-soft-primary">
                            <i class="bi bi-plus me-1"></i>Nouveau Tutoriel
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Filtres -->
            <div class="p-3 border-bottom">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <select name="device_type" class="form-control form-control-soft">
                            <option value="">Tous les appareils</option>
                            @foreach($deviceTypes as $key => $label)
                                <option value="{{ $key }}" {{ $deviceType === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-control form-control-soft">
                            <option value="">Tous les statuts</option>
                            <option value="published" {{ $status === 'published' ? 'selected' : '' }}>Publiés</option>
                            <option value="draft" {{ $status === 'draft' ? 'selected' : '' }}>Brouillons</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control form-control-soft" 
                               placeholder="Rechercher par titre ou description..." value="{{ $search }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-soft btn-soft-primary w-100">
                            <i class="bi bi-search me-1"></i>Filtrer
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="p-0">
                @forelse($tutorials as $tutorial)
                    <div class="p-3 border-bottom">
                        <div class="row align-items-center">
                            <div class="col-md-2">
                                @if($tutorial->featured_image)
                                    <img src="{{ Storage::url($tutorial->featured_image) }}" 
                                         alt="{{ $tutorial->title }}" 
                                         class="img-fluid rounded" 
                                         style="max-height: 80px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                         style="height: 80px;">
                                        <i class="bi bi-image text-muted fs-2"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold mb-1">{{ $tutorial->title }}</h6>
                                        <p class="text-muted mb-2">{{ Str::limit($tutorial->intro, 100) }}</p>
                                        <div class="d-flex align-items-center gap-3">
                                            <span class="badge badge-soft bg-info">
                                                <i class="bi bi-{{ $tutorial->device_type === 'smart_tv' ? 'tv' : 'phone' }} me-1"></i>
                                                {{ $tutorial->device_type_name }}
                                            </span>
                                            <span class="badge badge-soft bg-secondary">
                                                <i class="bi bi-list-ol me-1"></i>
                                                {{ $tutorial->steps_count }} étape{{ $tutorial->steps_count > 1 ? 's' : '' }}
                                            </span>
                                            <span class="badge badge-soft bg-warning">
                                                <i class="bi bi-sort-numeric-up me-1"></i>
                                                Ordre: {{ $tutorial->sort_order }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-2 text-center">
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
                            
                            <div class="col-md-2">
                                <div class="btn-group-vertical w-100" role="group">
                                    <a href="{{ route('admin.tutorials.show', $tutorial) }}" 
                                       class="btn btn-soft btn-soft-outline btn-sm mb-1">
                                        <i class="bi bi-eye me-1"></i>Voir
                                    </a>
                                    <a href="{{ route('admin.tutorials.edit', $tutorial) }}" 
                                       class="btn btn-soft btn-soft-primary btn-sm mb-1">
                                        <i class="bi bi-pencil me-1"></i>Modifier
                                    </a>
                                    
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-soft btn-soft-outline btn-sm dropdown-toggle" 
                                                data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <form method="POST" action="{{ route('admin.tutorials.toggle-status', $tutorial) }}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="bi bi-{{ $tutorial->is_published ? 'eye-slash' : 'eye' }} me-2"></i>
                                                        {{ $tutorial->is_published ? 'Dépublier' : 'Publier' }}
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <form method="POST" action="{{ route('admin.tutorials.duplicate', $tutorial) }}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="bi bi-files me-2"></i>Dupliquer
                                                    </button>
                                                </form>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form method="POST" action="{{ route('admin.tutorials.destroy', $tutorial) }}" 
                                                      class="d-inline" 
                                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce tutoriel ? Cette action est irréversible.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="bi bi-trash me-2"></i>Supprimer
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-5 text-center">
                        <i class="bi bi-book fs-1 text-muted"></i>
                        <h5 class="text-muted mt-3">Aucun tutoriel trouvé</h5>
                        <p class="text-muted">Commencez par créer votre premier tutoriel.</p>
                        <a href="{{ route('admin.tutorials.create') }}" class="btn btn-soft btn-soft-primary">
                            <i class="bi bi-plus me-1"></i>Créer un tutoriel
                        </a>
                    </div>
                @endforelse
            </div>
            
            @if($tutorials->hasPages())
                <div class="p-3">
                    {{ $tutorials->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal de réorganisation -->
<div class="modal fade" id="reorderModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Réorganiser les tutoriels</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">Glissez-déposez les tutoriels pour les réorganiser :</p>
                <div id="sortable-tutorials">
                    @foreach($tutorials as $tutorial)
                        <div class="card-soft p-3 mb-2 sortable-item" data-id="{{ $tutorial->id }}">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-grip-vertical text-muted me-3"></i>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $tutorial->title }}</h6>
                                    <small class="text-muted">{{ $tutorial->device_type_name }}</small>
                                </div>
                                <span class="badge badge-soft bg-info">Ordre: {{ $tutorial->sort_order }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-soft-outline" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-soft btn-soft-primary" onclick="saveOrder()">Sauvegarder</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    // Initialiser le tri
    let sortable = null;
    
    document.addEventListener('DOMContentLoaded', function() {
        const sortableElement = document.getElementById('sortable-tutorials');
        if (sortableElement) {
            sortable = Sortable.create(sortableElement, {
                animation: 150,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen'
            });
        }
    });
    
    function saveOrder() {
        const items = document.querySelectorAll('.sortable-item');
        const tutorials = Array.from(items).map((item, index) => ({
            id: item.dataset.id,
            sort_order: index + 1
        }));
        
        fetch('{{ route("admin.tutorials.reorder") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ tutorials: tutorials })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la sauvegarde');
        });
    }
</script>

<style>
    .sortable-ghost {
        opacity: 0.4;
    }
    
    .sortable-chosen {
        background-color: rgba(203, 12, 159, 0.1);
    }
</style>
@endpush
