<?php

namespace App\Enums;

enum RegistrationStatus: string
{
    case Pending = 'pending';
    case PendingReview = 'pending_review';
    case Approved = 'approved';
    case Rejected = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Menunggu',
            self::PendingReview => 'Dalam Peninjauan',
            self::Approved => 'Disetujui',
            self::Rejected => 'Ditolak',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
