<?php

namespace Tests\Feature;

use App\Models\JobPosting;
use App\Models\Profile;
use App\Models\ToolDonation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AlumSmartTest extends TestCase
{
    use RefreshDatabase;

    public function test_empty_query_returns_default_quick_actions_and_suggestions(): void
    {
        $response = $this->getJson(route('alum-smart.search'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'query',
            'total_results',
            'alumni',
            'jobs',
            'tools',
            'quick_actions',
            'suggestions',
        ]);

        $this->assertEquals(0, $response->json('total_results'));
        $this->assertNotEmpty($response->json('quick_actions'));
        $this->assertNotEmpty($response->json('suggestions'));
    }

    public function test_query_matches_alumni_by_name_and_department(): void
    {
        $user = User::factory()->create([
            'name' => 'Dipak Parmar',
            'department' => 'Mechanical Engineering',
            'company' => 'Adani Ports Mundra',
            'phone' => '9876543210',
            'is_approved' => true,
        ]);

        Profile::create([
            'user_id' => $user->id,
            'department' => 'Mechanical Engineering',
            'graduation_year' => 2021,
            'current_company' => 'Adani Ports Mundra',
            'job_title' => 'Maintenance Engineer',
        ]);

        $response = $this->getJson(route('alum-smart.search', ['q' => 'mechanical']));

        $response->assertStatus(200);
        $this->assertGreaterThan(0, $response->json('total_results'));
        $this->assertNotEmpty($response->json('alumni'));

        $firstAlum = $response->json('alumni.0');
        $this->assertEquals('Dipak Parmar', $firstAlum['name']);
        $this->assertStringContainsString('wa.me/919876543210', $firstAlum['whatsapp_url']);
    }

    public function test_query_matches_jobs(): void
    {
        $alumni = User::factory()->create(['role' => 'alumni', 'is_approved' => true]);

        JobPosting::create([
            'user_id' => $alumni->id,
            'title' => 'Embedded Firmware Trainee',
            'company' => 'Voltas GIDC',
            'location' => 'Sanand, Ahmedabad',
            'type' => 'Full-time',
            'description' => 'Great role for diploma electronic engineers.',
            'is_active' => true,
            'status' => 'approved',
        ]);

        $response = $this->getJson(route('alum-smart.search', ['q' => 'Voltas']));

        $response->assertStatus(200);
        $this->assertNotEmpty($response->json('jobs'));
        $this->assertEquals('Embedded Firmware Trainee', $response->json('jobs.0.title'));
        $this->assertEquals('Voltas GIDC', $response->json('jobs.0.company'));
    }

    public function test_query_matches_tool_bank_items(): void
    {
        $donor = User::factory()->create(['is_approved' => true]);

        ToolDonation::create([
            'user_id' => $donor->id,
            'title' => 'Mitutoyo Vernier Caliper 150mm',
            'slug' => 'mitutoyo-vernier-caliper-150mm',
            'item_type' => 'tool',
            'category' => 'Instruments',
            'description' => 'Workshop precision caliper.',
            'condition' => 'Gently Used',
            'department' => 'Mechanical Engineering',
            'status' => 'available',
        ]);

        $response = $this->getJson(route('alum-smart.search', ['q' => 'vernier caliper']));

        $response->assertStatus(200);
        $this->assertNotEmpty($response->json('tools'));
        $this->assertEquals('Mitutoyo Vernier Caliper 150mm', $response->json('tools.0.title'));
    }

    public function test_intent_detection_provides_document_desk_action(): void
    {
        $response = $this->getJson(route('alum-smart.search', ['q' => 'how to get my passing certificate']));

        $response->assertStatus(200);
        $this->assertNotEmpty($response->json('quick_actions'));

        $actionTitles = collect($response->json('quick_actions'))->pluck('title')->toArray();
        $this->assertContains('Request Official College Document', $actionTitles);
    }
}
