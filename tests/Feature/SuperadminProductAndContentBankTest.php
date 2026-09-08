<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\ContentBank;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SuperadminProductAndContentBankTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected Brand $brand;

    protected ProductCategory $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->admin = User::whereHas('roles', fn ($q) => $q->where('name', 'superadmin'))->first();
        $this->brand = Brand::first();
        $this->category = ProductCategory::first();
        Storage::fake('public');
    }

    public function test_product_image_url_accessor_handles_urls_and_storage_paths(): void
    {
        $remoteProduct = new Product(['image_path' => 'https://images.unsplash.com/photo-12345']);
        $this->assertSame('https://images.unsplash.com/photo-12345', $remoteProduct->image_url);

        $localProduct = new Product(['image_path' => 'products/sample.jpg']);
        $this->assertStringContainsString('products/sample.jpg', $localProduct->image_url);

        $emptyProduct = new Product(['image_path' => null]);
        $this->assertNotEmpty($emptyProduct->image_url);
    }

    public function test_superadmin_can_create_product_with_image_upload(): void
    {
        $file = UploadedFile::fake()->image('lipcream.png', 600, 600);

        $response = $this->actingAs($this->admin)->post(route('superadmin.products.store'), [
            'brand_id' => $this->brand->id,
            'category_id' => $this->category->id,
            'name' => 'Matte Lipcream Velvet',
            'sku' => 'LIP-001',
            'price' => 85000,
            'locked_commission_percent' => 40,
            'stock' => 50,
            'promotion_pathway' => 'both',
            'short_description' => 'Lipcream velvet tahan lama',
            'description' => 'Formula ringan tidak membuat bibir kering dengan vitamin E.',
            'image' => $file,
        ]);

        $response->assertRedirect(route('superadmin.products.index'));

        $this->assertDatabaseHas('products', [
            'name' => 'Matte Lipcream Velvet',
            'brand_id' => $this->brand->id,
            'price' => 85000,
        ]);

        $product = Product::where('name', 'Matte Lipcream Velvet')->first();
        $this->assertNotNull($product->image_path);
        Storage::disk('public')->assertExists($product->image_path);
    }

    public function test_superadmin_can_update_product_and_replace_image(): void
    {
        $oldFile = UploadedFile::fake()->image('old_photo.jpg');
        $oldPath = $oldFile->store('products', 'public');

        $product = Product::create([
            'brand_id' => $this->brand->id,
            'category_id' => $this->category->id,
            'name' => 'Sunscreen SPF 50',
            'price' => 120000,
            'locked_commission_percent' => 40,
            'stock' => 100,
            'promotion_pathway' => 'both',
            'image_path' => $oldPath,
        ]);

        Storage::disk('public')->assertExists($oldPath);

        $newFile = UploadedFile::fake()->image('new_photo.png');

        $response = $this->actingAs($this->admin)->put(route('superadmin.products.update', $product->id), [
            'brand_id' => $this->brand->id,
            'category_id' => $this->category->id,
            'name' => 'Sunscreen SPF 50 Ultimate',
            'price' => 135000,
            'locked_commission_percent' => 40,
            'stock' => 80,
            'promotion_pathway' => 'marketplace',
            'image' => $newFile,
        ]);

        $response->assertRedirect(route('superadmin.products.index'));

        $product->refresh();
        $this->assertSame('Sunscreen SPF 50 Ultimate', $product->name);
        $this->assertNotEquals($oldPath, $product->image_path);
        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($product->image_path);
    }

    public function test_superadmin_can_add_content_bank_with_drive_link(): void
    {
        $product = Product::first();

        $response = $this->actingAs($this->admin)->post(route('superadmin.products.content-banks.store', $product->id), [
            'title' => 'Folder Master Google Drive - '.$product->name,
            'external_url' => 'https://drive.google.com/drive/folders/broll123',
            'content_text' => '1. Video footage 4K\n2. Foto produk HD PNG\n3. Script naskah copywriting',
        ]);

        $response->assertRedirect(route('superadmin.products.edit', $product->id));

        $this->assertDatabaseHas('content_banks', [
            'product_id' => $product->id,
            'asset_type' => 'drive_link',
            'external_url' => 'https://drive.google.com/drive/folders/broll123',
            'content_text' => '1. Video footage 4K\n2. Foto produk HD PNG\n3. Script naskah copywriting',
        ]);
    }

    public function test_superadmin_can_create_product_with_integrated_drive_folder(): void
    {
        $file = UploadedFile::fake()->image('sunscreen.png');

        $response = $this->actingAs($this->admin)->post(route('superadmin.products.store'), [
            'brand_id' => $this->brand->id,
            'category_id' => $this->category->id,
            'name' => 'Hydrating Sunscreen Gel',
            'sku' => 'SUN-001',
            'price' => 99000,
            'locked_commission_percent' => 40,
            'stock' => 50,
            'promotion_pathway' => 'both',
            'short_description' => 'Sunscreen gel ringan SPF 50',
            'description' => 'Perlindungan maksimal UV dengan ekstrak Centella Asiatica.',
            'image' => $file,
            'drive_folder_url' => 'https://drive.google.com/drive/folders/sunscreen-assets',
            'drive_description' => '1. Video B-Roll\n2. Foto HD\n3. Script Naskah',
        ]);

        $response->assertRedirect(route('superadmin.products.index'));

        $product = Product::where('name', 'Hydrating Sunscreen Gel')->first();
        $this->assertNotNull($product);

        $this->assertDatabaseHas('content_banks', [
            'product_id' => $product->id,
            'asset_type' => 'drive_link',
            'external_url' => 'https://drive.google.com/drive/folders/sunscreen-assets',
            'content_text' => '1. Video B-Roll\n2. Foto HD\n3. Script Naskah',
        ]);
    }

    public function test_superadmin_can_update_and_delete_content_bank_asset(): void
    {
        $product = Product::first();

        $asset = ContentBank::create([
            'brand_id' => $product->brand_id,
            'product_id' => $product->id,
            'title' => 'Folder Master GDrive',
            'asset_type' => 'drive_link',
            'external_url' => 'https://drive.google.com/drive/folders/oldbanner',
            'content_text' => 'Koleksi materi lama.',
        ]);

        // Update asset link & description
        $response = $this->actingAs($this->admin)->put(route('superadmin.content-banks.update', $asset->id), [
            'title' => 'Folder Master GDrive Update',
            'external_url' => 'https://drive.google.com/drive/folders/newbanner',
            'content_text' => '1. Video B-Roll 4K\n2. Foto Banner 9:16',
        ]);

        $response->assertRedirect(route('superadmin.products.edit', $product->id));
        $this->assertDatabaseHas('content_banks', [
            'id' => $asset->id,
            'external_url' => 'https://drive.google.com/drive/folders/newbanner',
            'content_text' => '1. Video B-Roll 4K\n2. Foto Banner 9:16',
        ]);

        // Delete asset
        $delResponse = $this->actingAs($this->admin)->delete(route('superadmin.content-banks.destroy', $asset->id));
        $delResponse->assertRedirect(route('superadmin.products.edit', $product->id));

        $this->assertDatabaseMissing('content_banks', [
            'id' => $asset->id,
        ]);
    }

    public function test_approved_product_verification_keeps_product_unpublished_until_superadmin_publishes(): void
    {
        $pendingProduct = Product::create([
            'brand_id' => $this->brand->id,
            'category_id' => $this->category->id,
            'name' => 'Pending Serum Booster',
            'price' => 150000,
            'locked_commission_percent' => 40,
            'stock' => 20,
            'promotion_pathway' => 'both',
            'verification_status' => 'pending',
            'is_active' => false,
        ]);

        $response = $this->actingAs($this->admin)->post(route('superadmin.product-verifications.verify', $pendingProduct->id), [
            'status' => 'approved',
        ]);

        $response->assertRedirect(route('superadmin.product-verifications.index'));

        $pendingProduct->refresh();
        $this->assertSame('approved', $pendingProduct->verification_status);
        $this->assertFalse($pendingProduct->is_active); // Must remain unpublished until superadmin publishes
    }

    public function test_superadmin_cannot_publish_product_without_gdrive_bank_konten(): void
    {
        $draftProduct = Product::create([
            'brand_id' => $this->brand->id,
            'category_id' => $this->category->id,
            'name' => 'Draft Product No GDrive',
            'price' => 120000,
            'locked_commission_percent' => 40,
            'stock' => 10,
            'promotion_pathway' => 'both',
            'verification_status' => 'approved',
            'is_active' => false,
        ]);

        // Attempt to publish without GDrive
        $response = $this->actingAs($this->admin)->post(route('superadmin.products.toggle-publish', $draftProduct->id));

        $response->assertRedirect(route('superadmin.products.edit', $draftProduct->id));
        $response->assertSessionHas('error');

        $draftProduct->refresh();
        $this->assertFalse($draftProduct->is_active);
    }

    public function test_superadmin_can_publish_and_unpublish_product_with_gdrive_bank_konten(): void
    {
        $draftProduct = Product::create([
            'brand_id' => $this->brand->id,
            'category_id' => $this->category->id,
            'name' => 'Draft Product With GDrive',
            'price' => 120000,
            'locked_commission_percent' => 40,
            'stock' => 10,
            'promotion_pathway' => 'both',
            'verification_status' => 'approved',
            'is_active' => false,
        ]);

        // Attach GDrive bank konten
        ContentBank::create([
            'brand_id' => $draftProduct->brand_id,
            'product_id' => $draftProduct->id,
            'title' => 'Folder GDrive Master',
            'asset_type' => 'drive_link',
            'external_url' => 'https://drive.google.com/drive/folders/test12345',
            'content_text' => '1. Video 4K\n2. Foto HD',
        ]);

        // Publish to E-Commerce
        $response = $this->actingAs($this->admin)->post(route('superadmin.products.toggle-publish', $draftProduct->id));
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $draftProduct->refresh();
        $this->assertTrue($draftProduct->is_active);

        // Unpublish from E-Commerce
        $unpublishResponse = $this->actingAs($this->admin)->post(route('superadmin.products.toggle-publish', $draftProduct->id));
        $unpublishResponse->assertRedirect();
        $unpublishResponse->assertSessionHas('success');

        $draftProduct->refresh();
        $this->assertFalse($draftProduct->is_active);
    }
}
