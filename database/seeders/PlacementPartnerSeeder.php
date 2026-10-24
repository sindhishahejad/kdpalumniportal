<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PlacementPartner;

class PlacementPartnerSeeder extends Seeder
{
    public function run(): void
    {
        $partners = [
            [
                'name' => 'Larsen & Toubro (L&T)',
                'sector' => 'EPC & Heavy Infrastructure',
                'package_range' => '₹3.2 - 4.5 LPA',
                'roles_offered' => 'Diploma Engineer Trainee (DET) - Construction & Projects',
                'hiring_departments' => 'Civil Engineering, Mechanical Engineering, Electrical Engineering',
                'website_url' => 'https://www.larsentoubro.com',
                'order_num' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Adani Group',
                'sector' => 'Energy, Green Hydrogen & Logistics',
                'package_range' => '₹3.0 - 4.2 LPA',
                'roles_offered' => 'Operations & Maintenance Trainee (Thermal & Solar)',
                'hiring_departments' => 'Electrical Engineering, Mechanical Engineering, Civil Engineering',
                'website_url' => 'https://www.adani.com',
                'order_num' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Tata Motors',
                'sector' => 'Automotive & EV Manufacturing',
                'package_range' => '₹2.8 - 3.8 LPA',
                'roles_offered' => 'Production Trainee & Chassis Assembly Technician',
                'hiring_departments' => 'Mechanical Engineering, Electrical Engineering',
                'website_url' => 'https://www.tatamotors.com',
                'order_num' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Siemens India',
                'sector' => 'Industrial Automation & Digital Grid',
                'package_range' => '₹3.5 - 4.5 LPA',
                'roles_offered' => 'Junior Automation & Drive Systems Specialist',
                'hiring_departments' => 'Electrical Engineering, Computer Engineering',
                'website_url' => 'https://www.siemens.com/in',
                'order_num' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'GETCO (Gujarat Energy Transmission Corp)',
                'sector' => 'State Power Grid & Substation Utility',
                'package_range' => '₹2.6 - 3.6 LPA',
                'roles_offered' => 'Substation Maintenance Apprenticeship / DET',
                'hiring_departments' => 'Electrical Engineering, Civil Engineering',
                'website_url' => 'https://getcogujarat.com',
                'order_num' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Torrent Power',
                'sector' => 'Power Generation & City Distribution',
                'package_range' => '₹2.8 - 4.0 LPA',
                'roles_offered' => 'Distribution Sub-Division Technical Assistant',
                'hiring_departments' => 'Electrical Engineering',
                'website_url' => 'https://www.torrentpower.com',
                'order_num' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'Welspun Corp',
                'sector' => 'Large Diameter Pipes & Infrastructure',
                'package_range' => '₹2.6 - 3.5 LPA',
                'roles_offered' => 'Quality Control & Non-Destructive Testing (NDT)',
                'hiring_departments' => 'Mechanical Engineering, Civil Engineering',
                'website_url' => 'https://www.welspuncorp.com',
                'order_num' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'Suzuki Motor Gujarat (SMG)',
                'sector' => 'Vehicle Robotics & Advanced Assembly',
                'package_range' => '₹2.9 - 3.8 LPA',
                'roles_offered' => 'Paint Shop & Welding Robotics Maintenance',
                'hiring_departments' => 'Mechanical Engineering, Electrical Engineering',
                'website_url' => 'https://www.marutisuzuki.com',
                'order_num' => 8,
                'is_active' => true,
            ],
            [
                'name' => 'Hitachi Energy',
                'sector' => 'High-Voltage Transformers & Switchgear',
                'package_range' => '₹3.2 - 4.2 LPA',
                'roles_offered' => 'Switchgear Testing & Field Service Engineer',
                'hiring_departments' => 'Electrical Engineering',
                'website_url' => 'https://www.hitachienergy.com',
                'order_num' => 9,
                'is_active' => true,
            ],
            [
                'name' => 'Reliance Industries Limited',
                'sector' => 'Petrochemicals & Green Energy Gigafactories',
                'package_range' => '₹3.0 - 4.5 LPA',
                'roles_offered' => 'Diploma Engineer Trainee - Plant Operations',
                'hiring_departments' => 'Mechanical Engineering, Electrical Engineering, Computer Engineering',
                'website_url' => 'https://www.ril.com',
                'order_num' => 10,
                'is_active' => true,
            ],
            [
                'name' => 'Essar Group',
                'sector' => 'Steel, Power & Maritime Terminals',
                'package_range' => '₹2.8 - 3.6 LPA',
                'roles_offered' => 'Site Engineering Associate',
                'hiring_departments' => 'Mechanical Engineering, Civil Engineering, Electrical Engineering',
                'website_url' => 'https://www.essar.com',
                'order_num' => 11,
                'is_active' => true,
            ],
            [
                'name' => 'Tech Mahindra / IT Solutions',
                'sector' => 'Enterprise IT & Cloud Infrastructure',
                'package_range' => '₹2.6 - 3.6 LPA',
                'roles_offered' => 'Network Operations Center (NOC) Engineer',
                'hiring_departments' => 'Computer Engineering',
                'website_url' => 'https://www.techmahindra.com',
                'order_num' => 12,
                'is_active' => true,
            ],
        ];

        foreach ($partners as $partner) {
            PlacementPartner::updateOrCreate(
                ['name' => $partner['name']],
                $partner
            );
        }
    }
}
