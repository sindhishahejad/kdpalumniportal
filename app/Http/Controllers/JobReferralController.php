<?php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use App\Models\JobReferral;
use App\Notifications\PortalNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class JobReferralController extends Controller
{
    /**
     * Store a new referral request with resume upload.
     */
    public function store(Request $request, JobPosting $job)
    {
        $user = Auth::user();

        // 1. Prevent job poster from applying to their own listing
        if ($user->id === $job->user_id) {
            return back()->with('error', 'You cannot request a referral for your own job posting.');
        }

        // 2. Prevent duplicate referral requests for the same job
        $existing = JobReferral::where('job_posting_id', $job->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existing) {
            return back()->with('error', 'You have already submitted a referral request for this opportunity.');
        }

        // 3. Validate form submission
        $request->validate([
            'candidate_name' => ['required', 'string', 'max:255'],
            'candidate_email' => ['required', 'email', 'max:255'],
            'candidate_phone' => ['nullable', 'string', 'max:25'],
            'candidate_linkedin' => ['nullable', 'url', 'max:255'],
            'department' => ['nullable', 'string', 'max:100'],
            'enrollment_no' => ['nullable', 'string', 'max:50'],
            'graduation_year' => ['nullable', 'string', 'max:20'],
            'cpi' => ['nullable', 'string', 'max:10'],
            'portfolio_url' => ['nullable', 'url', 'max:255'],
            'cover_note' => ['nullable', 'string', 'max:2000'],
            'resume' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:5120'], // 5MB max
        ]);

        // 4. Store uploaded resume securely
        $resumePath = $request->file('resume')->store('resumes', 'public');

        // 5. Create referral record
        $referral = JobReferral::create([
            'job_posting_id' => $job->id,
            'user_id' => $user->id,
            'candidate_name' => $request->candidate_name,
            'candidate_email' => $request->candidate_email,
            'candidate_phone' => $request->candidate_phone,
            'candidate_linkedin' => $request->candidate_linkedin,
            'department' => $request->department,
            'enrollment_no' => $request->enrollment_no,
            'graduation_year' => $request->graduation_year,
            'cpi' => $request->cpi,
            'portfolio_url' => $request->portfolio_url,
            'resume_path' => $resumePath,
            'cover_note' => $request->cover_note,
            'status' => 'pending',
        ]);

        // 6. Notify job poster if an alumni posted it
        if ($job->user && $job->user_id !== $user->id) {
            $job->user->notify(new PortalNotification(
                'New Alumni Referral Request',
                "{$user->name} requested a referral for '{$job->title}' at {$job->company}.",
                route('jobs.pipeline', ['job_id' => $job->id])
            ));

            // Automated WhatsApp alert to alumni job poster
            \App\Services\WhatsAppAlertService::dispatchNewReferralAlert($referral);
        }

        return back()->with('success', 'Your resume and referral request have been submitted to the alumni poster!');
    }

    /**
     * Pipeline dashboard for alumni posters & admins to review applicants.
     */
    public function pipeline(Request $request)
    {
        $user = Auth::user();

        // Only alumni, faculty, or admins can view the pipeline
        if (!in_array($user->role, ['admin', 'alumni', 'faculty'])) {
            abort(403, 'Unauthorized access to recruitment pipeline.');
        }

        // Determine accessible jobs
        $jobsQuery = JobPosting::query();
        if ($user->role !== 'admin') {
            $jobsQuery->where('user_id', $user->id);
        }
        $myJobs = $jobsQuery->latest()->get();
        $myJobIds = $myJobs->pluck('id')->toArray();

        // Query referrals for user's jobs (or all if admin)
        $query = JobReferral::with(['jobPosting.user', 'user.profile'])
            ->whereIn('job_posting_id', $myJobIds)
            ->latest();

        // Filter by specific job if requested
        $selectedJob = null;
        if ($request->filled('job_id')) {
            $query->where('job_posting_id', $request->job_id);
            $selectedJob = $myJobs->firstWhere('id', (int) $request->job_id);
        }

        // Filter by status if requested
        if ($request->filled('status') && in_array($request->status, ['pending', 'reviewed', 'referred', 'shortlisted', 'declined'])) {
            $query->where('status', $request->status);
        }

        // Search candidates by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('candidate_name', 'like', "%{$search}%")
                  ->orWhere('candidate_email', 'like', "%{$search}%");
            });
        }

        $referrals = $query->paginate(15)->withQueryString();

        // Calculate summary metrics
        $allReferralsForMetrics = JobReferral::whereIn('job_posting_id', $myJobIds)->get();
        $stats = [
            'total' => $allReferralsForMetrics->count(),
            'pending' => $allReferralsForMetrics->where('status', 'pending')->count(),
            'referred' => $allReferralsForMetrics->where('status', 'referred')->count(),
            'shortlisted' => $allReferralsForMetrics->where('status', 'shortlisted')->count(),
            'reviewed' => $allReferralsForMetrics->where('status', 'reviewed')->count(),
        ];

        return view('jobs.pipeline', compact('referrals', 'myJobs', 'selectedJob', 'stats'));
    }

    /**
     * View submitted referral requests for candidate students / alumni.
     */
    public function myApplications()
    {
        $user = Auth::user();

        $referrals = JobReferral::with(['jobPosting.user'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(12);

        return view('jobs.my_applications', compact('referrals'));
    }

    /**
     * Download a candidate's resume securely.
     */
    public function downloadResume(JobReferral $referral)
    {
        $user = Auth::user();

        // Authorization: Candidate, Job Poster, or Admin
        $isCandidate = $referral->user_id === $user->id;
        $isPoster = $referral->jobPosting && $referral->jobPosting->user_id === $user->id;
        $isAdmin = $user->role === 'admin';

        if (!$isCandidate && !$isPoster && !$isAdmin) {
            abort(403, 'Unauthorized access to resume document.');
        }

        if (!$referral->resume_path || !Storage::disk('public')->exists($referral->resume_path)) {
            return back()->with('error', 'The requested resume file was not found on storage.');
        }

        $extension = pathinfo($referral->resume_path, PATHINFO_EXTENSION) ?: 'pdf';
        $downloadName = Str::slug($referral->candidate_name) . '-resume-' . $referral->id . '.' . $extension;

        return Storage::disk('public')->download($referral->resume_path, $downloadName);
    }

    /**
     * Update referral status and mentor feedback notes.
     */
    public function updateStatus(Request $request, JobReferral $referral)
    {
        $user = Auth::user();

        // Authorization: Job Poster or Admin
        $isPoster = $referral->jobPosting && $referral->jobPosting->user_id === $user->id;
        $isAdmin = $user->role === 'admin';

        if (!$isPoster && !$isAdmin) {
            abort(403, 'Unauthorized to update this referral request.');
        }

        $request->validate([
            'status' => ['required', 'in:pending,reviewed,referred,shortlisted,declined'],
            'feedback' => ['nullable', 'string', 'max:2000'],
        ]);

        $referral->update([
            'status' => $request->status,
            'feedback' => $request->feedback,
        ]);

        // Notify the candidate of the status update & feedback
        if ($referral->user) {
            $statusLabel = $referral->status_label;
            $msg = "Your referral application for '{$referral->jobPosting->title}' has been updated to {$statusLabel}.";
            if ($request->filled('feedback')) {
                $msg .= " Note: \"{$request->feedback}\"";
            }

            $referral->user->notify(new PortalNotification(
                'Job Referral Status Updated',
                $msg,
                route('jobs.my-applications')
            ));

            // Automated WhatsApp status alert to candidate
            \App\Services\WhatsAppAlertService::dispatchReferralStatusUpdate($referral, $request->status, $request->feedback);
        }

        return back()->with('success', 'Candidate referral status updated successfully!');
    }
}
