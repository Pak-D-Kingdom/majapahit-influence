<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\ProductCategory;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BrandPortalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('brand.dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_brand_user_can_access_dashboard_and_pages(): void
    {
        $brandRole = Role::firstOrCreate(['name' => 'brand'], ['display_name' => 'Brand']);
        $user = User::factory()->create();
        $user->roles()->attach($brandRole);

        $brand = Brand::create([
            'user_id' => $user->id,
            'name' => 'Brand Test',
            'pic_name' => 'Brand PIC',
            'pic_email' => $user->email,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->get(route('brand.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Brand Test');

        $prodResponse = $this->actingAs($user)->get(route('brand.products.index'));
        $prodResponse->assertStatus(200);

        $campResponse = $this->actingAs($user)->get(route('brand.campaigns.index'));
        $campResponse->assertStatus(200);

        $endResponse = $this->actingAs($user)->get(route('brand.endorsements.index'));
        $endResponse->assertStatus(200);
    }

    public function test_brand_user_can_create_product_for_verification(): void
    {
        Storage::fake('public');

        $brandRole = Role::firstOrCreate(['name' => 'brand'], ['display_name' => 'Brand']);
        $user = User::factory()->create();
        $user->roles()->attach($brandRole);

        $brand = Brand::create([
            'user_id' => $user->id,
            'name' => 'Brand Test',
            'pic_name' => 'Brand PIC',
            'pic_email' => $user->email,
            'is_active' => true,
        ]);

        $category = ProductCategory::firstOrCreate(
            ['slug' => 'beauty-test'],
            ['name' => 'Beauty Test', 'description' => 'Test Cat']
        );

        $response = $this->actingAs($user)->post(route('brand.products.store'), [
            'category_id' => $category->id,
            'name' => 'Serum Baru',
            'price' => 125000,
            'locked_commission_percent' => 10,
            'description' => 'Serum pencerah kulit',
            'image' => UploadedFile::fake()->image('serum.jpg'),
        ]);

        $response->assertRedirect(route('brand.products.index'));
        $this->assertDatabaseHas('products', [
            'brand_id' => $brand->id,
            'name' => 'Serum Baru',
            'verification_status' => 'pending',
        ]);
    }
}
