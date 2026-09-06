<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Campaign;
use App\Models\Endorsement;
use App\Models\KolProfile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KolDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_kol_can_view_dashboard(): void
    {
        $user = $this->createUserWithRole('kol');
        $profile = KolProfile::create([
            'user_id' => $user->id,
            'nickname' => 'Dashboard KOL',
            'status' => 'aktif',
        ]);

        $superadmin = $this->createUserWithRole('superadmin');
        $brand = Brand::create(['name' => 'TechBrand', 'industry' => 'Technology']);
        $campaign = Campaign::create([
            'name' => 'Tech Campaign Q4',
            'brand_id' => $brand->id,
            'budget' => 5000000,
            'created_by' => $superadmin->id,
            'status' => 'active',
        ]);

        Endorsement::create([
            'campaign_id' => $campaign->id,
            'kol_profile_id' => $profile->id,
            'content_type' => 'video',
            'fee' => 1500000,
            'deadline' => now()->addDays(5)->toDateString(),
            'status' => 'assigned',
            'assigned_by' => $superadmin->id,
        ]);

        $response = $this->actingAs($user)->get(route('kol.dashboard'));

        $response->assertOk();
        $response->assertSee('Dashboard KOL');
        $response->assertSee('TechBrand');
        $response->assertSee('Tech Campaign Q4');
    }

    public function test_non_kol_cannot_access_dashboard(): void
    {
        $user = $this->createUserWithRole('superadmin');

        $response = $this->actingAs($user)->get(route('kol.dashboard'));

        $response->assertForbidden();
    }

    public function test_kol_without_profile_cannot_access_dashboard(): void
    {
        $user = $this->createUserWithRole('kol');

        $response = $this->actingAs($user)->get(route('kol.dashboard'));

        $response->assertNotFound();
    }

    private function createUserWithRole(string $role): User
    {
        $user = User::factory()->create();
        $user->assignRole(Role::firstOrCreate(['name' => $role], ['display_name' => ucfirst($role)]));

        return $user;
    }
}
