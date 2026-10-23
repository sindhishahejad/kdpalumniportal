<?php

namespace Tests\Feature;

use App\Models\ToolDonation;
use App\Models\ToolDonationGratitude;
use App\Models\ToolDonationRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ToolDonationTest extends TestCase
{
    use RefreshDatabase;

    protected User $alumni;
    protected User $student;
    protected User $admin;
    protected ToolDonation $donation;

    protected function setUp(): void
    {
        parent::setUp();

        $this->alumni = User::factory()->create([
            'role' => 'alumni',
            'is_approved' => true,
            'department' => 'Mechanical Engineering',
            'graduation_year' => 2018,
        ]);

        $this->student = User::factory()->create([
            'role' => 'student',
            'is_approved' => true,
            'department' => 'Civil Engineering',
        ]);

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'is_approved' => true,
        ]);

        $this->donation = ToolDonation::create([
            'user_id' => $this->alumni->id,
            'title' => 'Rotring Mini Drafter + Board Set',
            'slug' => 'rotring-mini-drafter-board-set',
            'item_type' => 'drawing_tools',
            'department' => 'Mechanical Engineering',
            'semester_suitability' => 'Sem 1-2',
            'condition' => 'like_new',
            'description' => 'Used for first year diploma engineering drawing graphics.',
            'pickup_method' => 'campus_locker',
            'pickup_location' => 'Mechanical Workshop Office',
            'estimated_value' => 1400,
            'status' => 'available',
        ]);
    }

    public function test_user_can_view_tool_bank_catalog(): void
    {
        $response = $this->actingAs($this->student)->get(route('tools.index'));

        $response->assertStatus(200);
        $response->assertSee('Rotring Mini Drafter');
        $response->assertSee('Save ₹1,400');
    }

    public function test_user_can_filter_tools_by_item_type(): void
    {
        ToolDonation::create([
            'user_id' => $this->alumni->id,
            'title' => 'Casio FX-991EX Calculator',
            'slug' => 'casio-fx-991ex-calculator',
            'item_type' => 'calculators_electronics',
            'department' => 'General / All Branches',
            'semester_suitability' => 'All Semesters',
            'condition' => 'like_new',
            'description' => 'Original scientific calculator for diploma calculations.',
            'pickup_method' => 'campus_locker',
            'estimated_value' => 1600,
            'status' => 'available',
        ]);

        $response = $this->actingAs($this->student)->get(route('tools.index', ['item_type' => 'calculators_electronics']));

        $response->assertStatus(200);
        $response->assertSee('Casio FX-991EX Calculator');
        $response->assertDontSee('Rotring Mini Drafter');
    }

    public function test_user_can_view_donation_creation_form(): void
    {
        $response = $this->actingAs($this->alumni)->get(route('tools.create'));

        $response->assertStatus(200);
        $response->assertSee('Donate Books or Engineering Tools');
    }

    public function test_alumni_can_donate_an_item(): void
    {
        $response = $this->actingAs($this->alumni)->post(route('tools.store'), [
            'title' => 'Arduino Uno IoT Starter Kit',
            'item_type' => 'lab_hardware',
            'department' => 'Electrical Engineering',
            'semester_suitability' => 'Sem 5-6',
            'condition' => 'brand_new',
            'description' => 'Complete microcontroller starter kit with ultrasonic sensor and relays.',
            'pickup_method' => 'campus_locker',
            'pickup_location' => 'Electrical Department Office',
            'estimated_value' => 1800,
            'donor_note' => 'Best of luck for your diploma final project!',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tool_donations', [
            'title' => 'Arduino Uno IoT Starter Kit',
            'user_id' => $this->alumni->id,
            'status' => 'available',
        ]);
    }

    public function test_donation_requires_mandatory_fields(): void
    {
        $response = $this->actingAs($this->alumni)->post(route('tools.store'), []);

        $response->assertSessionHasErrors(['title', 'item_type', 'department', 'condition', 'description', 'estimated_value', 'pickup_method']);
    }

    public function test_user_can_view_tool_details_page(): void
    {
        $response = $this->actingAs($this->student)->get(route('tools.show', $this->donation->slug));

        $response->assertStatus(200);
        $response->assertSee('Rotring Mini Drafter');
        $response->assertSee('Mechanical Workshop Office');
    }

    public function test_junior_student_can_request_available_tool(): void
    {
        $response = $this->actingAs($this->student)->post(route('tools.request', $this->donation->id), [
            'reason' => 'I am enrolled in Civil 1st sem and need this drafter for my Engineering Graphics practicals.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('tool_donation_requests', [
            'tool_donation_id' => $this->donation->id,
            'user_id' => $this->student->id,
            'status' => 'pending',
        ]);
    }

    public function test_donor_cannot_request_own_item(): void
    {
        $response = $this->actingAs($this->alumni)->post(route('tools.request', $this->donation->id), [
            'reason' => 'Self claiming is invalid.',
        ]);

        $response->assertSessionHas('error', 'You cannot request your own donated item.');
    }

    public function test_user_cannot_submit_duplicate_request(): void
    {
        ToolDonationRequest::create([
            'tool_donation_id' => $this->donation->id,
            'user_id' => $this->student->id,
            'reason' => 'Initial valid request.',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->student)->post(route('tools.request', $this->donation->id), [
            'reason' => 'Second attempt should fail.',
        ]);

        $response->assertSessionHas('error', 'You have already submitted a request for this item.');
    }

    public function test_donor_can_view_my_donations(): void
    {
        $response = $this->actingAs($this->alumni)->get(route('tools.my-donations'));

        $response->assertStatus(200);
        $response->assertSee('Rotring Mini Drafter');
    }

    public function test_student_can_view_my_requests(): void
    {
        ToolDonationRequest::create([
            'tool_donation_id' => $this->donation->id,
            'user_id' => $this->student->id,
            'reason' => 'Semester requirement.',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->student)->get(route('tools.my-requests'));

        $response->assertStatus(200);
        $response->assertSee('Rotring Mini Drafter');
        $response->assertSee('Semester requirement.');
    }

    public function test_donor_can_accept_student_claim_request(): void
    {
        $request = ToolDonationRequest::create([
            'tool_donation_id' => $this->donation->id,
            'user_id' => $this->student->id,
            'reason' => 'Need drafter for first year diploma.',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->alumni)->patch(route('tools.requests.accept', $request->id), [
            'donor_response' => 'Accepted! Pick up from Workshop Office Locker #14.',
        ]);

        $response->assertRedirect();
        $this->assertEquals('accepted', $request->fresh()->status);
        $this->assertEquals('claimed', $this->donation->fresh()->status);
        $this->assertEquals($this->student->id, $this->donation->fresh()->claimed_by_user_id);
    }

    public function test_donor_can_confirm_item_handed_over(): void
    {
        $this->donation->update([
            'status' => 'claimed',
            'claimed_by_user_id' => $this->student->id,
            'claimed_at' => now(),
        ]);

        $response = $this->actingAs($this->alumni)->patch(route('tools.handover', $this->donation->id));

        $response->assertRedirect();
        $this->assertEquals('handed_over', $this->donation->fresh()->status);
        $this->assertNotNull($this->donation->fresh()->handed_over_at);
    }

    public function test_recipient_can_post_gratitude_note(): void
    {
        $this->donation->update([
            'status' => 'handed_over',
            'claimed_by_user_id' => $this->student->id,
            'handed_over_at' => now(),
        ]);

        $response = $this->actingAs($this->student)->post(route('tools.gratitude', $this->donation->id), [
            'message' => 'Thank you so much Er. Bhavesh! The drafter is in great condition and saved me ₹1,400.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tool_donation_gratitudes', [
            'tool_donation_id' => $this->donation->id,
            'user_id' => $this->student->id,
        ]);
    }

    public function test_donor_can_delete_donation_item(): void
    {
        $response = $this->actingAs($this->alumni)->delete(route('tools.destroy', $this->donation->id));

        $response->assertRedirect(route('tools.index'));
        $this->assertDatabaseMissing('tool_donations', ['id' => $this->donation->id]);
    }
}
