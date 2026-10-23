<?php

namespace Database\Seeders;

use App\Models\BatchReunion;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BatchReunionSeeder extends Seeder
{
    public function run(): void
    {
        $organizer = User::where('role', 'alumni')->first() 
            ?? User::first() 
            ?? User::factory()->create([
                'name' => 'KDP Alumni Association',
                'email' => 'reunions@kdpalumni.in',
                'role' => 'alumni',
                'is_approved' => true,
            ]);

        $reunions = [
            [
                'user_id' => $organizer->id,
                'title' => 'Class of 2016 Decennial Reunion (10-Year Milestone)',
                'slug' => 'class-of-2016-decennial-reunion',
                'batch_year' => '2016',
                'department' => 'Computer Engineering',
                'event_date' => now()->addDays(25)->setHour(10)->setMinute(0),
                'venue' => 'K. D. Polytechnic Central Auditorium & Lawn',
                'city' => 'Patan',
                'description' => 'A decade since we walked out of KDP corridors! Join your 2016 batchmates for campus nostalgia tour, faculty felicitation, and dinner banquet.',
                'milestone_tag' => '10-Year Decennial Milestone',
                'fee_per_person' => 500,
                'whatsapp_group_link' => 'https://chat.whatsapp.com/invite/kdp-2016-reunion',
                'status' => 'upcoming',
            ],
            [
                'user_id' => $organizer->id,
                'title' => 'Batch of 2021 Post-Diploma 5-Year Homecoming',
                'slug' => 'batch-of-2021-post-diploma-5-year-homecoming',
                'batch_year' => '2021',
                'department' => 'All Departments',
                'event_date' => now()->addDays(40)->setHour(14)->setMinute(30),
                'venue' => 'GIDC Club & Convention Hall',
                'city' => 'Patan',
                'description' => 'Five years in industry! Let us reconnect, share career progression, celebrate marriages and promotions, and revisit our favorite canteen spots.',
                'milestone_tag' => '5-Year Homecoming',
                'fee_per_person' => 300,
                'whatsapp_group_link' => 'https://chat.whatsapp.com/invite/kdp-2021-homecoming',
                'status' => 'upcoming',
            ],
            [
                'user_id' => $organizer->id,
                'title' => 'Class of 2001 Silver Jubilee Reunion (25th Year Grand Assembly)',
                'slug' => 'class-of-2001-silver-jubilee-reunion',
                'batch_year' => '2001',
                'department' => 'Mechanical & Electrical',
                'event_date' => now()->addDays(60)->setHour(9)->setMinute(30),
                'venue' => 'Grand Heritage Hall, Sidhpur Road',
                'city' => 'Patan',
                'description' => 'Quarter of a century of engineering excellence. Reconnecting the historic 2001 diploma batch with our beloved retired professors and families.',
                'milestone_tag' => 'Silver Jubilee (25 Years)',
                'fee_per_person' => 1200,
                'whatsapp_group_link' => 'https://chat.whatsapp.com/invite/kdp-2001-silverjubilee',
                'status' => 'upcoming',
            ],
        ];

        foreach ($reunions as $data) {
            $reunion = BatchReunion::firstOrCreate(['slug' => $data['slug']], $data);

            // Add a sample memory if none exists
            if ($reunion->memories()->count() === 0) {
                $reunion->memories()->create([
                    'user_id' => $organizer->id,
                    'caption' => 'Final semester project defense day with our professors outside the main workshop block! Who still remembers staying up all night debugging the PLC code?',
                    'year_taken' => $reunion->batch_year,
                    'likes_count' => 12,
                ]);
            }
        }
    }
}
