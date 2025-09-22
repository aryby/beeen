@extends('layouts.app')

@section('title', 'Test des Configurations')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h2 class="h4 mb-0">
                        <i class="bi bi-gear me-2"></i>Test des Configurations
                    </h2>
                </div>
                <div class="card-body">
                    
                    <!-- Status des configurations -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card border-0 bg-light">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        @if($smtpConfigured)
                                            <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                                        @else
                                            <i class="bi bi-exclamation-triangle-fill text-warning" style="font-size: 3rem;"></i>
                                        @endif
                                    </div>
                                    <h5 class="card-title">Configuration SMTP</h5>
                                    <p class="card-text">
                                        @if($smtpConfigured)
                                            <span class="text-success">✓ Configuré</span>
                                        @else
                                            <span class="text-warning">⚠ Non configuré</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 bg-light">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        @if($paypalConfigured)
                                            <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                                        @else
                                            <i class="bi bi-exclamation-triangle-fill text-warning" style="font-size: 3rem;"></i>
                                        @endif
                                    </div>
                                    <h5 class="card-title">Configuration PayPal</h5>
                                    <p class="card-text">
                                        @if($paypalConfigured)
                                            <span class="text-success">✓ Configuré</span>
                                        @else
                                            <span class="text-warning">⚠ Non configuré</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tests -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h4 class="mb-3">Tests</h4>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Test Email</h5>
                                    <p class="card-text">Tester l'envoi d'un email avec la configuration SMTP actuelle.</p>
                                    <div class="input-group mb-3">
                                        <input type="email" class="form-control" id="testEmail" placeholder="email@example.com" value="test@example.com">
                                        <button class="btn btn-primary" onclick="testEmail()">
                                            <i class="bi bi-send me-1"></i>Envoyer
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Test PayPal</h5>
                                    <p class="card-text">Tester la connexion à l'API PayPal.</p>
                                    <button class="btn btn-primary" onclick="testPayPal()">
                                        <i class="bi bi-paypal me-1"></i>Tester PayPal
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Détails des configurations -->
                    <div class="row">
                        <div class="col-12">
                            <h4 class="mb-3">Détails des Configurations</h4>
                            <div class="accordion" id="configAccordion">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#smtpConfig">
                                            <i class="bi bi-envelope me-2"></i>Configuration SMTP
                                        </button>
                                    </h2>
                                    <div id="smtpConfig" class="accordion-collapse collapse show" data-bs-parent="#configAccordion">
                                        <div class="accordion-body">
                                            <table class="table table-sm">
                                                <tr>
                                                    <td><strong>Host:</strong></td>
                                                    <td>{{ $settings['smtp_host'] ?: 'Non configuré' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Port:</strong></td>
                                                    <td>{{ $settings['smtp_port'] ?: 'Non configuré' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Username:</strong></td>
                                                    <td>{{ $settings['smtp_username'] ?: 'Non configuré' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Password:</strong></td>
                                                    <td>{{ $settings['smtp_password'] ?: 'Non configuré' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Encryption:</strong></td>
                                                    <td>{{ $settings['smtp_encryption'] ?: 'Non configuré' }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#paypalConfig">
                                            <i class="bi bi-paypal me-2"></i>Configuration PayPal
                                        </button>
                                    </h2>
                                    <div id="paypalConfig" class="accordion-collapse collapse" data-bs-parent="#configAccordion">
                                        <div class="accordion-body">
                                            <table class="table table-sm">
                                                <tr>
                                                    <td><strong>Client ID:</strong></td>
                                                    <td>{{ $settings['paypal_client_id'] ?: 'Non configuré' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Client Secret:</strong></td>
                                                    <td>{{ $settings['paypal_client_secret'] ?: 'Non configuré' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Sandbox:</strong></td>
                                                    <td>{{ $settings['paypal_sandbox'] ? 'Activé' : 'Désactivé' }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function testEmail() {
    const email = document.getElementById('testEmail').value;
    
    if (!email) {
        showToast('Veuillez saisir une adresse email', 'warning');
        return;
    }
    
    // Afficher un loading
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Envoi...';
    button.disabled = true;
    
    $.ajax({
        url: '{{ route("test.email") }}',
        method: 'POST',
        data: {
            email: email,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                showToast(response.message, 'success');
            } else {
                showToast(response.message, 'error');
            }
        },
        error: function(xhr) {
            showToast('Erreur lors du test email', 'error');
        },
        complete: function() {
            button.innerHTML = originalText;
            button.disabled = false;
        }
    });
}

function testPayPal() {
    // Afficher un loading
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Test...';
    button.disabled = true;
    
    $.ajax({
        url: '{{ route("test.paypal") }}',
        method: 'GET',
        success: function(response) {
            if (response.success) {
                showToast(response.message, 'success');
            } else {
                showToast(response.message, 'error');
            }
        },
        error: function(xhr) {
            showToast('Erreur lors du test PayPal', 'error');
        },
        complete: function() {
            button.innerHTML = originalText;
            button.disabled = false;
        }
    });
}
</script>
@endpush
