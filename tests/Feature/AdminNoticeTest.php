<?php

namespace Tests\Feature;

use App\Models\Notice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminNoticeTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $alumni;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'is_approved' => true,
        ]);

        $this->alumni = User::factory()->create([
            'role' => 'alumni',
            'is_approved' => true,
        ]);
    }

    public function test_admin_can_view_notices_in_admin_dashboard(): void
    {
        $notice = Notice::create([
            'title' => 'GTU Remedial Exam Circular AY26',
            'body' => 'All students must fill exam forms on student portal.',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('GTU Remedial Exam Circular AY26');
        $response->assertSee('Notice Board &amp; Circulars Control Center', false);
    }

    public function test_non_admin_cannot_store_or_update_or_toggle_notices(): void
    {
        $notice = Notice::create([
            'title' => 'Private Circular',
            'body' => 'Internal faculty circular.',
            'is_active' => true,
        ]);

        // Attempt store
        $this->actingAs($this->alumni)
            ->post(route('admin.notices.store'), [
                'title' => 'Hacked Circular',
                'body' => 'Spam content',
            ])
            ->assertForbidden();

        // Attempt update
        $this->actingAs($this->alumni)
            ->put(route('admin.notices.update', $notice), [
                'title' => 'Tampered Title',
                'body' => 'Tampered body',
            ])
            ->assertForbidden();

        // Attempt toggle
        $this->actingAs($this->alumni)
            ->patch(route('admin.notices.toggle', $notice))
            ->assertForbidden();

        // Attempt delete
        $this->actingAs($this->alumni)
            ->delete(route('admin.notices.destroy', $notice))
            ->assertForbidden();
    }

    public function test_admin_can_publish_a_new_notice(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.notices.store'), [
                'title' => 'First Sem Enrollment Notice 2026',
                'body' => 'Pay fees online before October 31.',
                'is_active' => '1',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Notice published successfully to the portal.');

        $this->assertDatabaseHas('notices', [
            'title' => 'First Sem Enrollment Notice 2026',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_update_an_existing_notice(): void
    {
        $notice = Notice::create([
            'title' => 'Draft Circular',
            'body' => 'Initial notes.',
            'is_active' => false,
        ]);

        $response = $this->actingAs($this->admin)
            ->put(route('admin.notices.update', $notice), [
                'title' => 'Final Approved Circular',
                'body' => 'Detailed GTU instructions with deadlines.',
                'is_active' => '1',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Notice updated successfully.');

        $notice->refresh();
        $this->assertEquals('Final Approved Circular', $notice->title);
        $this->assertEquals('Detailed GTU instructions with deadlines.', $notice->body);
        $this->assertTrue($notice->is_active);
    }

    public function test_admin_can_toggle_notice_visibility(): void
    {
        $notice = Notice::create([
            'title' => 'Annual Sports Meet Notice',
            'body' => 'Inter-department cricket and badminton registrations.',
            'is_active' => true,
        ]);

        // Toggle to hidden
        $response1 = $this->actingAs($this->admin)
            ->patch(route('admin.notices.toggle', $notice));

        $response1->assertRedirect();
        $notice->refresh();
        $this->assertFalse($notice->is_active);

        // Toggle back to active
        $response2 = $this->actingAs($this->admin)
            ->patch(route('admin.notices.toggle', $notice));

        $response2->assertRedirect();
        $notice->refresh();
        $this->assertTrue($notice->is_active);
    }

    public function test_hidden_notices_are_excluded_from_public_portal_feed(): void
    {
        $activeNotice = Notice::create([
            'title' => 'Visible Active Circular',
            'body' => 'Everyone should see this.',
            'is_active' => true,
        ]);

        $hiddenNotice = Notice::create([
            'title' => 'Archived Hidden Circular',
            'body' => 'This should be hidden from portal.',
            'is_active' => false,
        ]);

        // Check public welcome page
        $publicResponse = $this->get('/');
        $publicResponse->assertOk();
        $publicResponse->assertSee('Visible Active Circular');
        $publicResponse->assertDontSee('Archived Hidden Circular');

        // Check alumni dashboard
        $alumniResponse = $this->actingAs($this->alumni)->get(route('dashboard'));
        $alumniResponse->assertOk();
        $alumniResponse->assertSee('Visible Active Circular');
        $alumniResponse->assertDontSee('Archived Hidden Circular');
    }

    public function test_admin_can_delete_notice(): void
    {
        $notice = Notice::create([
            'title' => 'Expired Notice',
            'body' => 'Outdated content.',
            'is_active' => false,
        ]);

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.notices.destroy', $notice));

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Notice removed successfully.');

        $this->assertDatabaseMissing('notices', [
            'id' => $notice->id,
        ]);
    }

    public function test_admin_can_publish_notice_with_pdf_attachment(): void
    {
        Storage::fake('public');

        $pdf = UploadedFile::fake()->create('winter_exam_timetable.pdf', 1024, 'application/pdf');

        $response = $this->actingAs($this->admin)
            ->post(route('admin.notices.store'), [
                'title' => 'GTU Exam Timetable Notice',
                'body' => 'Download official schedule timetable attached below.',
                'is_active' => '1',
                'attachment' => $pdf,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Notice published successfully to the portal.');

        $notice = Notice::where('title', 'GTU Exam Timetable Notice')->first();
        $this->assertNotNull($notice);
        $this->assertNotNull($notice->attachment_path);
        $this->assertEquals('winter_exam_timetable.pdf', $notice->attachment_name);
        $this->assertTrue($notice->is_pdf);
        $this->assertFalse($notice->is_image);

        Storage::disk('public')->assertExists($notice->attachment_path);
    }

    public function test_admin_can_publish_notice_with_photo_attachment(): void
    {
        Storage::fake('public');

        $photo = UploadedFile::fake()->image('campus_drive_banner.jpg', 600, 400);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.notices.store'), [
                'title' => 'Clean Campus Drive Announcement',
                'body' => 'Volunteers needed for campus green drive.',
                'is_active' => '1',
                'attachment' => $photo,
            ]);

        $response->assertRedirect();

        $notice = Notice::where('title', 'Clean Campus Drive Announcement')->first();
        $this->assertNotNull($notice);
        $this->assertTrue($notice->is_image);
        $this->assertFalse($notice->is_pdf);

        Storage::disk('public')->assertExists($notice->attachment_path);
    }

    public function test_admin_can_remove_attachment_on_update(): void
    {
        Storage::fake('public');

        $photo = UploadedFile::fake()->image('old_poster.png');
        $storedPath = $photo->store('notices', 'public');

        $notice = Notice::create([
            'title' => 'Notice With File',
            'body' => 'File attached.',
            'attachment_path' => $storedPath,
            'attachment_name' => 'old_poster.png',
            'is_active' => true,
        ]);

        Storage::disk('public')->assertExists($storedPath);

        $response = $this->actingAs($this->admin)
            ->put(route('admin.notices.update', $notice), [
                'title' => 'Notice With File Removed',
                'body' => 'No file attached anymore.',
                'remove_attachment' => '1',
            ]);

        $response->assertRedirect();
        $notice->refresh();

        $this->assertNull($notice->attachment_path);
        $this->assertNull($notice->attachment_name);
        Storage::disk('public')->assertMissing($storedPath);
    }

    public function test_notice_attachment_indicator_and_modal_render_in_portal(): void
    {
        $notice = Notice::create([
            'title' => 'Official Circular with PDF',
            'body' => 'Check attached document.',
            'attachment_path' => 'notices/sample.pdf',
            'attachment_name' => 'exam_circular.pdf',
            'is_active' => true,
        ]);

        $response = $this->get('/');
        $response->assertOk();
        $response->assertSee('Official Circular with PDF');
        $response->assertSee('PDF');
    }
}
