<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Campaign;
use App\Models\Endorsement;
use App\Models\KolProfile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class KolContentProofTest extends TestCase
{
    use RefreshDatabase;

    public function test_kol_can_view_upload_proof_form_for_allowed_status(): void
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
            'status' => 'assigned',
            'assigned_by' => $superadmin->id,
        ]);

        $response = $this->actingAs($user)->get(route('kol.endorsements.proof.create', $endorsement));

        $response->assertOk();
        $response->assertSee('Upload Bukti Konten');
    }

    public function test_kol_cannot_upload_proof_for_finished_endorsement(): void
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

        $response = $this->actingAs($user)->get(route('kol.endorsements.proof.create', $endorsement));
        $response->assertForbidden();

        $file = UploadedFile::fake()->image('proof.jpg');
        $responseSubmit = $this->actingAs($user)->post(route('kol.endorsements.proof.store', $endorsement), [
            'posted_at' => now()->toDateString(),
            'files' => [$file],
        ]);
        $responseSubmit->assertForbidden();
    }

    public function test_kol_can_submit_content_proof_successfully(): void
    {
        Storage::fake('public');

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
            'status' => 'assigned',
            'assigned_by' => $superadmin->id,
        ]);

        $file = UploadedFile::fake()->image('proof.jpg');

        $response = $this->actingAs($user)->post(route('kol.endorsements.proof.store', $endorsement), [
            'posted_at' => now()->toDateString(),
            'post_url' => 'https://instagram.com/p/123',
            'notes' => 'Here is the proof',
            'files' => [$file],
        ]);

        $response->assertRedirect(route('kol.endorsements.show', $endorsement));

        $this->assertDatabaseHas('content_proofs', [
            'endorsement_id' => $endorsement->id,
            'post_url' => 'https://instagram.com/p/123',
        ]);

        $this->assertDatabaseHas('endorsements', [
            'id' => $endorsement->id,
            'status' => 'content_submitted',
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'content_proof_submitted',
            'entity_type' => 'endorsements',
            'entity_id' => $endorsement->id,
        ]);
    }

    public function test_proof_submission_validates_required_fields(): void
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
            'status' => 'assigned',
            'assigned_by' => $superadmin->id,
        ]);

        $response = $this->actingAs($user)->post(route('kol.endorsements.proof.store', $endorsement), []);

        $response->assertSessionHasErrors(['posted_at', 'files']);
    }

    private function createUserWithRole(string $role): User
    {
        $user = User::factory()->create();
        $user->assignRole(Role::firstOrCreate(['name' => $role], ['display_name' => ucfirst($role)]));

        return $user;
    }
}
