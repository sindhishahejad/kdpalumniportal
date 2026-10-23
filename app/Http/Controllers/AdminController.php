<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\JobPosting;
use App\Models\Notice;
use App\Models\SuccessStory;
use App\Mail\WeeklyNewsletter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'total_alumni' => User::where('role', 'alumni')->where('is_approved', true)->count(),
            'pending_jobs' => JobPosting::where('is_active', false)->count(),
            'active_students' => User::where('role', 'student')->where('is_approved', true)->count(),
        ];

        // Fetch pending registrations
        $pendingUsers = User::where('is_approved', false)->latest()->get();
        
        // Fetch approved active users
        $users = User::where('is_approved', true)->with('profile')->latest()->paginate(10);
        
        $notices = Notice::latest()->get();

        return view('dashboards.admin', compact('stats', 'users', 'pendingUsers', 'notices'));
    }

    // Method to approve a user
    public function approveUser(User $user)
    {
        $user->update(['is_approved' => true]);
        return back()->with('status', "{$user->name}'s account has been approved and activated.");
    }

    public function destroyUser(User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Cannot delete an administrator account.');
        }
        $user->delete();
        return back()->with('status', 'User account successfully removed.');
    }

    public function storeNotice(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        Notice::create([
            'title' => $request->title,
            'body' => $request->body,
            'is_active' => true,
        ]);

        return back()->with('status', 'Notice published successfully.');
    }

    public function destroyNotice(Notice $notice)
    {
        $notice->delete();
        return back()->with('status', 'Notice removed successfully.');
    }

    /**
     * Dispatch or test the weekly community newsletter digest.
     */
    public function sendNewsletter(Request $request)
    {
        $successStories = SuccessStory::latest()->take(3)->get();
        $jobs = JobPosting::where('is_active', true)->latest()->take(3)->get();
        $notices = Notice::latest()->take(3)->get();

        if ($request->boolean('test_only')) {
            $adminEmail = auth()->user()->email;
            Mail::to($adminEmail)->queue(
                new WeeklyNewsletter($successStories, $jobs, $notices)
            );
            return back()->with('status', "Test newsletter digest has been queued to {$adminEmail}.");
        }

        $users = User::where('is_approved', true)
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->get();

        foreach ($users as $user) {
            Mail::to($user->email)->queue(
                new WeeklyNewsletter($successStories, $jobs, $notices)
            );
        }

        return back()->with('status', "Weekly newsletter digest successfully queued for {$users->count()} active members.");
    }
}
