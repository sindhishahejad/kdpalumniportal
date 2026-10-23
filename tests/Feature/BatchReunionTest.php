<?php

namespace Tests\Feature;

use App\Models\BatchReunion;
use App\Models\ReunionMemory;
use App\Models\ReunionRsvp;
use App\Models\User;
use Database\Seeders\BatchReunionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BatchReunionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        (new BatchReunionSeeder())->run();
    }

    public function test_user_can_view_reunions_hub(): void
    {
        $user = User::factory()->create(['is_approved' => true]);

        $response = $this->actingAs($user)->get('/reunions');

        $response->assertStatus(200);
        $response->assertSee('Batch Reunion Planner');
        $response->assertSee('Homecomings');
        $response->assertSee('Class of 2016 Decennial Reunion');
        $response->assertSee('Batch of 2021 Post-Diploma 5-Year Homecoming');
    }

    public function test_user_can_filter_reunions_by_batch_year(): void
    {
        $user = User::factory()->create(['is_approved' => true]);

        $response = $this->actingAs($user)->get('/reunions?batch_year=2016');

        $response->assertStatus(200);
        $response->assertSee('Class of 2016 Decennial Reunion');
        $response->assertDontSee('Batch of 2021 Post-Diploma 5-Year Homecoming');
    }

    public function test_alumni_can_plan_a_new_batch_reunion(): void
    {
        $alumni = User::factory()->create(['role' => 'alumni', 'is_approved' => true]);

        $data = [
            'title' => 'Class of 2018 Mechanical 8-Year Reunion',
            'batch_year' => '2018',
            'department' => 'Mechanical Engineering',
            'event_date' => now()->addDays(30)->format('Y-m-d H:i:s'),
            'venue' => 'KDP Campus Lawn',
            'city' => 'Patan',
            'description' => 'Reconnecting our diploma mechanical batchmates for campus lunch.',
            'milestone_tag' => '8-Year Milestone',
            'fee_per_person' => 400,
        ];

        $response = $this->actingAs($alumni)->post('/reunions', $data);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('batch_reunions', [
            'title' => 'Class of 2018 Mechanical 8-Year Reunion',
            'batch_year' => '2018',
            'user_id' => $alumni->id,
        ]);

        $reunion = BatchReunion::where('title', 'Class of 2018 Mechanical 8-Year Reunion')->first();

        // Organizer should be auto-RSVP'd
        $this->assertDatabaseHas('reunion_rsvps', [
            'batch_reunion_id' => $reunion->id,
            'user_id' => $alumni->id,
            'status' => 'attending',
        ]);
    }

    public function test_student_cannot_plan_reunion(): void
    {
        $student = User::factory()->create(['role' => 'student', 'is_approved' => true]);

        $response = $this->actingAs($student)->post('/reunions', [
            'title' => 'Unauthorized Student Reunion',
            'batch_year' => '2026',
            'event_date' => now()->addDays(15)->format('Y-m-d H:i:s'),
            'venue' => 'Canteen',
            'city' => 'Patan',
            'description' => 'Test',
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('batch_reunions', [
            'title' => 'Unauthorized Student Reunion',
        ]);
    }

    public function test_user_can_rsvp_to_reunion_with_guests_and_carpool(): void
    {
        $user = User::factory()->create(['role' => 'alumni', 'is_approved' => true]);
        $reunion = BatchReunion::first();

        $response = $this->actingAs($user)->post("/reunions/{$reunion->slug}/rsvp", [
            'status' => 'attending',
            'guests_count' => 2,
            'carpool_note' => 'Driving from Ahmedabad via SG Highway, have 2 open seats.',
            'nostalgic_quote' => 'Excited to meet the old gang after 8 years!',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('reunion_rsvps', [
            'batch_reunion_id' => $reunion->id,
            'user_id' => $user->id,
            'status' => 'attending',
            'guests_count' => 2,
            'carpool_note' => 'Driving from Ahmedabad via SG Highway, have 2 open seats.',
        ]);
    }

    public function test_user_can_post_throwback_memory_with_photo(): void
    {
        Storage::fake('public');

        $user = User::factory()->create(['role' => 'alumni', 'is_approved' => true]);
        $reunion = BatchReunion::first();

        $photo = UploadedFile::fake()->image('campus_throwback.jpg', 600, 400);

        $response = $this->actingAs($user)->post("/reunions/{$reunion->slug}/memories", [
            'caption' => 'Semester 4 surveying practicals under the banyan tree!',
            'year_taken' => '2016',
            'photo' => $photo,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('reunion_memories', [
            'batch_reunion_id' => $reunion->id,
            'user_id' => $user->id,
            'caption' => 'Semester 4 surveying practicals under the banyan tree!',
            'year_taken' => '2016',
        ]);

        $memory = ReunionMemory::where('caption', 'Semester 4 surveying practicals under the banyan tree!')->first();
        Storage::disk('public')->assertExists($memory->photo_path);
    }

    public function test_user_can_like_a_memory(): void
    {
        $user = User::factory()->create(['role' => 'alumni', 'is_approved' => true]);
        $reunion = BatchReunion::first();
        $memory = $reunion->memories()->first();

        $initialLikes = $memory->likes_count;

        $response = $this->actingAs($user)->post("/reunions/{$reunion->slug}/memories/{$memory->id}/like");

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals($initialLikes + 1, $memory->fresh()->likes_count);
    }
}
