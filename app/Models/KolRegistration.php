<?php

namespace App\Models;

use App\Services\KolRegistrationService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KolRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_number',
        'full_name',
        'email',
        'password',
        'phone',
        'city',
        'niches',
        'social_media',
        'expected_rate',
        'join_reason',
        'status',
        'reviewed_by',
        'reviewed_at',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'niches' => 'array',
            'social_media' => 'array',
            'reviewed_at' => 'datetime',
            'approved_at' => 'datetime',
        ];
    }

    public function files(): HasMany
    {
        return $this->hasMany(RegistrationFile::class, 'registration_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get normalized social media accounts list.
     *
     * @return array<int, array{platform: string, username: string, profile_url: ?string, followers_count: int}>
     */
    public function getNormalizedSocialMediaAttribute(): array
    {
        return app(KolRegistrationService::class)->normalizeSocialMedia($this->social_media);
    }

    /**
     * Get formatted label of all platforms.
     */
    public function getPlatformsLabelAttribute(): string
    {
        $accounts = $this->normalized_social_media;
        if (empty($accounts)) {
            return '-';
        }

        return collect($accounts)->pluck('platform')->map(fn ($p) => str($p)->title())->join(', ');
    }

    /**
     * Generate registration number in format: REG-YYYYMMDD-XXXX
     */
    public static function generateRegistrationNumber(): string
    {
        $today = Carbon::now()->format('Ymd');
        $prefix = "REG-{$today}-";

        $lastRegistration = self::where('registration_number', 'like', "{$prefix}%")
            ->orderBy('id', 'desc')
            ->first();

        if ($lastRegistration) {
            $lastSequence = (int) substr($lastRegistration->registration_number, -4);
            $sequence = str_pad($lastSequence + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $sequence = '0001';
        }

        return $prefix.$sequence;
    }
}
