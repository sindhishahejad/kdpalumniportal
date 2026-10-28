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

    public function test_current_diploma_student_can_register_without_professional_details(): void
    {
        $response = $this->post('/register', [
            'name' => 'Dipen Prajapati',
            'email' => 'dipen@student.kdppatan.ac.in',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'student',
            'phone' => '9876500000',
            'entry_no' => '226220107005',
            'degree' => 'Diploma in Computer Engineering',
            'department' => 'Computer Engineering',
            'year_joining' => 2022,
            'graduation_year' => 2025,
            'company' => null,
            'designation' => null,
            'work_industry' => null,
            'skills' => null,
        ]);

        $this->assertGuest();
        $this->assertDatabaseHas('users', [
            'email' => 'dipen@student.kdppatan.ac.in',
            'role' => 'student',
            'is_approved' => false,
            'company' => null,
            'designation' => null,
        ]);
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('status');
    }

    public function test_alumni_registration_fails_if_professional_details_missing(): void
    {
        $response = $this->post('/register', [
            'name' => 'Alumni Missing Company',
            'email' => 'alumni_missing@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'alumni',
            'phone' => '9876511111',
            'entry_no' => 'KD18002',
            'degree' => 'Diploma in Mechanical',
            'department' => 'Mechanical Engineering',
            'year_joining' => 2018,
            'graduation_year' => 2021,
            // company, designation omitted
        ]);

        $response->assertSessionHasErrors(['company', 'designation', 'work_industry', 'skills']);
        $this->assertDatabaseMissing('users', [
            'email' => 'alumni_missing@example.com',
        ]);
    }
}
