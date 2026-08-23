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

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120' // Max 5MB per image
        ]);

        // 1. Create the Album Section code here.
        $album = GalleryAlbum::create([
            'title' => $request->title,
        ]);

        // 2. Process and save each image code here.
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Stores in storage/app/public/gallery code
                $path = $image->store('gallery', 'public'); 
                
                GalleryPhoto::create([
                    'gallery_album_id' => $album->id,
                    'image_path' => $path,
                ]);
            }
        }

        return back()->with('status', 'Gallery section created successfully!');
    }

    // ========================================================
    // NEW METHODS START HERE.
    // ========================================================

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
                
                \App\Models\GalleryPhoto::create([
                    'gallery_album_id' => $album->id,
                    'image_path' => $path,
                ]);
            }
        }

        return back()->with('status', 'New photos added successfully!');
    }

    public function destroyPhoto(\App\Models\GalleryPhoto $photo)
    {
        // Delete the physical file from storage
        \Illuminate\Support\Facades\Storage::disk('public')->delete($photo->image_path);
        // Delete the database record
        $photo->delete();
        
        return back()->with('status', 'Photo removed successfully!');
    }
}
