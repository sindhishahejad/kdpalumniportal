<?php

namespace Database\Seeders;

use App\Models\ToolDonation;
use App\Models\ToolDonationGratitude;
use App\Models\ToolDonationRequest;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ToolDonationSeeder extends Seeder
{
    public function run(): void
    {
        $alumni = User::where('role', 'alumni')->first() ?? User::factory()->create([
            'name' => 'Bhavesh Parmar',
            'email' => 'bhavesh.parmar@example.com',
            'role' => 'alumni',
            'department' => 'Mechanical Engineering',
            'graduation_year' => 2012,
            'company' => 'Tata Motors Sanand',
            'is_approved' => true,
        ]);

        $student = User::where('role', 'student')->first() ?? User::factory()->create([
            'name' => 'Jigar Prajapati',
            'email' => 'jigar.prajapati@example.com',
            'role' => 'student',
            'department' => 'Electrical Engineering',
            'year_joining' => 2024,
            'is_approved' => true,
        ]);

        $items = [
            [
                'title' => 'Rotring Mini Drafter + Drawing Board (Complete Engineering Graphics Set)',
                'item_type' => 'drawing_tools',
                'department' => 'Mechanical Engineering',
                'semester_suitability' => 'Sem 1-2',
                'condition' => 'like_new',
                'description' => 'Used carefully during First Year Diploma Engineering Drawing. Includes steel scale arms, protractor head, mounting clamp, and a waterproof carrying bag.',
                'pickup_method' => 'campus_locker',
                'pickup_location' => 'KDP Mechanical Workshop Office (Locker #14)',
                'estimated_value' => 1400,
                'donor_note' => 'I passed Diploma Mechanical with distinction in Graphics. Hope this serves a hard-working first year student!',
                'status' => 'available',
            ],
            [
                'title' => 'Casio FX-991EX ClassWiz Scientific Calculator (Original Japanese Edition)',
                'item_type' => 'calculators_electronics',
                'department' => 'General / All Branches',
                'semester_suitability' => 'All Semesters',
                'condition' => 'like_new',
                'description' => 'High-resolution LCD display, supports 552 functions including matrix calculations, integration, differential equations, and complex numbers.',
                'pickup_method' => 'campus_locker',
                'pickup_location' => 'Central Library Reference Desk',
                'estimated_value' => 1650,
                'donor_note' => 'Battery replaced recently. Keep it safe and pass it on when you graduate.',
                'status' => 'available',
            ],
            [
                'title' => 'Complete GTU Sem 3-4 Atul & Nirav Reference Books for Computer Engineering',
                'item_type' => 'textbook',
                'department' => 'Computer Engineering',
                'semester_suitability' => 'Sem 3-4',
                'condition' => 'gently_used',
                'description' => 'Set of 6 books covering Data Structures in C, Digital Memory Systems, Database Management Systems, and Computer Networks with solved question banks.',
                'pickup_method' => 'handover_patan',
                'pickup_location' => 'Near Hemchandracharya North Gujarat University Gate, Patan',
                'estimated_value' => 2200,
                'donor_note' => 'All key GTU past paper topics are highlighted in yellow. Very useful for mid-sem and GTU finals.',
                'status' => 'available',
            ],
            [
                'title' => 'Arduino Uno Rev3 Starter Kit with 37 Sensor Modules & Breadboard',
                'item_type' => 'lab_hardware',
                'department' => 'Electrical Engineering',
                'semester_suitability' => 'Sem 5-6',
                'condition' => 'brand_new',
                'description' => 'Original Arduino Uno board with ultrasonic distance sensor, relay module, servo motor, jumper wires, and breadboard. Ideal for 5th semester project work.',
                'pickup_method' => 'campus_locker',
                'pickup_location' => 'Electrical Microcontroller Lab (HOD Office)',
                'estimated_value' => 1850,
                'donor_note' => 'Sponsored from our IoT automation team at L&T for budding electrical diploma innovators.',
                'status' => 'available',
            ],
            [
                'title' => 'Mitutoyo Precision 0-150mm Vernier Caliper & Screw Gauge Set',
                'item_type' => 'other',
                'department' => 'Mechanical Engineering',
                'semester_suitability' => 'Sem 3-4',
                'condition' => 'like_new',
                'description' => 'High accuracy hardened stainless steel metric vernier caliper (0.02mm resolution) + 0-25mm micrometer screw gauge in velvet case.',
                'pickup_method' => 'campus_locker',
                'pickup_location' => 'Metrology & Quality Control Lab',
                'estimated_value' => 2100,
                'donor_note' => 'Essential for turning shop and fitting lab precision measurements.',
                'status' => 'handed_over',
            ],
        ];

        foreach ($items as $data) {
            $slug = Str::slug($data['title']);
            $donation = ToolDonation::firstOrCreate(
                ['slug' => $slug],
                array_merge($data, [
                    'user_id' => $alumni->id,
                    'claimed_by_user_id' => $data['status'] === 'handed_over' ? $student->id : null,
                    'claimed_at' => $data['status'] === 'handed_over' ? now()->subDays(3) : null,
                    'handed_over_at' => $data['status'] === 'handed_over' ? now()->subDay() : null,
                ])
            );

            // Add gratitude story for handed over item
            if ($donation->status === 'handed_over') {
                ToolDonationGratitude::firstOrCreate(
                    ['tool_donation_id' => $donation->id],
                    [
                        'user_id' => $student->id,
                        'message' => 'Respected Bhavesh Sir, thank you so much for the precision caliper and micrometer set! It saved me over ₹2,000 for my Fitting & Machine Shop practicals. I promise to keep it in pristine condition and pay it forward to a junior next year!',
                        'likes_count' => 12,
                    ]
                );
            }
        }
    }
}
