<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\JobPosting;
use App\Models\Notice;
use Illuminate\Http\Request;

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

    // ✨ NEW: Method to approve a user ✨
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
}