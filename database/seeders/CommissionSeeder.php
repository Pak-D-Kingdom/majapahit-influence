<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Campaign;
use App\Models\Commission;
use App\Models\Endorsement;
use App\Models\KolProfile;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommissionSeeder extends Seeder
{
    /**
     * Run the database seeds for testing commissions.
     */
    public function run(): void
    {
        $admin = User::first();
        $kolProfile = KolProfile::first();

        if (!$kolProfile) {
            $user = User::factory()->create(['name' => 'Dimas Influencer', 'email' => 'dimas@example.com']);
            $kolProfile = KolProfile::create([
                'user_id' => $user->id,
                'nickname' => 'Dimas',
                'bio' => 'Lifestyle & Travel Content Creator',
                'city' => 'Jakarta',
                'province' => 'DKI Jakarta',
                'tier_id' => 1,
                'bank_name' => 'BCA',
                'bank_account_number' => '1234567890',
                'bank_account_name' => 'Dimas Saputra',
                'status' => 'aktif',
                'joined_at' => now(),
            ]);
        }

        $brand = Brand::firstOrCreate(
            ['name' => 'Tokopedia'],
            ['industry' => 'E-Commerce', 'pic_name' => 'Budi PIC', 'pic_phone' => '08123456789', 'is_active' => true]
        );

        $campaign = Campaign::firstOrCreate(
            ['name' => 'Waktu Indonesia Belanja (WIB) Promo'],
            [
                'brand_id' => $brand->id,
                'description' => 'Promosikan promo tanggal kembar di Instagram Reels dan TikTok.',
                'start_date' => now()->subDays(10),
                'end_date' => now()->addDays(5),
                'budget' => 50000000,
                'status' => 'aktif',
            ]
        );

        $endorsement = Endorsement::firstOrCreate(
            ['campaign_id' => $campaign->id, 'kol_profile_id' => $kolProfile->id],
            [
                'content_type' => 'Instagram Reels & Story',
                'fee' => 10000000, // Rp 10.000.000
                'deadline' => now()->subDays(2),
                'start_date' => now()->subDays(8),
                'status' => 'selesai',
                'completed_at' => now()->subDay(),
            ]
        );

        // Commission 1 (Status: pending)
        Commission::firstOrCreate(
            ['id' => 1],
            [
                'endorsement_id' => $endorsement->id,
                'kol_profile_id' => $kolProfile->id,
                'endorsement_fee' => 10000000,
                'commission_pct' => 60.00,
                'commission_amount' => 6000000, // 60%
                'agency_amount' => 4000000, // 40%
                'status' => 'pending',
            ]
        );
    }
}
