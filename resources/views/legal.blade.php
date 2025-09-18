@extends('layouts.app')

@section('title', $title)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
                </ol>
            </nav>
            
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <h1 class="display-6 fw-bold mb-4 text-primary">{{ $title }}</h1>
                    
                    <div class="content">
                        @if(empty($content) || trim($content) === 'Contenu à configurer dans l\'administration.')
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Information :</strong> Le contenu de cette page n'a pas encore été configuré par l'administrateur.
                            </div>
                            
                            <p>Cette page sera bientôt disponible avec toutes les informations légales nécessaires.</p>
                            
                            <div class="mt-4">
                                <a href="{{ route('home') }}" class="btn btn-primary">
                                    <i class="bi bi-arrow-left me-2"></i>Retour à l'accueil
                                </a>
                            </div>
                        @else
                            <div class="legal-content">
                                {!! nl2br(e($content)) !!}
                            </div>
                            
                            <hr class="my-4">
                            
                            <div class="text-muted small">
                                <i class="bi bi-calendar me-2"></i>
                                Dernière mise à jour : {{ date('d/m/Y') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Liens vers les autres pages légales -->
            <div class="row mt-4">
                <div class="col-md-4 mb-3">
                    <a href="{{ route('legal', 'terms') }}" class="card text-decoration-none h-100 {{ request()->route('page') === 'terms' ? 'border-primary' : '' }}">
                        <div class="card-body text-center">
                            <i class="bi bi-file-text fs-2 text-primary mb-2"></i>
                            <h6 class="card-title">Conditions Générales</h6>
                        </div>
                    </a>
                </div>
                <div class="col-md-4 mb-3">
                    <a href="{{ route('legal', 'privacy') }}" class="card text-decoration-none h-100 {{ request()->route('page') === 'privacy' ? 'border-primary' : '' }}">
                        <div class="card-body text-center">
                            <i class="bi bi-shield-lock fs-2 text-success mb-2"></i>
                            <h6 class="card-title">Politique de Confidentialité</h6>
                        </div>
                    </a>
                </div>
                <div class="col-md-4 mb-3">
                    <a href="{{ route('legal', 'mentions') }}" class="card text-decoration-none h-100 {{ request()->route('page') === 'mentions' ? 'border-primary' : '' }}">
                        <div class="card-body text-center">
                            <i class="bi bi-info-circle fs-2 text-info mb-2"></i>
                            <h6 class="card-title">Mentions Légales</h6>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .legal-content {
        line-height: 1.8;
    }
    
    .legal-content h2 {
        color: var(--primary-color);
        margin-top: 2rem;
        margin-bottom: 1rem;
    }
    
    .legal-content h3 {
        color: var(--secondary-color);
        margin-top: 1.5rem;
        margin-bottom: 0.75rem;
    }
    
    .legal-content p {
        margin-bottom: 1rem;
    }
    
    .legal-content ul, .legal-content ol {
        margin-bottom: 1rem;
        padding-left: 2rem;
    }
    
    .legal-content li {
        margin-bottom: 0.5rem;
    }
</style>
@endpush
