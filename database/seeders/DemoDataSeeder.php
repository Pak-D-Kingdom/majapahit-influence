<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\Brand;
use App\Models\Campaign;
use App\Models\Commission;
use App\Models\CommissionApproval;
use App\Models\ContentProof;
use App\Models\Endorsement;
use App\Models\KolProfile;
use App\Models\KolRateCard;
use App\Models\KolSocialMedia;
use App\Models\Niche;
use App\Models\Notification;
use App\Models\Role;
use App\Models\Tier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $superadmin = User::where('email', 'admin@majapahit.com')->firstOrFail();
            $kolRole = Role::where('name', 'kol')->firstOrFail();
            $tiers = Tier::all()->keyBy('name');
            $niches = Niche::all()->keyBy('name');

            $profiles = $this->seedKols($kolRole, $tiers, $niches);
            $brands = $this->seedBrands();
            $campaigns = $this->seedCampaigns($brands, $superadmin);
            $endorsements = $this->seedEndorsements($campaigns, $profiles, $superadmin);
            $this->seedProofsAndCommissions($endorsements, $superadmin);
            $this->seedRegistrations($niches);
            $this->seedNotifications($superadmin, $profiles->first()['user']);
            $this->seedAuditLogs($superadmin, $campaigns->first());
        });
    }

    private function seedKols(Role $kolRole, $tiers, $niches): \Illuminate\Support\Collection
    {
        $data = [
            ['name' => 'Dimas Lifestyle', 'email' => 'kol@majapahit.com', 'nickname' => 'Dimas', 'city' => 'Jakarta Selatan', 'tier' => 'Micro', 'followers' => 75000, 'platform' => 'instagram', 'username' => '@dimas_lifestyle', 'niches' => ['Lifestyle', 'Fashion & Style'], 'status' => 'aktif'],
            ['name' => 'Alya Beauty', 'email' => 'alya@majapahit.com', 'nickname' => 'Alya', 'city' => 'Bandung', 'tier' => 'Macro', 'followers' => 245000, 'platform' => 'instagram', 'username' => '@alyabeauty.id', 'niches' => ['Beauty & Skincare'], 'status' => 'aktif'],
            ['name' => 'Raka Gaming', 'email' => 'raka@majapahit.com', 'nickname' => 'Raka', 'city' => 'Surabaya', 'tier' => 'Micro', 'followers' => 68000, 'platform' => 'tiktok', 'username' => '@raka.play', 'niches' => ['Gaming & Esports'], 'status' => 'aktif'],
            ['name' => 'Naya Kuliner', 'email' => 'naya@majapahit.com', 'nickname' => 'Naya', 'city' => 'Jakarta Barat', 'tier' => 'Micro', 'followers' => 42000, 'platform' => 'tiktok', 'username' => '@nayakuliner', 'niches' => ['Food & Beverage'], 'status' => 'aktif'],
            ['name' => 'Bimo Tech', 'email' => 'bimo@majapahit.com', 'nickname' => 'Bimo', 'city' => 'Tangerang', 'tier' => 'Macro', 'followers' => 180000, 'platform' => 'youtube', 'username' => '@bimotech', 'niches' => ['Tech & Gadgets'], 'status' => 'aktif'],
            ['name' => 'Salsa Travel', 'email' => 'salsa@majapahit.com', 'nickname' => 'Salsa', 'city' => 'Yogyakarta', 'tier' => 'Nano', 'followers' => 8500, 'platform' => 'instagram', 'username' => '@salsajalan', 'niches' => ['Travel & Tourism'], 'status' => 'aktif'],
            ['name' => 'Fajar Fitness', 'email' => 'fajar@majapahit.com', 'nickname' => 'Fajar', 'city' => 'Depok', 'tier' => 'Micro', 'followers' => 91000, 'platform' => 'instagram', 'username' => '@fajar.fit', 'niches' => ['Health & Fitness'], 'status' => 'nonaktif'],
            ['name' => 'Mira Fashion', 'email' => 'mira@majapahit.com', 'nickname' => 'Mira', 'city' => 'Semarang', 'tier' => 'Mega', 'followers' => 1200000, 'platform' => 'tiktok', 'username' => '@miramode', 'niches' => ['Fashion & Style', 'Lifestyle'], 'status' => 'pending'],
        ];

        return collect($data)->mapWithKeys(function (array $item, int $index) use ($kolRole, $tiers, $niches): array {
            $user = User::updateOrCreate(['email' => $item['email']], ['name' => $item['name'], 'password' => Hash::make('password'), 'is_active' => true]);
            $user->roles()->syncWithoutDetaching([$kolRole->id]);
            $profile = KolProfile::updateOrCreate(['user_id' => $user->id], ['nickname' => $item['nickname'], 'bio' => $item['name'].' — content creator Majapahit Influence.', 'city' => $item['city'], 'province' => 'Jawa Barat', 'tier_id' => $tiers[$item['tier']]->id, 'status' => $item['status'], 'joined_at' => now()->subMonths(10 - $index)]);
            $profile->niches()->sync($this->idsFor($item['niches'], $niches));
            KolSocialMedia::updateOrCreate(['kol_profile_id' => $profile->id, 'platform' => $item['platform']], ['username' => $item['username'], 'profile_url' => 'https://'.$item['platform'].'.com/'.$item['username'], 'followers_count' => $item['followers'], 'engagement_rate' => 3.5 + ($index * 0.4)]);
            KolRateCard::updateOrCreate(['kol_profile_id' => $profile->id, 'platform' => $item['platform'], 'content_type' => 'video'], ['rate' => 1500000 + ($index * 500000)]);
            return [$profile->id => ['profile' => $profile, 'user' => $user]];
        });
    }

    private function seedBrands(): \Illuminate\Support\Collection
    {
        return collect([
            ['name' => 'Kopi Nusantara', 'industry' => 'Food & Beverage', 'pic_name' => 'Arif Pranoto'],
            ['name' => 'Glow & Co.', 'industry' => 'Beauty', 'pic_name' => 'Maya Putri'],
            ['name' => 'NusaFit', 'industry' => 'Health & Fitness', 'pic_name' => 'Rizky Adi'],
            ['name' => 'Loka Living', 'industry' => 'Lifestyle', 'pic_name' => 'Sinta Dewi'],
            ['name' => 'TeknoKita', 'industry' => 'Technology', 'pic_name' => 'Andi Wijaya'],
        ])->mapWithKeys(fn (array $item): array => [$item['name'] => Brand::updateOrCreate(['name' => $item['name']], $item + ['is_active' => true])]);
    }

    private function seedCampaigns($brands, User $superadmin): \Illuminate\Support\Collection
    {
        $names = ['Ramadan Ceria', 'Glow Up Everyday', 'Move Better', 'Rumah Nyaman', 'Tech for Everyone', 'Merdeka Berkreasi'];
        return collect($names)->mapWithKeys(function (string $name, int $index) use ($brands, $superadmin): array {
            $brand = $brands->values()->get($index % $brands->count());
            $campaign = Campaign::updateOrCreate(['name' => $name], ['brand_id' => $brand->id, 'description' => 'Campaign demo untuk kebutuhan validasi dashboard.', 'start_date' => now()->subMonths(2)->startOfMonth()->toDateString(), 'end_date' => now()->addMonths(1)->endOfMonth()->toDateString(), 'budget' => 25000000 + ($index * 5000000), 'content_requirements' => 'Buat konten sesuai brief brand dan cantumkan CTA.', 'status' => $index === 5 ? 'draft' : ($index === 4 ? 'selesai' : 'aktif'), 'created_by' => $superadmin->id]);
            return [$campaign->id => $campaign];
        });
    }

    private function seedEndorsements($campaigns, $profiles, User $superadmin): \Illuminate\Support\Collection
    {
        return collect(range(0, 11))->map(function (int $index) use ($campaigns, $profiles, $superadmin): Endorsement {
            $profile = $profiles->values()->get($index % $profiles->count())['profile'];
            $campaign = $campaigns->values()->get($index % $campaigns->count());
            $statuses = ['assigned', 'in_progress', 'content_submitted', 'content_approved', 'selesai'];
            return Endorsement::updateOrCreate(['campaign_id' => $campaign->id, 'kol_profile_id' => $profile->id, 'content_type' => $index % 2 ? 'video' : 'reels'], ['fee' => 3500000 + ($index * 350000), 'deadline' => now()->addDays($index < 4 ? $index + 2 : $index + 14)->toDateString(), 'start_date' => now()->subDays($index + 3)->toDateString(), 'status' => $statuses[$index % count($statuses)], 'assigned_by' => $superadmin->id]);
        });
    }

    private function seedProofsAndCommissions($endorsements, User $superadmin): void
    {
        $endorsements->each(function (Endorsement $endorsement, int $index) use ($superadmin): void {
            if ($index % 3 !== 0) {
                ContentProof::updateOrCreate(['endorsement_id' => $endorsement->id], ['posted_at' => now()->subDays($index + 1)->toDateString(), 'post_url' => 'https://instagram.com/p/demo-'.$endorsement->id, 'review_status' => $index % 3 === 1 ? 'pending' : 'approved', 'reviewed_by' => $index % 3 === 1 ? null : $superadmin->id, 'reviewed_at' => $index % 3 === 1 ? null : now()->subDays($index)]);
            }
            $commission = Commission::updateOrCreate(['endorsement_id' => $endorsement->id], ['kol_profile_id' => $endorsement->kol_profile_id, 'endorsement_fee' => $endorsement->fee, 'commission_pct' => $endorsement->kolProfile->effective_commission_pct, 'commission_amount' => $endorsement->fee * ($endorsement->kolProfile->effective_commission_pct / 100), 'agency_amount' => $endorsement->fee * (1 - ($endorsement->kolProfile->effective_commission_pct / 100)), 'status' => ['pending', 'approved', 'dicairkan'][$index % 3], 'disbursed_at' => $index % 3 === 2 ? now()->subDays($index)->toDateString() : null]);
            CommissionApproval::updateOrCreate(['commission_id' => $commission->id, 'action' => $commission->status === 'dicairkan' ? 'disburse' : 'request'], ['performed_by' => $superadmin->id, 'notes' => 'Data demo untuk alur approval komisi.']);
        });
    }

    private function seedRegistrations($niches): void
    {
        foreach ([['REG-DEMO-0001', 'Gita Prameswari', 'gita@example.com', 'Lifestyle'], ['REG-DEMO-0002', 'Rio Creative', 'rio@example.com', 'Entertainment & Comedy'], ['REG-DEMO-0003', 'Tari Beauty', 'tari@example.com', 'Beauty & Skincare']] as [$number, $name, $email, $niche]) {
            \App\Models\KolRegistration::updateOrCreate(['registration_number' => $number], ['full_name' => $name, 'email' => $email, 'phone' => '081234567890', 'city' => 'Jakarta', 'niches' => [$niche], 'social_media' => ['platform' => 'instagram', 'username' => strtolower(str_replace(' ', '', $name))], 'join_reason' => 'Ingin berkembang bersama Majapahit Influence.', 'status' => 'pending_review']);
        }
    }

    private function seedNotifications(User $superadmin, User $kol): void
    {
        foreach ([[$superadmin, 'Pendaftaran KOL baru', 'Ada 3 pendaftaran KOL yang menunggu review.'], [$superadmin, 'Deadline mendekat', 'Beberapa endorsement memiliki deadline dalam 7 hari.'], [$kol, 'Endorsement baru', 'Kamu mendapatkan assignment endorsement baru.']] as [$user, $title, $body]) {
            Notification::updateOrCreate(['user_id' => $user->id, 'title' => $title], ['id' => (string) Str::uuid(), 'type' => 'system', 'body' => $body, 'is_read' => false]);
        }
    }

    private function seedAuditLogs(User $superadmin, Campaign $campaign): void
    {
        AuditLog::firstOrCreate(['user_id' => $superadmin->id, 'action' => 'demo_seeded', 'entity_type' => 'campaigns', 'entity_id' => $campaign->id], ['new_values' => ['source' => 'DemoDataSeeder']]);
    }

    private function idsFor(array $names, $lookup): array
    {
        return collect($names)->map(fn (string $name) => $lookup[$name]->id)->all();
    }
}
