<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Campaign;
use App\Models\Commission;
use App\Models\Endorsement;
use App\Models\KolProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuperadminDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected User $superadmin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->superadmin = User::whereHas('roles', fn ($q) => $q->where('name', 'superadmin'))->first();
    }

    public function test_superadmin_can_view_dashboard_with_dual_metric_trend(): void
    {
        $response = $this->actingAs($this->superadmin)->get(route('superadmin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Tren Kinerja');
        $response->assertSee('Nilai Komisi');
        $response->assertSee('Volume Endorsement');
        $response->assertSee('Total Komisi');
        $response->assertSee('Mingguan');
        $response->assertSee('1 Bulan');
        $response->assertSee('6 Bulan');
        $response->assertSee('1 Tahun');
        $response->assertSee('dualMetricTrendChart');
    }

    public function test_dashboard_computes_trend_metrics_and_multi_periods_correctly(): void
    {
        $brand = Brand::first();
        $kolUser = User::factory()->create();
        $kolUser->assignRole('kol');
        $kolProfile = KolProfile::create([
            'user_id' => $kolUser->id,
            'nickname' => 'TesterKOL',
            'status' => 'aktif',
        ]);

        $campaign = Campaign::create([
            'brand_id' => $brand->id,
            'name' => 'Q3 Campaign',
            'status' => 'aktif',
            'start_date' => now()->subMonth(),
            'end_date' => now()->addMonth(),
        ]);

        $endorsement = Endorsement::create([
            'campaign_id' => $campaign->id,
            'kol_profile_id' => $kolProfile->id,
            'content_type' => 'video_reels',
            'fee' => 500000,
            'deadline' => now()->addDays(5),
            'status' => 'in_progress',
            'created_at' => now(),
        ]);

        Commission::create([
            'endorsement_id' => $endorsement->id,
            'kol_profile_id' => $kolProfile->id,
            'endorsement_fee' => 500000,
            'commission_pct' => 20,
            'commission_amount' => 100000,
            'agency_amount' => 400000,
            'status' => 'approved',
            'created_at' => now(),
        ]);

        $response = $this->actingAs($this->superadmin)->get(route('superadmin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('trendSummary');
        $response->assertViewHas('endorsementTrend');
        $response->assertViewHas('trendDatasets');

        $trendSummary = $response->viewData('trendSummary');
        $this->assertGreaterThanOrEqual(1, $trendSummary['totalEndorsements']);
        $this->assertGreaterThanOrEqual(100000, $trendSummary['totalCommission']);

        $trendDatasets = $response->viewData('trendDatasets');
        $this->assertArrayHasKey('weekly', $trendDatasets);
        $this->assertArrayHasKey('1m', $trendDatasets);
        $this->assertArrayHasKey('6m', $trendDatasets);
        $this->assertArrayHasKey('1y', $trendDatasets);

        $this->assertCount(8, $trendDatasets['weekly']['labels']);
        $this->assertStringContainsString('-', $trendDatasets['weekly']['labels'][0]);
        $this->assertCount(30, $trendDatasets['1m']['labels']);
        $this->assertCount(6, $trendDatasets['6m']['labels']);
        $this->assertCount(12, $trendDatasets['1y']['labels']);
    }
}
