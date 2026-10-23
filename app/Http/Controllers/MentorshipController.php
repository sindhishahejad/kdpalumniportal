<?php

namespace App\Http\Controllers;

use App\Models\MentorshipListing;
use App\Models\Message;
use App\Notifications\PortalNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mail\MentorshipRequestMail;
use Illuminate\Support\Facades\Mail;

class MentorshipController extends Controller
{
    public function index()
    {
        // Fetch all available mentors
        $mentors = MentorshipListing::with('user.profile')
            ->where('is_available', true)
            ->latest()
            ->paginate(10);

        // Fetch the current user's listing if they have one
        $userListing = Auth::user()->mentorshipListing;

        return view('mentorship.index', compact('mentors', 'userListing'));
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
        ]);

        $mentorUser = $listing->user;

        if ($mentorUser->id === Auth::id()) {
            return back()->with('error', 'You cannot send a mentorship request to yourself.');
        }

        // 1. Trigger automated email notification to the mentor
        Mail::to($mentorUser->email)->queue(new MentorshipRequestMail(Auth::user(), $request->message));

        // 2. Deliver in-app message so the mentor sees it in their Inbox
        Message::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $mentorUser->id,
            'message' => '[Mentorship Request] ' . $request->message,
        ]);

        // 3. Dispatch in-app notification bell update
        $mentorUser->notify(new PortalNotification(
            'New Mentorship Request',
            Auth::user()->name . ' requested mentorship guidance.',
            route('messages.inbox', Auth::id())
        ));

        return back()->with('status', 'Mentorship request sent successfully! The mentor has been notified.');
    }
}