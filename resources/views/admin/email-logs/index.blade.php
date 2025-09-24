@extends('layouts.admin')

@section('title', 'Email Logs')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Email Logs</h5>
        </div>
        <div class="table-responsive">
            <table class="table align-items-center mb-0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Sujet</th>
                        <th>Expéditeur</th>
                        <th>Envoyés</th>
                        <th>Ouverts</th>
                        <th>Clics</th>
                        <th>Bounces</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($emailLogs as $log)
                        <tr>
                            <td>{{ $log->created_at->format('Y-m-d H:i') }}</td>
                            <td>{{ $log->subject }}</td>
                            <td>{{ optional($log->sender)->email ?? '-' }}</td>
                            <td>{{ $log->total_sent }}</td>
                            <td>{{ $log->unique_opens }}</td>
                            <td>{{ $log->clicked_count }}</td>
                            <td>{{ $log->bounce_count }}</td>
                            <td>
                                <a href="{{ route('admin.email-logs.show', $log) }}" class="btn btn-sm btn-primary">Voir</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center py-4">Aucun enregistrement</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $emailLogs->links() }}
        </div>
    </div>
</div>
@endsection


