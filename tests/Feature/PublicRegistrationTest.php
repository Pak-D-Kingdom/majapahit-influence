<?php

namespace Tests\Feature;

use App\Models\Niche;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PublicRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_kol_can_submit_registration_successfully()
    {
        // 1. Setup mock storage untuk file upload & buat Niche di DB
        Storage::fake('local');
        $niche = Niche::create(['name' => 'Technology', 'is_active' => true]);

        // 2. Siapkan file palsu (mock file)
        $file = UploadedFile::fake()->image('portfolio.jpg')->size(1024); // 1MB

        // 3. Data payload yang akan dikirim (tanpa UI)
        $payload = [
            'full_name' => 'Testing KOL Tanpa UI',
            'email'     => 'testkol@example.com',
            'phone'     => '081234567890',
            'city'      => 'Jakarta',
            'join_reason' => 'Saya ingin mencoba mendaftar tanpa lewat UI karena saya backend developer.',
            'expected_rate' => 'Rp 1.000.000',
            'agreement' => 'on', // Checkbox "on"
            'niches'    => [
                $niche->name
            ],
            'social_media' => [
                [
                    'platform' => 'instagram',
                    'username' => 'testkol',
                    'profile_url' => 'https://instagram.com/testkol',
                    'followers_count' => 50000,
                ]
            ],
            'portfolio' => [
                $file
            ]
        ];

        // 4. Hit endpoint POST /daftar langsung (simulate form submit)
        $response = $this->post(route('public.kol.store'), $payload);

        // 5. Assert: Pastikan redirect ke halaman konfirmasi
        $response->assertRedirect(route('public.kol.confirmation'));
        $response->assertSessionHas('registration_number');

        // 6. Assert: Pastikan masuk ke Database tabel kol_registrations
        $this->assertDatabaseHas('kol_registrations', [
            'email' => 'testkol@example.com',
            'full_name' => 'Testing KOL Tanpa UI',
            'status' => 'pending_review'
        ]);
        
        // 7. Assert: Pastikan portfolio file tersimpan
        $registrationId = \App\Models\KolRegistration::first()->id;
        $filesInStorage = Storage::disk('local')->allFiles("registrations/{$registrationId}");
        $this->assertNotEmpty($filesInStorage, 'File portfolio harusnya tersimpan di storage.');
    }

    public function test_registration_fails_if_validation_not_met()
    {
        // Hit endpoint tanpa data (kosong)
        $response = $this->post(route('public.kol.store'), []);

        // Assert: Pastikan error validation muncul
        $response->assertSessionHasErrors(['full_name', 'email', 'phone', 'niches', 'social_media', 'portfolio']);
    }
}
