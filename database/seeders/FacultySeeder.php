<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FacultyMember;

class FacultySeeder extends Seeder
{
    public function run(): void
    {
        $faculty = [
            // COMPUTER ENGINEERING
            [
                'name' => 'Prof. P. V. Patel',
                'designation' => 'Head of Department (HOD) & Associate Professor',
                'department' => 'Computer Engineering',
                'qualification' => 'M.E. (Computer Engineering), B.E.',
                'experience_years' => 18,
                'specialization' => 'Operating Systems, Database Architecture, Cloud Computing',
                'email' => 'hod.comp@kdppatan.ac.in',
                'phone' => '+91 2766 220419',
                'is_hod' => true,
                'order_num' => 1,
            ],
            [
                'name' => 'Prof. S. N. Modi',
                'designation' => 'Lecturer in Computer Engineering',
                'department' => 'Computer Engineering',
                'qualification' => 'M.Tech (AI & Software Systems)',
                'experience_years' => 11,
                'specialization' => 'Data Structures, Python, Web Development',
                'email' => 'snmodi.comp@kdppatan.ac.in',
                'phone' => '+91 2766 220419',
                'is_hod' => false,
                'order_num' => 2,
            ],
            [
                'name' => 'Prof. K. B. Chaudhari',
                'designation' => 'Lecturer in Computer Engineering',
                'department' => 'Computer Engineering',
                'qualification' => 'M.E. (Cyber Security & Networking)',
                'experience_years' => 8,
                'specialization' => 'Computer Networks, Cryptography, Mobile App Dev',
                'email' => 'kbchaudhari.comp@kdppatan.ac.in',
                'phone' => '+91 2766 220419',
                'is_hod' => false,
                'order_num' => 3,
            ],
            [
                'name' => 'Prof. Shahejad Sindhi',
                'designation' => 'Lecturer in Computer Engineering',
                'department' => 'Computer Engineering',
                'qualification' => 'M.E. (Computer Engineering), B.E.',
                'experience_years' => 10,
                'specialization' => 'Full-Stack Web Architectures, Database Systems, Cloud Computing',
                'email' => 'shahejadsindhi2008@gmail.com',
                'phone' => '+91 2766 220419',
                'is_hod' => false,
                'order_num' => 4,
            ],

            // MECHANICAL ENGINEERING
            [
                'name' => 'Dr. V. J. Sharma',
                'designation' => 'Head of Department (HOD) & Lecturer Selection Grade',
                'department' => 'Mechanical Engineering',
                'qualification' => 'Ph.D., M.Tech (Thermal & Fluid Dynamics)',
                'experience_years' => 22,
                'specialization' => 'Thermodynamics, IC Engines, CAD/CAM Manufacturing',
                'email' => 'hod.mech@kdppatan.ac.in',
                'phone' => '+91 2766 220419',
                'is_hod' => true,
                'order_num' => 4,
            ],
            [
                'name' => 'Prof. H. R. Prajapati',
                'designation' => 'Senior Lecturer in Mechanical Engineering',
                'department' => 'Mechanical Engineering',
                'qualification' => 'M.E. (Tool Design & Manufacturing Technology)',
                'experience_years' => 14,
                'specialization' => 'Workshop Technology, CNC Machining, Metrology',
                'email' => 'hrprajapati.mech@kdppatan.ac.in',
                'phone' => '+91 2766 220419',
                'is_hod' => false,
                'order_num' => 5,
            ],
            [
                'name' => 'Prof. N. M. Solanki',
                'designation' => 'Lecturer in Mechanical Engineering',
                'department' => 'Mechanical Engineering',
                'qualification' => 'M.Tech (Industrial Engineering & Robotics)',
                'experience_years' => 9,
                'specialization' => 'Automobile Engineering, Fluid Mechanics, Hydraulics',
                'email' => 'nmsolanki.mech@kdppatan.ac.in',
                'phone' => '+91 2766 220419',
                'is_hod' => false,
                'order_num' => 6,
            ],

            // ELECTRICAL ENGINEERING
            [
                'name' => 'Prof. M. G. Vaghela',
                'designation' => 'Head of Department (HOD) & Senior Lecturer',
                'department' => 'Electrical Engineering',
                'qualification' => 'M.E. (Electrical Power Systems)',
                'experience_years' => 20,
                'specialization' => 'Power Transmission, Switchgear & Protection, High Voltage',
                'email' => 'hod.elec@kdppatan.ac.in',
                'phone' => '+91 2766 220419',
                'is_hod' => true,
                'order_num' => 7,
            ],
            [
                'name' => 'Prof. D. S. Panchal',
                'designation' => 'Lecturer in Electrical Engineering',
                'department' => 'Electrical Engineering',
                'qualification' => 'M.E. (Power Electronics & Drives)',
                'experience_years' => 12,
                'specialization' => 'DC/AC Machines, Renewable Energy Systems, Solar Photovoltaics',
                'email' => 'dspanchal.elec@kdppatan.ac.in',
                'phone' => '+91 2766 220419',
                'is_hod' => false,
                'order_num' => 8,
            ],
            [
                'name' => 'Prof. B. T. Joshi',
                'designation' => 'Lecturer in Electrical Engineering',
                'department' => 'Electrical Engineering',
                'qualification' => 'M.E. (Industrial Automation & Control)',
                'experience_years' => 7,
                'specialization' => 'Microcontrollers, PLC Automation, Electrical Circuit Analysis',
                'email' => 'btjoshi.elec@kdppatan.ac.in',
                'phone' => '+91 2766 220419',
                'is_hod' => false,
                'order_num' => 9,
            ],

            // CIVIL ENGINEERING
            [
                'name' => 'Dr. R. B. Dave',
                'designation' => 'Head of Department (HOD) & Lecturer Selection Grade',
                'department' => 'Civil Engineering',
                'qualification' => 'Ph.D., M.E. (Structural Engineering)',
                'experience_years' => 24,
                'specialization' => 'Reinforced Concrete Structures, Earthquake Engineering, Surveying',
                'email' => 'hod.civil@kdppatan.ac.in',
                'phone' => '+91 2766 220419',
                'is_hod' => true,
                'order_num' => 10,
            ],
            [
                'name' => 'Prof. A. K. Soni',
                'designation' => 'Senior Lecturer in Civil Engineering',
                'department' => 'Civil Engineering',
                'qualification' => 'M.E. (Transportation & Highway Engineering)',
                'experience_years' => 15,
                'specialization' => 'Highway Engineering, Advanced Surveying (Total Station & GPS)',
                'email' => 'aksoni.civil@kdppatan.ac.in',
                'phone' => '+91 2766 220419',
                'is_hod' => false,
                'order_num' => 11,
            ],
            [
                'name' => 'Prof. J. M. Darji',
                'designation' => 'Lecturer in Civil Engineering',
                'department' => 'Civil Engineering',
                'qualification' => 'M.E. (Geotechnical & Water Resources)',
                'experience_years' => 8,
                'specialization' => 'Soil Mechanics, Environmental Engineering, Concrete Technology',
                'email' => 'jmdarji.civil@kdppatan.ac.in',
                'phone' => '+91 2766 220419',
                'is_hod' => false,
                'order_num' => 12,
            ],

            // APPLIED MECHANICS & GENERAL SCIENCE
            [
                'name' => 'Dr. T. C. Trivedi',
                'designation' => 'Head of Department (HOD) - General Department & Applied Mechanics',
                'department' => 'Applied Mechanics',
                'qualification' => 'Ph.D. (Applied Mathematics & Mechanics), M.Sc.',
                'experience_years' => 26,
                'specialization' => 'Engineering Mechanics, Strength of Materials, Numerical Analysis',
                'email' => 'hod.gen@kdppatan.ac.in',
                'phone' => '+91 2766 220419',
                'is_hod' => true,
                'order_num' => 13,
            ],
            [
                'name' => 'Prof. P. L. Rawal',
                'designation' => 'Lecturer in Applied Science',
                'department' => 'General (Science & Humanities)',
                'qualification' => 'M.Sc., M.Phil (Physics & Material Science)',
                'experience_years' => 16,
                'specialization' => 'Engineering Physics, Material Properties, Fiber Optics',
                'email' => 'plrawal.gen@kdppatan.ac.in',
                'phone' => '+91 2766 220419',
                'is_hod' => false,
                'order_num' => 14,
            ],
        ];

        foreach ($faculty as $member) {
            FacultyMember::updateOrCreate(
                ['email' => $member['email']],
                $member
            );
        }
    }
}
