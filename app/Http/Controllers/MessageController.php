<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Display WhatsApp-style chat interface.
     */
    public function inbox($userId = null)
    {
        $currentUserId = Auth::id();

        // 1. Get all unique users who have exchanged messages with the authenticated user
        $conversations = User::where('id', '!=', $currentUserId)
            ->where(function ($query) use ($currentUserId) {
                $query->whereHas('sentMessages', function ($q) use ($currentUserId) {
                    $q->where('recipient_id', $currentUserId);
                })->orWhereHas('receivedMessages', function ($q) use ($currentUserId) {
                    $q->where('sender_id', $currentUserId);
                });
            })
            ->get();

        // If no user is selected yet, default to the first conversation if available
        if (!$userId && $conversations->isNotEmpty()) {
            return redirect()->route('messages.inbox', $conversations->first()->id);
        }

        $activeUser = $userId ? User::findOrFail($userId) : null;
        $messages = collect();

        if ($activeUser) {
            // Fetch conversation history between current user and active user
            $messages = Message::where(function ($q) use ($currentUserId, $activeUser) {
                $q->where('sender_id', $currentUserId)->where('recipient_id', $activeUser->id);
            })->orWhere(function ($q) use ($currentUserId, $activeUser) {
                $q->where('sender_id', $activeUser->id)->where('recipient_id', $currentUserId);
            })->orderBy('created_at', 'asc')->get();
        }

        return view('messages.inbox', compact('conversations', 'activeUser', 'messages'));
    }

    public function store(Request $request, $recipientId)
    {
        $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $recipientId,
            'message' => $request->message,
        ]);

        $message->load('sender');

        // Broadcast event in real-time without toOthers() since this is a standard form post
        broadcast(new MessageSent($message));

        return back();
    }
}