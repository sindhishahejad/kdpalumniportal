<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRsvp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Mail\EventRsvpMail;
use App\Notifications\PortalNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index()
    {
        $upcomingEvents = Event::whereDate('event_date', '>=', today())
                               ->withCount('rsvps')
                               ->orderBy('event_date', 'asc')
                               ->get();

        $pastEvents = Event::whereDate('event_date', '<', today())
                           ->withCount('rsvps')
                           ->orderBy('event_date', 'desc')
                           ->get();

        // For backward compatibility
        $events = $upcomingEvents;

        return view('events.index', compact('upcomingEvents', 'pastEvents', 'events'));
    }

    public function show(Event $event)
    {
        $event->loadCount('rsvps');
        return view('events.show', compact('event'));
    }

    public function store(Request $request)
    {
        abort_if(!Auth::check() || Auth::user()->role !== 'admin', 403, 'Unauthorized action.');

        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:50',
            'event_date' => 'required|date',
            'time_display' => 'required|string|max:255',
            'description' => 'nullable|string',
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
            'description' => $request->description,
            'image_path' => $imagePath,
        ]);

        return back()->with('event_status', 'Event scheduled successfully!');
    }

    public function update(Request $request, Event $event)
    {
        abort_if(!Auth::check() || Auth::user()->role !== 'admin', 403, 'Unauthorized action.');

        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:50',
            'event_date' => 'required|date',
            'time_display' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $data = [
            'title' => $request->title,
            'category' => strtoupper($request->category),
            'event_date' => $request->event_date,
            'time_display' => $request->time_display,
            'description' => $request->description,
        ];

        if ($request->hasFile('image')) {
            if ($event->image_path) {
                Storage::disk('public')->delete($event->image_path);
            }
            $data['image_path'] = $request->file('image')->store('events', 'public');
        }

        $event->update($data);

        return back()->with('event_status', 'Event updated successfully!');
    }

    public function rsvp(Event $event)
    {
        $user = Auth::user();

        $existingRsvp = EventRsvp::where('event_id', $event->id)->where('user_id', $user->id)->first();

        if ($existingRsvp) {
            return redirect()->route('events.ticket', $existingRsvp->id);
        }

        $ticketCode = 'KDP-PASS-' . $event->id . '-' . $user->id . '-' . strtoupper(Str::random(6));

        $rsvp = EventRsvp::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'ticket_code' => $ticketCode,
        ]);

        try {
            Mail::to($user->email)->queue(new EventRsvpMail($event, $user));
        } catch (\Throwable $e) {}

        try {
            $user->notify(new PortalNotification(
                'RSVP Confirmed!',
                'Your digital entry pass for ' . $event->title . ' has been successfully generated.',
                route('events.ticket', $rsvp->id)
            ));
        } catch (\Throwable $e) {}

        return redirect()->route('events.ticket', $rsvp->id)->with('event_status', 'RSVP confirmed! Your digital entry pass has been generated.');
    }

    public function showTicket(EventRsvp $rsvp)
    {
        abort_if($rsvp->user_id !== auth()->id() && auth()->user()->role !== 'admin', 403);
        
        return view('events.ticket', compact('rsvp'));
    }

    public function destroy(Event $event)
    {
        abort_if(!Auth::check() || Auth::user()->role !== 'admin', 403, 'Unauthorized action.');

        if ($event->image_path) {
            Storage::disk('public')->delete($event->image_path);
        }
        
        $event->delete();
        
        return back()->with('event_status', 'Event deleted successfully!');
    }
}