<?php

namespace Tests\Feature;

use App\Models\KolProfile;
use App\Models\KolRateCard;
use App\Models\KolSocialMedia;
use App\Models\Role;
use App\Models\Tier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class KolProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('kol.profile.show'));

        $response->assertRedirect(route('login'));
    }

    public function test_non_kol_user_cannot_access_profile(): void
    {
        $user = $this->createUserWithRole('superadmin');

        $response = $this->actingAs($user)->get(route('kol.profile.show'));

        $response->assertForbidden();
    }

    public function test_kol_without_profile_gets_404(): void
    {
        $user = $this->createUserWithRole('kol');

        $response = $this->actingAs($user)->get(route('kol.profile.show'));

        $response->assertNotFound();
    }

    public function test_kol_with_profile_can_view_profile_page(): void
    {
        $user = $this->createUserWithRole('kol');
        $tier = Tier::create([
            'name' => 'Micro',
            'min_followers' => 10000,
            'max_followers' => 100000,
            'commission_pct' => 65.00,
            'agency_pct' => 35.00,
        ]);

        $profile = KolProfile::create([
            'user_id' => $user->id,
            'nickname' => 'Budi Gamer',
            'bio' => 'Konten kreator gaming & tech',
            'city' => 'Surabaya',
            'province' => 'Jawa Timur',
            'tier_id' => $tier->id,
            'bank_name' => 'BCA',
            'bank_account_number' => '1234567890',
            'bank_account_name' => 'Budi Santoso',
            'status' => 'aktif',
        ]);

        KolSocialMedia::create([
            'kol_profile_id' => $profile->id,
            'platform' => 'instagram',
            'username' => 'budigamer',
            'profile_url' => 'https://instagram.com/budigamer',
            'followers_count' => 25000,
            'engagement_rate' => 4.5,
        ]);

        KolRateCard::create([
            'kol_profile_id' => $profile->id,
            'platform' => 'instagram',
            'content_type' => 'reels',
            'rate' => 1500000,
        ]);

        $response = $this->actingAs($user)->get(route('kol.profile.show'));

        $response->assertOk();
        $response->assertSee('Budi Gamer');
        $response->assertSee('Konten kreator gaming & tech');
        $response->assertSee('Surabaya');
        $response->assertSee('Tier Micro');
        $response->assertSee('budigamer');
        $response->assertSee('Reels');
        $response->assertSee('1.500.000');
        $response->assertSee('BCA');
    }

    public function test_guest_cannot_access_edit_profile(): void
    {
        $this->get(route('kol.profile.edit'))->assertRedirect(route('login'));
        $this->put(route('kol.profile.update'))->assertRedirect(route('login'));
    }

    public function test_non_kol_cannot_access_edit_profile(): void
    {
        $user = $this->createUserWithRole('superadmin');

        $this->actingAs($user)->get(route('kol.profile.edit'))->assertForbidden();
        $this->actingAs($user)->put(route('kol.profile.update'))->assertForbidden();
    }

    public function test_kol_without_profile_gets_404_on_edit(): void
    {
        $user = $this->createUserWithRole('kol');

        $this->actingAs($user)->get(route('kol.profile.edit'))->assertNotFound();
    }

    public function test_kol_can_view_edit_profile_form(): void
    {
        $user = $this->createUserWithRole('kol');
        $profile = KolProfile::create([
            'user_id' => $user->id,
            'nickname' => 'Sarah Vlogger',
            'bio' => 'Food and Travel Creator',
            'city' => 'Bandung',
            'province' => 'Jawa Barat',
            'bank_name' => 'BCA',
            'bank_account_number' => '9876543210',
            'bank_account_name' => 'Sarah Vlogger',
            'status' => 'aktif',
        ]);

        KolSocialMedia::create([
            'kol_profile_id' => $profile->id,
            'platform' => 'tiktok',
            'username' => 'sarahvlog',
            'followers_count' => 50000,
            'engagement_rate' => 5.2,
        ]);

        KolRateCard::create([
            'kol_profile_id' => $profile->id,
            'platform' => 'tiktok',
            'content_type' => 'video',
            'rate' => 2000000,
        ]);

        $response = $this->actingAs($user)->get(route('kol.profile.edit'));

        $response->assertOk();
        $response->assertSee('Sarah Vlogger');
        $response->assertSee('Food and Travel Creator');
        $response->assertSee('Bandung');
        $response->assertSee('sarahvlog');
        $response->assertSee('2000000');
    }

    public function test_kol_can_update_profile_and_bank_info(): void
    {
        $user = $this->createUserWithRole('kol');
        $profile = KolProfile::create([
            'user_id' => $user->id,
            'nickname' => 'Old Nickname',
            'bio' => 'Old bio',
            'city' => 'Jakarta',
            'province' => 'DKI Jakarta',
            'bank_name' => 'Mandiri',
            'bank_account_number' => '111222333',
            'bank_account_name' => 'Old Name',
            'status' => 'aktif',
        ]);

        $social = KolSocialMedia::create([
            'kol_profile_id' => $profile->id,
            'platform' => 'instagram',
            'username' => 'old_handle',
            'followers_count' => 1000,
            'engagement_rate' => 2.0,
        ]);

        $response = $this->actingAs($user)->put(route('kol.profile.update'), [
            'nickname' => 'New Nickname',
            'bio' => 'Updated bio description',
            'city' => 'Surabaya',
            'province' => 'Jawa Timur',
            'bank_name' => 'BCA',
            'bank_account_number' => '999888777',
            'bank_account_name' => 'New Account Name',
            'npwp' => '12.345.678.9-012.000',
            'social_media' => [
                [
                    'id' => $social->id,
                    'platform' => 'instagram',
                    'username' => 'new_handle',
                    'profile_url' => 'https://instagram.com/new_handle',
                    'followers_count' => 15000,
                    'engagement_rate' => 4.5,
                ],
            ],
        ]);

        $response->assertRedirect(route('kol.profile.show'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('kol_profiles', [
            'id' => $profile->id,
            'nickname' => 'New Nickname',
            'bio' => 'Updated bio description',
            'city' => 'Surabaya',
            'province' => 'Jawa Timur',
            'bank_name' => 'BCA',
            'bank_account_number' => '999888777',
            'bank_account_name' => 'New Account Name',
            'npwp' => '12.345.678.9-012.000',
        ]);
    }

    public function test_kol_can_sync_social_media_accounts(): void
    {
        $user = $this->createUserWithRole('kol');
        $profile = KolProfile::create([
            'user_id' => $user->id,
            'nickname' => 'KOL Multi',
            'status' => 'aktif',
        ]);

        $existingSocial1 = KolSocialMedia::create([
            'kol_profile_id' => $profile->id,
            'platform' => 'instagram',
            'username' => 'keep_me',
            'followers_count' => 10000,
            'engagement_rate' => 3.0,
        ]);

        $existingSocial2 = KolSocialMedia::create([
            'kol_profile_id' => $profile->id,
            'platform' => 'twitter',
            'username' => 'delete_me',
            'followers_count' => 5000,
            'engagement_rate' => 1.5,
        ]);

        $response = $this->actingAs($user)->put(route('kol.profile.update'), [
            'nickname' => 'KOL Multi',
            'social_media' => [
                [
                    'id' => $existingSocial1->id,
                    'platform' => 'instagram',
                    'username' => 'keep_me_updated',
                    'profile_url' => 'https://instagram.com/keep_me_updated',
                    'followers_count' => 12000,
                    'engagement_rate' => 3.5,
                ],
                [
                    'id' => null,
                    'platform' => 'tiktok',
                    'username' => 'new_tiktok',
                    'profile_url' => 'https://tiktok.com/@new_tiktok',
                    'followers_count' => 50000,
                    'engagement_rate' => 6.0,
                ],
            ],
        ]);

        $response->assertRedirect(route('kol.profile.show'));

        $this->assertDatabaseHas('kol_social_media', [
            'id' => $existingSocial1->id,
            'username' => 'keep_me_updated',
            'followers_count' => 12000,
        ]);

        $this->assertDatabaseHas('kol_social_media', [
            'kol_profile_id' => $profile->id,
            'platform' => 'tiktok',
            'username' => 'new_tiktok',
        ]);

        $this->assertDatabaseMissing('kol_social_media', [
            'id' => $existingSocial2->id,
        ]);
    }

    public function test_kol_can_sync_rate_cards_and_audit_trail_is_recorded(): void
    {
        $user = $this->createUserWithRole('kol');
        $profile = KolProfile::create([
            'user_id' => $user->id,
            'nickname' => 'Rate Card KOL',
            'status' => 'aktif',
        ]);

        $existingRate = KolRateCard::create([
            'kol_profile_id' => $profile->id,
            'platform' => 'instagram',
            'content_type' => 'reels',
            'rate' => 1000000,
        ]);

        $response = $this->actingAs($user)->put(route('kol.profile.update'), [
            'nickname' => 'Rate Card KOL',
            'social_media' => [
                [
                    'platform' => 'instagram',
                    'username' => 'testuser',
                    'followers_count' => 10000,
                    'engagement_rate' => 2.5,
                ],
            ],
            'rate_cards' => [
                [
                    'id' => $existingRate->id,
                    'platform' => 'instagram',
                    'content_type' => 'reels',
                    'rate' => 1800000,
                ],
                [
                    'id' => null,
                    'platform' => 'tiktok',
                    'content_type' => 'video',
                    'rate' => 2500000,
                ],
            ],
        ]);

        $response->assertRedirect(route('kol.profile.show'));

        $this->assertDatabaseHas('kol_rate_cards', [
            'kol_profile_id' => $profile->id,
            'platform' => 'instagram',
            'content_type' => 'reels',
            'rate' => 1800000,
        ]);

        $this->assertDatabaseHas('kol_rate_cards', [
            'kol_profile_id' => $profile->id,
            'platform' => 'tiktok',
            'content_type' => 'video',
            'rate' => 2500000,
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'rate_card_updated',
            'entity_type' => 'kol_profiles',
            'entity_id' => $profile->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_kol_can_upload_new_photo(): void
    {
        Storage::fake('public');

        $user = $this->createUserWithRole('kol');
        $profile = KolProfile::create([
            'user_id' => $user->id,
            'nickname' => 'Photo KOL',
            'status' => 'aktif',
        ]);

        $file = UploadedFile::fake()->image('avatar.jpg', 400, 400);

        $response = $this->actingAs($user)->put(route('kol.profile.update'), [
            'nickname' => 'Photo KOL',
            'photo' => $file,
            'social_media' => [
                [
                    'platform' => 'instagram',
                    'username' => 'photokol',
                    'followers_count' => 5000,
                    'engagement_rate' => 3.0,
                ],
            ],
        ]);

        $response->assertRedirect(route('kol.profile.show'));

        $profile->refresh();
        $this->assertNotNull($profile->photo_path);
        Storage::disk('public')->assertExists($profile->photo_path);
    }

    public function test_validation_requires_at_least_one_social_media(): void
    {
        $user = $this->createUserWithRole('kol');
        KolProfile::create([
            'user_id' => $user->id,
            'nickname' => 'Valid KOL',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($user)->put(route('kol.profile.update'), [
            'nickname' => 'Valid KOL',
            'social_media' => [],
        ]);

        $response->assertSessionHasErrors('social_media');
    }

    public function test_validation_requires_nickname(): void
    {
        $user = $this->createUserWithRole('kol');
        KolProfile::create([
            'user_id' => $user->id,
            'nickname' => 'Valid KOL',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($user)->put(route('kol.profile.update'), [
            'nickname' => '',
            'social_media' => [
                [
                    'platform' => 'instagram',
                    'username' => 'validkol',
                    'followers_count' => 1000,
                    'engagement_rate' => 2.0,
                ],
            ],
        ]);

        $response->assertSessionHasErrors('nickname');
    }

    private function createUserWithRole(string $role): User
    {
        $user = User::factory()->create();
        $user->assignRole(Role::create(['name' => $role, 'display_name' => ucfirst($role)]));

        return $user;
    }
}
