<?php

namespace App\Http\Controllers;

use App\Models\CampusMemorySpot;
use App\Models\CampusMemoryStory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminCampusSpotController extends Controller
{
    /**
     * Display all campus memory landmark spots and submitted stories for admin management.
     */
    public function index()
    {
        $spots = CampusMemorySpot::withCount('stories')->orderBy('order_num')->get();
        $recentStories = CampusMemoryStory::with('spot')->latest()->paginate(15);

        return view('admin.spots.index', compact('spots', 'recentStories'));
    }

    /**
     * Store a new campus memory spot.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'category' => 'required|string|in:academic,workshop,landmark,recreation,hostel,admin',
            'description' => 'required|string|max:1000',
            'x_percent' => 'required|numeric|min:0|max:100',
            'y_percent' => 'required|numeric|min:0|max:100',
            'icon' => 'required|string|max:50',
            'then_year' => 'required|string|max:20',
            'then_title' => 'required|string|max:150',
            'then_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'then_photo_url' => 'nullable|url|max:500',
            'now_year' => 'required|string|max:20',
            'now_title' => 'required|string|max:150',
            'now_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'now_photo_url' => 'nullable|url|max:500',
            'fun_fact' => 'nullable|string|max:500',
            'order_num' => 'nullable|integer',
        ]);

        // Process "Then" image
        if ($request->hasFile('then_photo')) {
            $thenPath = asset('storage/' . $request->file('then_photo')->store('spots', 'public'));
        } elseif ($request->filled('then_photo_url')) {
            $thenPath = $request->then_photo_url;
        } else {
            $thenPath = 'https://images.unsplash.com/photo-1541829070764-84a7d30dd3f3?auto=format&fit=crop&w=1200&q=80';
        }

        // Process "Now" image
        if ($request->hasFile('now_photo')) {
            $nowPath = asset('storage/' . $request->file('now_photo')->store('spots', 'public'));
        } elseif ($request->filled('now_photo_url')) {
            $nowPath = $request->now_photo_url;
        } else {
            $nowPath = 'https://images.unsplash.com/photo-1562774053-701939374585?auto=format&fit=crop&w=1200&q=80';
        }

        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug;
        $counter = 1;
        while (CampusMemorySpot::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        CampusMemorySpot::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'category' => $validated['category'],
            'description' => $validated['description'],
            'x_percent' => $validated['x_percent'],
            'y_percent' => $validated['y_percent'],
            'icon' => $validated['icon'],
            'then_photo_path' => $thenPath,
            'then_year' => $validated['then_year'],
            'then_title' => $validated['then_title'],
            'now_photo_path' => $nowPath,
            'now_year' => $validated['now_year'],
            'now_title' => $validated['now_title'],
            'fun_fact' => $validated['fun_fact'] ?? null,
            'order_num' => $validated['order_num'] ?? 0,
        ]);

        return back()->with('status', "Landmark spot '{$validated['name']}' created and placed on the campus map!");
    }

    /**
     * Update an existing campus memory spot.
     */
    public function update(Request $request, CampusMemorySpot $spot)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'category' => 'required|string|in:academic,workshop,landmark,recreation,hostel,admin',
            'description' => 'required|string|max:1000',
            'x_percent' => 'required|numeric|min:0|max:100',
            'y_percent' => 'required|numeric|min:0|max:100',
            'icon' => 'required|string|max:50',
            'then_year' => 'required|string|max:20',
            'then_title' => 'required|string|max:150',
            'then_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'then_photo_url' => 'nullable|url|max:500',
            'now_year' => 'required|string|max:20',
            'now_title' => 'required|string|max:150',
            'now_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'now_photo_url' => 'nullable|url|max:500',
            'fun_fact' => 'nullable|string|max:500',
            'order_num' => 'nullable|integer',
        ]);

        // Process "Then" image update
        $thenPath = $spot->then_photo_path;
        if ($request->hasFile('then_photo')) {
            $thenPath = asset('storage/' . $request->file('then_photo')->store('spots', 'public'));
        } elseif ($request->filled('then_photo_url')) {
            $thenPath = $request->then_photo_url;
        }

        // Process "Now" image update
        $nowPath = $spot->now_photo_path;
        if ($request->hasFile('now_photo')) {
            $nowPath = asset('storage/' . $request->file('now_photo')->store('spots', 'public'));
        } elseif ($request->filled('now_photo_url')) {
            $nowPath = $request->now_photo_url;
        }

        $spot->update([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'x_percent' => $validated['x_percent'],
            'y_percent' => $validated['y_percent'],
            'icon' => $validated['icon'],
            'then_photo_path' => $thenPath,
            'then_year' => $validated['then_year'],
            'then_title' => $validated['then_title'],
            'now_photo_path' => $nowPath,
            'now_year' => $validated['now_year'],
            'now_title' => $validated['now_title'],
            'fun_fact' => $validated['fun_fact'] ?? null,
            'order_num' => $validated['order_num'] ?? 0,
        ]);

        return back()->with('status', "Landmark spot '{$spot->name}' updated successfully!");
    }

    /**
     * Delete a campus memory spot.
     */
    public function destroy(CampusMemorySpot $spot)
    {
        $name = $spot->name;
        $spot->delete();

        return back()->with('status', "Landmark spot '{$name}' has been deleted.");
    }

    /**
     * Delete an inappropriate alumni story.
     */
    public function destroyStory(CampusMemoryStory $story)
    {
        $story->delete();

        return back()->with('status', 'Alumni throwback memory removed.');
    }
}
