<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Events\MessageSent; // ✨ Import the new event
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
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

        // ✨ Load the sender relation so the event can grab their name
        $message->load('sender');

        // ✨ Fire the broadcast event
        broadcast(new MessageSent($message))->toOthers();

        return back()->with('success', 'Message sent successfully!');
    }

    public function inbox()
    {
        $messages = Message::where('recipient_id', Auth::id())
            ->with('sender')
            ->latest()
            ->paginate(10);

        return view('messages.inbox', compact('messages'));
    }
}