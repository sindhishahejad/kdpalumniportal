<?php

namespace App\Http\Controllers;

use App\Models\CampusMemorySpot;
use App\Models\CampusMemoryStory;
use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        $albums = GalleryAlbum::with('photos')->latest()->get();
        $memorySpots = CampusMemorySpot::with('stories')->orderBy('order_num')->get();

        return view('gallery.index', compact('albums', 'memorySpots'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120' // Max 5MB per image
        ]);

        $album = GalleryAlbum::create([
            'title' => $request->title,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('gallery', 'public'); 
                
                GalleryPhoto::create([
                    'gallery_album_id' => $album->id,
                    'image_path' => $path,
                ]);
            }
        }

        return back()->with('status', 'Gallery section created successfully!');
    }

    public function edit(GalleryAlbum $album)
    {
        $album->load('photos');
        return view('gallery.edit', compact('album'));
    }

    public function update(Request $request, GalleryAlbum $album)
    {
        $request->validate(['title' => 'required|string|max:255']);
        $album->update(['title' => $request->title]);
        return back()->with('status', 'Section renamed successfully!');
    }

    public function addPhotos(Request $request, GalleryAlbum $album)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120'
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('gallery', 'public'); 
                
                GalleryPhoto::create([
                    'gallery_album_id' => $album->id,
                    'image_path' => $path,
                ]);
            }
        }

        return back()->with('status', 'New photos added successfully!');
    }

    public function destroyPhoto(GalleryPhoto $photo)
    {
        Storage::disk('public')->delete($photo->image_path);
        $photo->delete();
        
        return back()->with('status', 'Photo removed successfully!');
    }

    /**
     * Submit an alumni throwback story pinned to a campus memory spot.
     */
    public function storeStory(Request $request)
    {
        $validated = $request->validate([
            'campus_memory_spot_id' => 'required|exists:campus_memory_spots,id',
            'author_name' => 'required|string|max:120',
            'batch_year' => 'required|integer|min:1970|max:' . (date('Y') + 1),
            'department' => 'required|string|max:120',
            'story' => 'required|string|max:1000',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('memories', 'public');
        }

        CampusMemoryStory::create([
            'campus_memory_spot_id' => $validated['campus_memory_spot_id'],
            'user_id' => auth()->id(),
            'author_name' => $validated['author_name'],
            'batch_year' => $validated['batch_year'],
            'department' => $validated['department'],
            'story' => $validated['story'],
            'photo_path' => $photoPath,
            'is_approved' => true,
        ]);

        return back()->with('status', 'Your throwback campus memory has been pinned to the time machine!');
    }

    /**
     * Upvote/like a campus memory story.
     */
    public function likeStory(CampusMemoryStory $story)
    {
        $story->increment('likes_count');

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'likes_count' => $story->likes_count]);
        }

        return back()->with('status', 'Thank you for appreciating this nostalgic memory!');
    }
}
