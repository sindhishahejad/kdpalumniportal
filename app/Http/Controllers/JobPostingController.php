<?php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use Illuminate\Http\Request;

class JobPostingController extends Controller
{
    public function index(Request $request)
    {
        $query = JobPosting::with('user.profile')
            ->where('is_active', true)
            ->latest();

        // 1. Filter by Employment Type (e.g. Apprenticeship for GIDC Connect)
        if ($request->filled('type') && in_array($request->type, ['full-time', 'part-time', 'apprenticeship', 'internship', 'contract'])) {
            $query->where('employment_type', $request->type);
        }

        // 2. Keyword Search across Title, Company, and Location
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $jobs = $query->paginate(12)->withQueryString();

        return view('jobs.index', compact('jobs'));
    }

    public function store(Request $request)
    {
        $user = $request->user();

        // Allow Admin, Alumni, and Faculty to post opportunities
        if (!in_array($user->role, ['admin', 'alumni', 'faculty'])) {
            return back()->with('error', 'Only Alumni, Faculty, or Administrators can post job openings and apprenticeships.');
        }

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'company' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'employment_type' => ['required', 'in:full-time,part-time,apprenticeship,internship,contract'],
            'description' => ['required', 'string'],
            'application_link_or_email' => ['nullable', 'string', 'max:255'],
        ]);

        $user->jobPostings()->create([
            'title' => $request->title,
            'company' => $request->company,
            'location' => $request->location,
            'employment_type' => $request->employment_type,
            'description' => $request->description,
            'application_link_or_email' => $request->application_link_or_email,
            'is_active' => true,
        ]);

        return back()
            ->with('success', 'Opportunity posted successfully to the KDP network!')
            ->with('job_status', 'Job posted successfully!');
    }

    public function destroy(JobPosting $job)
    {
        $user = auth()->user();

        // Allow Admins to delete any job, or original posters to delete their own listing
        if ($user->role !== 'admin' && $job->user_id !== $user->id) {
            return back()->with('error', 'Unauthorized. You can only delete your own listings.');
        }

        $job->delete();

        return back()
            ->with('success', 'Job listing removed successfully.')
            ->with('job_status', 'Job deleted successfully!');
    }
}
