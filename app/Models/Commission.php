<?php

namespace App\Models;

use App\Enums\CommissionStatus;
use App\Services\CommissionService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Commission extends Model
{
    use HasFactory;

    protected $fillable = [
        'endorsement_id',
        'kol_profile_id',
        'endorsement_fee',
        'commission_pct',
        'commission_amount',
        'agency_amount',
        'is_override',
        'override_reason',
        'status',
        'disbursed_at',
        'disbursement_proof_path',
    ];

    protected function casts(): array
    {
        return [
            'endorsement_fee' => 'decimal:2',
            'commission_pct' => 'decimal:2',
            'commission_amount' => 'decimal:2',
            'agency_amount' => 'decimal:2',
            'is_override' => 'boolean',
            'disbursed_at' => 'date',
        ];
    }

    public function statusEnum(): CommissionStatus
    {
        return CommissionStatus::tryFrom($this->status) ?? CommissionStatus::Pending;
    }

    public function endorsement(): BelongsTo
    {
        return $this->belongsTo(Endorsement::class);
    }

    public function kolProfile(): BelongsTo
    {
        return $this->belongsTo(KolProfile::class);
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(CommissionApproval::class);
    }

    /**
     * Calculate commission amounts based on Business Rule 1 (delegates to CommissionService).
     */
    public static function calculateCommission(Endorsement $endorsement, ?float $overridePct = null, ?string $overrideReason = null): self
    {
        return app(CommissionService::class)->calculateAndCreate($endorsement, $overridePct, $overrideReason);
    }
}
