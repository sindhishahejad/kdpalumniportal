<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mail\NewMessageMail;
use Illuminate\Support\Facades\Log;
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
        $users = User::where('id', '!=', $currentUserId)
            ->where('is_approved', true)
            ->where(function ($query) use ($currentUserId, $userId) {
                $query->whereHas('sentMessages', function ($q) use ($currentUserId) {
                    $q->where('recipient_id', $currentUserId);
                })->orWhereHas('receivedMessages', function ($q) use ($currentUserId) {
                    $q->where('sender_id', $currentUserId);
                });

                if ($userId) {
                    $query->orWhere('id', $userId);
                }
            })
            ->get();

        // Enrich each conversation partner with latest message, unread count, and timestamp
        $conversations = $users->map(function ($user) use ($currentUserId) {
            $latestMessage = Message::where(function ($q) use ($currentUserId, $user) {
                $q->where('sender_id', $currentUserId)->where('recipient_id', $user->id);
            })->orWhere(function ($q) use ($currentUserId, $user) {
                $q->where('sender_id', $user->id)->where('recipient_id', $currentUserId);
            })->latest()->first();

            $unreadCount = Message::where('sender_id', $user->id)
                ->where('recipient_id', $currentUserId)
                ->where('is_read', false)
                ->count();

            $user->latest_message = $latestMessage ? $latestMessage->message : null;
            $user->latest_message_time = $latestMessage ? $latestMessage->created_at : null;
            $user->latest_message_time_human = $latestMessage 
                ? ($latestMessage->created_at->isToday() 
                    ? $latestMessage->created_at->format('h:i A') 
                    : ($latestMessage->created_at->isYesterday() ? 'Yesterday' : $latestMessage->created_at->format('d/m/Y')))
                : null;
            $user->unread_count = $unreadCount;

            return $user;
        })
        ->sortByDesc(function ($user) {
            return $user->latest_message_time ? $user->latest_message_time->timestamp : 0;
        })
        ->values();

        if (!$userId && $conversations->isNotEmpty()) {
            return redirect()->route('messages.inbox', $conversations->first()->id);
        }

        $activeUser = $userId ? User::where('is_approved', true)->findOrFail($userId) : null;
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

        // Available contacts for "New Chat" modal and empty state recommendations
        $availableContacts = User::where('id', '!=', $currentUserId)
            ->where('is_approved', true)
            ->select('id', 'name', 'role', 'department', 'email')
            ->orderBy('name', 'asc')
            ->take(30)
            ->get();

        return view('messages.inbox', compact('conversations', 'activeUser', 'messages', 'availableContacts'));
    }

    public function store(Request $request, $recipientId)
    {
        $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $recipient = User::where('is_approved', true)->findOrFail($recipientId);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $recipient->id,
            'message' => trim($request->message),
            'is_read' => false,
        ]);

        $message->load('sender');

        // Broadcast event in real-time
        try {
            broadcast(new MessageSent($message));
        } catch (\Throwable $e) {
            Log::warning('Broadcast failed for message ' . $message->id . ': ' . $e->getMessage());
        }

        // Trigger automated email notification to the recipient
        try {
            Mail::to($recipient->email)->queue(new NewMessageMail(Auth::user(), $request->message));
        } catch (\Throwable $e) {
            Log::warning('Failed to queue new message email: ' . $e->getMessage());
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => [
                    'id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'recipient_id' => $message->recipient_id,
                    'text' => $message->message,
                    'is_read' => false,
                    'time' => $message->created_at->format('h:i A'),
                    'date_badge' => 'Today',
                ],
            ]);
        }

        return back();
    }
}
