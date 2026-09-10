<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BrandRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_name',
        'company_name',
        'industry_category',
        'pic_name',
        'pic_title',
        'pic_email',
        'pic_phone',
        'password',
        'social_media',
        'website',
        'service_need',
        'notes',
        'status',
        'reviewed_by',
        'reviewed_at',
        'admin_notes',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function getServiceNeedLabelAttribute(): string
    {
        return match ($this->service_need) {
            'endorsement' => 'Promosi & Endorsement Influencer',
            'maklon' => 'Maklon / Pembuatan Produk Baru',
            'both' => 'Maklon Produk + Promosi Influencer',
            default => $this->service_need,
        };
    }
}
