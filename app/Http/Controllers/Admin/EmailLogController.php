<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailLog;

class EmailLogController extends Controller
{
    public function index()
    {
        $emailLogs = EmailLog::withMetrics()
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.email-logs.index', compact('emailLogs'));
    }

    public function show(EmailLog $emailLog)
    {
        $emailLog->load(['trackings' => function ($q) {
            $q->orderByDesc('id');
        }, 'sender']);

        return view('admin.email-logs.show', compact('emailLog'));
    }
}


