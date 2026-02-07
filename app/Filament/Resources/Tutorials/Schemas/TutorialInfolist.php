<?php

declare(strict_types=1);

namespace App\Filament\Resources\Tutorials\Schemas;

use App\Filament\Resources\Categories\CategoryResource;
use App\Filament\Resources\Users\UserResource;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TutorialInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Grid::make(6)
                    ->columnSpanFull()
                    ->schema([
                        Section::make()
                            ->columns()
                            ->columnSpan(4)
                            ->schema([
                                Fieldset::make('Basic Information')
                                    ->columnSpanFull()
                                    ->schema([
                                        TextEntry::make('title'),
                                        TextEntry::make('slug'),
                                        TextEntry::make('description')
                                            ->html(),
                                        TextEntry::make('description_short'),
                                        TextEntry::make('time')->placeholder('-'),
                                        TextEntry::make('status')
                                            ->badge(),
                                        TextEntry::make('created_at')
                                            ->dateTime()
                                            ->placeholder('-'),
                                        TextEntry::make('updated_at')
                                            ->dateTime()
                                            ->placeholder('-'),
                                    ]),
                            ]),
                        Section::make()
                            ->columnSpan(2)
                            ->schema([
                                Fieldset::make('Associations')
                                    ->columnSpanFull()
                                    ->schema([
                                        TextEntry::make('category.name')->columnSpanFull()
                                            ->badge()
                                            ->url(
                                                fn ($record) => CategoryResource::getUrl(
                                                    'view',
                                                    ['record' => $record->category->id],
                                                ),
                                            )
                                            ->openUrlInNewTab(),
                                        TextEntry::make('teacher.email')
                                            ->columnSpanFull()
                                            ->url(
                                                fn ($record) => UserResource::getUrl(
                                                    'view',
                                                    ['record' => $record->teacher->id],
                                                ),
                                            )
                                            ->openUrlInNewTab(),
                                    ]),
                                Fieldset::make('Pricing')
                                    ->columnSpanFull()
                                    ->relationship('price')
                                    ->schema([
                                        TextEntry::make('price'),
                                        TextEntry::make('compare_at_price'),
                                    ]),

                            ]),
                    ]),

            ]);
    }
}
