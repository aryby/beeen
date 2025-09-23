@extends('layouts.admin')

@section('title', 'Email Log #'.$emailLog->id)

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0">Contenu</h5></div>
                <div class="card-body" style="overflow:auto; max-height:70vh">
                    {!! $emailLog->body !!}
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0">Détails</h5></div>
                <div class="card-body">
                    <div class="mb-2"><strong>Sujet:</strong> {{ $emailLog->subject }}</div>
                    <div class="mb-2"><strong>Date:</strong> {{ $emailLog->created_at->format('Y-m-d H:i') }}</div>
                    <div class="mb-2"><strong>Expéditeur:</strong> {{ optional($emailLog->sender)->email ?? '-' }}</div>
                    <div class="mb-2"><strong>Envoyés:</strong> {{ $emailLog->total_sent }}</div>
                    <div class="mb-2"><strong>Ouverts (uniques):</strong> {{ $emailLog->unique_opens }}</div>
                    <div class="mb-2"><strong>Clics:</strong> {{ $emailLog->clicked_count }}</div>
                    <div class="mb-2"><strong>Bounces:</strong> {{ $emailLog->bounce_count }}</div>
                </div>
            </div>
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Destinataires</h5></div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Email</th>
                                <th>Opens</th>
                                <th>Clicks</th>
                                <th>Dernier</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($emailLog->trackings as $t)
                                <tr>
                                    <td>{{ $t->recipient }}</td>
                                    <td>{{ $t->open_count }}</td>
                                    <td>{{ $t->click_count }}</td>
                                    <td>{{ $t->last_open_at?->format('Y-m-d H:i') ?? ($t->last_clicked_at?->format('Y-m-d H:i') ?? '-') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center py-4">Aucun destinataire</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


