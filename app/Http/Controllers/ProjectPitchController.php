<?php

namespace App\Http\Controllers;

use App\Models\ProjectPitch;
use App\Models\Message;
use App\Notifications\PortalNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectPitchController extends Controller
{
    public function index(Request $request)
    {
        $query = ProjectPitch::with('student')->latest();

        // 1. Filter by Status
        if ($request->filled('status') && in_array($request->status, ['open', 'in_progress', 'completed'])) {
            $query->where('status', $request->status);
        }

        // 2. Keyword Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('tech_stack', 'like', "%{$search}%")
                  ->orWhere('assistance_needed', 'like', "%{$search}%")
                  ->orWhereHas('student', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // 3. Filter My Pitches
        if ($request->boolean('my_pitches') && Auth::check()) {
            $query->where('user_id', Auth::id());
        }

        $pitches = $query->paginate(9)->withQueryString();

        $stats = [
            'total' => ProjectPitch::count(),
            'open' => ProjectPitch::where('status', 'open')->count(),
            'in_progress' => ProjectPitch::where('status', 'in_progress')->count(),
            'completed' => ProjectPitch::where('status', 'completed')->count(),
        ];

        return view('pitches.index', compact('pitches', 'stats'));
    }

    public function create()
    {
        return view('pitches.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'tech_stack' => 'required|string|max:255',
            'assistance_needed' => 'required|string|max:255',
        ]);

        $pitch = ProjectPitch::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'tech_stack' => $request->tech_stack,
            'assistance_needed' => $request->assistance_needed,
            'status' => 'open',
        ]);

        return redirect()->route('pitches.show', $pitch->id)->with('success', 'Project pitch submitted successfully!');
    }

    public function show(ProjectPitch $pitch)
    {
        $pitch->load('student');
        return view('pitches.show', compact('pitch'));
    }

    public function updateStatus(Request $request, ProjectPitch $pitch)
    {
        $user = Auth::user();

        // Only pitch owner, faculty, or admin can update status
        if ($pitch->user_id !== $user->id && $user->role !== 'faculty' && !$user->isAdmin()) {
            abort(403, 'Unauthorized to update this project status.');
        }

        $request->validate([
            'status' => 'required|in:open,in_progress,completed',
        ]);

        $pitch->update(['status' => $request->status]);

        // Notify student if status was updated by faculty or admin
        if ($pitch->user_id !== $user->id && $pitch->student) {
            $pitch->student->notify(new PortalNotification(
                'Project Incubation Status Updated',
                "Your project '{$pitch->title}' status has been updated to " . ucfirst(str_replace('_', ' ', $request->status)) . " by {$user->name}.",
                route('pitches.show', $pitch->id)
            ));
        }

        return back()->with('success', 'Project incubation status updated successfully!');
    }

    public function offerGuidance(Request $request, ProjectPitch $pitch)
    {
        $mentor = Auth::user();

        if ($mentor->id === $pitch->user_id) {
            return back()->with('error', 'You cannot offer guidance to your own project pitch.');
        }

        $request->validate([
            'guidance' => 'required|string|min:5|max:1000',
        ]);

        // 1. Deliver direct in-app message
        Message::create([
            'sender_id' => $mentor->id,
            'recipient_id' => $pitch->user_id,
            'message' => "[Project Incubation - '{$pitch->title}'] " . $request->guidance,
        ]);

        // 2. Deliver in-app notification to the pitching student
        if ($pitch->student) {
            $pitch->student->notify(new PortalNotification(
                'New Project Mentorship Guidance',
                "{$mentor->name} ({$mentor->role}) offered guidance on your project '{$pitch->title}'.",
                route('messages.inbox', $mentor->id)
            ));
        }

        return back()->with('success', 'Mentorship guidance sent! A direct chat thread has been opened with the student.');
    }

    public function destroy(ProjectPitch $pitch)
    {
        $user = Auth::user();

        if ($pitch->user_id !== $user->id && !$user->isAdmin()) {
            abort(403, 'Unauthorized to delete this project pitch.');
        }

        $pitch->delete();

        return redirect()->route('pitches.index')->with('success', 'Project pitch deleted successfully.');
    }
}
