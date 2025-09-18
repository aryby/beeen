@extends('layouts.admin')

@section('title', 'Gestion des Messages')
@section('page-title', 'Messages & Support')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Messages</h1>
        <p class="text-muted">Gérez les messages de contact et demandes de support</p>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-2 col-md-4 mb-3">
        <div class="stats-card">
            <div class="stats-label">Total</div>
            <div class="stats-value">{{ number_format($stats['total']) }}</div>
            <div class="d-flex align-items-center mt-2">
                <i class="bi bi-envelope text-primary me-2"></i>
                <small class="text-muted">Messages</small>
            </div>
        </div>
    </div>
    
    <div class="col-xl-2 col-md-4 mb-3">
        <div class="stats-card warning">
            <div class="stats-label">Ouverts</div>
            <div class="stats-value">{{ number_format($stats['open']) }}</div>
            <div class="d-flex align-items-center mt-2">
                <i class="bi bi-envelope-open text-warning me-2"></i>
                <small class="text-muted">À traiter</small>
            </div>
        </div>
    </div>
    
    <div class="col-xl-2 col-md-4 mb-3">
        <div class="stats-card info">
            <div class="stats-label">En Cours</div>
            <div class="stats-value">{{ number_format($stats['in_progress']) }}</div>
            <div class="d-flex align-items-center mt-2">
                <i class="bi bi-clock text-info me-2"></i>
                <small class="text-muted">Traitement</small>
            </div>
        </div>
    </div>
    
    <div class="col-xl-2 col-md-4 mb-3">
        <div class="stats-card success">
            <div class="stats-label">Résolus</div>
            <div class="stats-value">{{ number_format($stats['resolved']) }}</div>
            <div class="d-flex align-items-center mt-2">
                <i class="bi bi-check-circle text-success me-2"></i>
                <small class="text-muted">Terminés</small>
            </div>
        </div>
    </div>
    
    <div class="col-xl-2 col-md-4 mb-3">
        <div class="stats-card danger">
            <div class="stats-label">Non Lus</div>
            <div class="stats-value">{{ number_format($stats['unread']) }}</div>
            <div class="d-flex align-items-center mt-2">
                <i class="bi bi-envelope-exclamation text-danger me-2"></i>
                <small class="text-muted">Nouveaux</small>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="table-card mb-4">
    <div class="card-header">
        <h6 class="mb-0"><i class="bi bi-funnel me-2"></i>Filtres</h6>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.messages.index') }}">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="status" class="form-label">Statut</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Tous les statuts</option>
                        <option value="open" {{ $status === 'open' ? 'selected' : '' }}>Ouvert</option>
                        <option value="in_progress" {{ $status === 'in_progress' ? 'selected' : '' }}>En cours</option>
                        <option value="resolved" {{ $status === 'resolved' ? 'selected' : '' }}>Résolu</option>
                    </select>
                </div>
                
                <div class="col-md-4 mb-3">
                    <label for="type" class="form-label">Type</label>
                    <select class="form-select" id="type" name="type">
                        <option value="">Tous les types</option>
                        <option value="contact" {{ $type === 'contact' ? 'selected' : '' }}>Contact</option>
                        <option value="support" {{ $type === 'support' ? 'selected' : '' }}>Support</option>
                    </select>
                </div>
                
                <div class="col-md-4 mb-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search me-1"></i>Filtrer
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Messages Table -->
<div class="table-card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-list me-2"></i>Liste des messages</h5>
            <small class="text-muted">{{ $messages->total() }} message(s)</small>
        </div>
    </div>
    <div class="card-body p-0">
        @if($messages->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Expéditeur</th>
                            <th>Sujet</th>
                            <th>Type</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($messages as $message)
                            <tr class="{{ $message->isUnread() ? 'table-warning' : '' }}">
                                <td>
                                    <div class="fw-bold">{{ $message->name }}</div>
                                    <small class="text-muted">{{ $message->email }}</small>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ Str::limit($message->subject, 40) }}</div>
                                    <small class="text-muted">{{ Str::limit($message->message, 60) }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $message->type === 'support' ? 'danger' : 'info' }}">
                                        {{ $message->type_name }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $message->status === 'resolved' ? 'success' : ($message->status === 'in_progress' ? 'warning' : 'secondary') }}">
                                        {{ $message->status_name }}
                                    </span>
                                    @if($message->isUnread())
                                        <br><small class="text-danger fw-bold">Non lu</small>
                                    @endif
                                </td>
                                <td>
                                    <div>{{ $message->created_at->format('d/m/Y') }}</div>
                                    <small class="text-muted">{{ $message->created_at->format('H:i') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.messages.show', $message) }}" 
                                           class="btn btn-sm btn-outline-info" 
                                           title="Voir">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-success" 
                                                title="Marquer comme résolu"
                                                onclick="updateStatus({{ $message->id }}, 'resolved')">
                                            <i class="bi bi-check"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger" 
                                                title="Supprimer"
                                                onclick="confirmDelete({{ $message->id }}, '{{ $message->subject }}')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="card-footer">
                {{ $messages->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-envelope-check fs-1 text-muted mb-3"></i>
                <h4>Aucun message</h4>
                <p class="text-muted">Aucun message ne correspond à vos critères</p>
            </div>
        @endif
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Changer le statut</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="statusForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Changer le statut du message ?</p>
                    <input type="hidden" name="status" id="newStatus">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Confirmer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Supprimer le message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer ce message ?</p>
                <p><strong id="messageSubject"></strong></p>
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
    function updateStatus(messageId, status) {
        document.getElementById('newStatus').value = status;
        document.getElementById('statusForm').action = `/admin/messages/${messageId}/status`;
        
        const modal = new bootstrap.Modal(document.getElementById('statusModal'));
        modal.show();
    }

    function confirmDelete(messageId, subject) {
        document.getElementById('messageSubject').textContent = subject;
        document.getElementById('deleteForm').action = `/admin/messages/${messageId}`;
        
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }

    // Auto-refresh for new messages every 60 seconds
    setInterval(function() {
        if (!document.hidden) {
            const unreadCount = {{ $stats['unread'] }};
            if (unreadCount > 0) {
                console.log('Checking for new messages...');
                // Could implement real-time updates here
            }
        }
    }, 60000);
</script>
@endpush
