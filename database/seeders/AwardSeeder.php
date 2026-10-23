<?php

namespace Database\Seeders;

use App\Models\AwardCategory;
use App\Models\AwardCycle;
use App\Models\AwardNomination;
use App\Models\AwardVote;
use App\Models\HallOfFameInductee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AwardSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::factory()->create([
            'name' => 'KDP Awards Committee',
            'email' => 'awards@kdpatan.ac.in',
            'role' => 'admin',
            'is_approved' => true,
        ]);

        $alumni = User::where('role', 'alumni')->first() ?? User::factory()->create([
            'name' => 'Bhavesh Parmar',
            'email' => 'bhavesh.parmar@example.com',
            'role' => 'alumni',
            'department' => 'Mechanical Engineering',
            'graduation_year' => 2012,
            'company' => 'Tata Motors Sanand',
            'designation' => 'Lead Automation Engineer',
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

        // 1. Concluded 2025 Cycle with Hall of Fame Inductees
        $cycle2025 = AwardCycle::firstOrCreate(
            ['year' => 2025],
            [
                'title' => 'KDP Annual Alumni Awards 2025',
                'theme' => 'Four Decades of Engineering Groundwork & Nation Building',
                'description' => 'Honoring our foundational alumni who laid the industrial milestones of Gujarat.',
                'status' => 'concluded',
                'nominations_start_at' => now()->subYear()->startOfMonth(),
                'nominations_end_at' => now()->subYear()->addMonth(),
                'voting_start_at' => now()->subYear()->addMonths(2),
                'voting_end_at' => now()->subYear()->addMonths(3),
                'ceremony_date' => now()->subYear()->addMonths(4)->toDateString(),
                'is_active' => false,
            ]
        );

        // 2. Active 2026 Cycle
        $cycle2026 = AwardCycle::firstOrCreate(
            ['year' => 2026],
            [
                'title' => 'KDP Annual Alumni Awards 2026',
                'theme' => 'Igniting Industrial Innovation, Clean Tech & Young Leadership',
                'description' => 'Celebrating the extraordinary achievements of K.D. Polytechnic Patan graduates across manufacturing, high-tech, public administration, and entrepreneurship.',
                'status' => 'voting_open',
                'nominations_start_at' => now()->subMonth(),
                'nominations_end_at' => now()->subDays(5),
                'voting_start_at' => now()->subDays(4),
                'voting_end_at' => now()->addDays(20),
                'ceremony_date' => now()->addMonth()->toDateString(),
                'is_active' => true,
            ]
        );

        // Categories for 2026
        $categoriesData = [
            [
                'name' => 'Distinguished Industrial Leader',
                'slug' => 'distinguished-industrial-leader',
                'icon' => '🏭',
                'description' => 'Awarded to an alumnus holding executive, plant head, or chief engineering roles with distinguished track records in industrial management.',
                'eligibility_criteria' => 'Minimum 10 years of post-diploma experience in manufacturing, infrastructure, or EPC sectors.',
                'display_order' => 1,
            ],
            [
                'name' => 'Young Polytechnic Innovator',
                'slug' => 'young-polytechnic-innovator',
                'icon' => '🚀',
                'description' => 'Recognizing alumni under 32 who have introduced disruptive technology, automation solutions, or filed patents.',
                'eligibility_criteria' => 'Graduated within the last 8 years; demonstrable patent, research paper, or technological deployment.',
                'display_order' => 2,
            ],
            [
                'name' => 'Women in Engineering Excellence',
                'slug' => 'women-in-engineering-excellence',
                'icon' => '👩‍💻',
                'description' => 'Honoring outstanding female engineers breaking glass ceilings across mechanical, electrical, IT, and civil fields.',
                'eligibility_criteria' => 'Female alumnus of KDP with remarkable career milestones or leadership impact.',
                'display_order' => 3,
            ],
            [
                'name' => 'Public Service & Nation Building',
                'slug' => 'public-service-nation-building',
                'icon' => '🏛️',
                'description' => 'Recognizing alumni serving in Gujarat Government, Indian Railways, ISRO, Defense, or public utility enterprises.',
                'eligibility_criteria' => 'Active or retired officer in public service, governance, or civil defense.',
                'display_order' => 4,
            ],
            [
                'name' => 'Outstanding Educator & Mentor',
                'slug' => 'outstanding-educator-mentor',
                'icon' => '📚',
                'description' => 'Honoring alumni dedicated to educating future technicians, polytechnic students, and mentoring junior engineers.',
                'eligibility_criteria' => 'Educator or dedicated industry mentor with proven contributions to diploma pedagogy.',
                'display_order' => 5,
            ],
            [
                'name' => 'Entrepreneur of the Year (GIDC & Tech)',
                'slug' => 'entrepreneur-of-the-year',
                'icon' => '💼',
                'description' => 'Celebrating founders who started manufacturing units, fabrication workshops, EPC firms, or tech startups.',
                'eligibility_criteria' => 'Founder or co-founder of a registered enterprise operating for at least 2 years.',
                'display_order' => 6,
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $catData) {
            $categories[$catData['slug']] = AwardCategory::firstOrCreate(
                [
                    'award_cycle_id' => $cycle2026->id,
                    'slug' => $catData['slug'],
                ],
                $catData
            );
        }

        // Approved Nominees for 2026 so voting is immediately active
        $nominees = [
            [
                'category' => 'distinguished-industrial-leader',
                'nominee_name' => 'Er. Rajesh K. Patel',
                'nominee_department' => 'Mechanical Engineering',
                'nominee_batch_year' => 1998,
                'current_organization' => 'Adani Ports & SEZ Mundra',
                'designation' => 'General Manager - Marine Heavy Equipment',
                'achievement_summary' => 'Spearheaded port crane automation and preventative maintenance overhaul handling over 40 million metric tonnes of cargo annually.',
                'impact_statement' => 'Trained over 70 diploma apprentices from North Gujarat polytechnics into high-skilled port technicians.',
                'portfolio_url' => 'https://linkedin.com',
                'status' => 'approved',
            ],
            [
                'category' => 'distinguished-industrial-leader',
                'nominee_name' => 'Er. Kaushik V. Chauhan',
                'nominee_department' => 'Electrical Engineering',
                'nominee_batch_year' => 2002,
                'current_organization' => 'Larsen & Toubro Heavy Engineering',
                'designation' => 'Senior Engineering Manager',
                'achievement_summary' => 'Led electrification of nuclear power components and cryogenic containment testing at Hazira complex.',
                'impact_statement' => 'Active contributor to KDP syllabus modernization committees and GIDC placement drives.',
                'portfolio_url' => 'https://linkedin.com',
                'status' => 'approved',
            ],
            [
                'category' => 'young-polytechnic-innovator',
                'nominee_name' => 'Parthiv S. Panchal',
                'nominee_department' => 'Computer Engineering',
                'nominee_batch_year' => 2019,
                'current_organization' => 'AgriSensor IoT Labs Ahmedabad',
                'designation' => 'Co-Founder & Chief Hardware Architect',
                'achievement_summary' => 'Developed low-cost LoRaWAN soil moisture and canal water sensors currently deployed across 350+ farms in Patan and Banaskantha.',
                'impact_statement' => 'Reduced agricultural water wastage by 28% for local cumin and mustard farmers.',
                'portfolio_url' => 'https://github.com',
                'status' => 'approved',
            ],
            [
                'category' => 'women-in-engineering-excellence',
                'nominee_name' => 'Sneha D. Chaudhari',
                'nominee_department' => 'Electrical Engineering',
                'nominee_batch_year' => 2014,
                'current_organization' => 'GETCO (Gujarat Energy Transmission Corp)',
                'designation' => 'Deputy Executive Engineer - 220kV Substation',
                'achievement_summary' => 'Managed zero-outage grid operations during severe cyclone conditions in coastal Saurashtra and grid synchronization.',
                'impact_statement' => 'Frequent keynote speaker at KDP Women in STEM symposiums encouraging female enrollment in electrical diploma courses.',
                'portfolio_url' => 'https://linkedin.com',
                'status' => 'approved',
            ],
            [
                'category' => 'entrepreneur-of-the-year',
                'nominee_name' => 'Hitesh B. Prajapati',
                'nominee_department' => 'Mechanical Engineering',
                'nominee_batch_year' => 2010,
                'current_organization' => 'Shree Hari CNC Engineering Works, Chhatral GIDC',
                'designation' => 'Founder & Managing Director',
                'achievement_summary' => 'Grew a single manual lathe workshop into a 22,000 sq ft CNC turning and precision aerospace components manufacturing facility.',
                'impact_statement' => 'Employs 45+ diploma technicians and sponsors technical tools for KDP machine shop workshops.',
                'portfolio_url' => 'https://linkedin.com',
                'status' => 'approved',
            ],
        ];

        foreach ($nominees as $nom) {
            $cat = $categories[$nom['category']] ?? null;
            if (!$cat) continue;

            $nomination = AwardNomination::firstOrCreate(
                [
                    'award_cycle_id' => $cycle2026->id,
                    'award_category_id' => $cat->id,
                    'nominee_name' => $nom['nominee_name'],
                ],
                [
                    'nominator_user_id' => $alumni->id,
                    'nominee_user_id' => null,
                    'nominee_email' => Str::slug($nom['nominee_name']) . '@example.com',
                    'nominee_department' => $nom['nominee_department'],
                    'nominee_batch_year' => $nom['nominee_batch_year'],
                    'current_organization' => $nom['current_organization'],
                    'designation' => $nom['designation'],
                    'achievement_summary' => $nom['achievement_summary'],
                    'impact_statement' => $nom['impact_statement'],
                    'portfolio_url' => $nom['portfolio_url'],
                    'status' => $nom['status'],
                    'is_winner' => false,
                ]
            );

            // Seed initial sample vote
            if ($nom['nominee_name'] === 'Er. Rajesh K. Patel') {
                AwardVote::firstOrCreate([
                    'award_cycle_id' => $cycle2026->id,
                    'award_category_id' => $cat->id,
                    'user_id' => $student->id,
                ], [
                    'award_nomination_id' => $nomination->id,
                ]);
            }
        }

        // 3. Populate Virtual Hall of Fame (Past Honorees)
        $inductees = [
            [
                'name' => 'Er. Dharmesh S. Patel',
                'department' => 'Mechanical Engineering',
                'batch_year' => 1988,
                'induction_year' => 2025,
                'award_title' => 'Lifetime Industrial Pioneer Award',
                'citation' => 'For extraordinary lifelong leadership in establishing North Gujarat’s precision tooling industry and mentoring hundreds of diploma engineers over 35 years.',
                'current_role' => 'Chairman & Managing Director',
                'company' => 'Patel Precision Castings & Tooling Ltd.',
                'is_featured' => true,
            ],
            [
                'name' => 'Priyanka M. Solanki',
                'department' => 'Electrical Engineering',
                'batch_year' => 2005,
                'induction_year' => 2025,
                'award_title' => 'Distinguished Clean Energy Leader',
                'citation' => 'Recognized for pioneering solar-wind hybrid substation integration for Gujarat Energy Transmission Corporation and Charanka Solar Park.',
                'current_role' => 'Superintending Engineer (Renewable Grid)',
                'company' => 'GETCO Vadodara',
                'is_featured' => true,
            ],
            [
                'name' => 'Nilesh B. Joshi',
                'department' => 'Civil Engineering',
                'batch_year' => 1994,
                'induction_year' => 2024,
                'award_title' => 'Infrastructure Excellence & Public Service',
                'citation' => 'For stellar engineering contributions to the Sardar Sarovar North Gujarat Branch Canal network and Patan municipal water drainage management.',
                'current_role' => 'Executive Engineer',
                'company' => 'Narmada Water Resources & Water Supply Dept.',
                'is_featured' => false,
            ],
            [
                'name' => 'Bhavik R. Dave',
                'department' => 'Computer Engineering',
                'batch_year' => 2012,
                'induction_year' => 2024,
                'award_title' => 'Technology Innovator of the Decade',
                'citation' => 'For developing indigenous SCADA telemetry systems for agricultural pumps and water distribution across North Gujarat rural cooperatives.',
                'current_role' => 'Chief Technology Officer',
                'company' => 'TechVanguard Telemetry Pvt. Ltd.',
                'is_featured' => false,
            ],
        ];

        foreach ($inductees as $ind) {
            HallOfFameInductee::firstOrCreate(
                [
                    'name' => $ind['name'],
                    'induction_year' => $ind['induction_year'],
                ],
                array_merge($ind, [
                    'award_cycle_id' => $cycle2025->id,
                ])
            );
        }
    }
}
