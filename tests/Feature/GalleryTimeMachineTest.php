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
}
