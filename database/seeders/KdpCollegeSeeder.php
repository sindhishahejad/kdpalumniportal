<?php

namespace Database\Seeders;

use App\Models\Notice;
use App\Models\Event;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class KdpCollegeSeeder extends Seeder
{
    /**
     * Run the database seeds reverse-engineered from https://www.kdppatan.ac.in/
     */
    public function run(): void
    {
        // 1. Official College Notices & Circulars (from kdppatan.ac.in)
        $notices = [
            [
                'title' => 'First sem enrollment fee notice',
                'body' => 'All newly admitted students of 1st Semester are hereby informed to pay their GTU enrollment fees online before the due date. For queries, contact Student Section.',
                'attachment_path' => 'notices/gtu_circular_ay26.pdf',
                'attachment_name' => 'GTU_Enrollment_Fee_Schedule_2026.pdf',
                'created_at' => Carbon::parse('2026-10-19 10:00:00'),
                'is_active' => true,
            ],
            [
                'title' => 'CtoD sem enrollment fee notice',
                'body' => 'Certificate to Diploma (CtoD) admitted students must submit their enrollment verification forms along with required fee receipts at Department coordinator desk.',
                'created_at' => Carbon::parse('2026-10-19 09:30:00'),
                'is_active' => true,
            ],
            [
                'title' => 'Remedial Exam Form Notice for 1st and 2nd sem and CtoD student AY 26-1',
                'body' => 'Gujarat Technological University (GTU) Winter 2026 remedial examination forms are now active on student portal. Verify subject codes carefully.',
                'created_at' => Carbon::parse('2026-09-17 11:15:00'),
                'is_active' => true,
            ],
            [
                'title' => 'Regular Exam Form Notice for 3rd and 5th Sem for A.Y. 26-1',
                'body' => 'Regular examination forms for 3rd and 5th Semester Diploma Engineering students are open. Submit before the deadline to avoid late penalty.',
                'created_at' => Carbon::parse('2026-09-08 14:00:00'),
                'is_active' => true,
            ],
            [
                'title' => 'Term Extension Exam Form Notice for A.Y. 26-1',
                'body' => 'Eligible students requiring term extension for completion of diploma curriculum must forward their application through respective Head of Department.',
                'created_at' => Carbon::parse('2026-09-03 12:00:00'),
                'is_active' => true,
            ],
            [
                'title' => 'Remedial Exam Form Notice for 3-8 Sem A.Y. 26-1',
                'body' => 'Remedial examination form schedule for senior semesters. Check internal backlog clearance status prior to submission.',
                'created_at' => Carbon::parse('2026-08-21 15:45:00'),
                'is_active' => true,
            ],
            [
                'title' => 'Term Work Submission Notice for Student all Sem A.Y. 26-1',
                'body' => 'All students are instructed to complete laboratory journals, term work files, and project reports signed by subject faculty before the continuous evaluation deadline.',
                'created_at' => Carbon::parse('2026-08-05 10:30:00'),
                'is_active' => true,
            ],
            [
                'title' => 'Hostel admission notice for sem-1 admitted Students 2026',
                'body' => 'Applications are invited for college campus hostel accommodation for the Academic Year 2026-27. Merit lists and seat allocation will be displayed on Notice Board.',
                'created_at' => Carbon::parse('2026-08-03 16:00:00'),
                'is_active' => true,
            ],
            [
                'title' => 'Seminar on "Creating Safe, Respectful and inclusive Workplace - POSH & POCSO act"',
                'body' => 'The Women Development Cell (WDC) and Internal Complaint Committee of K.D. Polytechnic Patan cordially invites all students and faculty to an awareness seminar.',
                'attachment_path' => 'notices/clean_campus_drive_circular.jpg',
                'attachment_name' => 'WDC_Seminar_Official_Flyer.jpg',
                'created_at' => Carbon::parse('2026-07-18 11:00:00'),
                'is_active' => true,
            ],
            [
                'title' => 'Submission Notice - Civil Engineering Department',
                'body' => 'Civil engineering students must submit Surveying and Concrete Technology field work reports to Dr. H. M. Patel sir before semester end evaluation.',
                'created_at' => Carbon::parse('2026-05-26 14:30:00'),
                'is_active' => true,
            ],
        ];

        foreach ($notices as $noticeData) {
            Notice::updateOrCreate(
                ['title' => $noticeData['title']],
                $noticeData
            );
        }

        // 2. Official Campus Events & Activities (from kdppatan.ac.in)
        $events = [
            [
                'title' => 'Plastic free campus drive in KDP College Campus',
                'category' => 'DRIVE',
                'image_path' => 'events/kdp_event_1.jpg',
                'event_date' => Carbon::parse('2026-05-19'),
                'time_display' => '09:00 AM - 01:00 PM',
                'description' => 'NSS unit and Nature Club organized a mega Plastic Free Campus cleanliness and tree plantation drive across institutional grounds.',
            ],
            [
                'title' => 'Seminar on "Energy & Environment Conservation Awareness Program" for Computer Engg Dept',
                'category' => 'SEMINAR',
                'image_path' => 'events/kdp_event_2.jpg',
                'event_date' => Carbon::parse('2026-05-01'),
                'time_display' => '11:00 AM - 01:30 PM',
                'description' => 'Comprehensive technical seminar on Green Computing and practical energy conservation awareness conducted by GEDA experts for diploma computer engineers.',
            ],
            [
                'title' => 'Seminar on "Menstrual Hygiene & Health"',
                'category' => 'WORKSHOP',
                'image_path' => 'events/kdp_event_3.jpg',
                'event_date' => Carbon::parse('2026-03-24'),
                'time_display' => '02:00 PM - 04:30 PM',
                'description' => 'Health awareness workshop organized by Women Development Cell focusing on modern hygiene practices, medical wellness, and student wellbeing.',
            ],
            [
                'title' => 'Seminar on Mechanisms for Protection and Effective Response to Sexual and Mental Harassment',
                'category' => 'SEMINAR',
                'image_path' => 'events/kdp_event_4.jpg',
                'event_date' => Carbon::parse('2026-03-09'),
                'time_display' => '10:30 AM - 01:00 PM',
                'description' => 'Legal and institutional grievance redressal seminar detailing college committees, anti-ragging policies, and student support helplines.',
            ],
            [
                'title' => 'Theory cum Practical Energy & Environment Conservation Awareness Program',
                'category' => 'WORKSHOP',
                'image_path' => 'events/kdp_event_5.jpg',
                'event_date' => Carbon::parse('2026-01-05'),
                'time_display' => '10:00 AM - 04:00 PM',
                'description' => 'Hands-on practical demonstration of energy auditing instruments, renewable solar models, and industrial electrical efficiency metrics.',
            ],
        ];

        foreach ($events as $eventData) {
            Event::updateOrCreate(
                ['title' => $eventData['title']],
                $eventData
            );
        }
    }
}
