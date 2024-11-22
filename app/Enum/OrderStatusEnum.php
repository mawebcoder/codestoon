<?php

namespace App\Enum;

use Filament\Support\Colors\Color;

enum OrderStatusEnum: int
{
    case PENDING = 1;
    case PAID = 2;
    case CANCELED = 3;

    public function getLabel(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::PAID => 'Paid',
            self::CANCELED => 'Canceled',
        };
    }

    public function getColor(): array
    {
        return match ($this) {
            self::PENDING => Color::Yellow,
            self::PAID => Color::Green,
            self::CANCELED => Color::Red,
        };
    }
}
