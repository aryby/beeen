<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'IPTV Pro') }} - @yield('title', 'Service IPTV Légal Premium')</title>
    <link rel="icon" type="image/x-icon" href="images/logo.jpg">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Soft UI Design System CSS -->
    <style>
        :root {
            /* Soft UI Colors */
            --soft-primary: #cb0c9f;
            --soft-secondary: #8392ab;
            --soft-success: #82d616;
            --soft-danger: #ea0606;
            --soft-warning: #fbcf33;
            --soft-info: #17c1e8;
            --soft-light: #e9ecef;
            --soft-dark: #344767;
            --soft-white: #fff;
            
            /* Gradients */
            --gradient-primary: linear-gradient(310deg, #7928ca 0%, #ff0080 100%);
            --gradient-secondary: linear-gradient(310deg, #627594 0%, #a8b8d8 100%);
            --gradient-success: linear-gradient(310deg, #17ad37 0%, #98ec2d 100%);
            --gradient-danger: linear-gradient(310deg, #ea0606 0%, #ff667c 100%);
            --gradient-warning: linear-gradient(310deg, #f53939 0%, #fbcf33 100%);
            --gradient-info: linear-gradient(310deg, #2152ff 0%, #21d4fd 100%);
            --gradient-dark: linear-gradient(310deg, #141727 0%, #3a416f 100%);
            
            /* Shadows */
            --shadow-soft: 0 0.3125rem 0.625rem 0 rgba(0, 0, 0, 0.12);
            --shadow-soft-lg: 0 0.625rem 1.875rem 0 rgba(0, 0, 0, 0.1);
            --shadow-soft-xl: 0 1.25rem 3.125rem 0 rgba(0, 0, 0, 0.15);
            
            /* Border radius */
            --border-radius-soft: 0.75rem;
            --border-radius-soft-lg: 1rem;
            --border-radius-soft-xl: 1.5rem;
        }

        * {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: "Open Sans", sans-serif;
            font-weight: 400;
            line-height: 1.6;
            color: var(--soft-dark);
            background-color: #f8f9fa;
        }

        /* Soft UI Buttons */
        .btn-soft {
            border: none;
            border-radius: var(--border-radius-soft);
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: none;
            transition: all 0.15s ease-in;
            box-shadow: var(--shadow-soft);
            position: relative;
            overflow: hidden;
        }

        .btn-soft:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-soft-lg);
        }

        .btn-soft-primary {
            background: var(--gradient-primary);
            color: white;
        }

        .btn-soft-success {
            background: var(--gradient-success);
            color: white;
        }

        .btn-soft-info {
            background: var(--gradient-info);
            color: white;
        }

        .btn-soft-warning {
            background: var(--gradient-warning);
            color: white;
        }

        .btn-soft-outline {
            background: transparent;
            border: 1px solid var(--soft-primary);
            color: var(--soft-primary);
        }

        /* Soft UI Cards */
        .card-soft {
            border: none;
            border-radius: var(--border-radius-soft-lg);
            box-shadow: var(--shadow-soft);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .card-soft:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-soft-xl);
        }

        .card-soft-gradient {
            background: var(--gradient-primary);
            color: white;
        }

        /* Soft UI Navbar */
        .navbar-soft {
             backdrop-filter: saturate(200%) blur(30px); 
            background: rgba(255, 255, 255, 0.8) !important;
            box-shadow: 0 0 1rem 0 rgba(136, 152, 170, 0.15);
        /*             border-radius: 0 0 var(--border-radius-soft-lg) var(--border-radius-soft-lg);
        */            transition: all 0.3s ease;
        }

        .navbar-soft.scrolled {
            background: rgba(255, 255, 255, 0.95) !important;
            box-shadow: var(--shadow-soft-lg);
        }

        .navbar-brand-soft {
            font-weight: 700;
            font-size: 1.5rem;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-link-soft {
            color: var(--soft-dark) !important;
            font-weight: 600;
            padding: 0.5rem 1rem !important;
            border-radius: var(--border-radius-soft);
            transition: all 0.3s ease;
        }

        .nav-link-soft:hover {
            background: rgba(203, 12, 159, 0.1);
            color: var(--soft-primary) !important;
        }

        .nav-link-soft.active {
            background: var(--gradient-primary);
            color: white !important;
            box-shadow: var(--shadow-soft);
        }

        /* Hero Section */
        .hero-soft {
            background: linear-gradient(310deg, #7928ca 0%, #ff0080 100%);
            position: relative;
            overflow: hidden;
            border-radius: 0 0 3rem 3rem;
        }

        .hero-soft::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><pattern id="soft-pattern" x="0" y="0" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="1000" height="1000" fill="url(%23soft-pattern)"/></svg>');
            opacity: 0.3;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        /* Soft UI Forms */
        .form-control-soft {
            border: 1px solid #d2d6da;
            border-radius: var(--border-radius-soft);
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            background-color: #fff;
        }

        .form-control-soft:focus {
            border-color: var(--soft-primary);
            box-shadow: 0 0 0 2px rgba(203, 12, 159, 0.25);
            background-color: #fff;
        }

        /* Pricing Cards */
        .pricing-card {
            border: none;
            border-radius: var(--border-radius-soft-xl);
            box-shadow: var(--shadow-soft);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .pricing-card.featured {
            transform: scale(1.05);
            box-shadow: var(--shadow-soft-xl);
            border: 2px solid transparent;
            background: linear-gradient(white, white) padding-box,
                        var(--gradient-primary) border-box;
        }

        .pricing-card.featured::before {
            content: "POPULAIRE";
            position: absolute;
            top: 1rem;
            right: -2rem;
            background: var(--gradient-warning);
            color: white;
            padding: 0.5rem 3rem;
            font-size: 0.75rem;
            font-weight: 700;
            transform: rotate(45deg);
            z-index: 10;
        }

        .pricing-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-soft-xl);
        }

        .pricing-card.featured:hover {
            transform: scale(1.05) translateY(-10px);
        }

        /* Statistics Cards */
        .stat-card {
            background: white;
            border-radius: var(--border-radius-soft-lg);
            padding: 2rem;
            box-shadow: var(--shadow-soft);
            border-left: 4px solid var(--soft-primary);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-soft-lg);
        }

        .stat-card.success::before {
            background: var(--gradient-success);
        }

        .stat-card.info::before {
            background: var(--gradient-info);
        }

        .stat-card.warning::before {
            background: var(--gradient-warning);
        }

        /* Feature Icons */
        .feature-icon {
            width: 4rem;
            height: 4rem;
            border-radius: var(--border-radius-soft-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--gradient-primary);
            color: white;
            box-shadow: var(--shadow-soft);
            transition: all 0.3s ease;
        }

        .feature-icon:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-soft-lg);
        }

        .feature-icon.success {
            background: var(--gradient-success);
        }

        .feature-icon.info {
            background: var(--gradient-info);
        }

        .feature-icon.warning {
            background: var(--gradient-warning);
        }

        /* Testimonials */
        .testimonial-soft {
            background: white;
            border-radius: var(--border-radius-soft-xl);
            padding: 2rem;
            box-shadow: var(--shadow-soft);
            position: relative;
            transition: all 0.3s ease;
        }

        .testimonial-soft:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-soft-lg);
        }

        .testimonial-soft::before {
            content: """;
            position: absolute;
            top: -1rem;
            left: 1.5rem;
            font-size: 4rem;
            color: var(--soft-primary);
            opacity: 0.3;
            font-family: serif;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Soft UI Inputs */
        .input-group-soft .form-control {
            border: 1px solid #d2d6da;
            border-radius: var(--border-radius-soft);
        }

        .input-group-soft .input-group-text {
            background: #f8f9fa;
            border: 1px solid #d2d6da;
            border-radius: var(--border-radius-soft);
            color: var(--soft-secondary);
        }

        /* Footer */
        .footer-soft {
            background: var(--gradient-dark);
            color: white;
            position: relative;
            overflow: hidden;
        }

        .footer-soft::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><pattern id="footer-pattern" x="0" y="0" width="50" height="50" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="1000" height="1000" fill="url(%23footer-pattern)"/></svg>');
            opacity: 0.5;
        }

        .footer-content {
            position: relative;
            z-index: 2;
        }

        /* IPTV Specific Styles */
        .iptv-hero-bg {
            background: linear-gradient(310deg, #141727 0%, #3a416f 100%);
            position: relative;
            overflow: hidden;
        }

        .iptv-hero-bg::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 30% 20%, rgba(120, 40, 202, 0.3) 0%, transparent 50%),
                        radial-gradient(circle at 70% 80%, rgba(255, 0, 128, 0.3) 0%, transparent 50%);
        }

        .tv-glow {
            filter: drop-shadow(0 0 20px rgba(23, 193, 232, 0.5));
        }

        /* Responsive */
        @media (max-width: 768px) {
            .pricing-card.featured {
                transform: none;
                margin-bottom: 2rem;
            }
            
            .hero-soft {
                border-radius: 0 0 1.5rem 1.5rem;
            }
        }

        /* Hover Effects */
        .hover-lift {
            transition: all 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-5px);
        }

        /* Glassmorphism Effect */
        .glass-effect {
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Soft UI Alerts */
        .alert-soft {
            border: none;
            border-radius: var(--border-radius-soft-lg);
            padding: 1rem 1.5rem;
            border-left: 4px solid;
        }

        .alert-soft.alert-success {
            background: rgba(130, 214, 22, 0.1);
            border-left-color: var(--soft-success);
            color: #0f5132;
        }

        .alert-soft.alert-danger {
            background: rgba(234, 6, 6, 0.1);
            border-left-color: var(--soft-danger);
            color: #721c24;
        }

        .alert-soft.alert-warning {
            background: rgba(251, 207, 51, 0.1);
            border-left-color: var(--soft-warning);
            color: #664d03;
        }

        .alert-soft.alert-info {
            background: rgba(23, 193, 232, 0.1);
            border-left-color: var(--soft-info);
            color: #055160;
        }
    </style>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-17598379353"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-17598379353');
</script>
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-soft fixed-top" id="mainNavbar">
        <div class="container">
            <!-- Brand -->
            <a class="navbar-brand navbar-brand-soft d-flex align-items-center" href="{{ route('home') }}">
                <div class="feature-icon me-3" style="width: 2rem; height: 2rem;">
                    <i class="bi bi-tv fs-4"></i>
                </div>
                {{ config('app.name', 'IPTV Pro') }}
            </a>
            
            <!-- Toggler -->
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Navigation Links -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link nav-link-soft {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="bi bi-house me-1"></i>Accueil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-soft {{ request()->routeIs('subscriptions.*') ? 'active' : '' }}" href="{{ route('subscriptions.index') }}">
                            <i class="bi bi-tv me-1"></i>Abonnements
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-soft {{ request()->routeIs('tutorials.*') ? 'active' : '' }}" href="{{ route('tutorials.index') }}">
                            <i class="bi bi-book me-1"></i>Tutoriels
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-soft {{ request()->routeIs('resellers.*') ? 'active' : '' }}" href="{{ route('resellers.index') }}">
                            <i class="bi bi-people me-1"></i>Revendeurs
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-soft {{ request()->routeIs('contact.*') ? 'active' : '' }}" href="{{ route('contact.index') }}">
                            <i class="bi bi-headset me-1"></i>Contact
                        </a>
                    </li>
                </ul>
                
                <!-- Auth Links -->
                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link nav-link-soft dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                <div class="feature-icon me-2" style="width: 2rem; height: 2rem; font-size: 0.875rem;">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg" style="border-radius: var(--border-radius-soft-lg);">
                                <li>
                                    <a class="dropdown-item" href="{{ route('dashboard') }}">
                                        <i class="bi bi-speedometer2 me-2 text-primary"></i>Tableau de bord
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="bi bi-person me-2 text-info"></i>Mon profil
                                    </a>
                                </li>
                                @if(Auth::user()->isAdmin())
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                            <i class="bi bi-gear me-2 text-warning"></i>Administration
                                        </a>
                                    </li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-box-arrow-right me-2 text-danger"></i>Déconnexion
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link nav-link-soft" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i>Connexion
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-soft btn-soft-primary ms-2" href="{{ route('contact.index') }}">
                                <i class="bi bi-envelope me-1"></i>Nous contacter
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Alerts -->
    @if(session('success'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1060; margin-top: 80px;">
            <div class="alert alert-soft alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1060; margin-top: 80px;">
            <div class="alert alert-soft alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @if(session('warning'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1060; margin-top: 80px;">
            <div class="alert alert-soft alert-warning alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @if(session('info'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1060; margin-top: 80px;">
            <div class="alert alert-soft alert-info alert-dismissible fade show" role="alert">
                <i class="bi bi-info-circle me-2"></i>{{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main style="margin-top: 60px;">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer-soft mt-5 pt-5 pb-3">
        <div class="footer-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="feature-icon me-3">
                                <i class="bi bi-tv fs-4"></i>
                            </div>
                            <h5 class="mb-0">{{ config('app.name', 'IPTV Pro') }}</h5>
                        </div>
                        <p class="mb-3 opacity-75">
                            Votre service IPTV de confiance avec plus de 12000 chaînes HD, VOD illimité et support 24/7.
                        </p>
                        <div class="d-flex gap-3">
                            <a href="#" class="text-white opacity-75 hover-lift">
                                <i class="bi bi-facebook fs-4"></i>
                            </a>
                            <a href="#" class="text-white opacity-75 hover-lift">
                                <i class="bi bi-twitter fs-4"></i>
                            </a>
                            <a href="#" class="text-white opacity-75 hover-lift">
                                <i class="bi bi-instagram fs-4"></i>
                            </a>
                            <a href="#" class="text-white opacity-75 hover-lift">
                                <i class="bi bi-youtube fs-4"></i>
                            </a>
                        </div>
                    </div>
                    
                    <div class="col-lg-2 col-md-6 mb-4">
                        <h6 class="mb-3">Services</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2"><a href="{{ route('subscriptions.index') }}" class="text-white opacity-75 text-decoration-none hover-lift">Abonnements</a></li>
                            <li class="mb-2"><a href="{{ route('tutorials.index') }}" class="text-white opacity-75 text-decoration-none hover-lift">Tutoriels</a></li>
                            <li class="mb-2"><a href="{{ route('resellers.index') }}" class="text-white opacity-75 text-decoration-none hover-lift">Revendeurs</a></li>
                            <li class="mb-2"><a href="{{ route('contact.index') }}" class="text-white opacity-75 text-decoration-none hover-lift">Support</a></li>
                        </ul>
                    </div>
                    
                    <div class="col-lg-2 col-md-6 mb-4">
                        <h6 class="mb-3">Aide</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2"><a href="{{ route('tutorials.index') }}" class="text-white opacity-75 text-decoration-none hover-lift">Installation</a></li>
                            <li class="mb-2"><a href="{{ route('contact.index') }}" class="text-white opacity-75 text-decoration-none hover-lift">Contact</a></li>
                            <li class="mb-2"><a href="#" class="text-white opacity-75 text-decoration-none hover-lift">FAQ</a></li>
                            <li class="mb-2"><a href="#" class="text-white opacity-75 text-decoration-none hover-lift">Support 24/7</a></li>
                        </ul>
                    </div>
                    
                    <div class="col-lg-4 mb-4">
                        <h6 class="mb-3">Légal & Sécurité</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2"><a href="{{ route('legal', 'terms') }}" class="text-white opacity-75 text-decoration-none hover-lift">Conditions générales</a></li>
                            <li class="mb-2"><a href="{{ route('legal', 'privacy') }}" class="text-white opacity-75 text-decoration-none hover-lift">Confidentialité</a></li>
                            <li class="mb-2"><a href="{{ route('legal', 'mentions') }}" class="text-white opacity-75 text-decoration-none hover-lift">Mentions légales</a></li>
                        </ul>
                        <div class="mt-3 p-3 glass-effect rounded">
                            <small class="d-flex align-items-center">
                                <i class="bi bi-shield-check me-2 text-success"></i>
                                Service 100% légal et sécurisé
                            </small>
                        </div>
                    </div>
                </div>
                
                <hr class="my-4 opacity-25">
                
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="mb-0 opacity-75">&copy; {{ date('Y') }} {{ config('app.name', 'IPTV Pro') }}. Tous droits réservés.</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <small class="opacity-75">Propulsé par Laravel avec ❤️</small>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Quick Order Modal -->
    <div class="modal fade" id="quickOrderModal" tabindex="-1" aria-labelledby="quickOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="border-radius: var(--border-radius-soft-xl); border: none; box-shadow: var(--shadow-soft-xl);">
                <div class="modal-header text-white position-relative overflow-hidden" style="background: var(--gradient-primary); border-radius: var(--border-radius-soft-xl) var(--border-radius-soft-xl) 0 0;">
                    <div class="position-absolute top-0 start-0 w-100 h-100 opacity-20">
                        <svg width="100%" height="100%" viewBox="0 0 100 100">
                            <circle cx="20" cy="20" r="2" fill="white" opacity="0.3"/>
                            <circle cx="80" cy="40" r="1.5" fill="white" opacity="0.4"/>
                            <circle cx="60" cy="80" r="1" fill="white" opacity="0.5"/>
                        </svg>
                    </div>
                    <div class="position-relative">
                        <h4 class="modal-title fw-bold" id="quickOrderModalLabel">
                            <i class="bi bi-lightning-charge me-2"></i>Commande Rapide
                        </h4>
                        <small class="opacity-75">Commandez en 30 secondes sans créer de compte</small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="quickOrderForm">
                        @csrf
                        <input type="hidden" id="quick_item_type" name="item_type"> <!-- subscription or reseller_pack -->
                        <input type="hidden" id="quick_item_id" name="item_id">
                        
                        <!-- Item Summary -->
                        <div class="card-soft mb-4 p-3" style="background: rgba(203, 12, 159, 0.1);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1 fw-bold" id="quick_item_name">Nom de l'item</h6>
                                    <small class="text-muted" id="quick_item_description">Description</small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold fs-5" style="color: var(--soft-primary);" id="quick_item_price">0€</div>
                                    <small class="text-muted" id="quick_item_details">Détails</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Customer Info -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="quick_customer_name" class="form-label fw-bold">Nom complet *</label>
                                <div class="input-group input-group-soft">
                                    <span class="input-group-text">
                                        <i class="bi bi-person text-primary"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control form-control-soft" 
                                           id="quick_customer_name" 
                                           name="customer_name" 
                                           placeholder="Votre nom complet"
                                           required>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="quick_customer_email" class="form-label fw-bold">Adresse email *</label>
                                <div class="input-group input-group-soft">
                                    <span class="input-group-text">
                                        <i class="bi bi-envelope text-primary"></i>
                                    </span>
                                    <input type="email" 
                                           class="form-control form-control-soft" 
                                           id="quick_customer_email" 
                                           name="customer_email" 
                                           placeholder="votre@email.com"
                                           required>
                                </div>
                                <div class="form-text">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Vos identifiants seront envoyés à cette adresse
                                </div>
                            </div>
                        </div>
                        
                        <!-- Terms -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="quick_terms" name="terms_accepted" value="1" required>
                                <label class="form-check-label fw-bold" for="quick_terms">
                                    J'accepte les <a href="{{ route('legal', 'terms') }}" target="_blank" class="text-decoration-none">conditions générales</a> *
                                </label>
                            </div>
                        </div>
                        
                        <!-- Benefits -->
                        <div class="alert alert-soft alert-success">
                            <h6 class="fw-bold mb-2">
                                <i class="bi bi-check-circle me-2"></i>Avantages de la commande rapide
                            </h6>
                            <ul class="list-unstyled mb-0 small">
                                <li><i class="bi bi-lightning text-warning me-2"></i>Activation immédiate après paiement</li>
                                <li><i class="bi bi-shield-check text-success me-2"></i>Paiement 100% sécurisé via PayPal</li>
                                <li><i class="bi bi-envelope text-info me-2"></i>Identifiants envoyés par email</li>
                                <li><i class="bi bi-headset text-primary me-2"></i>Support 24/7 disponible</li>
                            </ul>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-soft-outline" data-bs-dismiss="modal">
                        <i class="bi bi-x me-2"></i>Annuler
                    </button>
                    <button type="button" class="btn btn-soft btn-soft-primary" onclick="processQuickOrder()">
                        <i class="bi bi-credit-card me-2"></i>Payer avec PayPal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Soft UI Scripts -->
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('mainNavbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Animate elements on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in-up');
                }
            });
        }, observerOptions);

        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);

            // Observe elements for animation
            document.querySelectorAll('.card-soft, .pricing-card, .stat-card, .testimonial-soft').forEach(el => {
                observer.observe(el);
            });
        });

        // Smooth scrolling for anchor links
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

        // Quick Order Modal Functions
        window.openQuickOrderModal = function(itemType, itemId, itemName, itemPrice, itemDescription, itemDetails) {
            document.getElementById('quick_item_type').value = itemType;
            document.getElementById('quick_item_id').value = itemId;
            document.getElementById('quick_item_name').textContent = itemName;
            document.getElementById('quick_item_description').textContent = itemDescription;
            document.getElementById('quick_item_price').textContent = itemPrice;
            document.getElementById('quick_item_details').textContent = itemDetails;
            
            const modal = new bootstrap.Modal(document.getElementById('quickOrderModal'));
            modal.show();
        };

        window.processQuickOrder = async function() {
            const form = document.getElementById('quickOrderForm');
            const formData = new FormData(form);
            const submitBtn = document.querySelector('#quickOrderModal .btn-soft-primary');
            
            // Validation
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            // Loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="spinner-border spinner-border-sm me-2"></i>Traitement...';
            submitBtn.style.background = 'var(--gradient-warning)';
            
            try {
                const response = await fetch('/quick-order', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Rediriger vers PayPal
                    window.location.href = result.paypal_url;
                } else {
                    alert('Erreur: ' + result.message);
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-credit-card me-2"></i>Payer avec PayPal';
                    submitBtn.style.background = 'var(--gradient-primary)';
                }
            } catch (error) {
                alert('Erreur de connexion. Veuillez réessayer.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-credit-card me-2"></i>Payer avec PayPal';
                submitBtn.style.background = 'var(--gradient-primary)';
            }
        };
    </script>
    
    @stack('scripts')
</body>
</html>
