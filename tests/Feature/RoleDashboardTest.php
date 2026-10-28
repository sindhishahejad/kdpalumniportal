<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\JobPosting;
use App\Models\Notice;
use App\Models\ProjectPitch;
use App\Models\ToolDonation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_dashboard(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_student_can_view_student_dashboard_with_all_action_portals(): void
    {
        $student = User::factory()->create([
            'role' => 'student',
            'is_approved' => true,
        ]);

        Notice::create([
            'title' => 'GTU Winter Diploma Examination Schedule',
            'body' => 'All semester students are instructed to download exam hall tickets.',
            'is_active' => true,
        ]);

        $alumni = User::factory()->create(['role' => 'alumni', 'is_approved' => true]);
        ToolDonation::create([
            'user_id' => $alumni->id,
            'title' => 'Scientific Calculator fx-991ES Plus',
            'slug' => 'scientific-calc-fx991es',
            'item_type' => 'calculator',
            'department' => 'Civil Engineering',
            'condition' => 'good',
            'description' => 'Original Casio scientific calculator with lid and battery.',
            'pickup_method' => 'on_campus',
            'pickup_location' => 'Library Counter',
            'status' => 'available',
        ]);

        $response = $this->actingAs($student)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Student Academic &amp; Career Hub', false);
        $response->assertSee('Tool &amp; Book Bank', false);
        $response->assertSee('KDP Mitra AI Counselor');
        $response->assertSee('Academic Calendar');
        $response->assertSee('GTU Winter Diploma Examination Schedule');
        $response->assertSee('Scientific Calculator fx-991ES Plus');
        $response->assertSee('Interactive Regional Chapter Map');
    }

    public function test_alumni_can_view_alumni_dashboard_with_service_cards_and_id_card(): void
    {
        $alumni = User::factory()->create([
            'role' => 'alumni',
            'is_approved' => true,
        ]);

        $response = $this->actingAs($alumni)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Welcome to KDP Alumni Association Network');
        $response->assertSee('Your Alumni Profile');
        $response->assertSee('Mentorship Hub');
        $response->assertSee('Alumni Directory');
        $response->assertSee('Resource Vault');
        $response->assertSee('Document Requests');
        $response->assertSee('Giving Back');
        $response->assertSee('Success Stories');
        $response->assertSee('Project Incubation');
        $response->assertSee('Awards &amp; Hall of Fame', false);
        $response->assertSee('Tool &amp; Book Bank', false);
        $response->assertSee('My Smart Alumni ID');
        $response->assertSee('Ask KDP Mitra AI');
    }

    public function test_admin_can_view_admin_dashboard_with_all_metrics_and_quick_modules(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_approved' => true,
        ]);

        $response = $this->actingAs($admin)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Admin Central Command');
        $response->assertSee('Direct WhatsApp &amp; Email Alert Center', false);
        $response->assertSee('Document Requests Desk');
        $response->assertSee('Contact Inquiries');
        $response->assertSee('Giving Back &amp; Donations Hub', false);
        $response->assertSee('Pay-It-Forward Tool &amp; Book Bank', false);
        $response->assertSee('GTU Academic Calendar &amp; Circulars', false);
        $response->assertSee('Training &amp; Placement (TPO) Partner Cell', false);
    }
}
