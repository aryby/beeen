@extends('layouts.app')

@section('title', $step->title . ' - ' . $tutorial->title)

@section('content')
<div class="container py-5">
    <!-- Progress Bar -->
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <small class="text-muted">Progression</small>
            <small class="text-muted">{{ $currentStepIndex + 1 }} / {{ $tutorial->steps->count() }}</small>
        </div>
        <div class="progress" style="height: 8px;">
            <div class="progress-bar bg-success" 
                 role="progressbar" 
                 style="width: {{ (($currentStepIndex + 1) / $tutorial->steps->count()) * 100 }}%">
            </div>
        </div>
    </div>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('tutorials.index') }}">Tutoriels</a></li>
            <li class="breadcrumb-item"><a href="{{ route('tutorials.show', $tutorial) }}">{{ $tutorial->title }}</a></li>
            <li class="breadcrumb-item active">Étape {{ $currentStepIndex + 1 }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <!-- Step Content -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <span class="badge bg-light text-primary me-2">{{ $currentStepIndex + 1 }}</span>
                            {{ $step->title }}
                        </h4>
                        <span class="badge bg-light text-primary">
                            {{ $tutorial->device_type_name }}
                        </span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Step Image -->
                    @if($step->hasImage())
                        <div class="text-center mb-4">
                            <img src="{{ $step->image }}" 
                                 alt="{{ $step->title }}" 
                                 class="img-fluid rounded shadow-sm"
                                 style="max-height: 400px;">
                        </div>
                    @endif
                    
                    <!-- Step Video -->
                    @if($step->hasVideo())
                        <div class="mb-4">
                            <div class="ratio ratio-16x9">
                                <iframe src="{{ $step->video_url }}" 
                                        title="{{ $step->title }}"
                                        allowfullscreen
                                        class="rounded"></iframe>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Step Content -->
                    <div class="step-content">
                        {!! $step->content !!}
                    </div>
                </div>
            </div>
            
            <!-- Navigation -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                @if($previousStep)
                    <a href="{{ route('tutorials.step', [$tutorial, $previousStep]) }}" 
                       class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Étape précédente
                    </a>
                @else
                    <a href="{{ route('tutorials.show', $tutorial) }}" 
                       class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Retour au tutoriel
                    </a>
                @endif
                
                @if($nextStep)
                    <a href="{{ route('tutorials.step', [$tutorial, $nextStep]) }}" 
                       class="btn btn-primary">
                        Étape suivante<i class="bi bi-arrow-right ms-2"></i>
                    </a>
                @else
                    <div class="text-end">
                        <div class="mb-2">
                            <span class="badge bg-success fs-6">
                                <i class="bi bi-check-circle me-1"></i>Tutoriel terminé !
                            </span>
                        </div>
                        <a href="{{ route('tutorials.index') }}" class="btn btn-success">
                            <i class="bi bi-collection me-2"></i>Autres tutoriels
                        </a>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Tutorial Overview -->
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-list-ol me-2"></i>Plan du tutoriel</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($tutorial->steps as $index => $tutorialStep)
                            <a href="{{ route('tutorials.step', [$tutorial, $tutorialStep]) }}" 
                               class="list-group-item list-group-item-action d-flex align-items-center {{ $tutorialStep->id === $step->id ? 'active' : '' }}">
                                <div class="bg-{{ $tutorialStep->id === $step->id ? 'white text-primary' : 'primary text-white' }} rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px; min-width: 30px;">
                                    @if($index < $currentStepIndex)
                                        <i class="bi bi-check"></i>
                                    @else
                                        <span class="small fw-bold">{{ $index + 1 }}</span>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold small">{{ $tutorialStep->title }}</div>
                                </div>
                                @if($tutorialStep->hasImage() || $tutorialStep->hasVideo())
                                    <div class="flex-shrink-0">
                                        @if($tutorialStep->hasVideo())
                                            <i class="bi bi-play-circle text-primary"></i>
                                        @elseif($tutorialStep->hasImage())
                                            <i class="bi bi-image text-info"></i>
                                        @endif
                                    </div>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Help Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0"><i class="bi bi-question-circle me-2"></i>Besoin d'aide ?</h6>
                </div>
                <div class="card-body">
                    <p class="small mb-3">
                        Vous rencontrez des difficultés avec cette étape ? Notre équipe est là pour vous aider.
                    </p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('contact.index') }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-headset me-2"></i>Contacter le support
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Tips -->
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="bi bi-lightbulb me-2"></i>Conseils</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-2">
                            <i class="bi bi-check2 text-success me-2"></i>
                            Suivez les étapes dans l'ordre
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check2 text-success me-2"></i>
                            Vérifiez votre connexion Internet
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check2 text-success me-2"></i>
                            Ayez vos identifiants IPTV à portée
                        </li>
                        <li class="mb-0">
                            <i class="bi bi-check2 text-success me-2"></i>
                            N'hésitez pas à nous contacter
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .step-content {
        line-height: 1.8;
    }
    
    .step-content h1, .step-content h2, .step-content h3 {
        color: var(--primary-color);
        margin-top: 2rem;
        margin-bottom: 1rem;
    }
    
    .step-content h1:first-child, .step-content h2:first-child, .step-content h3:first-child {
        margin-top: 0;
    }
    
    .step-content p {
        margin-bottom: 1rem;
    }
    
    .step-content ul, .step-content ol {
        margin-bottom: 1.5rem;
        padding-left: 2rem;
    }
    
    .step-content li {
        margin-bottom: 0.5rem;
    }
    
    .step-content strong {
        color: var(--primary-color);
    }
    
    .step-content em {
        background-color: #fff3cd;
        padding: 2px 4px;
        border-radius: 3px;
        font-style: normal;
    }
    
    .step-content code {
        background-color: #f8f9fa;
        padding: 2px 6px;
        border-radius: 4px;
        font-family: 'Courier New', monospace;
        color: #e83e8c;
    }
</style>
@endpush

@push('scripts')
<script>
    // Auto-scroll to content on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Highlight code blocks
        document.querySelectorAll('.step-content code').forEach(code => {
            code.addEventListener('click', function() {
                this.select();
                document.execCommand('copy');
                
                // Show feedback
                const originalText = this.textContent;
                this.textContent = 'Copié !';
                this.style.backgroundColor = '#d4edda';
                
                setTimeout(() => {
                    this.textContent = originalText;
                    this.style.backgroundColor = '#f8f9fa';
                }, 1500);
            });
        });
        
        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowLeft' && e.ctrlKey) {
                @if($previousStep)
                    window.location.href = '{{ route("tutorials.step", [$tutorial, $previousStep]) }}';
                @endif
            } else if (e.key === 'ArrowRight' && e.ctrlKey) {
                @if($nextStep)
                    window.location.href = '{{ route("tutorials.step", [$tutorial, $nextStep]) }}';
                @endif
            }
        });
    });
</script>
@endpush
