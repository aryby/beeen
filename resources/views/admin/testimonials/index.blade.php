@extends('layouts.admin')

@section('title', 'Témoignages')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Témoignages</h4>
        <a href="{{ route('admin.testimonials.create') }}" class="btn btn-soft btn-soft-primary">Nouveau</a>
    </div>
    <div class="card table-card">
        <div class="card-header">Liste</div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Lieu</th>
                        <th>Note</th>
                        <th>Extrait</th>
                        <th>Publié</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($testimonials as $t)
                        <tr>
                            <td>{{ $t->customer_name }}</td>
                            <td>{{ $t->customer_location ?: '-' }}</td>
                            <td>{{ $t->stars }}</td>
                            <td>{{ $t->short_testimonial }}</td>
                            <td>
                                <span class="badge {{ $t->is_published ? 'bg-success' : 'bg-secondary' }}">{{ $t->is_published ? 'Oui' : 'Non' }}</span>
                            </td>
                            <td class="d-flex gap-2">
                                <a href="{{ route('admin.testimonials.edit', $t) }}" class="btn btn-sm btn-primary">Éditer</a>
                                <form method="POST" action="{{ route('admin.testimonials.destroy', $t) }}" onsubmit="return confirm('Supprimer ce témoignage ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Supprimer</button>
                                </form>
                                <form method="POST" action="{{ route('admin.testimonials.toggle-status', $t) }}">
                                    @csrf
                                    <button class="btn btn-sm btn-warning">{{ $t->is_published ? 'Dépublier' : 'Publier' }}</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-4">Aucun témoignage</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $testimonials->links() }}</div>
    </div>
</div>
@endsection


