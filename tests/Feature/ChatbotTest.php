<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\KolProfile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ChatbotTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_access_chatbot(): void
    {
        $response = $this->postJson(route('chatbot.chat'), [
            'message' => 'Halo',
            'role' => 'kol',
        ]);

        $response->assertUnauthorized();
    }

    public function test_validates_required_fields(): void
    {
        $user = $this->createUserWithRole('kol');

        $response = $this->actingAs($user)->postJson(route('chatbot.chat'), []);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['message', 'role']);
    }

    public function test_notifies_when_groq_api_key_is_missing(): void
    {
        Config::set('services.groq.api_key', null);

        $user = $this->createUserWithRole('kol');

        $response = $this->actingAs($user)->postJson(route('chatbot.chat'), [
            'message' => 'Halo Prabu',
            'role' => 'kol',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => false,
        ]);
        $this->assertStringContainsString('GROQ_API_KEY belum dikonfigurasi', $response->json('reply'));
    }

    public function test_kol_can_chat_with_prabu_ai(): void
    {
        Config::set('services.groq.api_key', 'gsk-fake-test-key');

        $user = $this->createUserWithRole('kol');
        KolProfile::create([
            'user_id' => $user->id,
            'nickname' => 'Gajah Mada',
            'status' => 'aktif',
        ]);

        Http::fake([
            'https://api.groq.com/openai/v1/chat/completions' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'role' => 'assistant',
                            'content' => 'Halo Gajah Mada! Anda saat ini berada di peringkat #1.',
                        ],
                    ],
                ],
            ], 200),
        ]);

        $response = $this->actingAs($user)->postJson(route('chatbot.chat'), [
            'message' => 'saya urutan ke berapa di leaderboard',
            'role' => 'kol',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'reply' => 'Halo Gajah Mada! Anda saat ini berada di peringkat #1.',
        ]);
    }

    public function test_brand_can_chat_with_prabu_ai(): void
    {
        Config::set('services.groq.api_key', 'gsk-fake-test-key');

        $user = $this->createUserWithRole('brand');
        Brand::create([
            'name' => 'Keraton Nusantara',
            'industry' => 'Fashion',
            'pic_email' => $user->email,
        ]);

        Http::fake([
            'https://api.groq.com/openai/v1/chat/completions' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'role' => 'assistant',
                            'content' => 'Halo Brand Keraton Nusantara! Anda memiliki beberapa produk aktif.',
                        ],
                    ],
                ],
            ], 200),
        ]);

        $response = $this->actingAs($user)->postJson(route('chatbot.chat'), [
            'message' => 'Berapa produk saya yang aktif?',
            'role' => 'brand',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'reply' => 'Halo Brand Keraton Nusantara! Anda memiliki beberapa produk aktif.',
        ]);
    }

    private function createUserWithRole(string $role): User
    {
        $user = User::factory()->create();
        $user->assignRole(Role::firstOrCreate(['name' => $role], ['display_name' => ucfirst($role)]));

        return $user;
    }
}
