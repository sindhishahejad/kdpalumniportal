<?php

namespace Tests\Feature;

use App\Models\BatchReunion;
use App\Models\Donation;
use App\Models\ProjectPitch;
use App\Models\ReunionRsvp;
use App\Models\ToolDonation;
use App\Models\User;
use App\Services\RazorpayService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class RazorpayPaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_create_payment_order(): void
    {
        $response = $this->postJson(route('payments.create-order'), [
            'amount' => 1000,
            'purpose' => 'giving_back',
            'campaign' => 'Student Scholarship Fund',
        ]);

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_create_razorpay_order_for_giving_back_campaign(): void
    {
        $user = User::factory()->create(['is_approved' => true]);

        $response = $this->actingAs($user)->postJson(route('payments.create-order'), [
            'amount' => 2500,
            'purpose' => 'giving_back',
            'campaign' => 'Computer Engineering Lab Upgrade',
            'notes' => 'Supporting high performance GPU workstations.',
            'is_anonymous' => 0,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'amount' => 2500,
            'currency' => 'INR',
            'campaign' => 'Computer Engineering Lab Upgrade',
        ]);

        $this->assertDatabaseHas('donations', [
            'user_id' => $user->id,
            'amount' => 2500,
            'campaign' => 'Computer Engineering Lab Upgrade',
            'status' => 'pending',
        ]);
    }

    public function test_authenticated_user_can_create_order_for_student_pitch_grant(): void
    {
        $student = User::factory()->create(['role' => 'student', 'is_approved' => true]);
        $alumni = User::factory()->create(['role' => 'alumni', 'is_approved' => true]);

        $pitch = ProjectPitch::create([
            'user_id' => $student->id,
            'title' => 'Smart Solar Irrigation Node',
            'description' => 'IoT node for agricultural fields.',
            'tech_stack' => 'ESP32, MQTT, Laravel',
            'assistance_needed' => 'Sensor procurement and guidance',
            'funding_goal' => 10000,
            'funds_raised' => 0,
            'status' => 'open',
        ]);

        $response = $this->actingAs($alumni)->postJson(route('payments.create-order'), [
            'amount' => 1500,
            'purpose' => 'pitch_seed',
            'payable_type' => ProjectPitch::class,
            'payable_id' => $pitch->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'amount' => 1500,
            'campaign' => 'Student Innovation Grant: Smart Solar Irrigation Node',
        ]);

        $this->assertDatabaseHas('donations', [
            'user_id' => $alumni->id,
            'amount' => 1500,
            'payable_type' => ProjectPitch::class,
            'payable_id' => $pitch->id,
            'status' => 'pending',
        ]);
    }

    public function test_authenticated_user_can_create_order_for_tool_bank_sponsorship(): void
    {
        $alumni = User::factory()->create(['role' => 'alumni', 'is_approved' => true]);

        $response = $this->actingAs($alumni)->postJson(route('payments.create-order'), [
            'amount' => 850,
            'purpose' => 'tool_bank_sponsor',
            'campaign' => 'Pay-It-Forward Tool Bank: Casio FX-991CW Calculator',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'amount' => 850,
            'campaign' => 'Pay-It-Forward Tool Bank: Casio FX-991CW Calculator',
        ]);

        $this->assertDatabaseHas('donations', [
            'user_id' => $alumni->id,
            'amount' => 850,
            'payable_type' => ToolDonation::class,
            'status' => 'pending',
        ]);
    }

    public function test_payment_verification_completes_donation_and_generates_receipt(): void
    {
        $user = User::factory()->create(['is_approved' => true]);

        $donation = Donation::create([
            'user_id' => $user->id,
            'amount' => 5000,
            'campaign' => 'Student Scholarship Fund',
            'transaction_id' => 'KDP-TXN-ORD1',
            'razorpay_order_id' => 'order_sim_test12345',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)->postJson(route('payments.verify'), [
            'donation_id' => $donation->id,
            'razorpay_order_id' => 'order_sim_test12345',
            'razorpay_payment_id' => 'pay_sim_987654321',
            'razorpay_signature' => 'sim_sig_abcdef123456',
            'payment_method' => 'Razorpay UPI',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'donation_id' => $donation->id,
            'razorpay_payment_id' => 'pay_sim_987654321',
        ]);

        $this->assertDatabaseHas('donations', [
            'id' => $donation->id,
            'status' => 'completed',
            'razorpay_payment_id' => 'pay_sim_987654321',
            'payment_method' => 'Razorpay UPI',
        ]);
    }

    public function test_pitch_sponsorship_payment_increments_funds_raised(): void
    {
        $student = User::factory()->create(['role' => 'student', 'is_approved' => true]);
        $alumni = User::factory()->create(['role' => 'alumni', 'is_approved' => true]);

        $pitch = ProjectPitch::create([
            'user_id' => $student->id,
            'title' => 'Robocon Automation Rig',
            'description' => 'Precision servo control rig.',
            'tech_stack' => 'C++, ROS, Raspberry Pi',
            'assistance_needed' => 'High torque servos',
            'funding_goal' => 20000,
            'funds_raised' => 2000,
            'status' => 'open',
        ]);

        $donation = Donation::create([
            'user_id' => $alumni->id,
            'amount' => 3000,
            'campaign' => "Student Innovation Grant: {$pitch->title}",
            'transaction_id' => 'KDP-TXN-PITCH1',
            'razorpay_order_id' => 'order_sim_pitch1',
            'payable_type' => ProjectPitch::class,
            'payable_id' => $pitch->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($alumni)->postJson(route('payments.verify'), [
            'donation_id' => $donation->id,
            'razorpay_order_id' => 'order_sim_pitch1',
            'razorpay_payment_id' => 'pay_sim_pitch_done',
            'razorpay_signature' => 'sim_sig_pitch_verify',
            'payment_method' => 'Razorpay Net Banking',
        ]);

        $response->assertStatus(200);

        // Verify that funds_raised was incremented from 2000 to 5000
        $this->assertEquals(5000.00, (float) $pitch->fresh()->funds_raised);
    }

    public function test_reunion_fee_payment_marks_rsvp_as_paid(): void
    {
        $organizer = User::factory()->create(['is_approved' => true]);
        $alumni = User::factory()->create(['is_approved' => true]);

        $reunion = BatchReunion::create([
            'user_id' => $organizer->id,
            'title' => 'Class of 2016 Decennial Reunion',
            'slug' => 'class-of-2016-decennial-reunion',
            'batch_year' => '2016',
            'department' => 'Civil Engineering',
            'event_date' => now()->addDays(30),
            'venue' => 'KDP Auditorium',
            'city' => 'Patan',
            'description' => '10-Year homecoming.',
            'fee_per_person' => 500,
            'status' => 'upcoming',
        ]);

        $donation = Donation::create([
            'user_id' => $alumni->id,
            'amount' => 1000, // 2 people
            'campaign' => "Batch Reunion Fee: {$reunion->title}",
            'transaction_id' => 'KDP-TXN-REUNION1',
            'razorpay_order_id' => 'order_sim_reunion1',
            'payable_type' => BatchReunion::class,
            'payable_id' => $reunion->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($alumni)->postJson(route('payments.verify'), [
            'donation_id' => $donation->id,
            'razorpay_order_id' => 'order_sim_reunion1',
            'razorpay_payment_id' => 'pay_sim_reunion_done',
            'razorpay_signature' => 'sim_sig_reunion_verify',
            'payment_method' => 'Razorpay UPI (Google Pay)',
            'guests_count' => 1,
        ]);

        $response->assertStatus(200);

        $rsvp = ReunionRsvp::where('batch_reunion_id', $reunion->id)
            ->where('user_id', $alumni->id)
            ->first();

        $this->assertNotNull($rsvp);
        $this->assertEquals('paid', $rsvp->payment_status);
        $this->assertEquals(1000.00, (float) $rsvp->amount_paid);
        $this->assertEquals(1, $rsvp->guests_count);
        $this->assertTrue($rsvp->isPaid());
    }

    public function test_payment_amount_validation(): void
    {
        $user = User::factory()->create(['is_approved' => true]);

        $response = $this->actingAs($user)->postJson(route('payments.create-order'), [
            'amount' => 5, // minimum is 10
            'purpose' => 'giving_back',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['amount']);
    }
}
