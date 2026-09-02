<?php

namespace App\Models;

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
     * Calculate commission amounts based on Business Rule 1.
     */
    public static function calculateCommission(Endorsement $endorsement, ?float $overridePct = null, ?string $overrideReason = null): self
    {
        $kol = $endorsement->kolProfile;
        $fee = (float) $endorsement->fee;

        if (!is_null($overridePct)) {
            $pct = $overridePct;
            $isOverride = true;
        } else {
            $pct = $kol->effective_commission_pct;
            $isOverride = !is_null($kol->commission_override_pct);
        }

        $commissionAmount = $fee * ($pct / 100);
        $agencyAmount = $fee - $commissionAmount;

        return new self([
            'endorsement_id' => $endorsement->id,
            'kol_profile_id' => $kol->id,
            'endorsement_fee' => $fee,
            'commission_pct' => $pct,
            'commission_amount' => $commissionAmount,
            'agency_amount' => $agencyAmount,
            'is_override' => $isOverride,
            'override_reason' => $overrideReason ?? $kol->status_reason,
            'status' => 'pending',
        ]);
    }
}
