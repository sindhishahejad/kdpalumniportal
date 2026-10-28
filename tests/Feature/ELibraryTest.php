<?php

namespace Tests\Feature;

use App\Models\ELibraryItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ELibraryTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_elibrary_index_with_categories_and_metrics(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_approved' => true]);

        ELibraryItem::create([
            'user_id' => $admin->id,
            'title' => 'Core C Programming for Diploma Engineers',
            'category' => 'ebook',
            'department' => 'Computer',
            'semester' => 3,
            'academic_year' => '2023-24',
            'subject_code' => '4330701',
            'subject_name' => 'Data Structures',
            'description' => 'Comprehensive textbook for diploma engineering students.',
            'downloads_count' => 45,
            'is_approved' => true,
        ]);

        ELibraryItem::create([
            'user_id' => $admin->id,
            'title' => 'IoT Smart Agriculture Edge Node',
            'category' => 'capstone_project',
            'department' => 'Computer',
            'semester' => 6,
            'academic_year' => '2023-24',
            'author_or_team' => 'KDP Student Team 2024',
            'guide_faculty' => 'Prof. N. D. Modi',
            'tech_stack' => 'ESP32, MQTT, LoRaWAN',
            'description' => 'Final year capstone synopsis on smart agriculture irrigation.',
            'downloads_count' => 60,
            'is_approved' => true,
        ]);

        $response = $this->get(route('elibrary.index'));

        $response->assertStatus(200);
        $response->assertSee('Digital E-Library &amp; Project Vault', false);
        $response->assertSee('Core C Programming for Diploma Engineers');
        $response->assertSee('IoT Smart Agriculture Edge Node');
        $response->assertSee('GTU E-Books &amp; Textbooks', false);
        $response->assertSee('Capstone Project Synopses', false);
        $response->assertSee('Lab Reference Manuals', false);
    }

    public function test_user_can_filter_elibrary_by_category(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_approved' => true]);

        $ebook = ELibraryItem::create([
            'user_id' => $admin->id,
            'title' => 'Thermodynamics & Thermal Power Engineering',
            'category' => 'ebook',
            'department' => 'Mechanical',
            'semester' => 4,
            'description' => 'Mechanical textbook for thermodynamic cycles.',
            'is_approved' => true,
        ]);

        $capstone = ELibraryItem::create([
            'user_id' => $admin->id,
            'title' => 'Automated Mini CNC PCB Plotter',
            'category' => 'capstone_project',
            'department' => 'Mechanical',
            'semester' => 6,
            'description' => 'Capstone synopsis on low-cost CNC plotter prototype.',
            'is_approved' => true,
        ]);

        // Filter by capstone_project
        $response = $this->get(route('elibrary.index', ['category' => 'capstone_project']));
        $response->assertStatus(200);
        $response->assertSee('Automated Mini CNC PCB Plotter');
        $response->assertDontSee('Thermodynamics & Thermal Power Engineering');
    }

    public function test_user_can_filter_by_department_and_semester(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_approved' => true]);

        $computerItem = ELibraryItem::create([
            'user_id' => $admin->id,
            'title' => 'Operating Systems & Linux Kernel Lab',
            'category' => 'lab_manual',
            'department' => 'Computer',
            'semester' => 4,
            'description' => 'Shell scripting lab manual.',
            'is_approved' => true,
        ]);

        $civilItem = ELibraryItem::create([
            'user_id' => $admin->id,
            'title' => 'Highway Surveying Practical Manual',
            'category' => 'lab_manual',
            'department' => 'Civil',
            'semester' => 3,
            'description' => 'Surveying practical procedures.',
            'is_approved' => true,
        ]);

        $response = $this->get(route('elibrary.index', [
            'department' => 'Computer',
            'semester' => 4,
        ]));

        $response->assertStatus(200);
        $response->assertSee('Operating Systems &amp; Linux Kernel Lab', false);
        $response->assertDontSee('Highway Surveying Practical Manual');
    }

    public function test_user_can_search_by_keyword_and_tech_stack(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_approved' => true]);

        ELibraryItem::create([
            'user_id' => $admin->id,
            'title' => 'Solar Hybrid Microgrid Controller',
            'category' => 'capstone_project',
            'department' => 'Electrical',
            'semester' => 6,
            'tech_stack' => 'STM32, SPWM, MPPT',
            'description' => 'Inverter control system.',
            'is_approved' => true,
        ]);

        ELibraryItem::create([
            'user_id' => $admin->id,
            'title' => 'Concrete Compressive Testing Guide',
            'category' => 'lab_manual',
            'department' => 'Civil',
            'semester' => 3,
            'tech_stack' => 'IS 516, CTM',
            'description' => 'Civil testing manual.',
            'is_approved' => true,
        ]);

        $response = $this->get(route('elibrary.index', ['search' => 'STM32']));
        $response->assertStatus(200);
        $response->assertSee('Solar Hybrid Microgrid Controller');
        $response->assertDontSee('Concrete Compressive Testing Guide');
    }

    public function test_user_can_view_single_resource_details_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_approved' => true]);

        $item = ELibraryItem::create([
            'user_id' => $admin->id,
            'title' => 'Intel 8085 Microprocessor Pinout Reference',
            'category' => 'lab_manual',
            'department' => 'Computer',
            'semester' => 4,
            'academic_year' => '2024-25',
            'subject_code' => '4340702',
            'subject_name' => 'Microprocessor Lab',
            'author_or_team' => 'KDP Hardware Lab Instructors',
            'description' => 'Laminated lab instruction sheet with complete opcode matrix.',
            'file_format' => 'PDF',
            'file_size' => '1.8 MB',
            'downloads_count' => 95,
            'is_approved' => true,
        ]);

        $response = $this->get(route('elibrary.show', $item->slug));

        $response->assertStatus(200);
        $response->assertSee('Intel 8085 Microprocessor Pinout Reference');
        $response->assertSee('4340702');
        $response->assertSee('KDP Hardware Lab Instructors');
        $response->assertSee('95');
    }

    public function test_download_increments_metric_and_returns_file(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create(['role' => 'admin', 'is_approved' => true]);

        $filePath = 'e_library/sample_manual.pdf';
        Storage::disk('public')->put($filePath, 'Mock PDF file contents for diploma lab');

        $item = ELibraryItem::create([
            'user_id' => $admin->id,
            'title' => 'Fluid Mechanics Laboratory Manual',
            'category' => 'lab_manual',
            'department' => 'Mechanical',
            'semester' => 4,
            'description' => 'Venturimeter and orifice experiment procedures.',
            'file_path' => $filePath,
            'file_format' => 'PDF',
            'file_size' => '2.5 MB',
            'downloads_count' => 10,
            'is_approved' => true,
        ]);

        $response = $this->get(route('elibrary.download', $item->id));

        $response->assertStatus(200);
        $this->assertEquals(11, $item->fresh()->downloads_count);
    }

    public function test_download_fallback_generates_synopsis_text_file_when_local_file_is_pending(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_approved' => true]);

        $item = ELibraryItem::create([
            'user_id' => $admin->id,
            'title' => 'Smart Solar Irrigation Node Synopsis',
            'category' => 'capstone_project',
            'department' => 'Computer',
            'semester' => 6,
            'author_or_team' => 'Parth Patel (2024)',
            'guide_faculty' => 'Prof. N. D. Modi',
            'tech_stack' => 'ESP32, MQTT',
            'description' => 'Edge IoT automated water conservation project for Patan.',
            'file_path' => null,
            'external_url' => null,
            'downloads_count' => 5,
            'is_approved' => true,
        ]);

        $response = $this->get(route('elibrary.download', $item->id));

        $response->assertStatus(200);
        $response->assertHeader('Content-Disposition');
        $this->assertStringContainsString('KILACHAND DEVCHAND POLYTECHNIC', $response->getContent());
        $this->assertStringContainsString('Smart Solar Irrigation Node Synopsis', $response->getContent());
        $this->assertEquals(6, $item->fresh()->downloads_count);
    }

    public function test_authenticated_user_can_upload_new_resource(): void
    {
        Storage::fake('public');

        $student = User::factory()->create(['role' => 'student', 'is_approved' => true]);

        $pdf = UploadedFile::fake()->create('project_synopsis.pdf', 500, 'application/pdf');

        $response = $this->actingAs($student)->post(route('elibrary.store'), [
            'title' => 'AI Waste Segregation Smart Bin',
            'category' => 'capstone_project',
            'department' => 'Computer',
            'semester' => 6,
            'academic_year' => '2024-25',
            'author_or_team' => 'Ravi Dave, Alok Shah (Class of 2025)',
            'guide_faculty' => 'Prof. S. R. Patel',
            'subject_code' => '4360701',
            'subject_name' => 'Capstone Project - II',
            'tech_stack' => 'YOLOv8, Raspberry Pi 4, Servo Motors, OpenCV',
            'description' => 'Computer vision driven automatic municipal waste sorting bin built for campus clean initiatives.',
            'external_url' => 'https://github.com/kdp-waste-ai',
            'file' => $pdf,
        ]);

        $response->assertRedirect(route('elibrary.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('e_library_items', [
            'title' => 'AI Waste Segregation Smart Bin',
            'category' => 'capstone_project',
            'department' => 'Computer',
            'user_id' => $student->id,
            'subject_code' => '4360701',
        ]);

        $item = ELibraryItem::where('title', 'AI Waste Segregation Smart Bin')->first();
        $this->assertNotNull($item->file_path);
        Storage::disk('public')->assertExists($item->file_path);
    }

    public function test_resource_owner_and_admin_can_delete_item(): void
    {
        $owner = User::factory()->create(['role' => 'student', 'is_approved' => true]);
        $admin = User::factory()->create(['role' => 'admin', 'is_approved' => true]);
        $stranger = User::factory()->create(['role' => 'student', 'is_approved' => true]);

        $item1 = ELibraryItem::create([
            'user_id' => $owner->id,
            'title' => 'Owner Uploaded Item',
            'category' => 'lab_manual',
            'department' => 'Civil',
            'description' => 'Sample manual for deletion.',
            'is_approved' => true,
        ]);

        $item2 = ELibraryItem::create([
            'user_id' => $owner->id,
            'title' => 'Second Item for Admin Delete',
            'category' => 'ebook',
            'department' => 'Electrical',
            'description' => 'Sample book for admin delete.',
            'is_approved' => true,
        ]);

        // Stranger cannot delete (403)
        $responseStranger = $this->actingAs($stranger)->delete(route('elibrary.destroy', $item1->id));
        $responseStranger->assertStatus(403);

        // Owner can delete item1
        $responseOwner = $this->actingAs($owner)->delete(route('elibrary.destroy', $item1->id));
        $responseOwner->assertRedirect(route('elibrary.index'));
        $this->assertDatabaseMissing('e_library_items', ['id' => $item1->id]);

        // Admin can delete item2
        $responseAdmin = $this->actingAs($admin)->delete(route('elibrary.destroy', $item2->id));
        $responseAdmin->assertRedirect(route('elibrary.index'));
        $this->assertDatabaseMissing('e_library_items', ['id' => $item2->id]);
    }
}
