@extends('layouts.soft-ui')

@section('title', 'Tutoriels d\'Installation IPTV')

@section('content')
<!-- Hero Section -->
<section class="position-relative overflow-hidden py-5 text-white" style="background: var(--gradient-info);">
    <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10">
        <svg width="100%" height="100%" viewBox="0 0 1000 1000">
            <defs>
                <pattern id="tutorial-pattern" x="0" y="0" width="60" height="60" patternUnits="userSpaceOnUse">
                    <circle cx="30" cy="30" r="2" fill="white"/>
                    <circle cx="10" cy="10" r="1" fill="white" opacity="0.5"/>
                    <circle cx="50" cy="50" r="1.5" fill="white" opacity="0.7"/>
                </pattern>
            </defs>
            <rect width="1000" height="1000" fill="url(#tutorial-pattern)"/>
        </svg>
    </div>
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">Tutoriels d'Installation</h1>
                <p class="lead mb-4 opacity-75">
                    Guides détaillés pour installer et configurer votre service IPTV sur tous vos appareils.
                </p>
                <div class="row">
                    <div class="col-6 col-md-3 mb-3">
                        <div class="text-center">
                            <div class="feature-icon success mb-2 mx-auto">
                                <i class="bi bi-play-circle fs-3"></i>
                            </div>
                            <div class="fw-bold">Vidéos HD</div>
                            <small class="opacity-75">Tutoriels en vidéo</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-3">
                        <div class="text-center">
                            <div class="feature-icon warning mb-2 mx-auto">
                                <i class="bi bi-list-ol fs-3"></i>
                            </div>
                            <div class="fw-bold">Étape par étape</div>
                            <small class="opacity-75">Instructions détaillées</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-3">
                        <div class="text-center">
                            <div class="feature-icon mb-2 mx-auto" style="background: var(--gradient-secondary);">
                                <i class="bi bi-devices fs-3"></i>
                            </div>
                            <div class="fw-bold">Multi-appareils</div>
                            <small class="opacity-75">Tous les devices</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-3">
                        <div class="text-center">
                            <div class="feature-icon mb-2 mx-auto" style="background: var(--gradient-danger);">
                                <i class="bi bi-headset fs-3"></i>
                            </div>
                            <div class="fw-bold">Support</div>
                            <small class="opacity-75">Aide personnalisée</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-center">
                <!-- Tutorial Devices SVG -->
                <svg width="350" height="250" viewBox="0 0 350 250" class="img-fluid">
                    <defs>
                        <linearGradient id="deviceGradient1" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#17c1e8"/>
                            <stop offset="100%" style="stop-color:#21d4fd"/>
                        </linearGradient>
                        <linearGradient id="deviceGradient2" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#82d616"/>
                            <stop offset="100%" style="stop-color:#98ec2d"/>
                        </linearGradient>
                    </defs>
                    
                    <!-- Smart TV -->
                    <g transform="translate(50, 50)">
                        <rect width="120" height="80" rx="8" fill="url(#deviceGradient1)" opacity="0.9"/>
                        <rect x="5" y="5" width="110" height="60" rx="4" fill="rgba(255,255,255,0.3)"/>
                        <text x="60" y="40" text-anchor="middle" fill="white" font-size="12" font-weight="bold">Smart TV</text>
                        <rect x="50" y="130" width="20" height="15" rx="3" fill="url(#deviceGradient1)"/>
                    </g>
                    
                    <!-- Smartphone -->
                    <g transform="translate(200, 30)">
                        <rect width="40" height="70" rx="8" fill="url(#deviceGradient2)" opacity="0.8"/>
                        <rect x="5" y="10" width="30" height="45" rx="3" fill="rgba(255,255,255,0.3)"/>
                        <circle cx="20" cy="60" r="3" fill="rgba(255,255,255,0.5)"/>
                        <text x="20" y="35" text-anchor="middle" fill="white" font-size="6" font-weight="bold">IPTV</text>
                    </g>
                    
                    <!-- Tablet -->
                    <g transform="translate(270, 80)">
                        <rect width="60" height="45" rx="6" fill="url(#deviceGradient1)" opacity="0.7"/>
                        <rect x="5" y="5" width="50" height="30" rx="3" fill="rgba(255,255,255,0.3)"/>
                        <text x="30" y="25" text-anchor="middle" fill="white" font-size="8" font-weight="bold">Tablet</text>
                    </g>
                    
                    <!-- PC -->
                    <g transform="translate(80, 150)">
                        <rect width="80" height="50" rx="4" fill="url(#deviceGradient2)" opacity="0.6"/>
                        <rect x="5" y="5" width="70" height="35" rx="2" fill="rgba(255,255,255,0.3)"/>
                        <text x="40" y="26" text-anchor="middle" fill="white" font-size="8" font-weight="bold">PC/Mac</text>
                        <rect x="35" y="200" width="10" height="20" rx="2" fill="url(#deviceGradient2)"/>
                    </g>
                    
                    <!-- Connection waves -->
                    <g opacity="0.5">
                        <circle cx="175" cy="125" r="30" fill="none" stroke="rgba(255,255,255,0.3)" stroke-width="2">
                            <animate attributeName="r" values="30;50;30" dur="3s" repeatCount="indefinite"/>
                            <animate attributeName="opacity" values="0.3;0;0.3" dur="3s" repeatCount="indefinite"/>
                        </circle>
                        <circle cx="175" cy="125" r="20" fill="none" stroke="rgba(255,255,255,0.5)" stroke-width="2">
                            <animate attributeName="r" values="20;40;20" dur="2s" repeatCount="indefinite"/>
                            <animate attributeName="opacity" values="0.5;0;0.5" dur="2s" repeatCount="indefinite"/>
                        </circle>
                    </g>
                </svg>
            </div>
        </div>
    </div>
