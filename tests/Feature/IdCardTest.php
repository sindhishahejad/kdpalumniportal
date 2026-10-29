<?php

namespace Tests\Feature;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IdCardTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_smart_id_card_with_qr_code(): void
    {
        $alumni = User::factory()->create([
            'name' => 'Chirag Prajapati',
            'role' => 'alumni',
            'is_approved' => true,
            'department' => 'Civil Engineering',
            'entry_no' => 'KD18CIV012',
            'blood_group' => 'B+',
        ]);

        $response = $this->actingAs($alumni)->get(route('id-card.show'));

        $response->assertStatus(200);
        $response->assertSee('Chirag Prajapati');
        $response->assertSee('KD18CIV012');
        $response->assertSee('Civil Engineering');
        $response->assertSee('B+');
        $response->assertSee('Scan to Verify');
        $response->assertSee(route('alumni.verify', $alumni->id));
    }

    public function test_authenticated_user_can_download_id_card_pdf(): void
    {
        $alumni = User::factory()->create([
            'name' => 'Dipak Solanki',
            'role' => 'alumni',
            'is_approved' => true,
            'entry_no' => 'KD19MECH05',
        ]);

        $response = $this->actingAs($alumni)->get(route('id-card.download'));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_public_can_verify_approved_alumni_by_id(): void
    {
        $alumni = User::factory()->create([
            'name' => 'Sneha Panchal',
            'role' => 'alumni',
            'is_approved' => true,
            'department' => 'Electrical Engineering',
            'graduation_year' => '2021',
        ]);

        // Public route without authentication
        $response = $this->get(route('alumni.verify', $alumni->id));

        $response->assertStatus(200);
        $response->assertSee('Verified Alumni Credential');
        $response->assertSee('Sneha Panchal');
        $response->assertSee('Electrical Engineering');
        $response->assertSee('Active Lifetime Member');
    }

    public function test_public_can_verify_approved_alumni_by_entry_no(): void
    {
        $alumni = User::factory()->create([
            'name' => 'Mehul Vaghela',
            'role' => 'alumni',
            'is_approved' => true,
            'entry_no' => 'KD20IT044',
            'department' => 'Information Technology',
        ]);

        $response = $this->get(route('alumni.verify', 'KD20IT044'));

        $response->assertStatus(200);
        $response->assertSee('Verified Alumni Credential');
        $response->assertSee('Mehul Vaghela');
        $response->assertSee('Information Technology');
    }

    public function test_public_verification_fails_for_unapproved_or_nonexistent_user(): void
    {
        // Unapproved user
        $unapproved = User::factory()->unapproved()->create([
            'name' => 'Pending Alum',
            'entry_no' => 'UNAPPROVED001',
        ]);

        $response1 = $this->get(route('alumni.verify', 'UNAPPROVED001'));
        $response1->assertStatus(200);
        $response1->assertSee('Unverified Credential');
        $response1->assertDontSee('Active Lifetime Member');

        // Non-existent ID
        $response2 = $this->get(route('alumni.verify', 999999));
        $response2->assertStatus(200);
        $response2->assertSee('Unverified Credential');
    }

    public function test_guest_cannot_view_or_download_authenticated_id_card(): void
    {
        $this->get(route('id-card.show'))->assertRedirect(route('login'));
        $this->get(route('id-card.download'))->assertRedirect(route('login'));
    }

    public function test_smart_id_card_renders_official_college_crest_and_cte_seal(): void
    {
        $alumni = User::factory()->create([
            'name' => 'Ketan Patel',
            'role' => 'alumni',
            'is_approved' => true,
        ]);

        $response = $this->actingAs($alumni)->get(route('id-card.show'));
        $response->assertStatus(200);
        $response->assertSee('images/kdp_crest.png');
        $response->assertSee('images/gtu_cte_logo.jpg');
        $response->assertSee('Commissionerate of Technical Education');
        $response->assertSee('OFFICIAL SMART ALUMNI CARD');
    }

    public function test_smart_id_card_displays_department_label_and_user_department_correctly(): void
    {
        $alumni = User::factory()->create([
            'name' => 'Shahejad Sindhi',
            'role' => 'alumni',
            'is_approved' => true,
            'department' => 'Computer Engineering',
        ]);

        $response = $this->actingAs($alumni)->get(route('id-card.show'));
        $response->assertStatus(200);
        $response->assertSee('Department');
        $response->assertSee(': Computer Engineering');
        $response->assertDontSee('<span class="w-24 font-bold text-slate-500 text-[11px] uppercase tracking-wider">Civil Engineering</span>', false);
    }

    public function test_dashboard_side_alumni_card_renders_profile_photo_dynamically(): void
    {
        $alumniWithPhoto = User::factory()->create([
            'name' => 'Rahul Sharma',
            'role' => 'alumni',
            'is_approved' => true,
            'photo_path' => 'profile-photos/rahul_custom_photo.jpg',
        ]);

        $responseWithPhoto = $this->actingAs($alumniWithPhoto)->get(route('dashboard'));
        $responseWithPhoto->assertStatus(200);
        $responseWithPhoto->assertSee('profile-photos/rahul_custom_photo.jpg');

        $alumniWithoutPhoto = User::factory()->create([
            'name' => 'Ankit Dave',
            'role' => 'alumni',
            'is_approved' => true,
            'photo_path' => null,
        ]);

        $responseWithoutPhoto = $this->actingAs($alumniWithoutPhoto)->get(route('dashboard'));
        $responseWithoutPhoto->assertStatus(200);
        $responseWithoutPhoto->assertSee('ui-avatars.com/api/?name=Ankit+Dave');
    }
}
