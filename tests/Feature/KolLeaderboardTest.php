<?php

namespace Tests\Feature;

use App\Models\Tier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KolLeaderboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_leaderboard(): void
    {
        $response = $this->get(route('kol.leaderboard.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_kol_can_view_leaderboard(): void
    {
        $this->seed();

        $kolUser = User::whereHas('roles', fn ($q) => $q->where('name', 'kol'))->first();
        $this->assertNotNull($kolUser);

        $response = $this->actingAs($kolUser)->get(route('kol.leaderboard.index'));

        $response->assertStatus(200);
        $response->assertSee('Leaderboard Kreator');
        $response->assertSee('Bulan Ini');
        $response->assertSee('Sepanjang Waktu');
        $response->assertSee('Top 3 Kreator');
    }

    public function test_kol_leaderboard_supports_period_and_tier_filter(): void
    {
        $this->seed();

        $kolUser = User::whereHas('roles', fn ($q) => $q->where('name', 'kol'))->first();
        $tier = Tier::first();

        $response = $this->actingAs($kolUser)->get(route('kol.leaderboard.index', [
            'period' => 'all_time',
            'tier_id' => $tier?->id,
        ]));

        $response->assertStatus(200);
        $response->assertSee('Leaderboard Kreator');
    }

    public function test_kol_dashboard_displays_leaderboard_spotlight(): void
    {
        $this->seed();

        $kolUser = User::whereHas('roles', fn ($q) => $q->where('name', 'kol'))->first();

        $response = $this->actingAs($kolUser)->get(route('kol.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Top Kreator Bulan Ini');
        $response->assertSee('Buka Leaderboard');
    }
}
