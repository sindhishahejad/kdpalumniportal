<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        // Added 'comments.user' to eager loading for optimized performance
        $query = Post::with(['user.profile', 'comments.user'])->latest();

        if ($request->has('type') && in_array($request->type, ['knowledge', 'challenge'])) {
            $query->where('type', $request->type);
        }

        $posts = $query->paginate(10);

        return view('posts.index', compact('posts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'type' => ['required', 'in:knowledge,challenge'],
        ]);

        $request->user()->posts()->create([
            'title' => $request->title,
            'body' => $request->body,
            'type' => $request->type,
        ]);

        return redirect()->route('posts.index')->with('status', 'Post published successfully!');
    }

    public function destroy(Post $post)
    {
        $user = auth()->user();

        // Allow Admins to moderate/delete any post, or author to delete their own
        if ($user->role !== 'admin' && $post->user_id !== $user->id) {
            return back()->with('error', 'Unauthorized. You can only delete your own posts.');
        }

        $post->delete();

        return redirect()->route('posts.index')->with('status', 'Post removed successfully.');
    }
}