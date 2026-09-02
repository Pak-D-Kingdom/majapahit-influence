<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KolSocialMedia extends Model
{
    use HasFactory;

    protected $table = 'kol_social_media';

    protected $fillable = [
        'kol_profile_id',
        'platform',
        'username',
        'profile_url',
        'followers_count',
        'engagement_rate',
    ];

    protected function casts(): array
    {
        return [
            'followers_count' => 'integer',
            'engagement_rate' => 'decimal:2',
        ];
    }

    public function kolProfile(): BelongsTo
    {
        return $this->belongsTo(KolProfile::class);
    }
}
