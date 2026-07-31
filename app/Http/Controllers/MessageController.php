<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function store(Request $request, $recipientId)
    {
        $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $recipientId,
            'message' => $request->message,
        ]);

        return back()->with('success', 'Message sent successfully!');
    }

    /**
     * Display the authenticated user's inbox.
     */
    public function inbox()
    {
        $messages = Message::where('recipient_id', Auth::id())
            ->with('sender')
            ->latest()
            ->paginate(10);

        return view('messages.inbox', compact('messages'));
    }
}