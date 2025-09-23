<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Administration') - {{ config('app.name', 'IPTV Pro') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
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
            
            /* Admin specific */
            --sidebar-width: 280px;
        }

        body {
            font-family: "Open Sans", sans-serif;
            font-weight: 400;
            line-height: 1.6;
            color: var(--soft-dark);
            background-color: #f8f9fa;
        }

        /* Soft UI Admin Sidebar */
        .admin-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: var(--gradient-dark);
            color: white;
            overflow-y: auto;
            transition: transform 0.3s ease;
            z-index: 1050;
            box-shadow: var(--shadow-soft-xl);
        }

        .admin-sidebar.collapsed {
            transform: translateX(-100%);
        }

        .admin-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        .admin-content.expanded {
            margin-left: 0;
        }

        .sidebar-brand {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            font-weight: 700;
            font-size: 1.25rem;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-section {
            padding: 0.5rem 1.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.6);
            margin-top: 1rem;
            letter-spacing: 0.5px;
        }

        .nav-section:first-child {
            margin-top: 0;
        }

        .sidebar-nav .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            border-radius: 0 var(--border-radius-soft) var(--border-radius-soft) 0;
            margin: 0.25rem 1rem 0.25rem 0;
        }

        .sidebar-nav .nav-link:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
            border-left-color: var(--soft-primary);
            transform: translateX(5px);
        }

        .sidebar-nav .nav-link.active {
            color: white;
            background: var(--gradient-primary);
            border-left-color: var(--soft-primary);
            box-shadow: var(--shadow-soft);
        }

        .sidebar-nav .nav-link i {
            width: 20px;
            margin-right: 0.75rem;
            text-align: center;
        }

        /* Soft UI Admin Navbar */
        .admin-navbar {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: saturate(200%) blur(30px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding: 1rem 1.5rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-soft);
            border-radius: 0 0 var(--border-radius-soft-lg) var(--border-radius-soft-lg);
        }

        /* Soft UI Stats Cards */
        .stats-card {
            background: white;
            border-radius: var(--border-radius-soft-lg);
            padding: 2rem;
            box-shadow: var(--shadow-soft);
            border: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-soft-lg);
        }

        .stats-card.success::before {
            background: var(--gradient-success);
        }

        .stats-card.warning::before {
            background: var(--gradient-warning);
        }

        .stats-card.danger::before {
            background: var(--gradient-danger);
        }

        .stats-card.info::before {
            background: var(--gradient-info);
        }

        .stats-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--soft-dark);
            margin-bottom: 0.5rem;
        }

        .stats-label {
            color: var(--soft-secondary);
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
            background: white;
            border-radius: var(--border-radius-soft-lg);
            padding: 1rem;
            box-shadow: var(--shadow-soft);
        }

        /* Soft UI Table Cards */
        .table-card {
            background: white;
            border-radius: var(--border-radius-soft-lg);
            overflow: hidden;
            box-shadow: var(--shadow-soft);
            border: none;
        }

        .table-card .card-header {
            background: var(--gradient-primary);
            color: white;
            border: none;
            padding: 1.5rem;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .table-card .table {
            margin-bottom: 0;
        }

        .table-card .table th {
            border-top: none;
            font-weight: 600;
            color: var(--soft-dark);
            padding: 1rem;
        }

        .table-card .table td {
            padding: 1rem;
            vertical-align: middle;
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

        .btn-soft-danger {
            background: var(--gradient-danger);
            color: white;
        }

        .btn-soft-warning {
            background: var(--gradient-warning);
            color: white;
        }

        .btn-soft-info {
            background: var(--gradient-info);
            color: white;
        }

        .btn-soft-outline {
            background: transparent;
            border: 1px solid var(--soft-primary);
            color: var(--soft-primary);
        }

        .btn-toggle-sidebar {
            display: none;
            background: var(--gradient-primary);
            border: none;
            color: white;
            border-radius: var(--border-radius-soft);
            padding: 0.5rem 1rem;
            box-shadow: var(--shadow-soft);
        }

        /* Soft UI Alerts */
        .alert-soft {
            border: none;
            border-radius: var(--border-radius-soft-lg);
            padding: 1rem 1.5rem;
            border-left: 4px solid;
            box-shadow: var(--shadow-soft);
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

        /* Responsive */
        @media (max-width: 992px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }

            .admin-sidebar.show {
                transform: translateX(0);
            }

            .admin-content {
                margin-left: 0;
            }

            .btn-toggle-sidebar {
                display: inline-block;
            }
        }

        /* Custom scrollbar */
        .admin-sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .admin-sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .admin-sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }

        .admin-sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        /* Badge styles */
        .badge-soft {
            border-radius: var(--border-radius-soft);
            padding: 0.5rem 1rem;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .badge-soft.bg-success {
            background: var(--gradient-success) !important;
        }

        .badge-soft.bg-warning {
            background: var(--gradient-warning) !important;
        }

        .badge-soft.bg-danger {
            background: var(--gradient-danger) !important;
        }

        .badge-soft.bg-info {
            background: var(--gradient-info) !important;
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <nav class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-brand">
            <i class="bi bi-shield-lock me-2"></i>
            Administration
        </div>
        
        <div class="sidebar-nav">
            <div class="nav-section">Tableau de bord</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                Vue d'ensemble
            </a>
            
            <div class="nav-section">Gestion</div>
            <a href="{{ route('admin.subscriptions.index') }}" class="nav-link {{ request()->routeIs('admin.subscriptions.*') ? 'active' : '' }}">
                <i class="bi bi-tv"></i>
                Abonnements
            </a>
            <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="bi bi-cart"></i>
                Commandes
                @if(App\Models\Message::unread()->count() > 0)
                    <span class="badge badge-soft bg-danger ms-auto">{{ App\Models\Order::pending()->count() }}</span>
                @endif
            </a>
            <a href="{{ route('admin.resellers.index') }}" class="nav-link {{ request()->routeIs('admin.resellers.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i>
                Revendeurs
            </a>
            
            <div class="nav-section">Contenu</div>
            <a href="{{ route('admin.tutorials.index') }}" class="nav-link {{ request()->routeIs('admin.tutorials.*') ? 'active' : '' }}">
                <i class="bi bi-book"></i>
                Tutoriels
            </a>
            <a href="{{ route('admin.messages.index') }}" class="nav-link {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                <i class="bi bi-envelope"></i>
                Messages
                @if(App\Models\Message::unread()->count() > 0)
                    <span class="badge badge-soft bg-warning ms-auto">{{ App\Models\Message::unread()->count() }}</span>
                @endif
            </a>
            <a href="{{ route('admin.testimonials.index') }}" class="nav-link {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">
                <i class="bi bi-chat-quote"></i>
                Témoignages
            </a>
            <a href="{{ route('admin.email-logs.index') }}" class="nav-link {{ request()->routeIs('admin.email-logs.*') ? 'active' : '' }}">
                <i class="bi bi-mailbox"></i>
                Email Logs
            </a>
            
            <div class="nav-section">Configuration</div>
            <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="bi bi-gear"></i>
                Paramètres
            </a>
            
            <div class="nav-section">Outils</div>
            <a href="#" class="nav-link">
                <i class="bi bi-download"></i>
                Export commandes
            </a>
            <a href="#" class="nav-link">
                <i class="bi bi-download"></i>
                Export revendeurs
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="admin-content" id="adminContent">
        <!-- Top Navbar -->
        <nav class="admin-navbar">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <button class="btn btn-outline-secondary btn-toggle-sidebar me-3" onclick="toggleSidebar()">
                        <i class="bi bi-list"></i>
                    </button>
                    <h4 class="mb-0">@yield('page-title', 'Administration')</h4>
                </div>
                
                <div class="d-flex align-items-center">
                    <!-- Notifications -->
                    <div class="dropdown me-3">
                        <button class="btn btn-soft-outline dropdown-toggle position-relative" data-bs-toggle="dropdown">
                            <i class="bi bi-bell"></i>
                            @if(App\Models\Message::unread()->count() > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge badge-soft bg-danger rounded-pill">
                                    {{ App\Models\Message::unread()->count() }}
                                </span>
                            @endif
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" style="width: 300px;">
                            <li><h6 class="dropdown-header">Notifications récentes</h6></li>
                            @forelse(App\Models\Message::unread()->latest()->limit(3)->get() as $message)
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.messages.show', $message) }}">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <i class="bi bi-envelope text-primary"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-2">
                                                <div class="fw-bold">{{ $message->name }}</div>
                                                <div class="small text-muted">{{ Str::limit($message->subject, 30) }}</div>
                                                <div class="small text-muted">{{ $message->created_at->diffForHumans() }}</div>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @empty
                                <li><span class="dropdown-item-text">Aucune notification</span></li>
                            @endforelse
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-center" href="{{ route('admin.messages.index') }}">Voir toutes les notifications</a></li>
                        </ul>
                    </div>
                    
                    <!-- User Menu -->
                    <div class="dropdown">
                        <button class="btn btn-soft-outline dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i>
                            {{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('home') }}"><i class="bi bi-house me-2"></i>Voir le site</a></li>
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i>Mon profil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right me-2"></i>Déconnexion
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-soft alert-success alert-dismissible fade show mx-4" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-soft alert-danger alert-dismissible fade show mx-4" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-soft alert-warning alert-dismissible fade show mx-4" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Page Content -->
        <div class="container-fluid px-4">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const content = document.getElementById('adminContent');
            
            sidebar.classList.toggle('show');
        }

        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
    
    @stack('scripts')
</body>
</html>
