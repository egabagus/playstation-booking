<?php

namespace App\Enum;

enum PaymentStatus: int
{
    case PENDING = 0;
    case SUCCESS = 1;
    case ERROR = 2;

    public static function labels(): array
    {
        return [
            self::PENDING->value => 'Pending',
            self::SUCCESS->value => 'Success',
            self::ERROR->value => 'Error',
        ];
    }

    public function label(): string
    {
        return match ($this) {
            PaymentStatus::PENDING => 'Pending',
            PaymentStatus::SUCCESS => 'Success',
            PaymentStatus::ERROR => 'Error',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::SUCCESS => 'success',
            self::ERROR => 'danger',
        };
    }
}
