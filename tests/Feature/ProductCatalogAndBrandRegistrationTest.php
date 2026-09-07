<?php

namespace Tests\Feature;

use App\Models\BrandRegistration;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCatalogAndBrandRegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_landing_page_renders_with_dual_audience_and_maklon_content(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Solusi Brand');
        $response->assertSee('Layanan Maklon');
        $response->assertSee('Daftar Brand');
        $response->assertSee('Katalog E-Commerce');
    }

    public function test_brand_registration_form_renders_and_submits(): void
    {
        $response = $this->get(route('brand.register'));
        $response->assertStatus(200);
        $response->assertSee('Form Pendaftaran Kemitraan Brand');

        $postResponse = $this->post(route('brand.register.store'), [
            'brand_name' => 'Brand Test Otomatis',
            'company_name' => 'PT Brand Test',
            'industry_category' => 'Beauty & Skincare',
            'pic_name' => 'Tester PIC',
            'pic_title' => 'CEO',
            'pic_email' => 'pic@brandtest.com',
            'pic_phone' => '081234567899',
            'service_need' => 'both',
            'notes' => 'Catatan test kebutuhan maklon',
        ]);

        $this->assertDatabaseHas('brand_registrations', [
            'brand_name' => 'Brand Test Otomatis',
            'pic_email' => 'pic@brandtest.com',
            'status' => 'pending',
        ]);

        $reg = BrandRegistration::where('brand_name', 'Brand Test Otomatis')->first();
        $postResponse->assertRedirect(route('brand.register.confirmation', $reg->id));
    }

    public function test_superadmin_can_approve_brand_registration(): void
    {
        $admin = User::whereHas('roles', fn ($q) => $q->where('name', 'superadmin'))->first();

        $reg = BrandRegistration::create([
            'brand_name' => 'Brand Herbal Super',
            'company_name' => 'CV Herbal Super',
            'industry_category' => 'Herbal & Kesehatan',
            'pic_name' => 'Ahmad PIC',
            'pic_email' => 'ahmad@herbal.com',
            'pic_phone' => '081122334455',
            'service_need' => 'endorsement',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->post(route('superadmin.brand-registrations.approve', $reg->id), [
            'admin_notes' => 'Disetujui.',
        ]);

        $response->assertRedirect(route('superadmin.brand-registrations.index'));
        $this->assertDatabaseHas('brand_registrations', [
            'id' => $reg->id,
            'status' => 'approved',
        ]);
        $this->assertDatabaseHas('brands', [
            'name' => 'Brand Herbal Super',
        ]);
    }

    public function test_catalog_index_and_filtering(): void
    {
        $response = $this->get(route('catalog.index'));
        $response->assertStatus(200);
        $response->assertSee('Katalog Produk', false);
        $response->assertSee('Komisi 40%', false);

        $category = ProductCategory::first();
        $filterResponse = $this->get(route('catalog.index', ['category' => $category->slug]));
        $filterResponse->assertStatus(200);
    }

    public function test_product_detail_and_bank_konten(): void
    {
        $product = Product::where('is_active', true)->first();

        $showResponse = $this->get(route('catalog.show', $product->slug));
        $showResponse->assertStatus(200);
        $showResponse->assertSee($product->name);
        $showResponse->assertSee('Bank Konten');

        $bankResponse = $this->get(route('catalog.content-bank', $product->slug));
        $bankResponse->assertStatus(200);
        $bankResponse->assertSee('BANK KONTEN BRAND RESMI');
    }
}
