<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Campaign;
use App\Models\Commission;
use App\Models\ContentProof;
use App\Models\Endorsement;
use App\Models\KolProfile;
use App\Models\Role;
use App\Models\Tier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class Dev3EndorsementTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected User $kolUser;
    protected KolProfile $kolProfile;
    protected Tier $tier;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');

        // Setup Roles
        $adminRole = Role::create(['name' => 'admin', 'display_name' => 'Admin']);
        $kolRole = Role::create(['name' => 'kol', 'display_name' => 'KOL']);

        // Setup Tier
        $this->tier = Tier::create([
            'name' => 'Micro',
            'min_followers' => 10000,
            'max_followers' => 100000,
            'commission_pct' => 65.00,
            'agency_pct' => 35.00,
        ]);

        // Setup Admin User
        $this->adminUser = User::create([
            'name' => 'Admin Majapahit',
            'email' => 'admin@majapahit.com',
            'password' => bcrypt('password'),
        ]);
        $this->adminUser->roles()->attach($adminRole);

        // Setup Active KOL User
        $this->kolUser = User::create([
            'name' => 'Dimas Influencer',
            'email' => 'dimas@kol.com',
            'password' => bcrypt('password'),
        ]);
        $this->kolUser->roles()->attach($kolRole);

        $this->kolProfile = KolProfile::create([
            'user_id' => $this->kolUser->id,
            'nickname' => 'Dimas',
            'tier_id' => $this->tier->id,
            'status' => 'aktif',
            'bank_name' => 'BCA',
            'bank_account_number' => '1234567890',
            'bank_account_name' => 'Dimas Pratama',
        ]);
    }

    public function test_superadmin_can_create_and_list_brands(): void
    {
        $response = $this->actingAs($this->adminUser)->postJson(route('superadmin.brands.store'), [
            'name' => 'Erigo Apparel',
            'industry' => 'Fashion',
            'pic_name' => 'Budi Santoso',
            'pic_email' => 'budi@erigo.com',
            'pic_phone' => '08123456789',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('brands', ['name' => 'Erigo Apparel']);

        $listResponse = $this->actingAs($this->adminUser)->getJson(route('superadmin.brands.index'));
        $listResponse->assertStatus(200)
            ->assertJsonFragment(['name' => 'Erigo Apparel']);
    }

    public function test_superadmin_can_create_campaign_with_brief_files(): void
    {
        $brand = Brand::create([
            'name' => 'Uniqlo Indonesia',
            'industry' => 'Fashion',
        ]);

        $file = UploadedFile::fake()->create('brief_campaign.pdf', 500, 'application/pdf');

        $response = $this->actingAs($this->adminUser)->postJson(route('superadmin.campaigns.store'), [
            'brand_id' => $brand->id,
            'name' => 'Summer Collection 2026',
            'description' => 'Promosi koleksi musim panas',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
            'budget' => 50000000.00,
            'status' => 'aktif',
            'brief_files' => [$file],
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('campaigns', ['name' => 'Summer Collection 2026']);
        $this->assertDatabaseHas('campaign_files', ['file_name' => 'brief_campaign.pdf']);
    }

    public function test_superadmin_can_assign_active_kol_to_campaign(): void
    {
        $brand = Brand::create(['name' => 'Gojek']);
        $campaign = Campaign::create([
            'brand_id' => $brand->id,
            'name' => 'Gojek Hemat Campaign',
            'description' => 'Brief hemat',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($this->adminUser)
            ->postJson(route('superadmin.campaigns.assign', $campaign), [
                'kol_profile_id' => $this->kolProfile->id,
                'content_type' => 'reels',
                'fee' => 5000000.00,
                'deadline' => now()->addDays(7)->toDateString(),
                'notes' => 'Sertakan hashtag #GojekHemat',
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('endorsements', [
            'campaign_id' => $campaign->id,
            'kol_profile_id' => $this->kolProfile->id,
            'fee' => 5000000.00,
            'status' => 'assigned',
        ]);

        // Notification for KOL should be generated
        $this->assertDatabaseHas('notifications', [
            'user_id' => $this->kolUser->id,
            'type' => 'new_endorsement',
        ]);
    }

    public function test_cannot_assign_inactive_or_pending_kol_due_to_business_rules(): void
    {
        $brand = Brand::create(['name' => 'Tokopedia']);
        $campaign = Campaign::create([
            'brand_id' => $brand->id,
            'name' => 'WIB Campaign',
            'description' => 'Brief WIB',
            'status' => 'aktif',
        ]);

        // Inactive KOL with its own user
        $inactiveUser = User::create([
            'name' => 'Inactive KOL User',
            'email' => 'inactive@kol.com',
            'password' => bcrypt('password'),
        ]);

        $inactiveKol = KolProfile::create([
            'user_id' => $inactiveUser->id,
            'nickname' => 'Pending KOL',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->adminUser)
            ->postJson(route('superadmin.campaigns.assign', $campaign), [
                'kol_profile_id' => $inactiveKol->id,
                'content_type' => 'reels',
                'fee' => 3000000.00,
                'deadline' => now()->addDays(5)->toDateString(),
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['kol_profile_id']);
    }

    public function test_kol_can_view_endorsements_and_upload_content_proof(): void
    {
        $brand = Brand::create(['name' => 'Shopee']);
        $campaign = Campaign::create(['brand_id' => $brand->id, 'name' => 'Shopee 9.9', 'description' => 'Brief 9.9']);
        $endorsement = Endorsement::create([
            'campaign_id' => $campaign->id,
            'kol_profile_id' => $this->kolProfile->id,
            'content_type' => 'story',
            'fee' => 2000000.00,
            'deadline' => now()->addDays(3)->toDateString(),
            'status' => 'in_progress',
        ]);

        // View index
        $indexResponse = $this->actingAs($this->kolUser)
            ->getJson(route('kol.endorsements.index', ['tab' => 'active']));
        $indexResponse->assertStatus(200);

        // Upload Proof
        $screenshot = UploadedFile::fake()->image('proof_screenshot.png');

        $response = $this->actingAs($this->kolUser)
            ->postJson(route('kol.endorsements.upload', $endorsement), [
                'posted_at' => now()->toDateString(),
                'post_url' => 'https://instagram.com/stories/dimas/123456789',
                'notes' => 'Sudah diposting pukul 12 siang.',
                'proof_files' => [$screenshot],
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('content_proofs', [
            'endorsement_id' => $endorsement->id,
            'post_url' => 'https://instagram.com/stories/dimas/123456789',
            'review_status' => 'pending',
        ]);

        // Endorsement status should become content_submitted
        $this->assertEquals('content_submitted', $endorsement->fresh()->status);
    }

    public function test_superadmin_can_review_and_reject_proof_with_notes(): void
    {
        $brand = Brand::create(['name' => 'Traveloka']);
        $campaign = Campaign::create(['brand_id' => $brand->id, 'name' => 'Epic Sale', 'description' => 'Brief Epic']);
        $endorsement = Endorsement::create([
            'campaign_id' => $campaign->id,
            'kol_profile_id' => $this->kolProfile->id,
            'content_type' => 'reels',
            'fee' => 4000000.00,
            'deadline' => now()->addDays(4)->toDateString(),
            'status' => 'content_submitted',
        ]);

        $proof = ContentProof::create([
            'endorsement_id' => $endorsement->id,
            'posted_at' => now()->toDateString(),
            'post_url' => 'https://instagram.com/p/abcdef',
            'review_status' => 'pending',
        ]);

        $response = $this->actingAs($this->adminUser)
            ->postJson(route('superadmin.endorsements.review', $endorsement), [
                'status' => 'rejected',
                'notes' => 'Logo brand di video belum terlihat jelas, tolong re-upload potongan awal.',
            ]);

        $response->assertStatus(200);
        $this->assertEquals('rejected', $proof->fresh()->review_status);
        $this->assertEquals('content_rejected', $endorsement->fresh()->status);
    }

    public function test_superadmin_can_approve_proof_and_mark_completed_with_commission(): void
    {
        $brand = Brand::create(['name' => 'Blibli']);
        $campaign = Campaign::create(['brand_id' => $brand->id, 'name' => 'Histeria 10.10', 'description' => 'Brief 10.10']);
        $fee = 10000000.00; // 10 juta

        $endorsement = Endorsement::create([
            'campaign_id' => $campaign->id,
            'kol_profile_id' => $this->kolProfile->id,
            'content_type' => 'reels',
            'fee' => $fee,
            'deadline' => now()->addDays(2)->toDateString(),
            'status' => 'content_submitted',
        ]);

        $proof = ContentProof::create([
            'endorsement_id' => $endorsement->id,
            'posted_at' => now()->toDateString(),
            'post_url' => 'https://instagram.com/reel/12345678',
            'review_status' => 'pending',
        ]);

        // 1. Approve proof
        $response = $this->actingAs($this->adminUser)
            ->postJson(route('superadmin.endorsements.review', $endorsement), [
                'status' => 'approved',
                'notes' => 'Konten bagus sesuai brief!',
            ]);

        $response->assertStatus(200);
        $this->assertEquals('approved', $proof->fresh()->review_status);
        $this->assertEquals('content_approved', $endorsement->fresh()->status);

        // 2. Complete endorsement
        $completeResponse = $this->actingAs($this->adminUser)
            ->postJson(route('superadmin.endorsements.complete', $endorsement));

        $completeResponse->assertStatus(200);
        $this->assertEquals('selesai', $endorsement->fresh()->status);

        // Commission calculation BR1 check:
        // Tier Micro = 65% KOL, 35% Agency
        // 65% of 10.000.000 = 6.500.000
        // Agency = 3.500.000
        $this->assertDatabaseHas('commissions', [
            'endorsement_id' => $endorsement->id,
            'kol_profile_id' => $this->kolProfile->id,
            'endorsement_fee' => 10000000.00,
            'commission_pct' => 65.00,
            'commission_amount' => 6500000.00,
            'agency_amount' => 3500000.00,
            'status' => 'pending',
        ]);
    }
}
