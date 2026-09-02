<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'min_followers',
        'max_followers',
        'commission_pct',
        'agency_pct',
    ];

    protected function casts(): array
    {
        return [
            'min_followers' => 'integer',
            'max_followers' => 'integer',
            'commission_pct' => 'decimal:2',
            'agency_pct' => 'decimal:2',
        ];
    }

    public function kolProfiles(): HasMany
    {
        return $this->hasMany(KolProfile::class);
    }
}
