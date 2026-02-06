<?php

declare(strict_types=1);

namespace App\Filament\Resources\Videos\Schemas;

use App\Models\Tutorial;
use App\Models\Video;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;

class VideoForm
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
                                Section::make('Basic Information')
                                    ->columnSpanFull()
                                    ->columns()
                                    ->schema([
                                        TextInput::make('title')
                                            ->live()
                                            ->afterStateUpdated(
                                                fn (?string $state, Set $set) => $set('slug', Str::slug($state)),
                                            )
                                            ->required(),
                                        TextInput::make('slug')
                                            ->required(),
                                        RichEditor::make('description')
                                            ->resizableImages()
                                            ->required()
                                            ->columnSpanFull(),
                                        Toggle::make('enabled')
                                            ->onColor('success')
                                            ->offColor('danger')
                                            ->required(),
                                    ]),
                                Section::make('Timing')
                                    ->icon(Heroicon::Clock)
                                    ->iconColor(Color::Blue)
                                    ->columnSpan(2)
                                    ->columns()
                                    ->schema([
                                        TextInput::make('time_in_hours')
                                            ->required()
                                            ->numeric()
                                            ->default(0),
                                        TextInput::make('time_in_minutes')
                                            ->required()
                                            ->numeric()
                                            ->default(0),
                                        TextInput::make('time_in_seconds')
                                            ->required()
                                            ->numeric()
                                            ->default(0),
                                    ]),
                                Section::make('Media')
                                    ->icon(Heroicon::VideoCamera)
                                    ->iconColor(Color::Orange)
                                    ->columnSpanFull()
                                    ->schema([
                                        SpatieMediaLibraryFileUpload::make('video')
                                            ->required()
                                            ->acceptedFileTypes(['video/mp4']),
                                    ]),

                            ]),
                        Fieldset::make('Associations')
                            ->columnSpan(2)
                            ->schema([
                                Select::make(Video::COLUMN_TUTORIAL_ID)
                                    ->label('Tutorial')
                                    ->options(function ($record, $operation) {
                                        $options = Tutorial::query()
                                            ->take(10)
                                            ->pluck(Tutorial::COLUMN_TITLE, Tutorial::COLUMN_ID);

                                        if ($operation === 'edit') {
                                            $options->put(
                                                $record->tutorial->id,
                                                $record->tutorial->{Tutorial::COLUMN_TITLE},
                                            );
                                        }

                                        return $options;
                                    })
                                    ->getSearchResultsUsing(function (?string $search) {
                                        return Tutorial::query()
                                            ->take(10)
                                            ->where(Tutorial::COLUMN_TITLE, 'like', "%{$search}%")
                                            ->pluck(Tutorial::COLUMN_TITLE, Tutorial::COLUMN_ID);
                                    })
                                    ->columnSpanFull()
                                    ->required(),
                            ]),
                    ]),

            ]);
    }
}
