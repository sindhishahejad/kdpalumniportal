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

    public function test_query_matches_smart_id_card_service_and_spotlight(): void
    {
        $response = $this->getJson(route('alum-smart.search', ['q' => 'id']));

        $response->assertStatus(200);
        $this->assertNotNull($response->json('spotlight'));
        $this->assertEquals('Digital Alumni Smart ID Card', $response->json('spotlight.title'));
        $this->assertNotEmpty($response->json('services'));
        $serviceIds = collect($response->json('services'))->pluck('id')->toArray();
        $this->assertContains('smart-id-card', $serviceIds);
    }

    public function test_case_insensitive_matching_uppercase_lowercase_and_mixed(): void
    {
        // 1. All Uppercase "ID"
        $resUpper = $this->getJson(route('alum-smart.search', ['q' => 'ID']));
        $resUpper->assertStatus(200);
        $this->assertEquals('Digital Alumni Smart ID Card', $resUpper->json('spotlight.title'));

        // 2. Title / Mixed Case "Id" and "iD"
        $resTitle = $this->getJson(route('alum-smart.search', ['q' => 'Id']));
        $resTitle->assertStatus(200);
        $this->assertEquals('Digital Alumni Smart ID Card', $resTitle->json('spotlight.title'));

        $resInverted = $this->getJson(route('alum-smart.search', ['q' => 'iD']));
        $resInverted->assertStatus(200);
        $this->assertEquals('Digital Alumni Smart ID Card', $resInverted->json('spotlight.title'));

        // 3. Mixed case "tRanScript"
        $resTrans = $this->getJson(route('alum-smart.search', ['q' => 'tRanScript']));
        $resTrans->assertStatus(200);
        $this->assertEquals('Request Official College Document', $resTrans->json('spotlight.title'));

        // 4. Mixed case "mEnTor"
        $resMentor = $this->getJson(route('alum-smart.search', ['q' => 'mEnTor']));
        $resMentor->assertStatus(200);
        $this->assertEquals('1-on-1 Alumni Mentorship & Career Guidance', $resMentor->json('spotlight.title'));

        // 5. Uppercase "JOBS"
        $resJobs = $this->getJson(route('alum-smart.search', ['q' => 'JOBS']));
        $resJobs->assertStatus(200);
        $this->assertEquals('Engineering Career Board & Referral Desk', $resJobs->json('spotlight.title'));
    }

    public function test_half_word_and_prefix_matching(): void
    {
        // 1. Single character "i" matches Smart ID Card
        $resSingleI = $this->getJson(route('alum-smart.search', ['q' => 'i']));
        $resSingleI->assertStatus(200);
        $serviceIds = collect($resSingleI->json('services'))->pluck('id')->toArray();
        $this->assertContains('smart-id-card', $serviceIds);

        // 2. Half-word "tra" matches Official Transcript Document Desk
        $resTra = $this->getJson(route('alum-smart.search', ['q' => 'tra']));
        $resTra->assertStatus(200);
        $serviceIds = collect($resTra->json('services'))->pluck('id')->toArray();
        $this->assertContains('document-desk', $serviceIds);

        // 3. Half-word "men" matches Mentorship Hub
        $resMen = $this->getJson(route('alum-smart.search', ['q' => 'men']));
        $resMen->assertStatus(200);
        $serviceIds = collect($resMen->json('services'))->pluck('id')->toArray();
        $this->assertContains('mentorship-hub', $serviceIds);

        // 4. Half-word "lat" matches Department Labs (Lathe machinery)
        $resLat = $this->getJson(route('alum-smart.search', ['q' => 'lat']));
        $resLat->assertStatus(200);
        $serviceIds = collect($resLat->json('services'))->pluck('id')->toArray();
        $this->assertContains('department-labs', $serviceIds);

        // 5. Half-word "reu" matches Batch Reunions
        $resReu = $this->getJson(route('alum-smart.search', ['q' => 'reu']));
        $resReu->assertStatus(200);
        $serviceIds = collect($resReu->json('services'))->pluck('id')->toArray();
        $this->assertContains('batch-reunions', $serviceIds);

        // 6. Half-word "don" matches Giving Back donation fund
        $resDon = $this->getJson(route('alum-smart.search', ['q' => 'don']));
        $resDon->assertStatus(200);
        $serviceIds = collect($resDon->json('services'))->pluck('id')->toArray();
        $this->assertContains('giving-back', $serviceIds);

        // 7. Half-word "nos" matches Campus Time-Machine Nostalgia Map
        $resNos = $this->getJson(route('alum-smart.search', ['q' => 'nos']));
        $resNos->assertStatus(200);
        $serviceIds = collect($resNos->json('services'))->pluck('id')->toArray();
        $this->assertContains('campus-time-machine', $serviceIds);
    }

    public function test_query_matches_mentorship_service(): void
    {
        $response = $this->getJson(route('alum-smart.search', ['q' => 'mentorship guidance d2d']));

        $response->assertStatus(200);
        $this->assertNotEmpty($response->json('services'));
        $serviceIds = collect($response->json('services'))->pluck('id')->toArray();
        $this->assertContains('mentorship-hub', $serviceIds);
    }

    public function test_multilingual_gujarati_search_matches_services(): void
    {
        $response = $this->getJson(route('alum-smart.search', ['q' => 'નોકરી']));

        $response->assertStatus(200);
        $this->assertNotEmpty($response->json('services'));
        $serviceIds = collect($response->json('services'))->pluck('id')->toArray();
        $this->assertContains('job-board', $serviceIds);
    }
}
