<?php

namespace App\Http\Controllers;

use App\Models\MentorshipListing;
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

        // ✨ Trigger automated email notification to the mentor ✨
        Mail::to($mentorUser->email)->queue(new MentorshipRequestMail(Auth::user(), $request->message));

        return back()->with('status', 'Mentorship request sent successfully!');
    }
}