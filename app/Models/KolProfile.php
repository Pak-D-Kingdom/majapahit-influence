<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class KolProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'nickname',
        'bio',
        'date_of_birth',
        'gender',
        'city',
        'province',
        'photo_path',
        'tier_id',
        'commission_override_pct',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'npwp',
        'status',
        'status_reason',
        'joined_at',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'commission_override_pct' => 'decimal:2',
            'joined_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tier(): BelongsTo
    {
        return $this->belongsTo(Tier::class);
    }

    public function niches(): BelongsToMany
    {
        return $this->belongsToMany(Niche::class, 'kol_niches');
    }

    public function socialMedia(): HasMany
    {
        return $this->hasMany(KolSocialMedia::class);
    }

    public function rateCards(): HasMany
    {
        return $this->hasMany(KolRateCard::class);
    }

    public function endorsements(): HasMany
    {
        return $this->hasMany(Endorsement::class);
    }

    public function commissions(): HasMany
    {
        return $this->hasMany(Commission::class);
    }

    /**
     * Get effective commission percentage (Tier default or Override).
     */
    public function getEffectiveCommissionPctAttribute(): float
    {
        if (!is_null($this->commission_override_pct)) {
            return (float) $this->commission_override_pct;
        }

        return (float) ($this->tier?->commission_pct ?? 60.00);
    }

    /**
     * Scope for active KOLs.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'aktif');
    }
}
