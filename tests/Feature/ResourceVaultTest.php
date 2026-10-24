<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Resource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ResourceVaultTest extends TestCase
{
    use RefreshDatabase;

    public function test_resources_page_renders_successfully(): void
    {
        $user = User::factory()->create(['role' => 'student', 'is_approved' => true]);

        Resource::create([
            'user_id' => $user->id,
            'title' => 'GTU Winter 2025 DBMS Question Paper',
            'category' => 'exam_paper',
            'department' => 'Computer Engineering',
            'semester' => 4,
            'subject' => 'Database Management Systems (4330703)',
            'description' => 'Original GTU question paper with solution outline.',
        ]);

        $response = $this->actingAs($user)->get('/resources');
        $response->assertStatus(200);
        $response->assertSee('GTU Study Resources &amp; Past Exam Papers Vault', false);
        $response->assertSee('GTU Winter 2025 DBMS Question Paper', false);
        $response->assertSee('Computer Engineering', false);
        $response->assertSee('Sem 4', false);
    }

    public function test_user_can_filter_resources_by_category(): void
    {
        $user = User::factory()->create(['role' => 'alumni', 'is_approved' => true]);

        Resource::create([
            'user_id' => $user->id,
            'title' => 'Thermodynamics Lecture Notes',
            'category' => 'study_notes',
            'department' => 'Mechanical Engineering',
            'semester' => 3,
        ]);

        Resource::create([
            'user_id' => $user->id,
            'title' => 'Digital Electronics MOOC Course',
            'category' => 'mooc',
            'department' => 'Electrical Engineering',
            'semester' => 2,
        ]);

        // Filter by study_notes
        $response = $this->actingAs($user)->get('/resources?category=study_notes');
        $response->assertStatus(200);
        $response->assertSee('Thermodynamics Lecture Notes', false);
        $response->assertDontSee('Digital Electronics MOOC Course', false);
    }

    public function test_user_can_filter_resources_by_department(): void
    {
        $user = User::factory()->create(['role' => 'student', 'is_approved' => true]);

        Resource::create([
            'user_id' => $user->id,
            'title' => 'Civil Surveying Field Book',
            'category' => 'study_notes',
            'department' => 'Civil Engineering',
            'semester' => 3,
        ]);

        Resource::create([
            'user_id' => $user->id,
            'title' => 'Computer Python Programming Guide',
            'category' => 'study_notes',
            'department' => 'Computer Engineering',
            'semester' => 2,
        ]);

        // Filter by Civil
        $response = $this->actingAs($user)->get('/resources?department=Civil');
        $response->assertStatus(200);
        $response->assertSee('Civil Surveying Field Book', false);
        $response->assertDontSee('Computer Python Programming Guide', false);
    }

    public function test_user_can_filter_resources_by_semester(): void
    {
        $user = User::factory()->create(['role' => 'student', 'is_approved' => true]);

        Resource::create([
            'user_id' => $user->id,
            'title' => 'Sem 1 Applied Mathematics Formula Sheet',
            'category' => 'study_notes',
            'department' => 'General',
            'semester' => 1,
        ]);

        Resource::create([
            'user_id' => $user->id,
            'title' => 'Sem 6 Project Documentation Guidelines',
            'category' => 'study_notes',
            'department' => 'Computer Engineering',
            'semester' => 6,
        ]);

        // Filter by Semester 6
        $response = $this->actingAs($user)->get('/resources?semester=6');
        $response->assertStatus(200);
        $response->assertSee('Sem 6 Project Documentation Guidelines', false);
        $response->assertDontSee('Sem 1 Applied Mathematics Formula Sheet', false);
    }

    public function test_user_can_search_resources_by_subject_or_keyword(): void
    {
        $user = User::factory()->create(['role' => 'alumni', 'is_approved' => true]);

        Resource::create([
            'user_id' => $user->id,
            'title' => 'Power Systems Analysis',
            'category' => 'exam_paper',
            'department' => 'Electrical Engineering',
            'semester' => 5,
            'subject' => 'GTU-4350901 Power System',
        ]);

        Resource::create([
            'user_id' => $user->id,
            'title' => 'Data Structures in C',
            'category' => 'study_notes',
            'department' => 'Computer Engineering',
            'semester' => 3,
            'subject' => 'DSI (4330702)',
        ]);

        $response = $this->actingAs($user)->get('/resources?search=Power');
        $response->assertStatus(200);
        $response->assertSee('Power Systems Analysis', false);
        $response->assertDontSee('Data Structures in C', false);
    }

    public function test_authenticated_user_can_upload_resource_with_department_and_semester(): void
    {
        Storage::fake('public');

        $user = User::factory()->create(['role' => 'faculty', 'is_approved' => true]);

        $fakePdf = UploadedFile::fake()->create('gtu_exam_paper.pdf', 500, 'application/pdf');

        $response = $this->actingAs($user)
                         ->post(route('resources.store'), [
                             'title' => 'Summer 2025 Advanced Java Paper',
                             'category' => 'exam_paper',
                             'department' => 'Computer Engineering',
                             'semester' => 5,
                             'subject' => 'Advanced Java (4350702)',
                             'description' => 'Official Summer GTU exam paper with model answers.',
                             'file' => $fakePdf,
                         ]);

        $response->assertRedirect(route('resources.index'));
        $this->assertDatabaseHas('resources', [
            'user_id' => $user->id,
            'title' => 'Summer 2025 Advanced Java Paper',
            'category' => 'exam_paper',
            'department' => 'Computer Engineering',
            'semester' => 5,
            'subject' => 'Advanced Java (4350702)',
        ]);
    }

    public function test_uploader_or_admin_can_delete_resource(): void
    {
        $user = User::factory()->create(['role' => 'student', 'is_approved' => true]);
        $admin = User::factory()->create(['role' => 'admin', 'is_approved' => true]);

        $resource1 = Resource::create([
            'user_id' => $user->id,
            'title' => 'Outdated Syllabus',
            'category' => 'other',
        ]);

        $resource2 = Resource::create([
            'user_id' => $user->id,
            'title' => 'Duplicate Notes',
            'category' => 'study_notes',
        ]);

        // User deletes own resource
        $this->actingAs($user)
             ->delete(route('resources.destroy', $resource1))
             ->assertRedirect();
        $this->assertDatabaseMissing('resources', ['id' => $resource1->id]);

        // Admin deletes user's resource
        $this->actingAs($admin)
             ->delete(route('resources.destroy', $resource2))
             ->assertRedirect();
        $this->assertDatabaseMissing('resources', ['id' => $resource2->id]);
    }

    public function test_unauthorized_user_cannot_delete_other_user_resource(): void
    {
        $owner = User::factory()->create(['role' => 'student', 'is_approved' => true]);
        $stranger = User::factory()->create(['role' => 'student', 'is_approved' => true]);

        $resource = Resource::create([
            'user_id' => $owner->id,
            'title' => 'Important Exam Notes',
            'category' => 'study_notes',
        ]);

        $this->actingAs($stranger)
             ->delete(route('resources.destroy', $resource))
             ->assertStatus(403);

        $this->assertDatabaseHas('resources', ['id' => $resource->id]);
    }
}
