<?php

namespace App\Http\Controllers;

use App\Services\GroqChatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ChatbotController extends Controller
{
    protected GroqChatService $groqService;

    public function __construct(GroqChatService $groqService)
    {
        $this->groqService = $groqService;
    }

    /**
     * Display the full-page AI Assistant & Counselor Hub.
     */
    public function index(): View
    {
        $history = session()->get('kdp_chat_history', []);
        $activeModel = config('services.groq.model', 'llama-3.3-70b-versatile');
        $hasApiKey = !empty(config('services.groq.api_key'));

        return view('chatbot.index', compact('history', 'activeModel', 'hasApiKey'));
    }

    /**
     * Process an incoming message via Groq API.
     */
    public function chat(Request $request): JsonResponse
    {
        $request->validate([
            'message' => 'required|string|min:1|max:2000',
        ]);

        $userMessage = trim($request->input('message'));
        $history = session()->get('kdp_chat_history', []);

        // Send to Groq service with user context
        $result = $this->groqService->chat($history, $userMessage, Auth::user());

        // Update session history
        $history[] = [
            'role' => 'user',
            'content' => $userMessage,
            'time' => now()->format('h:i A'),
        ];

        $history[] = [
            'role' => 'assistant',
            'content' => $result['reply'],
            'model' => $result['model'] ?? 'Groq LLaMA',
            'source' => $result['source'] ?? 'groq',
            'time' => now()->format('h:i A'),
        ];

        // Keep last 30 messages in session to prevent bloated session size
        if (count($history) > 30) {
            $history = array_slice($history, -30);
        }

        session()->put('kdp_chat_history', $history);

        return response()->json([
            'success' => true,
            'reply' => $result['reply'],
            'model' => $result['model'] ?? 'Groq LLaMA',
            'source' => $result['source'] ?? 'groq',
            'history' => $history,
        ]);
    }

    /**
     * Retrieve current conversation history.
     */
    public function history(): JsonResponse
    {
        $history = session()->get('kdp_chat_history', []);

        return response()->json([
            'success' => true,
            'history' => $history,
        ]);
    }

    /**
     * Clear the conversation history session.
     */
    public function clear(): JsonResponse
    {
        session()->forget('kdp_chat_history');

        return response()->json([
            'success' => true,
            'message' => 'Conversation history cleared successfully.',
        ]);
    }
}
