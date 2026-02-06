<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum TutorialStatusEnum: int implements HasColor, HasLabel
{
    case DRAFT = 1;
    case PUBLISHED = 2;
    case ARCHIVED = 3;

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::DRAFT => Color::Yellow,
            self::PUBLISHED => Color::Green,
            self::ARCHIVED => Color::Purple,
        };
    }

    public function getLabel(): string|Htmlable|null
    {
        return $this->name;
    }
}
