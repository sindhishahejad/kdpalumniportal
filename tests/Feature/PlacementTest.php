<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\PlacementPartner;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PlacementTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_placement_page_renders_successfully(): void
    {
        PlacementPartner::create([
            'name' => 'Larsen & Toubro (L&T)',
            'sector' => 'EPC & Heavy Infrastructure',
            'package_range' => '₹3.2 - 4.5 LPA',
            'roles_offered' => 'Diploma Engineer Trainee (DET)',
            'hiring_departments' => 'Civil Engineering, Mechanical Engineering',
            'is_active' => true,
        ]);

        $response = $this->get(route('placement.index'));

        $response->assertStatus(200);
        $response->assertSee('Training &amp; Placement Cell (TPO)', false);
        $response->assertSee('Corporate Recruitment Partners &amp; Placement Highlights', false);
        $response->assertSee('Larsen &amp; Toubro (L&amp;T)', false);
        $response->assertSee('EPC &amp; Heavy Infrastructure', false);
        $response->assertSee('₹3.2 - 4.5 LPA', false);
    }

    public function test_homepage_renders_recruiters_wall(): void
    {
        PlacementPartner::create([
            'name' => 'Adani Group',
            'sector' => 'Energy & Infrastructure',
            'package_range' => '₹3.0 - 4.2 LPA',
            'is_active' => true,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Major Corporate Recruiters &amp; Campus Placements', false);
        $response->assertSee('Adani Group', false);
        $response->assertSee('Training &amp; Placement Cell (TPO)', false);
    }

    public function test_placement_page_can_filter_by_department(): void
    {
        PlacementPartner::create([
            'name' => 'Siemens Automation',
            'sector' => 'Industrial Automation',
            'hiring_departments' => 'Computer Engineering',
            'is_active' => true,
        ]);

        PlacementPartner::create([
            'name' => 'Welspun Corp',
            'sector' => 'Pipe Manufacturing',
            'hiring_departments' => 'Civil Engineering',
            'is_active' => true,
        ]);

        // Filter by Computer
        $response = $this->get(route('placement.index', ['department' => 'Computer']));
        $response->assertStatus(200);
        $response->assertSee('Siemens Automation', false);
        $response->assertDontSee('Welspun Corp', false);

        // Filter by Civil
        $responseCivil = $this->get(route('placement.index', ['department' => 'Civil']));
        $responseCivil->assertStatus(200);
        $responseCivil->assertSee('Welspun Corp', false);
        $responseCivil->assertDontSee('Siemens Automation', false);
    }

    public function test_placement_page_can_search_by_keyword(): void
    {
        PlacementPartner::create([
            'name' => 'Tata Motors',
            'sector' => 'Automotive & EV Manufacturing',
            'is_active' => true,
        ]);

        PlacementPartner::create([
            'name' => 'Torrent Power',
            'sector' => 'Power Generation & Distribution',
            'is_active' => true,
        ]);

        $response = $this->get(route('placement.index', ['search' => 'Motors']));
        $response->assertStatus(200);
        $response->assertSee('Tata Motors', false);
        $response->assertDontSee('Torrent Power', false);
    }

    public function test_admin_can_add_placement_partner(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_approved' => true]);

        $payload = [
            'name' => 'Suzuki Motor Gujarat',
            'sector' => 'Automotive Manufacturing',
            'package_range' => '₹2.9 - 3.8 LPA',
            'roles_offered' => 'Production Trainee',
            'hiring_departments' => 'Mechanical Engineering, Electrical Engineering',
            'website_url' => 'https://www.marutisuzuki.com',
            'order_num' => 5,
        ];

        $response = $this->actingAs($admin)->post(route('admin.placement.partners.store'), $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('placement_partners', [
            'name' => 'Suzuki Motor Gujarat',
            'sector' => 'Automotive Manufacturing',
            'package_range' => '₹2.9 - 3.8 LPA',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_update_placement_partner(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_approved' => true]);

        $partner = PlacementPartner::create([
            'name' => 'Old Name',
            'sector' => 'General Engineering',
            'package_range' => '₹2.5 LPA',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.placement.partners.update', $partner), [
            'name' => 'Hitachi Energy',
            'sector' => 'High-Voltage Power Grid',
            'package_range' => '₹3.5 - 4.2 LPA',
            'roles_offered' => 'Commissioning Specialist',
            'hiring_departments' => 'Electrical Engineering',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $partner->refresh();
        $this->assertEquals('Hitachi Energy', $partner->name);
        $this->assertEquals('High-Voltage Power Grid', $partner->sector);
        $this->assertEquals('₹3.5 - 4.2 LPA', $partner->package_range);
    }

    public function test_admin_can_delete_placement_partner(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_approved' => true]);

        $partner = PlacementPartner::create([
            'name' => 'Partner To Delete',
            'sector' => 'Testing',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.placement.partners.destroy', $partner));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('placement_partners', ['id' => $partner->id]);
    }

    public function test_non_admin_cannot_manage_placement_partners(): void
    {
        $alumni = User::factory()->create(['role' => 'alumni', 'is_approved' => true]);

        $partner = PlacementPartner::create([
            'name' => 'Guarded Partner',
            'sector' => 'Security',
            'is_active' => true,
        ]);

        $this->actingAs($alumni)->post(route('admin.placement.partners.store'), ['name' => 'Fail', 'sector' => 'Test'])->assertStatus(403);
        $this->actingAs($alumni)->put(route('admin.placement.partners.update', $partner), ['name' => 'Fail', 'sector' => 'Test'])->assertStatus(403);
        $this->actingAs($alumni)->delete(route('admin.placement.partners.destroy', $partner))->assertStatus(403);
    }

    public function test_validation_rules_for_adding_placement_partner(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_approved' => true]);

        $response = $this->actingAs($admin)->post(route('admin.placement.partners.store'), []);

        $response->assertSessionHasErrors(['name', 'sector']);
    }
}
