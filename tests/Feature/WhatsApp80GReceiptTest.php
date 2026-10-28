<?php

namespace Tests\Feature;

use App\Models\Donation;
use App\Models\Profile;
use App\Models\User;
use App\Models\WhatsAppBroadcastLog;
use App\Services\WhatsAppAlertService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WhatsApp80GReceiptTest extends TestCase
{
    use RefreshDatabase;

    public function test_whatsapp_80g_receipt_message_formatting_contains_statutory_and_institutional_fields(): void
    {
        $user = User::factory()->create([
            'name' => 'Bhavin Patel',
            'phone' => '9876543210',
            'is_approved' => true,
        ]);

        $donation = Donation::create([
            'id' => 12,
            'user_id' => $user->id,
            'amount' => 5000,
            'campaign' => 'Student Scholarship Fund',
            'transaction_id' => 'KDP-TXN-98765432',
            'status' => 'completed',
        ]);

        $message = WhatsAppAlertService::donationReceiptMessage($donation);
        $expectedReceiptNo = 'KDP-REC-' . str_pad($donation->id, 5, '0', STR_PAD_LEFT);

        $this->assertStringContainsString('K.D. Polytechnic, Patan — Official 80G Tax Receipt', $message);
        $this->assertStringContainsString('Bhavin Patel', $message);
        $this->assertStringContainsString($expectedReceiptNo, $message);
        $this->assertStringContainsString('₹5,000.00', $message);
        $this->assertStringContainsString('Five Thousand Rupees Only', $message);
        $this->assertStringContainsString('Student Scholarship Fund', $message);
        $this->assertStringContainsString('KDP-TXN-98765432', $message);
        $this->assertStringContainsString('Section 80G of the Income Tax Act', $message);
        $this->assertStringContainsString(route('donations.receipt', $donation->id), $message);
        $this->assertStringContainsString('કિલાચંદ દેવચંદ પોલિટેકનિક, પાટણ • Estd. 1961', $message);
    }

    public function test_dispatch_donation_receipt_logs_to_database_and_sends_to_donor_phone(): void
    {
        $user = User::factory()->create([
            'name' => 'Hitesh Sharma',
            'phone' => '9876543210',
            'is_approved' => true,
        ]);

        $donation = Donation::create([
            'user_id' => $user->id,
            'amount' => 2500,
            'campaign' => 'Computer Engineering Lab Upgrade',
            'transaction_id' => 'KDP-TXN-112233',
            'status' => 'completed',
        ]);

        $result = WhatsAppAlertService::dispatchDonationReceipt($donation);

        $this->assertTrue($result['success']);
        $this->assertEquals('919876543210', $result['phone']);
        $this->assertStringStartsWith('https://wa.me/919876543210', $result['url']);

        $this->assertDatabaseHas('whatsapp_broadcast_logs', [
            'sender_id' => $user->id,
            'recipient_phone' => '919876543210',
            'recipient_name' => 'Hitesh Sharma',
            'alert_type' => 'donation_80g_receipt',
            'status' => 'sent',
        ]);
    }

    public function test_dispatch_donation_receipt_accepts_explicit_phone_override(): void
    {
        $user = User::factory()->create([
            'name' => 'Alok Mehta',
            'phone' => null,
            'is_approved' => true,
        ]);

        $donation = Donation::create([
            'user_id' => $user->id,
            'amount' => 1000,
            'campaign' => 'Civil Workshop Equipment Fund',
            'transaction_id' => 'KDP-TXN-334455',
            'status' => 'completed',
        ]);

        $result = WhatsAppAlertService::dispatchDonationReceipt($donation, '9123456789');

        $this->assertTrue($result['success']);
        $this->assertEquals('919123456789', $result['phone']);
        $this->assertStringStartsWith('https://wa.me/919123456789', $result['url']);
    }

    public function test_dispatch_donation_receipt_fallback_when_phone_is_missing(): void
    {
        $user = User::factory()->create([
            'name' => 'Sneha Dave',
            'phone' => null,
            'is_approved' => true,
        ]);

        $donation = Donation::create([
            'user_id' => $user->id,
            'amount' => 1500,
            'campaign' => 'Student Scholarship Fund',
            'transaction_id' => 'KDP-TXN-556677',
            'status' => 'completed',
        ]);

        $result = WhatsAppAlertService::dispatchDonationReceipt($donation);

        $this->assertTrue($result['success']);
        $this->assertEquals('', $result['phone']);
        $this->assertStringStartsWith('https://wa.me/?text=', $result['url']);

        $this->assertDatabaseHas('whatsapp_broadcast_logs', [
            'recipient_phone' => 'pending_user_selection',
            'alert_type' => 'donation_80g_receipt',
            'channel' => 'direct_click',
        ]);
    }

    public function test_donation_store_flashes_whatsapp_url_and_shows_in_view(): void
    {
        $user = User::factory()->create([
            'phone' => '9876543210',
            'is_approved' => true,
        ]);

        $response = $this->actingAs($user)->post(route('donations.store'), [
            'amount' => 3000,
            'campaign' => 'Electrical Engineering Smart Grid Lab',
            'payment_method' => 'UPI / QR Code',
            'notes' => 'Best wishes for the department.',
            'is_anonymous' => 0,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $response->assertSessionHas('whatsapp_url');

        $indexResponse = $this->actingAs($user)->get(route('donations.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('WhatsApp 80G');
        $indexResponse->assertSee('Electrical Engineering Smart Grid Lab');
    }

    public function test_razorpay_verify_payment_dispatches_whatsapp_receipt(): void
    {
        $user = User::factory()->create([
            'phone' => '9876543210',
            'is_approved' => true,
        ]);

        $donation = Donation::create([
            'user_id' => $user->id,
            'amount' => 5000,
            'campaign' => 'Student Scholarship Fund',
            'transaction_id' => 'order_kdp_sim_12345',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)->postJson(route('payments.verify'), [
            'donation_id' => $donation->id,
            'razorpay_order_id' => 'order_kdp_sim_12345',
            'razorpay_payment_id' => 'pay_sim_99999',
            'razorpay_signature' => 'mock_simulated_signature',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'amount' => 5000,
            'whatsapp_phone' => '919876543210',
            'whatsapp_dispatched' => true,
        ]);

        $this->assertDatabaseHas('whatsapp_broadcast_logs', [
            'recipient_phone' => '919876543210',
            'alert_type' => 'donation_80g_receipt',
        ]);
    }

    public function test_donor_can_access_whatsapp_receipt_redirect_and_json(): void
    {
        $user = User::factory()->create([
            'phone' => '9876543210',
            'is_approved' => true,
        ]);

        $donation = Donation::create([
            'user_id' => $user->id,
            'amount' => 2000,
            'campaign' => 'Mechanical Workshop Modernization',
            'transaction_id' => 'KDP-TXN-778899',
            'status' => 'completed',
        ]);

        // Web redirect
        $redirectResponse = $this->actingAs($user)->get(route('donations.whatsapp', $donation->id));
        $redirectResponse->assertRedirect();
        $this->assertStringContainsString('https://wa.me/919876543210', $redirectResponse->headers->get('Location'));

        // JSON API
        $jsonResponse = $this->actingAs($user)->getJson(route('donations.whatsapp', $donation->id));
        $jsonResponse->assertStatus(200);
        $jsonResponse->assertJson([
            'success' => true,
            'phone' => '919876543210',
        ]);
    }

    public function test_admin_can_access_any_donors_whatsapp_receipt(): void
    {
        $donor = User::factory()->create(['phone' => '9876543210', 'is_approved' => true]);
        $admin = User::factory()->create(['role' => 'admin', 'is_approved' => true]);

        $donation = Donation::create([
            'user_id' => $donor->id,
            'amount' => 2000,
            'campaign' => 'Student Scholarship Fund',
            'transaction_id' => 'KDP-TXN-ADM01',
            'status' => 'completed',
        ]);

        $response = $this->actingAs($admin)->get(route('donations.whatsapp', $donation->id));
        $response->assertRedirect();
    }

    public function test_unauthorized_user_cannot_access_other_users_whatsapp_receipt(): void
    {
        $donor = User::factory()->create(['phone' => '9876543210', 'is_approved' => true]);
        $stranger = User::factory()->create(['is_approved' => true]);

        $donation = Donation::create([
            'user_id' => $donor->id,
            'amount' => 2000,
            'campaign' => 'Student Scholarship Fund',
            'transaction_id' => 'KDP-TXN-OTH01',
            'status' => 'completed',
        ]);

        $response = $this->actingAs($stranger)->get(route('donations.whatsapp', $donation->id));
        $response->assertStatus(403);
    }
}
