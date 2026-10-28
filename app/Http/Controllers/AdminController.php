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
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'total_alumni' => User::where('role', 'alumni')->where('is_approved', true)->count(),
            'pending_jobs' => JobPosting::where('is_active', false)->count(),
            'active_students' => User::where('role', 'student')->where('is_approved', true)->count(),
            'available_tools' => \App\Models\ToolDonation::where('status', 'available')->count(),
            'open_pitches' => \App\Models\ProjectPitch::where('status', 'open')->count(),
            'total_donations' => (float) \App\Models\Donation::where('status', 'completed')->sum('amount'),
            'pending_inquiries' => \App\Models\ContactInquiry::where('status', 'new')->count(),
            'pending_documents' => \App\Models\DocumentRequest::where('status', 'pending')->count(),
        ];

        // Fetch pending registrations
        $pendingUsers = User::where('is_approved', false)->latest()->get();
        
        // Fetch approved active users
        $users = User::where('is_approved', true)->with('profile')->latest()->paginate(10);
        
        $notices = Notice::latest()->get();
        $albums = \App\Models\GalleryAlbum::withCount('photos')->latest()->get();
        $events = \App\Models\Event::withCount('rsvps')->orderBy('event_date', 'desc')->get();
        $jobs = \App\Models\JobPosting::latest()->get();
        $mapPins = \App\Models\RegionalChapter::getMapPins();
        $clusters = \App\Models\RegionalChapter::getClusterStats();
        $spots = \App\Models\CampusMemorySpot::withCount('stories')->orderBy('order_num')->get();
        $recentStories = \App\Models\CampusMemoryStory::with(['user', 'spot'])->latest()->take(8)->get();

        return view('dashboards.admin', compact('stats', 'users', 'pendingUsers', 'notices', 'albums', 'events', 'jobs', 'mapPins', 'clusters', 'spots', 'recentStories'));
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
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:10240',
        ]);

        $attachmentPath = null;
        $attachmentName = null;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachmentName = $file->getClientOriginalName();
            $attachmentPath = $file->store('notices', 'public');
        }

        Notice::create([
            'title' => $request->title,
            'body' => $request->body,
            'attachment_path' => $attachmentPath,
            'attachment_name' => $attachmentName,
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : true,
        ]);

        return back()->with('status', 'Notice published successfully to the portal.');
    }

    public function updateNotice(Request $request, Notice $notice)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:10240',
        ]);

        $attachmentPath = $notice->attachment_path;
        $attachmentName = $notice->attachment_name;

        if ($request->boolean('remove_attachment')) {
            if ($attachmentPath && Storage::disk('public')->exists($attachmentPath)) {
                Storage::disk('public')->delete($attachmentPath);
            }
            $attachmentPath = null;
            $attachmentName = null;
        }

        if ($request->hasFile('attachment')) {
            if ($attachmentPath && Storage::disk('public')->exists($attachmentPath)) {
                Storage::disk('public')->delete($attachmentPath);
            }
            $file = $request->file('attachment');
            $attachmentName = $file->getClientOriginalName();
            $attachmentPath = $file->store('notices', 'public');
        }

        $notice->update([
            'title' => $request->title,
            'body' => $request->body,
            'attachment_path' => $attachmentPath,
            'attachment_name' => $attachmentName,
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : $notice->is_active,
        ]);

        return back()->with('status', 'Notice updated successfully.');
    }

    public function toggleNotice(Notice $notice)
    {
        $notice->update([
            'is_active' => !$notice->is_active,
        ]);

        $status = $notice->is_active ? 'activated and is now live on the portal' : 'deactivated and hidden from the portal';
        return back()->with('status', "Notice has been {$status}.");
    }

    public function destroyNotice(Notice $notice)
    {
        if ($notice->attachment_path && Storage::disk('public')->exists($notice->attachment_path)) {
            Storage::disk('public')->delete($notice->attachment_path);
        }

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