</section>

<!-- Search and Filter -->
<section class="py-4" style="background: linear-gradient(310deg, #f8f9fa 0%, #fff 100%);">
    <div class="container">
        <div class="card-soft">
            <div class="card-body p-4">
                <form method="GET" action="{{ route('tutorials.index') }}">
                    <div class="row align-items-end">
                        <div class="col-md-6 mb-3">
                            <label for="search" class="form-label fw-bold">Rechercher un tutoriel</label>
                            <div class="input-group input-group-soft">
                                <span class="input-group-text">
                                    <i class="bi bi-search text-primary"></i>
                                </span>
                                <input type="text" 
                                       class="form-control form-control-soft" 
                                       id="search" 
                                       name="search" 
                                       value="{{ $search }}" 
                                       placeholder="Rechercher par titre ou contenu...">
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="device" class="form-label fw-bold">Filtrer par appareil</label>
                            <select class="form-select form-control-soft" id="device" name="device">
                                <option value="">Tous les appareils</option>
                                @foreach($deviceTypes as $key => $name)
                                    <option value="{{ $key }}" {{ $deviceFilter === $key ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-2 mb-3">
                            <button type="submit" class="btn btn-soft btn-soft-primary w-100">
                                <i class="bi bi-funnel me-1"></i>Filtrer
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Tutorials Content -->
<section class="py-5">
    <div class="container">
        @if($tutorials->count() > 0)
            @if($deviceFilter)
                <!-- Filtered results -->
                <div class="mb-4">
                    <h2 class="h4 mb-3 d-flex align-items-center" style="color: var(--soft-dark);">
                        <div class="feature-icon me-3" style="width: 3rem; height: 3rem;">
                            <i class="bi bi-{{ $deviceFilter === 'android' ? 'android2' : ($deviceFilter === 'ios' ? 'apple' : 'display') }}"></i>
                        </div>
                        Tutoriels pour {{ $deviceTypes[$deviceFilter] }}
                        <span class="badge ms-2" style="background: var(--gradient-primary); color: white;">{{ $tutorials->count() }}</span>
                    </h2>
                </div>
                
                <div class="row">
                    @foreach($tutorials as $tutorial)
                        @include('tutorials.partials.tutorial-card-soft', ['tutorial' => $tutorial])
                    @endforeach
                </div>
            @else
                <!-- Group by device -->
                @foreach($tutorialsByDevice as $deviceType => $deviceTutorials)
                    <div class="mb-5">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h2 class="h4 mb-0 d-flex align-items-center" style="color: var(--soft-dark);">
                                <div class="feature-icon me-3" style="width: 3rem; height: 3rem;">
                                    <i class="bi bi-{{ $deviceType === 'android' ? 'android2' : ($deviceType === 'ios' ? 'apple' : 'display') }}"></i>
                                </div>
                                {{ $deviceTypes[$deviceType] ?? ucfirst($deviceType) }}
                                <span class="badge ms-2" style="background: var(--gradient-secondary); color: white;">{{ $deviceTutorials->count() }}</span>
                            </h2>
                            <a href="{{ route('tutorials.index', ['device' => $deviceType]) }}" class="btn btn-soft-outline">
                                Voir tout
                            </a>
                        </div>
                        
                        <div class="row">
                            @foreach($deviceTutorials->take(3) as $tutorial)
                                @include('tutorials.partials.tutorial-card-soft', ['tutorial' => $tutorial])
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @endif
        @else
            <div class="text-center py-5">
                <div class="feature-icon warning mx-auto mb-4" style="width: 5rem; height: 5rem;">
                    <i class="bi bi-book fs-1"></i>
                </div>
                <h3 class="fw-bold mb-3">Aucun tutoriel trouvé</h3>
                @if($search || $deviceFilter)
                    <p class="text-muted">Aucun tutoriel ne correspond à vos critères de recherche.</p>
                    <a href="{{ route('tutorials.index') }}" class="btn btn-soft btn-soft-primary">
                        <i class="bi bi-arrow-left me-2"></i>Voir tous les tutoriels
                    </a>
                @else
                    <p class="text-muted">Les tutoriels seront bientôt disponibles.</p>
                    <a href="{{ route('contact.index') }}" class="btn btn-soft btn-soft-primary">
                        <i class="bi bi-envelope me-2"></i>Nous contacter
                    </a>
                @endif
            </div>
        @endif
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 position-relative overflow-hidden">
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: var(--gradient-primary);"></div>
    <div class="position-relative">
        <div class="container text-center text-white">
            <h2 class="display-6 fw-bold mb-4">Besoin d'aide ?</h2>
            <p class="lead mb-4 opacity-75">
                Notre équipe support est disponible 24h/24 pour vous accompagner dans l'installation.
            </p>
            <div class="d-flex flex-wrap justify-content-center gap-3">
                <a href="{{ route('contact.index') }}" class="btn btn-soft" style="background: white; color: var(--soft-primary);">
                    <i class="bi bi-headset me-2"></i>Contacter le support
                </a>
                <a href="{{ route('subscriptions.index') }}" class="btn btn-soft-outline" style="border-color: white; color: white;">
                    <i class="bi bi-tv me-2"></i>Voir les abonnements
                </a>
            </div>
        </div>
    </div>
</section>
@endsection