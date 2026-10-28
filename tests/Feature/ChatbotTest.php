<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ChatbotTest extends TestCase
{
    use RefreshDatabase;

    public function test_chatbot_page_renders_successfully(): void
    {
        $response = $this->get(route('chatbot.index'));

        $response->assertStatus(200);
        $response->assertSee('KDP Mitra');
        $response->assertSee('AI Assistant');
        $response->assertSee('Groq');
    }

    public function test_chatbot_message_requires_message_field(): void
    {
        $response = $this->postJson(route('chatbot.message'), []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['message']);
    }

    public function test_chatbot_fallback_handles_query_without_api_key(): void
    {
        Config::set('services.groq.api_key', null);

        $response = $this->postJson(route('chatbot.message'), [
            'message' => 'How do I apply for official transcripts and bonafide certificates?'
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertStringContainsString('Document & Transcript Services', $response->json('reply'));
        $this->assertStringContainsString('/documents', $response->json('reply'));

        // Check that session recorded conversation
        $this->assertTrue(session()->has('kdp_chat_history'));
        $history = session()->get('kdp_chat_history');
        $this->assertCount(2, $history); // 1 user + 1 assistant
        $this->assertEquals('user', $history[0]['role']);
        $this->assertEquals('assistant', $history[1]['role']);
    }

    public function test_chatbot_handles_placement_query(): void
    {
        Config::set('services.groq.api_key', null);

        $response = $this->postJson(route('chatbot.message'), [
            'message' => 'Tell me about campus placements and recruiters'
        ]);

        $response->assertStatus(200);
        $this->assertStringContainsString('Training & Placement', $response->json('reply'));
        $this->assertStringContainsString('/placement', $response->json('reply'));
    }

    public function test_chatbot_handles_gujarati_query(): void
    {
        Config::set('services.groq.api_key', null);

        $response = $this->postJson(route('chatbot.message'), [
            'message' => 'કેમ છો'
        ]);

        $response->assertStatus(200);
        $this->assertStringContainsString('નમસ્તે', $response->json('reply'));
        $this->assertStringContainsString('કિલાચંદ', $response->json('reply'));
    }

    public function test_chatbot_clears_history(): void
    {
        session()->put('kdp_chat_history', [
            ['role' => 'user', 'content' => 'Hello', 'time' => '10:00 AM'],
            ['role' => 'assistant', 'content' => 'Hi there!', 'time' => '10:00 AM']
        ]);

        $this->assertTrue(session()->has('kdp_chat_history'));

        $response = $this->deleteJson(route('chatbot.clear'));

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertFalse(session()->has('kdp_chat_history'));
    }

    public function test_chatbot_calls_groq_api_when_key_is_provided(): void
    {
        Config::set('services.groq.api_key', 'gsk_mock_test_key_123456');
        Config::set('services.groq.model', 'llama-3.3-70b-versatile');

        Http::fake([
            'https://api.groq.com/*' => Http::response([
                'id' => 'chatcmpl-test-123',
                'object' => 'chat.completion',
                'created' => time(),
                'model' => 'llama-3.3-70b-versatile',
                'choices' => [
                    [
                        'index' => 0,
                        'message' => [
                            'role' => 'assistant',
                            'content' => 'Hello! I am KDP Mitra powered by Meta LLaMA 3.3 on Groq Cloud.'
                        ],
                        'finish_reason' => 'stop'
                    ]
                ]
            ], 200)
        ]);

        $user = User::factory()->create([
            'name' => 'Shahejad Sindhi',
            'role' => 'alumni',
            'department' => 'Computer Engineering',
        ]);

        $response = $this->actingAs($user)->postJson(route('chatbot.message'), [
            'message' => 'Tell me about the computer engineering department.'
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'reply' => 'Hello! I am KDP Mitra powered by Meta LLaMA 3.3 on Groq Cloud.',
            'source' => 'groq',
            'model' => 'llama-3.3-70b-versatile',
        ]);
    }

    public function test_chatbot_redirects_gtu_pyq_query_to_official_papers_portal(): void
    {
        Config::set('services.groq.api_key', null);

        $response = $this->postJson(route('chatbot.message'), [
            'message' => 'Where can I get gtu diploma pyq question papers?'
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertStringContainsString('https://oldquestionpapers.gtu.ac.in/', $response->json('reply'));
        $this->assertStringContainsString('GTU', $response->json('reply'));
    }

    public function test_chatbot_redirects_gtu_results_query_to_official_results_portal(): void
    {
        Config::set('services.groq.api_key', null);

        $response = $this->postJson(route('chatbot.message'), [
            'message' => 'When is the gtu diploma result date and where can I check my marks?'
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertStringContainsString('https://www.gtu.ac.in/result.aspx', $response->json('reply'));
        $this->assertStringContainsString('student.gtu.ac.in', $response->json('reply'));
    }

    public function test_chatbot_refuses_non_educational_entertainment_or_jokes(): void
    {
        Config::set('services.groq.api_key', null);

        $response = $this->postJson(route('chatbot.message'), [
            'message' => 'tell me a funny joke about movies'
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertStringContainsString('KDP Academic & Technical Counseling Scope', $response->json('reply'));
        $this->assertStringContainsString('I cannot assist with entertainment, jokes, or non-educational topics', $response->json('reply'));
    }
}
