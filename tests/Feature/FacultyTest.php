<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\FacultyMember;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FacultyTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_faculty_page_renders_successfully(): void
    {
        FacultyMember::create([
            'name' => 'Prof. P. V. Patel',
            'designation' => 'Head of Department & Associate Professor',
            'department' => 'Computer Engineering',
            'qualification' => 'M.E. (Computer Engineering)',
            'experience_years' => 18,
            'specialization' => 'Database Systems & Cloud Computing',
            'email' => 'hod.comp@kdppatan.ac.in',
            'is_hod' => true,
        ]);

        $response = $this->get(route('faculty.index'));

        $response->assertStatus(200);
        $response->assertSee('Department Faculty &amp; HOD Directory', false);
        $response->assertSee('Heads of Departments (HOD)', false);
        $response->assertSee('Prof. P. V. Patel', false);
        $response->assertSee('Computer Engineering', false);
        $response->assertSee('Database Systems &amp; Cloud Computing', false);
    }

    public function test_faculty_can_filter_by_department(): void
    {
        FacultyMember::create([
            'name' => 'Dr. V. J. Sharma',
            'designation' => 'Head of Department',
            'department' => 'Mechanical Engineering',
            'qualification' => 'Ph.D., M.Tech',
            'is_hod' => true,
        ]);

        FacultyMember::create([
            'name' => 'Prof. M. G. Vaghela',
            'designation' => 'Head of Department',
            'department' => 'Electrical Engineering',
            'qualification' => 'M.E.',
            'is_hod' => true,
        ]);

        // Filter by Mechanical
        $response = $this->get(route('faculty.index', ['department' => 'Mechanical']));
        $response->assertStatus(200);
        $response->assertSee('Dr. V. J. Sharma', false);
        $response->assertDontSee('Prof. M. G. Vaghela', false);

        // Filter by Electrical
        $responseElec = $this->get(route('faculty.index', ['department' => 'Electrical']));
        $responseElec->assertStatus(200);
        $responseElec->assertSee('Prof. M. G. Vaghela', false);
        $responseElec->assertDontSee('Dr. V. J. Sharma', false);
    }

    public function test_faculty_can_search_by_name_or_specialization(): void
    {
        FacultyMember::create([
            'name' => 'Dr. R. B. Dave',
            'designation' => 'HOD Civil',
            'department' => 'Civil Engineering',
            'qualification' => 'Ph.D.',
            'specialization' => 'Earthquake Engineering',
            'is_hod' => true,
        ]);

        FacultyMember::create([
            'name' => 'Prof. S. N. Modi',
            'designation' => 'Lecturer',
            'department' => 'Computer Engineering',
            'qualification' => 'M.Tech',
            'specialization' => 'Python & Machine Learning',
            'is_hod' => false,
        ]);

        $response = $this->get(route('faculty.index', ['search' => 'Earthquake']));
        $response->assertStatus(200);
        $response->assertSee('Dr. R. B. Dave', false);
        $response->assertDontSee('Prof. S. N. Modi', false);
    }

    public function test_admin_can_add_faculty_member(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_approved' => true]);

        $payload = [
            'name' => 'Dr. T. C. Trivedi',
            'designation' => 'Head of General Department',
            'department' => 'Applied Mechanics',
            'qualification' => 'Ph.D. (Applied Mathematics)',
            'experience_years' => 26,
            'specialization' => 'Engineering Mechanics',
            'email' => 'hod.gen@kdppatan.ac.in',
            'phone' => '+91 2766 220419',
            'is_hod' => '1',
            'order_num' => 1,
        ];

        $response = $this->actingAs($admin)->post(route('admin.faculty.store'), $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('faculty_members', [
            'name' => 'Dr. T. C. Trivedi',
            'department' => 'Applied Mechanics',
            'is_hod' => true,
        ]);
    }

    public function test_admin_can_update_faculty_member(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_approved' => true]);

        $faculty = FacultyMember::create([
            'name' => 'Prof. Old Name',
            'designation' => 'Lecturer',
            'department' => 'Civil Engineering',
            'qualification' => 'B.E.',
            'is_hod' => false,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.faculty.update', $faculty), [
            'name' => 'Prof. A. K. Soni',
            'designation' => 'Senior Lecturer in Civil Engineering',
            'department' => 'Civil Engineering',
            'qualification' => 'M.E. (Transportation Engg)',
            'experience_years' => 15,
            'specialization' => 'Highway Engineering & Total Station',
            'is_hod' => '1',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $faculty->refresh();
        $this->assertEquals('Prof. A. K. Soni', $faculty->name);
        $this->assertEquals('Senior Lecturer in Civil Engineering', $faculty->designation);
        $this->assertTrue((bool)$faculty->is_hod);
    }

    public function test_admin_can_delete_faculty_member(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_approved' => true]);

        $faculty = FacultyMember::create([
            'name' => 'To Be Removed',
            'designation' => 'Temporary Lecturer',
            'department' => 'General',
            'qualification' => 'B.Sc.',
            'is_hod' => false,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.faculty.destroy', $faculty));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('faculty_members', ['id' => $faculty->id]);
    }

    public function test_non_admin_cannot_manage_faculty(): void
    {
        $student = User::factory()->create(['role' => 'student', 'is_approved' => true]);

        $faculty = FacultyMember::create([
            'name' => 'Dr. Guarded',
            'designation' => 'Professor',
            'department' => 'Computer Engineering',
            'qualification' => 'Ph.D.',
            'is_hod' => true,
        ]);

        $this->actingAs($student)->post(route('admin.faculty.store'), ['name' => 'Fail'])->assertStatus(403);
        $this->actingAs($student)->put(route('admin.faculty.update', $faculty), ['name' => 'Fail'])->assertStatus(403);
        $this->actingAs($student)->delete(route('admin.faculty.destroy', $faculty))->assertStatus(403);
    }

    public function test_validation_rules_for_faculty(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_approved' => true]);

        $response = $this->actingAs($admin)->post(route('admin.faculty.store'), []);

        $response->assertSessionHasErrors(['name', 'designation', 'department', 'qualification']);
    }

    public function test_admin_dashboard_renders_faculty_management_card_and_dropdown_link(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_approved' => true]);

        FacultyMember::create([
            'name' => 'Prof. P. V. Patel',
            'designation' => 'Head of Department',
            'department' => 'Computer Engineering',
            'qualification' => 'M.E.',
            'is_hod' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Department Faculty &amp; HOD Directory', false);
        $response->assertSee('Manage Faculty &amp; HODs', false);
        $response->assertSee('Faculty &amp; HOD Management', false);
        $response->assertSee(route('faculty.index'));
    }

    public function test_faculty_pill_badge_on_admin_dashboard_updates_dynamically_in_real_time(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_approved' => true]);

        // Start with 1 HOD and 1 Faculty
        $hod = FacultyMember::create([
            'name' => 'Prof. HOD One',
            'designation' => 'Head of Department',
            'department' => 'Computer Engineering',
            'qualification' => 'Ph.D.',
            'is_hod' => true,
        ]);
        $lecturer = FacultyMember::create([
            'name' => 'Prof. Lecturer One',
            'designation' => 'Lecturer',
            'department' => 'Computer Engineering',
            'qualification' => 'M.E.',
            'is_hod' => false,
        ]);

        $response = $this->actingAs($admin)->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertSee('2 Faculty (1 HODs)');

        // Add a 2nd HOD dynamically
        $hod2 = FacultyMember::create([
            'name' => 'Prof. HOD Two',
            'designation' => 'Head of Department',
            'department' => 'Mechanical Engineering',
            'qualification' => 'M.Tech',
            'is_hod' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertSee('3 Faculty (2 HODs)');

        // Delete a member dynamically
        $lecturer->delete();

        $response = $this->actingAs($admin)->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertSee('2 Faculty (2 HODs)');
    }
}
