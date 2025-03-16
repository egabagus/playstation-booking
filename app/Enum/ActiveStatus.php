<?php

namespace App\Enum;

enum ActiveStatus: int
{
    case ACTIVE = 1;
    case NONACTIVE = 0;

    public static function labels(): array
    {
        return [
            self::ACTIVE->value => 'Active',
            self::NONACTIVE->value => 'Non Active',
        ];
    }

    public function label(): string
    {
        return match ($this) {
            ActiveStatus::ACTIVE => 'Active',
            ActiveStatus::NONACTIVE => 'Non Active',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::ACTIVE => 'success',
            self::NONACTIVE => 'warning',
        };
    }
}
