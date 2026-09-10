<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Brand;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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

        // Notify Superadmins
        try {
            app(NotificationService::class)->notifySuperadmins(
                'product_created',
                'Produk Baru Menunggu Verifikasi',
                "Brand '{$brand->name}' mendaftarkan produk baru '{$product->name}' yang memerlukan persetujuan.",
                route('superadmin.product-verifications.index')
            );
        } catch (\Throwable) {
            // Ignore notification failure if table not ready
        }

        return $product;
    }

    /**
     * Request an update/edit for an existing product.
     * The changes will be stored in pending_changes until approved by Superadmin.
     */
    public function requestUpdate(Product $product, array $data, ?UploadedFile $image = null): Product
    {
        $pendingChanges = [
            'name' => $data['name'],
            'category_id' => $data['category_id'],
            'description' => $data['description'],
            'price' => (float) $data['price'],
            'locked_commission_percent' => (float) $data['locked_commission_percent'],
            'short_description' => $data['short_description'] ?? $product->short_description,
            'sku' => $data['sku'] ?? $product->sku,
        ];

        if ($image) {
            // Store new temporary image for review
            $path = $image->store("products/{$product->brand_id}/pending", 'public');
            $pendingChanges['image_path'] = $path;
        }

        $product->pending_changes = $pendingChanges;
        $product->verification_status = 'pending_update';
        $product->rejection_reason = null;
        $product->save();

        // Notify Superadmins
        try {
            app(NotificationService::class)->notifySuperadmins(
                'product_update_request',
                'Pengajuan Edit Produk',
                "Brand '{$product->brand?->name}' mengajukan perubahan data untuk produk '{$product->name}'.",
                route('superadmin.product-verifications.index', ['type' => 'update'])
            );
        } catch (\Throwable) {
            // Ignore notification failure
        }

        return $product;
    }

    /**
     * Request deletion of a product.
     * Sets verification_status to 'pending_delete' with a deletion reason.
     */
    public function requestDeletion(Product $product, ?string $reason = null): Product
    {
        $product->verification_status = 'pending_delete';
        $product->deletion_reason = $reason ?: 'Pengajuan penghapusan produk oleh Brand.';
        $product->save();

        // Notify Superadmins
        try {
            app(NotificationService::class)->notifySuperadmins(
                'product_delete_request',
                'Pengajuan Hapus Produk',
                "Brand '{$product->brand?->name}' mengajukan penghapusan produk '{$product->name}'.",
                route('superadmin.product-verifications.index', ['type' => 'delete'])
            );
        } catch (\Throwable) {
            // Ignore notification failure
        }

        return $product;
    }

    /**
     * Verify a product (approve or reject) across Create, Update, and Delete requests.
     */
    public function verifyProduct(Product $product, string $status, ?string $reason, User $admin): Product
    {
        $oldVerificationStatus = $product->verification_status;
        $oldValues = [
            'verification_status' => $product->verification_status,
            'is_active' => $product->is_active,
            'rejection_reason' => $product->rejection_reason,
            'pending_changes' => $product->pending_changes,
        ];

        // 1. Handling PENDING UPDATE (Edit Approval)
        if ($oldVerificationStatus === 'pending_update') {
            if ($status === 'approved') {
                $pending = $product->pending_changes ?? [];

                if (! empty($pending['image_path'])) {
                    // Delete old active image if exists
                    if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
                        Storage::disk('public')->delete($product->image_path);
                    }
                    $product->image_path = $pending['image_path'];
                }

                if (isset($pending['name'])) {
                    $product->name = $pending['name'];
                }
                if (isset($pending['category_id'])) {
                    $product->category_id = $pending['category_id'];
                }
                if (isset($pending['description'])) {
                    $product->description = $pending['description'];
                }
                if (isset($pending['price'])) {
                    $product->price = $pending['price'];
                }
                if (isset($pending['locked_commission_percent'])) {
                    $product->locked_commission_percent = $pending['locked_commission_percent'];
                    $product->locked_commission_amount = round(($product->price * $product->locked_commission_percent) / 100, 2);
                }
                if (isset($pending['short_description'])) {
                    $product->short_description = $pending['short_description'];
                }
                if (isset($pending['sku'])) {
                    $product->sku = $pending['sku'];
                }

                $product->pending_changes = null;
                $product->verification_status = 'approved';
                $product->rejection_reason = null;
                $product->save();

                if ($product->brand?->user) {
                    app(NotificationService::class)->send(
                        $product->brand->user,
                        'product_verification',
                        'Perubahan Produk Disetujui',
                        "Pengajuan perubahan data untuk produk '{$product->name}' Anda telah disetujui oleh Admin.",
                        route('brand.products.index')
                    );
                }
            } else {
                // Rejected update
                $pending = $product->pending_changes ?? [];
                if (! empty($pending['image_path']) && Storage::disk('public')->exists($pending['image_path'])) {
                    Storage::disk('public')->delete($pending['image_path']);
                }

                $product->pending_changes = null;
                $product->verification_status = 'approved'; // Revert back to approved live state
                $product->rejection_reason = $reason;
                $product->save();

                if ($product->brand?->user) {
                    app(NotificationService::class)->send(
                        $product->brand->user,
                        'product_verification',
                        'Perubahan Produk Ditolak',
                        "Pengajuan perubahan data untuk produk '{$product->name}' ditolak oleh Admin. Alasan: {$reason}",
                        route('brand.products.index')
                    );
                }
            }
        }
        // 2. Handling PENDING DELETE (Delete Approval)
        elseif ($oldVerificationStatus === 'pending_delete') {
            if ($status === 'approved') {
                $productName = $product->name;
                $brandUser = $product->brand?->user;

                $product->verification_status = 'approved';
                $product->save();
                $product->delete(); // Soft delete

                if ($brandUser) {
                    app(NotificationService::class)->send(
                        $brandUser,
                        'product_verification',
                        'Penghapusan Produk Disetujui',
                        "Pengajuan penghapusan produk '{$productName}' telah disetujui oleh Admin dan produk telah dihapus dari katalog.",
                        route('brand.products.index')
                    );
                }

                return $product;
            } else {
                // Rejected deletion
                $product->verification_status = 'approved';
                $product->deletion_reason = null;
                $product->rejection_reason = $reason;
                $product->save();

                if ($product->brand?->user) {
                    app(NotificationService::class)->send(
                        $product->brand->user,
                        'product_verification',
                        'Penghapusan Produk Ditolak',
                        "Pengajuan penghapusan produk '{$product->name}' ditolak oleh Admin. Alasan: {$reason}",
                        route('brand.products.index')
                    );
                }
            }
        }
        // 3. Handling PENDING CREATE (New Product Approval)
        else {
            $product->verification_status = $status;

            if ($status === 'approved') {
                $product->is_active = false;
                $product->rejection_reason = null;
            } elseif ($status === 'rejected') {
                $product->is_active = false;
                $product->rejection_reason = $reason;
            }

            $product->save();

            if ($product->brand?->user) {
                $title = $status === 'approved' ? 'Produk Disetujui' : 'Produk Ditolak';
                $body = $status === 'approved'
                    ? "Produk '{$product->name}' Anda telah disetujui dan masuk ke antrean katalog produk."
                    : "Produk '{$product->name}' Anda ditolak. Alasan: {$reason}";

                app(NotificationService::class)->send(
                    $product->brand->user,
                    'product_verification',
                    $title,
                    $body,
                    route('brand.products.index')
                );
            }
        }

        $newValues = [
            'verification_status' => $product->verification_status,
            'is_active' => $product->is_active,
            'rejection_reason' => $product->rejection_reason,
            'pending_changes' => $product->pending_changes,
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
