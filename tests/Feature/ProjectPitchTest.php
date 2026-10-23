<?php

namespace Tests\Feature;

use App\Models\Message;
use App\Models\ProjectPitch;
use App\Models\User;
use App\Notifications\PortalNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ProjectPitchTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_incubation_board_and_filter_by_status(): void
    {
        $viewer = User::factory()->create(['role' => 'alumni', 'is_approved' => true]);

        $openPitch = ProjectPitch::create([
            'user_id' => $viewer->id,
            'title' => 'Smart Irrigation IoT',
            'description' => 'Automated soil moisture sensing.',
            'tech_stack' => 'ESP8266, C++',
            'assistance_needed' => 'Circuit review',
            'status' => 'open',
        ]);

        $completedPitch = ProjectPitch::create([
            'user_id' => $viewer->id,
            'title' => 'Solar Tracking Array',
            'description' => 'Dual axis solar tracking.',
            'tech_stack' => 'Arduino, Servos',
            'assistance_needed' => '3D printing',
            'status' => 'completed',
        ]);

        $response = $this->actingAs($viewer)->get(route('pitches.index', ['status' => 'open']));

        $response->assertStatus(200);
        $response->assertSee('Smart Irrigation IoT');
        $response->assertDontSee('Solar Tracking Array');
    }

    public function test_student_can_create_project_pitch(): void
    {
        $student = User::factory()->create(['role' => 'student', 'is_approved' => true]);

        $response = $this->actingAs($student)->post(route('pitches.store'), [
            'title' => 'Electric Go-Kart Telemetry',
            'description' => 'Live speed and battery monitoring over 4G LTE.',
            'tech_stack' => 'Raspberry Pi, Python, MQTT',
            'assistance_needed' => 'Battery cell safety guidance',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('project_pitches', [
            'title' => 'Electric Go-Kart Telemetry',
            'user_id' => $student->id,
            'status' => 'open',
        ]);
    }

    public function test_user_can_view_project_pitch_details(): void
    {
        $student = User::factory()->create(['role' => 'student', 'is_approved' => true]);
        $pitch = ProjectPitch::create([
            'user_id' => $student->id,
            'title' => 'Autonomous Quadcopter',
            'description' => 'Obstacle avoidance with ultrasonic sensors.',
            'tech_stack' => 'ArduPilot, ROS',
            'assistance_needed' => 'PID tuning',
            'status' => 'open',
        ]);

        $response = $this->actingAs($student)->get(route('pitches.show', $pitch->id));

        $response->assertStatus(200);
        $response->assertSee('Autonomous Quadcopter');
        $response->assertSee('Obstacle avoidance with ultrasonic sensors.');
        $response->assertSee('PID tuning');
    }

    public function test_pitch_owner_and_faculty_can_update_lifecycle_status(): void
    {
        Notification::fake();

        $student = User::factory()->create(['role' => 'student', 'is_approved' => true]);
        $faculty = User::factory()->create(['role' => 'faculty', 'is_approved' => true]);

        $pitch = ProjectPitch::create([
            'user_id' => $student->id,
            'title' => 'Hydroponic Controller',
            'description' => 'pH and nutrient auto dosing.',
            'tech_stack' => 'NodeMCU',
            'assistance_needed' => 'Pump calibration',
            'status' => 'open',
        ]);

        // Student owner updates to in_progress
        $response1 = $this->actingAs($student)->patch(route('pitches.update-status', $pitch->id), [
            'status' => 'in_progress',
        ]);
        $response1->assertRedirect();
        $this->assertEquals('in_progress', $pitch->fresh()->status);

        // Faculty updates to completed
        $response2 = $this->actingAs($faculty)->patch(route('pitches.update-status', $pitch->id), [
            'status' => 'completed',
        ]);
        $response2->assertRedirect();
        $this->assertEquals('completed', $pitch->fresh()->status);

        Notification::assertSentTo($student, PortalNotification::class);
    }

    public function test_student_cannot_update_other_students_pitch_status(): void
    {
        $owner = User::factory()->create(['role' => 'student', 'is_approved' => true]);
        $otherStudent = User::factory()->create(['role' => 'student', 'is_approved' => true]);

        $pitch = ProjectPitch::create([
            'user_id' => $owner->id,
            'title' => 'Secret Invention',
            'description' => 'Top secret.',
            'tech_stack' => 'C',
            'assistance_needed' => 'None',
            'status' => 'open',
        ]);

        $response = $this->actingAs($otherStudent)->patch(route('pitches.update-status', $pitch->id), [
            'status' => 'completed',
        ]);

        $response->assertStatus(403);
        $this->assertEquals('open', $pitch->fresh()->status);
    }

    public function test_alumni_can_offer_guidance_delivering_message_and_notification(): void
    {
        Notification::fake();

        $student = User::factory()->create(['role' => 'student', 'is_approved' => true]);
        $alumni = User::factory()->create(['role' => 'alumni', 'is_approved' => true]);

        $pitch = ProjectPitch::create([
            'user_id' => $student->id,
            'title' => 'CNC Foam Cutter',
            'description' => 'Stepper driven foam cutter for prototyping.',
            'tech_stack' => 'GRBL, Arduino',
            'assistance_needed' => 'Motor driver heat sinking',
            'status' => 'open',
        ]);

        $guidanceText = 'I have extra A4988 heatsinks and driver shields from my previous project. Happy to mentor and send them over!';

        $response = $this->actingAs($alumni)->post(route('pitches.offer-guidance', $pitch->id), [
            'guidance' => $guidanceText,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Check in-app chat message created
        $this->assertDatabaseHas('messages', [
            'sender_id' => $alumni->id,
            'recipient_id' => $student->id,
        ]);

        // Check student notified
        Notification::assertSentTo($student, PortalNotification::class);
    }

    public function test_user_cannot_offer_guidance_to_own_pitch(): void
    {
        $student = User::factory()->create(['role' => 'student', 'is_approved' => true]);

        $pitch = ProjectPitch::create([
            'user_id' => $student->id,
            'title' => 'Self Guiding',
            'description' => 'Desc',
            'tech_stack' => 'Python',
            'assistance_needed' => 'Help',
            'status' => 'open',
        ]);

        $response = $this->actingAs($student)->post(route('pitches.offer-guidance', $pitch->id), [
            'guidance' => 'Trying to mentor myself.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_pitch_owner_or_admin_can_delete_pitch(): void
    {
        $student = User::factory()->create(['role' => 'student', 'is_approved' => true]);
        $admin = User::factory()->create(['role' => 'admin', 'is_approved' => true]);

        $pitch1 = ProjectPitch::create([
            'user_id' => $student->id,
            'title' => 'Delete Me By Student',
            'description' => 'Desc',
            'tech_stack' => 'Python',
            'assistance_needed' => 'None',
            'status' => 'open',
        ]);

        $pitch2 = ProjectPitch::create([
            'user_id' => $student->id,
            'title' => 'Delete Me By Admin',
            'description' => 'Desc',
            'tech_stack' => 'Python',
            'assistance_needed' => 'None',
            'status' => 'open',
        ]);

        // Student deletes pitch1
        $this->actingAs($student)->delete(route('pitches.destroy', $pitch1->id))->assertRedirect(route('pitches.index'));
        $this->assertDatabaseMissing('project_pitches', ['id' => $pitch1->id]);

        // Admin deletes pitch2
        $this->actingAs($admin)->delete(route('pitches.destroy', $pitch2->id))->assertRedirect(route('pitches.index'));
        $this->assertDatabaseMissing('project_pitches', ['id' => $pitch2->id]);
    }
}
