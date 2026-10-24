<?php

namespace App\Http\Controllers;

use App\Models\MentorshipListing;
use App\Models\MentorshipRequest;
use App\Models\Message;
use App\Notifications\PortalNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mail\MentorshipRequestMail;
use Illuminate\Support\Facades\Mail;

class MentorshipController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // 1. Fetch all available mentors with search/filter support
        $mentorsQuery = MentorshipListing::with('user.profile')
            ->where('is_available', true);

        if ($request->filled('search')) {
            $term = '%' . trim($request->search) . '%';
            $mentorsQuery->where(function ($q) use ($term) {
                $q->where('title', 'like', $term)
                  ->orWhere('expertise_areas', 'like', $term)
                  ->orWhere('description', 'like', $term)
                  ->orWhereHas('user', function ($uq) use ($term) {
                      $uq->where('name', 'like', $term)
                         ->orWhereHas('profile', function ($pq) use ($term) {
                             $pq->where('department', 'like', $term)
                                ->orWhere('current_company', 'like', $term);
                         });
                  });
            });
        }

        if ($request->filled('department') && $request->department !== 'all') {
            $deptTerm = '%' . $request->department . '%';
            $mentorsQuery->whereHas('user.profile', function ($pq) use ($deptTerm) {
                $pq->where('department', 'like', $deptTerm);
            });
        }

        $mentors = $mentorsQuery->latest()->paginate(9)->withQueryString();

        // 2. Fetch the current user's mentor profile
        $userListing = $user->mentorshipListing;

        // 3. Fetch requests sent by current user as a student/mentee
        $mySentRequests = MentorshipRequest::with(['mentor.profile', 'listing'])
            ->where('mentee_id', $user->id)
            ->latest()
            ->get();

        // 4. Fetch incoming requests received by current user as a mentor
        $myReceivedRequests = MentorshipRequest::with(['mentee.profile', 'listing'])
            ->where('mentor_id', $user->id)
            ->latest()
            ->get();

        $pendingReceivedCount = $myReceivedRequests->where('status', 'pending')->count();
        $activeSessionsCount = $mySentRequests->where('status', 'accepted')->count() + $myReceivedRequests->where('status', 'accepted')->count();

        return view('mentorship.index', compact(
            'mentors',
            'userListing',
            'mySentRequests',
            'myReceivedRequests',
            'pendingReceivedCount',
            'activeSessionsCount'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'expertise_areas' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ]);

        $request->user()->mentorshipListing()->updateOrCreate(
            ['user_id' => $request->user()->id],
            [
                'title' => $request->title,
                'expertise_areas' => $request->expertise_areas,
                'description' => $request->description,
                'is_available' => $request->has('is_available'),
            ]
        );

        return back()->with('status', 'Mentorship profile updated successfully!');
    }

    public function sendRequest(Request $request, MentorshipListing $listing)
    {
        $request->validate([
            'message' => ['required', 'string', 'max:1000'],
            'topic' => ['nullable', 'string', 'max:255'],
        ]);

        $mentorUser = $listing->user;

        if ($mentorUser->id === Auth::id()) {
            return back()->with('error', 'You cannot send a mentorship request to yourself.');
        }

        // 1. Create formal Mentorship Request record
        $mentorshipRequest = MentorshipRequest::create([
            'mentorship_listing_id' => $listing->id,
            'mentor_id' => $mentorUser->id,
            'mentee_id' => Auth::id(),
            'topic' => $request->topic,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        // 2. Trigger automated email notification to the mentor
        try {
            Mail::to($mentorUser->email)->queue(new MentorshipRequestMail(Auth::user(), $request->message));
        } catch (\Throwable $e) {}

        // 3. Deliver in-app message so the mentor sees it in their Inbox
        Message::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $mentorUser->id,
            'message' => '[Mentorship Request' . ($request->topic ? ': ' . $request->topic : '') . '] ' . $request->message,
        ]);

        // 4. Dispatch in-app notification bell update
        try {
            $mentorUser->notify(new PortalNotification(
                'New Mentorship Request',
                Auth::user()->name . ' requested 1-on-1 mentorship guidance.',
                route('mentorship.index') . '#mentor-requests'
            ));
        } catch (\Throwable $e) {}

        return back()->with('status', 'Mentorship request sent successfully! You can track its status in "My Mentorship Sessions".');
    }

    public function acceptRequest(Request $request, MentorshipRequest $mentorshipRequest)
    {
        abort_if(Auth::id() !== $mentorshipRequest->mentor_id, 403, 'Unauthorized.');

        $request->validate([
            'meeting_link' => ['nullable', 'url', 'max:255'],
            'session_time' => ['nullable', 'string', 'max:255'],
            'mentor_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $mentorshipRequest->update([
            'status' => 'accepted',
            'meeting_link' => $request->meeting_link,
            'session_time' => $request->session_time,
            'mentor_notes' => $request->mentor_notes,
        ]);

        // Send confirmation chat message with meeting details
        $mentee = $mentorshipRequest->mentee;
        $detailsMsg = "Great news! I have accepted your mentorship request.";
        if ($request->session_time) {
            $detailsMsg .= " Scheduled Time: " . $request->session_time;
        }
        if ($request->meeting_link) {
            $detailsMsg .= " Meeting Link: " . $request->meeting_link;
        }
        if ($request->mentor_notes) {
            $detailsMsg .= " Note: " . $request->mentor_notes;
        }

        Message::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $mentee->id,
            'message' => '[Mentorship Accepted] ' . $detailsMsg,
        ]);

        try {
            $mentee->notify(new PortalNotification(
                'Mentorship Request Accepted!',
                Auth::user()->name . ' accepted your mentorship request and scheduled your session.',
                route('mentorship.index') . '#my-sessions'
            ));
        } catch (\Throwable $e) {}

        return back()->with('status', 'Mentorship request accepted and session confirmed!');
    }

    public function completeSession(Request $request, MentorshipRequest $mentorshipRequest)
    {
        abort_if(Auth::id() !== $mentorshipRequest->mentor_id && Auth::id() !== $mentorshipRequest->mentee_id && Auth::user()->role !== 'admin', 403, 'Unauthorized.');

        $mentorshipRequest->update(['status' => 'completed']);

        return back()->with('status', 'Mentorship session marked as completed. Thank you for guiding fellow polytechnic students!');
    }

    public function declineRequest(Request $request, MentorshipRequest $mentorshipRequest)
    {
        abort_if(Auth::id() !== $mentorshipRequest->mentor_id, 403, 'Unauthorized.');

        $request->validate([
            'mentor_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $mentorshipRequest->update([
            'status' => 'declined',
            'mentor_notes' => $request->mentor_notes,
        ]);

        try {
            $mentorshipRequest->mentee->notify(new PortalNotification(
                'Mentorship Update',
                Auth::user()->name . ' could not accept your request at this time.',
                route('mentorship.index') . '#my-sessions'
            ));
        } catch (\Throwable $e) {}

        return back()->with('status', 'Mentorship request updated.');
    }

    public function cancelRequest(MentorshipRequest $mentorshipRequest)
    {
        abort_if(Auth::id() !== $mentorshipRequest->mentee_id && Auth::user()->role !== 'admin', 403, 'Unauthorized.');

        $mentorshipRequest->delete();

        return back()->with('status', 'Mentorship request cancelled.');
    }
}
