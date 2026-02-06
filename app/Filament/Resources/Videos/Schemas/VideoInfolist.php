<?php

declare(strict_types=1);

namespace App\Filament\Resources\Videos\Schemas;

use App\Models\Video;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Schemas\Schema;

class VideoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title'),
                IconEntry::make('enabled')
                    ->boolean(),
                TextEntry::make('description')
                    ->html()
                    ->columnSpanFull(),
                TextEntry::make('slug'),
                TextEntry::make('time_in_hours')
                    ->numeric(),
                TextEntry::make('time_in_minutes')
                    ->numeric(),
                TextEntry::make('time_in_seconds')
                    ->numeric(),
                TextEntry::make('tutorial.title')
                    ->numeric(),
                ViewEntry::make('video')
                    ->view('Filament.Components.video')
                    ->columnSpanFull()
                    ->label('Video')
                    ->state(function (Video $record) {
                        return $record->media()->first()->getUrl();
                    }),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
