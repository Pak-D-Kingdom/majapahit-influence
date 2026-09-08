<?php

namespace Tests\Unit;

use App\Enums\CommissionStatus;
use App\Enums\EndorsementStatus;
use App\Enums\KolStatus;
use App\Enums\RegistrationStatus;
use App\Models\Brand;
use App\Models\Campaign;
use App\Models\Endorsement;
use App\Models\KolProfile;
use App\Models\Tier;
use App\Models\User;
use App\Services\CommissionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommissionCalculationTest extends TestCase
{
    use RefreshDatabase;

    public function test_commission_calculation_uses_kol_tier_percentage(): void
    {
        $user = User::factory()->create();
        $brand = Brand::create(['name' => 'Brand Test', 'industry' => 'Fashion', 'is_active' => true]);
        $tier = Tier::create([
            'name' => 'Gold',
            'min_followers' => 10000,
            'max_followers' => 50000,
            'commission_pct' => 70.00,
        ]);

        $kol = KolProfile::create([
            'user_id' => $user->id,
            'nickname' => 'Gold KOL',
            'tier_id' => $tier->id,
            'status' => 'aktif',
        ]);

        $campaign = Campaign::create([
            'brand_id' => $brand->id,
            'name' => 'Promo Merdeka',
            'status' => 'aktif',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(14)->toDateString(),
        ]);

        $endorsement = Endorsement::create([
            'campaign_id' => $campaign->id,
            'kol_profile_id' => $kol->id,
            'content_type' => 'reels',
            'fee' => 1000000,
            'deadline' => now()->addDays(7)->toDateString(),
            'status' => 'selesai',
        ]);

        $service = app(CommissionService::class);
        $commission = $service->calculateAndCreate($endorsement);

        $this->assertEquals(70.00, $commission->commission_pct);
        $this->assertEquals(700000, $commission->commission_amount);
        $this->assertEquals(300000, $commission->agency_amount);
        $this->assertFalse($commission->is_override);
        $this->assertEquals(1000000, $commission->commission_amount + $commission->agency_amount);
    }

    public function test_commission_calculation_respects_override_percentage(): void
    {
        $user = User::factory()->create();
        $brand = Brand::create(['name' => 'Brand Test 2', 'industry' => 'Beauty', 'is_active' => true]);
        $tier = Tier::create([
            'name' => 'Silver',
            'min_followers' => 1000,
            'max_followers' => 9999,
            'commission_pct' => 50.00,
        ]);

        $kol = KolProfile::create([
            'user_id' => $user->id,
            'nickname' => 'Special KOL',
            'tier_id' => $tier->id,
            'commission_override_pct' => 85.00,
            'status' => 'aktif',
        ]);

        $campaign = Campaign::create([
            'brand_id' => $brand->id,
            'name' => 'Mega Campaign',
            'status' => 'aktif',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(14)->toDateString(),
        ]);

        $endorsement = Endorsement::create([
            'campaign_id' => $campaign->id,
            'kol_profile_id' => $kol->id,
            'content_type' => 'feed_post',
            'fee' => 2000000,
            'deadline' => now()->addDays(5)->toDateString(),
            'status' => 'selesai',
        ]);

        $service = app(CommissionService::class);
        $commission = $service->calculateAndCreate($endorsement);

        $this->assertEquals(85.00, $commission->commission_pct);
        $this->assertEquals(1700000, $commission->commission_amount);
        $this->assertEquals(300000, $commission->agency_amount);
        $this->assertTrue($commission->is_override);
    }

    public function test_status_enums_return_expected_values_and_labels(): void
    {
        $this->assertEquals('pending', CommissionStatus::Pending->value);
        $this->assertEquals('dicairkan', CommissionStatus::Disbursed->value);
        $this->assertEquals('Dicairkan', CommissionStatus::Disbursed->label());

        $this->assertEquals('selesai', EndorsementStatus::Completed->value);
        $this->assertEquals('Selesai', EndorsementStatus::Completed->label());

        $this->assertEquals('aktif', KolStatus::Active->value);
        $this->assertEquals('Aktif', KolStatus::Active->label());

        $this->assertEquals('pending_review', RegistrationStatus::PendingReview->value);
        $this->assertEquals('Dalam Peninjauan', RegistrationStatus::PendingReview->label());
    }
}
