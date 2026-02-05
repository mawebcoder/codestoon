<?php

declare(strict_types=1);

namespace App\Helpers;

use Filament\Support\Colors\Color;

class FilamentHelper
{
    public static function getEnabledColor(int $state): array
    {
        return match ($state) {
            1 => Color::Green,
            0 => Color::Red
        };
    }

    public static function getEnabledLabel(int $state): string
    {
        return match ($state) {
            1 => 'Enabled',
            0 => 'Disabled'
        };
    }
}
