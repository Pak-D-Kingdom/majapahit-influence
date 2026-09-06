<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Campaign;
use App\Models\Commission;
use App\Models\Endorsement;
use App\Models\KolProfile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KolCommissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_kol_can_view_commissions_list(): void
    {
        $user = $this->createUserWithRole('kol');
        $profile = KolProfile::create(['user_id' => $user->id, 'nickname' => 'Test KOL', 'status' => 'aktif']);

        $superadmin = $this->createUserWithRole('superadmin');
        $brand = Brand::create(['name' => 'Brand A', 'industry' => 'Tech']);
        $campaign = Campaign::create(['name' => 'Campaign A', 'brand_id' => $brand->id, 'budget' => 1000000, 'created_by' => $superadmin->id, 'status' => 'active']);

        $endorsement = Endorsement::create([
            'campaign_id' => $campaign->id,
            'kol_profile_id' => $profile->id,
            'content_type' => 'reels',
            'fee' => 1000000,
            'deadline' => now()->addDays(3)->toDateString(),
            'status' => 'selesai',
            'assigned_by' => $superadmin->id,
        ]);

        Commission::create([
            'endorsement_id' => $endorsement->id,
            'kol_profile_id' => $profile->id,
            'base_fee' => 1000000,
            'commission_pct' => 70,
            'commission_amount' => 700000,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)->get(route('kol.commissions.index'));

        $response->assertOk();
        $response->assertSee('700.000');
    }

    public function test_kol_can_view_commission_detail(): void
    {
        $user = $this->createUserWithRole('kol');
        $profile = KolProfile::create(['user_id' => $user->id, 'nickname' => 'Test KOL', 'status' => 'aktif']);

        $superadmin = $this->createUserWithRole('superadmin');
        $brand = Brand::create(['name' => 'Brand A', 'industry' => 'Tech']);
        $campaign = Campaign::create(['name' => 'Campaign A', 'brand_id' => $brand->id, 'budget' => 1000000, 'created_by' => $superadmin->id, 'status' => 'active']);

        $endorsement = Endorsement::create([
            'campaign_id' => $campaign->id,
            'kol_profile_id' => $profile->id,
            'content_type' => 'reels',
            'fee' => 1000000,
            'deadline' => now()->addDays(3)->toDateString(),
            'status' => 'selesai',
            'assigned_by' => $superadmin->id,
        ]);

        $commission = Commission::create([
            'endorsement_id' => $endorsement->id,
            'kol_profile_id' => $profile->id,
            'base_fee' => 1000000,
            'commission_pct' => 70,
            'commission_amount' => 700000,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)->get(route('kol.commissions.show', $commission));

        $response->assertOk();
        $response->assertSee('Detail Komisi');
    }

    public function test_kol_can_request_disbursement_for_unpaid_commission(): void
    {
        $user = $this->createUserWithRole('kol');
        $profile = KolProfile::create([
            'user_id' => $user->id,
            'nickname' => 'Test KOL',
            'status' => 'aktif',
            'bank_name' => 'BCA',
            'bank_account_number' => '123123',
            'bank_account_name' => 'Test',
        ]);

        $superadmin = $this->createUserWithRole('superadmin');
        $brand = Brand::create(['name' => 'Brand A', 'industry' => 'Tech']);
        $campaign = Campaign::create(['name' => 'Campaign A', 'brand_id' => $brand->id, 'budget' => 1000000, 'created_by' => $superadmin->id, 'status' => 'active']);

        $endorsement = Endorsement::create([
            'campaign_id' => $campaign->id,
            'kol_profile_id' => $profile->id,
            'content_type' => 'reels',
            'fee' => 1000000,
            'deadline' => now()->addDays(3)->toDateString(),
            'status' => 'selesai',
            'assigned_by' => $superadmin->id,
        ]);

        $commission = Commission::create([
            'endorsement_id' => $endorsement->id,
            'kol_profile_id' => $profile->id,
            'base_fee' => 1000000,
            'commission_pct' => 70,
            'commission_amount' => 700000,
            'status' => 'approved',
        ]);

        $response = $this->actingAs($user)->post(route('kol.commissions.request-disbursement', $commission), [
            'notes' => 'Tolong dicairkan segera',
        ]);

        $response->assertRedirect(); // Or assertSessionHasNoErrors();
        
        $this->assertDatabaseHas('commission_approvals', [
            'commission_id' => $commission->id,
            'action' => 'request'
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'commission_disbursement_requested',
            'entity_type' => 'commissions',
            'entity_id' => $commission->id,
        ]);
    }

    public function test_kol_cannot_request_disbursement_for_already_paid_commission(): void
    {
        $user = $this->createUserWithRole('kol');
        $profile = KolProfile::create(['user_id' => $user->id, 'nickname' => 'Test KOL', 'status' => 'aktif']);

        $superadmin = $this->createUserWithRole('superadmin');
        $brand = Brand::create(['name' => 'Brand A', 'industry' => 'Tech']);
        $campaign = Campaign::create(['name' => 'Campaign A', 'brand_id' => $brand->id, 'budget' => 1000000, 'created_by' => $superadmin->id, 'status' => 'active']);

        $endorsement = Endorsement::create([
            'campaign_id' => $campaign->id,
            'kol_profile_id' => $profile->id,
            'content_type' => 'reels',
            'fee' => 1000000,
            'deadline' => now()->addDays(3)->toDateString(),
            'status' => 'selesai',
            'assigned_by' => $superadmin->id,
        ]);

        $commission = Commission::create([
            'endorsement_id' => $endorsement->id,
            'kol_profile_id' => $profile->id,
            'base_fee' => 1000000,
            'commission_pct' => 70,
            'commission_amount' => 700000,
            'status' => 'dicairkan',
        ]);

        $response = $this->actingAs($user)->post(route('kol.commissions.request-disbursement', $commission), []);

        $response->assertStatus(422);
    }

    public function test_kol_cannot_view_other_kol_commission(): void
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
            'status' => 'selesai',
            'assigned_by' => $superadmin->id,
        ]);

        $commission = Commission::create([
            'endorsement_id' => $endorsement->id,
            'kol_profile_id' => $profile1->id,
            'base_fee' => 1000000,
            'commission_pct' => 70,
            'commission_amount' => 700000,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user2)->get(route('kol.commissions.show', $commission));
        $response->assertForbidden();
    }

    private function createUserWithRole(string $role): User
    {
        $user = User::factory()->create();
        $user->assignRole(Role::firstOrCreate(['name' => $role], ['display_name' => ucfirst($role)]));

        return $user;
    }
}
