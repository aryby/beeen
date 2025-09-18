<div class="col-lg-4 col-md-6 mb-4">
    <div class="card h-100 shadow-sm tutorial-card">
        @if($tutorial->featured_image)
            <img src="{{ $tutorial->featured_image }}" class="card-img-top" alt="{{ $tutorial->title }}" style="height: 200px; object-fit: cover;">
        @else
            <div class="card-img-top bg-gradient d-flex align-items-center justify-content-center text-white" style="height: 200px; background: linear-gradient(135deg, #0dcaf0 0%, #0d6efd 100%);">
                <i class="bi bi-{{ $tutorial->device_type === 'android' ? 'android2' : ($tutorial->device_type === 'ios' ? 'apple' : 'display') }} fs-1"></i>
            </div>
        @endif
        
        <div class="card-body d-flex flex-column">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="badge bg-info">{{ $tutorial->device_type_name }}</span>
                <small class="text-muted">{{ $tutorial->steps_count }} étape{{ $tutorial->steps_count > 1 ? 's' : '' }}</small>
            </div>
            
            <h5 class="card-title fw-bold">{{ $tutorial->title }}</h5>
            
            @if($tutorial->intro)
                <p class="card-text text-muted flex-grow-1">{{ Str::limit($tutorial->intro, 120) }}</p>
            @endif
            
            <div class="mt-auto">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center text-muted small">
                        <i class="bi bi-clock me-1"></i>
                        <span>{{ $tutorial->steps_count * 2 }}-{{ $tutorial->steps_count * 3 }} min</span>
                    </div>
                    <a href="{{ route('tutorials.show', $tutorial) }}" class="btn btn-primary">
                        <i class="bi bi-play-circle me-1"></i>Commencer
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .tutorial-card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    
    .tutorial-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
</style>
@endpush
