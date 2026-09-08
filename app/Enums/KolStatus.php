<?php

namespace App\Enums;

enum KolStatus: string
{
    case Active = 'aktif';
    case Inactive = 'nonaktif';
    case Suspended = 'suspend';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Aktif',
            self::Inactive => 'Nonaktif',
            self::Suspended => 'Ditangguhkan',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
