@extends('layouts.admin')

@section('title', 'Commande ' . $order->order_number)
@section('page-title', 'Détails de la Commande')

@push('styles')
<style>
    :root {
        --soft-primary: #cb0c9f;
        --soft-secondary: #8392ab;
        --soft-success: #82d616;
        --soft-info: #17c1e8;
        --soft-warning: #fbcf33;
        --soft-danger: #ea0606;
        --soft-light: #e9ecef;
        --soft-dark: #344767;
        --soft-white: #ffffff;
        --soft-gray-100: #f8f9fa;
        --soft-gray-200: #e9ecef;
        --soft-gray-300: #dee2e6;
        --soft-gray-400: #ced4da;
        --soft-gray-500: #adb5bd;
        --soft-gray-600: #6c757d;
        --soft-gray-700: #495057;
        --soft-gray-800: #343a40;
        --soft-gray-900: #212529;
        --border-radius-soft: 0.75rem;
        --border-radius-soft-lg: 1rem;
        --border-radius-soft-xl: 1.5rem;
        --shadow-soft: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        --shadow-soft-lg: 0 1rem 3rem rgba(0, 0, 0, 0.175);
        --shadow-soft-xl: 0 1.5rem 4rem rgba(0, 0, 0, 0.2);
    }

    .card-soft {
        background: var(--soft-white);
        border-radius: var(--border-radius-soft);
        box-shadow: var(--shadow-soft);
        border: none;
        transition: all 0.3s ease;
    }

    .card-soft:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-soft-lg);
    }

    .card-soft-header {
        background: linear-gradient(135deg, var(--soft-primary) 0%, #9c27b0 100%);
        color: white;
        border-radius: var(--border-radius-soft) var(--border-radius-soft) 0 0;
        padding: 1.5rem;
        border: none;
    }

    .btn-soft {
        border-radius: var(--border-radius-soft);
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .btn-soft-primary {
        background: linear-gradient(135deg, var(--soft-primary) 0%, #9c27b0 100%);
        color: white;
    }

    .btn-soft-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 12px rgba(203, 12, 159, 0.4);
        color: white;
    }

    .btn-soft-success {
        background: linear-gradient(135deg, var(--soft-success) 0%, #28a745 100%);
        color: white;
    }

    .btn-soft-success:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 12px rgba(130, 214, 22, 0.4);
        color: white;
    }

    .btn-soft-warning {
        background: linear-gradient(135deg, var(--soft-warning) 0%, #ffc107 100%);
        color: var(--soft-dark);
    }

    .btn-soft-warning:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 12px rgba(251, 207, 51, 0.4);
        color: var(--soft-dark);
    }

    .btn-soft-danger {
        background: linear-gradient(135deg, var(--soft-danger) 0%, #dc3545 100%);
        color: white;
    }

    .btn-soft-danger:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 12px rgba(234, 6, 6, 0.4);
        color: white;
    }

    .btn-soft-outline {
        background: transparent;
        border: 2px solid var(--soft-primary);
        color: var(--soft-primary);
    }

    .btn-soft-outline:hover {
        background: var(--soft-primary);
        color: white;
        transform: translateY(-1px);
    }

    .badge-soft {
        border-radius: var(--border-radius-soft);
        font-weight: 600;
        padding: 0.5rem 1rem;
    }

    .timeline-soft {
        position: relative;
        padding-left: 2rem;
    }

    .timeline-soft::before {
        content: '';
        position: absolute;
        left: 1rem;
        top: 0;
        bottom: 0;
        width: 3px;
        background: linear-gradient(180deg, var(--soft-primary) 0%, var(--soft-secondary) 100%);
        border-radius: 2px;
    }

    .timeline-item-soft {
        position: relative;
        margin-bottom: 2rem;
        background: var(--soft-white);
        border-radius: var(--border-radius-soft);
        padding: 1.5rem;
        box-shadow: var(--shadow-soft);
        border-left: 4px solid var(--soft-primary);
    }

    .timeline-marker-soft {
        position: absolute;
        left: -2.5rem;
        top: 1.5rem;
        width: 1.5rem;
        height: 1.5rem;
        border-radius: 50%;
        border: 3px solid var(--soft-white);
        box-shadow: 0 0 0 3px var(--soft-primary);
    }

    .form-control-soft {
        border: 2px solid var(--soft-gray-200);
        border-radius: var(--border-radius-soft);
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .form-control-soft:focus {
        border-color: var(--soft-primary);
        box-shadow: 0 0 0 0.2rem rgba(203, 12, 159, 0.25);
    }

    .input-group-soft .input-group-text {
        background: var(--soft-gray-100);
        border: 2px solid var(--soft-gray-200);
        border-right: none;
        border-radius: var(--border-radius-soft) 0 0 var(--border-radius-soft);
    }

    .input-group-soft .form-control {
        border-left: none;
        border-radius: 0 var(--border-radius-soft) var(--border-radius-soft) 0;
    }

    .gradient-text {
        background: linear-gradient(135deg, var(--soft-primary) 0%, #9c27b0 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-weight: 700;
    }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: var(--border-radius-soft);
        font-weight: 600;
        font-size: 0.875rem;
    }

    .status-paid {
        background: linear-gradient(135deg, var(--soft-success) 0%, #28a745 100%);
        color: white;
    }

    .status-pending {
        background: linear-gradient(135deg, var(--soft-warning) 0%, #ffc107 100%);
        color: var(--soft-dark);
    }

    .status-cancelled {
        background: linear-gradient(135deg, var(--soft-danger) 0%, #dc3545 100%);
        color: white;
    }

    .status-refunded {
        background: linear-gradient(135deg, var(--soft-secondary) 0%, #6c757d 100%);
        color: white;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Tableau de bord</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}" class="text-decoration-none">Commandes</a></li>
                <li class="breadcrumb-item active">{{ $order->order_number }}</li>
            </ol>
        </nav>

        <!-- Order Header -->
        <div class="card-soft mb-4">
            <div class="card-soft-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1 fw-bold">
                            <i class="bi bi-receipt me-2"></i>Commande {{ $order->order_number }}
                        </h4>
                        <p class="mb-0 opacity-75">Créée le {{ $order->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                    <div class="text-end">
                        <span class="status-badge status-{{ $order->status === 'paid' ? 'paid' : ($order->status === 'paid_pending_validation' ? 'pending' : ($order->status === 'pending' ? 'pending' : ($order->status === 'cancelled' ? 'cancelled' : 'refunded'))) }}">
                            @if($order->status === 'paid_pending_validation')
                                <i class="bi bi-clock me-1"></i>En attente de validation
                            @elseif($order->status === 'paid')
                                <i class="bi bi-check-circle me-1"></i>Payée
                            @elseif($order->status === 'pending')
                                <i class="bi bi-hourglass-split me-1"></i>En attente
                            @elseif($order->status === 'cancelled')
                                <i class="bi bi-x-circle me-1"></i>Annulée
                            @elseif($order->status === 'refunded')
                                <i class="bi bi-arrow-return-left me-1"></i>Remboursée
                            @endif
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h6 class="gradient-text mb-3">
                                <i class="bi bi-info-circle me-2"></i>Informations de commande
                            </h6>
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="p-3 bg-light rounded-3">
                                        <small class="text-muted d-block">Type</small>
                                        @if($order->item_type === 'reseller_pack')
                                            <span class="badge-soft bg-success text-white">Pack Revendeur</span>
                                        @else
                                            <span class="badge-soft bg-primary text-white">Abonnement IPTV</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 bg-light rounded-3">
                                        <small class="text-muted d-block">Montant</small>
                                        <strong class="fs-5 text-success">{{ $order->formatted_amount }}</strong>
                                    </div>
                                </div>
                            </div>
                            
                            @if($order->subscription)
                                <div class="mt-3 p-3 bg-primary bg-opacity-10 rounded-3">
                                    <h6 class="mb-1">{{ $order->subscription->name }}</h6>
                                    <small class="text-muted">{{ $order->subscription->description }}</small>
                                </div>
                            @elseif($order->resellerPack)
                                <div class="mt-3 p-3 bg-success bg-opacity-10 rounded-3">
                                    <h6 class="mb-1">{{ $order->resellerPack->name }}</h6>
                                    <small class="text-muted">{{ $order->resellerPack->credits }} crédits</small>
                                </div>
                            @endif
                            
                            @if($order->payment_id)
                                <div class="mt-3">
                                    <small class="text-muted d-block">ID PayPal</small>
                                    <code class="bg-light p-2 rounded">{{ $order->payment_id }}</code>
                                </div>
                            @endif
                            
                            @if($order->is_guest_order)
                                <div class="mt-3">
                                    <span class="badge-soft bg-info text-white">
                                        <i class="bi bi-lightning me-1"></i>Commande rapide
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h6 class="gradient-text mb-3">
                                <i class="bi bi-person me-2"></i>Informations client
                            </h6>
                            <div class="p-3 bg-light rounded-3 mb-3">
                                <h6 class="mb-1">{{ $order->customer_name }}</h6>
                                <small class="text-muted">{{ $order->customer_email }}</small>
                            </div>
                            
                            @if($order->customer_address)
                                <div class="p-3 bg-light rounded-3 mb-3">
                                    <small class="text-muted d-block">Adresse</small>
                                    {{ $order->customer_address }}
                                </div>
                            @endif
                            
                            @if($order->user)
                                <div class="p-3 bg-light rounded-3">
                                    <small class="text-muted d-block">Compte utilisateur</small>
                                    <span class="badge-soft bg-{{ $order->user->role === 'admin' ? 'danger' : ($order->user->role === 'reseller' ? 'warning' : 'primary') }} text-white">
                                        {{ ucfirst($order->user->role) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        
                        <!-- PayPal Details -->
                        @if($paypalDetails)
                            <div class="p-3 bg-info bg-opacity-10 rounded-3">
                                <h6 class="gradient-text mb-3">
                                    <i class="bi bi-paypal me-2"></i>Détails PayPal
                                </h6>
                                @if(isset($paypalDetails['payer']))
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <small class="text-muted d-block">Email PayPal</small>
                                            <strong>{{ $paypalDetails['payer']['email_address'] ?? 'N/A' }}</strong>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">Nom PayPal</small>
                                            <strong>{{ $paypalDetails['payer']['name']['given_name'] ?? '' }} {{ $paypalDetails['payer']['name']['surname'] ?? '' }}</strong>
                                        </div>
                                    </div>
                                @endif
                                @if(isset($paypalDetails['status']))
                                    <div class="mt-2">
                                        <small class="text-muted d-block">Statut PayPal</small>
                                        <span class="badge-soft bg-{{ $paypalDetails['status'] === 'COMPLETED' ? 'success' : 'warning' }} text-white">
                                            {{ $paypalDetails['status'] }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- IPTV Details -->
        @if($order->iptv_code)
            <div class="card-soft mb-4">
                <div class="card-soft-header" style="background: linear-gradient(135deg, var(--soft-success) 0%, #28a745 100%);">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-key me-2"></i>Détails IPTV
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="p-3 bg-success bg-opacity-10 rounded-3 mb-3">
                                <h6 class="gradient-text mb-2">Code IPTV</h6>
                                <div class="input-group input-group-soft">
                                    <input type="text" class="form-control form-control-soft" id="iptvCode" value="{{ $order->iptv_code }}" readonly>
                                    <button class="btn btn-soft btn-soft-success" onclick="copyIptvCode()">
                                        <i class="bi bi-clipboard me-1"></i>Copier
                                    </button>
                                </div>
                            </div>
                            
                            @if($order->expires_at)
                                <div class="p-3 bg-warning bg-opacity-10 rounded-3">
                                    <h6 class="gradient-text mb-2">Expiration</h6>
                                    <p class="mb-1"><strong>Date :</strong> {{ $order->expires_at->format('d/m/Y à H:i') }}</p>
                                    <p class="mb-0">
                                        <strong>Temps restant :</strong> 
                                        @if($order->expires_at->isFuture())
                                            <span class="text-success fw-bold">{{ $order->expires_at->diffForHumans() }}</span>
                                        @else
                                            <span class="text-danger fw-bold">Expiré</span>
                                        @endif
                                    </p>
                                </div>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <div class="p-3 bg-info bg-opacity-10 rounded-3 mb-3">
                                <h6 class="gradient-text mb-2">Informations</h6>
                                <p class="mb-1"><strong>Durée :</strong> {{ $order->subscription ? $order->subscription->duration_text : 'N/A' }}</p>
                                <p class="mb-0"><strong>Activé le :</strong> {{ $order->updated_at->format('d/m/Y à H:i') }}</p>
                            </div>
                            
                            <!-- Quick Message Templates -->
                            <div class="p-3 bg-light rounded-3">
                                <h6 class="gradient-text mb-3">Messages rapides</h6>
                                <div class="d-flex flex-wrap gap-2">
                                    <button class="btn btn-soft btn-soft-outline" onclick="useTemplate('mag')">
                                        <i class="bi bi-tv me-1"></i>MAG
                                    </button>
                                    <button class="btn btn-soft btn-soft-outline" onclick="useTemplate('kodi')">
                                        <i class="bi bi-play-circle me-1"></i>Kodi
                                    </button>
                                    <button class="btn btn-soft btn-soft-outline" onclick="useTemplate('general')">
                                        <i class="bi bi-chat me-1"></i>Général
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- M3U Details -->
        @if($order->hasM3UCredentials())
            <div class="card-soft mb-4">
                <div class="card-soft-header" style="background: linear-gradient(135deg, var(--soft-info) 0%, #17c1e8 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-broadcast me-2"></i>Identifiants M3U
                        </h5>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-light" onclick="testM3UConnection({{ $order->id }})">
                                <i class="bi bi-wifi me-1"></i>Tester
                            </button>
                            <button class="btn btn-sm btn-warning" onclick="showM3UEditModal()">
                                <i class="bi bi-pencil me-1"></i>Modifier
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="deleteM3U({{ $order->id }})">
                                <i class="bi bi-trash me-1"></i>Supprimer
                            </button>
                            <a href="{{ $order->m3u_url }}" target="_blank" class="btn btn-sm btn-light">
                                <i class="bi bi-download me-1"></i>Télécharger M3U
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="p-3 bg-info bg-opacity-10 rounded-3 mb-3">
                                <h6 class="gradient-text mb-2">Nom d'utilisateur</h6>
                                <div class="input-group input-group-soft">
                                    <input type="text" class="form-control form-control-soft" id="m3uUsername" value="{{ $order->m3u_username }}" readonly>
                                    <button class="btn btn-soft btn-soft-info" onclick="copyToClipboard('m3uUsername')">
                                        <i class="bi bi-clipboard me-1"></i>Copier
                                    </button>
                                </div>
                            </div>
                            
                            <div class="p-3 bg-info bg-opacity-10 rounded-3 mb-3">
                                <h6 class="gradient-text mb-2">Mot de passe</h6>
                                <div class="input-group input-group-soft">
                                    <input type="text" class="form-control form-control-soft" id="m3uPassword" value="{{ $order->m3u_password }}" readonly>
                                    <button class="btn btn-soft btn-soft-info" onclick="copyToClipboard('m3uPassword')">
                                        <i class="bi bi-clipboard me-1"></i>Copier
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="p-3 bg-info bg-opacity-10 rounded-3 mb-3">
                                <h6 class="gradient-text mb-2">Serveur</h6>
                                <div class="input-group input-group-soft">
                                    <input type="text" class="form-control form-control-soft" id="m3uServer" value="{{ $order->m3u_server_url }}" readonly>
                                    <button class="btn btn-soft btn-soft-info" onclick="copyToClipboard('m3uServer')">
                                        <i class="bi bi-clipboard me-1"></i>Copier
                                    </button>
                                </div>
                            </div>
                            
                            <div class="p-3 bg-info bg-opacity-10 rounded-3 mb-3">
                                <h6 class="gradient-text mb-2">URL M3U</h6>
                                <div class="input-group input-group-soft">
                                    <input type="text" class="form-control form-control-soft" id="m3uUrl" value="{{ $order->m3u_url }}" readonly>
                                    <button class="btn btn-soft btn-soft-info" onclick="copyToClipboard('m3uUrl')">
                                        <i class="bi bi-clipboard me-1"></i>Copier
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($order->m3u_generated_at)
                        <div class="text-center text-muted">
                            <small>
                                <i class="bi bi-clock me-1"></i>
                                Généré le {{ $order->m3u_generated_at->format('d/m/Y à H:i') }}
                            </small>
                        </div>
                    @endif
                </div>
            </div>
        @elseif($order->isPaid())
            <div class="card-soft mb-4">
                <div class="card-soft-header" style="background: linear-gradient(135deg, var(--soft-warning) 0%, #ffc107 100%);">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-exclamation-triangle me-2"></i>Identifiants M3U non générés
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-8">
                            <p class="mb-3">Cette commande est payée mais les identifiants M3U n'ont pas encore été générés.</p>
                            <p class="text-muted mb-0">Vous pouvez générer automatiquement les identifiants ou extraire depuis un fichier M3U existant.</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <button class="btn btn-soft btn-soft-primary mb-2" onclick="generateM3U({{ $order->id }})">
                                <i class="bi bi-magic me-1"></i>Générer automatiquement
                            </button>
                            <br>
                            <button class="btn btn-soft btn-soft-outline mb-2" onclick="showM3UExtractModal()">
                                <i class="bi bi-upload me-1"></i>Extraire depuis fichier
                            </button>
                            <br>
                            <button class="btn btn-soft btn-soft-outline" onclick="showM3UUrlExtractModal()">
                                <i class="bi bi-link-45deg me-1"></i>Extraire depuis URL
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Messages History -->
        <div class="card-soft mb-4">
            <div class="card-soft-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-chat-left-text me-2"></i>Historique des Messages
                    </h5>
                    <button class="btn btn-soft btn-soft-primary" onclick="showMessageModal('{{ $order->customer_name }}', '{{ $order->customer_email }}', {{ $order->id }})">
                        <i class="bi bi-plus-circle me-1"></i>Nouveau message
                    </button>
                </div>
            </div>
            <div class="card-body p-4">
                @php
                    try {
                        $adminMessages = App\Models\AdminMessage::where('order_id', $order->id)
                            ->with('adminUser')
                            ->latest()
                            ->get();
                    } catch (\Exception $e) {
                        $adminMessages = collect();
                    }
                @endphp
                
                @if($adminMessages->count() > 0)
                    <div class="timeline-soft">
                        @foreach($adminMessages as $message)
                            <div class="timeline-item-soft">
                                <div class="timeline-marker-soft bg-{{ $message->delivery_status === 'read' ? 'success' : ($message->delivery_status === 'delivered' ? 'info' : ($message->delivery_status === 'failed' ? 'danger' : ($message->delivery_status === 'spam' ? 'warning' : 'secondary'))) }}"></div>
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <h6 class="mb-0 gradient-text">{{ $message->subject }}</h6>
                                        <small class="text-muted">{{ $message->created_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                    <p class="text-muted mb-3">{{ Str::limit($message->message, 150) }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center flex-wrap gap-2">
                                            <small class="text-muted">
                                                <i class="bi bi-person me-1"></i>Par {{ $message->adminUser->name }}
                                            </small>
                                            <span class="badge-soft bg-{{ $message->delivery_status === 'read' ? 'success' : ($message->delivery_status === 'delivered' ? 'info' : ($message->delivery_status === 'failed' ? 'danger' : ($message->delivery_status === 'spam' ? 'warning' : 'secondary'))) }} text-white">
                                                <i class="bi bi-{{ $message->delivery_status === 'read' ? 'check-circle' : ($message->delivery_status === 'delivered' ? 'envelope-check' : ($message->delivery_status === 'failed' ? 'exclamation-triangle' : ($message->delivery_status === 'spam' ? 'shield-exclamation' : 'clock'))) }} me-1"></i>
                                                {{ $message->delivery_status_name }}
                                            </span>
                                            @if($message->is_spam)
                                                <span class="badge-soft bg-warning text-dark">
                                                    <i class="bi bi-shield-exclamation me-1"></i>Spam
                                                </span>
                                            @endif
                                            @if($message->error_message)
                                                <span class="badge-soft bg-danger text-white" title="{{ $message->error_message }}">
                                                    <i class="bi bi-exclamation-circle me-1"></i>Erreur
                                                </span>
                                            @endif
                                        </div>
                                        <div class="d-flex gap-1">
                                            <button class="btn btn-soft btn-soft-outline btn-sm" onclick="viewMessage({{ $message->id }})">
                                                <i class="bi bi-eye me-1"></i>Voir
                                            </button>
                                            @if($message->delivery_status === 'failed')
                                                <button class="btn btn-soft btn-soft-warning btn-sm" onclick="retryMessage({{ $message->id }})">
                                                    <i class="bi bi-arrow-clockwise me-1"></i>Réessayer
                                                </button>
                                            @endif
                                            @if($message->delivery_status === 'sent' || $message->delivery_status === 'delivered')
                                                <button class="btn btn-soft btn-soft-danger btn-sm" onclick="markAsSpam({{ $message->id }})">
                                                    <i class="bi bi-shield-exclamation me-1"></i>Marquer spam
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                    @if($message->delivered_at || $message->read_at)
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                @if($message->delivered_at)
                                                    <i class="bi bi-envelope-check me-1"></i>Livré le {{ $message->delivered_at->format('d/m/Y H:i') }}
                                                @endif
                                                @if($message->read_at)
                                                    <i class="bi bi-eye me-1"></i>Lu le {{ $message->read_at->format('d/m/Y H:i') }}
                                                @endif
                                            </small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="bi bi-chat-left-text" style="font-size: 4rem; color: var(--soft-gray-400);"></i>
                        </div>
                        <h5 class="gradient-text mb-3">Aucun message envoyé</h5>
                        <p class="text-muted mb-4">Commencez la conversation avec ce client</p>
                        <button class="btn btn-soft btn-soft-primary" onclick="showMessageModal('{{ $order->customer_name }}', '{{ $order->customer_email }}', {{ $order->id }})">
                            <i class="bi bi-envelope me-2"></i>Envoyer le premier message
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Refund Details -->
        @if($order->isRefunded())
            <div class="table-card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="bi bi-arrow-return-left me-2"></i>Détails du remboursement</h5>
                </div>
                <div class="card-body">
                    <p><strong>Montant remboursé :</strong> {{ number_format($order->refund_amount, 2) }}€</p>
                    <p><strong>Date :</strong> {{ $order->refunded_at?->format('d/m/Y à H:i') }}</p>
                    @if($order->refund_reason)
                        <p><strong>Raison :</strong> {{ $order->refund_reason }}</p>
                    @endif
                </div>
            </div>
        @endif
    </div>
    
    <!-- Actions Sidebar -->
    <div class="col-lg-4">
        <div class="card-soft mb-4">
            <div class="card-soft-header">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-tools me-2"></i>Actions
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="d-grid gap-3">
                    @if($order->status === 'paid_pending_validation')
                        <button type="button" 
                                class="btn btn-soft btn-soft-success" 
                                onclick="showValidationModal()">
                            <i class="bi bi-check-circle me-2"></i>Valider la commande
                        </button>
                    @endif
                    
                    @if($order->isPending())
                        <button type="button" 
                                class="btn btn-soft btn-soft-danger" 
                                onclick="confirmAction('{{ route('admin.orders.cancel', $order) }}', 'Annuler cette commande ?', 'Annuler')">
                            <i class="bi bi-x-circle me-2"></i>Annuler la commande
                        </button>
                    @endif
                    
                    @if($order->isPaid())
                        <a href="{{ route('admin.orders.invoice', $order) }}" class="btn btn-soft btn-soft-outline">
                            <i class="bi bi-file-pdf me-2"></i>Télécharger la facture
                        </a>
                        <button type="button" 
                                class="btn btn-soft btn-soft-warning" 
                                onclick="showRefundModal({{ $order->id }}, '{{ $order->order_number }}', {{ $order->amount }})">
                            <i class="bi bi-arrow-return-left me-2"></i>Rembourser
                        </button>
                        
                        @if(!$order->hasM3UCredentials())
                            <button type="button" 
                                    class="btn btn-soft btn-soft-primary" 
                                    onclick="generateM3U({{ $order->id }})">
                                <i class="bi bi-magic me-2"></i>Générer M3U
                            </button>
                            <button type="button" 
                                    class="btn btn-soft btn-soft-outline" 
                                    onclick="showM3UExtractModal()">
                                <i class="bi bi-upload me-2"></i>Extraire M3U
                            </button>
                            <button type="button" 
                                    class="btn btn-soft btn-soft-outline" 
                                    onclick="showM3UUrlExtractModal()">
                                <i class="bi bi-link-45deg me-2"></i>Extraire depuis URL
                            </button>
                        @else
                            <button type="button" 
                                    class="btn btn-soft btn-soft-info" 
                                    onclick="testM3UConnection({{ $order->id }})">
                                <i class="bi bi-wifi me-2"></i>Tester M3U
                            </button>
                            <button type="button" 
                                    class="btn btn-soft btn-soft-warning" 
                                    onclick="showM3UEditModal()">
                                <i class="bi bi-pencil me-2"></i>Modifier M3U
                            </button>
                            <button type="button" 
                                    class="btn btn-soft btn-soft-danger" 
                                    onclick="deleteM3U({{ $order->id }})">
                                <i class="bi bi-trash me-2"></i>Supprimer M3U
                            </button>
                            <a href="{{ $order->m3u_url }}" target="_blank" class="btn btn-soft btn-soft-outline">
                                <i class="bi bi-download me-2"></i>Télécharger M3U
                            </a>
                        @endif
                    @endif
                    
                    <!-- Send Message to Customer -->
                    <button type="button" 
                            class="btn btn-soft btn-soft-primary" 
                            onclick="showMessageModal('{{ $order->customer_name }}', '{{ $order->customer_email }}', {{ $order->id }})">
                        <i class="bi bi-envelope me-2"></i>Envoyer un message
                    </button>
                    
                    @if($order->isPending() || $order->isCancelled())
                        <button type="button" 
                                class="btn btn-soft btn-soft-outline" 
                                style="border-color: var(--soft-danger); color: var(--soft-danger);"
                                onclick="confirmDelete({{ $order->id }}, '{{ $order->order_number }}')">
                            <i class="bi bi-trash me-2"></i>Supprimer
                        </button>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Customer Actions -->
        <div class="card-soft mb-4">
            <div class="card-soft-header">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-person me-2"></i>Actions client
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="d-grid gap-3">
                    <a href="mailto:{{ $order->customer_email }}?subject=Votre commande {{ $order->order_number }}" 
                       class="btn btn-soft btn-soft-outline">
                        <i class="bi bi-envelope me-2"></i>Contacter par email
                    </a>
                    @if($order->isPaid() && $order->iptv_code)
                        <button type="button" class="btn btn-soft btn-soft-outline" onclick="copyIptvCode()">
                            <i class="bi bi-clipboard me-2"></i>Copier le code IPTV
                        </button>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Timeline -->
        <div class="card-soft">
            <div class="card-soft-header">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-clock-history me-2"></i>Historique
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="timeline-soft">
                    <div class="timeline-item-soft">
                        <div class="timeline-marker-soft bg-primary"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1 gradient-text">Commande créée</h6>
                            <small class="text-muted">{{ $order->created_at->format('d/m/Y à H:i') }}</small>
                        </div>
                    </div>
                    
                    @if($order->status === 'paid_pending_validation' || $order->isPaid())
                        <div class="timeline-item-soft">
                            <div class="timeline-marker-soft bg-warning"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1 gradient-text">Paiement confirmé</h6>
                                <small class="text-muted">En attente de validation admin</small>
                            </div>
                        </div>
                    @endif
                    
                    @if($order->isPaid())
                        <div class="timeline-item-soft">
                            <div class="timeline-marker-soft bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1 gradient-text">Commande validée</h6>
                                <small class="text-muted">{{ $order->updated_at->format('d/m/Y à H:i') }}</small>
                            </div>
                        </div>
                    @endif
                    
                    @if($order->iptv_code)
                        <div class="timeline-item-soft">
                            <div class="timeline-marker-soft bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1 gradient-text">Code IPTV généré</h6>
                                <small class="text-muted">{{ $order->updated_at->format('d/m/Y à H:i') }}</small>
                            </div>
                        </div>
                    @endif
                    
                    @if($order->isRefunded())
                        <div class="timeline-item-soft">
                            <div class="timeline-marker-soft bg-danger"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1 gradient-text">Remboursé</h6>
                                <small class="text-muted">{{ $order->refunded_at?->format('d/m/Y à H:i') }}</small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Send Message Modal -->
<div class="modal fade" id="messageModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Envoyer un message au client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="messageForm" method="POST" action="{{ route('admin.orders.send-message', $order) }}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="message_recipient" class="form-label">Destinataire</label>
                                <input type="text" class="form-control" id="message_recipient" readonly>
                            </div>
                            
                            <div class="mb-3">
                                <label for="message_subject" class="form-label">Sujet</label>
                                <input type="text" class="form-control" id="message_subject" name="subject" required placeholder="Sujet du message...">
                            </div>
                            
                            <div class="mb-3">
                                <label for="message_type" class="form-label">Type de message</label>
                                <select class="form-select" id="message_type" name="type" required>
                                    <option value="order_update">Mise à jour commande</option>
                                    <option value="support">Support client</option>
                                    <option value="notification">Notification</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="message_content" class="form-label">Message</label>
                                <textarea class="form-control" id="message_content" name="message" rows="8" required placeholder="Votre message au client..."></textarea>
                                <div class="form-text">
                                    Variables disponibles : 
                                    [customer_name], 
                                    [iptv_code], 
                                    [order_number],
                                     [portal_url], 
                                     [m3u_url]
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <h6 class="fw-bold mb-3">Templates prédéfinis</h6>
                            @php
                                try {
                                    $templates = App\Models\MessageTemplate::active()->ordered()->get();
                                } catch (\Exception $e) {
                                    $templates = collect();
                                }
                            @endphp
                            
                            @if($templates->count() > 0)
                                @foreach($templates as $template)
                                    <div class="card mb-2">
                                        <div class="card-body p-2">
                                            <h6 class="card-title small mb-1">{{ $template->name }}</h6>
                                            <p class="card-text small text-muted mb-2">{{ Str::limit($template->message, 60) }}</p>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-primary w-100" 
                                                    onclick="loadTemplate({{ $template->id }})">
                                                Utiliser ce template
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center text-muted py-3">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Aucun template disponible
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send me-2"></i>Envoyer le message
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- M3U Extract Modal -->
<div class="modal fade" id="m3uExtractModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Extraire les identifiants M3U</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="m3uExtractForm" method="POST" action="{{ route('admin.orders.extract-m3u', $order) }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="m3u_file" class="form-label">Fichier M3U</label>
                        <input type="file" class="form-control" id="m3u_file" name="m3u_file" accept=".m3u,.txt" required>
                        <div class="form-text">Sélectionnez un fichier M3U (.m3u ou .txt) pour extraire les identifiants.</div>
                    </div>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Le fichier M3U sera analysé pour extraire automatiquement les identifiants de connexion.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-upload me-2"></i>Extraire les identifiants
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- M3U URL Extract Modal -->
<div class="modal fade" id="m3uUrlExtractModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Extraire les identifiants M3U depuis une URL</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="m3uUrlExtractForm" method="POST" action="{{ route('admin.orders.extract-m3u-from-url', $order) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="m3u_url" class="form-label">URL M3U</label>
                        <input type="url" class="form-control" id="m3u_url" name="m3u_url" placeholder="https://iptv2smartv.com/playlist.m3u" required>
                        <div class="form-text">Entrez l'URL complète du fichier M3U pour extraire les identifiants.</div>
                    </div>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        L'URL M3U sera téléchargée et analysée pour extraire automatiquement les identifiants de connexion.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-link-45deg me-2"></i>Extraire depuis l'URL
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- M3U Edit Modal -->
<div class="modal fade" id="m3uEditModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier les identifiants M3U</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="m3uEditForm" method="POST" action="{{ route('admin.orders.update-m3u', $order) }}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_m3u_username" class="form-label">Nom d'utilisateur</label>
                                <input type="text" class="form-control" id="edit_m3u_username" name="m3u_username" value="{{ $order->m3u_username ?? '' }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_m3u_password" class="form-label">Mot de passe</label>
                                <input type="text" class="form-control" id="edit_m3u_password" name="m3u_password" value="{{ $order->m3u_password ?? '' }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_m3u_server_url" class="form-label">URL du serveur</label>
                        <input type="url" class="form-control" id="edit_m3u_server_url" name="m3u_server_url" value="{{ $order->m3u_server_url ?? '' }}" placeholder="http://portal.iptv-pro.com:8080" required>
                        <div class="form-text">URL de base du serveur IPTV (ex: http://portal.iptv-pro.com:8080)</div>
                    </div>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        La modification des identifiants M3U mettra à jour automatiquement l'URL M3U générée.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-pencil me-2"></i>Modifier les identifiants
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Message Detail Modal -->
<div class="modal fade" id="messageDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Détails du message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="messageDetailContent">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Validation Modal -->
<div class="modal fade" id="validationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-check-circle me-2"></i>Valider la commande {{ $order->order_number }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="validationForm" method="POST" action="{{ route('admin.orders.validate', $order) }}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Informations de la commande</h6>
                            <div class="p-3 bg-light rounded-3 mb-3">
                                <p class="mb-1"><strong>Client:</strong> {{ $order->customer_name }}</p>
                                <p class="mb-1"><strong>Email:</strong> {{ $order->customer_email }}</p>
                                <p class="mb-1"><strong>Montant:</strong> {{ $order->formatted_amount }}</p>
                                <p class="mb-0"><strong>Abonnement:</strong> {{ $order->subscription ? $order->subscription->name : 'Pack Revendeur' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Configuration M3U</h6>
                            <div class="mb-3">
                                <label for="validation_m3u_username" class="form-label">Nom d'utilisateur M3U</label>
                                <input type="text" class="form-control" id="validation_m3u_username" name="m3u_username" value="{{ $order->iptv_code ?? '' }}" required>
                                <div class="form-text">Identifiant pour la connexion M3U</div>
                            </div>
                            <div class="mb-3">
                                <label for="validation_m3u_password" class="form-label">Mot de passe M3U</label>
                                <input type="text" class="form-control" id="validation_m3u_password" name="m3u_password" value="{{ $order->iptv_code ?? '' }}" required>
                                <div class="form-text">Mot de passe pour la connexion M3U</div>
                            </div>
                            <div class="mb-3">
                                <label for="validation_m3u_server_url" class="form-label">URL du serveur</label>
                                <input type="url" class="form-control" id="validation_m3u_server_url" name="m3u_server_url" value="http://portal.iptv-pro.com:8080" required>
                                <div class="form-text">URL de base du serveur IPTV</div>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="mb-3">
                        <label for="validation_message" class="form-label">
                            <i class="bi bi-envelope me-2"></i>Message personnalisé au client
                        </label>
                        <textarea class="form-control" id="validation_message" name="validation_message" rows="6" placeholder="Tapez votre message personnalisé ici...">Bonjour {{ $order->customer_name }},

Votre commande {{ $order->order_number }} a été validée avec succès !

Voici vos identifiants IPTV :
- Code IPTV : [iptv_code]
- Nom d'utilisateur M3U : [m3u_username]
- Mot de passe M3U : [m3u_password]
- URL M3U : [m3u_url]

Vous pouvez maintenant profiter de votre abonnement IPTV.

Cordialement,
L'équipe {{ config('app.name') }}</textarea>
                        <div class="form-text">
                            Variables disponibles : [customer_name], [order_number], [iptv_code], [m3u_username], [m3u_password], [m3u_url], [subscription_name], [expires_at]
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Note :</strong> La validation de cette commande va :
                        <ul class="mb-0 mt-2">
                            <li>Générer le code IPTV et les identifiants M3U</li>
                            <li>Envoyer un email de confirmation au client</li>
                            <li>Enregistrer le message dans l'historique des communications</li>
                            <li>Marquer la commande comme payée</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-2"></i>Valider la commande
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Other Modals -->
@include('admin.orders.modals')
@endsection

@push('scripts')
<script>
    // Templates data for JavaScript
    @php
        try {
            $templatesJson = App\Models\MessageTemplate::active()->get()->toJson();
        } catch (\Exception $e) {
            $templatesJson = '[]';
        }
    @endphp
    const templates = {!! $templatesJson !!};
    
    function copyIptvCode() {
        const code = '{{ $order->iptv_code ?? "" }}';
        if (code) {
            navigator.clipboard.writeText(code).then(function() {
                const btn = event.target.closest('button');
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="bi bi-check me-2"></i>Copié !';
                btn.classList.remove('btn-outline-success');
                btn.classList.add('btn-success');
                
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-outline-success');
                }, 2000);
            });
        }
    }

    function showMessageModal(customerName, customerEmail, orderId) {
        document.getElementById('message_recipient').value = customerName + ' (' + customerEmail + ')';
        document.getElementById('message_subject').value = 'Concernant votre commande {{ $order->order_number }}';
        
        const modal = new bootstrap.Modal(document.getElementById('messageModal'));
        modal.show();
    }

    function loadTemplate(templateId) {
        const template = templates.find(t => t.id === templateId);
        if (template) {
            document.getElementById('message_subject').value = renderTemplate(template.subject);
            document.getElementById('message_content').value = renderTemplate(template.message);
        }
    }

    function useTemplate(deviceType) {
        const template = templates.find(t => t.device_type === deviceType);
        if (template) {
            loadTemplate(template.id);
        }
    }

    function renderTemplate(text) {
        if (!text) return '';
        
        const variables = {
            customer_name: '{{ $order->customer_name ?? "" }}',
            iptv_code: '{{ $order->iptv_code ?? "" }}',
            order_number: '{{ $order->order_number ?? "" }}',
            subscription_name: '{{ $order->subscription ? $order->subscription->name : "Pack Revendeur" }}',
            expires_at: '{{ $order->expires_at ? $order->expires_at->format("d/m/Y") : "N/A" }}',
            portal_url: 'http://portal.iptv-pro.com/c/',
            m3u_url: 'http://portal.iptv-pro.com/get.php?username={{ $order->iptv_code ?? "" }}&password={{ $order->iptv_code ?? "" }}&type=m3u_plus',
            app_name: '{{ config("app.name") }}',
            tutorials_link: '{{ route("tutorials.index") }}',
            support_link: '{{ route("contact.index") }}',
            server_url: 'http://portal.iptv-pro.com:8080',
            username: '{{ $order->iptv_code ?? "" }}',
            password: '{{ $order->iptv_code ?? "" }}',
            test_link: 'http://portal.iptv-pro.com/test.php'
        };
        
        let rendered = text;
        for (const [key, value] of Object.entries(variables)) {
            // Échapper les accolades pour éviter l'interprétation JavaScript
            const pattern = new RegExp('\\{\\{' + key + '\\}\\}', 'g');
            rendered = rendered.replace(pattern, value);
        }
        
        return rendered;
    }

    function viewMessage(messageId) {
        // Load message details via AJAX
        fetch(`/admin/messages/${messageId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('messageDetailContent').innerHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Informations du message</h6>
                            <p><strong>Sujet:</strong> ${data.subject}</p>
                            <p><strong>Type:</strong> ${data.type_name}</p>
                            <p><strong>Statut:</strong> ${data.delivery_status_name}</p>
                            <p><strong>Envoyé le:</strong> ${data.sent_at || 'Non envoyé'}</p>
                            ${data.delivered_at ? `<p><strong>Livré le:</strong> ${data.delivered_at}</p>` : ''}
                            ${data.read_at ? `<p><strong>Lu le:</strong> ${data.read_at}</p>` : ''}
                        </div>
                        <div class="col-md-6">
                            <h6>Destinataire</h6>
                            <p><strong>Nom:</strong> ${data.recipient_name}</p>
                            <p><strong>Email:</strong> ${data.recipient_email}</p>
                            <p><strong>Envoyé par:</strong> ${data.admin_user.name}</p>
                        </div>
                    </div>
                    <hr>
                    <h6>Contenu du message</h6>
                    <div class="bg-light p-3 rounded">
                        ${data.message.replace(/\n/g, '<br>')}
                    </div>
                    ${data.error_message ? `
                        <div class="alert alert-danger mt-3">
                            <strong>Erreur:</strong> ${data.error_message}
                        </div>
                    ` : ''}
                `;
                new bootstrap.Modal(document.getElementById('messageDetailModal')).show();
            })
            .catch(error => {
                console.error('Error loading message:', error);
                alert('Erreur lors du chargement du message');
            });
    }

    function generateM3U(orderId) {
        if (confirm('Générer automatiquement les identifiants M3U pour cette commande ?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/orders/${orderId}/generate-m3u`;
            form.innerHTML = '@csrf';
            document.body.appendChild(form);
            form.submit();
        }
    }

    function showM3UExtractModal() {
        new bootstrap.Modal(document.getElementById('m3uExtractModal')).show();
    }

    function showM3UUrlExtractModal() {
        new bootstrap.Modal(document.getElementById('m3uUrlExtractModal')).show();
    }

    function showM3UEditModal() {
        new bootstrap.Modal(document.getElementById('m3uEditModal')).show();
    }

    function showValidationModal() {
        // Synchroniser les champs M3U avec le code IPTV
        const iptvCode = '{{ $order->iptv_code ?? "" }}';
        if (iptvCode) {
            document.getElementById('validation_m3u_username').value = iptvCode;
            document.getElementById('validation_m3u_password').value = iptvCode;
        }
        
        new bootstrap.Modal(document.getElementById('validationModal')).show();
    }

    function deleteM3U(orderId) {
        if (confirm('Êtes-vous sûr de vouloir supprimer les identifiants M3U pour cette commande ? Cette action ne peut pas être annulée.')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/orders/${orderId}/delete-m3u`;
            form.innerHTML = '@csrf @method("DELETE")';
            document.body.appendChild(form);
            form.submit();
        }
    }

    function testM3UConnection(orderId) {
        const btn = event.target.closest('button');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Test...';
        btn.disabled = true;

        fetch(`/admin/orders/${orderId}/test-m3u`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('✅ Connexion M3U réussie !\n' + data.message);
                } else {
                    alert('❌ Échec de la connexion M3U\n' + data.message);
                }
            })
            .catch(error => {
                console.error('Error testing M3U:', error);
                alert('Erreur lors du test de connexion');
            })
            .finally(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
    }

    function copyToClipboard(elementId) {
        const element = document.getElementById(elementId);
        const text = element.value;
        
        navigator.clipboard.writeText(text).then(function() {
            const btn = event.target;
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-check me-1"></i>Copié !';
            btn.classList.remove('btn-soft-info');
            btn.classList.add('btn-success');
            
            setTimeout(function() {
                btn.innerHTML = originalText;
                btn.classList.remove('btn-success');
                btn.classList.add('btn-soft-info');
            }, 2000);
        }).catch(function(err) {
            console.error('Erreur lors de la copie: ', err);
            alert('Impossible de copier automatiquement. Veuillez sélectionner le texte manuellement.');
        });
    }

    function retryMessage(messageId) {
        if (confirm('Réessayer l\'envoi de ce message ?')) {
            // TODO: Implement retry functionality
            console.log('Retry message:', messageId);
        }
    }

    function markAsSpam(messageId) {
        if (confirm('Marquer ce message comme spam ? Cette action ne peut pas être annulée.')) {
            // TODO: Implement spam marking functionality
            console.log('Mark as spam:', messageId);
        }
    }

    // Form submission for message
    document.getElementById('messageForm').addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="spinner-border spinner-border-sm me-2"></i>Envoi...';
    });

    // Include other modal functions
    function confirmAction(actionUrl, message, buttonText) {
        if (confirm(message)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = actionUrl;
            form.innerHTML = '@csrf';
            document.body.appendChild(form);
            form.submit();
        }
    }

    function showRefundModal(orderId, orderNumber, orderAmount) {
        // Simplified refund for now
        const reason = prompt('Raison du remboursement (optionnel):');
        const amount = prompt('Montant à rembourser (€):', orderAmount);
        
        if (amount !== null) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/orders/${orderId}/refund`;
            form.innerHTML = `
                @csrf
                <input type="hidden" name="refund_amount" value="${amount}">
                <input type="hidden" name="refund_reason" value="${reason || ''}">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }

    function confirmDelete(orderId, orderNumber) {
        if (confirm(`Êtes-vous sûr de vouloir supprimer la commande ${orderNumber} ?`)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/orders/${orderId}`;
            form.innerHTML = '@csrf @method("DELETE")';
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endpush

@push('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 2rem;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 0.75rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #dee2e6;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .timeline-marker {
        position: absolute;
        left: -2.25rem;
        top: 0.25rem;
        width: 1rem;
        height: 1rem;
        border-radius: 50%;
        border: 2px solid white;
    }

    .timeline-content h6 {
        margin-bottom: 0.25rem;
        font-weight: 600;
    }
</style>
@endpush