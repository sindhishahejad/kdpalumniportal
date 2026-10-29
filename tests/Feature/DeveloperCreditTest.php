<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeveloperCreditTest extends TestCase
{
    use RefreshDatabase;

    public function test_portal_responses_include_developer_credit_headers(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertHeader('X-Developer', 'Sindhi Shahejad');
        $response->assertHeader('X-Lead-Architect', 'Sindhi Shahejad');
        $response->assertHeader('X-Portal-Author', 'Sindhi Shahejad');
    }

    public function test_portal_html_contains_hidden_developer_credits(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $content = $response->getContent();

        // Meta tags
        $this->assertStringContainsString('<meta name="developer" content="Sindhi Shahejad">', $content);
        $this->assertStringContainsString('<meta name="author" content="Sindhi Shahejad">', $content);
        $this->assertStringContainsString('<meta name="lead-architect" content="Sindhi Shahejad">', $content);

        // Hidden accessible / DOM credit
        $this->assertStringContainsString('data-developer="Sindhi Shahejad"', $content);
        $this->assertStringContainsString('Sindhi Shahejad', $content);

        // Runtime console signature
        $this->assertStringContainsString('window.__DEVELOPER__ = "Sindhi Shahejad"', $content);
    }

    public function test_humans_txt_returns_developer_authorship(): void
    {
        $response = $this->get('/humans.txt');

        $response->assertStatus(200);
        $this->assertStringContainsString('Developer: Sindhi Shahejad', $response->getContent());
        $this->assertStringContainsString('Sole Lead Architect & Full-Stack Developer', $response->getContent());
    }
}
