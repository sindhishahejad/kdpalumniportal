<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\JobPosting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobPostingTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_job_board_and_filter_apprenticeships(): void
    {
        $user = User::factory()->create();

        JobPosting::create([
            'user_id' => $user->id,
            'title' => 'GIDC Mechanical Trainee',
            'company' => 'Patan Engineering Works',
            'location' => 'GIDC Patan',
            'employment_type' => 'apprenticeship',
            'description' => '6-month industrial diploma training.',
            'is_active' => true,
        ]);

        JobPosting::create([
            'user_id' => $user->id,
            'title' => 'Senior Laravel Developer',
            'company' => 'Tech Solutions',
            'location' => 'Ahmedabad',
            'employment_type' => 'full-time',
            'description' => 'Full-time web development position.',
            'is_active' => true,
        ]);

        // Access all jobs
        $response = $this->actingAs($user)->get('/jobs');
        $response->assertStatus(200);
        $response->assertSee('GIDC Mechanical Trainee');
        $response->assertSee('Senior Laravel Developer');

        // Filter by apprenticeship (GIDC Connect)
        $filterResponse = $this->actingAs($user)->get('/jobs?type=apprenticeship');
        $filterResponse->assertStatus(200);
        $filterResponse->assertSee('GIDC Mechanical Trainee');
        $filterResponse->assertDontSee('Senior Laravel Developer');
    }

    public function test_alumni_can_post_opportunity(): void
    {
        $alumni = User::factory()->create(['role' => 'alumni', 'is_approved' => true]);

        $response = $this->actingAs($alumni)->post('/jobs', [
            'title' => 'Junior Network Engineer',
            'company' => 'Cisco Systems Partner',
            'location' => 'GIDC Patan',
            'employment_type' => 'apprenticeship',
            'description' => 'Apprenticeship opportunity for diploma computer students.',
            'application_link_or_email' => 'hr@networkpartner.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('job_postings', [
            'title' => 'Junior Network Engineer',
            'company' => 'Cisco Systems Partner',
            'user_id' => $alumni->id,
            'is_active' => 1,
        ]);
    }

    public function test_student_cannot_post_opportunity(): void
    {
        $student = User::factory()->create(['role' => 'student', 'is_approved' => true]);

        $response = $this->actingAs($student)->post('/jobs', [
            'title' => 'Unauthorized Job',
            'company' => 'Student Co',
            'employment_type' => 'full-time',
            'description' => 'Test description',
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('job_postings', [
            'title' => 'Unauthorized Job',
        ]);
    }

    public function test_job_poster_or_admin_can_delete_job(): void
    {
        $alumni = User::factory()->create(['role' => 'alumni', 'is_approved' => true]);
        $job = JobPosting::create([
            'user_id' => $alumni->id,
            'title' => 'Temporary Post',
            'company' => 'Temp Co',
            'employment_type' => 'internship',
            'description' => 'To be deleted',
            'is_active' => true,
        ]);

        $otherAlumni = User::factory()->create(['role' => 'alumni', 'is_approved' => true]);

        // Unauthorized user cannot delete
        $unauthResponse = $this->actingAs($otherAlumni)->delete("/jobs/{$job->id}");
        $unauthResponse->assertSessionHas('error');
        $this->assertDatabaseHas('job_postings', ['id' => $job->id]);

        // Author can delete
        $deleteResponse = $this->actingAs($alumni)->delete("/jobs/{$job->id}");
        $deleteResponse->assertSessionHas('success');
        $this->assertDatabaseMissing('job_postings', ['id' => $job->id]);
    }
}
