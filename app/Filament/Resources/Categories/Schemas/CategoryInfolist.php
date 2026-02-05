<?php

declare(strict_types=1);

namespace App\Filament\Resources\Categories\Schemas;

use App\Models\Category;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;

class CategoryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make(Category::COLUMN_NAME),
                TextEntry::make(Category::COLUMN_SLUG),
                TextEntry::make(Category::COLUMN_DESCRIPTION),
                IconEntry::make(Category::COLUMN_ENABLED)
                    ->icon(function ($state) {
                        return match ($state) {
                            1 => Heroicon::CheckCircle,
                            0 => Heroicon::XCircle
                        };
                    })->color(function ($state) {
                        return match ($state) {
                            1 => Color::Green,
                            0 => Color::Red,
                        };
                    }),

                SpatieMediaLibraryImageEntry::make('image')
                    ->label('Media'),

            ]);
    }
}
