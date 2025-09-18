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
    
    <!-- Custom Admin CSS -->
    <style>
        :root {
            --admin-primary: #0d6efd;
            --admin-secondary: #6c757d;
            --admin-success: #198754;
            --admin-danger: #dc3545;
            --admin-warning: #ffc107;
            --admin-info: #0dcaf0;
            --admin-dark: #212529;
            --admin-light: #f8f9fa;
            --sidebar-width: 280px;
        }

        body {
            font-family: 'Figtree', sans-serif;
            background-color: #f8f9fa;
        }

        .admin-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(135deg, var(--admin-dark) 0%, #495057 100%);
            color: white;
            overflow-y: auto;
            transition: transform 0.3s ease;
            z-index: 1050;
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
        }

        .sidebar-nav .nav-link:hover {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
            border-left-color: var(--admin-primary);
        }

        .sidebar-nav .nav-link.active {
            color: white;
            background-color: rgba(13, 110, 253, 0.2);
            border-left-color: var(--admin-primary);
        }

        .sidebar-nav .nav-link i {
            width: 20px;
            margin-right: 0.75rem;
            text-align: center;
        }

        .admin-navbar {
            background: white;
            border-bottom: 1px solid #dee2e6;
            padding: 1rem 1.5rem;
            margin-bottom: 2rem;
        }

        .stats-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border-left: 4px solid var(--admin-primary);
            transition: transform 0.2s ease;
        }

        .stats-card:hover {
            transform: translateY(-2px);
        }

        .stats-card.success {
            border-left-color: var(--admin-success);
        }

        .stats-card.warning {
            border-left-color: var(--admin-warning);
        }

        .stats-card.danger {
            border-left-color: var(--admin-danger);
        }

        .stats-card.info {
            border-left-color: var(--admin-info);
        }

        .stats-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--admin-dark);
        }

        .stats-label {
            color: var(--admin-secondary);
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }

        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }

        .table-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .table-card .card-header {
            background: var(--admin-light);
            border-bottom: 1px solid #dee2e6;
            padding: 1rem 1.5rem;
            font-weight: 600;
        }

        .btn-toggle-sidebar {
            display: none;
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

        /* Dark mode for charts */
        .chart-dark {
            background-color: #2d3748;
            color: white;
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
                    <span class="badge bg-danger ms-auto">{{ App\Models\Order::pending()->count() }}</span>
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
                    <span class="badge bg-warning ms-auto">{{ App\Models\Message::unread()->count() }}</span>
                @endif
            </a>
            <a href="{{ route('admin.testimonials.index') }}" class="nav-link {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">
                <i class="bi bi-chat-quote"></i>
                Témoignages
            </a>
            
            <div class="nav-section">Configuration</div>
            <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="bi bi-gear"></i>
                Paramètres
            </a>
            
            <div class="nav-section">Outils</div>
            <a href="{{ route('admin.export.orders') }}" class="nav-link">
                <i class="bi bi-download"></i>
                Export commandes
            </a>
            <a href="{{ route('admin.export.resellers') }}" class="nav-link">
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
                        <button class="btn btn-outline-secondary dropdown-toggle position-relative" data-bs-toggle="dropdown">
                            <i class="bi bi-bell"></i>
                            @if(App\Models\Message::unread()->count() > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
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
                        <button class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
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
            <div class="alert alert-success alert-dismissible fade show mx-4" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mx-4" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show mx-4" role="alert">
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
