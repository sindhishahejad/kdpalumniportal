<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\RegionalChapter;
use App\Models\User;
use Database\Seeders\RegionalChapterSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegionalChapterController extends Controller
{
    /**
     * Display the Geographic Alumni Distribution Map & Regional Chapters Hub.
     */
    public function index(Request $request)
    {
        // Auto-seed default chapters if none exist
        if (RegionalChapter::count() === 0) {
            (new RegionalChapterSeeder())->run();
        }

        $query = RegionalChapter::with(['lead', 'members.profile', 'activities'])
            ->withCount('members')
            ->where('is_active', true);

        // Filter by region type (gujarat, national, global)
        if ($request->filled('type') && in_array($request->type, ['gujarat', 'national', 'global'])) {
            $query->where('region_type', $request->type);
        }

        // Search by chapter name, city or venue
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('meeting_venue', 'like', "%{$search}%");
            });
        }

        $chapters = $query->orderBy('name', 'asc')->get();

        // Calculate geographic alumni distribution metrics across Gujarat and India
        $totalAlumni = User::where('role', 'alumni')->where('is_approved', true)->count();
        $totalMembersJoined = \DB::table('chapter_memberships')->distinct('user_id')->count('user_id');

        // Distribution statistics by cluster
        $clusters = RegionalChapter::getClusterStats();

        // Member chapter associations for logged-in user
        $myChapterIds = [];
        if (Auth::check()) {
            $myChapterIds = Auth::user()->chapters()->pluck('regional_chapters.id')->toArray();
        }

        // Map pin data payload for Leaflet.js
        $mapPins = $chapters->map(function ($c) {
            return [
                'id' => $c->id,
                'name' => $c->name,
                'city' => $c->city,
                'state' => $c->state,
                'lat' => $c->latitude,
                'lng' => $c->longitude,
                'members_count' => $c->members_count,
                'url' => route('chapters.show', $c->slug),
                'venue' => $c->meeting_venue,
            ];
        });

        return view('chapters.index', compact(
            'chapters', 'clusters', 'totalAlumni', 'totalMembersJoined', 'myChapterIds', 'mapPins'
        ));
    }

    /**
     * Display a specific Regional Chapter with its community members & activities.
     */
    public function show(RegionalChapter $chapter)
    {
        $chapter->load(['lead.profile', 'members.profile', 'activities.user']);

        $isMember = Auth::check() ? $chapter->isMember(Auth::user()) : false;

        // Upcoming activities for this chapter
        $activities = $chapter->activities()
            ->with('user')
            ->where('event_date', '>=', now()->subDays(1))
            ->orderBy('event_date', 'asc')
            ->get();

        return view('chapters.show', compact('chapter', 'isMember', 'activities'));
    }

    /**
     * 1-Click Join or Leave a Regional Chapter.
     */
    public function join(RegionalChapter $chapter)
    {
        $user = Auth::user();

        if ($chapter->members()->where('user_id', $user->id)->exists()) {
            $chapter->members()->detach($user->id);
            $msg = "You have left the {$chapter->name}.";
        } else {
            $chapter->members()->attach($user->id, ['role' => 'member']);
            $msg = "Welcome to the {$chapter->name}! You are now connected with fellow regional alumni.";
        }

        return back()->with('success', $msg);
    }

    /**
     * Schedule a regional chapter meetup / meetup announcement.
     */
    public function storeActivity(Request $request, RegionalChapter $chapter)
    {
        $user = Auth::user();

        // Allowed for chapter members, chapter lead, or admin
        $isMember = $chapter->isMember($user);
        $isAdmin = $user->role === 'admin';
        $isLead = $chapter->lead_user_id === $user->id;

        if (!$isMember && !$isAdmin && !$isLead) {
            return back()->with('error', 'You must be a member of this chapter to schedule a meetup.');
        }

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'event_date' => ['required', 'date'],
            'venue' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        $chapter->activities()->create([
            'user_id' => $user->id,
            'title' => $request->title,
            'event_date' => $request->event_date,
            'venue' => $request->venue,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Regional chapter meetup scheduled successfully!');
    }
}
