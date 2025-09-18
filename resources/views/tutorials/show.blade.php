@extends('layouts.soft-ui')

@section('title', $tutorial->title . ' - Tutoriel IPTV')

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('tutorials.index') }}">Tutoriels</a></li>
            <li class="breadcrumb-item active">{{ $tutorial->title }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <!-- Tutorial Header -->
            <div class="card-soft mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge bg-info fs-6">
                            <i class="bi bi-{{ $tutorial->device_type === 'android' ? 'android2' : ($tutorial->device_type === 'ios' ? 'apple' : 'display') }} me-2"></i>
                            {{ $tutorial->device_type_name }}
                        </span>
                        <div class="text-muted small">
                            <i class="bi bi-clock me-1"></i>
                            {{ $tutorial->steps->count() * 2 }}-{{ $tutorial->steps->count() * 3 }} minutes
                        </div>
                    </div>
                    
                    <h1 class="display-6 fw-bold mb-3">{{ $tutorial->title }}</h1>
                    
                    @if($tutorial->intro)
                        <p class="lead text-muted mb-4">{{ $tutorial->intro }}</p>
                    @endif
                    
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-list-ol text-primary me-2"></i>
                            <span><strong>{{ $tutorial->steps->count() }}</strong> étape{{ $tutorial->steps->count() > 1 ? 's' : '' }}</span>
                        </div>
                        
                        @if($tutorial->steps->count() > 0)
                            <a href="{{ route('tutorials.step', [$tutorial, $tutorial->steps->first()]) }}" 
                               class="btn btn-soft btn-soft-primary btn-lg">
                                <i class="bi bi-play-circle me-2"></i>Commencer le tutoriel
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Tutorial Steps Overview -->
            <div class="card-soft">
                <div class="card-header text-white" style="background: var(--gradient-info); border-radius: var(--border-radius-soft-lg) var(--border-radius-soft-lg) 0 0;">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-list-check me-2"></i>Étapes du tutoriel</h5>
                </div>
                <div class="card-body p-4">
                    @if($tutorial->steps->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($tutorial->steps as $index => $step)
                                <a href="{{ route('tutorials.step', [$tutorial, $step]) }}" 
                                   class="list-group-item list-group-item-action d-flex align-items-center">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; min-width: 40px;">
                                        <span class="fw-bold">{{ $index + 1 }}</span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $step->title }}</h6>
                                        @if($step->content)
                                            <p class="mb-0 text-muted small">{{ Str::limit(strip_tags($step->content), 100) }}</p>
                                        @endif
                                    </div>
                                    <div class="flex-shrink-0">
                                        @if($step->hasImage())
                                            <i class="bi bi-image text-success me-2" title="Contient des images"></i>
                                        @endif
                                        @if($step->hasVideo())
                                            <i class="bi bi-play-circle text-primary me-2" title="Contient une vidéo"></i>
                                        @endif
                                        <i class="bi bi-chevron-right text-muted"></i>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-exclamation-triangle fs-1 text-warning mb-3"></i>
                            <h5>Tutoriel en cours de préparation</h5>
                            <p class="text-muted">Les étapes de ce tutoriel sont en cours de rédaction.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="bi bi-lightning me-2"></i>Actions Rapides</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($tutorial->steps->count() > 0)
                            <a href="{{ route('tutorials.step', [$tutorial, $tutorial->steps->first()]) }}" 
                               class="btn btn-success">
                                <i class="bi bi-play-circle me-2"></i>Commencer
                            </a>
                        @endif
                        <a href="{{ route('subscriptions.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-tv me-2"></i>Voir les abonnements
                        </a>
                        <a href="{{ route('contact.index') }}" class="btn btn-outline-warning">
                            <i class="bi bi-headset me-2"></i>Besoin d'aide ?
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Device Requirements -->
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Prérequis</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            Appareil {{ $tutorial->device_type_name }}
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            Connexion Internet stable
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            Abonnement IPTV actif
                        </li>
                        <li class="mb-0">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            5-10 minutes de votre temps
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Related Tutorials -->
            @if($relatedTutorials->count() > 0)
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-collection me-2"></i>Autres tutoriels {{ $tutorial->device_type_name }}</h6>
                    </div>
                    <div class="card-body">
                        @foreach($relatedTutorials as $relatedTutorial)
                            <a href="{{ route('tutorials.show', $relatedTutorial) }}" 
                               class="d-flex align-items-center text-decoration-none mb-3">
                                <div class="bg-info text-white rounded d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <i class="bi bi-book"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $relatedTutorial->title }}</div>
                                    <small class="text-muted">{{ $relatedTutorial->steps_count }} étape{{ $relatedTutorial->steps_count > 1 ? 's' : '' }}</small>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Smooth scroll for step links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
</script>
@endpush
