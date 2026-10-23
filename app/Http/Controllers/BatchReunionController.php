<?php

namespace App\Http\Controllers;

use App\Models\BatchReunion;
use App\Models\ReunionMemory;
use App\Models\ReunionRsvp;
use Database\Seeders\BatchReunionSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BatchReunionController extends Controller
{
    /**
     * Display the Batch Reunion Planner & Hub.
     */
    public function index(Request $request)
    {
        // Auto-seed default milestone reunions if none exist
        if (BatchReunion::count() === 0) {
            (new BatchReunionSeeder())->run();
        }

        $query = BatchReunion::with(['organizer', 'rsvps'])
            ->withCount(['rsvps', 'memories'])
            ->latest('event_date');

        // Filter by batch / graduation year
        if ($request->filled('batch_year')) {
            $query->where('batch_year', $request->batch_year);
        }

        // Filter by department
        if ($request->filled('department')) {
            $query->where('department', 'like', "%{$request->department}%");
        }

        // Search by keyword
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('venue', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $reunions = $query->paginate(9)->withQueryString();

        // Unique filter options
        $batchYears = BatchReunion::select('batch_year')->distinct()->whereNotNull('batch_year')->pluck('batch_year');
        $departments = ['Computer Engineering', 'Mechanical Engineering', 'Electrical Engineering', 'Civil Engineering', 'All Departments'];

        // Authenticated user's RSVPs
        $userRsvps = [];
        if (Auth::check()) {
            $userRsvps = ReunionRsvp::where('user_id', Auth::id())
                ->pluck('status', 'batch_reunion_id')
                ->toArray();
        }

        return view('reunions.index', compact('reunions', 'batchYears', 'departments', 'userRsvps'));
    }

    /**
     * Store a newly organized batch reunion.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Allowed for alumni, faculty, and admins
        if (!in_array($user->role, ['admin', 'alumni', 'faculty'])) {
            return back()->with('error', 'Only Alumni, Faculty, or Admins can organize batch reunions.');
        }

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'batch_year' => ['required', 'string', 'max:50'],
            'department' => ['nullable', 'string', 'max:100'],
            'event_date' => ['required', 'date', 'after:today'],
            'venue' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string', 'max:3000'],
            'milestone_tag' => ['nullable', 'string', 'max:100'],
            'fee_per_person' => ['nullable', 'integer', 'min:0'],
            'whatsapp_group_link' => ['nullable', 'url', 'max:255'],
            'banner' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ]);

        $bannerPath = null;
        if ($request->hasFile('banner')) {
            $bannerPath = $request->file('banner')->store('reunions', 'public');
        }

        $slug = Str::slug($request->title) . '-' . strtolower(Str::random(4));

        $reunion = BatchReunion::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'slug' => $slug,
            'batch_year' => $request->batch_year,
            'department' => $request->department ?: 'All Departments',
            'event_date' => $request->event_date,
            'venue' => $request->venue,
            'city' => $request->city,
            'description' => $request->description,
            'milestone_tag' => $request->milestone_tag,
            'banner_image' => $bannerPath,
            'fee_per_person' => (int) ($request->fee_per_person ?? 0),
            'whatsapp_group_link' => $request->whatsapp_group_link,
            'status' => 'upcoming',
        ]);

        // Auto RSVP organizer as attending
        ReunionRsvp::create([
            'batch_reunion_id' => $reunion->id,
            'user_id' => $user->id,
            'status' => 'attending',
            'guests_count' => 0,
            'carpool_note' => 'Reunion Organizer',
            'nostalgic_quote' => 'Looking forward to meeting all classmates and faculty!',
        ]);

        return redirect()->route('reunions.show', $reunion->slug)
            ->with('success', 'Batch reunion organized successfully! Invite your classmates to RSVP.');
    }

    /**
     * Display the specified Batch Reunion with RSVP Roster and Memory Wall.
     */
    public function show(BatchReunion $reunion)
    {
        $reunion->load(['organizer.profile', 'memories.user.profile']);

        $attendingList = $reunion->rsvps()->where('status', 'attending')->with('user.profile')->latest()->get();
        $maybeList = $reunion->rsvps()->where('status', 'maybe')->with('user.profile')->latest()->get();
        $declinedList = $reunion->rsvps()->where('status', 'declined')->with('user.profile')->latest()->get();

        $confirmedAttendeesCount = $attendingList->count();
        $totalHeadcount = $attendingList->sum(fn ($r) => 1 + ($r->guests_count ?? 0));

        $myRsvp = Auth::check() ? $reunion->userRsvp(Auth::user()) : null;

        return view('reunions.show', compact(
            'reunion', 'attendingList', 'maybeList', 'declinedList',
            'confirmedAttendeesCount', 'totalHeadcount', 'myRsvp'
        ));
    }

    /**
     * Submit or update RSVP status for a reunion.
     */
    public function rsvp(Request $request, BatchReunion $reunion)
    {
        $user = Auth::user();

        $request->validate([
            'status' => ['required', 'in:attending,maybe,declined'],
            'guests_count' => ['nullable', 'integer', 'min:0', 'max:5'],
            'carpool_note' => ['nullable', 'string', 'max:255'],
            'nostalgic_quote' => ['nullable', 'string', 'max:500'],
        ]);

        ReunionRsvp::updateOrCreate(
            [
                'batch_reunion_id' => $reunion->id,
                'user_id' => $user->id,
            ],
            [
                'status' => $request->status,
                'guests_count' => (int) ($request->guests_count ?? 0),
                'carpool_note' => $request->carpool_note,
                'nostalgic_quote' => $request->nostalgic_quote,
            ]
        );

        $statusText = match ($request->status) {
            'attending' => 'Confirmed Attending! We look forward to seeing you.',
            'maybe' => 'Marked as Interested / Tentative.',
            'declined' => 'Marked as Unable to Attend.',
        };

        return back()->with('success', 'Your RSVP for ' . $reunion->title . ' has been updated: ' . $statusText);
    }

    /**
     * Post a nostalgic memory or campus throwback photo on the Memory Wall.
     */
    public function storeMemory(Request $request, BatchReunion $reunion)
    {
        $user = Auth::user();

        $request->validate([
            'caption' => ['required', 'string', 'max:1000'],
            'year_taken' => ['nullable', 'string', 'max:20'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('reunion_memories', 'public');
        }

        $reunion->memories()->create([
            'user_id' => $user->id,
            'caption' => $request->caption,
            'year_taken' => $request->year_taken,
            'photo_path' => $photoPath,
            'likes_count' => 0,
        ]);

        return back()->with('success', 'Your throwback memory was posted to the Memory Wall!');
    }

    /**
     * Like/heart a throwback memory.
     */
    public function likeMemory(BatchReunion $reunion, ReunionMemory $memory)
    {
        $memory->increment('likes_count');

        return back()->with('success', 'Heart added to the memory!');
    }
}
