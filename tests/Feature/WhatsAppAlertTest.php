<?php

namespace Tests\Feature;

use App\Mail\WeeklyNewsletter;
use App\Models\DocumentRequest;
use App\Models\JobPosting;
use App\Models\JobReferral;
use App\Models\Profile;
use App\Models\ToolDonation;
use App\Models\User;
use App\Models\WhatsAppBroadcastLog;
use App\Services\WhatsAppAlertService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class WhatsAppAlertTest extends TestCase
{
    use RefreshDatabase;

    public function test_phone_sanitization_formats_for_international_whatsapp(): void
    {
        // 10 digits
        $this->assertEquals('919876543210', WhatsAppAlertService::cleanPhoneNumber('9876543210'));

        // Formatted with spaces and dashes
        $this->assertEquals('919876543210', WhatsAppAlertService::cleanPhoneNumber('+91 98765-43210'));

        // With leading 0
        $this->assertEquals('919876543210', WhatsAppAlertService::cleanPhoneNumber('09876543210'));

        // Null / empty
        $this->assertEquals('', WhatsAppAlertService::cleanPhoneNumber(null));
        $this->assertEquals('', WhatsAppAlertService::cleanPhoneNumber(''));
    }

    public function test_click_to_chat_url_is_correctly_encoded(): void
    {
        $url = WhatsAppAlertService::generateClickToChatUrl('9876543210', 'Hello from KDP Alumni!');

        $this->assertStringStartsWith('https://wa.me/919876543210?text=', $url);
        $this->assertStringContainsString('Hello%20from%20KDP%20Alumni%21', $url);
    }

    public function test_document_ready_message_contains_essential_metadata(): void
    {
        $user = User::factory()->create(['name' => 'Ketan Patel']);
        $doc = DocumentRequest::create([
            'user_id' => $user->id,
            'document_type' => 'Diploma Passing Certificate',
            'purpose' => 'Higher Studies',
            'status' => 'Fulfilled',
        ]);

        $message = WhatsAppAlertService::documentReadyMessage($user, $doc);

        $this->assertStringContainsString('K.D. Polytechnic, Patan', $message);
        $this->assertStringContainsString('Ketan Patel', $message);
        $this->assertStringContainsString('Diploma Passing Certificate', $message);
        $this->assertStringContainsString(route('documents.download', $doc->id), $message);
    }

    public function test_job_referral_status_message_formats_correctly(): void
    {
        $alumni = User::factory()->create(['name' => 'Snehal Dave', 'role' => 'alumni']);
        $job = JobPosting::create([
            'user_id' => $alumni->id,
            'title' => 'Junior Embedded Engineer',
            'company' => 'Tata Electronics GIDC',
            'location' => 'Ahmedabad',
            'type' => 'Full-time',
            'description' => 'Great role for polytechnic grads.',
            'status' => 'approved',
        ]);

        $referral = JobReferral::create([
            'job_posting_id' => $job->id,
            'user_id' => $alumni->id,
            'candidate_name' => 'Aakash Trivedi',
            'candidate_email' => 'aakash@example.com',
            'candidate_phone' => '9825012345',
            'resume_path' => 'resumes/dummy.pdf',
            'status' => 'referred',
            'feedback' => 'Resume forwarded to technical hiring team.',
        ]);

        $message = WhatsAppAlertService::jobReferralStatusMessage($referral, $referral->status, $referral->feedback);

        $this->assertStringContainsString('Aakash Trivedi', $message);
        $this->assertStringContainsString('Junior Embedded Engineer', $message);
        $this->assertStringContainsString('Tata Electronics GIDC', $message);
        $this->assertStringContainsString('REFERRED', $message);
        $this->assertStringContainsString('Resume forwarded to technical hiring team', $message);
    }

    public function test_tool_claim_message_includes_pickup_venue(): void
    {
        $donor = User::factory()->create(['name' => 'Harshil Shah']);
        $student = User::factory()->create(['name' => 'Pooja Barot']);

        $donation = ToolDonation::create([
            'user_id' => $donor->id,
            'title' => 'Mitutoyo Vernier Caliper 150mm',
            'slug' => 'mitutoyo-vernier-caliper-150mm',
            'item_type' => 'tool',
            'category' => 'Instruments',
            'description' => 'Precision measurement tool for workshop students.',
            'condition' => 'Gently Used',
            'department' => 'Mechanical Engineering',
            'pickup_location' => 'Mechanical Workshop Lab 2',
            'status' => 'reserved',
        ]);

        $message = WhatsAppAlertService::toolClaimAcceptedMessage($donation, $student);

        $this->assertStringContainsString('Pooja Barot', $message);
        $this->assertStringContainsString('Harshil Shah', $message);
        $this->assertStringContainsString('Mitutoyo Vernier Caliper 150mm', $message);
        $this->assertStringContainsString('Mechanical Workshop Lab 2', $message);
    }

    public function test_whatsapp_alert_service_send_creates_audit_log(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_approved' => true]);

        $result = WhatsAppAlertService::send(
            '9876543210',
            'Annual Alumni Meet Scheduled for December 15th.',
            'event_alert',
            'Vipul Patel',
            $admin->id
        );

        $this->assertTrue($result['success']);
        $this->assertEquals('919876543210', $result['phone']);
        $this->assertDatabaseHas('whatsapp_broadcast_logs', [
            'recipient_phone' => '919876543210',
            'recipient_name' => 'Vipul Patel',
            'alert_type' => 'event_alert',
            'sender_id' => $admin->id,
        ]);
    }

    public function test_non_admin_cannot_access_alert_control_center(): void
    {
        $student = User::factory()->create(['role' => 'student', 'is_approved' => true]);

        $response = $this->actingAs($student)->get('/admin/alerts');
        $response->assertStatus(403);
    }

    public function test_admin_can_access_alert_control_center(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_approved' => true]);

        WhatsAppBroadcastLog::create([
            'sender_id' => $admin->id,
            'recipient_phone' => '919876543210',
            'recipient_name' => 'Chirag Soni',
            'alert_type' => 'document_ready',
            'message' => 'Your transcript is ready.',
            'channel' => 'direct_click',
            'status' => 'sent',
        ]);

        $response = $this->actingAs($admin)->get('/admin/alerts');

        $response->assertStatus(200);
        $response->assertSee('Direct WhatsApp &amp; Email Alert Hub', false);
        $response->assertSee('Chirag Soni');
        $response->assertSee('919876543210');
    }

    public function test_admin_can_send_test_email(): void
    {
        Mail::fake();

        $admin = User::factory()->create(['role' => 'admin', 'is_approved' => true]);

        $response = $this->actingAs($admin)->post('/admin/alerts/test-email', [
            'test_email' => 'admin-test@example.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        Mail::assertSent(WeeklyNewsletter::class, function ($mail) {
            return $mail->hasTo('admin-test@example.com');
        });
    }

    public function test_admin_can_dispatch_single_whatsapp_broadcast(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_approved' => true]);

        $response = $this->actingAs($admin)->post('/admin/alerts/whatsapp-broadcast', [
            'target_audience' => 'single_phone',
            'single_phone' => '9876543210',
            'title' => 'Urgent Campus Placement Drive',
            'message' => 'L&T Infotech is visiting KDP campus this Thursday. Bring 3 copies of resume.',
            'link' => 'https://kdppatan.ac.in',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('whatsapp_dispatch');

        $this->assertDatabaseHas('whatsapp_broadcast_logs', [
            'recipient_phone' => '919876543210',
            'sender_id' => $admin->id,
        ]);
    }

    public function test_admin_can_dispatch_branch_filtered_broadcast(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_approved' => true]);

        $u1 = User::factory()->create(['role' => 'student', 'phone' => '9800000001', 'department' => 'Computer Engineering', 'is_approved' => true]);
        Profile::create(['user_id' => $u1->id, 'department' => 'Computer Engineering', 'graduation_year' => 2025]);

        $u2 = User::factory()->create(['role' => 'alumni', 'phone' => '9800000002', 'department' => 'Civil Engineering', 'is_approved' => true]);
        Profile::create(['user_id' => $u2->id, 'department' => 'Civil Engineering', 'graduation_year' => 2024]);

        $response = $this->actingAs($admin)->post('/admin/alerts/whatsapp-broadcast', [
            'target_audience' => 'department',
            'department' => 'Computer Engineering',
            'title' => 'Web Dev Hackathon',
            'message' => 'Annual GTU TechFest Hackathon registration is open.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('whatsapp_dispatch');

        // Should log for Computer Engineering user
        $this->assertDatabaseHas('whatsapp_broadcast_logs', [
            'recipient_phone' => '919800000001',
        ]);

        // Should NOT log for Civil Engineering user
        $this->assertDatabaseMissing('whatsapp_broadcast_logs', [
            'recipient_phone' => '919800000002',
        ]);
    }

    public function test_candidate_phone_renders_direct_whatsapp_button_in_pipeline(): void
    {
        $alumni = User::factory()->create(['role' => 'alumni', 'is_approved' => true]);
        $job = JobPosting::create([
            'user_id' => $alumni->id,
            'title' => 'Automation Engineer',
            'company' => 'Adani Ports',
            'location' => 'Mundra',
            'type' => 'Full-time',
            'description' => 'Industrial automation role.',
            'status' => 'approved',
        ]);

        JobReferral::create([
            'job_posting_id' => $job->id,
            'user_id' => $alumni->id,
            'candidate_name' => 'Bhavin Parmar',
            'candidate_email' => 'bhavin@example.com',
            'candidate_phone' => '9876598765',
            'resume_path' => 'resumes/dummy.pdf',
            'status' => 'pending',
            'feedback' => null,
        ]);

        $response = $this->actingAs($alumni)->get(route('jobs.pipeline'));

        $response->assertStatus(200);
        $response->assertSee('WhatsApp Candidate');
        $response->assertSee('https://wa.me/919876598765', false);
    }
}
