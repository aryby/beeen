@extends('layouts.admin')

@section('title', 'Paramètres')
@section('page-title', 'Paramètres du Site')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">Paramètres</h1>
                <p class="text-muted">Configurez les paramètres globaux du site</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf
            
            @foreach($categories as $categoryKey => $category)
                <div class="table-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-{{ $category['icon'] }} me-2"></i>
                            {{ $category['title'] }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($category['settings'] as $settingKey)
                                @php
                                    $setting = $settings->get($settingKey);
                                    $default = $defaults[$settingKey] ?? [];
                                    $value = $setting ? $setting->value : ($default['value'] ?? '');
                                    $type = $default['type'] ?? 'text';
                                    $description = $default['description'] ?? '';
                                @endphp
                                
                                <div class="col-md-6 mb-3">
                                    <label for="{{ $settingKey }}" class="form-label">
                                        {{ $description ?: ucfirst(str_replace('_', ' ', $settingKey)) }}
                                    </label>
                                    
                                    @if($type === 'boolean')
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   id="{{ $settingKey }}" 
                                                   name="settings[{{ $settingKey }}]" 
                                                   value="1" 
                                                   {{ $value ? 'checked' : '' }}>
                                        </div>
                                    @elseif($type === 'text' && in_array($settingKey, ['terms_of_service', 'privacy_policy', 'legal_mentions', 'subscription_description']))
                                        <textarea class="form-control" 
                                                  id="{{ $settingKey }}" 
                                                  name="settings[{{ $settingKey }}]" 
                                                  rows="6" 
                                                  placeholder="Entrez le contenu...">{{ $value }}</textarea>
                                    @elseif($settingKey === 'smtp_password' || $settingKey === 'paypal_client_secret' || $settingKey === 'recaptcha_secret_key')
                                        <input type="password" 
                                               class="form-control" 
                                               id="{{ $settingKey }}" 
                                               name="settings[{{ $settingKey }}]" 
                                               value="{{ $value }}" 
                                               placeholder="••••••••">
                                    @elseif($type === 'number')
                                        <input type="number" 
                                               class="form-control" 
                                               id="{{ $settingKey }}" 
                                               name="settings[{{ $settingKey }}]" 
                                               value="{{ $value }}" 
                                               placeholder="Entrez un nombre">
                                    @else
                                        <input type="text" 
                                               class="form-control" 
                                               id="{{ $settingKey }}" 
                                               name="settings[{{ $settingKey }}]" 
                                               value="{{ $value }}" 
                                               placeholder="Entrez la valeur">
                                    @endif
                                    
                                    @if($settingKey === 'paypal_sandbox')
                                        <div class="form-text">Cochez pour utiliser le mode test de PayPal</div>
                                    @elseif($settingKey === 'smtp_port')
                                        <div class="form-text">Port SMTP (587 pour TLS, 465 pour SSL)</div>
                                    @elseif($settingKey === 'smtp_encryption')
                                        <div class="form-text">Type de chiffrement (tls, ssl, ou vide)</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
            
            <div class="text-end">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-save me-2"></i>Enregistrer les paramètres
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        
        form.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="spinner-border spinner-border-sm me-2"></i>Enregistrement...';
        });
    });
</script>
@endpush
