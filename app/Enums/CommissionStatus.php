<?php

namespace App\Enums;

enum CommissionStatus: string
{
    case Pending = 'pending';
    case PendingReview = 'pending_review';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case Disbursed = 'dicairkan';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::PendingReview => 'Menunggu Review',
            self::Approved => 'Disetujui',
            self::Rejected => 'Ditolak',
            self::Disbursed => 'Dicairkan',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Pending => 'bg-amber-50 text-amber-700 border-amber-200',
            self::PendingReview => 'bg-blue-50 text-blue-700 border-blue-200',
            self::Approved => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            self::Rejected => 'bg-rose-50 text-rose-700 border-rose-200',
            self::Disbursed => 'bg-purple-50 text-purple-700 border-purple-200',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
