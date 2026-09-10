<?php

namespace Tests\Feature;

use App\Models\KolRegistration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KolRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_renders_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_kol_registration_approve_handles_associative_social_media(): void
    {
        $this->seed();
        $admin = User::whereHas('roles', fn ($q) => $q->where('name', 'superadmin'))->first();

        // 1. Single associative social_media array
        $reg1 = KolRegistration::create([
            'registration_number' => 'REG-TEST-001',
            'full_name' => 'Budi Tester',
            'email' => 'budi.tester@example.com',
            'phone' => '081234567800',
            'city' => 'Jakarta',
            'niches' => ['Lifestyle'],
            'social_media' => [
                'platform' => 'instagram',
                'username' => '@buditester',
                'followers_count' => 15000,
            ],
            'status' => 'pending_review',
        ]);

        $response1 = $this->actingAs($admin)->post(route('superadmin.registrations.approve', $reg1->id), [
            'tier_id' => 1,
            'notes' => 'Test approved',
        ]);

        $response1->assertRedirect(route('superadmin.registrations.index'));
        $this->assertDatabaseHas('kol_registrations', [
            'id' => $reg1->id,
            'status' => 'approved',
        ]);
        $this->assertDatabaseHas('kol_social_media', [
            'username' => '@buditester',
            'platform' => 'instagram',
        ]);
    }

    public function test_kol_can_register_with_multiple_platforms(): void
    {
        $this->seed();

        $response = $this->post(route('registration.store'), [
            'full_name' => 'Siti MultiPlatform',
            'email' => 'siti.multi@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'phone' => '081298765432',
            'city' => 'Surabaya',
            'niches' => ['Fashion', 'Beauty'],
            'platforms' => ['instagram', 'tiktok', 'youtube', 'facebook', 'threads'],
            'social_media' => [
                'instagram' => [
                    'username' => '@sitigram',
                    'followers_count' => 25000,
                    'profile_url' => 'https://instagram.com/sitigram',
                ],
                'tiktok' => [
                    'username' => '@sititiktok',
                    'followers_count' => 80000,
                    'profile_url' => 'https://tiktok.com/@sititiktok',
                ],
                'youtube' => [
                    'username' => '@sitiyoutube',
                    'followers_count' => 10000,
                    'profile_url' => 'https://youtube.com/@sitiyoutube',
                ],
                'facebook' => [
                    'username' => 'Siti Official Page',
                    'followers_count' => 12000,
                    'profile_url' => 'https://facebook.com/sitiofficial',
                ],
                'threads' => [
                    'username' => '@sitithreads',
                    'followers_count' => 5000,
                    'profile_url' => 'https://threads.net/@sitithreads',
                ],
            ],
            'expected_rate' => 'Rp 1.000.000',
            'join_reason' => 'Ingin kolaborasi dengan brand ternama',
            'terms' => '1',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('kol_registrations', [
            'email' => 'siti.multi@example.com',
            'full_name' => 'Siti MultiPlatform',
        ]);

        $registration = KolRegistration::where('email', 'siti.multi@example.com')->first();
        $this->assertNotNull($registration);
        $this->assertCount(5, $registration->normalized_social_media);
        $this->assertStringContainsString('Instagram', $registration->platforms_label);
        $this->assertStringContainsString('Tiktok', $registration->platforms_label);
        $this->assertStringContainsString('Youtube', $registration->platforms_label);
        $this->assertStringContainsString('Facebook', $registration->platforms_label);
        $this->assertStringContainsString('Threads', $registration->platforms_label);

        // Test superadmin approve creates all 5 social media accounts
        $admin = User::whereHas('roles', fn ($q) => $q->where('name', 'superadmin'))->first();
        $approveResponse = $this->actingAs($admin)->post(route('superadmin.registrations.approve', $registration->id), [
            'tier_id' => 2,
            'notes' => 'Approved with multiple platforms',
        ]);

        $approveResponse->assertRedirect(route('superadmin.registrations.index'));
        $this->assertDatabaseHas('kol_social_media', [
            'username' => '@sitigram',
            'platform' => 'instagram',
        ]);
        $this->assertDatabaseHas('kol_social_media', [
            'username' => '@sititiktok',
            'platform' => 'tiktok',
        ]);
        $this->assertDatabaseHas('kol_social_media', [
            'username' => '@sitiyoutube',
            'platform' => 'youtube',
        ]);
        $this->assertDatabaseHas('kol_social_media', [
            'username' => 'Siti Official Page',
            'platform' => 'facebook',
        ]);
        $this->assertDatabaseHas('kol_social_media', [
            'username' => '@sitithreads',
            'platform' => 'threads',
        ]);
    }
}
