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
}
