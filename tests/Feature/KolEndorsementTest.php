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

class KolEndorsementTest extends TestCase
{
    use RefreshDatabase;

    public function test_kol_can_view_endorsements_list(): void
    {
        $user = $this->createUserWithRole('kol');
        $profile = KolProfile::create(['user_id' => $user->id, 'nickname' => 'Test KOL', 'status' => 'aktif']);

        $superadmin = $this->createUserWithRole('superadmin');
        $brand = Brand::create(['name' => 'Brand A', 'industry' => 'Tech']);
        $campaign = Campaign::create(['name' => 'Campaign A', 'brand_id' => $brand->id, 'budget' => 1000000, 'created_by' => $superadmin->id, 'status' => 'active']);

        Endorsement::create([
            'campaign_id' => $campaign->id,
            'kol_profile_id' => $profile->id,
            'content_type' => 'reels',
            'fee' => 1000000,
            'deadline' => now()->addDays(3)->toDateString(),
            'status' => 'assigned',
            'assigned_by' => $superadmin->id,
        ]);

        $response = $this->actingAs($user)->get(route('kol.endorsements.index'));

        $response->assertOk();
        $response->assertSee('Campaign A');
        $response->assertSee('Brand A');
    }

    public function test_kol_can_filter_endorsements_by_status(): void
    {
        $user = $this->createUserWithRole('kol');
        $profile = KolProfile::create(['user_id' => $user->id, 'nickname' => 'Test KOL', 'status' => 'aktif']);

        $superadmin = $this->createUserWithRole('superadmin');
        $brand = Brand::create(['name' => 'Brand A', 'industry' => 'Tech']);
        $campaign = Campaign::create(['name' => 'Campaign A', 'brand_id' => $brand->id, 'budget' => 1000000, 'created_by' => $superadmin->id, 'status' => 'active']);

        Endorsement::create([
            'campaign_id' => $campaign->id,
            'kol_profile_id' => $profile->id,
            'content_type' => 'reels',
            'fee' => 1000000,
            'deadline' => now()->addDays(3)->toDateString(),
            'status' => 'selesai',
            'assigned_by' => $superadmin->id,
        ]);

        $response = $this->actingAs($user)->get(route('kol.endorsements.index', ['tab' => 'aktif']));
        $response->assertOk();
        $response->assertDontSee('Campaign A');

        $responseSelesai = $this->actingAs($user)->get(route('kol.endorsements.index', ['tab' => 'riwayat']));
        $responseSelesai->assertOk();
        $responseSelesai->assertSee('Campaign A');
    }

    public function test_kol_can_view_endorsement_detail(): void
    {
        $user = $this->createUserWithRole('kol');
        $profile = KolProfile::create(['user_id' => $user->id, 'nickname' => 'Test KOL', 'status' => 'aktif']);

        $superadmin = $this->createUserWithRole('superadmin');
        $brand = Brand::create(['name' => 'Brand A', 'industry' => 'Tech']);
        $campaign = Campaign::create(['name' => 'Campaign A', 'brand_id' => $brand->id, 'budget' => 1000000, 'created_by' => $superadmin->id, 'status' => 'active', 'content_requirements' => 'Must include product']);

        $endorsement = Endorsement::create([
            'campaign_id' => $campaign->id,
            'kol_profile_id' => $profile->id,
            'content_type' => 'reels',
            'fee' => 1000000,
            'deadline' => now()->addDays(3)->toDateString(),
            'status' => 'assigned',
            'assigned_by' => $superadmin->id,
        ]);

        $response = $this->actingAs($user)->get(route('kol.endorsements.show', $endorsement));

        $response->assertOk();
        $response->assertSee('Campaign A');
        $response->assertSee('Must include product');
    }

    public function test_kol_cannot_view_other_kol_endorsement(): void
    {
        $user1 = $this->createUserWithRole('kol');
        $profile1 = KolProfile::create(['user_id' => $user1->id, 'nickname' => 'KOL 1', 'status' => 'aktif']);

        $user2 = $this->createUserWithRole('kol');
        $profile2 = KolProfile::create(['user_id' => $user2->id, 'nickname' => 'KOL 2', 'status' => 'aktif']);

        $superadmin = $this->createUserWithRole('superadmin');
        $brand = Brand::create(['name' => 'Brand A', 'industry' => 'Tech']);
        $campaign = Campaign::create(['name' => 'Campaign A', 'brand_id' => $brand->id, 'budget' => 1000000, 'created_by' => $superadmin->id, 'status' => 'active']);

        $endorsement = Endorsement::create([
            'campaign_id' => $campaign->id,
            'kol_profile_id' => $profile1->id,
            'content_type' => 'reels',
            'fee' => 1000000,
            'deadline' => now()->addDays(3)->toDateString(),
            'status' => 'assigned',
            'assigned_by' => $superadmin->id,
        ]);

        $response = $this->actingAs($user2)->get(route('kol.endorsements.show', $endorsement));

        $response->assertForbidden();
    }

    private function createUserWithRole(string $role): User
    {
        $user = User::factory()->create();
        $user->assignRole(Role::firstOrCreate(['name' => $role], ['display_name' => ucfirst($role)]));

        return $user;
    }
}
