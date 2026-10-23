<?php

namespace Tests\Feature;

use App\Models\CampusMemorySpot;
use App\Models\CampusMemoryStory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GalleryTimeMachineTest extends TestCase
{
    use RefreshDatabase;

    public function test_gallery_page_renders_time_machine_and_campus_spots(): void
    {
        $spot = CampusMemorySpot::create([
            'name' => 'Main Heritage Admin Porch',
            'slug' => 'main-heritage-porch',
            'category' => 'landmark',
            'description' => 'Original colonnade of K. D. Polytechnic, Patan.',
            'x_percent' => 50.0,
            'y_percent' => 20.0,
            'icon' => 'building',
            'then_photo_path' => 'https://example.com/then.jpg',
            'then_year' => '1985',
            'then_title' => 'Heritage Porch',
            'now_photo_path' => 'https://example.com/now.jpg',
            'now_year' => '2026',
            'now_title' => 'Modern Solar Porch',
        ]);

        $response = $this->get(route('gallery.index'));

        $response->assertStatus(200);
        $response->assertSee('Campus Memory Time-Machine &amp; Gallery', false);
        $response->assertSee('Main Heritage Admin Porch', false);
        $response->assertSee('1985', false);
        $response->assertSee('2026', false);
    }

    public function test_authenticated_user_can_pin_throwback_story(): void
    {
        $user = User::factory()->create(['name' => 'Ketan Patel', 'is_approved' => true]);

        $spot = CampusMemorySpot::create([
            'name' => 'Central Mechanical Workshop',
            'slug' => 'central-mechanical-workshop',
            'category' => 'workshop',
            'description' => 'Manual lathe turning and foundry.',
            'then_photo_path' => 'https://example.com/workshop-then.jpg',
            'now_photo_path' => 'https://example.com/workshop-now.jpg',
        ]);

        $response = $this->actingAs($user)->post(route('gallery.stories.store'), [
            'campus_memory_spot_id' => $spot->id,
            'author_name' => 'Ketan Patel',
            'batch_year' => 2005,
            'department' => 'Mechanical Engineering',
            'story' => 'Making that mild steel job on lathe machine #4 was our best memory.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('campus_memory_stories', [
            'campus_memory_spot_id' => $spot->id,
            'author_name' => 'Ketan Patel',
            'batch_year' => 2005,
            'department' => 'Mechanical Engineering',
        ]);
    }

    public function test_user_can_like_campus_memory_story(): void
    {
        $spot = CampusMemorySpot::create([
            'name' => 'Central Library',
            'slug' => 'central-library',
            'category' => 'academic',
            'description' => 'Vintage technical encyclopedias.',
            'then_photo_path' => 'https://example.com/lib-then.jpg',
            'now_photo_path' => 'https://example.com/lib-now.jpg',
        ]);

        $story = CampusMemoryStory::create([
            'campus_memory_spot_id' => $spot->id,
            'author_name' => 'Aakash Trivedi',
            'batch_year' => 2018,
            'department' => 'Computer Engineering',
            'story' => 'Quiet study sessions before GTU exam.',
            'likes_count' => 5,
        ]);

        $response = $this->post(route('gallery.stories.like', $story->id), [], ['Accept' => 'application/json']);

        $response->assertStatus(200);
        $response->assertJson(['success' => true, 'likes_count' => 6]);

        $this->assertEquals(6, $story->fresh()->likes_count);
    }

    public function test_non_admin_cannot_access_spots_management(): void
    {
        $student = User::factory()->create(['role' => 'student', 'is_approved' => true]);

        $response = $this->actingAs($student)->get(route('admin.spots.index'));
        $response->assertStatus(403);
    }

    public function test_admin_can_view_and_create_landmark_spot(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_approved' => true]);

        $response = $this->actingAs($admin)->get(route('admin.spots.index'));
        $response->assertStatus(200);
        $response->assertSee('Landmark Spots &amp; Time-Machine Management', false);

        $createResponse = $this->actingAs($admin)->post(route('admin.spots.store'), [
            'name' => 'Automobile Engineering Engine Lab',
            'category' => 'workshop',
            'description' => 'Four-stroke diesel engines and dynamometer test rigs.',
            'x_percent' => 38.5,
            'y_percent' => 62.0,
            'icon' => 'wrench',
            'then_year' => '1994',
            'then_title' => 'Carburetor Test Stands (1994)',
            'then_photo_url' => 'https://example.com/auto-then.jpg',
            'now_year' => '2026',
            'now_title' => 'EV Diagnostic Station (2026)',
            'now_photo_url' => 'https://example.com/auto-now.jpg',
            'fun_fact' => 'Revving the single-cylinder diesel engine shook the entire workshop floor!',
            'order_num' => 7,
        ]);

        $createResponse->assertRedirect();
        $createResponse->assertSessionHas('status');

        $this->assertDatabaseHas('campus_memory_spots', [
            'name' => 'Automobile Engineering Engine Lab',
            'then_year' => '1994',
            'now_year' => '2026',
        ]);
    }

    public function test_admin_can_update_and_delete_landmark_spot(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_approved' => true]);

        $spot = CampusMemorySpot::create([
            'name' => 'Old Gymnasium Hall',
            'slug' => 'old-gymnasium-hall',
            'category' => 'recreation',
            'description' => 'Indoor badminton and carrom hall.',
            'x_percent' => 45.0,
            'y_percent' => 60.0,
            'icon' => 'trophy',
            'then_photo_path' => 'https://example.com/gym-then.jpg',
            'then_year' => '1990',
            'then_title' => 'Wooden Floors (1990)',
            'now_photo_path' => 'https://example.com/gym-now.jpg',
            'now_year' => '2026',
            'now_title' => 'Fitness Complex (2026)',
        ]);

        // Update
        $updateResponse = $this->actingAs($admin)->put(route('admin.spots.update', $spot->id), [
            'name' => 'Renovated Student Fitness Hub',
            'category' => 'recreation',
            'description' => 'Modern cardiovascular training center.',
            'x_percent' => 48.0,
            'y_percent' => 62.0,
            'icon' => 'trophy',
            'then_year' => '1990',
            'then_title' => 'Wooden Badminton Court (1990)',
            'now_year' => '2026',
            'now_title' => 'Air-conditioned Fitness Hub (2026)',
        ]);

        $updateResponse->assertRedirect();
        $this->assertDatabaseHas('campus_memory_spots', [
            'id' => $spot->id,
            'name' => 'Renovated Student Fitness Hub',
        ]);

        // Delete
        $deleteResponse = $this->actingAs($admin)->delete(route('admin.spots.destroy', $spot->id));
        $deleteResponse->assertRedirect();
        $this->assertDatabaseMissing('campus_memory_spots', [
            'id' => $spot->id,
        ]);
    }

    public function test_admin_dashboard_renders_unified_gallery_and_campus_time_machine_hub(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_approved' => true]);

        CampusMemorySpot::create([
            'name' => 'Main Heritage Admin Porch',
            'slug' => 'main-heritage-porch-admin-test',
            'category' => 'landmark',
            'description' => 'Original colonnade of K. D. Polytechnic, Patan.',
            'x_percent' => 50.0,
            'y_percent' => 20.0,
            'icon' => 'building',
            'then_photo_path' => 'https://example.com/then.jpg',
            'then_year' => '1985',
            'then_title' => 'Heritage Porch',
            'now_photo_path' => 'https://example.com/now.jpg',
            'now_year' => '2026',
            'now_title' => 'Modern Solar Porch',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Manage Gallery &amp; Campus Time-Machine', false);
        $response->assertSee('Campus Time-Machine Spots', false);
        $response->assertSee('Main Heritage Admin Porch', false);
        $response->assertSee('Event Photo Albums', false);
        $response->assertSee('Pinned Throwback Memories', false);

        // Also verify /dashboard route
        $mainDashboardResponse = $this->actingAs($admin)->get('/dashboard');
        $mainDashboardResponse->assertStatus(200);
        $mainDashboardResponse->assertSee('Manage Gallery &amp; Campus Time-Machine', false);
    }
}
