<?php

namespace App\Http\Controllers;

use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        $albums = GalleryAlbum::with('photos')->latest()->get();
        return view('gallery.index', compact('albums'));
    }

    // Add this new method!
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120' // Max 5MB per image
        ]);

        // 1. Create the Album Section
        $album = GalleryAlbum::create([
            'title' => $request->title,
        ]);

        // 2. Process and save each image
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Stores in storage/app/public/gallery
                $path = $image->store('gallery', 'public'); 
                
                GalleryPhoto::create([
                    'gallery_album_id' => $album->id,
                    'image_path' => $path,
                ]);
            }
        }

        return back()->with('status', 'Gallery section created successfully!');
    }
}