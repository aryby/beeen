@extends('layouts.soft-ui')

@section('title', 'Connexion')

@section('content')
<section class="position-relative overflow-hidden min-vh-100 d-flex align-items-center" style="background: var(--gradient-primary);">
    <!-- Background Pattern -->
    <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10">
        <svg width="100%" height="100%" viewBox="0 0 1000 1000">
            <defs>
                <pattern id="login-pattern" x="0" y="0" width="100" height="100" patternUnits="userSpaceOnUse">
                    <circle cx="50" cy="50" r="2" fill="white"/>
                    <circle cx="25" cy="25" r="1" fill="white" opacity="0.5"/>
                    <circle cx="75" cy="75" r="1.5" fill="white" opacity="0.7"/>
                </pattern>
            </defs>
            <rect width="1000" height="1000" fill="url(#login-pattern)"/>
        </svg>
    </div>

    <div class="container position-relative">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card-soft" style="backdrop-filter: blur(20px); background: rgba(255, 255, 255, 0.95);">
                    <!-- Header -->
                    <div class="card-header text-center border-0 pt-4 pb-0" style="background: transparent;">
                        <div class="feature-icon mx-auto mb-3" style="width: 4rem; height: 4rem;">
                            <i class="bi bi-tv fs-1"></i>
                        </div>
                        <h3 class="fw-bold mb-2" style="color: var(--soft-dark);">Bienvenue !</h3>
                        <p class="text-muted">Connectez-vous à votre espace IPTV</p>
                    </div>

                    <div class="card-body px-4 pb-4">
                        <!-- Session Status -->
                        @if (session('status'))
                            <div class="alert alert-soft alert-success mb-4">
                                <i class="bi bi-check-circle me-2"></i>{{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Email Address -->
                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold">Adresse email</label>
                                <div class="input-group input-group-soft">
                                    <span class="input-group-text">
                                        <i class="bi bi-envelope text-primary"></i>
                                    </span>
                                    <input id="email" 
                                           class="form-control form-control-soft @error('email') is-invalid @enderror" 
                                           type="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           placeholder="votre@email.com"
                                           required 
                                           autofocus 
                                           autocomplete="username">
                                </div>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label fw-bold">Mot de passe</label>
                                <div class="input-group input-group-soft">
                                    <span class="input-group-text">
                                        <i class="bi bi-lock text-primary"></i>
                                    </span>
                                    <input id="password" 
                                           class="form-control form-control-soft @error('password') is-invalid @enderror"
                                           type="password"
                                           name="password"
                                           placeholder="••••••••"
                                           required 
                                           autocomplete="current-password">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                                        <i class="bi bi-eye" id="toggleIcon"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Remember Me -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                                    <label for="remember_me" class="form-check-label">Se souvenir de moi</label>
                                </div>
                                
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-decoration-none small">
                                        Mot de passe oublié ?
                                    </a>
                                @endif
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-soft btn-soft-primary btn-lg py-3">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
                                </button>
                            </div>
                        </form>

                        <!-- Demo Accounts -->
                        {{-- div class="alert alert-soft alert-info">
                            <h6 class="fw-bold mb-2">
                                <i class="bi bi-info-circle me-2"></i>Comptes de démonstration
                            </h6>
                            <div class="small">
                                <strong>Administrateur :</strong><br>
                                Email: <code>admin@iptv.com</code><br>
                                Mot de passe: <code>admin123</code>
                            </div>
                        </div> --}}

                        <!-- Footer Links -->
                        <div class="text-center mt-4 pt-3 border-top">
                            <p class="text-muted small mb-2">Pas encore de compte ?</p>
                            <a href="{{ route('contact.index') }}" class="btn btn-soft-outline btn-sm">
                                <i class="bi bi-envelope me-1"></i>Nous contacter
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Back to Home -->
                <div class="text-center mt-4">
                    <a href="{{ route('home') }}" class="text-white text-decoration-none opacity-75 hover-lift">
                        <i class="bi bi-arrow-left me-2"></i>Retour à l'accueil
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.className = 'bi bi-eye-slash';
        } else {
            passwordInput.type = 'password';
            toggleIcon.className = 'bi bi-eye';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Animate card entrance
        const card = document.querySelector('.card-soft');
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px) scale(0.95)';
        card.style.transition = 'all 0.6s ease';
        
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0) scale(1)';
        }, 200);

        // Enhanced form validation
        const form = document.querySelector('form');
        const inputs = form.querySelectorAll('input[required]');
        
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                if (this.value.trim() === '') {
                    this.style.borderColor = 'var(--soft-danger)';
                    this.style.boxShadow = '0 0 0 2px rgba(234, 6, 6, 0.25)';
                } else {
                    this.style.borderColor = 'var(--soft-success)';
                    this.style.boxShadow = '0 0 0 2px rgba(130, 214, 22, 0.25)';
                }
            });
        });

        // Form submission with enhanced UX
        form.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="spinner-border spinner-border-sm me-2"></i>Connexion...';
            submitBtn.style.background = 'var(--gradient-info)';
        });
    });
</script>
@endpush