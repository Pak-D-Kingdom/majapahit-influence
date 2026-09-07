<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContentBank extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_id',
        'product_id',
        'title',
        'asset_type',
        'file_path',
        'external_url',
        'content_text',
        'file_size',
        'mime_type',
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getDownloadUrlAttribute(): string
    {
        if ($this->asset_type === 'drive_link' || ! empty($this->external_url)) {
            return $this->external_url;
        }

        if (! empty($this->file_path)) {
            return asset($this->file_path);
        }

        return '#';
    }
}
