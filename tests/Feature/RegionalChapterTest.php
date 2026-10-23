<?php

namespace Tests\Feature;

use App\Models\RegionalChapter;
use App\Models\User;
use Database\Seeders\RegionalChapterSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegionalChapterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        (new RegionalChapterSeeder())->run();
    }

    public function test_user_can_view_chapters_and_map_hub(): void
    {
        $user = User::factory()->create(['is_approved' => true]);

        $response = $this->actingAs($user)->get('/chapters');

        $response->assertStatus(200);
        $response->assertSee('Alumni Distribution');
        $response->assertSee('Regional Chapters');
        $response->assertSee('Patan & North Gujarat Chapter');
        $response->assertSee('Ahmedabad & GIFT City Chapter');
        $response->assertSee('alumniMap');
    }

    public function test_user_can_filter_chapters_by_region_type(): void
    {
        $user = User::factory()->create(['is_approved' => true]);

        // Filter by Gujarat
        $responseGujarat = $this->actingAs($user)->get('/chapters?type=gujarat');
        $responseGujarat->assertStatus(200);
        $responseGujarat->assertSee('Patan & North Gujarat Chapter');
        $responseGujarat->assertDontSee('Bengaluru Tech Chapter');

        // Filter by National
        $responseNational = $this->actingAs($user)->get('/chapters?type=national');
        $responseNational->assertStatus(200);
        $responseNational->assertSee('Bengaluru Tech Chapter');
        $responseNational->assertDontSee('Patan & North Gujarat Chapter');
    }

    public function test_user_can_join_and_leave_chapter(): void
    {
        $user = User::factory()->create(['role' => 'alumni', 'is_approved' => true]);
        $chapter = RegionalChapter::where('slug', 'patan-north-gujarat')->first();

        // 1. Join chapter
        $joinResponse = $this->actingAs($user)->post("/chapters/{$chapter->slug}/join");
        $joinResponse->assertRedirect();
        $joinResponse->assertSessionHas('success');

        $this->assertDatabaseHas('chapter_memberships', [
            'regional_chapter_id' => $chapter->id,
            'user_id' => $user->id,
        ]);

        // 2. Leave chapter (toggle)
        $leaveResponse = $this->actingAs($user)->post("/chapters/{$chapter->slug}/join");
        $leaveResponse->assertRedirect();
        $leaveResponse->assertSessionHas('success');

        $this->assertDatabaseMissing('chapter_memberships', [
            'regional_chapter_id' => $chapter->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_user_can_view_chapter_details(): void
    {
        $user = User::factory()->create(['is_approved' => true]);
        $chapter = RegionalChapter::where('slug', 'ahmedabad-gift-city')->first();

        $response = $this->actingAs($user)->get("/chapters/{$chapter->slug}");

        $response->assertStatus(200);
        $response->assertSee('Ahmedabad & GIFT City Chapter');
        $response->assertSee($chapter->meeting_venue);
        $response->assertSee('Chapter Members');
    }

    public function test_chapter_member_can_schedule_activity(): void
    {
        $user = User::factory()->create(['role' => 'alumni', 'is_approved' => true]);
        $chapter = RegionalChapter::where('slug', 'patan-north-gujarat')->first();

        // Join chapter first
        $chapter->members()->attach($user->id);

        $activityData = [
            'title' => 'Sunday GIDC Patan Industrial Coffee Catchup',
            'event_date' => now()->addDays(5)->format('Y-m-d H:i:s'),
            'venue' => 'GIDC Patan Association Hall',
            'description' => 'Discussion on local manufacturing apprenticeships.',
        ];

        $response = $this->actingAs($user)->post("/chapters/{$chapter->slug}/activities", $activityData);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('chapter_activities', [
            'regional_chapter_id' => $chapter->id,
            'user_id' => $user->id,
            'title' => 'Sunday GIDC Patan Industrial Coffee Catchup',
        ]);
    }

    public function test_non_member_cannot_schedule_activity(): void
    {
        $user = User::factory()->create(['role' => 'alumni', 'is_approved' => true]);
        $chapter = RegionalChapter::where('slug', 'patan-north-gujarat')->first();

        $response = $this->actingAs($user)->post("/chapters/{$chapter->slug}/activities", [
            'title' => 'Unauthorized Meetup',
            'event_date' => now()->addDays(2)->format('Y-m-d H:i:s'),
            'venue' => 'Unknown Venue',
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('chapter_activities', [
            'title' => 'Unauthorized Meetup',
        ]);
    }

    public function test_admin_can_schedule_activity_even_if_not_member(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_approved' => true]);
        $chapter = RegionalChapter::where('slug', 'vadodara-industrial')->first();

        $response = $this->actingAs($admin)->post("/chapters/{$chapter->slug}/activities", [
            'title' => 'Official KDP Vadodara Chapter Assembly',
            'event_date' => now()->addDays(10)->format('Y-m-d H:i:s'),
            'venue' => 'Makarpura GIDC Center',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('chapter_activities', [
            'regional_chapter_id' => $chapter->id,
            'title' => 'Official KDP Vadodara Chapter Assembly',
        ]);
    }

    public function test_interactive_map_is_displayed_on_main_dashboard(): void
    {
        $alumni = User::factory()->create(['role' => 'alumni', 'is_approved' => true]);
        $responseAlumni = $this->actingAs($alumni)->get('/dashboard');
        $responseAlumni->assertStatus(200);
        $responseAlumni->assertSee('Interactive Regional Chapter Map');
        $responseAlumni->assertSee('dashboardAlumniMap');
        $responseAlumni->assertSee('Alumni Presence by Industrial Corridor');

        $student = User::factory()->create(['role' => 'student', 'is_approved' => true]);
        $responseStudent = $this->actingAs($student)->get('/dashboard');
        $responseStudent->assertStatus(200);
        $responseStudent->assertSee('Interactive Regional Chapter Map');
        $responseStudent->assertSee('studentDashboardMap');
    }
}
