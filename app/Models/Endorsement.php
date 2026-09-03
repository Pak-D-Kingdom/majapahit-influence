<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Endorsement extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'kol_profile_id',
        'content_type',
        'fee',
        'deadline',
        'start_date',
        'status',
        'assigned_by',
        'completed_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'fee' => 'decimal:2',
            'deadline' => 'date',
            'start_date' => 'date',
            'completed_at' => 'datetime',
        ];
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function kolProfile(): BelongsTo
    {
        return $this->belongsTo(KolProfile::class);
    }

    public function assigner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function contentProofs(): HasMany
    {
        return $this->hasMany(ContentProof::class);
    }

    public function latestContentProof(): HasOne
    {
        return $this->hasOne(ContentProof::class)->latestOfMany();
    }

    public function commission(): HasOne
    {
        return $this->hasOne(Commission::class);
    }
}
