<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'brand_id',
        'category_id',
        'name',
        'slug',
        'sku',
        'description',
        'short_description',
        'price',
        'locked_commission_percent',
        'locked_commission_amount',
        'image_path',
        'stock',
        'promotion_pathway',
        'is_active',
        'verification_status',
        'rejection_reason',
        'pending_changes',
        'deletion_reason',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'locked_commission_percent' => 'decimal:2',
            'locked_commission_amount' => 'decimal:2',
            'stock' => 'integer',
            'is_active' => 'boolean',
            'pending_changes' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name).'-'.Str::random(5);
            }
            if ($product->price > 0 && ($product->locked_commission_amount <= 0 || empty($product->locked_commission_amount))) {
                $percent = $product->locked_commission_percent ?: 40.00;
                $product->locked_commission_amount = round(($product->price * $percent) / 100, 2);
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty(['price', 'locked_commission_percent'])) {
                $percent = $product->locked_commission_percent ?: 40.00;
                $product->locked_commission_amount = round(($product->price * $percent) / 100, 2);
            }
        });
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function contentBanks(): HasMany
    {
        return $this->hasMany(ContentBank::class);
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp '.number_format((float) $this->price, 0, ',', '.');
    }

    public function getFormattedCommissionAttribute(): string
    {
        return 'Rp '.number_format((float) $this->locked_commission_amount, 0, ',', '.');
    }

    public function getImageUrlAttribute(): string
    {
        if (empty($this->image_path)) {
            return 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=500&auto=format&fit=crop&q=80';
        }

        if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
            return $this->image_path;
        }

        return Storage::url($this->image_path);
    }

    public function getPendingImageUrlAttribute(): ?string
    {
        $pendingPath = $this->pending_changes['image_path'] ?? null;
        if (empty($pendingPath)) {
            return null;
        }

        if (str_starts_with($pendingPath, 'http://') || str_starts_with($pendingPath, 'https://')) {
            return $pendingPath;
        }

        return Storage::url($pendingPath);
    }

    public function getPendingCategoryAttribute(): ?ProductCategory
    {
        $catId = $this->pending_changes['category_id'] ?? null;
        if (! $catId) {
            return null;
        }

        return ProductCategory::find($catId);
    }

    public function getVerificationStatusLabelAttribute(): string
    {
        return match ($this->verification_status) {
            'approved' => 'Disetujui',
            'pending' => 'Menunggu Review Baru',
            'pending_update' => 'Menunggu Persetujuan Edit',
            'pending_delete' => 'Menunggu Persetujuan Hapus',
            'rejected' => 'Ditolak',
            default => ucfirst((string) $this->verification_status),
        };
    }
}
