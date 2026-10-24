<?php

namespace Tests\Feature;

use App\Models\MentorshipListing;
use App\Models\MentorshipRequest;
use App\Models\User;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use App\Mail\MentorshipRequestMail;
use Tests\TestCase;

class MentorshipTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_mentorship_directory(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/mentorship');

        $response->assertStatus(200);
        $response->assertSee('Mentorship Program', false);
        $response->assertSee('Find a Mentor', false);
        $response->assertSee('My Mentorship Requests', false);
    }

    public function test_user_can_send_mentorship_request(): void
    {
        Mail::fake();

        $mentor = User::factory()->create(['name' => 'Prof. Sharma', 'email' => 'sharma@example.com']);
        $listing = MentorshipListing::create([
            'user_id' => $mentor->id,
            'title' => 'Senior Engineering Mentor',
            'expertise_areas' => 'D2D, Web Development',
            'description' => 'Available for diploma students.',
            'is_available' => true,
        ]);

        $student = User::factory()->create(['name' => 'John Doe']);

        $response = $this->actingAs($student)->post("/mentorship/{$listing->id}/request", [
            'topic' => 'D2D Degree Transition Guidance',
            'message' => 'I would love some guidance on D2D transition to GTU.',
        ]);

        $response->assertSessionHas('status');
        $response->assertRedirect();

        // Assert formal MentorshipRequest record was created in database
        $this->assertDatabaseHas('mentorship_requests', [
            'mentorship_listing_id' => $listing->id,
            'mentor_id' => $mentor->id,
            'mentee_id' => $student->id,
            'topic' => 'D2D Degree Transition Guidance',
            'status' => 'pending',
        ]);

        // Assert email was queued
        Mail::assertQueued(MentorshipRequestMail::class);

        // Assert direct message was created
        $this->assertDatabaseHas('messages', [
            'sender_id' => $student->id,
            'recipient_id' => $mentor->id,
        ]);
    }

    public function test_user_cannot_send_mentorship_request_to_self(): void
    {
        $mentor = User::factory()->create();
        $listing = MentorshipListing::create([
            'user_id' => $mentor->id,
            'title' => 'Mentor',
            'expertise_areas' => 'Testing',
            'description' => 'Test description',
            'is_available' => true,
        ]);

        $response = $this->actingAs($mentor)->post("/mentorship/{$listing->id}/request", [
            'message' => 'Test message to myself',
        ]);

        $response->assertSessionHas('error', 'You cannot send a mentorship request to yourself.');
    }

    public function test_mentor_can_accept_and_schedule_request(): void
    {
        $mentor = User::factory()->create(['name' => 'Alum Mentor']);
        $student = User::factory()->create(['name' => 'Junior Mentee']);

        $listing = MentorshipListing::create([
            'user_id' => $mentor->id,
            'title' => 'Software Engineer',
            'expertise_areas' => 'Laravel, Cloud',
            'description' => 'Guiding diploma students.',
            'is_available' => true,
        ]);

        $mentorshipRequest = MentorshipRequest::create([
            'mentorship_listing_id' => $listing->id,
            'mentor_id' => $mentor->id,
            'mentee_id' => $student->id,
            'topic' => 'Interview Prep',
            'message' => 'Need help preparing for interview.',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($mentor)->patch(route('mentorship.requests.accept', $mentorshipRequest), [
            'session_time' => 'Saturday, Nov 10 at 4:00 PM',
            'meeting_link' => 'https://meet.google.com/abc-defg-hij',
            'mentor_notes' => 'Please bring your resume.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('mentorship_requests', [
            'id' => $mentorshipRequest->id,
            'status' => 'accepted',
            'session_time' => 'Saturday, Nov 10 at 4:00 PM',
            'meeting_link' => 'https://meet.google.com/abc-defg-hij',
        ]);

        // Assert message notification delivered to mentee
        $this->assertDatabaseHas('messages', [
            'sender_id' => $mentor->id,
            'recipient_id' => $student->id,
        ]);
    }

    public function test_mentor_or_mentee_can_complete_session(): void
    {
        $mentor = User::factory()->create();
        $student = User::factory()->create();

        $listing = MentorshipListing::create([
            'user_id' => $mentor->id,
            'title' => 'CAD Designer',
            'expertise_areas' => 'SolidWorks, AutoCAD',
            'description' => 'Mechanical guidance.',
            'is_available' => true,
        ]);

        $mentorshipRequest = MentorshipRequest::create([
            'mentorship_listing_id' => $listing->id,
            'mentor_id' => $mentor->id,
            'mentee_id' => $student->id,
            'message' => 'CAD review session.',
            'status' => 'accepted',
        ]);

        $response = $this->actingAs($student)->patch(route('mentorship.requests.complete', $mentorshipRequest));
        $response->assertRedirect();
        $this->assertDatabaseHas('mentorship_requests', [
            'id' => $mentorshipRequest->id,
            'status' => 'completed',
        ]);
    }

    public function test_mentor_can_decline_request(): void
    {
        $mentor = User::factory()->create();
        $student = User::factory()->create();

        $listing = MentorshipListing::create([
            'user_id' => $mentor->id,
            'title' => 'Electrical Engineer',
            'expertise_areas' => 'Circuit Design',
            'description' => 'Guidance.',
            'is_available' => true,
        ]);

        $mentorshipRequest = MentorshipRequest::create([
            'mentorship_listing_id' => $listing->id,
            'mentor_id' => $mentor->id,
            'mentee_id' => $student->id,
            'message' => 'Help with circuits.',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($mentor)->patch(route('mentorship.requests.decline', $mentorshipRequest), [
            'mentor_notes' => 'Currently busy with project launch.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('mentorship_requests', [
            'id' => $mentorshipRequest->id,
            'status' => 'declined',
            'mentor_notes' => 'Currently busy with project launch.',
        ]);
    }

    public function test_mentee_can_cancel_pending_request(): void
    {
        $mentor = User::factory()->create();
        $student = User::factory()->create();

        $listing = MentorshipListing::create([
            'user_id' => $mentor->id,
            'title' => 'Civil Engineer',
            'expertise_areas' => 'Surveying',
            'description' => 'Guidance.',
            'is_available' => true,
        ]);

        $mentorshipRequest = MentorshipRequest::create([
            'mentorship_listing_id' => $listing->id,
            'mentor_id' => $mentor->id,
            'mentee_id' => $student->id,
            'message' => 'Guidance on surveying.',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($student)->delete(route('mentorship.requests.cancel', $mentorshipRequest));
        $response->assertRedirect();
        $this->assertDatabaseMissing('mentorship_requests', ['id' => $mentorshipRequest->id]);
    }

    public function test_unauthorized_user_cannot_accept_another_mentors_request(): void
    {
        $mentor = User::factory()->create();
        $stranger = User::factory()->create();
        $student = User::factory()->create();

        $listing = MentorshipListing::create([
            'user_id' => $mentor->id,
            'title' => 'Mentor',
            'expertise_areas' => 'General',
            'description' => 'Guidance.',
            'is_available' => true,
        ]);

        $mentorshipRequest = MentorshipRequest::create([
            'mentorship_listing_id' => $listing->id,
            'mentor_id' => $mentor->id,
            'mentee_id' => $student->id,
            'message' => 'Help needed.',
            'status' => 'pending',
        ]);

        $this->actingAs($stranger)
             ->patch(route('mentorship.requests.accept', $mentorshipRequest))
             ->assertStatus(403);
    }
}
