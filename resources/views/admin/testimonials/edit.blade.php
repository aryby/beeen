@extends('layouts.admin')

@section('title', 'Éditer témoignage')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header">Éditer le témoignage</div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.testimonials.update', $testimonial) }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Nom du client</label>
                    <input type="text" class="form-control" name="customer_name" value="{{ $testimonial->customer_name }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Lieu</label>
                    <input type="text" class="form-control" name="customer_location" value="{{ $testimonial->customer_location }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Témoignage</label>
                    <textarea class="form-control" name="testimonial" rows="4" required>{{ $testimonial->testimonial }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Note</label>
                    <select class="form-select" name="rating" required>
                        @for($i=5;$i>=1;$i--)
                            <option value="{{ $i }}" @selected($testimonial->rating==$i)>{{ $i }} / 5</option>
                        @endfor
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Ordre</label>
                    <input type="number" class="form-control" name="sort_order" value="{{ $testimonial->sort_order }}">
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="is_published" value="1" id="is_published" @checked($testimonial->is_published)>
                    <label class="form-check-label" for="is_published">Publié</label>
                </div>
                <button class="btn btn-soft btn-soft-primary">Mettre à jour</button>
            </form>
        </div>
    </div>
</div>
@endsection


