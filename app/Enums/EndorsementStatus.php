<?php

namespace App\Enums;

enum EndorsementStatus: string
{
    case Assigned = 'assigned';
    case InProgress = 'in_progress';
    case ContentSubmitted = 'content_submitted';
    case ContentApproved = 'content_approved';
    case ContentRejected = 'content_rejected';
    case Completed = 'selesai';
    case Cancelled = 'dibatalkan';

    public function label(): string
    {
        return match ($this) {
            self::Assigned => 'Ditugaskan',
            self::InProgress => 'Sedang Berjalan',
            self::ContentSubmitted => 'Konten Diserahkan',
            self::ContentApproved => 'Konten Disetujui',
            self::ContentRejected => 'Konten Ditolak / Perlu Revisi',
            self::Completed => 'Selesai',
            self::Cancelled => 'Dibatalkan',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
