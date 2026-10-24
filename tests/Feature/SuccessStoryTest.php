<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\SuccessStory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SuccessStoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_success_stories_page_renders_successfully(): void
    {
        SuccessStory::create([
            'title' => 'Leading Infrastructure at L&T Construction',
            'alumni_name' => 'Ketan Dave',
            'batch_year' => '2018',
            'department' => 'Civil Engineering',
            'story' => 'Overseeing highway bridges and metro infrastructure across Western India.',
            'is_featured' => false,
        ]);

        $response = $this->get(route('stories.index'));

        $response->assertStatus(200);
        $response->assertSee('Alumni Success Stories &amp; Global Impact', false);
        $response->assertSee('Leading Infrastructure at L&amp;T Construction', false);
        $response->assertSee('Ketan Dave', false);
        $response->assertSee('Civil Engineering', false);
    }

    public function test_public_success_stories_can_filter_by_department(): void
    {
        SuccessStory::create([
            'title' => 'Silicon Valley AI Engineer',
            'alumni_name' => 'Mehul Panchal',
            'batch_year' => '2019',
            'department' => 'Computer Engineering',
            'story' => 'Developing LLM inference optimizers.',
        ]);

        SuccessStory::create([
            'title' => 'Substation Design Lead at GETCO',
            'alumni_name' => 'Dharmesh Prajapati',
            'batch_year' => '2016',
            'department' => 'Electrical Engineering',
            'story' => 'Spearheading transmission line expansions across North Gujarat.',
        ]);

        // Filter by Computer
        $response = $this->get(route('stories.index', ['department' => 'Computer']));
        $response->assertStatus(200);
        $response->assertSee('Silicon Valley AI Engineer', false);
        $response->assertDontSee('Substation Design Lead at GETCO', false);

        // Filter by Electrical
        $response = $this->get(route('stories.index', ['department' => 'Electrical']));
        $response->assertStatus(200);
        $response->assertSee('Substation Design Lead at GETCO', false);
        $response->assertDontSee('Silicon Valley AI Engineer', false);
    }

    public function test_public_success_stories_can_search_by_keyword(): void
    {
        SuccessStory::create([
            'title' => 'Robotics Automation Pioneer at ABB',
            'alumni_name' => 'Jignesh Soni',
            'batch_year' => '2015',
            'department' => 'Mechanical Engineering',
            'story' => 'Designed modular SCARA arms for automotive assembly lines.',
        ]);

        SuccessStory::create([
            'title' => 'Renewable Solar Farm Grid Architect',
            'alumni_name' => 'Alkesh Vaghela',
            'batch_year' => '2017',
            'department' => 'Electrical Engineering',
            'story' => 'Installed 50MW solar substation arrays in Charanka.',
        ]);

        $response = $this->get(route('stories.index', ['search' => 'Robotics']));
        $response->assertStatus(200);
        $response->assertSee('Robotics Automation Pioneer at ABB', false);
        $response->assertDontSee('Renewable Solar Farm Grid Architect', false);

        // Search by author name
        $responseName = $this->get(route('stories.index', ['search' => 'Alkesh']));
        $responseName->assertStatus(200);
        $responseName->assertSee('Renewable Solar Farm Grid Architect', false);
        $responseName->assertDontSee('Robotics Automation Pioneer at ABB', false);
    }

    public function test_featured_story_is_displayed_in_spotlight(): void
    {
        $featured = SuccessStory::create([
            'title' => 'Founder of TechSprint Innovations',
            'alumni_name' => 'Pooja Trivedi',
            'batch_year' => '2020',
            'department' => 'Computer Engineering',
            'story' => 'Empowering 50,000 diploma learners with open-source engineering lab simulations.',
            'is_featured' => true,
        ]);

        $response = $this->get(route('stories.index'));
        $response->assertStatus(200);
        $response->assertSee('Featured Spotlight', false);
        $response->assertSee('Founder of TechSprint Innovations', false);
        $response->assertSee('Pooja Trivedi', false);
    }

    public function test_admin_can_access_success_stories_management_panel(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_approved' => true]);

        $response = $this->actingAs($admin)->get(route('admin.stories.index'));
        $response->assertStatus(200);
        $response->assertSee('Success Stories Management', false);
        $response->assertSee('Publish Success Story', false);
    }

    public function test_non_admin_cannot_access_success_stories_management_panel(): void
    {
        $alumni = User::factory()->create(['role' => 'alumni', 'is_approved' => true]);
        $student = User::factory()->create(['role' => 'student', 'is_approved' => true]);

        $this->actingAs($alumni)->get(route('admin.stories.index'))->assertStatus(403);
        $this->actingAs($student)->get(route('admin.stories.index'))->assertStatus(403);
        $this->get(route('admin.stories.index'))->assertStatus(403);
    }

    public function test_admin_can_create_success_story_with_image(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create(['role' => 'admin', 'is_approved' => true]);
        $image = UploadedFile::fake()->image('alumni_headshot.jpg', 600, 600);

        $payload = [
            'title' => 'Chief Technology Officer at GujInfo Tech',
            'alumni_name' => 'Pranav Patel',
            'batch_year' => '2014',
            'department' => 'Computer Engineering',
            'story' => 'Leading state digital governance systems and cloud scalability architectures.',
            'is_featured' => '1',
            'image' => $image,
        ];

        $response = $this->actingAs($admin)->post(route('admin.stories.store'), $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('success_stories', [
            'title' => 'Chief Technology Officer at GujInfo Tech',
            'alumni_name' => 'Pranav Patel',
            'batch_year' => '2014',
            'department' => 'Computer Engineering',
            'is_featured' => true,
        ]);

        $story = SuccessStory::where('alumni_name', 'Pranav Patel')->first();
        $this->assertNotNull($story->image_path);
        Storage::disk('public')->assertExists($story->image_path);
    }

    public function test_admin_can_update_success_story(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_approved' => true]);

        $story = SuccessStory::create([
            'title' => 'Junior Field Engineer',
            'alumni_name' => 'Nirav Shah',
            'batch_year' => '2022',
            'department' => 'Mechanical Engineering',
            'story' => 'Starting out in hydraulic pump maintenance.',
            'is_featured' => false,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.stories.update', $story), [
            'title' => 'Senior Operations Manager at Tata Motors',
            'alumni_name' => 'Nirav Shah',
            'batch_year' => '2022',
            'department' => 'Mechanical Engineering',
            'story' => 'Promoted to lead automotive EV assembly operations.',
            'is_featured' => '1',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $story->refresh();
        $this->assertEquals('Senior Operations Manager at Tata Motors', $story->title);
        $this->assertEquals('Promoted to lead automotive EV assembly operations.', $story->story);
        $this->assertTrue((bool)$story->is_featured);
    }

    public function test_admin_can_delete_success_story(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create(['role' => 'admin', 'is_approved' => true]);
        $imagePath = 'success-stories/test_story.jpg';
        Storage::disk('public')->put($imagePath, 'fake content');

        $story = SuccessStory::create([
            'title' => 'Story to be deleted',
            'alumni_name' => 'Test User',
            'batch_year' => '2020',
            'department' => 'Civil Engineering',
            'story' => 'Temporary test content.',
            'image_path' => $imagePath,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.stories.destroy', $story));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('success_stories', ['id' => $story->id]);
        Storage::disk('public')->assertMissing($imagePath);
    }

    public function test_validation_rules_for_creating_success_story(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_approved' => true]);

        $response = $this->actingAs($admin)->post(route('admin.stories.store'), []);

        $response->assertSessionHasErrors(['title', 'alumni_name', 'batch_year', 'department', 'story']);
    }
}
