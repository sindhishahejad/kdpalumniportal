<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'body' => ['required', 'string'],
        ]);

        $post->comments()->create([
            'user_id' => $request->user()->id,
            'body' => $request->body,
        ]);

        return back()->with('status', 'Reply posted successfully!');
    }

    public function destroy(Comment $comment)
    {
        $user = auth()->user();

        // Allow Admins to moderate/delete any reply, or author to delete their own
        if ($user->role !== 'admin' && $comment->user_id !== $user->id) {
            return back()->with('error', 'Unauthorized. You can only delete your own replies.');
        }

        $comment->delete();

        return back()->with('status', 'Reply removed successfully.');
    }
}