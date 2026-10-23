<?php

namespace Database\Seeders;

use App\Models\RegionalChapter;
use Illuminate\Database\Seeder;

class RegionalChapterSeeder extends Seeder
{
    public static function getDefaultChapters(): array
    {
        return [
            [
                'name' => 'Patan & North Gujarat Chapter',
                'slug' => 'patan-north-gujarat',
                'city' => 'Patan',
                'state' => 'Gujarat',
                'country' => 'India',
                'region_type' => 'gujarat',
                'description' => 'The founding home chapter of K.D. Polytechnic, encompassing Patan GIDC, Mehsana engineering corridors, and Palanpur.',
                'meeting_venue' => 'KDP Campus Conference Hall / GIDC Patan',
                'whatsapp_group_link' => 'https://chat.whatsapp.com/invite/kdp-patan',
                'contact_email' => 'patan-chapter@kdpalumni.in',
                'latitude' => 23.8507,
                'longitude' => 72.1266,
                'is_active' => true,
            ],
            [
                'name' => 'Ahmedabad & GIFT City Chapter',
                'slug' => 'ahmedabad-gift-city',
                'city' => 'Ahmedabad',
                'state' => 'Gujarat',
                'country' => 'India',
                'region_type' => 'gujarat',
                'description' => 'Representing alumni working in Gandhinagar GIFT City, Sanand Automobile Industrial Corridor, and Ahmedabad tech parks.',
                'meeting_venue' => 'Vastrapur / SG Highway Meetup Hub',
                'whatsapp_group_link' => 'https://chat.whatsapp.com/invite/kdp-ahmedabad',
                'contact_email' => 'ahmedabad-chapter@kdpalumni.in',
                'latitude' => 23.0225,
                'longitude' => 72.5714,
                'is_active' => true,
            ],
            [
                'name' => 'Vadodara Industrial Corridor Chapter',
                'slug' => 'vadodara-industrial',
                'city' => 'Vadodara',
                'state' => 'Gujarat',
                'country' => 'India',
                'region_type' => 'gujarat',
                'description' => 'Heavy engineering, chemical processing, L&T, and Makarpura GIDC alumni network in central Gujarat.',
                'meeting_venue' => 'Makarpura GIDC Association Hall',
                'whatsapp_group_link' => 'https://chat.whatsapp.com/invite/kdp-vadodara',
                'contact_email' => 'vadodara-chapter@kdpalumni.in',
                'latitude' => 22.3072,
                'longitude' => 73.1812,
                'is_active' => true,
            ],
            [
                'name' => 'Surat & South Gujarat Chapter',
                'slug' => 'surat-south-gujarat',
                'city' => 'Surat',
                'state' => 'Gujarat',
                'country' => 'India',
                'region_type' => 'gujarat',
                'description' => 'Covering Hazira industrial belt, Diamond Research and Mercantile City, and South Gujarat chemical/petrochemical zones.',
                'meeting_venue' => 'Ichhapore Hazira Road Hub',
                'whatsapp_group_link' => 'https://chat.whatsapp.com/invite/kdp-surat',
                'contact_email' => 'surat-chapter@kdpalumni.in',
                'latitude' => 21.1702,
                'longitude' => 72.8311,
                'is_active' => true,
            ],
            [
                'name' => 'Rajkot & Saurashtra Chapter',
                'slug' => 'rajkot-saurashtra',
                'city' => 'Rajkot',
                'state' => 'Gujarat',
                'country' => 'India',
                'region_type' => 'gujarat',
                'description' => 'Connecting mechanical, automobile foundry, diesel engine, and CNC engineering alumni across Saurashtra.',
                'meeting_venue' => 'Metoda GIDC Technical Center',
                'whatsapp_group_link' => 'https://chat.whatsapp.com/invite/kdp-rajkot',
                'contact_email' => 'rajkot-chapter@kdpalumni.in',
                'latitude' => 22.3039,
                'longitude' => 70.8022,
                'is_active' => true,
            ],
            [
                'name' => 'Bengaluru Tech Chapter',
                'slug' => 'bengaluru-tech',
                'city' => 'Bengaluru',
                'state' => 'Karnataka',
                'country' => 'India',
                'region_type' => 'national',
                'description' => 'Hub for KDP alumni working in software engineering, electronics, semiconductor, and IT product startups in Silicon Valley of India.',
                'meeting_venue' => 'HSR Layout / Electronic City Alumni Lounge',
                'whatsapp_group_link' => 'https://chat.whatsapp.com/invite/kdp-bengaluru',
                'contact_email' => 'bengaluru-chapter@kdpalumni.in',
                'latitude' => 12.9716,
                'longitude' => 77.5946,
                'is_active' => true,
            ],
            [
                'name' => 'Pune & Mumbai Chapter',
                'slug' => 'pune-mumbai',
                'city' => 'Pune',
                'state' => 'Maharashtra',
                'country' => 'India',
                'region_type' => 'national',
                'description' => 'Serving KDP engineers across Chakan automotive cluster, Hinjewadi IT park, and Mumbai industrial enterprises.',
                'meeting_venue' => 'Bandra-Kurla Complex / Hinjewadi Phase 1',
                'whatsapp_group_link' => 'https://chat.whatsapp.com/invite/kdp-punemumbai',
                'contact_email' => 'pune-mumbai@kdpalumni.in',
                'latitude' => 18.5204,
                'longitude' => 73.8567,
                'is_active' => true,
            ],
            [
                'name' => 'Global Alumni Chapter (Overseas)',
                'slug' => 'global-overseas',
                'city' => 'Dubai',
                'state' => 'Global',
                'country' => 'United Arab Emirates',
                'region_type' => 'global',
                'description' => 'Connecting KDP alumni residing in North America, Europe, Australia, and Gulf GCC nations for global networking.',
                'meeting_venue' => 'Virtual Meetups & Annual Dubai Gathering',
                'whatsapp_group_link' => 'https://chat.whatsapp.com/invite/kdp-global',
                'contact_email' => 'global-chapter@kdpalumni.in',
                'latitude' => 25.2048,
                'longitude' => 55.2708,
                'is_active' => true,
            ],
        ];
    }

    public function run(): void
    {
        foreach (self::getDefaultChapters() as $chapterData) {
            RegionalChapter::firstOrCreate(['slug' => $chapterData['slug']], $chapterData);
        }
    }
}
