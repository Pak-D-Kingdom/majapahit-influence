<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Brand;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ProductService
{
    /**
     * Create a new product for a brand.
     * The product will automatically have 'pending' verification status.
     */
    public function createProduct(Brand $brand, array $data, ?UploadedFile $image = null): Product
    {
        $product = new Product($data);
        $product->brand_id = $brand->id;

        // Generate unique slug
        $baseSlug = Str::slug($data['name']);
        $slug = $baseSlug;
        $counter = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }
        $product->slug = $slug;

        // Force to pending and inactive
        $product->verification_status = 'pending';
        $product->is_active = false;

        if ($image) {
            $path = $image->store("products/{$brand->id}", 'public');
            $product->image_path = $path;
        }

        $product->save();

        return $product;
    }

    /**
     * Verify a product (approve or reject).
     */
    public function verifyProduct(Product $product, string $status, ?string $reason, User $admin): Product
    {
        $oldValues = [
            'verification_status' => $product->verification_status,
            'is_active' => $product->is_active,
            'rejection_reason' => $product->rejection_reason,
        ];

        $product->verification_status = $status;

        if ($status === 'approved') {
            $product->is_active = true;
            $product->rejection_reason = null;
        } elseif ($status === 'rejected') {
            $product->is_active = false;
            $product->rejection_reason = $reason;
        }

        $product->save();

        $newValues = [
            'verification_status' => $product->verification_status,
            'is_active' => $product->is_active,
            'rejection_reason' => $product->rejection_reason,
        ];

        AuditLog::log(
            'product_verified',
            'product',
            $product->id,
            $oldValues,
            $newValues
        );

        return $product;
    }
}
