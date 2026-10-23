<?php

namespace Tests\Feature;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AlumniDirectoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_approved_alumni_are_displayed_in_directory_and_unapproved_are_hidden(): void
    {
        $viewer = User::factory()->create([
            'role' => 'student',
            'is_approved' => true,
        ]);

        $approvedAlum = User::factory()->create([
            'name' => 'Ketan Patel',
            'role' => 'alumni',
            'is_approved' => true,
            'department' => 'Computer Engineering',
        ]);
        Profile::create([
            'user_id' => $approvedAlum->id,
            'graduation_year' => '2022',
            'department' => 'Computer Engineering',
        ]);

        $unapprovedAlum = User::factory()->create([
            'name' => 'Hidden Alumni',
            'role' => 'alumni',
            'is_approved' => false,
            'department' => 'Computer Engineering',
        ]);
        Profile::create([
            'user_id' => $unapprovedAlum->id,
            'graduation_year' => '2022',
            'department' => 'Computer Engineering',
        ]);

        $response = $this->actingAs($viewer)->get(route('alumni.index'));

        $response->assertStatus(200);
        $response->assertSee('Ketan Patel');
        $response->assertDontSee('Hidden Alumni');
    }

    public function test_batchmates_filter_returns_only_alumni_sharing_graduation_year_and_department(): void
    {
        $me = User::factory()->create([
            'name' => 'Hardik Prajapati',
            'role' => 'alumni',
            'is_approved' => true,
            'department' => 'Civil Engineering',
        ]);
        Profile::create([
            'user_id' => $me->id,
            'graduation_year' => '2021',
            'department' => 'Civil Engineering',
        ]);

        $myBatchmate = User::factory()->create([
            'name' => 'Nilesh Solanki',
            'role' => 'alumni',
            'is_approved' => true,
            'department' => 'Civil Engineering',
        ]);
        Profile::create([
            'user_id' => $myBatchmate->id,
            'graduation_year' => '2021',
            'department' => 'Civil Engineering',
        ]);

        $otherAlum = User::factory()->create([
            'name' => 'Pooja Dave',
            'role' => 'alumni',
            'is_approved' => true,
            'department' => 'Mechanical Engineering',
        ]);
        Profile::create([
            'user_id' => $otherAlum->id,
            'graduation_year' => '2018',
            'department' => 'Mechanical Engineering',
        ]);

        $response = $this->actingAs($me)->get(route('alumni.index', ['batchmates' => 1]));

        $response->assertStatus(200);
        $response->assertSee('Nilesh Solanki');
        $response->assertDontSee('Pooja Dave');
        $response->assertSee('My Batchmates');
    }

    public function test_location_filter_filters_alumni_by_city(): void
    {
        $viewer = User::factory()->create([
            'role' => 'alumni',
            'is_approved' => true,
        ]);

        $patanAlum = User::factory()->create([
            'name' => 'Bhavik Parmar',
            'role' => 'alumni',
            'is_approved' => true,
        ]);
        Profile::create([
            'user_id' => $patanAlum->id,
            'location' => 'Patan, Gujarat',
        ]);

        $mumbaiAlum = User::factory()->create([
            'name' => 'Rohit Sharma',
            'role' => 'alumni',
            'is_approved' => true,
        ]);
        Profile::create([
            'user_id' => $mumbaiAlum->id,
            'location' => 'Mumbai, Maharashtra',
        ]);

        $response = $this->actingAs($viewer)->get(route('alumni.index', ['location' => 'Patan']));

        $response->assertStatus(200);
        $response->assertSee('Bhavik Parmar');
        $response->assertDontSee('Rohit Sharma');
    }

    public function test_keyword_search_matches_company_and_name(): void
    {
        $viewer = User::factory()->create([
            'role' => 'student',
            'is_approved' => true,
        ]);

        $tcsAlum = User::factory()->create([
            'name' => 'Vikas Joshi',
            'role' => 'alumni',
            'is_approved' => true,
        ]);
        Profile::create([
            'user_id' => $tcsAlum->id,
            'current_company' => 'Tata Consultancy Services',
        ]);

        $isroAlum = User::factory()->create([
            'name' => 'Anil Trivedi',
            'role' => 'alumni',
            'is_approved' => true,
        ]);
        Profile::create([
            'user_id' => $isroAlum->id,
            'current_company' => 'ISRO Space Center',
        ]);

        $response = $this->actingAs($viewer)->get(route('alumni.index', ['search' => 'ISRO']));

        $response->assertStatus(200);
        $response->assertSee('Anil Trivedi');
        $response->assertDontSee('Vikas Joshi');
    }

    public function test_contact_privacy_is_respected_for_non_admins(): void
    {
        $student = User::factory()->create([
            'role' => 'student',
            'is_approved' => true,
        ]);

        $admin = User::factory()->create([
            'role' => 'admin',
            'is_approved' => true,
        ]);

        $alum = User::factory()->create([
            'name' => 'Private Alumni',
            'email' => 'private_alum@example.com',
            'phone' => '9988776655',
            'role' => 'alumni',
            'is_approved' => true,
        ]);
        Profile::create([
            'user_id' => $alum->id,
            'is_email_public' => false,
            'is_phone_public' => false,
        ]);

        // Student should see "Hidden" instead of email/phone
        $studentResponse = $this->actingAs($student)->get(route('alumni.index'));
        $studentResponse->assertStatus(200);
        $studentResponse->assertSee('Hidden');
        $studentResponse->assertDontSee('private_alum@example.com');

        // Admin should see raw email and phone
        $adminResponse = $this->actingAs($admin)->get(route('alumni.index'));
        $adminResponse->assertStatus(200);
        $adminResponse->assertSee('private_alum@example.com');
        $adminResponse->assertSee('9988776655');
    }
}
