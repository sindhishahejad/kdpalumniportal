<?php

namespace Database\Seeders;

use App\Models\CampusMemorySpot;
use App\Models\CampusMemoryStory;
use Illuminate\Database\Seeder;

class CampusMemorySpotSeeder extends Seeder
{
    public function run(): void
    {
        $spots = [
            [
                'name' => 'Main Campus Entrance Gate (University Road)',
                'slug' => 'main-campus-entrance-gate',
                'category' => 'landmark',
                'description' => 'The official gateway to K. D. Polytechnic on Hemchandracharya North Gujarat University Road (Opp. T. B. Hospital), welcoming diploma engineers since 1961.',
                'x_percent' => 50.0,
                'y_percent' => 6.0,
                'icon' => 'building',
                'then_photo_path' => 'https://images.unsplash.com/photo-1541829070764-84a7d30dd3f3?auto=format&fit=crop&w=1200&q=80',
                'then_year' => '1961',
                'then_title' => 'Inaugural Cast-Iron Arch & University Road Entrance (1961)',
                'now_photo_path' => 'https://images.unsplash.com/photo-1562774053-701939374585?auto=format&fit=crop&w=1200&q=80',
                'now_year' => '2026',
                'now_title' => 'Renovated Grand Gate & CCTV Security Pavilion (2026)',
                'fun_fact' => 'Generations of students remember rushing through this gate at 8:55 AM sharp to beat the morning bell!',
                'order_num' => 1,
                'stories' => [
                    [
                        'author_name' => 'Maheshbhai Chaudhari',
                        'batch_year' => 1989,
                        'department' => 'Civil Engineering',
                        'story' => 'Walking through this gate on my bicycle from Patan town in 1986. That brass signboard gave every diploma student a sense of immense pride.',
                    ]
                ]
            ],
            [
                'name' => 'Heritage Main Administrative Building & Arched Porch',
                'slug' => 'admin-heritage-porch',
                'category' => 'admin',
                'description' => 'The architectural centerpiece of KDP. Houses the Principal’s Chamber, Administrative Sections, and the Student Window handling GTU enrollment, exam marksheets, and pass certificates.',
                'x_percent' => 50.0,
                'y_percent' => 22.0,
                'icon' => 'building',
                'then_photo_path' => 'https://images.unsplash.com/photo-1541829070764-84a7d30dd3f3?auto=format&fit=crop&w=1200&q=80',
                'then_year' => '1985',
                'then_title' => 'Handcrafted Red Sandstone Colonnade & Porch (1985)',
                'now_photo_path' => 'https://images.unsplash.com/photo-1562774053-701939374585?auto=format&fit=crop&w=1200&q=80',
                'now_year' => '2026',
                'now_title' => 'Solar-Powered GTU Digital Administration Hub (2026)',
                'fun_fact' => 'The cornerstone was blessed by prominent Gujarati educationists during the initial development of technical institutes in North Gujarat.',
                'order_num' => 2,
                'stories' => [
                    [
                        'author_name' => 'Kiritbhai Patel',
                        'batch_year' => 1992,
                        'department' => 'Civil Engineering',
                        'story' => 'I still remember taking our final class photo right on these stone stairs. Dean sir told us that Patan diploma engineers would build Gujarat’s infrastructure, and we truly did!',
                    ],
                    [
                        'author_name' => 'Bhavin Shah',
                        'batch_year' => 2014,
                        'department' => 'Computer Engineering',
                        'story' => 'Waiting outside this porch for the final GTU result sheet notice board posting was the most nerve-wracking and unforgettable feeling!',
                    ]
                ]
            ],
            [
                'name' => 'LRUC Building & Central Technical Library',
                'slug' => 'central-technical-library',
                'category' => 'academic',
                'description' => 'Spans the 1st floor of the LRUC block (~150 sq. meter reading hall, stack area, reference section, and E-Library terminals for GTU online research journals).',
                'x_percent' => 74.0,
                'y_percent' => 28.0,
                'icon' => 'book',
                'then_photo_path' => 'https://images.unsplash.com/photo-1521587760476-6c12a4b040da?auto=format&fit=crop&w=1200&q=80',
                'then_year' => '1992',
                'then_title' => 'Wooden Card-Catalogs & Heavy Blueprint Shelves (1992)',
                'now_photo_path' => 'https://images.unsplash.com/photo-1568667256549-094345857637?auto=format&fit=crop&w=1200&q=80',
                'now_year' => '2026',
                'now_title' => 'High-Speed E-Library & Air-Conditioned GTU Resource Wing (2026)',
                'fun_fact' => 'There was only one physical copy of Khurmi’s Strength of Materials in the reserved reference section, and students would arrive at 7:30 AM to claim it!',
                'order_num' => 3,
                'stories' => [
                    [
                        'author_name' => 'Pooja Barot',
                        'batch_year' => 2018,
                        'department' => 'Computer Engineering',
                        'story' => 'The quiet corner on the 1st floor was my sanctuary during project submissions. Found all my GTU algorithm references right here.',
                    ]
                ]
            ],
            [
                'name' => 'Computer Engineering Department & IT Labs',
                'slug' => 'computer-engineering-labs',
                'category' => 'academic',
                'description' => 'Home to high-speed networked PC laboratories, server rooms, and software development stations for C++, Java, DBMS, and Python programming practicals.',
                'x_percent' => 85.0,
                'y_percent' => 36.0,
                'icon' => 'building',
                'then_photo_path' => 'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?auto=format&fit=crop&w=1200&q=80',
                'then_year' => '1999',
                'then_title' => 'Original Pentium II Floppy Disk Lab & CRT Monitors (1999)',
                'now_photo_path' => 'https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&w=1200&q=80',
                'now_year' => '2026',
                'now_title' => 'AI-Ready Smart Cloud Computing & Cyber Systems Lab (2026)',
                'fun_fact' => 'The transition from 1.44 MB floppy disks to 100 Mbps LAN in the early 2000s felt like magic to the Computer Engineering batches!',
                'order_num' => 4,
                'stories' => [
                    [
                        'author_name' => 'Sneha Panchal',
                        'batch_year' => 2016,
                        'department' => 'Computer Engineering',
                        'story' => 'Debugging Turbo C++ code with blue screens in Lab 3 until the lab assistant sir had to gently remind us that the college was closing for the day!',
                    ]
                ]
            ],
            [
                'name' => 'Civil Engineering & Applied Mechanics Department',
                'slug' => 'civil-engineering-wing',
                'category' => 'academic',
                'description' => 'Houses the surveying instrument stores, theodolites, concrete compression testing machines, and soil mechanics testing yard for infrastructure engineering.',
                'x_percent' => 20.0,
                'y_percent' => 28.0,
                'icon' => 'building',
                'then_photo_path' => 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=1200&q=80',
                'then_year' => '1987',
                'then_title' => 'Manual Optical Theodolites & Cast-Iron Cube Moulds (1987)',
                'now_photo_path' => 'https://images.unsplash.com/photo-1541888946425-d0fbb186c5f8?auto=format&fit=crop&w=1200&q=80',
                'now_year' => '2026',
                'now_title' => 'Digital Total Station & Computerized Material Testing Lab (2026)',
                'fun_fact' => 'Surveying practicals with leveling staves along the university road was always the most publicly visible student activity in Patan!',
                'order_num' => 5,
                'stories' => [
                    [
                        'author_name' => 'Jayesh Patel',
                        'batch_year' => 2004,
                        'department' => 'Civil Engineering',
                        'story' => 'Carrying leveling tripods across the campus grounds in burning Patan summer heat forged lifelong brotherhood.',
                    ]
                ]
            ],
            [
                'name' => 'Central Mechanical Workshops & Foundry Sheds',
                'slug' => 'mechanical-workshop-foundry',
                'category' => 'workshop',
                'description' => 'The powerhouse of engineering fabrication: manual lathe turning machines, carpentry benches, welding bays, smithy hearths, and sand casting foundries.',
                'x_percent' => 24.0,
                'y_percent' => 52.0,
                'icon' => 'wrench',
                'then_photo_path' => 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=1200&q=80',
                'then_year' => '1988',
                'then_title' => 'Belt-Driven Heavy Lathes & Coal Foundry Forge (1988)',
                'now_photo_path' => 'https://images.unsplash.com/photo-1581092335397-9583fe92d232?auto=format&fit=crop&w=1200&q=80',
                'now_year' => '2026',
                'now_title' => 'Modern CNC Center & 3D Fabrication Workstation (2026)',
                'fun_fact' => 'The rhythmic sound of 40 students filing mild steel blocks could be heard across the Patan highway every morning at 9:00 AM sharp!',
                'order_num' => 6,
                'stories' => [
                    [
                        'author_name' => 'Naresh Prajapati',
                        'batch_year' => 1998,
                        'department' => 'Mechanical Engineering',
                        'story' => 'Filing that 50x50 mm square job with 0.02 mm tolerance on a manual vice taught us patience that no corporate desk job ever could. Proud KDP Mechanical batch!',
                    ]
                ]
            ],
            [
                'name' => 'Electrical Engineering & High Voltage Machines Lab',
                'slug' => 'electrical-machines-lab',
                'category' => 'academic',
                'description' => 'Where students connect DC shunt motors, calibrate 3-phase alternators, test high-voltage transformers, and operate the RTO Learning License facilitation desk.',
                'x_percent' => 74.0,
                'y_percent' => 52.0,
                'icon' => 'bolt',
                'then_photo_path' => 'https://images.unsplash.com/photo-1581092580497-e0d23cbdf1dc?auto=format&fit=crop&w=1200&q=80',
                'then_year' => '1990',
                'then_title' => 'Analog Knife-Switches & Wire-Wound Rheostats (1990)',
                'now_photo_path' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=1200&q=80',
                'now_year' => '2026',
                'now_title' => 'Smart PLC Scada Panel & Solar Inverter Testbenches (2026)',
                'fun_fact' => 'Accidentally tripping the main circuit breaker during motor load tests was practically considered a graduation requirement!',
                'order_num' => 7,
                'stories' => [
                    [
                        'author_name' => 'Hardik Suthar',
                        'batch_year' => 2012,
                        'department' => 'Electrical Engineering',
                        'story' => 'Lab instructor Solanki sir’s famous dialogue: "First check your multimeter on AC or DC, then touch the wire!" Kept us all safe and sound.',
                    ]
                ]
            ],
            [
                'name' => 'Campus Canteen & Heritage Neem Tree Courtyard',
                'slug' => 'old-canteen-neem-tree',
                'category' => 'recreation',
                'description' => 'The epicenter of friendships, celebrations, and endless technical debates over hot cutting chai, crispy Bataka Vada, and samosas.',
                'x_percent' => 32.0,
                'y_percent' => 74.0,
                'icon' => 'coffee',
                'then_photo_path' => 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?auto=format&fit=crop&w=1200&q=80',
                'then_year' => '1995',
                'then_title' => 'Tin-Shaded Canteen with Kerosene Stoves & Clay Kulkars (1995)',
                'now_photo_path' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=1200&q=80',
                'now_year' => '2026',
                'now_title' => 'Eco-Friendly Campus Green Food Court & WiFi Pavilion (2026)',
                'fun_fact' => 'More campus startup plans, industrial project teams, and lifelong marriages were planned under this single neem tree than anywhere else!',
                'order_num' => 8,
                'stories' => [
                    [
                        'author_name' => 'Dharmesh Joshi',
                        'batch_year' => 2005,
                        'department' => 'Electrical Engineering',
                        'story' => 'Two rupees cutting chai and 1 rupee bun-maska while debating transformer circuit formulas. Golden days that money can never buy back!',
                    ]
                ]
            ],
            [
                'name' => 'KDP Diploma Residential Hostels (Boys & Girls)',
                'slug' => 'kdp-residential-hostels',
                'category' => 'hostel',
                'description' => 'The home away from home for hundreds of students from across Gujarat. Equipped with furnished dormitories, solar water heating, dining mess hall, and evening study rooms.',
                'x_percent' => 84.0,
                'y_percent' => 74.0,
                'icon' => 'building',
                'then_photo_path' => 'https://images.unsplash.com/photo-1555854877-bab0e564b8d5?auto=format&fit=crop&w=1200&q=80',
                'then_year' => '1975',
                'then_title' => 'Historic Red-Brick Hostel Wings & Communal Dining Hall (1975)',
                'now_photo_path' => 'https://images.unsplash.com/photo-1590402494682-cd3fb53b1f70?auto=format&fit=crop&w=1200&q=80',
                'now_year' => '2026',
                'now_title' => 'Modernized Multi-Seater Quarters with Solar Power & RO Water (2026)',
                'fun_fact' => 'Late-night hostel maggi sessions the night before GTU final exams are among the most cherished alumni traditions.',
                'order_num' => 9,
                'stories' => [
                    [
                        'author_name' => 'Vipul Solanki',
                        'batch_year' => 2011,
                        'department' => 'Mechanical Engineering',
                        'story' => 'Hostel room #14 on 2nd floor! We studied, laughed, celebrated birthdays with birthday bumps, and cried during farewell.',
                    ]
                ]
            ],
            [
                'name' => 'Polytechnic Cricket Pavilion & Sports Ground',
                'slug' => 'cricket-pavilion-ground',
                'category' => 'recreation',
                'description' => 'The battleground for epic inter-department sports tournaments. Civil vs Mechanical matches gathered hundreds of passionate cheerleaders on the boundary lines.',
                'x_percent' => 54.0,
                'y_percent' => 84.0,
                'icon' => 'trophy',
                'then_photo_path' => 'https://images.unsplash.com/photo-1531415074868-036b107e775a?auto=format&fit=crop&w=1200&q=80',
                'then_year' => '1996',
                'then_title' => 'Raw Clay Pitch with Red Brick Boundary Markers (1996)',
                'now_photo_path' => 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?auto=format&fit=crop&w=1200&q=80',
                'now_year' => '2026',
                'now_title' => 'All-Weather Turf Field & LED Floodlit Sports Complex (2026)',
                'fun_fact' => 'Hitting a six over the workshop tin roof meant you had to climb and fetch the tennis ball yourself!',
                'order_num' => 10,
                'stories' => [
                    [
                        'author_name' => 'Rajesh Vaghela',
                        'batch_year' => 2001,
                        'department' => 'Civil Engineering',
                        'story' => 'Scored 48 runs in the 2001 Annual Sports Cup final against Electrical. That feeling of lifting the brass trophy with your batchmates is etched in my soul.',
                    ]
                ]
            ],
        ];

        foreach ($spots as $spotData) {
            $stories = $spotData['stories'] ?? [];
            unset($spotData['stories']);

            $spot = CampusMemorySpot::updateOrCreate(
                ['slug' => $spotData['slug']],
                $spotData
            );

            foreach ($stories as $storyData) {
                CampusMemoryStory::create([
                    'campus_memory_spot_id' => $spot->id,
                    'author_name' => $storyData['author_name'],
                    'batch_year' => $storyData['batch_year'],
                    'department' => $storyData['department'],
                    'story' => $storyData['story'],
                    'is_approved' => true,
                ]);
            }
        }
    }
}
