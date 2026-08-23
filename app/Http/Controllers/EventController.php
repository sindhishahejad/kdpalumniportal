<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function show(Event $event)
    {
        return view('events.show', compact('event'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:50',
            'event_date' => 'required|date',
            'time_display' => 'required|string|max:255',
            'description' => 'nullable|string', // Added description validation
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('events', 'public');
        }

        Event::create([
            'title' => $request->title,
            'category' => strtoupper($request->category),
            'event_date' => $request->event_date,
            'time_display' => $request->time_display,
            'description' => $request->description, // Save the description
            'image_path' => $imagePath,
        ]);

        return back()->with('event_status', 'Event created successfully!');
    }

    public function destroy(Event $event)
    {
        // Delete the image file from storage if it exists
        if ($event->image_path) {
            Storage::disk('public')->delete($event->image_path);
        }
        
        // Delete the database record
        $event->delete();
        
        return back()->with('event_status', 'Event deleted successfully!');
    }
}