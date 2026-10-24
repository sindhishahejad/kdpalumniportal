<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\AcademicSchedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class AcademicCalendarTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_academic_calendar_renders_successfully(): void
    {
        AcademicSchedule::create([
            'title' => 'GTU Winter 2026 Diploma Theory Examinations',
            'category' => 'exam',
            'semester_scope' => 'Sem 1, 3, 5 Regular',
            'start_date' => Carbon::now()->addDays(10),
            'end_date' => Carbon::now()->addDays(30),
            'description' => 'Official GTU examinations conducted at K. D. Polytechnic center.',
            'attachment_url' => 'https://www.gtu.ac.in',
            'is_important' => true,
        ]);

        $response = $this->get(route('calendar.index'));

        $response->assertStatus(200);
        $response->assertSee('GTU Academic Calendar &amp; Examination Circulars', false);
        $response->assertSee('GTU Winter 2026 Diploma Theory Examinations', false);
        $response->assertSee('GTU Examination', false);
        $response->assertSee('Sem 1, 3, 5 Regular', false);
    }

    public function test_calendar_can_filter_by_category(): void
    {
        AcademicSchedule::create([
            'title' => 'GTU Practical Submission Week',
            'category' => 'exam',
            'semester_scope' => 'All Semesters',
            'start_date' => Carbon::now()->addDays(5),
        ]);

        AcademicSchedule::create([
            'title' => 'Even Term Academic Commencement',
            'category' => 'term',
            'semester_scope' => 'Sem 2, 4, 6',
            'start_date' => Carbon::now()->addDays(40),
        ]);

        // Filter by exam
        $response = $this->get(route('calendar.index', ['category' => 'exam']));
        $response->assertStatus(200);
        $response->assertSee('GTU Practical Submission Week', false);
        $response->assertDontSee('Even Term Academic Commencement', false);

        // Filter by term
        $responseTerm = $this->get(route('calendar.index', ['category' => 'term']));
        $responseTerm->assertStatus(200);
        $responseTerm->assertSee('Even Term Academic Commencement', false);
        $responseTerm->assertDontSee('GTU Practical Submission Week', false);
    }

    public function test_calendar_can_search_by_keyword(): void
    {
        AcademicSchedule::create([
            'title' => 'ACPC D2D Lateral Entry Registration Guidelines',
            'category' => 'circular',
            'semester_scope' => 'Final Year Sem 6',
            'start_date' => Carbon::now()->addDays(12),
            'description' => 'Guidance for degree engineering lateral admissions.',
        ]);

        AcademicSchedule::create([
            'title' => 'Diwali Vacation & Academic Break',
            'category' => 'holiday',
            'semester_scope' => 'All Students',
            'start_date' => Carbon::now()->addDays(20),
        ]);

        $response = $this->get(route('calendar.index', ['search' => 'ACPC']));
        $response->assertStatus(200);
        $response->assertSee('ACPC D2D Lateral Entry Registration Guidelines', false);
        $response->assertDontSee('Diwali Vacation &amp; Academic Break', false);
    }

    public function test_admin_can_add_calendar_schedule(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_approved' => true]);

        $payload = [
            'title' => 'GTU Summer 2027 Re-assessment Verification Portal',
            'category' => 'circular',
            'semester_scope' => 'Sem 2, 4, 6',
            'start_date' => Carbon::now()->addDays(2)->format('Y-m-d'),
            'end_date' => Carbon::now()->addDays(15)->format('Y-m-d'),
            'description' => 'Re-checking and viewing evaluated answer-sheets.',
            'attachment_url' => 'https://www.gtu.ac.in',
            'is_important' => '1',
        ];

        $response = $this->actingAs($admin)->post(route('admin.calendar.store'), $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('academic_schedules', [
            'title' => 'GTU Summer 2027 Re-assessment Verification Portal',
            'category' => 'circular',
            'is_important' => true,
        ]);
    }

    public function test_admin_can_update_calendar_schedule(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_approved' => true]);

        $schedule = AcademicSchedule::create([
            'title' => 'Draft Term Schedule',
            'category' => 'term',
            'semester_scope' => 'All',
            'start_date' => Carbon::now()->addDays(10),
            'is_important' => false,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.calendar.update', $schedule), [
            'title' => 'Finalized GTU Term Calendar 2026',
            'category' => 'term',
            'semester_scope' => 'All Semesters',
            'start_date' => Carbon::now()->addDays(12)->format('Y-m-d'),
            'end_date' => Carbon::now()->addDays(90)->format('Y-m-d'),
            'description' => 'Updated term dates approved by CTE Gujarat.',
            'is_important' => '1',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $schedule->refresh();
        $this->assertEquals('Finalized GTU Term Calendar 2026', $schedule->title);
        $this->assertTrue((bool)$schedule->is_important);
    }

    public function test_admin_can_delete_calendar_schedule(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_approved' => true]);

        $schedule = AcademicSchedule::create([
            'title' => 'Temporary Entry To Delete',
            'category' => 'circular',
            'semester_scope' => 'None',
            'start_date' => Carbon::now()->addDays(1),
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.calendar.destroy', $schedule));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('academic_schedules', ['id' => $schedule->id]);
    }

    public function test_non_admin_cannot_manage_calendar(): void
    {
        $student = User::factory()->create(['role' => 'student', 'is_approved' => true]);

        $schedule = AcademicSchedule::create([
            'title' => 'Exam Schedule',
            'category' => 'exam',
            'semester_scope' => 'Sem 1',
            'start_date' => Carbon::now(),
        ]);

        $this->actingAs($student)->post(route('admin.calendar.store'), ['title' => 'Fail'])->assertStatus(403);
        $this->actingAs($student)->put(route('admin.calendar.update', $schedule), ['title' => 'Fail'])->assertStatus(403);
        $this->actingAs($student)->delete(route('admin.calendar.destroy', $schedule))->assertStatus(403);
    }

    public function test_validation_rules_for_calendar(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_approved' => true]);

        $response = $this->actingAs($admin)->post(route('admin.calendar.store'), []);

        $response->assertSessionHasErrors(['title', 'category', 'semester_scope', 'start_date']);
    }
}
