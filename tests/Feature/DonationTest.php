<?php

namespace Tests\Feature;

use App\Models\Donation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DonationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_giving_back_hub_with_campaigns_and_progress(): void
    {
        $user = User::factory()->create(['is_approved' => true]);

        Donation::create([
            'user_id' => $user->id,
            'amount' => 5000,
            'campaign' => 'Student Scholarship Fund',
            'transaction_id' => 'KDP-TXN-TEST01',
            'status' => 'completed',
        ]);

        $response = $this->actingAs($user)->get(route('donations.index'));

        $response->assertStatus(200);
        $response->assertSee('Student Scholarship Fund');
        $response->assertSee('₹5,000.00');
        $response->assertSee('Active Campus Campaigns');
    }

    public function test_user_can_submit_donation(): void
    {
        $user = User::factory()->create(['is_approved' => true]);

        $response = $this->actingAs($user)->post(route('donations.store'), [
            'amount' => 2500,
            'campaign' => 'Computer Engineering Lab Upgrade',
            'payment_method' => 'UPI / QR Code',
            'notes' => 'Proud to support our computer lab.',
            'is_anonymous' => 0,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('donations', [
            'user_id' => $user->id,
            'amount' => 2500,
            'campaign' => 'Computer Engineering Lab Upgrade',
            'notes' => 'Proud to support our computer lab.',
            'is_anonymous' => false,
            'status' => 'completed',
        ]);
    }

    public function test_anonymous_donations_are_masked_on_the_wall(): void
    {
        $donor = User::factory()->create([
            'name' => 'Secret Philanthropist',
            'is_approved' => true,
        ]);

        $viewer = User::factory()->create([
            'name' => 'Public Visitor',
            'is_approved' => true,
        ]);

        Donation::create([
            'user_id' => $donor->id,
            'amount' => 10000,
            'campaign' => 'General Campus Development Fund',
            'is_anonymous' => true,
            'transaction_id' => 'KDP-TXN-SECRET',
            'status' => 'completed',
        ]);

        $response = $this->actingAs($viewer)->get(route('donations.index'));

        $response->assertStatus(200);
        $response->assertSee('Anonymous Donor');
        $response->assertDontSee('Secret Philanthropist');
    }

    public function test_donor_can_download_their_official_80g_tax_receipt_pdf(): void
    {
        $donor = User::factory()->create([
            'name' => 'Rajesh Mehta',
            'is_approved' => true,
        ]);

        $donation = Donation::create([
            'user_id' => $donor->id,
            'amount' => 5000,
            'campaign' => 'Student Scholarship Fund',
            'transaction_id' => 'KDP-TXN-RECEIPT1',
            'status' => 'completed',
        ]);

        $response = $this->actingAs($donor)->get(route('donations.receipt', $donation->id));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_admin_can_download_any_donor_tax_receipt(): void
    {
        $donor = User::factory()->create(['is_approved' => true]);
        $admin = User::factory()->create(['role' => 'admin', 'is_approved' => true]);

        $donation = Donation::create([
            'user_id' => $donor->id,
            'amount' => 3000,
            'campaign' => 'Library & Resource Center',
            'transaction_id' => 'KDP-TXN-ADMINVIEW',
            'status' => 'completed',
        ]);

        $response = $this->actingAs($admin)->get(route('donations.receipt', $donation->id));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_unauthorized_user_cannot_download_another_users_receipt(): void
    {
        $donor = User::factory()->create(['is_approved' => true]);
        $otherUser = User::factory()->create(['role' => 'student', 'is_approved' => true]);

        $donation = Donation::create([
            'user_id' => $donor->id,
            'amount' => 2000,
            'campaign' => 'General Campus Development Fund',
            'transaction_id' => 'KDP-TXN-PRIVATE',
            'status' => 'completed',
        ]);

        $response = $this->actingAs($otherUser)->get(route('donations.receipt', $donation->id));

        $response->assertStatus(403);
    }

    public function test_donation_amount_validation(): void
    {
        $user = User::factory()->create(['is_approved' => true]);

        $response = $this->actingAs($user)->post(route('donations.store'), [
            'amount' => 10, // below minimum 50
            'campaign' => 'Student Scholarship Fund',
        ]);

        $response->assertSessionHasErrors('amount');
    }
}
