@extends('layouts.admin')

@section('title', 'Détails du Message')
@section('page-title', 'Message #' . $message->id)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.messages.index') }}">Messages</a></li>
                <li class="breadcrumb-item active">Message #{{ $message->id }}</li>
            </ol>
        </nav>
    </div>
    <div class="btn-group" role="group">
        <a href="{{ route('admin.messages.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Retour
        </a>
        <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#replyModal">
            <i class="bi bi-reply me-1"></i>Répondre
        </button>
    </div>
</div>

<div class="row">
    <!-- Message Details -->
    <div class="col-lg-8 mb-4">
        <div class="table-card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-envelope me-2"></i>Message de {{ $message->name }}
                    </h5>
                    <div>
                        <span class="badge bg-{{ $message->type === 'support' ? 'danger' : 'info' }} me-2">
                            {{ $message->type_name }}
                        </span>
                        <span class="badge bg-{{ $message->status === 'resolved' ? 'success' : ($message->status === 'in_progress' ? 'warning' : 'secondary') }}">
                            {{ $message->status_name }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Message Header -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-person-circle fs-3 text-primary me-3"></i>
                            <div>
                                <h6 class="mb-0">{{ $message->name }}</h6>
                                <small class="text-muted">{{ $message->email }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-calendar-event fs-3 text-info me-3"></i>
                            <div>
                                <h6 class="mb-0">{{ $message->created_at->format('d/m/Y à H:i') }}</h6>
                                <small class="text-muted">{{ $message->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Subject -->
                <div class="mb-4">
                    <h4 class="fw-bold text-primary">{{ $message->subject }}</h4>
                </div>

                <!-- Message Content -->
                <div class="message-content p-4 rounded" style="background-color: #f8f9fa; border-left: 4px solid var(--bs-primary);">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-chat-quote fs-4 text-primary me-2"></i>
                        <span class="fw-bold">Message :</span>
                    </div>
                    <div class="message-text" style="white-space: pre-wrap; line-height: 1.6;">{{ $message->message }}</div>
                </div>

                <!-- Actions -->
                <div class="mt-4 pt-3 border-top">
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-success" onclick="updateMessageStatus({{ $message->id }}, 'resolved')">
                            <i class="bi bi-check-circle me-1"></i>Marquer comme résolu
                        </button>
                        <button type="button" class="btn btn-warning" onclick="updateMessageStatus({{ $message->id }}, 'in_progress')">
                            <i class="bi bi-clock me-1"></i>En cours
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="updateMessageStatus({{ $message->id }}, 'open')">
                            <i class="bi bi-envelope me-1"></i>Rouvert
                        </button>
                        <button type="button" class="btn btn-outline-danger ms-auto" onclick="confirmDelete({{ $message->id }}, '{{ $message->subject }}')">
                            <i class="bi bi-trash me-1"></i>Supprimer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Contact Info -->
        <div class="table-card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-person-lines-fill me-2"></i>Informations de contact</h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-person-circle fs-4 text-primary me-3"></i>
                    <div>
                        <div class="fw-bold">{{ $message->name }}</div>
                        <small class="text-muted">Expéditeur</small>
                    </div>
                </div>
                
                <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-envelope fs-4 text-info me-3"></i>
                    <div>
                        <div class="fw-bold">{{ $message->email }}</div>
                        <small class="text-muted">Email</small>
                    </div>
                </div>

                <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-tag fs-4 text-{{ $message->type === 'support' ? 'danger' : 'info' }} me-3"></i>
                    <div>
                        <div class="fw-bold">{{ $message->type_name }}</div>
                        <small class="text-muted">Type de demande</small>
                    </div>
                </div>

                <div class="d-flex align-items-center">
                    <i class="bi bi-{{ $message->status === 'resolved' ? 'check-circle' : ($message->status === 'in_progress' ? 'clock' : 'envelope') }} fs-4 text-{{ $message->status === 'resolved' ? 'success' : ($message->status === 'in_progress' ? 'warning' : 'secondary') }} me-3"></i>
                    <div>
                        <div class="fw-bold">{{ $message->status_name }}</div>
                        <small class="text-muted">Statut actuel</small>
                    </div>
                </div>

                @if($message->replied_at)
                    <hr>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-reply fs-4 text-success me-3"></i>
                        <div>
                            <div class="fw-bold text-success">Répondu</div>
                            <small class="text-muted">{{ $message->replied_at->format('d/m/Y à H:i') }}</small>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="table-card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-lightning me-2"></i>Actions rapides</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="mailto:{{ $message->email }}?subject=Re: {{ $message->subject }}" class="btn btn-outline-primary">
                        <i class="bi bi-envelope me-2"></i>Envoyer un email
                    </a>
                    <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#replyModal">
                        <i class="bi bi-reply me-2"></i>Répondre via le système
                    </button>
                    <button type="button" class="btn btn-outline-warning" onclick="copyMessageInfo()">
                        <i class="bi bi-clipboard me-2"></i>Copier les infos
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reply Modal -->
<div class="modal fade" id="replyModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Répondre à {{ $message->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.messages.reply', $message) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Destinataire</label>
                        <input type="text" class="form-control" value="{{ $message->name }} <{{ $message->email }}>" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sujet</label>
                        <input type="text" class="form-control" value="Re: {{ $message->subject }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="reply_message" class="form-label">Message de réponse *</label>
                        <textarea class="form-control" id="reply_message" name="reply_message" rows="8" 
                                  placeholder="Tapez votre réponse ici..." required></textarea>
                        <div class="form-text">Maximum 2000 caractères</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send me-1"></i>Envoyer la réponse
                    </button>
                </div>
            </form>
        </div>
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
    function updateMessageStatus(messageId, status) {
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

    function copyMessageInfo() {
        const info = `Nom: {{ $message->name }}
Email: {{ $message->email }}
Sujet: {{ $message->subject }}
Type: {{ $message->type_name }}
Statut: {{ $message->status_name }}
Date: {{ $message->created_at->format('d/m/Y à H:i') }}

Message:
{{ $message->message }}`;
        
        navigator.clipboard.writeText(info).then(function() {
            // Show success message
            const btn = event.target.closest('button');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-check me-2"></i>Copié !';
            btn.classList.remove('btn-outline-warning');
            btn.classList.add('btn-success');
            
            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.classList.remove('btn-success');
                btn.classList.add('btn-outline-warning');
            }, 2000);
        });
    }

    // Auto-resize textarea
    document.getElementById('reply_message').addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = this.scrollHeight + 'px';
    });
</script>
@endpush
