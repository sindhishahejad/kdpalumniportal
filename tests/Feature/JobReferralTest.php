<?php

namespace Tests\Feature;

use App\Models\JobPosting;
use App\Models\JobReferral;
use App\Models\User;
use App\Notifications\PortalNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class JobReferralTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_submit_referral_request_with_resume_upload(): void
    {
        Storage::fake('public');
        Notification::fake();

        $poster = User::factory()->create(['role' => 'alumni', 'is_approved' => true]);
        $student = User::factory()->create(['role' => 'student', 'is_approved' => true]);

        $job = JobPosting::create([
            'user_id' => $poster->id,
            'title' => 'Embedded Systems Intern',
            'company' => 'Patan Automation Systems',
            'location' => 'GIDC Patan',
            'employment_type' => 'internship',
            'description' => 'IoT and microcontroller firmware developer.',
            'is_active' => true,
        ]);

        $resume = UploadedFile::fake()->create('jignesh_cv.pdf', 300, 'application/pdf');

        $response = $this->actingAs($student)->post("/jobs/{$job->id}/referral", [
            'candidate_name' => 'Jignesh Patel',
            'candidate_email' => 'jignesh@example.com',
            'candidate_phone' => '+91 9876543210',
            'candidate_linkedin' => 'https://linkedin.com/in/jignesh',
            'cover_note' => 'I have built Arduino and ESP32 projects in semester 5.',
            'resume' => $resume,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('job_referrals', [
            'job_posting_id' => $job->id,
            'user_id' => $student->id,
            'candidate_name' => 'Jignesh Patel',
            'status' => 'pending',
        ]);

        $referral = JobReferral::first();
        Storage::disk('public')->assertExists($referral->resume_path);

        Notification::assertSentTo(
            $poster,
            PortalNotification::class,
            function ($notification) use ($job) {
                return str_contains($notification->title, 'Alumni Referral Request')
                    && str_contains($notification->message, $job->title);
            }
        );
    }

    public function test_user_cannot_submit_duplicate_referral_for_same_job(): void
    {
        Storage::fake('public');

        $poster = User::factory()->create(['role' => 'alumni', 'is_approved' => true]);
        $student = User::factory()->create(['role' => 'student', 'is_approved' => true]);

        $job = JobPosting::create([
            'user_id' => $poster->id,
            'title' => 'Network Engineer',
            'company' => 'Gujarat Telecom',
            'employment_type' => 'full-time',
            'description' => 'Networking job.',
            'is_active' => true,
        ]);

        // First submission
        JobReferral::create([
            'job_posting_id' => $job->id,
            'user_id' => $student->id,
            'candidate_name' => $student->name,
            'candidate_email' => $student->email,
            'resume_path' => 'resumes/test.pdf',
            'status' => 'pending',
        ]);

        // Attempt second submission
        $resume = UploadedFile::fake()->create('duplicate_cv.pdf', 200, 'application/pdf');
        $response = $this->actingAs($student)->post("/jobs/{$job->id}/referral", [
            'candidate_name' => $student->name,
            'candidate_email' => $student->email,
            'resume' => $resume,
        ]);

        $response->assertSessionHas('error');
        $this->assertEquals(1, JobReferral::where('job_posting_id', $job->id)->where('user_id', $student->id)->count());
    }

    public function test_job_poster_cannot_refer_to_own_job(): void
    {
        Storage::fake('public');

        $poster = User::factory()->create(['role' => 'alumni', 'is_approved' => true]);

        $job = JobPosting::create([
            'user_id' => $poster->id,
            'title' => 'Self Job',
            'company' => 'Poster Co',
            'employment_type' => 'full-time',
            'description' => 'Description',
            'is_active' => true,
        ]);

        $resume = UploadedFile::fake()->create('cv.pdf', 200, 'application/pdf');
        $response = $this->actingAs($poster)->post("/jobs/{$job->id}/referral", [
            'candidate_name' => $poster->name,
            'candidate_email' => $poster->email,
            'resume' => $resume,
        ]);

        $response->assertSessionHas('error');
        $this->assertEquals(0, JobReferral::count());
    }

    public function test_job_poster_can_view_pipeline_and_filter_candidates(): void
    {
        $poster = User::factory()->create(['role' => 'alumni', 'is_approved' => true]);
        $student1 = User::factory()->create(['role' => 'student', 'is_approved' => true]);
        $student2 = User::factory()->create(['role' => 'student', 'is_approved' => true]);

        $job = JobPosting::create([
            'user_id' => $poster->id,
            'title' => 'Civil Draftsman',
            'company' => 'Patan Infra',
            'employment_type' => 'apprenticeship',
            'description' => 'CAD modeling.',
            'is_active' => true,
        ]);

        JobReferral::create([
            'job_posting_id' => $job->id,
            'user_id' => $student1->id,
            'candidate_name' => 'Aarav Shah',
            'candidate_email' => 'aarav@example.com',
            'resume_path' => 'resumes/aarav.pdf',
            'status' => 'pending',
        ]);

        JobReferral::create([
            'job_posting_id' => $job->id,
            'user_id' => $student2->id,
            'candidate_name' => 'Bhavin Patel',
            'candidate_email' => 'bhavin@example.com',
            'resume_path' => 'resumes/bhavin.pdf',
            'status' => 'referred',
        ]);

        // View all in pipeline
        $response = $this->actingAs($poster)->get('/jobs/pipeline');
        $response->assertStatus(200);
        $response->assertSee('Aarav Shah');
        $response->assertSee('Bhavin Patel');
        $response->assertSee('Alumni Referral Desk');

        // Filter by status=referred
        $filterResponse = $this->actingAs($poster)->get('/jobs/pipeline?status=referred');
        $filterResponse->assertStatus(200);
        $filterResponse->assertSee('Bhavin Patel');
        $filterResponse->assertDontSee('Aarav Shah');
    }

    public function test_candidate_poster_and_admin_can_download_resume(): void
    {
        Storage::fake('public');

        $poster = User::factory()->create(['role' => 'alumni', 'is_approved' => true]);
        $candidate = User::factory()->create(['role' => 'student', 'is_approved' => true]);
        $admin = User::factory()->create(['role' => 'admin', 'is_approved' => true]);
        $stranger = User::factory()->create(['role' => 'student', 'is_approved' => true]);

        $job = JobPosting::create([
            'user_id' => $poster->id,
            'title' => 'Software Trainee',
            'company' => 'Tech Corp',
            'employment_type' => 'full-time',
            'description' => 'Dev trainee',
            'is_active' => true,
        ]);

        // Put a fake file on storage
        $filePath = 'resumes/test_candidate_resume.pdf';
        Storage::disk('public')->put($filePath, 'PDF file dummy content');

        $referral = JobReferral::create([
            'job_posting_id' => $job->id,
            'user_id' => $candidate->id,
            'candidate_name' => $candidate->name,
            'candidate_email' => $candidate->email,
            'resume_path' => $filePath,
            'status' => 'pending',
        ]);

        // Candidate can download
        $responseCandidate = $this->actingAs($candidate)->get("/jobs/referrals/{$referral->id}/download-resume");
        $responseCandidate->assertStatus(200);

        // Poster can download
        $responsePoster = $this->actingAs($poster)->get("/jobs/referrals/{$referral->id}/download-resume");
        $responsePoster->assertStatus(200);

        // Admin can download
        $responseAdmin = $this->actingAs($admin)->get("/jobs/referrals/{$referral->id}/download-resume");
        $responseAdmin->assertStatus(200);

        // Stranger gets 403 forbidden
        $responseStranger = $this->actingAs($stranger)->get("/jobs/referrals/{$referral->id}/download-resume");
        $responseStranger->assertStatus(403);
    }

    public function test_job_poster_can_update_status_and_leave_feedback_which_notifies_candidate(): void
    {
        Notification::fake();

        $poster = User::factory()->create(['role' => 'alumni', 'is_approved' => true]);
        $candidate = User::factory()->create(['role' => 'student', 'is_approved' => true]);

        $job = JobPosting::create([
            'user_id' => $poster->id,
            'title' => 'Mechanical QA Engineer',
            'company' => 'Adani Port Logistics',
            'employment_type' => 'full-time',
            'description' => 'Quality inspection.',
            'is_active' => true,
        ]);

        $referral = JobReferral::create([
            'job_posting_id' => $job->id,
            'user_id' => $candidate->id,
            'candidate_name' => $candidate->name,
            'candidate_email' => $candidate->email,
            'resume_path' => 'resumes/mock.pdf',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($poster)->patch("/jobs/referrals/{$referral->id}", [
            'status' => 'referred',
            'feedback' => 'Directly forwarded your resume to our Senior QA Lead at Mundra port.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('job_referrals', [
            'id' => $referral->id,
            'status' => 'referred',
            'feedback' => 'Directly forwarded your resume to our Senior QA Lead at Mundra port.',
        ]);

        Notification::assertSentTo(
            $candidate,
            PortalNotification::class,
            function ($notification) {
                return str_contains($notification->title, 'Job Referral Status Updated')
                    && str_contains($notification->message, 'Referred to HR')
                    && str_contains($notification->message, 'Senior QA Lead');
            }
        );
    }

    public function test_candidate_can_view_my_applications_page(): void
    {
        $poster = User::factory()->create(['role' => 'alumni', 'is_approved' => true]);
        $candidate = User::factory()->create(['role' => 'student', 'is_approved' => true]);

        $job = JobPosting::create([
            'user_id' => $poster->id,
            'title' => 'Solar Technician',
            'company' => 'SunPower Gujarat',
            'employment_type' => 'apprenticeship',
            'description' => 'Solar rooftop installation.',
            'is_active' => true,
        ]);

        JobReferral::create([
            'job_posting_id' => $job->id,
            'user_id' => $candidate->id,
            'candidate_name' => $candidate->name,
            'candidate_email' => $candidate->email,
            'cover_note' => 'Graduating in 2026 with solar certification.',
            'resume_path' => 'resumes/candidate.pdf',
            'status' => 'reviewed',
            'feedback' => 'Looks great! Adding your profile to our upcoming GIDC hiring drive.',
        ]);

        $response = $this->actingAs($candidate)->get('/jobs/my-applications');
        $response->assertStatus(200);
        $response->assertSee('Solar Technician');
        $response->assertSee('SunPower Gujarat');
        $response->assertSee('Resume Reviewed');
        $response->assertSee('Adding your profile to our upcoming GIDC hiring drive');
    }
}
