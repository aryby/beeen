<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');
        $type = $request->get('type');

        $messages = Message::query()
            ->when($status, function ($query, $status) {
                return $query->byStatus($status);
            })
            ->when($type, function ($query, $type) {
                return $query->byType($type);
            })
            ->latest()
            ->paginate(20);

        $stats = [
            'total' => Message::count(),
            'open' => Message::open()->count(),
            'in_progress' => Message::inProgress()->count(),
            'resolved' => Message::resolved()->count(),
            'unread' => Message::unread()->count(),
        ];

        return view('admin.messages.index', compact('messages', 'stats', 'status', 'type'));
    }

    public function show(Message $message)
    {
        return view('admin.messages.show', compact('message'));
    }

    public function reply(Request $request, Message $message)
    {
        $validated = $request->validate([
            'reply_message' => 'required|string|max:2000',
        ]);

        // TODO: Envoyer la réponse par email
        // Mail::to($message->email)->send(new MessageReply($message, $validated['reply_message']));

        $message->update([
            'status' => 'resolved',
            'replied_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Réponse envoyée avec succès.');
    }

    public function updateStatus(Request $request, Message $message)
    {
        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,resolved',
        ]);

        $message->update(['status' => $validated['status']]);

        return redirect()->back()
            ->with('success', 'Statut mis à jour.');
    }

    public function destroy(Message $message)
    {
        $message->delete();

        return redirect()->route('admin.messages.index')
            ->with('success', 'Message supprimé avec succès.');
    }
}