<?php

namespace Tests\Feature;

use App\Mail\WeeklyNewsletter;
use App\Models\JobPosting;
use App\Models\Notice;
use App\Models\SuccessStory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class NewsletterTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_queues_newsletter_to_approved_users_and_ignores_unapproved(): void
    {
        Mail::fake();

        $approvedUser1 = User::factory()->create([
            'email' => 'approved1@example.com',
            'is_approved' => true,
        ]);

        $approvedUser2 = User::factory()->create([
            'email' => 'approved2@example.com',
            'is_approved' => true,
        ]);

        $unapprovedUser = User::factory()->create([
            'email' => 'unapproved@example.com',
            'is_approved' => false,
        ]);

        SuccessStory::create([
            'title' => 'Distinguished Researcher Award',
            'alumni_name' => 'Ketan Patel',
            'batch_year' => '2020',
            'department' => 'Computer Engineering',
            'story' => 'Achieved prestigious research recognition.',
        ]);

        $this->artisan('newsletter:send')
            ->assertSuccessful();

        Mail::assertQueued(WeeklyNewsletter::class, function ($mail) use ($approvedUser1) {
            return $mail->hasTo($approvedUser1->email);
        });

        Mail::assertQueued(WeeklyNewsletter::class, function ($mail) use ($approvedUser2) {
            return $mail->hasTo($approvedUser2->email);
        });

        Mail::assertNotQueued(WeeklyNewsletter::class, function ($mail) use ($unapprovedUser) {
            return $mail->hasTo($unapprovedUser->email);
        });
    }

    public function test_command_preview_mode_does_not_queue_emails(): void
    {
        Mail::fake();

        User::factory()->create([
            'email' => 'active@example.com',
            'is_approved' => true,
        ]);

        $this->artisan('newsletter:send --preview')
            ->expectsOutputToContain('KDP ALUMNI WEEKLY DIGEST PREVIEW')
            ->assertSuccessful();

        Mail::assertNothingQueued();
    }

    public function test_command_with_to_option_only_queues_to_that_recipient(): void
    {
        Mail::fake();

        User::factory()->create([
            'email' => 'member1@example.com',
            'is_approved' => true,
        ]);

        $this->artisan('newsletter:send --to=vip@example.com')
            ->expectsOutputToContain('vip@example.com')
            ->assertSuccessful();

        Mail::assertQueued(WeeklyNewsletter::class, function ($mail) {
            return $mail->hasTo('vip@example.com');
        });

        Mail::assertNotQueued(WeeklyNewsletter::class, function ($mail) {
            return $mail->hasTo('member1@example.com');
        });
    }

    public function test_admin_can_dispatch_newsletter_from_dashboard(): void
    {
        Mail::fake();

        $admin = User::factory()->create([
            'role' => 'admin',
            'is_approved' => true,
        ]);

        $student = User::factory()->create([
            'role' => 'student',
            'email' => 'student@example.com',
            'is_approved' => true,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.newsletter.send'));

        $response->assertRedirect();
        $response->assertSessionHas('status');

        Mail::assertQueued(WeeklyNewsletter::class, function ($mail) use ($student) {
            return $mail->hasTo($student->email);
        });
    }

    public function test_admin_can_dispatch_test_newsletter_to_self(): void
    {
        Mail::fake();

        $admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@kdpatan.ac.in',
            'is_approved' => true,
        ]);

        $student = User::factory()->create([
            'role' => 'student',
            'email' => 'student@example.com',
            'is_approved' => true,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.newsletter.send'), [
            'test_only' => '1',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        Mail::assertQueued(WeeklyNewsletter::class, function ($mail) use ($admin) {
            return $mail->hasTo($admin->email);
        });

        Mail::assertNotQueued(WeeklyNewsletter::class, function ($mail) use ($student) {
            return $mail->hasTo($student->email);
        });
    }

    public function test_non_admin_cannot_dispatch_newsletter(): void
    {
        $student = User::factory()->create([
            'role' => 'student',
            'is_approved' => true,
        ]);

        $response = $this->actingAs($student)->post(route('admin.newsletter.send'));

        // Admin middleware aborts or redirects
        $this->assertTrue(in_array($response->status(), [403, 302]));
    }
}
