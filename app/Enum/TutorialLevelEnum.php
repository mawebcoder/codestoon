<?php

namespace App\Enum;

use Filament\Support\Colors\Color;

enum TutorialLevelEnum: int
{
    case BEGINNER = 1;
    case MEDIUM = 2;
    case ADVANCED = 3;


    public function getName():string
    {
        return match ($this) {
            self::BEGINNER => 'Beginner',
            self::ADVANCED => 'Advanced',
            self::MEDIUM => 'Medium',
        };
    }

    public function getTableColor():array
    {
        return match ($this) {
            self::BEGINNER => Color::Red,
            self::ADVANCED => Color::Green,
            self::MEDIUM => Color::Blue,
        };
    }

}
