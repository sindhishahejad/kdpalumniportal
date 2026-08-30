<?php

namespace App\Http\Controllers;

use App\Models\SuccessStory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SuccessStoryController extends Controller
{
    // PUBLIC: View all success stories
    public function index()
    {
        $stories = SuccessStory::latest()->get();
        return view('success-stories.index', compact('stories'));
    }

    // ADMIN: View management panel
    public function adminIndex()
    {
        $stories = SuccessStory::latest()->get();
        return view('admin.stories.index', compact('stories'));
    }

    // ADMIN: Store a new success story
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'alumni_name' => 'required|string|max:255',
            'batch_year' => 'required|string|max:50',
            'department' => 'required|string|max:100',
            'story' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('success-stories', 'public');
        }

        SuccessStory::create([
            'title' => $request->title,
            'alumni_name' => $request->alumni_name,
            'batch_year' => $request->batch_year,
            'department' => $request->department,
            'story' => $request->story,
            'image_path' => $imagePath,
            'is_featured' => $request->has('is_featured'),
        ]);

        return back()->with('success', 'Success story published successfully.');
    }

    // ADMIN: Delete a success story
    public function destroy(SuccessStory $story)
    {
        if ($story->image_path) {
            Storage::disk('public')->delete($story->image_path);
        }
        
        $story->delete();

        return back()->with('success', 'Success story deleted successfully.');
    }
}