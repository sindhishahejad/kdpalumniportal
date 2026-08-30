<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mail\NewMessageMail;
use Illuminate\Support\Facades\Mail;

class MessageController extends Controller
{
    /**
     * Display WhatsApp-style chat interface.
     */
    public function inbox($userId = null)
    {
        $currentUserId = Auth::id();

        // Get users with existing messages OR the currently selected user profile
        $conversations = User::where('id', '!=', $currentUserId)
            ->where(function ($query) use ($currentUserId, $userId) {
                $query->whereHas('sentMessages', function ($q) use ($currentUserId) {
                    $q->where('recipient_id', $currentUserId);
                })->orWhereHas('receivedMessages', function ($q) use ($currentUserId) {
                    $q->where('sender_id', $currentUserId);
                });

                // ✨ Ensure a newly clicked user appears in the sidebar immediately ✨
                if ($userId) {
                    $query->orWhere('id', $userId);
                }
            })
            ->get();

        if (!$userId && $conversations->isNotEmpty()) {
            return redirect()->route('messages.inbox', $conversations->first()->id);
        }

        $activeUser = $userId ? User::findOrFail($userId) : null;
        $messages = collect();

        if ($activeUser) {
            // Automatically mark incoming messages from active user as read
            Message::where('sender_id', $activeUser->id)
                ->where('recipient_id', $currentUserId)
                ->where('is_read', false)
                ->update(['is_read' => true]);

            // Fetch conversation history
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

        // ✨ Trigger automated email notification to the recipient ✨
        $recipient = User::findOrFail($recipientId);
        Mail::to($recipient->email)->queue(new NewMessageMail(Auth::user(), $request->message));

        return back();
    }
}