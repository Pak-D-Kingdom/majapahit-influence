<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BrandProductApprovalWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected User $brandUser;

    protected Brand $brand;

    protected User $admin;

    protected ProductCategory $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        Storage::fake('public');

        $brandRole = Role::firstOrCreate(['name' => 'brand'], ['display_name' => 'Brand']);
        $this->brandUser = User::factory()->create();
        $this->brandUser->roles()->attach($brandRole);

        $this->brand = Brand::create([
            'user_id' => $this->brandUser->id,
            'name' => 'Brand Skincare Pro',
            'pic_name' => 'Brand PIC',
            'pic_email' => $this->brandUser->email,
            'is_active' => true,
        ]);

        $this->admin = User::whereHas('roles', fn ($q) => $q->where('name', 'superadmin'))->first();
        $this->category = ProductCategory::firstOrCreate(
            ['slug' => 'beauty-test-approval'],
            ['name' => 'Beauty Test Approval', 'description' => 'Test']
        );
    }

    public function test_brand_user_can_request_product_update_and_it_becomes_pending_update(): void
    {
        $product = Product::create([
            'brand_id' => $this->brand->id,
            'category_id' => $this->category->id,
            'name' => 'Original Face Wash',
            'slug' => 'original-face-wash',
            'price' => 50000,
            'locked_commission_percent' => 20,
            'locked_commission_amount' => 10000,
            'description' => 'Original description',
            'verification_status' => 'approved',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->brandUser)->put(route('brand.products.update', $product), [
            'name' => 'Updated Gentle Face Wash',
            'category_id' => $this->category->id,
            'price' => 75000,
            'locked_commission_percent' => 25,
            'description' => 'New formula description',
        ]);

        $response->assertRedirect(route('brand.products.index'));

        $product->refresh();
        $this->assertSame('pending_update', $product->verification_status);
        // Original attributes remain unchanged until approved
        $this->assertSame('Original Face Wash', $product->name);
        $this->assertEquals(50000, (float) $product->price);

        // Pending changes array contains proposed values
        $this->assertIsArray($product->pending_changes);
        $this->assertSame('Updated Gentle Face Wash', $product->pending_changes['name']);
        $this->assertEquals(75000, $product->pending_changes['price']);
    }

    public function test_superadmin_can_approve_product_update_request(): void
    {
        $product = Product::create([
            'brand_id' => $this->brand->id,
            'category_id' => $this->category->id,
            'name' => 'Old Shampoo',
            'slug' => 'old-shampoo',
            'price' => 60000,
            'locked_commission_percent' => 20,
            'locked_commission_amount' => 12000,
            'description' => 'Old description',
            'verification_status' => 'pending_update',
            'pending_changes' => [
                'name' => 'Super Herbal Shampoo',
                'category_id' => $this->category->id,
                'price' => 90000,
                'locked_commission_percent' => 30,
                'description' => 'Herbal formula description',
            ],
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->post(route('superadmin.product-verifications.verify', $product), [
            'status' => 'approved',
        ]);

        $response->assertRedirect();
        $product->refresh();

        $this->assertSame('approved', $product->verification_status);
        $this->assertSame('Super Herbal Shampoo', $product->name);
        $this->assertEquals(90000, (float) $product->price);
        $this->assertEquals(30, (float) $product->locked_commission_percent);
        $this->assertEquals(27000, (float) $product->locked_commission_amount);
        $this->assertNull($product->pending_changes);
    }

    public function test_superadmin_can_reject_product_update_request_and_keep_original_values(): void
    {
        $product = Product::create([
            'brand_id' => $this->brand->id,
            'category_id' => $this->category->id,
            'name' => 'Unchanged Lip Serum',
            'slug' => 'unchanged-lip-serum',
            'price' => 45000,
            'locked_commission_percent' => 15,
            'locked_commission_amount' => 6750,
            'description' => 'Current approved description',
            'verification_status' => 'pending_update',
            'pending_changes' => [
                'name' => 'Disallowed Change Name',
                'category_id' => $this->category->id,
                'price' => 999999,
                'locked_commission_percent' => 80,
                'description' => 'Invalid claims description',
            ],
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->post(route('superadmin.product-verifications.verify', $product), [
            'status' => 'rejected',
            'rejection_reason' => 'Harga tidak wajar untuk kategori ini.',
        ]);

        $response->assertRedirect();
        $product->refresh();

        $this->assertSame('approved', $product->verification_status);
        $this->assertSame('Unchanged Lip Serum', $product->name);
        $this->assertEquals(45000, (float) $product->price);
        $this->assertNull($product->pending_changes);
        $this->assertSame('Harga tidak wajar untuk kategori ini.', $product->rejection_reason);
    }

    public function test_brand_user_can_request_product_deletion_and_it_becomes_pending_delete(): void
    {
        $product = Product::create([
            'brand_id' => $this->brand->id,
            'category_id' => $this->category->id,
            'name' => 'Product to Discontinue',
            'slug' => 'product-to-discontinue',
            'price' => 80000,
            'locked_commission_percent' => 20,
            'description' => 'Discontinued product',
            'verification_status' => 'approved',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->brandUser)->delete(route('brand.products.destroy', $product), [
            'deletion_reason' => 'Produk sudah discontinued dari pabrik.',
        ]);

        $response->assertRedirect(route('brand.products.index'));

        $product->refresh();
        $this->assertSame('pending_delete', $product->verification_status);
        $this->assertSame('Produk sudah discontinued dari pabrik.', $product->deletion_reason);
    }

    public function test_superadmin_can_approve_product_deletion_request(): void
    {
        $product = Product::create([
            'brand_id' => $this->brand->id,
            'category_id' => $this->category->id,
            'name' => 'Discontinued Item',
            'slug' => 'discontinued-item',
            'price' => 80000,
            'locked_commission_percent' => 20,
            'description' => 'To be deleted',
            'verification_status' => 'pending_delete',
            'deletion_reason' => 'Stok habis total.',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->post(route('superadmin.product-verifications.verify', $product), [
            'status' => 'approved',
        ]);

        $response->assertRedirect();
        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }

    public function test_superadmin_can_reject_product_deletion_request(): void
    {
        $product = Product::create([
            'brand_id' => $this->brand->id,
            'category_id' => $this->category->id,
            'name' => 'Must Keep Product',
            'slug' => 'must-keep-product',
            'price' => 100000,
            'locked_commission_percent' => 20,
            'description' => 'Campaign still active',
            'verification_status' => 'pending_delete',
            'deletion_reason' => 'Mau hapus saja.',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->post(route('superadmin.product-verifications.verify', $product), [
            'status' => 'rejected',
            'rejection_reason' => 'Produk masih memiliki campaign aktif dengan KOL.',
        ]);

        $response->assertRedirect();
        $product->refresh();

        $this->assertSame('approved', $product->verification_status);
        $this->assertNull($product->deletion_reason);
        $this->assertSame('Produk masih memiliki campaign aktif dengan KOL.', $product->rejection_reason);
        $this->assertNull($product->deleted_at);
    }

    public function test_superadmin_verifications_page_renders_tabs_and_counts(): void
    {
        Product::create([
            'brand_id' => $this->brand->id,
            'category_id' => $this->category->id,
            'name' => 'Pending New Product',
            'slug' => 'pending-new-product',
            'price' => 50000,
            'locked_commission_percent' => 20,
            'description' => 'Desc',
            'verification_status' => 'pending',
        ]);

        Product::create([
            'brand_id' => $this->brand->id,
            'category_id' => $this->category->id,
            'name' => 'Pending Update Product',
            'slug' => 'pending-update-product',
            'price' => 60000,
            'locked_commission_percent' => 20,
            'description' => 'Desc',
            'verification_status' => 'pending_update',
            'pending_changes' => ['name' => 'New Name Proposal'],
        ]);

        Product::create([
            'brand_id' => $this->brand->id,
            'category_id' => $this->category->id,
            'name' => 'Pending Delete Product',
            'slug' => 'pending-delete-product',
            'price' => 70000,
            'locked_commission_percent' => 20,
            'description' => 'Desc',
            'verification_status' => 'pending_delete',
            'deletion_reason' => 'Mau dihapus',
        ]);

        $response = $this->actingAs($this->admin)->get(route('superadmin.product-verifications.index'));
        $response->assertStatus(200);
        $response->assertSee('Semua Antrean');
        $response->assertSee('Produk Baru');
        $response->assertSee('Pengajuan Edit');
        $response->assertSee('Pengajuan Hapus');
        $response->assertSee('Pending New Product');
        $response->assertSee('Pending Update Product');
        $response->assertSee('Pending Delete Product');

        $tabResponse = $this->actingAs($this->admin)->get(route('superadmin.product-verifications.index', ['type' => 'update']));
        $tabResponse->assertStatus(200);
        $tabResponse->assertSee('Pending Update Product');
    }
}
