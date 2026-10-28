<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create your default Admin account
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'sindhishahejad@gmail.com', // Change to your preferred login email
            'password' => Hash::make('shahejad2008'), // Your preferred password
            'role' => 'admin',
            'is_approved' => true,
        ]);
        
        // Optional: Create a test Alumni and Student for dashboard testing
        User::factory()->create([
            'name' => 'Test Alumni',
            'email' => 'sabana.sindhi1481@gmail.com',
            'password' => Hash::make('shahejad2008'),
            'role' => 'alumni',
            'is_approved' => true,
        ]);
        
        User::factory()->create([
            'name' => 'Test Student',
            'email' => 'js1818654@gmail.com',
            'password' => Hash::make('juned2008'),
            'role' => 'student',
            'is_approved' => true,
        ]);

        User::factory()->create([
            'name' => 'Prof. Shahejad Sindhi',
            'email' => 'shahejadsindhi2008@gmail.com',
            'password' => Hash::make('shahejad2008'),
            'role' => 'faculty',
            'department' => 'Computer Engineering',
            'designation' => 'Lecturer in Computer Engineering',
            'is_approved' => true,
        ]);

        $this->call(RegionalChapterSeeder::class);
        $this->call(BatchReunionSeeder::class);
        $this->call(AwardSeeder::class);
        $this->call(ToolDonationSeeder::class);
        $this->call(PlacementPartnerSeeder::class);
        $this->call(FacultySeeder::class);
        $this->call(AcademicScheduleSeeder::class);
    }
}