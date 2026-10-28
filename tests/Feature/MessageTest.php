<?php

namespace Tests\Feature;

use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class MessageTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_inbox(): void
    {
        $response = $this->get(route('messages.inbox'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_inbox_empty_state(): void
    {
        $user = User::factory()->create(['is_approved' => true, 'role' => 'alumni']);

        $response = $this->actingAs($user)->get(route('messages.inbox'));

        $response->assertStatus(200);
        $response->assertSee('Messages &amp; Campus Inbox', false);
        $response->assertSee('KDP Campus Messenger');
        $response->assertSee('No Active Conversations');
        $response->assertSee('Start a New Conversation');
    }

    public function test_inbox_redirects_to_first_conversation_if_threads_exist(): void
    {
        $sender = User::factory()->create(['name' => 'Ketan Patel', 'is_approved' => true, 'role' => 'alumni']);
        $recipient = User::factory()->create(['name' => 'Prof. Modi', 'is_approved' => true, 'role' => 'faculty']);

        Message::create([
            'sender_id' => $recipient->id,
            'recipient_id' => $sender->id,
            'message' => 'Hello Ketan, welcome to the department!',
            'is_read' => false,
        ]);

        $response = $this->actingAs($sender)->get(route('messages.inbox'));

        $response->assertRedirect(route('messages.inbox', $recipient->id));
    }

    public function test_user_can_view_conversation_and_messages_are_marked_read(): void
    {
        $sender = User::factory()->create(['name' => 'Student Rahul', 'is_approved' => true, 'role' => 'student']);
        $alumni = User::factory()->create(['name' => 'Alumni Hardik', 'is_approved' => true, 'role' => 'alumni']);

        $message = Message::create([
            'sender_id' => $alumni->id,
            'recipient_id' => $sender->id,
            'message' => 'Glad to mentor you for D2D admission.',
            'is_read' => false,
        ]);

        $response = $this->actingAs($sender)->get(route('messages.inbox', $alumni->id));

        $response->assertStatus(200);
        $response->assertSee('Alumni Hardik');
        $response->assertSee('Glad to mentor you for D2D admission.');

        // Assert message was automatically marked as read
        $this->assertTrue($message->fresh()->is_read);
    }

    public function test_user_can_send_message_via_standard_post(): void
    {
        Mail::fake();

        $sender = User::factory()->create(['name' => 'Student Juned', 'is_approved' => true, 'role' => 'student']);
        $faculty = User::factory()->create(['name' => 'Prof. Shahejad Sindhi', 'is_approved' => true, 'role' => 'faculty']);

        $response = $this->actingAs($sender)->post(route('alumni.message', $faculty->id), [
            'message' => 'Sir, when is the lab submission deadline?',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('messages', [
            'sender_id' => $sender->id,
            'recipient_id' => $faculty->id,
            'message' => 'Sir, when is the lab submission deadline?',
            'is_read' => false,
        ]);
    }

    public function test_user_can_send_message_via_ajax_json(): void
    {
        Mail::fake();

        $sender = User::factory()->create(['name' => 'Ketan Patel', 'is_approved' => true, 'role' => 'alumni']);
        $recipient = User::factory()->create(['name' => 'Prof. Shahejad Sindhi', 'is_approved' => true, 'role' => 'faculty']);

        $response = $this->actingAs($sender)
            ->withHeaders(['Accept' => 'application/json'])
            ->postJson(route('alumni.message', $recipient->id), [
                'message' => 'Great seeing you back on campus!',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => [
                'text' => 'Great seeing you back on campus!',
                'sender_id' => $sender->id,
                'recipient_id' => $recipient->id,
            ],
        ]);

        $this->assertDatabaseHas('messages', [
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'message' => 'Great seeing you back on campus!',
        ]);
    }

    public function test_message_validation_rejects_empty_message(): void
    {
        $sender = User::factory()->create(['is_approved' => true]);
        $recipient = User::factory()->create(['is_approved' => true]);

        $response = $this->actingAs($sender)->post(route('alumni.message', $recipient->id), [
            'message' => '',
        ]);

        $response->assertSessionHasErrors('message');
    }

    public function test_message_cannot_be_sent_to_unapproved_user(): void
    {
        $sender = User::factory()->create(['is_approved' => true]);
        $unapproved = User::factory()->create(['is_approved' => false]);

        $response = $this->actingAs($sender)->post(route('alumni.message', $unapproved->id), [
            'message' => 'Hello',
        ]);

        $response->assertStatus(404);
    }

    public function test_user_unread_messages_count_helper_and_navbar_notification_badge(): void
    {
        $user = User::factory()->create(['name' => 'Prof. Shahejad Sindhi', 'is_approved' => true, 'role' => 'faculty']);
        $sender = User::factory()->create(['name' => 'Alumni Hardik', 'is_approved' => true, 'role' => 'alumni']);

        $this->assertEquals(0, $user->unreadMessagesCount());

        // Create 2 unread messages
        Message::create([
            'sender_id' => $sender->id,
            'recipient_id' => $user->id,
            'message' => 'First unread message',
            'is_read' => false,
        ]);
        Message::create([
            'sender_id' => $sender->id,
            'recipient_id' => $user->id,
            'message' => 'Second unread message',
            'is_read' => false,
        ]);

        $this->assertEquals(2, $user->fresh()->unreadMessagesCount());

        // Verify navbar renders +2 badge
        $response = $this->actingAs($user)->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertSee('+2');
        $response->assertSee('Messages &amp; Inbox', false);
    }

    public function test_navbar_notification_badge_shows_plus_nine_when_unread_count_exceeds_nine(): void
    {
        $user = User::factory()->create(['name' => 'Prof. Shahejad Sindhi', 'is_approved' => true, 'role' => 'faculty']);
        $sender = User::factory()->create(['name' => 'Alumni Hardik', 'is_approved' => true, 'role' => 'alumni']);

        for ($i = 0; $i < 12; $i++) {
            Message::create([
                'sender_id' => $sender->id,
                'recipient_id' => $user->id,
                'message' => "Message {$i}",
                'is_read' => false,
            ]);
        }

        $this->assertEquals(12, $user->fresh()->unreadMessagesCount());

        $response = $this->actingAs($user)->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertSee('9+');
    }
}
