<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Sign In to Portal');
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create([
            'is_approved' => true,
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'is_approved' => true,
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_unapproved_users_cannot_authenticate(): void
    {
        $user = User::factory()->unapproved()->create([
            'email' => 'pending_user@example.com',
            'password' => 'password',
        ]);

        $response = $this->post('/login', [
            'email' => 'pending_user@example.com',
            'password' => 'password',
        ]);

        // User must be denied authentication and remain guest
        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create([
            'is_approved' => true,
        ]);

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }

    public function test_google_oauth_redirect_handles_unconfigured_credentials_gracefully(): void
    {
        config([
            'services.google.client_id' => null,
            'services.google.client_secret' => null,
        ]);

        $response = $this->get(route('auth.google'));

        // When unconfigured, should redirect back to login with friendly error message instead of 500 crash
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error');
    }

    public function test_login_with_email_rejects_unapproved_accounts(): void
    {
        $unapproved = User::factory()->unapproved()->create([
            'email' => 'unapproved_dev@example.com',
        ]);

        $response = $this->post(route('login.email'), [
            'email' => 'unapproved_dev@example.com',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }
}
