<?php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use Illuminate\Http\Request;

class JobPostingController extends Controller
{
    public function index(Request $request)
    {
        // Fetch active jobs, ordered by newest first
        $jobs = JobPosting::with('user.profile')
            ->where('is_active', true)
            ->latest()
            ->paginate(10);

        return view('jobs.index', compact('jobs'));
    }

    public function store(Request $request)
    {
        // 1. SECURITY: Only allow Admins to post jobs
        if (auth()->user()->role !== 'admin') {
            return back()->with('error', 'Only Administrators can post job openings.');
        }

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'company' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'employment_type' => ['required', 'in:full-time,part-time,apprenticeship,internship,contract'],
            'description' => ['required', 'string'],
            'application_link_or_email' => ['nullable', 'string', 'max:255'],
        ]);

        $request->user()->jobPostings()->create([
            'title' => $request->title,
            'company' => $request->company,
            'location' => $request->location,
            'employment_type' => $request->employment_type,
            'description' => $request->description,
            'application_link_or_email' => $request->application_link_or_email,
            'is_active' => true, // 2. AUTO-APPROVE: Shows up instantly
        ]);

        // ✨ CHANGED: Now redirects back to the Admin Dashboard instead of the public job board
        return back()->with('job_status', 'Job posted successfully!');
    }

    // ✨ NEW: Added destroy method so Admins can delete jobs from the dashboard
    public function destroy(JobPosting $job)
    {
        if (auth()->user()->role !== 'admin') {
            return back()->with('error', 'Unauthorized.');
        }
        
        $job->delete();
        return back()->with('job_status', 'Job deleted successfully!');
    }
}