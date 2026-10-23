<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register_and_pending_approval(): void
    {
        $response = $this->post('/register', [
            'name' => 'Ketan Patel',
            'email' => 'ketan@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'alumni',
            'phone' => '9876543210',
            'entry_no' => 'KD19001',
            'degree' => 'Diploma',
            'department' => 'Computer Engineering',
            'year_joining' => 2019,
            'graduation_year' => 2022,
            'company' => 'Infosys',
            'designation' => 'Software Engineer',
            'work_industry' => 'IT',
            'skills' => 'PHP, Laravel, MySQL',
        ]);

        $this->assertGuest();
        $this->assertDatabaseHas('users', [
            'email' => 'ketan@example.com',
            'is_approved' => false,
            'company' => 'Infosys',
        ]);
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('status');
    }
}
