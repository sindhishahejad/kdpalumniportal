<?php

namespace Tests\Feature;

use App\Models\AwardCategory;
use App\Models\AwardCycle;
use App\Models\AwardNomination;
use App\Models\AwardVote;
use App\Models\HallOfFameInductee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AwardTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $alumni;
    protected User $student;
    protected AwardCycle $activeCycle;
    protected AwardCategory $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'is_approved' => true,
        ]);

        $this->alumni = User::factory()->create([
            'role' => 'alumni',
            'is_approved' => true,
            'department' => 'Mechanical Engineering',
            'graduation_year' => 2012,
        ]);

        $this->student = User::factory()->create([
            'role' => 'student',
            'is_approved' => true,
            'department' => 'Electrical Engineering',
        ]);

        $this->activeCycle = AwardCycle::create([
            'year' => 2026,
            'title' => 'KDP Annual Alumni Awards 2026',
            'theme' => 'Four Decades of Engineering Excellence',
            'status' => 'voting_open',
            'is_active' => true,
        ]);

        $this->category = AwardCategory::create([
            'award_cycle_id' => $this->activeCycle->id,
            'name' => 'Distinguished Industrial Leader',
            'slug' => 'distinguished-industrial-leader',
            'icon' => '🏭',
            'description' => 'Honoring plant heads and chief engineers.',
            'eligibility_criteria' => 'Minimum 10 years experience.',
            'display_order' => 1,
        ]);
    }

    public function test_user_can_view_awards_hub(): void
    {
        $response = $this->actingAs($this->alumni)->get(route('awards.index'));

        $response->assertStatus(200);
        $response->assertSee('KDP Annual Alumni Awards 2026');
        $response->assertSee('Distinguished Industrial Leader');
    }

    public function test_user_can_view_nomination_form(): void
    {
        $this->activeCycle->update(['status' => 'nominations_open']);

        $response = $this->actingAs($this->alumni)->get(route('awards.nominate'));

        $response->assertStatus(200);
        $response->assertSee('Nominate a Distinguished Alumnus');
    }

    public function test_user_can_submit_valid_nomination(): void
    {
        $this->activeCycle->update(['status' => 'nominations_open']);

        $response = $this->actingAs($this->alumni)->post(route('awards.nominate.store'), [
            'award_category_id' => $this->category->id,
            'nominee_name' => 'Er. Vikram V. Solanki',
            'nominee_department' => 'Mechanical Engineering',
            'nominee_batch_year' => 2008,
            'current_organization' => 'Adani Power',
            'designation' => 'Lead Turbine Specialist',
            'achievement_summary' => 'Commissioned supercritical thermal boilers achieving 99.4% plant uptime.',
            'impact_statement' => 'Trained 40 diploma interns from KDP.',
            'portfolio_url' => 'https://linkedin.com/in/vikramsolanki',
        ]);

        $response->assertRedirect(route('awards.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('award_nominations', [
            'nominee_name' => 'Er. Vikram V. Solanki',
            'status' => 'pending',
            'is_winner' => false,
        ]);
    }

    public function test_nomination_requires_mandatory_fields(): void
    {
        $response = $this->actingAs($this->alumni)->post(route('awards.nominate.store'), []);

        $response->assertSessionHasErrors(['award_category_id', 'nominee_name', 'nominee_department', 'nominee_batch_year', 'achievement_summary']);
    }

    public function test_user_can_view_category_with_approved_nominees(): void
    {
        $nominee = AwardNomination::create([
            'award_cycle_id' => $this->activeCycle->id,
            'award_category_id' => $this->category->id,
            'nominator_user_id' => $this->alumni->id,
            'nominee_name' => 'Jaimin R. Dave',
            'nominee_department' => 'Mechanical Engineering',
            'nominee_batch_year' => 2010,
            'achievement_summary' => 'Developed CNC automation tooling in GIDC.',
            'status' => 'approved',
        ]);

        $response = $this->actingAs($this->student)->get(route('awards.category', $this->category->slug));

        $response->assertStatus(200);
        $response->assertSee('Jaimin R. Dave');
        $response->assertSee('Developed CNC automation tooling');
    }

    public function test_user_can_cast_vote_for_approved_nominee(): void
    {
        $nominee = AwardNomination::create([
            'award_cycle_id' => $this->activeCycle->id,
            'award_category_id' => $this->category->id,
            'nominator_user_id' => $this->alumni->id,
            'nominee_name' => 'Jaimin R. Dave',
            'nominee_department' => 'Mechanical Engineering',
            'nominee_batch_year' => 2010,
            'achievement_summary' => 'Developed CNC automation tooling in GIDC.',
            'status' => 'approved',
        ]);

        $response = $this->actingAs($this->student)->post(route('awards.vote'), [
            'award_category_id' => $this->category->id,
            'award_nomination_id' => $nominee->id,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('award_votes', [
            'award_cycle_id' => $this->activeCycle->id,
            'award_category_id' => $this->category->id,
            'award_nomination_id' => $nominee->id,
            'user_id' => $this->student->id,
        ]);
    }

    public function test_user_cannot_vote_twice_in_same_category(): void
    {
        $nominee1 = AwardNomination::create([
            'award_cycle_id' => $this->activeCycle->id,
            'award_category_id' => $this->category->id,
            'nominator_user_id' => $this->alumni->id,
            'nominee_name' => 'Candidate One',
            'nominee_department' => 'Mechanical Engineering',
            'nominee_batch_year' => 2010,
            'achievement_summary' => 'Great achievements summary text.',
            'status' => 'approved',
        ]);

        $nominee2 = AwardNomination::create([
            'award_cycle_id' => $this->activeCycle->id,
            'award_category_id' => $this->category->id,
            'nominator_user_id' => $this->alumni->id,
            'nominee_name' => 'Candidate Two',
            'nominee_department' => 'Mechanical Engineering',
            'nominee_batch_year' => 2011,
            'achievement_summary' => 'Another great set of achievements.',
            'status' => 'approved',
        ]);

        // First vote
        $this->actingAs($this->student)->post(route('awards.vote'), [
            'award_category_id' => $this->category->id,
            'award_nomination_id' => $nominee1->id,
        ])->assertSessionHas('success');

        // Second vote should be rejected
        $response = $this->actingAs($this->student)->post(route('awards.vote'), [
            'award_category_id' => $this->category->id,
            'award_nomination_id' => $nominee2->id,
        ]);

        $response->assertSessionHas('error', 'You have already cast your vote in this category.');
        $this->assertEquals(1, AwardVote::where('user_id', $this->student->id)->count());
    }

    public function test_nominee_cannot_vote_for_self(): void
    {
        $nominee = AwardNomination::create([
            'award_cycle_id' => $this->activeCycle->id,
            'award_category_id' => $this->category->id,
            'nominator_user_id' => $this->admin->id,
            'nominee_user_id' => $this->alumni->id,
            'nominee_name' => $this->alumni->name,
            'nominee_department' => $this->alumni->department,
            'nominee_batch_year' => $this->alumni->graduation_year,
            'achievement_summary' => 'Industrial leader achievements summary.',
            'status' => 'approved',
        ]);

        $response = $this->actingAs($this->alumni)->post(route('awards.vote'), [
            'award_category_id' => $this->category->id,
            'award_nomination_id' => $nominee->id,
        ]);

        $response->assertSessionHas('error', 'Self-voting is not permitted under KDP Awards Committee guidelines.');
        $this->assertDatabaseMissing('award_votes', [
            'user_id' => $this->alumni->id,
        ]);
    }

    public function test_cannot_vote_when_cycle_is_not_voting_open(): void
    {
        $this->activeCycle->update(['status' => 'nominations_open']);

        $nominee = AwardNomination::create([
            'award_cycle_id' => $this->activeCycle->id,
            'award_category_id' => $this->category->id,
            'nominator_user_id' => $this->alumni->id,
            'nominee_name' => 'Candidate Name',
            'nominee_department' => 'Mechanical Engineering',
            'nominee_batch_year' => 2010,
            'achievement_summary' => 'Engineering achievements summary.',
            'status' => 'approved',
        ]);

        $response = $this->actingAs($this->student)->post(route('awards.vote'), [
            'award_category_id' => $this->category->id,
            'award_nomination_id' => $nominee->id,
        ]);

        $response->assertSessionHas('error', 'Voting is not currently open for this award cycle.');
    }

    public function test_user_can_view_virtual_hall_of_fame_and_filter(): void
    {
        HallOfFameInductee::create([
            'name' => 'Er. Dharmesh Patel',
            'department' => 'Mechanical Engineering',
            'batch_year' => 1988,
            'induction_year' => 2025,
            'award_title' => 'Lifetime Pioneer Award',
            'citation' => 'Pioneered precision CNC manufacturing across North Gujarat.',
            'company' => 'Patel Toolings',
        ]);

        $response = $this->actingAs($this->student)->get(route('awards.hall-of-fame', ['year' => 2025]));

        $response->assertStatus(200);
        $response->assertSee('Er. Dharmesh Patel');
        $response->assertSee('Lifetime Pioneer Award');
    }

    public function test_admin_can_access_awards_management_dashboard(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.awards.index'));

        $response->assertStatus(200);
        $response->assertSee('Alumni Awards');
        $response->assertSee('Nominations');
    }

    public function test_non_admin_cannot_access_admin_awards_management(): void
    {
        $response = $this->actingAs($this->alumni)->get(route('admin.awards.index'));

        $response->assertStatus(403);
    }

    public function test_admin_can_approve_nomination(): void
    {
        $nomination = AwardNomination::create([
            'award_cycle_id' => $this->activeCycle->id,
            'award_category_id' => $this->category->id,
            'nominator_user_id' => $this->alumni->id,
            'nominee_name' => 'Pending Alumnus',
            'nominee_department' => 'Mechanical Engineering',
            'nominee_batch_year' => 2012,
            'achievement_summary' => 'Candidate accomplishments description.',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->patch(route('admin.awards.nominations.update-status', $nomination->id), [
            'status' => 'approved',
            'admin_notes' => 'Verified with GIDC records.',
        ]);

        $response->assertRedirect();
        $this->assertEquals('approved', $nomination->fresh()->status);
    }

    public function test_admin_can_induct_winner_into_hall_of_fame(): void
    {
        $nomination = AwardNomination::create([
            'award_cycle_id' => $this->activeCycle->id,
            'award_category_id' => $this->category->id,
            'nominator_user_id' => $this->alumni->id,
            'nominee_name' => 'Winning Alumnus',
            'nominee_department' => 'Electrical Engineering',
            'nominee_batch_year' => 2004,
            'current_organization' => 'GETCO',
            'designation' => 'Executive Engineer',
            'achievement_summary' => 'Modernized 220kV power substations.',
            'status' => 'approved',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.awards.nominations.induct', $nomination->id), [
            'citation' => 'For stellar leadership in electrical grid resilience during natural disasters.',
            'is_featured' => 1,
        ]);

        $response->assertRedirect();
        $this->assertTrue($nomination->fresh()->is_winner);

        $this->assertDatabaseHas('hall_of_fame_inductees', [
            'name' => 'Winning Alumnus',
            'award_title' => $this->category->name,
            'induction_year' => 2026,
            'is_featured' => true,
        ]);
    }

    public function test_admin_can_dynamically_create_award_cycle(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.awards.cycles.store'), [
            'year' => 2027,
            'title' => 'KDP Annual Alumni Awards 2027',
            'theme' => 'Next-Gen Automation & Green Robotics',
            'status' => 'nominations_open',
            'is_active' => 1,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('award_cycles', [
            'year' => 2027,
            'title' => 'KDP Annual Alumni Awards 2027',
            'is_active' => true,
        ]);
        // Previously active cycle should now be inactive
        $this->assertFalse($this->activeCycle->fresh()->is_active);
    }

    public function test_admin_can_dynamically_create_award_category(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.awards.categories.store'), [
            'award_cycle_id' => $this->activeCycle->id,
            'name' => 'Renewable Energy Innovator',
            'icon' => '⚡',
            'description' => 'Honoring breakthroughs in solar and wind engineering.',
            'eligibility_criteria' => 'Demonstrated project in green energy grid.',
            'display_order' => 5,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('award_categories', [
            'award_cycle_id' => $this->activeCycle->id,
            'name' => 'Renewable Energy Innovator',
            'slug' => 'renewable-energy-innovator',
            'icon' => '⚡',
        ]);
    }

    public function test_admin_can_dynamically_delete_award_category(): void
    {
        $category = AwardCategory::create([
            'award_cycle_id' => $this->activeCycle->id,
            'name' => 'Temporary Category',
            'slug' => 'temporary-category',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.awards.categories.destroy', $category->id));

        $response->assertRedirect();
        $this->assertDatabaseMissing('award_categories', ['id' => $category->id]);
    }

    public function test_admin_can_directly_induct_alumnus_into_hall_of_fame(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.awards.inductees.store'), [
            'name' => 'Er. Natubhai K. Prajapati',
            'department' => 'Mechanical Engineering',
            'batch_year' => 1985,
            'induction_year' => 2026,
            'award_title' => 'Lifetime Pillar of North Gujarat Industry',
            'citation' => 'Built foundational fabrication infrastructure employing 200+ diploma technicians across Patan district.',
            'company' => 'Prajapati Heavy Fabricators',
            'current_role' => 'Founder & Chairman',
            'is_featured' => 1,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('hall_of_fame_inductees', [
            'name' => 'Er. Natubhai K. Prajapati',
            'batch_year' => 1985,
            'induction_year' => 2026,
            'is_featured' => true,
        ]);
    }

    public function test_admin_can_dynamically_delete_hall_of_fame_inductee(): void
    {
        $inductee = HallOfFameInductee::create([
            'name' => 'Temporary Inductee',
            'department' => 'Civil Engineering',
            'batch_year' => 1995,
            'induction_year' => 2020,
            'award_title' => 'Honor',
            'citation' => 'Detailed citation text exceeding twenty characters.',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.awards.inductees.destroy', $inductee->id));

        $response->assertRedirect();
        $this->assertDatabaseMissing('hall_of_fame_inductees', ['id' => $inductee->id]);
    }

    public function test_admin_can_update_award_cycle_stage_dynamically(): void
    {
        // 1. Advance to Step 1: Open Nominations
        $res1 = $this->actingAs($this->admin)->patch(route('admin.awards.cycles.update-status', $this->activeCycle->id), [
            'status' => 'nominations_open',
        ]);
        $res1->assertRedirect();
        $this->assertEquals('nominations_open', $this->activeCycle->fresh()->status);

        // 2. Advance to Step 2: Jury Shortlisting
        $res2 = $this->actingAs($this->admin)->patch(route('admin.awards.cycles.update-status', $this->activeCycle->id), [
            'status' => 'draft',
        ]);
        $res2->assertRedirect();
        $this->assertEquals('draft', $this->activeCycle->fresh()->status);

        // 3. Advance to Step 3: Live Community Voting
        $res3 = $this->actingAs($this->admin)->patch(route('admin.awards.cycles.update-status', $this->activeCycle->id), [
            'status' => 'voting_open',
        ]);
        $res3->assertRedirect();
        $this->assertEquals('voting_open', $this->activeCycle->fresh()->status);

        // 4. Advance to Step 4: Convocation Enshrinement
        $res4 = $this->actingAs($this->admin)->patch(route('admin.awards.cycles.update-status', $this->activeCycle->id), [
            'status' => 'concluded',
        ]);
        $res4->assertRedirect();
        $this->assertEquals('concluded', $this->activeCycle->fresh()->status);
    }

    public function test_awards_hero_stepper_and_stage_pill_dynamically_highlight_active_step(): void
    {
        // When cycle is in Step 3: voting_open
        $this->activeCycle->update(['status' => 'voting_open']);
        $response = $this->actingAs($this->alumni)->get(route('awards.index'));
        $response->assertStatus(200);
        $response->assertSee('Stage 3: Community Voting Active');
        $response->assertDontSee('Stage 2: Community Voting Active');

        // When cycle is in Step 2: draft / jury review
        $this->activeCycle->update(['status' => 'draft']);
        $response2 = $this->actingAs($this->alumni)->get(route('awards.index'));
        $response2->assertStatus(200);
        $response2->assertSee('Stage 2: Jury Review &amp; Shortlisting', false);

        // When admin visits, admin 1-click stage activation controls are present
        $adminResponse = $this->actingAs($this->admin)->get(route('awards.index'));
        $adminResponse->assertStatus(200);
        $adminResponse->assertSee('ADMIN CONTROLS: Click any stage below to dynamically advance');
        $adminResponse->assertSee('Activate Step 1');
        $adminResponse->assertSee('Activate Step 3');
        $adminResponse->assertSee('Activate Step 4');

        // When non-admin visits, admin buttons are hidden
        $alumniResponse = $this->actingAs($this->alumni)->get(route('awards.index'));
        $alumniResponse->assertDontSee('ADMIN CONTROLS: Click any stage below');
        $alumniResponse->assertDontSee('Activate Step 1');
    }
}
