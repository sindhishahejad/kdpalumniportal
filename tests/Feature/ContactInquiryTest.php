<?php

namespace Tests\Feature;

use App\Models\ContactInquiry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactInquiryTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_contact_page(): void
    {
        $response = $this->get(route('contact'));

        $response->assertStatus(200);
        $response->assertSee('Connect with K. D. Polytechnic, Patan');
        $response->assertSee('kdp-patan-dte@gujarat.gov.in');
        $response->assertSee('02766-220419');
    }

    public function test_guest_or_user_can_submit_contact_inquiry(): void
    {
        $response = $this->post(route('contact.store'), [
            'name' => 'Patel Rajesh',
            'email' => 'rajesh.patel@example.com',
            'subject' => 'Inquiry regarding 2018 Batch Transcripts',
            'message' => 'Hello, I need guidance regarding obtaining official attested transcripts for WES evaluation.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('contact_inquiries', [
            'name' => 'Patel Rajesh',
            'email' => 'rajesh.patel@example.com',
            'subject' => 'Inquiry regarding 2018 Batch Transcripts',
            'status' => 'Unread',
        ]);
    }

    public function test_contact_inquiry_validation_rules(): void
    {
        $response = $this->post(route('contact.store'), [
            'name' => '',
            'email' => 'invalid-email',
            'subject' => '',
            'message' => '',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'subject', 'message']);
    }

    public function test_admin_can_view_inquiries_list(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_approved' => true]);

        ContactInquiry::create([
            'name' => 'Test Sender',
            'email' => 'sender@example.com',
            'subject' => 'Placement Question',
            'message' => 'When is the upcoming drive?',
            'status' => 'Unread',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.inquiries.index'));

        $response->assertStatus(200);
        $response->assertSee('Placement Question');
        $response->assertSee('sender@example.com');
    }

    public function test_non_admin_cannot_access_inquiries_management(): void
    {
        $student = User::factory()->create(['role' => 'student', 'is_approved' => true]);

        $response = $this->actingAs($student)->get(route('admin.inquiries.index'));

        $response->assertStatus(403);
    }

    public function test_admin_can_update_inquiry_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_approved' => true]);

        $inquiry = ContactInquiry::create([
            'name' => 'Test Sender',
            'email' => 'sender@example.com',
            'subject' => 'Alumni Card Verification',
            'message' => 'Please verify my card',
            'status' => 'Unread',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.inquiries.update', $inquiry->id), [
            'status' => 'Replied',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals('Replied', $inquiry->fresh()->status);
    }
}
