<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
        $response->assertSee('Campus & Alumni Events', false);
        $response->assertSee('Annual Alumni Reunion 2026', false);
        $response->assertSee('REUNION', false);
    }
}
