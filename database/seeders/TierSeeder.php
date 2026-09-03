<?php

namespace Database\Seeders;

use App\Models\Tier;
use Illuminate\Database\Seeder;

class TierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tiers = [
            [
                'name' => 'Nano',
                'min_followers' => 0,
                'max_followers' => 9999,
                'commission_pct' => 60.00,
                'agency_pct' => 40.00,
            ],
            [
                'name' => 'Micro',
                'min_followers' => 10000,
                'max_followers' => 100000,
                'commission_pct' => 65.00,
                'agency_pct' => 35.00,
            ],
            [
                'name' => 'Macro',
                'min_followers' => 100001,
                'max_followers' => 1000000,
                'commission_pct' => 70.00,
                'agency_pct' => 30.00,
            ],
            [
                'name' => 'Mega',
                'min_followers' => 1000001,
                'max_followers' => null,
                'commission_pct' => 75.00,
                'agency_pct' => 25.00,
            ],
        ];

        foreach ($tiers as $tier) {
            Tier::firstOrCreate(['name' => $tier['name']], $tier);
        }
    }
}
