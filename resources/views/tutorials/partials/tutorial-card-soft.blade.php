<div class="col-lg-4 col-md-6 mb-4">
    <div class="card-soft h-100 tutorial-card">
        @if($tutorial->featured_image)
            <div class="position-relative overflow-hidden" style="border-radius: var(--border-radius-soft-lg) var(--border-radius-soft-lg) 0 0;">
                <img src="{{ $tutorial->featured_image }}" 
                     class="card-img-top" 
                     alt="{{ $tutorial->title }}" 
                     style="height: 200px; object-fit: cover;">
                <div class="position-absolute top-0 start-0 w-100 h-100" style="background: var(--gradient-primary); opacity: 0.2;"></div>
            </div>
        @else
            <div class="position-relative overflow-hidden d-flex align-items-center justify-content-center text-white" 
                 style="height: 200px; background: var(--gradient-{{ $tutorial->device_type === 'android' ? 'success' : ($tutorial->device_type === 'ios' ? 'info' : 'primary') }}); border-radius: var(--border-radius-soft-lg) var(--border-radius-soft-lg) 0 0;">
                <div class="position-absolute top-0 start-0 w-100 h-100 opacity-20">
                    <svg width="100%" height="100%" viewBox="0 0 100 100">
                        <circle cx="20" cy="20" r="1" fill="white" opacity="0.5"/>
                        <circle cx="80" cy="30" r="1.5" fill="white" opacity="0.7"/>
                        <circle cx="60" cy="80" r="1" fill="white" opacity="0.6"/>
                        <circle cx="30" cy="70" r="0.8" fill="white" opacity="0.4"/>
                    </svg>
                </div>
                <div class="position-relative">
                    <div class="feature-icon mb-3" style="width: 4rem; height: 4rem; background: rgba(255,255,255,0.2);">
                        <i class="bi bi-{{ $tutorial->device_type === 'android' ? 'android2' : ($tutorial->device_type === 'ios' ? 'apple' : 'display') }} fs-1"></i>
                    </div>
                    <h5 class="fw-bold">{{ $tutorial->device_type_name }}</h5>
                </div>
            </div>
        @endif
        
        <div class="card-body d-flex flex-column p-4">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <span class="badge px-3 py-2" style="background: var(--gradient-info); color: white; border-radius: var(--border-radius-soft);">
                    <i class="bi bi-{{ $tutorial->device_type === 'android' ? 'android2' : ($tutorial->device_type === 'ios' ? 'apple' : 'display') }} me-1"></i>
                    {{ $tutorial->device_type_name }}
                </span>
                <div class="d-flex align-items-center text-muted small">
                    <i class="bi bi-list-ol me-1"></i>
                    <span>{{ $tutorial->steps_count }} étape{{ $tutorial->steps_count > 1 ? 's' : '' }}</span>
                </div>
            </div>
            
            <h5 class="fw-bold mb-3" style="color: var(--soft-dark);">{{ $tutorial->title }}</h5>
            
            @if($tutorial->intro)
                <p class="text-muted flex-grow-1 mb-4">{{ Str::limit($tutorial->intro, 120) }}</p>
            @endif
            
            <div class="mt-auto">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center text-muted small">
                        <i class="bi bi-clock me-1"></i>
                        <span>{{ $tutorial->steps_count * 2 }}-{{ $tutorial->steps_count * 3 }} min</span>
                    </div>
                    <div class="d-flex align-items-center">
                        @if($tutorial->steps->first() && $tutorial->steps->first()->hasVideo())
                            <i class="bi bi-play-circle text-primary me-2" title="Contient des vidéos"></i>
                        @endif
                        @if($tutorial->steps->first() && $tutorial->steps->first()->hasImage())
                            <i class="bi bi-image text-success" title="Contient des images"></i>
                        @endif
                    </div>
                </div>
                <a href="{{ route('tutorials.show', $tutorial) }}" class="btn btn-soft btn-soft-primary w-100">
                    <i class="bi bi-play-circle me-2"></i>Commencer le tutoriel
                </a>
            </div>
        </div>
    </div>
</div>

@once
@push('styles')
<style>
    .tutorial-card {
        transition: all 0.3s ease;
    }
    
    .tutorial-card:hover {
        transform: translateY(-10px);
        box-shadow: var(--shadow-soft-xl) !important;
    }
    
    .tutorial-card .btn:hover {
        transform: translateY(-2px);
    }
</style>
@endpush
@endonce
