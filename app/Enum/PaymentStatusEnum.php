<?php

namespace App\Enum;

use Filament\Support\Colors\Color;

enum PaymentStatusEnum: int
{
    case APPROVED = 1;
    case CANCELED = 2;
    case PENDING = 3;

    public function getLabel(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::APPROVED => 'Approved',
            self::CANCELED => 'Canceled',
        };
    }

    public function getColor(): array
    {
        return match ($this) {
            self::PENDING => Color::Yellow,
            self::APPROVED => Color::Green,
            self::CANCELED => Color::Red,
        };
    }
}
