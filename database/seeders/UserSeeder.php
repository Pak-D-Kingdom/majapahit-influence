<?php

namespace Database\Seeders;

use App\Models\KolProfile;
use App\Models\KolRateCard;
use App\Models\KolSocialMedia;
use App\Models\Niche;
use App\Models\Role;
use App\Models\Tier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superadminRole = Role::where('name', 'superadmin')->first();
        $kolRole = Role::where('name', 'kol')->first();
        $microTier = Tier::where('name', 'Micro')->first();

        // 1. Superadmin User
        $superadmin = User::firstOrCreate(
            ['email' => 'admin@majapahit.com'],
            [
                'name' => 'Superadmin Majapahit',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );
        if ($superadminRole) {
            $superadmin->roles()->syncWithoutDetaching([$superadminRole->id]);
        }

        // 2. Sample Active KOL User
        $kolUser = User::firstOrCreate(
            ['email' => 'kol@majapahit.com'],
            [
                'name' => 'Dimas Lifestyle',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );
        if ($kolRole) {
            $kolUser->roles()->syncWithoutDetaching([$kolRole->id]);
        }

        // Create Profile for KOL
        $kolProfile = KolProfile::firstOrCreate(
            ['user_id' => $kolUser->id],
            [
                'nickname' => 'Dimas',
                'bio' => 'Lifestyle & Fashion content creator based in Jakarta.',
                'date_of_birth' => '2000-05-15',
                'gender' => 'Laki-laki',
                'city' => 'Jakarta Selatan',
                'province' => 'DKI Jakarta',
                'tier_id' => $microTier?->id,
                'bank_name' => 'BCA',
                'bank_account_number' => '1234567890',
                'bank_account_name' => 'Dimas Pratama',
                'npwp' => '12.345.678.9-012.000',
                'status' => 'aktif',
                'joined_at' => now(),
            ]
        );

        // Attach Niches
        $lifestyleNiche = Niche::where('name', 'Lifestyle')->first();
        $fashionNiche = Niche::where('name', 'Fashion & Style')->first();
        if ($lifestyleNiche && $kolProfile) {
            $kolProfile->niches()->syncWithoutDetaching([$lifestyleNiche->id, $fashionNiche?->id]);
        }

        // Social Media
        KolSocialMedia::firstOrCreate(
            [
                'kol_profile_id' => $kolProfile->id,
                'platform' => 'instagram',
            ],
            [
                'username' => '@dimas_lifestyle',
                'profile_url' => 'https://instagram.com/dimas_lifestyle',
                'followers_count' => 75000,
                'engagement_rate' => 4.25,
            ]
        );

        KolSocialMedia::firstOrCreate(
            [
                'kol_profile_id' => $kolProfile->id,
                'platform' => 'tiktok',
            ],
            [
                'username' => '@dimas_tiktok',
                'profile_url' => 'https://tiktok.com/@dimas_tiktok',
                'followers_count' => 120000,
                'engagement_rate' => 6.10,
            ]
        );

        // Rate Cards
        KolRateCard::firstOrCreate(
            [
                'kol_profile_id' => $kolProfile->id,
                'platform' => 'instagram',
                'content_type' => 'reels',
            ],
            [
                'rate' => 3500000.00,
            ]
        );

        KolRateCard::firstOrCreate(
            [
                'kol_profile_id' => $kolProfile->id,
                'platform' => 'instagram',
                'content_type' => 'story',
            ],
            [
                'rate' => 1500000.00,
            ]
        );

        KolRateCard::firstOrCreate(
            [
                'kol_profile_id' => $kolProfile->id,
                'platform' => 'tiktok',
                'content_type' => 'video',
            ],
            [
                'rate' => 4000000.00,
            ]
        );
    }
}
