<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AcademicSchedule;
use Carbon\Carbon;

class AcademicScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $schedules = [
            [
                'title' => 'GTU Diploma Winter 2026 Regular & Remedial Theory Exams',
                'category' => 'exam',
                'semester_scope' => 'Sem 1, 3 & 5 (Regular) and Sem 2, 4, 6 (Remedial)',
                'start_date' => Carbon::now()->addDays(15)->format('Y-m-d'),
                'end_date' => Carbon::now()->addDays(38)->format('Y-m-d'),
                'description' => 'Official GTU University theory examinations across all diploma branches. Hall tickets downloadable from GTU Student Portal 5 days prior.',
                'attachment_url' => 'https://www.gtu.ac.in',
                'is_important' => true,
                'is_active' => true,
            ],
            [
                'title' => 'Mid-Semester Theory & Practical Internal Assessment Week',
                'category' => 'term',
                'semester_scope' => 'All Odd Semesters (Sem 1, 3, 5)',
                'start_date' => Carbon::now()->subDays(10)->format('Y-m-d'),
                'end_date' => Carbon::now()->subDays(2)->format('Y-m-d'),
                'description' => 'Institute level progressive mid-sem assessment exams conducted as per GTU academic curriculum guidelines.',
                'attachment_url' => null,
                'is_important' => false,
                'is_active' => true,
            ],
            [
                'title' => 'GTU Diploma Re-checking & Re-assessment Window (Summer 2026)',
                'category' => 'circular',
                'semester_scope' => 'Sem 2, 4, 6 Passouts',
                'start_date' => Carbon::now()->subDays(5)->format('Y-m-d'),
                'end_date' => Carbon::now()->addDays(10)->format('Y-m-d'),
                'description' => 'Online application for verification, re-checking, and viewing answer-book copy through GTU Student Portal. Circular Ref: GTU/Exam/Diploma/Recheck/2026/894.',
                'attachment_url' => 'https://www.gtu.ac.in',
                'is_important' => true,
                'is_active' => true,
            ],
            [
                'title' => 'Even Term (Sem 2, 4, 6) Academic Session Commencement',
                'category' => 'term',
                'semester_scope' => 'Even Semesters (Sem 2, 4, 6)',
                'start_date' => Carbon::now()->addDays(55)->format('Y-m-d'),
                'end_date' => null,
                'description' => 'Formal opening of Even semester academic instructional sessions, laboratory coursework, and departmental orientations.',
                'attachment_url' => null,
                'is_important' => false,
                'is_active' => true,
            ],
            [
                'title' => 'Diploma to Degree (D2D) ACPC Engineering Admission Guidance',
                'category' => 'circular',
                'semester_scope' => 'Final Year Diploma (Sem 6)',
                'start_date' => Carbon::now()->subDays(20)->format('Y-m-d'),
                'end_date' => Carbon::now()->addDays(25)->format('Y-m-d'),
                'description' => 'Admission committee guidelines for diploma graduates seeking lateral entry admission to B.E./B.Tech 2nd year engineering colleges.',
                'attachment_url' => 'https://jacpcldce.ac.in',
                'is_important' => true,
                'is_active' => true,
            ],
            [
                'title' => 'GTU Practical / Viva & Term Work Progressive Submission Deadline',
                'category' => 'exam',
                'semester_scope' => 'Sem 3 & Sem 5 Diploma',
                'start_date' => Carbon::now()->addDays(8)->format('Y-m-d'),
                'end_date' => Carbon::now()->addDays(14)->format('Y-m-d'),
                'description' => 'Laboratory journal completion, workshop models grading, and internal viva examinations by department faculties.',
                'attachment_url' => null,
                'is_important' => false,
                'is_active' => true,
            ],
        ];

        foreach ($schedules as $schedule) {
            AcademicSchedule::updateOrCreate(
                ['title' => $schedule['title']],
                $schedule
            );
        }
    }
}
