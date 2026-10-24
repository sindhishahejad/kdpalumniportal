<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Event;
use App\Models\EventRsvp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class EventTest extends TestCase
{
    use RefreshDatabase;

    public function test_events_page_renders_successfully(): void
    {
        Event::create([
            'title' => 'Annual Alumni Reunion 2026',
            'category' => 'REUNION',
            'event_date' => now()->addDays(10)->toDateString(),
            'time_display' => '10:00 AM - 4:00 PM',
            'description' => 'Gathering of KD Polytechnic alumni from all batches.',
        ]);

        $response = $this->get('/events');
        $response->assertStatus(200);
        $response->assertSee('Campus Activities, Seminars &amp; Alumni Events', false);
        $response->assertSee('Annual Alumni Reunion 2026', false);
        $response->assertSee('REUNION', false);
    }

    public function test_past_events_are_displayed_in_past_archives(): void
    {
        Event::create([
            'title' => 'Historic TechFest 2024',
            'category' => 'TECHNICAL FEST',
            'event_date' => now()->subMonths(6)->toDateString(),
            'time_display' => '9:00 AM - 5:00 PM',
            'description' => 'State level technical symposium with 500+ participants.',
        ]);

        $response = $this->get('/events');
        $response->assertStatus(200);
        $response->assertSee('Historic TechFest 2024', false);
        $response->assertSee('Past Activities &amp; Archives', false);
    }

    public function test_event_show_page_renders(): void
    {
        $user = User::factory()->create(['role' => 'alumni', 'is_approved' => true]);

        $event = Event::create([
            'title' => 'AI and Robotics Workshop 2026',
            'category' => 'WORKSHOP',
            'event_date' => now()->addDays(15)->toDateString(),
            'time_display' => '11:00 AM - 2:00 PM',
            'description' => 'Hands on micro-controller and robotics lab session.',
        ]);

        $response = $this->actingAs($user)->get(route('events.show', $event));
        $response->assertStatus(200);
        $response->assertSee('AI and Robotics Workshop 2026', false);
        $response->assertSee('WORKSHOP', false);
        $response->assertSee('Hands on micro-controller', false);
    }

    public function test_non_admin_cannot_create_or_update_or_delete_event(): void
    {
        $user = User::factory()->create(['role' => 'alumni', 'is_approved' => true]);

        $event = Event::create([
            'title' => 'Polytechnic Sports Day',
            'category' => 'SPORTS',
            'event_date' => now()->addDays(5)->toDateString(),
            'time_display' => '8:00 AM',
            'description' => 'Inter-department cricket and athletics tournament.',
        ]);

        // Non-admin attempt store
        $this->actingAs($user)
             ->post(route('events.store'), [
                 'title' => 'Unauthorized Event',
                 'category' => 'SEMINAR',
                 'event_date' => now()->addDays(10)->toDateString(),
                 'time_display' => '10:00 AM',
             ])
             ->assertStatus(403);

        // Non-admin attempt update
        $this->actingAs($user)
             ->put(route('events.update', $event), [
                 'title' => 'Hacked Event Title',
                 'category' => 'SEMINAR',
                 'event_date' => now()->addDays(10)->toDateString(),
                 'time_display' => '10:00 AM',
             ])
             ->assertStatus(403);

        // Non-admin attempt delete
        $this->actingAs($user)
             ->delete(route('events.destroy', $event))
             ->assertStatus(403);

        $this->assertDatabaseHas('events', ['id' => $event->id, 'title' => 'Polytechnic Sports Day']);
    }

    public function test_admin_can_schedule_event(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_approved' => true]);

        $response = $this->actingAs($admin)
                         ->post(route('events.store'), [
                             'title' => 'State Level Solar Energy Seminar',
                             'category' => 'Seminar',
                             'event_date' => now()->addDays(20)->toDateString(),
                             'time_display' => '10:00 AM - 1:00 PM',
                             'description' => 'Guest lecture by alumni industry leaders.',
                         ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('events', [
            'title' => 'State Level Solar Energy Seminar',
            'category' => 'SEMINAR',
        ]);
    }

    public function test_admin_can_update_event(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_approved' => true]);

        $event = Event::create([
            'title' => 'Old Title TechFest',
            'category' => 'WORKSHOP',
            'event_date' => now()->addDays(10)->toDateString(),
            'time_display' => '10:00 AM',
            'description' => 'Initial draft description.',
        ]);

        $response = $this->actingAs($admin)
                         ->put(route('events.update', $event), [
                             'title' => 'Updated Grand TechFest 2026',
                             'category' => 'Technical Fest',
                             'event_date' => now()->addDays(12)->toDateString(),
                             'time_display' => '9:30 AM - 4:30 PM',
                             'description' => 'Updated comprehensive details.',
                         ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'title' => 'Updated Grand TechFest 2026',
            'category' => 'TECHNICAL FEST',
            'time_display' => '9:30 AM - 4:30 PM',
        ]);
    }

    public function test_admin_can_delete_event(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_approved' => true]);

        $event = Event::create([
            'title' => 'Cancelled Seminar',
            'category' => 'SEMINAR',
            'event_date' => now()->addDays(8)->toDateString(),
            'time_display' => '2:00 PM',
        ]);

        $response = $this->actingAs($admin)
                         ->delete(route('events.destroy', $event));

        $response->assertRedirect();
        $this->assertDatabaseMissing('events', ['id' => $event->id]);
    }

    public function test_user_can_rsvp_and_receive_digital_entry_pass(): void
    {
        $user = User::factory()->create(['role' => 'alumni', 'is_approved' => true]);

        $event = Event::create([
            'title' => 'Silver Jubilee Alumni Reunion',
            'category' => 'REUNION',
            'event_date' => now()->addDays(30)->toDateString(),
            'time_display' => '10:00 AM - 5:00 PM',
            'description' => 'Grand celebration of K. D. Polytechnic Silver Jubilee batches.',
        ]);

        $response = $this->actingAs($user)->get(route('events.rsvp', $event));

        $this->assertDatabaseHas('event_rsvps', [
            'event_id' => $event->id,
            'user_id' => $user->id,
        ]);

        $rsvp = EventRsvp::where('event_id', $event->id)->where('user_id', $user->id)->first();
        $this->assertNotNull($rsvp);
        $this->assertStringStartsWith('KDP-PASS-', $rsvp->ticket_code);

        $response->assertRedirect(route('events.ticket', $rsvp->id));

        // Test ticket view
        $ticketResponse = $this->actingAs($user)->get(route('events.ticket', $rsvp->id));
        $ticketResponse->assertStatus(200);
        $ticketResponse->assertSee($rsvp->ticket_code, false);
        $ticketResponse->assertSee($user->name, false);
        $ticketResponse->assertSee('Official Digital Event Entry Pass', false);
    }
}
