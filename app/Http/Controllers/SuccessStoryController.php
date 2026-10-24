<?php

namespace App\Http\Controllers;

use App\Models\SuccessStory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class SuccessStoryController extends Controller
{
    // PUBLIC: View all success stories
    public function index(Request $request)
    {
        $query = SuccessStory::query();

        if ($request->filled('department') && $request->department !== 'all') {
            $query->where('department', 'like', '%' . $request->department . '%');
        }

        if ($request->filled('search')) {
            $term = '%' . trim($request->search) . '%';
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', $term)
                  ->orWhere('alumni_name', 'like', $term)
                  ->orWhere('story', 'like', $term)
                  ->orWhere('department', 'like', $term);
            });
        }

        $stories = $query->latest()->paginate(9)->withQueryString();
        $featuredStory = SuccessStory::where('is_featured', true)->latest()->first();

        return view('success-stories.index', compact('stories', 'featuredStory'));
    }

    // ADMIN: View management panel
    public function adminIndex()
    {
        abort_if(!Auth::check() || Auth::user()->role !== 'admin', 403);
        $stories = SuccessStory::latest()->get();
        return view('admin.stories.index', compact('stories'));
    }

    // ADMIN: Store a new success story
    public function store(Request $request)
    {
        abort_if(!Auth::check() || Auth::user()->role !== 'admin', 403);

        $request->validate([
            'title' => 'required|string|max:255',
            'alumni_name' => 'required|string|max:255',
            'batch_year' => 'required|string|max:50',
            'department' => 'required|string|max:100',
            'story' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
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

    // ADMIN: Update an existing success story
    public function update(Request $request, SuccessStory $story)
    {
        abort_if(!Auth::check() || Auth::user()->role !== 'admin', 403);

        $request->validate([
            'title' => 'required|string|max:255',
            'alumni_name' => 'required|string|max:255',
            'batch_year' => 'required|string|max:50',
            'department' => 'required|string|max:100',
            'story' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $data = [
            'title' => $request->title,
            'alumni_name' => $request->alumni_name,
            'batch_year' => $request->batch_year,
            'department' => $request->department,
            'story' => $request->story,
            'is_featured' => $request->has('is_featured'),
        ];

        if ($request->hasFile('image')) {
            if ($story->image_path) {
                Storage::disk('public')->delete($story->image_path);
            }
            $data['image_path'] = $request->file('image')->store('success-stories', 'public');
        }

        $story->update($data);

        return back()->with('success', 'Success story updated successfully.');
    }

    // ADMIN: Delete a success story
    public function destroy(SuccessStory $story)
    {
        abort_if(!Auth::check() || Auth::user()->role !== 'admin', 403);

        if ($story->image_path) {
            Storage::disk('public')->delete($story->image_path);
        }
        
        $story->delete();

        return back()->with('success', 'Success story deleted successfully.');
    }
}
