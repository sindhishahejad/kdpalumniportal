<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Lab;
use App\Models\Resource;
use App\Models\ProjectPitch;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FacultyDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_faculty_member_can_access_dashboard_with_all_metrics(): void
    {
        $faculty = User::factory()->create([
            'role' => 'faculty',
            'is_approved' => true,
        ]);

        Lab::create([
            'name' => 'Computer Networks Lab',
            'department' => 'Computer Engineering',
            'faculty_in_charge' => 'Prof. Patel',
            'location' => 'Block B, 2nd Floor',
            'equipment_list' => 'Cisco Routers, 30 PCs',
            'description' => 'Dedicated networking lab for diploma students.',
        ]);

        $student = User::factory()->create(['role' => 'student', 'is_approved' => true]);

        ProjectPitch::create([
            'user_id' => $student->id,
            'title' => 'Smart Campus Attendance System',
            'description' => 'RFID & Face recognition attendance for diploma classes.',
            'tech_stack' => 'ESP32, Python, OpenCV',
            'assistance_needed' => 'Faculty sponsorship and RFID sensors',
            'status' => 'open',
        ]);

        $response = $this->actingAs($faculty)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Faculty Dashboard');
        $response->assertSee('Academic Resource Vault');
        $response->assertSee('Alumni Directory');
        $response->assertSee('Campus Events');
        $response->assertSee('Department Labs');
        $response->assertSee('Project Incubation');
        $response->assertSee('Smart Campus Attendance System');
    }
}
