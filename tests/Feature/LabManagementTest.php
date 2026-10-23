<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Lab;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LabManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_any_authenticated_user_can_view_labs_and_details(): void
    {
        $student = User::factory()->create(['role' => 'student', 'is_approved' => true]);

        $lab = Lab::create([
            'name' => 'Embedded Systems Lab',
            'department' => 'Computer Engineering',
            'faculty_in_charge' => 'Prof. H. Patel',
            'location' => 'Room 201',
            'equipment_list' => 'Raspberry Pi 4, Arduino Uno, Keil IDE',
            'description' => 'Dedicated hardware lab for diploma students.',
        ]);

        $indexResponse = $this->actingAs($student)->get('/labs');
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Embedded Systems Lab');

        $showResponse = $this->actingAs($student)->get("/labs/{$lab->id}");
        $showResponse->assertStatus(200);
        $showResponse->assertSee('Embedded Systems Lab');
        $showResponse->assertSee('Raspberry Pi 4');
    }

    public function test_student_cannot_create_or_modify_labs(): void
    {
        $student = User::factory()->create(['role' => 'student', 'is_approved' => true]);

        // Student cannot access create form
        $createResponse = $this->actingAs($student)->get('/labs-manage/create');
        $createResponse->assertStatus(403);

        // Student cannot store new lab
        $storeResponse = $this->actingAs($student)->post('/labs-manage', [
            'name' => 'Student Hacker Lab',
            'department' => 'Computer Engineering',
            'faculty_in_charge' => 'None',
            'location' => 'Cafeteria',
            'equipment_list' => 'Laptops',
            'description' => 'Unauthorized lab entry',
        ]);
        $storeResponse->assertStatus(403);

        // Student cannot delete an existing lab
        $lab = Lab::create([
            'name' => 'Physics Lab',
            'department' => 'Science & Humanities',
            'faculty_in_charge' => 'Prof. Trivedi',
            'location' => 'Room 101',
            'equipment_list' => 'Vernier Calipers',
            'description' => 'Basic physics experiments',
        ]);

        $deleteResponse = $this->actingAs($student)->delete("/labs/{$lab->id}");
        $deleteResponse->assertStatus(403);
    }

    public function test_faculty_member_can_create_update_and_delete_lab(): void
    {
        $faculty = User::factory()->create(['role' => 'faculty', 'is_approved' => true]);

        // 1. Create Lab
        $storeResponse = $this->actingAs($faculty)->post('/labs-manage', [
            'name' => 'Cloud Computing Lab',
            'department' => 'Computer Engineering',
            'faculty_in_charge' => 'Prof. S. Joshi',
            'location' => 'Room 305',
            'equipment_list' => 'High-end Servers, AWS Workstations',
            'description' => 'Virtualization and cloud testbed.',
        ]);
        $storeResponse->assertRedirect();
        $storeResponse->assertSessionHas('success');

        $lab = Lab::where('name', 'Cloud Computing Lab')->first();
        $this->assertNotNull($lab);

        // 2. Update Lab
        $updateResponse = $this->actingAs($faculty)->put("/labs/{$lab->id}", [
            'name' => 'Cloud & DevOps Lab',
            'department' => 'Computer Engineering',
            'faculty_in_charge' => 'Prof. S. Joshi',
            'location' => 'Room 305-B',
            'equipment_list' => 'High-end Servers, Docker & Kubernetes Clusters',
            'description' => 'Updated syllabus testbed.',
        ]);
        $updateResponse->assertRedirect();
        $updateResponse->assertSessionHas('success');

        $lab->refresh();
        $this->assertEquals('Cloud & DevOps Lab', $lab->name);
        $this->assertEquals('Room 305-B', $lab->location);

        // 3. Delete Lab
        $deleteResponse = $this->actingAs($faculty)->delete("/labs/{$lab->id}");
        $deleteResponse->assertRedirect('/labs');
        $deleteResponse->assertSessionHas('success');

        $this->assertDatabaseMissing('labs', ['id' => $lab->id]);
    }
}
