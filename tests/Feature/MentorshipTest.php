<?php

namespace Tests\Feature;

use App\Models\MentorshipListing;
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
            'message' => 'I would love some guidance on D2D transition to GTU.',
        ]);

        $response->assertSessionHas('status');
        $response->assertRedirect();

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
}
